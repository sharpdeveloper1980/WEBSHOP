<?php

namespace App\Jobs;

use App\Category;
use App\Client;
use App\Product;
use App\Server;
use GuzzleHttp\Client as GuzzleClient;
use App\Store;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DataSync implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $timeout = 400;
    
    private $since;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($since = '')
    {
        $this->since = $since;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $servers = Server::where('is_sync_enabled', 1)->get();

        if(!$servers->isEmpty()) {
            $this->syncCategories($servers[0]);
            foreach ($servers as $server) {
                $this->syncStores($server);
            }
            dispatch(new RefreshCategories())->onQueue('cache');
        }

    }

    public function syncStores($server)
    {
        $stores = $this->sendRequest($server, 'GetStoreData');

        if($stores['status'] === 'OK' && !empty($stores['company_listing'][$server->company])) {
            $store = $stores['company_listing'][$server->company];
            $properties = $store['properties'];
            unset($store['properties']);

            $properties['server_id'] = $server->server_id;
            $properties['company_visible'] = filter_var($properties['company_visible'], FILTER_VALIDATE_BOOLEAN);
            $properties['allow_guest_spectate'] = filter_var($properties['allow_guest_spectate'], FILTER_VALIDATE_BOOLEAN);
            $properties['allow_client_registration'] = filter_var($properties['allow_client_registration'], FILTER_VALIDATE_BOOLEAN);
            $properties['allow_client_login'] = filter_var($properties['allow_client_login'], FILTER_VALIDATE_BOOLEAN);
            $properties['allow_client_reservation'] = filter_var($properties['allow_client_reservation'], FILTER_VALIDATE_BOOLEAN);
            $properties['allow_client_product_marketing'] = filter_var($properties['allow_client_product_marketing'], FILTER_VALIDATE_BOOLEAN);
            $properties['product_pricing_enabled'] = filter_var($properties['product_pricing_enabled'], FILTER_VALIDATE_BOOLEAN);
            $properties['sales_view_enabled'] = filter_var($properties['sales_view_enabled'], FILTER_VALIDATE_BOOLEAN);
            $properties['allow_webshop_product_pricing'] = filter_var($properties['allow_webshop_product_pricing'], FILTER_VALIDATE_BOOLEAN);
            $properties['use_the_term_product_feed_instead_of_webshop'] = filter_var($properties['use_the_term_product_feed_instead_of_webshop'], FILTER_VALIDATE_BOOLEAN);
            $properties['product_pricing_1_by_1'] = filter_var($properties['product_pricing_1_by_1'], FILTER_VALIDATE_BOOLEAN);
            $properties['mobile_app_show_product_recognition_button'] = filter_var($properties['mobile_app_show_product_recognition_button'], FILTER_VALIDATE_BOOLEAN);

            $store_db_record = Store::updateOrCreate(
                [
                    'technical_name' => $store['technical_name'],
                    'server_id' => $server->server_id
                ],
                array_merge($store, $properties)
            );
         
            $this->syncSellers($server, $store_db_record->id);
            $this->syncProducts($server, $store_db_record->id);
        }
    }
    
    public function syncProducts($server, $store_id)
    {
        $products = $this->sendRequest($server, 'SyncProducts');

        if($products['status'] === 'OK' && $products['number_of_results'] > 0) {
            $synced_products = [];

            foreach ($products['data'] as $product) {
                $record = Product::updateOrCreate(
                    [
                        'product_id' => $product['product_id'],
                        'server_id' => $server->server_id
                    ],
                    array_merge($product, [
                        'server_id' => $server->server_id,
                        'webshop_store_id' => $store_id
                    ])
                );
                $synced_products[] = $record;

                $record->productCategories()->detach();
                foreach ($record->categories as $category) {
                    $record->productCategories()->attach($category);
                }
            }

            $this->mapProductsToClients($synced_products);
        }
    }

    public function syncSellers($server, $store_id)
    {
        $sellers = $this->sendRequest($server, 'SyncSellers');

        if($sellers['status'] === 'OK' && $sellers['number_of_results'] > 0) {
            $clients = [];
            foreach ($sellers['data'] as $seller) {
                if(empty($seller['password'])) $seller['password'] = Hash::make(Str::random(10));
                // TODO: finish logic to determine unique user
                $user = User::updateOrCreate(
                    [
                        'server_id' => $server->server_id,
                        'user_id' => $seller['user_id']
                    ],
                    array_merge($seller, [
                        'name' => $seller['fullname'],
                        'server_id' => $server->server_id
                    ])
                );
                $client = $seller;
                unset($client['fullname']);
                unset($client['firstname']);
                unset($client['lastname']);
                $clients[] = Client::updateOrCreate(
                    [
                        'webshop_store_id' => $store_id,
                        'user_id' => $client['user_id']
                    ],
                    array_merge($client, [
                        'server_id' => $server->server_id,
                        'webshop_store_id' => $store_id,
                        'webshop_user_id' => $user->id
                    ])
                );
            }
        }
    }

    public function syncCategories($server)
    {
        $categories = $this->sendRequest($server, 'SyncCategories');

        if($categories['status'] === 'OK' && !empty($categories['categories'])) {
            foreach ($categories['categories'] as $category) {
                Category::updateOrCreate(
                    ['category_uid' => $category['category_uid']],
                    $category
                );
            }
        }
    }

    public function mapProductsToClients($products = null)
    {
        if($products) {
            foreach ($products as $product) {
                $client = Client::where(['client_number' => $product->client_number, 'server_id' => $product->server_id])->first();
                $client_products = Product::where(['client_number' => $client->client_number, 'server_id' => $client->server_id])->pluck('id');
                
                $client->products()->sync($client_products);
            }
        }
    }
    
    public function sendRequest($server, $request)
    {
        $query = [
            'company' => $server->company,
            'authcode' => $server->authcode,
            'request' => $request,
        ];
        if(!empty($this->since)) {
            $query['since'] = $this->since;
        }
        $client = new GuzzleClient();
        $response = $client->get($server->api_url, [
            'query' => $query
        ]);
        
        return json_decode($response->getBody(), true);
    }
}

<?php

namespace App\Http\Controllers;

use App\Category;
use App\Client;
use App\Product;
use App\Server;
use App\Services\WebshopClient;
use App\Store;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DataSyncController extends Controller
{
    private $client;
    private $api_endpoint;

    public function __construct(WebshopClient $client)
    {
        $this->client = $client;
        $this->api_endpoint = env('API_ENDPOINT');
    }
    
    public function init()
    {
        $servers = Server::where('is_sync_enabled', 1)->get();

        if(!$servers->isEmpty()) {
            foreach ($servers as $server) {
                $this->getStores($server);
            }
        }
    }

    public function getStores($server)
    {
        $query = [
            'company' => $server->company,
            'authcode' => $server->authcode,
            'request' => 'GetStoreData',
        ];
        $response = $this->client->get($server->api_url, [
            'query' => $query
        ]);
//        $i = 0; // Temporary
        
        $stores = json_decode($response->getBody(), true);

        if($stores['status'] === 'OK' && !empty($stores['company_listing'][$server->company])) {
//            foreach ($stores['company_listing'] as $store) {
//                if(!empty($store['technical_name']) && $store['technical_name'] === $server->company) {
                    $store = $stores['company_listing'][$server->company];
                    $properties = $store['properties'];
                    unset($store['properties']);
                    
//                    $server = Server::where('company', $store['technical_name'])->orWhere('company', 'pos'.$store['technical_name'])->first();
//                    $server = Server::where('company', 'webshop2demo')->first();
//                    if($server && $server->is_sync_enabled) {
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
//                        if($i == 0) { // Temporary
                            $this->syncProducts($server, $store_db_record->id);
                            $this->syncSellers($server, $store_db_record->id);
//                            $i++;
//                        }
//                    }
//                }
//            }
            
            $this->syncCategories();
        } else {
            return $stores;
        }
        
    }
    

    public function syncProducts($server, $store_id)
    {
        $query = [
            'company' => $server->company,
            'authcode' => $server->authcode,
            'request' => 'SyncProducts',
            'since' => '2019-01-01%2000:00:00'
        ];
        
        $response = $this->client->get($server->api_url, [
            'query' => $query
        ]);
        $products = json_decode($response->getBody(), true);
        
        if($products['status'] === 'OK' && $products['number_of_results'] > 0) {
            foreach ($products['data'] as $product) {
                $record = Product::updateOrCreate(
                    [
                        'product_id' => $product['product_id'],
                        'server_id' => $server->server_id
                    ],
                    array_merge($product, [
                        'server_id' => $server->server_id,
                        'webshop_store_id' => $store_id,
                        'description' => json_encode($product['description'])
                    ])
                );
                
                $record->productCategories()->detach();
                foreach ($record->categories as $category) {
                    $record->productCategories()->attach($category);
                }
            }
        }
    }
    
    public function syncSellers($server, $store_id)
    {
        $query = [
            'company' => $server->company,
            'authcode' => $server->authcode,
            'request' => 'SyncSellers',
            'since' => '2019-01-01%2000:00:00'
        ];
        $response = $this->client->get($server->api_url, [
            'query' => $query
        ]);
        $sellers = json_decode($response->getBody(), true);
        
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
                        'webshop_user_id' => $user->id,
                        'seller_description' => json_encode($client['seller_description'])
                    ])
                );
            }

            $this->mapProductsToClients($clients);
        }
    }
   
    public function syncCategories()
    {
        $query = [
            'company' => 'webshop2demo',
            'authcode' => env('API_AUTHCODE'),
            'request' => 'SyncCategories',
        ];
        $response = $this->client->get($this->api_endpoint, [
            'query' => $query
        ]);
        $categories = json_decode($response->getBody(), true);

        if($categories['status'] === 'OK' && !empty($categories['categories'])) {
            foreach ($categories['categories'] as $category) {
                Category::updateOrCreate(
                    ['category_uid' => $category['category_uid']],
                    $category
                );
            }
        }
    }
    
    public function mapProductsToClients($clients = null)
    {
        if(!$clients) {
            $clients = Client::all();
        }

        foreach ($clients as $client) {
            $products = Product::where(['client_number' => $client->client_number, 'server_id' => $client->server_id])->get();

            $client->products()->detach();
            foreach ($products as $product) {
                $client->products()->attach($product);
            }
        }
    }
}

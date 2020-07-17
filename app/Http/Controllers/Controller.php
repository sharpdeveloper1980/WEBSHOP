<?php

namespace App\Http\Controllers;

use App\Domain;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\App;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function __construct() {
        $this->request_domain = config('request_domain');
        $this->store_ids = [];
        $this->hero_img_url = '';
        $this->hero_img_link = '';
        $this->logo_img_url = '';
        $this->language = 'en';
        $this->commercial_html = '';

        $domains_settings = Domain::all()->keyBy('domain')->toArray();
        print_r($domains_settings);die;
        if (array_key_exists($this->request_domain, $domains_settings)) {
            App::setLocale($domains_settings[$this->request_domain]['language']);
            $this->language = !empty($domains_settings[$this->request_domain]['language']) ? $domains_settings[$this->request_domain]['language'] : 'en';
            $this->store_ids = $domains_settings[$this->request_domain]['store_ids'];
            $this->hero_img_url = $domains_settings[$this->request_domain]['hero_img_url'];
            $this->hero_img_link = $domains_settings[$this->request_domain]['hero_img_link'];
            $this->logo_img_url = $domains_settings[$this->request_domain]['logo_img_url'];
            $this->commercial_html = $domains_settings[$this->request_domain]['commercial_html'];
        }
        $this->display_store = true;
        if(count($this->store_ids) === 1) $this->display_store = false;
        config(['store_ids' => $this->store_ids, 'display_store' => $this->display_store]);
    }

    public function paginateCollection($collection, $perPage, $total = null, $page = null, $pageName = 'page' )
    {
        $page = $page ?: LengthAwarePaginator::resolveCurrentPage( $pageName );

        return new LengthAwarePaginator( $collection->slice(($page-1)*$perPage, $perPage)->values(), $total ?: $collection->count(), $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
            'pageName' => $pageName,
        ]);
    }
}

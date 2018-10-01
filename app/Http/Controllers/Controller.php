<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     *
     *  뷰 생성
     * @param $page
     * @param string $menu
     * @param string $sub
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createView($page, $menu = "", $sub = "") {
        $view = view($page);
        $view->navMenu = $menu;
        $view->navSubMenu = $sub;
        return $view;
    }
}

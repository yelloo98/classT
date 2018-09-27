<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class MainController extends Controller
{

    public function main(){
        return view('admin.main');
    }
}
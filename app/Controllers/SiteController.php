<?php

namespace App\Controllers;

class SiteController extends BaseController
{

    public function accueil() :string
    {
        $data = array();
        $data['PAGE_CONTENT'] = 'accueil.php';

        return view('layout', $data);
    }
}
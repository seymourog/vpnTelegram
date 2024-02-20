<?php

namespace App\Http\Controllers;

use App\Service\ApiWiregouard\ApiService;
use Illuminate\Http\Request;

class VPNController extends Controller
{
    public function auth()
    {
        return ApiService::Auth();
    }

    public function getClients()
    {
        return ApiService::getClients();
    }

    public function createClient(string $name)
    {
        return ApiService::createClient($name);
    }

    public function getQrCode(string $name)
    {
        return ApiService::getQrCode($name);
    }
    public function getFile(string $name)
    {
        $fileName = ApiService::getFile($name);

        return response()->download(public_path($fileName));
    }
    public function disableClient(string $name)
    {
        return ApiService::disableClient($name);
    }
    public function enableClient(string $name)
    {
        return ApiService::enableClient($name);
    }
    public function deleteClient(string $name)
    {
        return ApiService::deleteClient($name);
    }
}

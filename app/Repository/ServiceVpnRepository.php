<?php

namespace App\Repository;

use App\Models\ServiceVpn;

class ServiceVpnRepository
{

    public static function Create(string $name, array $body): ServiceVpn|null
    {
        return ServiceVpn::create([
            'name' => $name,
            'address' => (string)$body['address'],
            'privateKey' => (string)$body['privateKey'],
            'publicKey' => (string)$body['publicKey'],
            'preSharedKey' => (string)$body['preSharedKey'],
            'enabled' => true
        ]);
    }

    public static function GetByName(string $name): ServiceVpn|null
    {
        return ServiceVpn::where('name', $name)->get()->first();
    }
}

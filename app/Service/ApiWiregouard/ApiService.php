<?php

namespace App\Service\ApiWiregouard;

use App\Models\ServiceVpn;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

class ApiService
{
    private static $cookieJar;

    public static function Auth()
    {
        self::$cookieJar = new CookieJar();

        $client = new Client();

        $response = $client->request('POST', env('API_URL').'/api/session', [
            'json' => [
                'password' => env('PASSWORD_API',''),
            ],
            'cookies' => self::$cookieJar

        ]);

        $statusCode = $response->getStatusCode();
        $body = $response->getBody()->getContents();
        $head = $response->getHeaders();

        return ['statusCode' => $statusCode, 'body' => $body, 'head'=>$head];
    }

    public static function getClients()
    {
        self::Auth();
        $client = new Client();
        $response = $client->request('GET', env('API_URL').'/api/wireguard/client',[
            'cookies' => self::$cookieJar
        ]);

        $statusCode = $response->getStatusCode();
        $body = $response->getBody()->getContents();

        return ['statusCode' => $statusCode, 'body' => $body];
    }

    public  static function createClient($name)
    {
        $serviceDb = ServiceVpn::where('name', $name)->get()->first();
        if($serviceDb !== null){
            return ['statusCode' => 400, 'body' => 'Client already exists'];
        }
        self::Auth();
        $client = new Client();
        $response = $client->request('POST', env('API_URL').'/api/wireguard/client', [
            'cookies' => self::$cookieJar,
            'json' => [
                'name' => $name
            ]
        ]);
        $statusCode = $response->getStatusCode();
        $body = json_decode($response->getBody()->getContents(),true);
        ServiceVpn::create([
            'name' => $name,
            'address' => (string)$body['address'],
            'privateKey' => (string)$body['privateKey'],
            'publicKey' => (string)$body['publicKey'],
            'preSharedKey' => (string)$body['preSharedKey'],
            'enabled' => true
        ]);
        return ['statusCode' => $statusCode, 'body' => $body];
    }

    public static function getIdByName($name)
    {
        $clients = self::getClients();
        $body = $clients['body'];
        $clientsArray = json_decode($body,true);
        $id = null;
        foreach ($clientsArray as $item) {
            if($item['name'] === $name){
                $id = $item['id'];
            }
        }
        return $id;
    }
    public static function getQrCode($name)
    {
        $id = self::getIdByName($name);
        $client = new Client();
        $response = $client->request('GET', '/api/wireguard/client/'.$id.'/qrcode.svg', [
            'cookies' => self::$cookieJar
        ]);
        $body = $response->getBody()->getContents();

        return $body;
    }

    public static function getFile($name)
    {
        $id = self::getIdByName($name);
        $client = new Client();
        $response = $client->request('GET',env('API_URL').'/api/wireguard/client/'.$id.'/configuration', [
            'cookies' => self::$cookieJar
        ]);
        $body = $response->getBody()->getContents();
        $fileName = $name . '.conf';
        file_put_contents($fileName, $body);
        return $fileName;
    }

    public static function disableClient($name)
    {
        $id = self::getIdByName($name);
        $client = new Client();
        $response = $client->request('POST',env('API_URL').'/api/wireguard/client/'.$id.'/disable', [
            'cookies' => self::$cookieJar
        ]);
        $body = $response->getBody()->getContents();
        return $body;

    }
    public static function enableClient($name)
    {
        $id = self::getIdByName($name);
        $client = new Client();
        $response = $client->request('POST',env('API_URL').'/api/wireguard/client/'.$id.'/enable', [
            'cookies' => self::$cookieJar
        ]);
        $body = $response->getBody()->getContents();
        return $body;

    }

    public static function deleteClient($name)
    {
        $id = self::getIdByName($name);
        $client = new Client();
        $response = $client->request('DELETE',env('API_URL').'/api/wireguard/client/'. $id, [
            'cookies' => self::$cookieJar
        ]);
        $body = $response->getBody()->getContents();
        return $body;
    }
}

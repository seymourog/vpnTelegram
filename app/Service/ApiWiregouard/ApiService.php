<?php

namespace App\Service\ApiWiregouard;

use App\Models\ServiceVpn;
use App\Repository\ServiceVpnRepository;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

class ApiService
{
    private static $cookieJar;

    public static function Auth(): array|object
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

    public static function getClients(): array
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

    public  static function createClient(string $name): array
    {
        $serviceDb = ServiceVpnRepository::GetByName($name);
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
        ServiceVpnRepository::Create($name,$body);
        return ['statusCode' => $statusCode, 'body' => $body];
    }

    public static function getIdByName(string $name): string|null
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

    public static function getFile(string $name): string
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

    public static function disableClient(string $name)
    {
        $id = self::getIdByName($name);
        $client = new Client();
        $response = $client->request('POST',env('API_URL').'/api/wireguard/client/'.$id.'/disable', [
            'cookies' => self::$cookieJar
        ]);
        $body = $response->getBody()->getContents();
        return $body;

    }
    public static function enableClient(string $name)
    {
        $id = self::getIdByName($name);
        $client = new Client();
        $response = $client->request('POST',env('API_URL').'/api/wireguard/client/'.$id.'/enable', [
            'cookies' => self::$cookieJar
        ]);
        $body = $response->getBody()->getContents();
        return $body;

    }

    public static function deleteClient(string $name)
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

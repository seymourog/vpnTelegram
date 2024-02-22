<?php

namespace App\Service\Telegram;

use App\Service\ApiWiregouard\ApiService;
use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;

class Handler extends WebhookHandler
{
    public function start(): void
    {
        $this->chat->message('*Добро пожаловать в нашего телеграм бота!*

Этот бот служит для получения доступов Wireguard')->keyboard(Keyboard::make()->buttons([
            Button::make('Инструкция')->action('manual'),
            Button::make('Получить доступ')->action('get')]))->send();

        $this->bot->registerCommands([
            'manual' => 'Инструкция',
            'get' => 'Оплатить сервер',
            'file' => 'Получить файл для WireGuard',
        ])->send();
    }


    public function get(): void
    {
        $name = $this->chat->chat_id;
        ApiService::createClient($name);
        $this->file();
        $this->reply('Ваше подключение успешно создано!');
    }

    public function file(): void
    {
        $name = $this->chat->chat_id;
        $file = ApiService::getFile($name);
        $this->chat->document($file)->send();
        $filePath = $_SERVER['DOCUMENT_ROOT'] . '/' . $name . '.conf';
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    public function manual(): void
    {
        $this->chat->message('Для того что бы активировать подключение скачайте и установите приложение WireGuard на ваше устройство. После установки приложения, нажмите на кнопку "Получить файл для wireguard" и установите файл на ваше устройство. После установки файла, откройте приложение WireGuard и нажмите на кнопку "Добавить туннель" и выберите файл, который вы установили. После этого вы сможете подключиться к нашему серверу.')->keyboard(Keyboard::make()->buttons([
            Button::make('Получить файл для WireGuard')->action('get')]))->send();
    }

}

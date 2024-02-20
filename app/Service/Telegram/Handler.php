<?php

namespace App\Service\Telegram;

use DefStudio\Telegraph\Handlers\WebhookHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends WebhookHandler
{
    protected function onFailure(\Throwable $throwable): void
    {
        if ($throwable instanceof NotFoundHttpException) {
            throw $throwable;
        }

        report($throwable);

        $this->reply('sorry man, I failed');
    }
    public function start(){
        $this->start();
        $this->reply('ky');
    }

    public function hello()
    {
        $this->reply('hello');
    }
}

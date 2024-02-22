<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Телеграмм бот, для выдачи Vpn доступов Wireguard пользователям

Для работы бота нужен wg-easy который будет крутиться у вас на сервере, который будет выполнять роль vpn
## Запуск Wireguard 
Для запуска Wireguard, вам нужно установить docker и docker-compose
```bash
curl -sSL https://get.docker.com | sh
sudo usermod -aG docker $(whoami)
exit
```

После установки докера, вы можете запустить wg-easy командой
```bash
docker run -d \
--name=wg-easy \
-e WG_HOST=IP_SERVER \
-e PASSWORD=PASSWORD \
-v ~/.wg-easy:/etc/wireguard \
-p 51820:51820/udp \
-p 51821:51821/tcp \
--cap-add=NET_ADMIN \
--cap-add=SYS_MODULE \
--sysctl="net.ipv4.conf.all.src_valid_mark=1" \
--sysctl="net.ipv4.ip_forward=1" \
--restart unless-stopped \
weejewel/wg-easy
```

## Установка

Регистрация ссл сертификата 

Исправьте файл init-letsencrypt.sh, введите свои данные в переменные
```bash
./init-letsencrypt.sh
```
После этого запустите docker-compose
```bash
docker-compose up -d --build
```

Далее установить все зависимости, выполните миграции, и установите права на папку storage 777
```bash
composer install
php artisan migrate
chmod 777 -R storage
```
## Использование
В .env укажите
- API_URL, который будет указывать на адресс где крутится wg-easy
- и PASSWORD_API, который будет использоваться для авторизации входа в wg-easy

Зарегистрируйте своего бота командой 
```bash 
php artisan telegraph:new-bot
```

Так же не забудьте установить вебхук, это можно сделать командой 
```bash
php artisan telegraph:set-webhook
```

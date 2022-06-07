# laravel-default

## ローカル環境URL

```
http://localhost:8080/
```

## appコンテナに入るコマンド

```
$ docker compose exec app bash
```

## MySQLコンテナへの接続方法

```
docker-compose exec db bash -c 'mysql -u${MYSQL_USER} -p${MYSQL_PASSWORD} ${MYSQL_DATABASE}'
```

## 環境構築手順

```

git clone https://github.com/TakumaTajikawa/laravel-default.git
cd laravel-default

docker compose up -d --build

Laravelインストール
$ docker compose exec app bash
$ composer install

$ npm install

envファイルコピー
$ cp .env.example .env

$ php artisan key:generate

$ chmod -R 777 storage bootstrap/cache

$ php artisan migrate


```

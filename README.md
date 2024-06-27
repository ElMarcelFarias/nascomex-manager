
# Setup Docker Para Instalar Nascomex - Manager (Back-end API)


### Passo a passo
Clone Repositório
```sh
git clone https://github.com/ElMarcelFarias/nascomex-manager
```
```sh
cd nascomex-manager/backend/api/nascomex-manager-api
```


Alterne para a branch master
```sh
git checkout master
```


Remova o versionamento (opcional)
```sh
rm -rf .git/
```


Crie o Arquivo .env
```sh
cp .env.example .env
```


Atualize as variáveis de ambiente do arquivo .env
```dosini
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:Rk5fxkqL7ErFdO3Es1ktQ278Jm3f2gdyHdcelhi5aHc=
APP_DEBUG=true
APP_URL=http://localhost:8989

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=nascomex_manager
DB_USERNAME=root
DB_PASSWORD=root

BROADCAST_DRIVER=log
CACHE_DRIVER=redis
FILESYSTEM_DISK=public
QUEUE_CONNECTION=sync
SESSION_DRIVER=redis
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

```

Caso utilizar Macbook com chip M1
Alterar docker-compose.yml
```
version: "3.7"

services:
    # image project
    app:
        build:
            context: .
            dockerfile: Dockerfile
        # image: especializati/laravel9-app
        restart: unless-stopped
        working_dir: /var/www/
        volumes:
            - ./:/var/www
        depends_on:
            - redis
        networks:
            - laravel-9

    # nginx
    nginx:
        image: nginx:alpine
        restart: unless-stopped
        ports: 
            - "8989:80"
        volumes: 
            - ./:/var/www
            - ./docker/nginx/:/etc/nginx/conf.d/
        networks: 
            - laravel-9

    # db mysql
    mysql:
        platform: linux/amd64
        image: mysql:8.0
        restart: unless-stopped
        environment:
            MYSQL_DATABASE: ${DB_DATABASE}
            MYSQL_ROOT_PASSWORD: ${DB_PASSWORD}
            MYSQL_PASSWORD: ${DB_PASSWORD}
        volumes:
            - ./.docker/mysql/dbdata:/var/lib/mysql
        ports:
            - "3399:3306"
        networks:
            - laravel-9

    # queue
    queue:
        image: especializati/laravel9-app
        restart: unless-stopped
        command: "php artisan queue:work"
        volumes:
            - ./:/var/www
        depends_on:
            - redis
            - app
        networks:
            - laravel-9

    # redis
    redis:
        image: redis:latest
        networks:
            - laravel-9

networks:
    laravel-9:
        driver: bridge

```



Suba os containers do projeto
```sh
docker-compose up -d
```


Acesse o container app com o bash
```sh
docker-compose exec app bash
```


Instale as dependências do projeto
```sh
composer install
```


Gere a key do projeto Laravel
```sh
php artisan key:generate
```


Acesse o projeto
http://localhost:8989

# Setup Nascomex - Manager (Front-end prod)

Instale as dependências do projeto
```sh
npm install
```

Iniciar o Front-end
```sh
npm run build
```

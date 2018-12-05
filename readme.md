## WalletConnect Bridge Laravel Implementation 

A full introduction is described in WalletConnect docs: [https://docs.walletconnect.org/technical-specification](https://docs.walletconnect.org/technical-specification)

### Requirements

If you want to host it yourself, you need

* PHP 7.1+ or newer
* HTTP server with PHP support (eg: Apache, Nginx, Caddy)
* [Composer](https://getcomposer.org)
* MySQL or PostgreSQL

### Installation

Download source code with Git
```bash
$ cd /var/www # Or wherever you chose to install web applications to
$ git clone https://github.com/Tokenary/laravel-walletconnect-bridge.git
$ cd laravel-walletconnect-bridge
```
Configure environment. By default project comes with a `.env.example` file. You'll need to rename this file to just `.env` regardless of what environment you're working on.
```dotenv
APP_NAME=WalletConnectBridge
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=

LOG_CHANNEL=stack

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

SESSION_EXPIRATION=100
CALL_EXPIRATION=100
```
Install Composer dependencies  
```bash
composer install --no-dev -o
```
Set up application 
```bash
php artisan key:generate
```
Set up database tables 
```bash
php artisan migrate
```
Set up cron tasks by adding the following Cron entry to your server
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

To run tests, execute the phpunit command
```bash
phpunit
```
                    
### License

Copyright (c) 2018

Licensed under the LGPL-3.0 License. [View license](/LICENSE).   
                       




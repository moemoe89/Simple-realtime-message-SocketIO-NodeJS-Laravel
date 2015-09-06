# Simple-realtime-message-SocketIO-NodeJS-Laravel 5.1
Simple realtime push message using [Socket.IO](http://socket.io/) ([Node.JS](https://nodejs.org/en/)) integrated with PHP ([Laravel](http://laravel.com/)) and database MySQL

# Demo
[Visit Here](https://www.youtube.com/watch?v=54yhqN3ITEQ)

# Setup
Download or clone [Master File](https://github.com/moemoe89/Simple-realtime-message-SocketIO-NodeJS-Laravel)
and then install the composer using terminal / cmd. Make sure that you've installed [Composer](https://getcomposer.org/)
```
composer install
```
# Setting db
open .env file and configure the database connection
```
DB_HOST=your_host
DB_DATABASE=your_db
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

# Generate key
generate key using
```
php artisan key:generate
```

# Migrate the db
```
php artisan migrate
```

# Install Node.JS modules
Make sure that you have already installed  ([Node.JS](https://nodejs.org/en/)).
```
npm install --prefix ./public/
```

# Running Node.JS server
After that go to your root directory and run the server using terminal / command prompt with this syntax :
```
node server.js
```
if no error you will get this log :
```
Server listening at port 3000
```
Finally you can try send message

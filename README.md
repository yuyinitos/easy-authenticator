# easyAuthenticator

A Laravel 5 package that makes authenticating users a breeze! 

This package allows you to authenticate users with all social networks that are currently supported by Laravel Socialite and store them in a database. You can also register and login in regular users and there is the option to allow account activation via email. This package comes with all the base templates and migrations you need to get started.

For custom Laravel packages contact me.

For more Laravel news visit www.codeanchor.net

You can register issues <a href="https://github.com/lucabernardino/easy-authenticator/issues">here</a>. 

#Setup

Add the following line to the require section of your composer.json file


```
"bernardino/easy-authenticator": "dev-master"
```


Run 

```
composer update
```

Run 

```
php artisan vendor:publish
```


Run

```
php artisan migrate
```

You should now have a base login page by visiting /login in the browser


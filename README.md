Translatable v1
===================

Latest version.

Simple language file manager for Laravel 5

Requirements
------------

* Laravel 5

Installation
------------

### Step 2: Install via Composer

```
composer require abbylynn/translatable
```

### Step 2: Enable the package

Withun your config/app.php file, add the following to the providers array.

``` php
AbbyLynn\Translatable\TranslatableServiceProvider::class,
```

### Step 3: Publish Resources

This will add the necessary views, migrations, seeds, and configs to your laravel installation.

```
php artisan vendor:publish
```

### Step 4: Run Migration & Seeder

```
php artisan migrate
php artisan db:seed
```

Pull Requests
-------------

I am open to pull requests for additional features and/or improvements.


To Do
-------------

- Allow Translation of the texts for this system
- Duplicating languages
- Adding/removing new translations to the language files via interface
- Option to store translations in database
- Helper functions for display language changers, flags, etc.

License
-------------
Translatable is open-sourced software licensed under the MIT license.
# Laravel Nextcloud Filesystem Driver

Based on [pbmedia/laravel-webdav](https://github.com/pascalbaljetmedia/laravel-webdav), fork of [jedlikowski/laravel-nextcloud](https://github.com/jedlikowski/laravel-nextcloud).

## Installation

```bash
composer require nextgen-tech/laravel-nextcloud-driver
```

## Usage

Create a Nextcloud filesystem disk:

```php
// config/filesystems.php

'disks' => [
    ...
    'nextcloud' => [
        'driver'   => 'nextcloud',
        'url'      => env('NEXTCLOUD_URL', ''),
        'user'     => env('NEXTCLOUD_USER', ''),
        'password' => env('NEXTCLOUD_PASSWORD'),
        'proxy'    => env('NEXTCLOUD_PROXY'),
        'encoding' => env('NEXTCLOUD_ENCODING'),
    ],
    ...
];
```

Add variables to .env file:

```
NEXTCLOUD_URL=
NEXTCLOUD_USER=
NEXTCLOUD_PASSWORD=
NEXTCLOUD_PROXY=
NEXTCLOUD_ENCODING=
```

<?php

declare(strict_types=1);

namespace NGT\Laravel\NextcloudDriver;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;

class NextcloudServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Storage::extend('nextcloud', function ($app, $config) {
            $pathPrefix = 'remote.php/dav/files/' . $config['user'];

            if (array_key_exists('pathPrefix', $config)) {
                $pathPrefix = rtrim($config['pathPrefix'], '/') . '/' . $pathPrefix;
            }

            $client = new NextcloudWebDAVClient([
                'baseUri' => $config['url'],

                'userName' => $config['user'],
                'password' => !empty($config['password']) ? $config['password'] : null,
                'authType' => CURLAUTH_BASIC,

                'proxy'    => !empty($config['proxy']) ? $config['proxy'] : null,
                'encoding' => !empty($config['encoding']) ? $config['encoding'] : null,
            ]);

            $adapter = new NextcloudWebDAVAdapter($client, $pathPrefix);

            return new FilesystemAdapter(
                new Filesystem($adapter, $config),
                $adapter,
                $config
            );
        });
    }
}

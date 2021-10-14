<?php

declare(strict_types=1);

namespace NGT\Laravel\NextcloudDriver\Tests;

use Illuminate\Support\Facades\Storage;
use NGT\Laravel\NextcloudDriver\NextcloudServiceProvider;
use NGT\Laravel\NextcloudDriver\NextcloudWebDAVAdapter;

class ServiceProviderTest extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app): array
    {
        return [NextcloudServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('filesystems.disks.nextcloud', [
            'driver'   => 'nextcloud',
            'url'      => 'https://mywebdavstorage.com',
            'user'     => 'nextgen-tech',
            'password' => 'supersecretpassword',
        ]);
    }

    /** @test */
    public function it_registers_a_webdav_driver(): void
    {
        $filesystem = Storage::disk('nextcloud');
        $driver     = $filesystem->getDriver();
        $adapter    = $driver->getAdapter();

        $this->assertInstanceOf(NextcloudWebDAVAdapter::class, $adapter);
    }

    /** @test */
    public function it_can_have_an_optional_path_prefix(): void
    {
        $this->app['config']->set('filesystems.disks.nextcloud.pathPrefix', 'prefix');
        $user = $this->app['config']->get('filesystems.disks.nextcloud.user');

        $filesystem = Storage::disk('nextcloud');
        $driver     = $filesystem->getDriver();
        $adapter    = $driver->getAdapter();

        $this->assertInstanceOf(NextcloudWebDAVAdapter::class, $adapter);
        $this->assertEquals('prefix/remote.php/dav/files/' . $user . '/', $adapter->getPathPrefix());
    }

    /** @test */
    public function it_can_generate_direct_url_to_file(): void
    {
        $user       = $this->app['config']->get('filesystems.disks.nextcloud.user');
        $filesystem = Storage::disk('nextcloud');
        $driver     = $filesystem->getDriver();
        $adapter    = $driver->getAdapter();

        $filename  = 'backup-2019-09-25-21-00-00.zip';
        $targetUrl = 'https://mywebdavstorage.com/remote.php/dav/files/' . $user . '/' . $filename;

        $this->assertInstanceOf(NextcloudWebDAVAdapter::class, $adapter);
        $this->assertEquals($targetUrl, $filesystem->url($filename));
    }
}

<?php

declare(strict_types=1);

namespace NGT\Laravel\NextcloudDriver;

use League\Flysystem\PathPrefixer;
use League\Flysystem\WebDAV\WebDAVAdapter;

class NextcloudWebDAVAdapter extends WebDAVAdapter
{
    /**
     * List of metadata fields.
     *
     * @var array<string>
     */
    protected static $metadataFields = [
        '{DAV:}getlastmodified',
        '{DAV:}getetag',
        '{DAV:}getcontenttype',
        '{DAV:}resourcetype',
        '{DAV:}getcontentlength',
        '{http://owncloud.org/ns}size',
    ];

    /**
     * List of metadata field casts.
     *
     * @var  array<string, string>
     */
    protected static $resultMap = [
        '{DAV:}getcontentlength'       => 'size',
        '{DAV:}getcontenttype'         => 'mimetype',
        'content-length'               => 'size',
        'content-type'                 => 'mimetype',
        '{http://owncloud.org/ns}size' => 'size',
    ];

    /**
     * Constructor.
     *
     * @param Client $client
     * @param string $prefix
     */
    public function __construct(NextcloudWebDAVClient $client, $prefix = '')
    {
        parent::__construct($client, $prefix);
        $this->client = $client;
        $this->prefixer = new PathPrefixer($prefix);
    }

    /**
     * Get the remote URL for the file at the given path.
     *
     * @param  string  $path
     *
     * @return string
     */
    public function getUrl(string $path): string
    {
        return $this->client->getAbsoluteUrl(
            $this->applyPathPrefix($this->encodePath($path))
        );
    }

    /**
     * Apply the path prefix.
     *
     * @param  string $path
     *
     * @return string prefixed path
     */
    protected function applyPathPrefix($path): string
    {
        return '/' . trim($this->prefixer->prefixPath($path), '/');
    }
}

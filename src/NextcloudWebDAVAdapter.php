<?php

declare(strict_types=1);

namespace NGT\Laravel\NextcloudDriver;

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
     * Get the remote URL for the file at the given path.
     *
     * @param   string  $path
     *
     * @return  string
     */
    public function getUrl(string $path): string
    {
        return $this->client->getAbsoluteUrl(
            $this->applyPathPrefix($this->encodePath($path))
        );
    }
}

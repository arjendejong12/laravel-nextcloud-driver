<?php

declare(strict_types=1);

namespace NGT\Laravel\NextcloudDriver;

use Sabre\DAV\Client as WebDAVClient;
use Sabre\HTTP\RequestInterface;

class NextcloudWebDAVClient extends WebDAVClient
{
    /**
     * @inheritDoc
     *
     * Temporary fix for missing filesize in request.
     * @see https://github.com/sabre-io/http/pull/172
     *
     * @param   \Sabre\HTTP\RequestInterface  $request
     *
     * @return  array<int, mixed>
     */
    protected function createCurlSettingsArray(RequestInterface $request): array
    {
        $settings = parent::createCurlSettingsArray($request);

        if (in_array($request->getMethod(), ['HEAD', 'GET'], true)) {
            return $settings;
        }

        $body = $request->getBody();

        if (!is_resource($body)) {
            return $settings;
        }

        $bodyStat = fstat($body);

        if ($bodyStat !== false && array_key_exists('size', $bodyStat)) {
            $settings[CURLOPT_INFILESIZE] = $bodyStat['size'];
        }

        return $settings;
    }
}

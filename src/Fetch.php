<?php

namespace Balsama;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;

use function PHPUnit\Framework\throwException;

class Fetch
{
    /**
     * @param string $url
     * @param int $retryOnError
     * @return mixed
     * @throws GuzzleException
     */
    public static function fetch($url, $retryOnError = 5)
    {
        $client = new Client();
        try {
            $response = $client->get($url);
            return json_decode($response->getBody());
        } catch (ServerException $e) {
            if ($retryOnError) {
                $retryOnError--;
                usleep(250000);
                return self::fetch($retryOnError);
            }
            throw $e;
        } catch (GuzzleException $e) {
            throw $e;
        }
    }
}

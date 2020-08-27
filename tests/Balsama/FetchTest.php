<?php

namespace Balsama;

use donatj\MockWebServer\MockWebServer;
use donatj\MockWebServer\Response as MockResponse;
use PHPUnit\Framework\TestCase;

class FetchTest extends TestCase
{

    private MockWebServer $server;
    private $url;

    protected function setup(): void
    {
        $this->server = new MockWebServer();
        $this->server->start();
        $this->url[200] = $this->server->setResponseOfPath(
            '/200',
            new MockResponse(
                json_encode(['foo' => 'bar']),
                [ 'Cache-Control' => 'no-cache' ],
                200
            )
        );
        $this->url[404] = $this->server->setResponseOfPath(
            '/404',
            new MockResponse(
                'Not found.',
                [ 'Cache-Control' => 'no-cache' ],
                404
            )
        );
    }

    public function testFetch()
    {
        $response200 = Fetch::fetch($this->url[200]);
        $this->assertTrue($response200->foo == 'bar');

        $this->expectException('GuzzleHttp\Exception\ClientException');
        $response404 = Fetch::fetch($this->url[404]);
    }
}

<?php

namespace Islandora\Chullo\Test;

use donatj\MockWebServer\Response;
use Islandora\Chullo\FedoraApi;

class GetTimemapURITest extends ChulloTestBase
{

    /**
     * @covers  \Islandora\Chullo\FedoraApi::getTimemapURI
     * @uses    \GuzzleHttp\Client
     */
    public function testReturnsTimemapHeaderOn200()
    {

        $headers = [
            'Status' => '200 OK',
            'ETag' => "bbdd92e395800153a686773f773bcad80a51f47b",
            'Last-Modified' => 'Wed, 28 May 2014 18:31:36 GMT',
            'Link' => '<http://www.w3.org/ns/ldp#Resource>;rel="type"',
            'Link' => '<http://www.w3.org/ns/ldp#Container>;rel="type"',
            'Link' => '<http://localhost:8080/rest/path/to/resource/fcr:versions>;rel="timemap"',
        ];
        $test_uri = self::$webserver->setResponseOfPath(
            "/rest/path/to/resource",
            new Response(
                "",
                $headers,
                200
            )
        );

        $api = FedoraApi::create(self::$webserver->getHost());

        $timemapuri = $api->getTimemapURI($test_uri);

        $this->assertEquals("http://localhost:8080/rest/path/to/resource/fcr:versions", $timemapuri);
    }
}

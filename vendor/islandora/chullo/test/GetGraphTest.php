<?php

namespace Islandora\Chullo\Test;

use donatj\MockWebServer\Response;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;

use Islandora\Chullo\FedoraApi;
use PHPUnit\Framework\TestCase;

class GetGraphTest extends ChulloTestBase
{

    /**
     * @covers  \Islandora\Chullo\FedoraApi::getGraph
     * @uses    \GuzzleHttp\Client
     */
    public function testReturnsContentOn200()
    {
        $fixture = <<<EOD
            [ {
              "@id" : "http://127.0.0.1:8080/fcrepo/rest/4d/8b/2d/8e/4d8b2d8e-d063-4c9f-aac9-6b285b193ed6",
              "@type" : [ "http://www.w3.org/ns/ldp#RDFSource", "http://www.w3.org/ns/ldp#Container",
"http://www.jcp.org/jcr/nt/1.0folder", "http://www.jcp.org/jcr/nt/1.0hierarchyNode", 
"http://www.jcp.org/jcr/nt/1.0base", "http://www.jcp.org/jcr/mix/1.0created", 
"http://fedora.info/definitions/v4/repository#Container", "http://fedora.info/definitions/v4/repository#Resource", 
"http://www.jcp.org/jcr/mix/1.0lastModified", "http://www.jcp.org/jcr/mix/1.0referenceable" ],
              "http://fedora.info/definitions/v4/repository#created" : [ {
                "@type" : "http://www.w3.org/2001/XMLSchema#dateTime",
                "@value" : "2015-10-03T02:14:34.391Z"
              } ],
              "http://fedora.info/definitions/v4/repository#createdBy" : [ {
                "@value" : "bypassAdmin"
              } ],
              "http://fedora.info/definitions/v4/repository#exportsAs" : [ {
                "@id" : 
"http://127.0.0.1:8080/fcrepo/rest/4d/8b/2d/8e/4d8b2d8e-d063-4c9f-aac9-6b285b193ed6/fcr:export?format=jcr/xml"
              } ],
              "http://fedora.info/definitions/v4/repository#hasParent" : [ {
                "@id" : "http://127.0.0.1:8080/fcrepo/rest/"
              } ],
              "http://fedora.info/definitions/v4/repository#lastModified" : [ {
                "@type" : "http://www.w3.org/2001/XMLSchema#dateTime",
                "@value" : "2015-10-03T02:14:34.631Z"
              } ],
              "http://fedora.info/definitions/v4/repository#lastModifiedBy" : [ {
                "@value" : "bypassAdmin"
              } ],
              "http://fedora.info/definitions/v4/repository#mixinTypes" : [ {
                "@value" : "fedora:Container"
              }, {
                "@value" : "fedora:Resource"
              } ],
              "http://fedora.info/definitions/v4/repository#primaryType" : [ {
                "@value" : "nt:folder"
              } ],
              "http://fedora.info/definitions/v4/repository#writable" : [ {
                "@type" : "http://www.w3.org/2001/XMLSchema#boolean",
                "@value" : "true"
              } ],
              "http://purl.org/dc/terms/title" : [ {
                "@value" : "My Sweet Title"
              } ]
            }, {
              "@id" :
"http://127.0.0.1:8080/fcrepo/rest/4d/8b/2d/8e/4d8b2d8e-d063-4c9f-aac9-6b285b193ed6/fcr:export?format=jcr/xml",
              "http://purl.org/dc/elements/1.1/format" : [ {
                "@id" : "http://fedora.info/definitions/v4/repository#jcr/xml"
              } ]
            } ]
EOD;
        $test_uri = self::$webserver->setResponseOfPath(
            "/rest/path/to/resource",
            new Response(
                $fixture,
                ['Content-Type' => 'application/ld+json'],
                200
            )
        );

        $api = FedoraApi::create(self::$webserver->getHost());

        $result = $api->getResource($test_uri);

        $graph = $api->getGraph($result);
        $title = (string)$graph->get(
            "http://127.0.0.1:8080/fcrepo/rest/4d/8b/2d/8e/4d8b2d8e-d063-4c9f-aac9-6b285b193ed6",
            "dc:title"
        );

        $this->assertSame("My Sweet Title", $title);
    }
}

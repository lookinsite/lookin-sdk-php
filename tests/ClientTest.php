<?php

namespace Lookin\Tests;

use PHPUnit\Framework\TestCase;
use Lookin\Client;

/**
 * Lookin\Client test
 *
 * @property \Lookin\LookinClient $client
 */
class ClientTest extends TestCase
{

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     * fail with 401 response
     *
     * @expectedException \Lookin\Exception\InvalidSecretKeyException
     * @expectedExceptionCode 401
     */
    public function testSearcEndWith401()
    {
        new Client();
    }

    /**
     * fail with 401 response 2
     *
     * @expectedException \Lookin\Exception\InvalidSecretKeyException
     * @expectedExceptionCode 401
     */
    public function testSearchEndWith401()
    {
        new Client("invalid_format");
    }

    /**
     * fail with 406 response
     *
     * @expectedException \Lookin\Exception\InvalidRequestException
     * @expectedExceptionCode 406
     */
    public function testSearchEndWith406()
    {
        $client = new Client('sk_0000000000000000000000000000000000000000');
        $obj = new \stdClass();
        $client->search($obj);
    }

    /**
     * fail with 500 response
     *
     * @expectedException \Lookin\Exception\InvalidJsonSchemaException
     * @expectedExceptionCode 500
     */
    public function testSearchFailure()
    {
        $mockresponse = [
            new \GuzzleHttp\Psr7\Response(200, [], ""),
        ];

        $client = new Client('sk_0000000000000000000000000000000000000000');

        // モックを設定
        $client->mockResponses = $mockresponse;
        $req = new \Lookin\Request\ApiSearchRequest();
        $client->search($req);
    }

    /**
     * search 異常系
     *
     * @expectedException \Lookin\Exception\HttpErrorException
     * @expectedExceptionCode 400
     */
    public function testSearchEndWith400()
    {
        $mockresponse = [
            new \GuzzleHttp\Psr7\Response(400, [], '{"status":400,"message":"Please specify a search keyword."}'),
        ];
        $client = new Client('sk_0000000000000000000000000000000000000000');

        // モックを設定
        $client->mockResponses = $mockresponse;
        $req = new \Lookin\Request\ApiSearchRequest();
        $client->search($req);
    }

    /**
     * search 正常系
     *
     */
    public function testSearchEndWithOK()
    {
        $mockresponse = [
            new \GuzzleHttp\Psr7\Response(302, [], '{"duration": 3,"total": 2,"size": 30,"total_pages": 1,"current_page": 1,"has_prev": false,"has_next": false,"start": 1,"end": 2,"hits": [{"language": "ja","title": "TEST","content": "テストページの内容","url": "http://example.com/path/to/page1","score": 13.4268875}, {"language": "ja","title": "TEST","content": "テストページの内容","url": "http://example.com/path/to/page2","score": 13.4268875}]}'),
        ];
        $client = new Client('sk_0000000000000000000000000000000000000000');

        // モックを設定
        $client->mockResponses = $mockresponse;
        $req = new \Lookin\Request\ApiSearchRequest();

        $res = $client->search($req);
        $this->assertEquals('Lookin\Response\ApiSearchResponse', get_class($res));
    }
}
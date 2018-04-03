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

    private $client;

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
     * 401エラーが発生するテストケース
     *
     * @expectedException \Lookin\Exception\SecretKeyNotSpecifiedException
     * @expectedExceptionCode 401
     */
    public function testSearcEndhWith401()
    {
        $client = new Client();
    }

    /**
     * 406エラーが発生するテストケース
     *
     * @expectedException \Lookin\Exception\InvalidRequestException
     * @expectedExceptionCode 406
     */
    public function testSearchEndWith406()
    {
        $client = new Client('dummy');
        $obj = new \stdClass();
        $client->search($obj);
    }

    /**
     * search 異常系
     *
     * @expectedException \Lookin\Exception\InvalidJsonSchemaException
     * @expectedExceptionCode 500
     */
    public function testSearchFailure()
    {
        $mockresponse = [
            new \GuzzleHttp\Psr7\Response(200),
        ];

        $client = new Client('sk_00000000000000000000000000000000');

        // モックを設定
        $client->mockResponses = $mockresponse;
        $req = new \Lookin\Request\ApiSearchRequest();
        $res = $client->search($req);
        $this->assertEquals('[{"property":"","pointer":"","message":"NULL value found, but an object is required","constraint":"type","context":1}]', $res);
    }

    /**
     * search 異常系
     *
     * @expectedException \GuzzleHttp\Exception\ClientException
     * @expectedExceptionCode 400
     */
    public function testSearchEndWith400()
    {
        $mockresponse = [
            new \GuzzleHttp\Psr7\Response(400, [], '{"status":400,"message":"Please specify a search keyword."}'),
        ];
        $client = new Client('sk_00000000000000000000000000000000');

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
        $client = new Client('sk_00000000000000000000000000000000');

        // モックを設定
        $client->mockResponses = $mockresponse;
        $req = new \Lookin\Request\ApiSearchRequest();
        $req->q = "keyword";

        $res = $client->search($req);
        $this->assertEquals('Lookin\Response\ApiSearchResponse', get_class($res));
    }
}

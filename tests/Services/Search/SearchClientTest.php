<?php

namespace Lookin\Tests\Services\Search;

use PHPUnit\Framework\TestCase;
use Lookin\Services\Search\SearchClient;
use Lookin\Services\Search\SearchRequest;


/**
 * Lookin\Client test
 *
 * @property \Lookin\LookinClient $client
 */
class SearchClientTest extends TestCase
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
        new SearchClient();
    }

    /**
     * fail with 401 response 2
     *
     * @expectedException \Lookin\Exception\InvalidSecretKeyException
     * @expectedExceptionCode 401
     */
    public function testSearchEndWith401()
    {
        new SearchClient("invalid_format");
    }

    /**
     * fail with 406 response
     *
     * @expectedException \Lookin\Exception\InvalidRequestException
     * @expectedExceptionCode 406
     */
    public function testSearchEndWith406()
    {
        $client = new SearchClient('sk_0000000000000000000000000000000000000000');
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

        $client = new SearchClient('sk_0000000000000000000000000000000000000000');

        // モックを設定
        $client->mockResponses = $mockresponse;
        $req = new SearchRequest();
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
        $client = new SearchClient('sk_0000000000000000000000000000000000000000');

        // モックを設定
        $client->mockResponses = $mockresponse;
        $req = new SearchRequest();
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
        $client = new SearchClient('sk_0000000000000000000000000000000000000000');

        // モックを設定
        $client->mockResponses = $mockresponse;
        $req = new SearchRequest();

        $res = $client->search($req);
        $this->assertEquals('Lookin\Services\Search\SearchResponse', get_class($res));
    }
}

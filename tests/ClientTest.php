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
    public function testSearchWith401()
    {
        $this->client = new Client();
    }

    /**
     * 406エラーが発生するテストケース
     *
     * @expectedException \Lookin\Exception\InvalidRequestException
     * @expectedExceptionCode 406
     */
    public function testSearchWith406()
    {
        $this->client = new Client("hogehoge");
        $obj = new \stdClass();
        $this->client->search($obj);
    }

    /**
     * 正常系
     */
    public function testSearch()
    {
        $this->client = new Client("hogehoge");
        $req = new \Lookin\Request\ApiSearchRequest();
        $res = $this->client->search($req);
        $this->assertEquals("", $res);
    }
}

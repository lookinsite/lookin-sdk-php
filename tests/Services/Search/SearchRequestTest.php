<?php

namespace Lookin\Tests\Services\Search;

use PHPUnit\Framework\TestCase;
use Lookin\Services\Search\SearchRequest;

/**
 * Lookin\Client test
 */
class SearchRequestTest extends TestCase
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
     * fail with 404 response
     *
     * @expectedException \Lookin\Exception\MissingKeyException
     * @expectedExceptionCode 404
     */
    public function testSetEndWith404()
    {
        $req = new SearchRequest();
        $req->invalid = "dummy";
    }

    /**
     * setter check
     */
    public function testSetter()
    {
        $cases = [
            // ok
            [
                "expect" => true,
                "data" => [
                    "q" => "keyword", "size" => 30, "page" => 1, "device" => "desktop",
                ],
            ],
            // ng
            [
                "expect" => false,
                "data" => [
                    "q" => 1,
                ],
            ],
            // ng
            [
                "expect" => false,
                "data" => [
                    "size" => "a",
                ],
            ],
            // ng
            [
                "expect" => false,
                "data" => [
                    "page" => "a",
                ],
            ],
            // ng
            [
                "expect" => false,
                "data" => [
                    "device" => "pc",
                ],
            ],
        ];

        foreach ($cases as $i => $case) {
            $req = new SearchRequest();
            foreach ($case['data'] as $k => $v) {
                try {
                    $req->$k = $v;
                    $res = true;
                } catch (\Exception $_) {
                    $res = false;
                }

                $this->assertTrue($case['expect'] === $res, sprintf('assert #%s failed.', $i + 1));
            }
        }
    }

    /**
     * check configuration match
     */
    public function testGetResponse()
    {
        $req = new SearchRequest();
        $config = $req->getRequest();
        $this->assertEquals('{"q":"","size":30,"page":1,"device":"desktop","domain":""}', json_encode($config));
    }
}

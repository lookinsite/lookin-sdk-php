<?php

namespace Lookin\Tests\Request;

use PHPUnit\Framework\TestCase;
use Lookin\Response\ApiSearchResponse;

/**
 * Lookin\Client test
 *
 * @property \Lookin\ApiSearchRequest $request
 */
class ApiSearchResponseTest extends TestCase
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

    private $okResponse = '{"duration": 3,"total": 2,"size": 30,"total_pages": 1,"current_page": 1,"has_prev": false,"has_next": false,"start": 1,"end": 2,"hits": [{"language": "ja","title": "TEST","content": "テストページの内容","url": "http://example.com/path/to/page1","score": 13.4268875}, {"language": "ja","title": "TEST","content": "テストページの内容","url": "http://example.com/path/to/page2","score": 13.4268875}]}';

    /**
     * test __construct
     */
    public function test__construct()
    {
        $cases = [
            // ok
            [
                "expect" => true,
                "data" => $this->okResponse,
            ],
            // ng
            [
                "expect" => false,
                "data" => '{"invalid": 0}',
            ],
        ];

        foreach ($cases as $i => $case) {

            try {
                new ApiSearchResponse($case['data']);
                $res = true;
            } catch (\Exception $_) {
                $res = false;
            }

            $this->assertTrue($case['expect'] === $res, sprintf('assert #%s failed.', $i + 1));
        }
    }

    /**
     * test getter
     */
    public function test_get()
    {
        // prepare
        $response = new ApiSearchResponse($this->okResponse);

        $cases = [
            // ok
            ["expect" => true, "keyname" => 'total'],
            ["expect" => true, "keyname" => 'size'],
            ["expect" => true, "keyname" => 'current_page'],
            ["expect" => true, "keyname" => 'total_pages'],
            ["expect" => true, "keyname" => 'has_prev'],
            ["expect" => true, "keyname" => 'has_next'],
            ["expect" => true, "keyname" => 'start'],
            ["expect" => true, "keyname" => 'end'],
            ["expect" => true, "keyname" => 'duration'],
            // ng
            ["expect" => false, "keyname" => 'invalid'],
        ];

        foreach ($cases as $i => $case) {
            try {
                $response->{$case['keyname']};
                $res = true;
            } catch (\Exception $_) {
                $res = false;
            }

            $this->assertTrue($case['expect'] === $res, sprintf('assert #%s failed.', $i + 1));
        }
    }

    /**
     * test getIterator method
     */
    public function testGetIterator()
    {
        // prepare
        $response = new ApiSearchResponse($this->okResponse);
        $it = $response->getIterator();
        $this->assertTrue(get_class($it) === "Generator");

        $i = 0;
        foreach ($it as $key => $val) {
            $i++;
        }

        $this->assertTrue($i === 2);
    }
}

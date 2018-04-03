<?php

namespace Lookin\Tests;

use PHPUnit\Framework\TestCase;
use Lookin\Schema\SchemaValidator;

/**
 * Lookin\Client test
 *
 * @property \Lookin\LookinClient $client
 */
class SchemaValidatorTest extends TestCase
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
     * test validate method
     *
     */
    public function testValidate()
    {
        $cases = [
            // ok
            [
                "expect" => true,
                "definition" => 'api-search-request',
                "data" => '{"q":"","size":30,"page":1,"device":"desktop","domain":""}',
            ],
            // ok
            [
                "expect" => true,
                "definition" => 'api-search-response',
                "data" => '{"total": 0, "size": 0, "current_page": 0, "total_pages": 0, "has_prev": false, "has_next": false, "start": 0, "end": 0, "duration": 0, "hits": []}',
            ],
            // ng
            [
                "expect" => false,
                "definition" => 'invalid-json-schema',
                "data" => '',
            ],
        ];

        foreach ($cases as $i => $case) {

            try {
                $validator = new SchemaValidator();
                $validator->validate($case['definition'], json_decode($case['data']));

                $res = true;
            } catch (\Exception $_) {
                $res = false;
            }

            $this->assertTrue($case['expect'] === $res, sprintf('assert #%s failed.', $i + 1));
        }
    }
}

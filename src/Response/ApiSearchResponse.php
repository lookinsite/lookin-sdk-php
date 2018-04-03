<?php

namespace Lookin\Response;

/**
 * Lookin
 *
 * @copyright     Copyright (c) Instoll. inc
 * @link          https://lookin.site Lookin
 * @since         1.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
use Lookin\Schema\SchemaValidator;
use Lookin\Exception\MissingKeyException;
use Lookin\Exception\InvalidTypeException;

/**
 * Build API search response and validate with json schema
 */
class ApiSearchResponse
{

    /**
     * array of response data
     *
     * @var array
     */
    private $data = [];

    /**
     * constructor
     *
     * @param string $str
     */
    public function __construct($str = null)
    {
        $this->data = json_decode($str, true);

        $this->build($this->data);
    }

    public function build($data)
    {
        // Validate with json schema
        $this->validate($data);
    }

    public function getResponse()
    {
        return $this->data;
    }

    public function validate($data = [])
    {
        $validator = new SchemaValidator();
        $validator->validate('api-search-response', $data);
    }
}

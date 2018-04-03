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
     * schema validator
     *
     * @var SchemaValidator
     */
    private $validator;

    /**
     * constructor
     *
     * @param string $str
     */
    public function __construct($str = null)
    {
        $this->data = json_decode($str);

        // validate schema
        $this->validator = new SchemaValidator();
        $this->validator->validate('api-search-response', $this->data);
    }
}

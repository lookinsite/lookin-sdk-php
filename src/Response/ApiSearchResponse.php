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

/**
 * Build API search response and validate with json schema
 */
class ApiSearchResponse
{

    /**
     *
     * @var array
     */
    private $data = [];

    public function __construct($body = null)
    {
        $this->data = json_decode($body, true);

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

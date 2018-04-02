<?php

namespace Lookin\Request;

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
 * Build API search request and validate with json schema
 */
class ApiSearchRequest
{

    /**
     * Default configurations
     *
     * @var array
     */
    private $defaults = [
        'q' => '',
        'size' => 30,
        'page' => 1,
        'device' => 'desktop',
        'domain' => '', // domain will be automatically set.
    ];

    /**
     * Request configuration
     *
     * @var array
     */
    private $config = array(
    );

    public function __construct($opts = [])
    {
        $this->config = array_merge($this->defaults, $opts);
    }

    public function build()
    {
        // Validate with json schema
        $this->validate($this->config);
    }

    public function getRequest()
    {
        return $this->config;
    }

    public function validate($data = [])
    {
        $validator = new SchemaValidator();
        $validator->validate('api-search-request', $data);
    }
}

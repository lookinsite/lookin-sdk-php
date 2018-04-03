<?php

namespace Lookin\Request;

/**
 * Lookin
 *
 * @copyright     Copyright (c) Instoll. inc
 * @link          https://lookin.site Lookin
 * @since         1.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @author        tomohiroukawa https://github.com/tomohiroukawa
 */

use Lookin\Schema\SchemaValidator;
use Lookin\Exception\MissingKeyException;

/**
 * Build API search request and validate with json schema
 *
 * @property string $q
 * @property int $size
 * @property int $page
 * @property string $device
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
        'domain' => '', // domain will be ignored.
    ];

    /**
     * Request configuration
     *
     * @var array
     */
    private $config = [];

    /**
     * schema validator
     *
     * @var SchemaValidator
     */
    private $validator;

    /**
     * constructor
     *
     * @param array $opts
     */
    public function __construct($opts = [])
    {
        $this->config = array_merge($this->defaults, $opts);
        $this->validator = new SchemaValidator();
        $this->validator->validate('api-search-request', $this->config);
    }

    /**
     * returns request configuration
     *
     * @return array
     */
    public function getRequest()
    {
        return $this->config;
    }

    /**
     * property setter
     *
     * @param string $name keyname of $config
     * @param mixed $value
     * @throws MissingKeyException
     */
    public function __set($name, $value)
    {
        if (!isset($this->config[$name])) {
            // array key missing
            throw new MissingKeyException(sprintf('specified key "%s" not found.', $name));
        }

        $this->config[$name] = $value;
        $this->validator->validate('api-search-request', $this->config);
    }
}

<?php

namespace Lookin\Response;

/**
 * Lookin
 *
 * @copyright     Copyright (c) Instoll. inc
 * @link          https://lookin.site Lookin
 * @since         1.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @author        tomohiroukawa https://github.com/tomohiroukawa
 */

use IteratorAggregate;
use Lookin\Schema\SchemaValidator;
use Lookin\Exception\MissingKeyException;

/**
 * Build API search response and validate with json schema
 *
 * @property int $total
 * @property int $size
 * @property int $current_page
 * @property int $total_pages
 * @property boolean $has_prev
 * @property boolean $has_next
 * @property int $start
 * @property int $end
 * @property int $duration duration time in ms
 */
class ApiSearchResponse implements IteratorAggregate
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

    /**
     * getter
     *
     * @param string $key keyname
     * @return mixed
     */
    public function __get($key)
    {
        if (!property_exists($this->data, $key)) {
            throw new MissingKeyException(sprintf('specified key "%s" not found', $key));
        }
        return $this->data->$key;
    }

    /**
     * return iterator of pages
     */
    public function getIterator()
    {
        foreach ($this->data->hits as $key => $val) {
            yield $key => $val;
        }
    }
}

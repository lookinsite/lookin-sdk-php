<?php

namespace Lookin\Schema;

/**
 * Lookin
 *
 * @copyright     Copyright (c) Instoll. inc
 * @link          https://lookin.site Lookin
 * @since         1.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
use JsonSchema\Validator;
use Lookin\Exception\InvalidJsonSchemaException;

/**
 * Validate with jsonschema
 */
class SchemaValidator
{

    /**
     *  JsonSchema Validator instance
     *
     * @var \JsonSchema\Validator
     */
    private $validator;

    public function __construct()
    {
        $this->validator = new Validator();
    }

    /**
     * Validate array with JSON Schema
     *
     * @param string $schema Schema name
     * @param array $data array to validate
     */
    public function validate($schema = null, $data = [])
    {
        var_dump($data);
        $schemaPath = sprintf('file://%s/%s.json', dirname(__FILE__), $schema);
        $this->validator->validate($data, (object) ['$ref' => $schemaPath]);
        $_errors = $this->validator->getErrors();

        if (!empty($_errors)) {
            // バリデート失敗時は例外スロー
            throw new InvalidJsonSchemaException(sprintf("json is invalid. definition: %s message: %s", $schema, json_encode($_errors)));
        }
    }
}

<?php

namespace Lookin\Schema;

/**
 * Lookin
 *
 * @copyright     Copyright (c) Instoll. inc
 * @link          https://lookin.site Lookin
 * @since         1.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @author        tomohiroukawa https://github.com/tomohiroukawa
 */

use JsonSchema\Validator;
use Lookin\Exception\InvalidJsonSchemaException;
use Exception;

/**
 * Validate with jsonschema
 */
class SchemaValidator
{

    /**
     * validator instance
     *
     * @var \JsonSchema\Validator
     */
    private $validator;

    public function __construct()
    {
        $this->validator = new Validator();

        return $this;
    }

    /**
     * get schema definition
     *
     * @param string $definitionName name of the definition
     * @throws Exception
     * @return object
     */
    private function getJSONSchema($definitionName)
    {
        $schema = sprintf('file://%s/%s.json', dirname(__FILE__), $definitionName);

        if (!file_exists($schema)) {
            // schema file not found
            throw new Exception(sprintf("json schema file not found. path: %s", $schema));
        }

        $schemaJson = json_decode(file_get_contents($schema));

        if (!property_exists($schemaJson, "definitions")) {
            // definition block not found
            throw new InvalidJsonSchemaException(sprintf('json schema definitions "%s" not found', $definitionName)); // @codeCoverageIgnore
        }

        if (!property_exists($schemaJson->definitions, $definitionName)) {
            // definition not found
            throw new InvalidJsonSchemaException(sprintf("json schema '%s' not found", $definitionName)); // @codeCoverageIgnore
        }

        return $schemaJson->definitions->$definitionName;
    }

    /**
     * validate array with JSON Schema
     *
     * @param string $definitionName Schema definition
     * @param array $data array to validate
     */
    public function validate($definitionName = null, $data = [])
    {
        if (!is_object($data)) {
            // cast object if not object
            $data = (object) $data;
        }

        // get definition
        $schema = $this->getJSONSchema($definitionName);

        // validate
        $this->validator->validate($data, $schema);

        if (!$this->validator->isValid()) {
            $_errors = $this->validator->getErrors();
            throw new InvalidJsonSchemaException(sprintf("json is invalid. definition: %s message: %s", $definitionName, json_encode($_errors)));
        }
    }
}

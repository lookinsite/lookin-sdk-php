<?php

namespace Lookin\Exception;

/**
 * Lookin
 *
 * @copyright     Copyright (c) Instoll. inc
 * @link          https://lookin.site Lookin
 * @since         1.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @author        tomohiroukawa https://github.com/tomohiroukawa
 */

use Exception;

/**
 * Invalid secret key not specified exception
 */
class InvalidSecretKeyException extends Exception
{

    /**
     * Constructor.
     *
     * @param string $message The error message
     * @param int $code The code of the error, is also the HTTP status code for the error.
     * @param \Exception|null $previous the previous exception.
     */
    public function __construct($message, $code = null, $previous = null)
    {
        parent::__construct($message, 401, $previous);
    }
}

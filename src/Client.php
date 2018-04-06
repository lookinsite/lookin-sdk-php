<?php

namespace Lookin;

/**
 * Lookin
 *
 * @copyright     Copyright (c) Instoll. inc
 * @link          https://lookin.site Lookin
 * @since         1.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 * @author        tomohiroukawa https://github.com/tomohiroukawa
 */

use Lookin\Exception\HttpErrorException;
use Lookin\Exception\InvalidRequestException;
use Lookin\Exception\InvalidSecretKeyException;
use Lookin\Response\ApiSearchResponse;

class Client
{

    /**
     * API endpoint url
     *
     * @var string
     */
    private $endpoint = 'https://api.staging.lookin.site/';

    /**
     * Request instance
     *
     * @var \Lookin\Request\ApiSearchRequest
     */
    private $request = null;

    /**
     * Mock response array for testing
     *
     * @var array
     */
    public $mockResponses = [];

    /**
     * Lookin secret key
     *
     * @var string
     */
    private $secretKey = '';

    public function __construct($secretKey = null)
    {
        if (!$secretKey) {
            // get secret key from environment variable if not specified.
            $secretKey = getenv('LOOKIN_SECRET_KEY');
        }

        if (!$secretKey) {
            throw new InvalidSecretKeyException('secret key not specified');
        }

        if (!preg_match("/^sk_([0-9a-zA-Z]{40})$/", $secretKey)) {
            // check secret key format
            throw new InvalidSecretKeyException('secret key is invalid');
        }

        $this->secretKey = $secretKey;

        if (getenv('LOOKIN_SEARCH_ENDPOINT')) {
            // override endpoint with environment variable
            $this->endpoint = getenv('LOOKIN_SEARCH_ENDPOINT');
        }
    }

    /**
     * search mehtods
     *
     * @param  \Lookin\Request\ApiSearchRequest $request ApiSearchRequest instance
     * @throws InvalidRequestException
     */
    public function search($request = null)
    {
        if (get_class($request) !== 'Lookin\Request\ApiSearchRequest') {
            // instance check
            throw new InvalidRequestException('request must be an instance of \Lookin\Request\ApiSearchRequest');
        }

        try {
            // send search request
            $url = $this->endpoint . 'search';
            $response = $this->__sendGET($url, $request->getRequest());
        } catch (\Exception $ex) {
            // wrap guzzle's exception
            throw new HttpErrorException($ex->getMessage(), $ex->getCode());
        }

        // create response instance
        return new ApiSearchResponse((string) $response->getBody());
    }

    /**
     * send GET request to api endpoint
     *
     * @param string $url URL
     * @param array $params array of request parameters
     */
    private function __sendGET($url, $params = [])
    {
        if (getenv('ENV') === 'TEST') {
            // when testing
            $mock = new \GuzzleHttp\Handler\MockHandler($this->mockResponses);
            $handler = \GuzzleHttp\HandlerStack::create($mock);
            $http = new \GuzzleHttp\Client(['handler' => $handler]);
        } else {
            // when not testing
            $http = new \GuzzleHttp\Client();
        }

        $response = $http->request('GET', $url, [
            'headers' => [
                'Authorization' => $this->secretKey,
            ],
            'query' => $params,
        ]);

        return $response;
    }
}

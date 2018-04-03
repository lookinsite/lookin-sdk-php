<?php

namespace Lookin;

use Lookin\Exception\HttpErrorException;
use Lookin\Exception\InvalidRequestException;
use Lookin\Exception\SecretKeyNotSpecifiedException;
use Lookin\Request\ApiSearchRequest;
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
            throw new SecretKeyNotSpecifiedException('secret key not specified');
        }

        $this->secretKey = $secretKey;
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

        // send search request
        $url = sprintf('%s/search', $this->endpoint, 'search');
        $response = $this->__sendGET($url, $request);

        if ($response->getStatusCode() >= 400) {
            throw new HttpErrorException(sprintf('http error occurred because of "%s %s"', $response->getStatusCode(), $response->getReasonPhrase()), $response->getStatusCode());
        }

        // create response instance
        $res = new ApiSearchResponse((string) $response->getBody());

        return $res;
    }

    /**
     * send GET request to api endpoint
     *
     * @param string $url URL
     * @param array $params array of request parameters
     */
    private function __sendGET($url = '', $params = array())
    {
        if (getenv('ENV') === 'TEST') {
            // when testing
            $mock = new \GuzzleHttp\Handler\MockHandler($this->mockResponses);
            $handler = \GuzzleHttp\HandlerStack::create($mock);
            $client = new \GuzzleHttp\Client(['handler' => $handler]);
        } else {
            // when not testing
            $client = new \GuzzleHttp\Client();
        }

        $response = $client->request('GET', $url, [
            'Authorization' => $this->secretKey,
        ]);

        return $response;
    }
}

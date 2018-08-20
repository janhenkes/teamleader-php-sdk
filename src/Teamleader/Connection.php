<?php
/**
 * Created by PhpStorm.
 * User: janhenkes
 * Date: 20/08/2018
 * Time: 14:09
 */

namespace Teamleader;

use Exception;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7;
use Teamleader\Exceptions\Api\TooManyRequestsException;
use Teamleader\Exceptions\ApiException;
use Psr\Http\Message\ResponseInterface;

class Connection {

    /**
     * @var string
     */
    protected $authorizationCode;

    /**
     * @var string
     */
    protected $administrationId;

    /**
     * @var string
     */
    private $apiUrl = 'https://api.teamleader.eu';

    /**
     * @var string
     */
    private $authUrl = 'https://app.teamleader.eu/oauth2/authorize';

    /**
     * @var string
     */
    private $tokenUrl = 'https://app.teamleader.eu/oauth2/access_token';

    /**
     * @var
     */
    private $clientId;

    /**
     * @var
     */
    private $clientSecret;

    /**
     * @var
     */
    private $accessToken;

    /**
     * @var
     */
    private $redirectUrl;

    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * @var array Middlewares for the Guzzle 6 client
     */
    protected $middleWares = [];

    /**
     * @return \GuzzleHttp\Client
     */
    private function client() {
        if ( $this->client ) {
            return $this->client;
        }

        $handlerStack = HandlerStack::create();
        foreach ( $this->middleWares as $middleWare ) {
            $handlerStack->push( $middleWare );
        }

        $this->client = new \GuzzleHttp\Client( [
            'http_errors' => true,
            'handler'     => $handlerStack,
            'expect'      => false,
        ] );

        return $this->client;
    }

    /**
     * @return \GuzzleHttp\Client
     * @throws ApiException
     */
    public function connect() {
        // If access token is not set or token has expired, acquire new token
        if ( empty( $this->accessToken ) ) {
            $this->acquireAccessToken();
        }

        $client = $this->client();

        return $client;
    }

    /**
     * @return string
     */
    private function getAuthUrl() {
        return $this->authUrl . '?' . http_build_query( [
                'client_id'     => $this->clientId,
                'redirect_uri'  => $this->redirectUrl,
                'response_type' => 'code',
            ] );
    }

    private function authorizeRedirect() {
        $authUrl = $this->getAuthUrl();
        header( 'Location: ' . $authUrl );
        exit;
    }

    /**
     * @throws ApiException
     */
    private function acquireAccessToken() {
        if ( empty( $_GET['code'] ) ) {
            $this->authorizeRedirect();
        }

        $code = rawurldecode( $_GET['code'] );

        $body = [
            'form_params' => [
                'code'          => $code,
                'client_id'     => $this->clientId,
                'client_secret' => $this->clientSecret,
                'redirect_uri'  => $this->redirectUrl,
                'grant_type'    => 'authorization_code',
            ],
        ];

        $response = $this->client()->post( $this->getTokenUrl(), $body );

        if ( $response->getStatusCode() == 200 ) {
            Psr7\rewind_body( $response );
            $body = json_decode( $response->getBody()->getContents(), true );

            if ( json_last_error() === JSON_ERROR_NONE ) {
                $this->accessToken = array_key_exists( 'access_token', $body ) ? $body['access_token'] : null;
            } else {
                throw new ApiException( 'Could not acquire tokens, json decode failed. Got response: ' . $response->getBody()->getContents() );
            }
        } else {
            throw new ApiException( 'Could not acquire or refresh tokens' );
        }
    }

    /**
     * @param mixed $redirectUrl
     */
    public function setRedirectUrl( $redirectUrl ) {
        $this->redirectUrl = $redirectUrl;
    }

    public function setClientId( $clientId ) {
        $this->clientId = $clientId;
    }

    public function setClientSecret( $clientSecret ) {
        $this->clientSecret = $clientSecret;
    }

    /**
     * @return string
     */
    public function getTokenUrl()
    {
        return $this->tokenUrl;
    }

    /**
     * @param string $method
     * @param string $endpoint
     * @param null $body
     * @param array $params
     * @param array $headers
     * @return Request
     */
    private function createRequest($method = 'GET', $endpoint, $body = null, array $params = [], array $headers = [])
    {
        // Add default json headers to the request
        $headers = array_merge($headers, [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ]);

        // If access token is not set or token has expired, acquire new token
        if (empty($this->accessToken)) {
            $this->acquireAccessToken();
        }

        // If we have a token, sign the request
        if (! empty($this->accessToken)) {
            $headers['Authorization'] = 'Bearer ' . $this->accessToken;
        }

        // Create param string
        if (!empty($params)) {
            $endpoint .= '?' . http_build_query($params);
        }

        // Create the request
        $request = new Request($method, $endpoint, $headers, $body);

        return $request;
    }

    /**
     * @param string $url
     * @param array $params
     * @param bool $fetchAll
     * @return mixed
     * @throws ApiException
     */
    public function get($url, array $params = [], $fetchAll = false)
    {
        try {
            $request = $this->createRequest('GET', $this->formatUrl($url, 'get'), null, $params);
            $response = $this->client()->send($request);

            $json = $this->parseResponse($response);

            if ($fetchAll === true) {
                if (($nextParams = $this->getNextParams($response->getHeaderLine('Link')))) {
                    $json = array_merge($json, $this->get($url, $nextParams, $fetchAll));
                }
            }

            return $json;
        } catch (Exception $e) {
            $this->parseExceptionForErrorMessages($e);
        }
    }

    /**
     * @param string $url
     * @param string $body
     * @return mixed
     * @throws ApiException
     */
    public function post($url, $body)
    {
        try {
            $request = $this->createRequest('POST', $this->formatUrl($url, 'post'), $body);
            $response = $this->client()->send($request);

            return $this->parseResponse($response);
        } catch (Exception $e) {
            $this->parseExceptionForErrorMessages($e);
        }
    }

    /**
     * @param string $url
     * @param string $body
     * @return mixed
     * @throws ApiException
     */
    public function patch($url, $body)
    {
        try {
            $request = $this->createRequest('PATCH', $this->formatUrl($url, 'patch'), $body);
            $response = $this->client()->send($request);

            return $this->parseResponse($response);
        } catch (Exception $e) {
            $this->parseExceptionForErrorMessages($e);
        }
    }

    /**
     * @param string $url
     * @return mixed
     * @throws ApiException
     */
    public function delete($url)
    {
        try {
            $request = $this->createRequest('DELETE', $this->formatUrl($url, 'delete'));
            $response = $this->client()->send($request);

            return $this->parseResponse($response);
        } catch (Exception $e) {
            $this->parseExceptionForErrorMessages($e);
        }
    }

    /**
     * @param Response $response
     * @return mixed
     * @throws ApiException
     */
    private function parseResponse(Response $response)
    {
        try {
            Psr7\rewind_body($response);
            $json = json_decode($response->getBody()->getContents(), true);

            return $json;
        } catch (\RuntimeException $e) {
            throw new ApiException($e->getMessage());
        }
    }

    /**
     * Parse the response in the Exception to return the Exact error messages.
     *
     * @param Exception $exception
     *
     * @throws ApiException | TooManyRequestsException
     */
    private function parseExceptionForErrorMessages(Exception $exception)
    {
        if (!$exception instanceof BadResponseException) {
            throw new ApiException($exception->getMessage());
        }

        $response = $exception->getResponse();
        Psr7\rewind_body($response);
        $responseBody = $response->getBody()->getContents();
        $decodedResponseBody = json_decode($responseBody, true);

        if (!is_null($decodedResponseBody) && isset($decodedResponseBody['error']['message']['value'])) {
            $errorMessage = $decodedResponseBody['error']['message']['value'];
        } else {
            $errorMessage = $responseBody;
        }

        $this->checkWhetherRateLimitHasBeenReached($response, $errorMessage);

        throw new ApiException('Error ' . $response->getStatusCode() . ': ' . $errorMessage, $response->getStatusCode());
    }

    /**
     * @param ResponseInterface $response
     * @param string $errorMessage
     *
     * @return void
     *
     * @throws TooManyRequestsException
     */
    private function checkWhetherRateLimitHasBeenReached(ResponseInterface $response, $errorMessage)
    {
        $retryAfterHeaders = $response->getHeader('Retry-After');
        if($response->getStatusCode() === 429 && count($retryAfterHeaders) > 0){
            $exception = new TooManyRequestsException('Error ' . $response->getStatusCode() . ': ' . $errorMessage, $response->getStatusCode());
            $exception->retryAfterNumberOfSeconds = (int) current($retryAfterHeaders);

            throw $exception;
        }
    }

    /**
     * @param string $url
     * @param string $method
     *
     * @return string
     */
    private function formatUrl($url, $method = 'get')
    {
        return $this->apiUrl . '/'  . $url;
    }
}
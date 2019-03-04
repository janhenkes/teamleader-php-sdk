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
use Teamleader\Actions\Attributes\Page;
use Teamleader\Exceptions\Api\TooManyRequestsException;
use Teamleader\Exceptions\ApiException;
use Psr\Http\Message\ResponseInterface;
use Teamleader\Exceptions\InvalidAccessTokenException;
use Teamleader\Handlers\CacheHandlerInterface;
use Teamleader\Handlers\DefaultCacheHandler;

class Connection
{

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
     * @var CacheHandlerInterface
     */
    private $cacheHandler;

    public function __construct($cacheHandler = null)
    {
        $this->client();
        $this->cacheHandler = $cacheHandler instanceof CacheHandlerInterface ? $cacheHandler : new DefaultCacheHandler();
    }

    /**
     * @return bool|mixed
     * @throws ApiException
     */
    public function getAccessToken()
    {
        // Check if tokens exist
        if (empty($this->cacheHandler->get('accessToken')) || empty($this->cacheHandler->get('refreshToken')) || empty($this->cacheHandler->get('tokenExpire'))) {
            return false;
        }

        // Check if token is expired
        // Get current time + 5 minutes (to allow for time differences)
        $now = time() + 300;
        if ($this->cacheHandler->get('tokenExpire') <= $now) {
            $this->acquireRefreshToken();

            return $this->getAccessToken();
        }

        return $this->cacheHandler->get('accessToken');
    }

    /**
     * @param string $accessToken
     * @param string $refreshToken
     * @param int    $expiresIn Seconds
     * @param int    $expiresOn Timestamp
     */
    public function storeTokens(string $accessToken, string $refreshToken, int $expiresIn, int $expiresOn)
    {
        $this->cacheHandler->set('accessToken', $accessToken, $expiresIn / 60);
        $this->cacheHandler->set('refreshToken', $refreshToken, $expiresIn / 60);
        $this->cacheHandler->set('tokenExpire', $expiresOn, $expiresIn / 60);
    }

    public function clearTokens()
    {
        $this->cacheHandler->forget('accessToken');
        $this->cacheHandler->forget('refreshToken');
        $this->cacheHandler->forget('tokenExpire');
    }

    /**
     * @return \GuzzleHttp\Client
     */
    private function client()
    {
        if ($this->client) {
            return $this->client;
        }

        $handlerStack = HandlerStack::create();
        foreach ($this->middleWares as $middleWare) {
            $handlerStack->push($middleWare);
        }

        $this->client = new \GuzzleHttp\Client([
            'http_errors' => true,
            'handler'     => $handlerStack,
            'expect'      => false,
        ]);
    }

    /**
     * @return string
     */
    private function getAuthUrl()
    {
        return $this->authUrl . '?' . http_build_query([
                'client_id'     => $this->clientId,
                'redirect_uri'  => $this->redirectUrl,
                'response_type' => 'code',
            ]);
    }

    private function authorizeRedirect()
    {
        $authUrl = $this->getAuthUrl();
        header('Location: ' . $authUrl);
        exit;
    }

    /**
     * @throws ApiException
     */
    private function acquireRefreshToken()
    {
        $body = [
            'form_params' => [
                'client_id'     => $this->clientId,
                'client_secret' => $this->clientSecret,
                'refresh_token' => $this->cacheHandler->get('refreshToken'),
                'grant_type'    => 'refresh_token',
            ],
        ];

        $response = $this->client()->post($this->getTokenUrl(), $body);

        if ($response->getStatusCode() == 200) {
            Psr7\rewind_body($response);
            $body = json_decode($response->getBody()->getContents(), true);

            if (json_last_error() === JSON_ERROR_NONE) {
                $this->accessToken = array_key_exists('access_token', $body) ? $body['access_token'] : null;
                $this->storeTokens($body['access_token'], $body['refresh_token'], $body['expires_in'], time() + $body['expires_in']);
            } else {
                throw new ApiException('Could not acquire tokens, json decode failed. Got response: ' . $response->getBody()->getContents());
            }
        } else {
            throw new ApiException('Could not acquire or refresh tokens');
        }
    }

    /**
     * @throws ApiException
     */
    public function acquireAccessToken()
    {
        if (empty($_GET['code'])) {
            $this->authorizeRedirect();
        }

        $code = rawurldecode($_GET['code']);

        $body = [
            'form_params' => [
                'code'          => $code,
                'client_id'     => $this->clientId,
                'client_secret' => $this->clientSecret,
                'redirect_uri'  => $this->redirectUrl,
                'grant_type'    => 'authorization_code',
            ],
        ];

        $response = $this->client()->post($this->getTokenUrl(), $body);

        if ($response->getStatusCode() == 200) {
            Psr7\rewind_body($response);
            $body = json_decode($response->getBody()->getContents(), true);

            if (json_last_error() === JSON_ERROR_NONE) {
                $this->accessToken = array_key_exists('access_token', $body) ? $body['access_token'] : null;
                $this->storeTokens($body['access_token'], $body['refresh_token'], $body['expires_in'], time() + $body['expires_in']);
            } else {
                throw new ApiException('Could not acquire tokens, json decode failed. Got response: ' . $response->getBody()->getContents());
            }
        } else {
            throw new ApiException('Could not acquire or refresh tokens');
        }
    }

    /**
     * @param mixed $redirectUrl
     */
    public function setRedirectUrl($redirectUrl)
    {
        $this->redirectUrl = $redirectUrl;
    }

    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
    }

    public function setClientSecret($clientSecret)
    {
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
     * @param null   $body
     * @param array  $params
     * @param array  $headers
     *
     * @throws InvalidAccessTokenException
     * @return Request
     * @throws ApiException
     */
    private function createRequest($method = 'GET', $endpoint, $body = null, array $params = [], array $headers = [])
    {
        // Add default json headers to the request
        $headers = array_merge($headers, [
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json',
        ]);

        // If access token is not set or token has expired, acquire new token
        if (empty($this->getAccessToken())) {
            throw new InvalidAccessTokenException('Invalid access token, please acquire a new one.');
        }

        // If we have a token, sign the request
        if (! empty($this->getAccessToken())) {
            $headers['Authorization'] = 'Bearer ' . $this->getAccessToken();
        }

        // Create param string
        if (! empty($params)) {
            $endpoint .= '?' . http_build_query($params);
        }

        // Create the request
        $request = new Request($method, $endpoint, $headers, $body);

        return $request;
    }

    /**
     * @param string  $url
     * @param array $params
     * @param bool  $fetchAll
     *
     * @return array|mixed
     * @throws ApiException
     * @throws TooManyRequestsException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws InvalidAccessTokenException
     */
    public function get(string $url, array $params = [], bool $fetchAll = false)
    {
        try {
            if ($fetchAll && !array_keys($params, 'page')) {
                $params['page'] = new Page(100);
            }

            $request  = $this->createRequest('GET', $this->formatUrl($url, 'get'), json_encode($params));
            $response = $this->client()->send($request);

            $json = $this->parseResponse($response);

            if (!$fetchAll) {
                return $json;
            }

            if ($this->hasMoreData($json, $params['page'])) {
                do {
                    $params['page']->next();

                    $nextPageJson = $this->get($url, $params);
                    $json = array_merge_recursive($json, $nextPageJson);
                } while ($this->hasMoreData($nextPageJson, $params['page']));
            }

            return $json;
        } catch (Exception $e) {
            $this->parseExceptionForErrorMessages($e);
        }
    }

    private function hasMoreData(array $json, Page $page): bool
    {
        return count($json['data'] ?? []) === $page->getSize();
    }

    /**
     * @param string $url
     * @param string $body
     *
     * @return mixed
     * @throws ApiException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws InvalidAccessTokenException
     */
    public function post($url, $body)
    {
        try {
            $request  = $this->createRequest('POST', $this->formatUrl($url, 'post'), $body);
            $response = $this->client()->send($request);

            return $this->parseResponse($response);
        } catch (Exception $e) {
            $this->parseExceptionForErrorMessages($e);
        }
    }

    /**
     * @param string $url
     * @param string $body
     *
     * @return mixed
     * @throws ApiException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws InvalidAccessTokenException
     */
    public function patch($url, $body)
    {
        try {
            $request  = $this->createRequest('PATCH', $this->formatUrl($url, 'patch'), $body);
            $response = $this->client()->send($request);

            return $this->parseResponse($response);
        } catch (Exception $e) {
            $this->parseExceptionForErrorMessages($e);
        }
    }

    /**
     * @param string $url
     *
     * @return mixed
     * @throws ApiException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws InvalidAccessTokenException
     */
    public function delete($url)
    {
        try {
            $request  = $this->createRequest('DELETE', $this->formatUrl($url, 'delete'));
            $response = $this->client()->send($request);

            return $this->parseResponse($response);
        } catch (Exception $e) {
            $this->parseExceptionForErrorMessages($e);
        }
    }

    /**
     * @param Response $response
     *
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
     * @throws ApiException
     * @throws TooManyRequestsException
     * @throws InvalidAccessTokenException
     */
    private function parseExceptionForErrorMessages(Exception $exception)
    {
        if ($exception instanceof InvalidAccessTokenException) {
            throw $exception;
        }

        if (! $exception instanceof BadResponseException) {
            throw new ApiException($exception->getMessage());
        }

        $response = $exception->getResponse();
        Psr7\rewind_body($response);
        $responseBody        = $response->getBody()->getContents();
        $decodedResponseBody = json_decode($responseBody, true);

        if (! is_null($decodedResponseBody) && isset($decodedResponseBody['error']['message']['value'])) {
            $errorMessage = $decodedResponseBody['error']['message']['value'];
        } else {
            $errorMessage = $responseBody;
        }

        $this->checkWhetherRateLimitHasBeenReached($response, $errorMessage);

        throw new ApiException('Error ' . $response->getStatusCode() . ': ' . $errorMessage, $response->getStatusCode());
    }

    /**
     * @param ResponseInterface $response
     * @param string            $errorMessage
     *
     * @return void
     *
     * @throws TooManyRequestsException
     */
    private function checkWhetherRateLimitHasBeenReached(ResponseInterface $response, $errorMessage)
    {
        $retryAfterHeaders = $response->getHeader('Retry-After');
        if ($response->getStatusCode() === 429 && count($retryAfterHeaders) > 0) {
            $exception                            = new TooManyRequestsException('Error ' . $response->getStatusCode() . ': ' . $errorMessage, $response->getStatusCode());
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
        return $this->apiUrl . '/' . $url;
    }
}

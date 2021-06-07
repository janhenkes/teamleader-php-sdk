<?php

namespace Teamleader;

use Exception;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7;
use Teamleader\Actions\Attributes\Page;
use Teamleader\Exceptions\Api\TooManyRequestsException;
use Teamleader\Exceptions\ApiException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
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
    private $apiUrl = 'https://api.focus.teamleader.eu';

    /**
     * @var string
     */
    private $authUrl = 'https://focus.teamleader.eu/oauth2/authorize';

    /**
     * @var string
     */
    private $tokenUrl = 'https://focus.teamleader.eu/oauth2/access_token';

    /**
     * @var string
     */
    private $clientId;

    /**
     * @var string
     */
    private $clientSecret;

    /**
     * @var string
     */
    private $accessToken;

    /**
     * @var string
     */
    private $redirectUrl;

    /**
     * @var GuzzleHttpClient
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

    public function __construct(CacheHandlerInterface $cacheHandler = null)
    {
        $this->client();
        $this->cacheHandler = $cacheHandler ?? new DefaultCacheHandler();
    }

    /**
     * @return bool|string
     * @throws ApiException
     */
    public function getAccessToken()
    {
        $accesstoken = $this->cacheHandler->get('accessToken');
        if($accesstoken)
        {
            return $accesstoken;
        }

        $refreshToken = $this->cacheHandler->get('refreshToken');
        if($refreshToken) {
            $this->acquireRefreshToken();

            return $this->getAccessToken();
        }
        return false;
    }

    /**
     * @param string $accessToken
     * @param string $refreshToken
     * @param int $expiresIn Seconds
     * @param int $expiresOn Timestamp
     */
    public function storeTokens(string $accessToken, string $refreshToken, int $expiresIn, int $expiresOn): void
    {
        $this->cacheHandler->set('accessToken', $accessToken, $expiresIn / 60);
        $this->cacheHandler->set('refreshToken', $refreshToken, 60 * 60 * 24 * 365); // a year.
    }

    public function clearTokens(): void
    {
        $this->cacheHandler->forget('accessToken');
        $this->cacheHandler->forget('refreshToken');
        $this->cacheHandler->forget('tokenExpire');
    }

    /**
     * @return GuzzleHttpClient
     */
    private function client(): GuzzleHttpClient
    {
        if ($this->client) {
            return $this->client;
        }

        $handlerStack = HandlerStack::create();
        foreach ($this->middleWares as $middleWare) {
            $handlerStack->push($middleWare);
        }

        $this->client = new GuzzleHttpClient(
            [
                'http_errors' => true,
                'handler' => $handlerStack,
                'expect' => false,
            ]
        );

        return $this->client;
    }

    /**
     * @return string
     */
    private function getAuthUrl(): string
    {
        $queryString = http_build_query(
            [
                'client_id' => $this->clientId,
                'redirect_uri' => $this->redirectUrl,
                'response_type' => 'code',
            ]
        );

        return $this->authUrl . '?' . $queryString;
    }

    private function authorizeRedirect(): void
    {
        $authUrl = $this->getAuthUrl();
        header('Location: ' . $authUrl);
        exit;
    }

    /**
     * @throws ApiException
     */
    private function acquireRefreshToken(): void
    {
        $body = [
            'form_params' => [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'refresh_token' => $this->cacheHandler->get('refreshToken'),
                'grant_type' => 'refresh_token',
            ],
        ];

        $response = $this->client()->post($this->getTokenUrl(), $body);

        if (!$response->getStatusCode() == 200) {
            throw new ApiException('Could not acquire or refresh tokens');
        }

        Psr7\rewind_body($response);
        $body = json_decode($response->getBody()->getContents(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ApiException(
                'Could not acquire tokens, json decode failed. Got response: ' . $response->getBody()->getContents()
            );
        }

        $this->accessToken = array_key_exists('access_token', $body) ? $body['access_token'] : null;
        $this->storeTokens(
            $body['access_token'],
            $body['refresh_token'],
            $body['expires_in'],
            time() + $body['expires_in']
        );
    }

    /**
     * @throws ApiException
     */
    public function acquireAccessToken(): void
    {
        if (empty($_GET['code'])) {
            $this->authorizeRedirect();
        }

        $code = rawurldecode($_GET['code']);

        $body = [
            'form_params' => [
                'code' => $code,
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'redirect_uri' => $this->redirectUrl,
                'grant_type' => 'authorization_code',
            ],
        ];

        $response = $this->client()->post($this->getTokenUrl(), $body);

        if ($response->getStatusCode() != 200) {
            throw new ApiException('Could not acquire or refresh tokens');
        }

        Psr7\rewind_body($response);
        $body = json_decode($response->getBody()->getContents(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ApiException(
                'Could not acquire tokens, json decode failed. Got response: ' . $response->getBody()->getContents()
            );
        }

        $this->accessToken = array_key_exists('access_token', $body) ? $body['access_token'] : null;
        $this->storeTokens(
            $body['access_token'],
            $body['refresh_token'],
            $body['expires_in'],
            time() + $body['expires_in']
        );
    }

    /**
     * @param string $redirectUrl
     */
    public function setRedirectUrl(string $redirectUrl): void
    {
        $this->redirectUrl = $redirectUrl;
    }

    public function setClientId(string $clientId): void
    {
        $this->clientId = $clientId;
    }

    public function setClientSecret(string $clientSecret): void
    {
        $this->clientSecret = $clientSecret;
    }

    /**
     * @return string
     */
    public function getTokenUrl(): string
    {
        return $this->tokenUrl;
    }

    /**
     * @param string $method
     * @param string $endpoint
     * @param string|null|resource|StreamInterface $body
     * @param array $params
     * @param array $headers
     *
     * @throws InvalidAccessTokenException
     * @return Request
     * @throws ApiException
     */
    private function createRequest(
        string $method = 'GET',
        string $endpoint,
        $body = null,
        array $params = [],
        array $headers = []
    ): Request {
        // Add default json headers to the request
        $headers = array_merge(
            $headers,
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ]
        );

        // If access token is not set or token has expired, acquire new token
        if (empty($this->getAccessToken())) {
            throw new InvalidAccessTokenException('Invalid access token, please acquire a new one.');
        }

        // If we have a token, sign the request
        if (!empty($this->getAccessToken())) {
            $headers['Authorization'] = 'Bearer ' . $this->getAccessToken();
        }

        // Create param string
        if (!empty($params)) {
            $endpoint .= '?' . http_build_query($params);
        }

        // Create the request
        return new Request($method, $endpoint, $headers, $body);
    }

    /**
     * @param string $url
     * @param array $params
     * @param bool $fetchAll
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

            $request = $this->createRequest('GET', $this->formatUrl($url, 'get'), json_encode($params));
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
     * @param string|null|resource|StreamInterface $body
     *
     * @return mixed
     * @throws ApiException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws InvalidAccessTokenException
     */
    public function post(string $url, $body)
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
     * @param string|null|resource|StreamInterface $body
     *
     * @return mixed
     * @throws ApiException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws InvalidAccessTokenException
     */
    public function patch(string $url, $body)
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
     *
     * @return mixed
     * @throws ApiException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws InvalidAccessTokenException
     */
    public function delete(string $url)
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
     *
     * @return mixed
     * @throws ApiException
     */
    private function parseResponse(Response $response)
    {
        try {
            Psr7\rewind_body($response);

            return json_decode($response->getBody()->getContents(), true);
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
    private function parseExceptionForErrorMessages(Exception $exception): void
    {
        if ($exception instanceof InvalidAccessTokenException) {
            throw $exception;
        }

        if (!$exception instanceof BadResponseException) {
            throw new ApiException($exception->getMessage());
        }

        $response = $exception->getResponse();
        Psr7\rewind_body($response);
        $errorMessage = $response->getBody()->getContents();
        $decodedResponseBody = json_decode($errorMessage, true);

        if (!is_null($decodedResponseBody) && isset($decodedResponseBody['error']['message']['value'])) {
            $errorMessage = $decodedResponseBody['error']['message']['value'];
        }

        $this->checkWhetherRateLimitHasBeenReached($response, $errorMessage);

        throw new ApiException(
            'Error ' . $response->getStatusCode() . ': ' . $errorMessage, $response->getStatusCode()
        );
    }

    /**
     * @param ResponseInterface $response
     * @param string $errorMessage
     *
     * @return void
     *
     * @throws TooManyRequestsException
     */
    private function checkWhetherRateLimitHasBeenReached(ResponseInterface $response, string $errorMessage): void
    {
        $retryAfterHeaders = $response->getHeader('Retry-After');
        if ($response->getStatusCode() === 429 && count($retryAfterHeaders) > 0) {
            $exception = new TooManyRequestsException(
                'Error ' . $response->getStatusCode() . ': ' . $errorMessage,
                $response->getStatusCode()
            );
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
    private function formatUrl(string $url, string $method = 'get'): string
    {
        return $this->apiUrl . '/' . $url;
    }
}

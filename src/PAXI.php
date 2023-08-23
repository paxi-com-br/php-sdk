<?php

namespace PAXI\SDK;

class PAXI
{
    /**
     * @var HTTPClient
     */
    private $client;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $apiSecret;

    /**
     * @var string|null
     */
    private $accessToken;

    /**
     * @var string|null
     */
    private $refreshToken;

    /**
     * @param string $apiKey
     * @param string $apiSecret
     * @param string|null $accessToken
     */
    public function __construct($apiKey, $apiSecret, $accessToken = null)
    {
        $this->client = new HTTPClient($this->accessToken);
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->accessToken = $accessToken;

        if (!$accessToken) {
            $this->oauthToken();
        }

        $this->client->setAuthorization($this->accessToken, "Bearer");
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function oauthToken()
    {
        $res = $this->client->request("/api/v1/oauth/token", "POST", [
            "grant_type" => "client_credentials",
            "api_key" => $this->apiKey,
            "api_secret" => $this->apiSecret
        ]);

        if (!isset($res["success"]) || $res["success"] !== true) {
            throw new \Exception($res["message"] ?? "Access denied");
        }

        $this->accessToken = $res["access_token"];
        $this->refreshToken = $res["refresh_token"];
        return true;
    }

    /**
     * @return bool
     */
    public function refreshToken()
    {
        $res = $this->client->request("/api/v1/oauth/token", "POST", [
            "grant_type" => "refresh_token",
            "refresh_token" => $this->refreshToken
        ]);

        if (!isset($res["success"]) || $res["success"] !== true) {
            throw new \Exception($res["message"] ?? "Access denied");
        }

        $this->accessToken = $res["access_token"];
        $this->refreshToken = $res["refresh_token"];
        return true;
    }

    /**
     * @return HTTPClient
     */
    public function withApi()
    {
        return $this->client;
    }
}

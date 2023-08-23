<?php

namespace PAXI\SDK\Support;

class HMACSupport
{
    /**
     * @return string|false
     */
    public static function getFromHTTPHeader()
    {
        $commom = array(
            'HTTP_CONTENT_HMAC',
            'HTTP_CONTENT-HMAC',
            'HTTP_CONTENT_Hmac',
            'CONTENT_HMAC',
            'CONTENT-HMAC',
            'Content_Hmac'
        );
    
        foreach ($commom as $header) {
            if (isset($_SERVER[$header])) {
                return $_SERVER[$header];
            }
        }
    
        return false;
    }

    /**
     * @param string $payload
     * @param string $secret
     * @return string
     */
    public static function generateHash($payload, $secret)
    {
        return hash_hmac('sha256', $payload, $secret);
    }
    
    /**
     * @param string $received
     * @param string $payload
     * @param string $secret
     * @return bool
     */
    public static function verifyHash($received, $payload, $secret)
    {
        return $received === self::generateHash($payload, $secret);
    }
}

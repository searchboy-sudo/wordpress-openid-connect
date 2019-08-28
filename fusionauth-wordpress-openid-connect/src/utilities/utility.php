<?php

namespace fusionauth\openidconnect\src\utilities;

use http\Encoding\Stream;

class utility
{
    public static function getCurrentUrl()
    {
        global $wp;
        return add_query_arg( home_url( $wp->request ), $wp->query_vars  );
    }

    public  static function HttpPost($url, $headers = array(), $body = array())
    {
        $ch = curl_init( $url );
        curl_setopt( $ch, CURLOPT_POST, 1);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt( $ch, CURLOPT_HEADER, 0);
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt( $ch, CURLOPT_BINARYTRANSFER, 1);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec( $ch );
        curl_close($ch);
        return $response;
    }

    public static function generateRandomAlphaNumeric(int $length = 20)
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      return self::generate_string($permitted_chars, $length);
    }

    public static function generateRandomAlphaNumericAndStore(int $length = 20)
    {
        $randomValue = self::generateRandomAlphaNumeric($length);
        return $randomValue;
    }

    private static function generate_string($input, $strength = 16) {
        $input_length = strlen($input);
        $random_string = '';
        for($i = 0; $i < $strength; $i++) {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }

        return $random_string;
    }

    public static function generateNonce()
    {
       return self::generateRandomNumbers();
    }

    public static function generateRandomNumbers(){
        return bin2hex(random_bytes(32));
    }

    public static function setAppCookie(string $cookieid, string $cookieValue, $expiration)
    {
        $_COOKIE[$cookieid] = $cookieValue;
        setcookie( $cookieid, $cookieValue, $expiration, COOKIEPATH, COOKIE_DOMAIN );
    }

    public static function getAppCookie(string $cookieid)
    {
        $cookieValue = "";
        if(isset($_COOKIE[$cookieid])){
            $cookieValue = $_COOKIE[$cookieid];
        }
        return $cookieValue;
    }

    public static function clearAppCookie(string $cookieid)
    {
        setcookie( $cookieid, ' ', time() - YEAR_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
    }

    public static function getSubDomain($url)
    {
        $urlparts = parse_url($url);
        $domain = $urlparts['host'];
        return $domain;
    }

    public static function getRootDomain()
    {
        $urlparts = parse_url(site_url());
        $domain = $urlparts['host'];
        $domainparts = explode(".", $domain);
        $domain = $domainparts[count($domainparts)-2] . "." . $domainparts[count($domainparts)-1];
        return $domain;
    }

    public static function getWildCardDomain()
    {
        return "*.". self::getRootDomain();
    }

    public static function getQuery(string $query)
    {
        if(isset($_GET[$query])) {
            return $_GET[$query];
        }

    }

    public static function getPost(string $query)
    {
        if(isset($_POST[$query])) {
            return $_POST[$query];
        }

    }

}
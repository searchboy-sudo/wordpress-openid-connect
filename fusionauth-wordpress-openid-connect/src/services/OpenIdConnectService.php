<?php
namespace fusionauth\openidconnect\src\services;

use fusionauth\openidconnect\src\data as ds;
use fusionauth\openidconnect\src\models as ms;
use fusionauth\openidconnect\src\utilities as us;
use fusionauth\openidconnect\src\constants as cs;
use http\Cookie;

require_once(__DIR__.'/../data/ClientConnectSettingsRepository.php');
require_once(__DIR__.'/../models/ClientConnectSettings.php');
require_once(__DIR__.'/../models/IdentityUser.php');
require_once(__DIR__.'/../utilities/utility.php');
require_once(__DIR__.'/../constants/PluginConstants.php');

class OpenIdConnectService
{
    private $code;
    private $state;
    private $nonce;
    private $userState;
    private $accessToken;
    private $expiresIn;
    private $scope;
    private $tokenType;
    private $clientConnectSettings;
    private $openIdServerConfig;
    private $redirect_to;
    private $isConfigurationEnabled;

    public function __construct()
    {
        $this->Initialization();
    }

    public function StartAuthentication()
    {
        if(!$this->userState =="Authenticated")
        {
            $this->Authenticate();
        }

        if($this->userState =="Authenticated")
        {
            $state = us\utility::getAppCookie(cs\PluginConstants::oauthstate);
            us\utility::clearAppCookie(cs\PluginConstants::oauthstate);
            if($this->state == $state)
            {
                $this->Authorize();
                $this->LoginUser();
            }
        }
    }

    private function Initialization()
    {
        $this->code = us\utility::getQuery('code');
        $this->userState = us\utility::getQuery('userState');
        $this->state = us\utility::getQuery('state');
        $this->nonce = us\utility::getQuery('nonce');
        $this->accessToken = us\utility::getQuery('access_token');
        $this->expiresIn = us\utility::getQuery('expires_in');
        $this->scope = us\utility::getQuery('scope');
        $this->tokenType = us\utility::getQuery('token_type');
        $this->redirect_to = us\utility::getQuery('redirect_to');

        $this->clientConnectSettings = $this->GetConnectSettings();
        if(!$this->clientConnectSettings->getId() == null)
        {
            $this->GetWellKnownConfiguration();
            $this->isConfigurationEnabled = true;
        }else{
            $this->isConfigurationEnabled = false;
        }

    }

    private function Authenticate()
    {
        $authenticationUrl = $this->GenerateAuthenticationUrl();
        wp_redirect($authenticationUrl);
        exit;
    }

    private function GenerateAuthenticationUrl():string
    {
        $state = us\utility::generateRandomNumbers();
        us\utility::setAppCookie(cs\PluginConstants::oauthstate, $state, time()+300);
        us\utility::setAppCookie(cs\PluginConstants::redirectUrl, $this->redirect_to == null ? '': $this->redirect_to, time()+300);

        $data = array();
        $nonce = us\utility::generateNonce();
        if(!$this->clientConnectSettings->getIsImplicitGrant() == "1")
        {
            $data = array(
                'client_id' => rtrim($this->clientConnectSettings->getClientId()),
                'response_type' => 'code',
                'redirect_uri' => $this->clientConnectSettings->getRedirectUri(),
                'state' => $state
            );
        }else{
            $data = array(
                'client_id' => rtrim($this->clientConnectSettings->getClientId()),
                'response_type' => 'id_token',
                'redirect_uri' => $this->clientConnectSettings->getRedirectUri(),
                'scope' => $this->clientConnectSettings->getScopes(),
                'nonce' => $nonce,
                'state' => $state
            );
        }

        $url = $this->openIdServerConfig->authorization_endpoint.'?'.http_build_query($data);
        return $url;
    }

    private function Authorize()
    {
        $headers = array(
            'POST' => '/oauth2/token HTTP/1.1',
            'Host' =>  us\utility::getSubDomain($this->clientConnectSettings->getOpenIdServerUrl()),
            'Content-type' => 'application/x-www-form-urlencoded',
            'Accept' => '*',
            'Content-length' => 436
        );

        $body = array();
        $body = array(
            'client_id' => rtrim($this->clientConnectSettings->getClientId()),
            'client_secret' => rtrim($this->clientConnectSettings->getClientSecret()),
            'code' => $this->code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->clientConnectSettings->getRedirectUri(),
            'state' => $this->state
        );

        $response = us\utility::HttpPost($this->openIdServerConfig->token_endpoint, $headers, $body);
        $accessTokenObject = json_decode($response);
        if(property_exists($accessTokenObject, "access_token"))
        {
            $this->accessToken = $accessTokenObject->access_token;
        }else{
            echo 'No access token';
;        }
        if(!$this->accessToken == null){
            us\utility::setAppCookie(cs\PluginConstants::accessToken, $this->accessToken, time()+3600);
        }
    }

    private function LoginUser()
    {
        $identityUser = $this->GetUserDetails();
        if($identityUser == null)
        {
            exit;
        }
        $userid = $this->GetUseridFromLocal($identityUser);

        if($userid == null)
        {
            $userid = $this->SaveUser($identityUser);
        }

        wp_clear_auth_cookie();
        wp_set_current_user ( $userid );
        wp_set_auth_cookie  ( $userid );

        if ( is_user_logged_in() ) {
            $currentRedirectUri = us\utility::getAppCookie(cs\PluginConstants::redirectUrl);
            if(strlen($currentRedirectUri) < 1){
                $currentRedirectUri = $this->clientConnectSettings->getRedirectUri();
            }
            us\utility::clearAppCookie(cs\PluginConstants::redirectUrl);
            us\utility::setAppCookie(cs\PluginConstants::userIsLoggedIn, 'true', time()+3600);
            wp_redirect( $currentRedirectUri );
            exit;
        }
    }

    private function GetUserIdFromLocal(ms\IdentityUser $identityUser)
    {
        global $wpdb;
        $sql = "select ID from ".$wpdb->base_prefix."users where user_email = '". $identityUser->getEmail()."';";
        $results = $wpdb->get_results($sql);
       return $results[0]->ID;
    }

    private function SaveUser(ms\IdentityUser $identityUser)
    {
        $user_id = wp_insert_user(array(
            'user_login' => $identityUser->getEmail(),
            'user_email' => $identityUser->getEmail(),
            'first_name' => $identityUser->getFirstName(),
            'last_name' => $identityUser->getLastName(),
            'role' => 'subscriber',
            'user_pass' => wp_generate_password()
        ));

        return $user_id;
    }

    public function ForceWpLogin()
    {
        us\utility::setAppCookie(cs\PluginConstants::forcewplogin, 'true', time()+300);

    }

    public function IsWpLogin()
    {
       $forceWpLogin = us\utility::getAppCookie(cs\PluginConstants::forcewplogin);
       us\utility::clearAppCookie(cs\PluginConstants::forcewplogin);
       if($forceWpLogin == 'true'){
           return true;
       }else{
           return false;
       }

    }

    public function GetForceWpLoginQuery()
    {
       return $this->clientConnectSettings->getForceWPLoginQuery();
    }

    public function IsEnabled()
    {
        return $this->isConfigurationEnabled;
    }

    public function HasOauthSession()
    {
        $oathsession = us\utility::getAppCookie(cs\PluginConstants::userIsLoggedIn);
        if($oathsession == 'true'){
            return true;
        }else{
            return false;
        }
    }

    private function GetUserDetails()
    {
        $headers = array(
            'Authorization' => "Bearer $this->accessToken"
        );
        $args = array(
            'headers' => $headers
        );
        $response = wp_remote_get($this->openIdServerConfig->userinfo_endpoint, $args);
        if(!($response['response']['message'] == "OK"))
        {
           return null;
        }
        $decoded = json_decode($response['body'],True);
        $identityUser = new ms\IdentityUser();
        $identityUser->populateFields($decoded);
        return $identityUser;
    }

    private function GenerateOauthUrl(string $authurlType):string
    {
        $url = $this->clientConnectSettings->getOpenIdServerUrl().'/oauth2/'.$authurlType;
        return $url;
    }

    private function GetConnectSettings():ms\ClientConnectSettings
    {
        $clientConnectSettingsRepository = new ds\ClientConnectSettingsRepository();
        $settings = null;
        if(count($clientConnectSettingsRepository->getDefaultSettings()) > 0)
        {
            $settings = $clientConnectSettingsRepository->getDefaultSettings()[0];
        }

        if($settings == null)
        {
            $settings = new ms\ClientConnectSettings();
        }
        return $settings;
    }

    public function Logout()
    {
        $logoutUrl = $this->GenerateOauthUrl('logout');

        $data = array(
            'client_id' => rtrim($this->clientConnectSettings->getClientId())
        );

        $url = $logoutUrl.'?'.http_build_query($data);
        us\utility::clearAppCookie(cs\PluginConstants::userIsLoggedIn);
        wp_redirect($url);
        wp_destroy_current_session();
        wp_clear_auth_cookie();
        exit;
    }

    private function GetWellKnownConfiguration()
    {
        $url = $this->clientConnectSettings->getOpenIdServerUrl().'/.well-known/openid-configuration';
        $response = wp_remote_get($url);
        $decoded = json_decode($response['body']);
        $this->openIdServerConfig = $decoded;
    }

}
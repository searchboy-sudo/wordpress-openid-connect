<?php


namespace fusionauth\openidconnect\src\models;


class ClientConnectSettings
{
    private $id;
    private $appName;
    private $openIdServerUrl;
    private $clientId;
    private $clientSecret;
    private $redirectUri;
    private $scopes;
    private $insertTime;
    private $updateTime;
    private $isImplicitGrant;
    private $isDefault;
    private $forceWPLoginQuery;

    function setMembers($id,
                        $appName,
                        $openIdServerUrl,
                        $clientId,
                        $clientSecret,
                        $redirectUri,
                        $isImplicitGrant,
                        $scopes,
                        $insertTime,
                        $updateTime,
                        $isDefault,
                        $forceWPLoginQuery){
        $this->id = $id;
        $this->appName = $appName;
        $this->openIdServerUrl = $openIdServerUrl;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUri = $redirectUri;
        $this->scopes = $scopes;
        $this->insertTime = $insertTime;
        $this->updateTime = $updateTime;
        $this->isImplicitGrant = $isImplicitGrant;
        $this->isDefault = $isDefault;
        $this->forceWPLoginQuery = $forceWPLoginQuery;
    }

    function setId($id){
        $this->id = $id;
    }

    function getId(){
        return $this->id;
    }

    function setAppName($appName){
        $this->appName = $appName;
    }

    function getAppName(){
        return $this->appName;
    }

    function setOpenIdServerUrl($openIdServerUrl){
        $this->openIdServerUrl = $openIdServerUrl;
    }

    function getOpenIdServerUrl(){
        return $this->openIdServerUrl;
    }

    function setClientId($clientId){
        $this->clientId = $clientId;
    }

    function getClientId(){
        return $this->clientId;
    }

    function setClientSecret($clientSecret){
        $this->clientSecret = $clientSecret;
    }

    function getClientSecret(){
        return $this->clientSecret;
    }

    function setRedirectUri($redirectUri){
        $this->redirectUri = $redirectUri;
    }

    function getRedirectUri(){
        return $this->redirectUri;
    }

    function setScopes($scopes){
        $this->scopes = $scopes;
    }

    function getScopes(){
        return $this->scopes;
    }

    function setIsImplicitGrant($isImplicitGrant){
        $this->isImplicitGrant = $isImplicitGrant;
    }

    function getIsImplicitGrant(){
        return $this->isImplicitGrant;
    }

    function getCheckBoxIsImplicitGrant(){
        if($this->isImplicitGrant == 1){
            return 'checked';
        }else{
            return '';
        }
    }

    function setIsDefault($isDefault){
        $this->isDefault = $isDefault;
    }

    function getIsDefault(){
        return $this->isDefault;
    }

    function getInsertTime(){
        return $this->insertTime;
    }

    function getUpdateTime(){
        return $this->updateTime;
    }

    function setForceWPLoginQuery($forceWPLoginQuery){
        $this->forceWPLoginQuery = $forceWPLoginQuery;
    }

    function getForceWPLoginQuery(){
        return $this->forceWPLoginQuery;
    }
}
?>
<?php


namespace fusionauth\openidconnect\src\processors;

use fusionauth\openidconnect\src\processors\interfaces as pi;
use fusionauth\openidconnect\src\utilities as us;
use fusionauth\openidconnect\src\services as ss;

require_once(__DIR__.'/../services/OpenIdConnectService.php');
require_once(__DIR__.'/../utilities/utility.php');
require_once (__DIR__.'/interfaces/IProcessor.php');

class AuthenticationProcessor implements pi\IProcessor
{
    private $forceWPLoginQuery;
    private $connectService;
    private $isEnabled;

    public function __construct(){
        $this->connectService = new ss\OpenIdConnectService();
        $this->isEnabled = $this->connectService->IsEnabled();
        $this->forceWPLoginQuery = $this->connectService->GetForceWpLoginQuery();
    }

    public function Process()
    {

    }

    public function ProcessLogin()
    {
        if (!isset($_GET[$this->forceWPLoginQuery])) {
            if (!is_user_logged_in()){
                try{
                    $this->ProcessAuthentication();
                } catch (\Exception $e) {
                    error_log($e->getMessage());
                }
            }
        }
    }

    public function EnForceWPLogin()
    {
        if ($this->isEnabled == true && isset($_GET[$this->forceWPLoginQuery])) {
            if (!is_user_logged_in()){
                try{
                    $this->connectService->ForceWpLogin();
                } catch (\Exception $e) {
                    error_log($e->getMessage());
                }
            }
        }
    }

    public function ProcessLoggedInUser()
    {
        try {
            //$userState = $_GET['userState'];
            $userState = us\utility::getQuery('userState');
            if ($this->isEnabled == true && ($userState == 'Authenticated')) {
                $this->ProcessAuthentication();
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
    }

    public function LoginIfUserHasOauthSession()
    {
        try
        {
            if ($this->isEnabled == true && $this->connectService->HasOauthSession() && ! is_user_logged_in()) {
                $this->ProcessAuthentication();
            }
        }catch (\Exception $e) {
            error_log($e->getMessage());
        }
    }

    public function ProcessLogout()
    {
        if ( $this->isEnabled == true && is_user_logged_in() ) {
            $this->connectService->Logout();
        }
    }

    private function ProcessAuthentication()
    {
        if ($this->isEnabled == true && ! is_user_logged_in() ) {
            if(!$this->connectService->IsWpLogin()){
                $this->connectService->StartAuthentication();
            }
        }
    }


}
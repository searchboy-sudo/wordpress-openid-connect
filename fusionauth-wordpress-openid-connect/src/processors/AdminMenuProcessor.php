<?php


namespace fusionauth\openidconnect\src\processors;

use fusionauth\openidconnect\src\processors\interfaces as pi;

require_once (__DIR__.'/interfaces/IProcessor.php');

class AdminMenuProcessor implements pi\IProcessor
{
    function  __construct() {

    }

    public function Process()
    {
        add_action('admin_menu', array($this,'addMenu'));
    }

    public function addMenu()
    {
        add_menu_page( 'FusionAuth SSO Client',
            'FusionAuth SSO Client',
            'manage_options',
            'fusionauth_wordpress_openid_connect',
            array($this,'adminIndex'),
            'dashicons-id',
            110);
    }

    public function adminIndex()
    {
        require_once(__DIR__.'/../templates/add-client-connect-settings.php');
    }
}
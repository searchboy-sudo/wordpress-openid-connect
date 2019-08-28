<?php


namespace fusionauth\openidconnect\src\processors;

use fusionauth\openidconnect\src\data as ds;
use fusionauth\openidconnect\src\processors\interfaces as pi;

require_once(__DIR__.'/../data/Db_Objects.php');
require_once (__DIR__.'/interfaces/IProcessor.php');

class ActivationProcessor implements pi\IProcessor
{
    private $db;

    function  __construct() {
        $this->db = new ds\Db_Objects();
    }

    public function Process()
    {
        $this->runDbOperations();
        $this->addAdminPages();
    }

    private function runDbOperations()
    {
        try {
            $this->db->create_tables();
        } catch (\Exception $e) {
            error_log($e->getMessage(), 0);
        }
    }

    private function addAdminPages()
    {
        add_menu_page( 'FusionAuth SSO Client',
            'FusionAuth SSO Client',
            'manage_options',
            'fusionauth_wordpress_openid_connect',
            array($this,'admin_index'),
                'dashicons-id',
            110);
    }

    private function admin_index (){

    }
}
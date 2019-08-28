<?php


namespace fusionauth\openidconnect\src\processors;

use fusionauth\openidconnect\src\data as ds;
use fusionauth\openidconnect\src\processors\interfaces as pi;

require_once(__DIR__.'/../data/Db_Objects.php');
require_once (__DIR__.'/interfaces/IProcessor.php');

class DeactivationProcessor implements pi\IProcessor
{
    private $db;

    function  __construct() {
        $this->db = new ds\Db_Objects();
    }

    public function Process()
    {
        $this->runDbOperations();
    }

    private function runDbOperations()
    {
        try {
            $this->db->drop_tables();
        } catch (\Exception $e) {
            error_log($e->getMessage(), 0);
        }
    }
}
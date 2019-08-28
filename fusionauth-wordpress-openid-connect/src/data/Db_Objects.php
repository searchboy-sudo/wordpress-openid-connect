<?php

namespace fusionauth\openidconnect\src\data;

use fusionauth\openidconnect\src\constants as cs;

require_once(__DIR__ . '/../constants/PluginConstants.php');

class Db_Objects
{
    public function create_tables()
    {
       $this->createClientConnectionSettings();
    }

    private function createClientConnectionSettings()
    {
        global $wpdb;

        $sql = "CREATE TABLE IF NOT EXISTS ".$wpdb->base_prefix.cs\PluginConstants::tblClientConnectSettings." (
            id INT NOT NULL AUTO_INCREMENT, 
            app_name VARCHAR(255), 
            openid_server_url VARCHAR(255), 
            client_id VARCHAR(255),
            client_secret VARCHAR(255), 
            redirect_uri VARCHAR(255), 
            scopes VARCHAR(255), 
            is_implicit_grant tinyint(1) DEFAULT '0',
            is_default tinyint(1) DEFAULT '0',
            force_wp_login_query VARCHAR(255),
            insert_time DATETIME DEFAULT CURRENT_TIMESTAMP, 
            update_time DATETIME ON UPDATE CURRENT_TIMESTAMP, 
            PRIMARY KEY ( id )
        ); ";
        $wpdb->query($sql);
    }

    public function drop_tables()
    {
       $this->drop_ClientConnectionSettings();
    }

    private function drop_ClientConnectionSettings()
    {
        global $wpdb;
        $sql = "DROP TABLE IF EXISTS ".$wpdb->base_prefix.cs\PluginConstants::tblClientConnectSettings.";";
        $wpdb->query($sql);
    }
}
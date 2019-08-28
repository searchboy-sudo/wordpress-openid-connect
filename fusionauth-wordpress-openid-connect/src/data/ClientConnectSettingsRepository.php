<?php

namespace fusionauth\openidconnect\src\data;

use fusionauth\openidconnect\src\constants as fc;
use fusionauth\openidconnect\src\models as fm;

require_once(__DIR__ . '/../constants/PluginConstants.php');
require_once(__DIR__.'/../models/ClientConnectSettings.php');

class ClientConnectSettingsRepository
{
    public function insertSettings(fm\ClientConnectSettings $settings)
    {

        global $wpdb;
        try
        {
            $wpdb->insert($wpdb->base_prefix.fc\PluginConstants::tblClientConnectSettings,
                array(
                    'app_name' => $settings->getAppName(),
                    'openid_server_url' => $settings->getOpenIdServerUrl(),
                    'client_id' => $settings->getClientId(),
                    'client_secret' => $settings->getClientSecret(),
                    'redirect_uri' => $settings->getRedirectUri(),
                    'is_implicit_grant' => $settings->getIsImplicitGrant(),
                    'scopes' => $settings->getScopes(),
                    'force_wp_login_query' => $settings->getForceWPLoginQuery()
                ));
        }
        catch (\Exception $e){
            echo $e->getMessage();
        }

    }

    public function updateSettings(fm\ClientConnectSettings $settings)
    {
        global $wpdb;
        try
        {
            $wpdb->update(
                $wpdb->base_prefix.fc\PluginConstants::tblClientConnectSettings,
                array(
                    'app_name' => $settings->getAppName(),
                    'openid_server_url' => $settings->getOpenIdServerUrl(),
                    'client_id' => $settings->getClientId(),
                    'client_secret' => $settings->getClientSecret(),
                    'redirect_uri' => $settings->getRedirectUri(),
                    'is_implicit_grant' => $settings->getIsImplicitGrant(),
                    'scopes' => $settings->getScopes(),
                    'force_wp_login_query' => $settings->getForceWPLoginQuery()
                ),
                array( 'id' => $settings->getId() ) );
        }
        catch (\Exception $e){
            echo $e->getMessage();
        }

    }

    public function getSettings()
    {
        $settings = [];
        global $wpdb;
        $sql = "SELECT * FROM " .$wpdb->base_prefix.fc\PluginConstants::tblClientConnectSettings;
        $results = $wpdb->get_results ($sql);
        foreach ($results as $result) {

            $settingItem = new fm\ClientConnectSettings();
            $settingItem->setMembers($result->id,
                $result->app_name,
                $result->openid_server_url,
                $result->client_id,
                $result->client_secret,
                $result->redirect_uri,
                $result->is_implicit_grant,
                $result->scopes,
                $result->insert_time,
                $result->update_time,
                $result->is_default,
                $result->force_wp_login_query);
            $settings[] = $settingItem;
        }

        return $settings;
    }

    public function getSettingsByClientId($clientId = 1)
    {
        $settings = [];
        global $wpdb;
        $sql = "SELECT * FROM " .$wpdb->base_prefix.fc\PluginConstants::tblClientConnectSettings ." WHERE id = ". $clientId;
        $results = $wpdb->get_results ($sql);
        foreach ($results as $result) {

            $settingItem = new fm\ClientConnectSettings();
            $settingItem->setMembers($result->id,
                $result->app_name,
                $result->openid_server_url,
                $result->client_id,
                $result->client_secret,
                $result->redirect_uri,
                $result->is_implicit_grant,
                $result->scopes,
                $result->insert_time,
                $result->update_time,
                $result->is_default,
                $result->force_wp_login_query);
            $settings[] = $settingItem;
        }

        return $settings;
    }

    public function getSettingsById($id = 1)
    {
        $settings = [];
        global $wpdb;
        $sql = "SELECT * FROM " .$wpdb->base_prefix.fc\PluginConstants::tblClientConnectSettings ." WHERE id = ". $id;
        $results = $wpdb->get_results ($sql);
        foreach ($results as $result) {

            $settingItem = new fm\ClientConnectSettings();
            $settingItem->setMembers($result->id,
                $result->app_name,
                $result->openid_server_url,
                $result->client_id,
                $result->client_secret,
                $result->redirect_uri,
                $result->is_implicit_grant,
                $result->scopes,
                $result->insert_time,
                $result->update_time,
                $result->is_default,
                $result->force_wp_login_query);
            $settings[] = $settingItem;
        }

        return $settings;
    }

    public function getDefaultSettings()
    {
        $settings = [];
        global $wpdb;
        $sql = "SELECT * FROM " .$wpdb->base_prefix.fc\PluginConstants::tblClientConnectSettings ." WHERE is_default = 1";
        $results = $wpdb->get_results ($sql);
        foreach ($results as $result) {

            $settingItem = new fm\ClientConnectSettings();
            $settingItem->setMembers($result->id,
                $result->app_name,
                $result->openid_server_url,
                $result->client_id,
                $result->client_secret,
                $result->redirect_uri,
                $result->is_implicit_grant,
                $result->scopes,
                $result->insert_time,
                $result->update_time,
                $result->is_default,
                $result->force_wp_login_query);
            $settings[] = $settingItem;
        }

        return $settings;
    }

    public function deleteSettings($id)
    {
        global $wpdb;
        $wpdb->delete($wpdb->base_prefix.fc\PluginConstants::tblClientConnectSettings, array('id' => $id));
    }

    public function setAsdefault($id)
    {
        global $wpdb;

        $sql = "update ".$wpdb->base_prefix.fc\PluginConstants::tblClientConnectSettings." set is_default = 0";
        $wpdb->query($sql);

        $wpdb->update($wpdb->base_prefix.fc\PluginConstants::tblClientConnectSettings,
            array('is_default' => 1),
            array('id' => $id));
    }
}
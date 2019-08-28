<?php
use fusionauth\openidconnect\src\data as ds;
use fusionauth\openidconnect\src\models as ms;
use fusionauth\openidconnect\src\utilities as us;
require_once(__DIR__.'/../data/ClientConnectSettingsRepository.php');
require_once(__DIR__.'/../models/ClientConnectSettings.php');
require_once(__DIR__.'/../utilities/utility.php');

defined( 'ABSPATH' ) || exit;

$currentSettings = new ms\ClientConnectSettings();
global $wp;
$scheme = "http";
if(is_ssl()){
    $scheme = "https";
}
$formAction =  home_url(add_query_arg(array(), $wp->request), $scheme).add_query_arg( home_url(), $wp->query_vars );
$queryId = us\utility::getQuery('connectsettingid');
$queryOperation = us\utility::getQuery('operation');
$postId = us\utility::getPost('connectsettingid');
$postOperation = us\utility::getPost('operation');
$id = '';
$operation = '';
$serverReferer = '';
if (isset($_SERVER['HTTP_REFERER'])){
    $serverReferer = $_SERVER['HTTP_REFERER'];
}

if(strlen($queryOperation) > 0)
{
    $id = $queryId;
    $operation = $queryOperation;
}

if(strlen($postOperation) > 0)
{
    $id = $postId;
    $operation = $postOperation;
}
if (strlen($operation) > 0) {
    $clientRepo = new ds\ClientConnectSettingsRepository();
    if ($operation == 'edit') {
        $retrievedSettings = $clientRepo->getSettingsById((int)$id);
        $currentSettings->setId($retrievedSettings[0]->getId());
        $currentSettings->setAppName($retrievedSettings[0]->getAppName());
        $currentSettings->setOpenIdServerUrl($retrievedSettings[0]->getOpenIdServerUrl());
        $currentSettings->setClientId($retrievedSettings[0]->getClientId());
        $currentSettings->setClientSecret($retrievedSettings[0]->getClientSecret());
        $currentSettings->setRedirectUri($retrievedSettings[0]->getRedirectUri());
        $currentSettings->setIsImplicitGrant($retrievedSettings[0]->getIsImplicitGrant());
        $currentSettings->setScopes($retrievedSettings[0]->getScopes());
        $currentSettings->setForceWPLoginQuery($retrievedSettings[0]->getForceWPLoginQuery());

    }
    if ($operation == 'delete') {
        $clientRepo->deleteSettings($id);
        header('Location: ' . $serverReferer);
    }
    if ($operation == 'save') {
        $appName = us\utility::getPost('appname');
        if (strlen($appName) > 0) {
            $openidserverurl = us\utility::getPost('openidserverurl');
            $clientid = us\utility::getPost('clientid');
            $clientsecret = us\utility::getPost('clientsecret');
            $redirecturi = us\utility::getPost('redirecturi');
            $scopes = us\utility::getPost('scopes');
            $implicitgrant = 0;
            $forcewploginquery = us\utility::getPost('forcewploginquery');
            if (isset($_POST['implicitgrant'])) {
                $implicitgrant = 1;
            }

            $clientConnectSettings = new \fusionauth\openidconnect\src\models\ClientConnectSettings();
            $clientConnectSettings->setAppName($appName);
            $clientConnectSettings->setOpenIdServerUrl($openidserverurl);
            $clientConnectSettings->setClientId($clientid);
            $clientConnectSettings->setClientSecret($clientsecret);
            $clientConnectSettings->setRedirectUri($redirecturi);
            $clientConnectSettings->setScopes($scopes);
            $clientConnectSettings->setIsImplicitGrant($implicitgrant);
            $clientConnectSettings->setForceWPLoginQuery($forcewploginquery);

            if (strlen($id) > 0 && (int)$id > 0) {
                $clientConnectSettings->setId($id);
                $clientRepo->updateSettings($clientConnectSettings);
            } else {
                $clientRepo->insertSettings($clientConnectSettings);
            }
            $arr_params = array('operation', 'connectsettingid');
            $serverReferer = esc_url(remove_query_arg($arr_params, $serverReferer));
            header('Location: ' . $serverReferer);
        }
    }
    if ($operation == 'enable') {
        $clientConnectSettings = $clientRepo->getSettingsById((int)$id);
        if ($clientConnectSettings[0]->getIsDefault() == true) {
            $clientRepo->setAsdefault('0');
        } else {
            $clientRepo->setAsdefault($id);
        }
        header('Location: ' . $serverReferer);
    }
}
?>
<div class="container">

    <div class="jumbotron">
        <h1>OpenID Connect Settings</h1>

    </div>

  <form name="adminForm" method="post" action="<?php echo $formAction; ?>">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="appName">App Name</label>
                <input type="text" class="form-control" value="<?php echo $currentSettings->getAppName(); ?>" name="appname" id="appname">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="openidserverurl">OpenID Server Url</label>
                <input type="text" class="form-control" value="<?php echo $currentSettings->getOpenIdServerUrl(); ?>" name="openidserverurl" id="openidserverurl">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="clientid">Client Id</label>
                <input type="text" class="form-control" value="<?php echo $currentSettings->getClientId(); ?>" name="clientid" id="clientid">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="clientsecret">Client Secret</label>
                <input type="text" class="form-control" value="<?php echo $currentSettings->getClientSecret(); ?>" name="clientsecret" id="clientsecret">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="redirecturi">Redirect Uri</label>
                <input type="text" class="form-control" value="<?php echo $currentSettings->getRedirectUri(); ?>" name="redirecturi" id="redirecturi">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="scopes">Scopes</label>
                <input type="text" class="form-control" value="<?php echo $currentSettings->getScopes(); ?>" name="scopes" id="scopes">
                <i><small class="form-text text-muted">openid email</small></i>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="scopes">Force WP Login Query</label>
                <input type="text" class="form-control" value="<?php echo $currentSettings->getForceWPLoginQuery(); ?>" name="forcewploginquery" id="forcewploginquery">
                <i><small class="form-text text-muted">put the value in the above field as a query parameter in the wp-login.php to force wordpress to use the local login page</small></i>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="implicitgrant">Implicit Grant</label>
                <input type="checkbox" class="form-control" value="yes" <?php echo $currentSettings->getCheckBoxIsImplicitGrant(); ?> name="implicitgrant" id="implicitgrant" disabled>
            </div>
        </div>
    </div>
    <button type="submit" id="saveconnectsettings" class="btn btn-primary">Save</button>
    <input type="hidden" name="connectsettingid" value="<?php echo $currentSettings->getId(); ?>" id="connectsettingid">
    <input type="hidden" name="goback" id="goback" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
    <input type="hidden" name="operation" id="operation" value="save">
    <div class="row">
        <br>
    </div>
      <br/>
    <div class="row">
        <div class="col-md-12">
            <table class="table">
                <thead>
                    <th scope="col">Id</th>
                    <th scope="col">App Name</th>
                    <th scope="col">Identity Provider</th>
                    <th scope="col">Is Default Setting</th>
                    <th scope="col">Disable/Enable</th>
                    <th scope="col">Edit</th>
                    <th scope="col">Delete</th>
                </thead>
                <tbody>
                <?php
                $repo = new ds\ClientConnectSettingsRepository();
                $allClientConnectSettings = $repo->getSettings();
                foreach ($allClientConnectSettings as $item){
                ?>
                    <tr>
                        <td scope="col"><?php echo $item->getId() ?></td>
                        <td scope="col"><?php echo $item->getAppName()?></td>
                        <td scope="col"><?php echo $item->getOpenIdServerUrl()?></td>
                        <td scope="col"><?php echo ($item->getIsDefault() > 0 ? 'true': 'false'); ?></td>
                        <td scope="col"><button type="submit" onclick="submitAdminForm( <?php echo $item->getId() ?>,'enable');" class="btn btn-primary"><?php echo ($item->getIsDefault() > 0 ? 'Disable': 'Enable'); ?></button></td>
                        <td scope="col"><a href="<?php echo add_query_arg( array('connectsettingid' => $item->getId(),'operation' => 'edit'), us\utility::getCurrentUrl()); ?>">Edit</a> </td>
                        <td scope="col"><button type="submit" onclick="submitAdminForm( <?php echo $item->getId() ?>,'delete');" class="btn btn-primary">Delete</button></td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    </form>
</div>

<?php
/**
 * @package FusionAuthOpenIDConnectPlugin
 */
/*
Plugin Name: FusionAuth OpenID Connect Plugin
 Plugin URI: https://github.com/FusionAuth/wordpress-openid-connect
 Description: Allows connecting to different open id connect clients
 Version:1.0.0
 Author: BOLANLE ILUMOKA
 License: GPLv2 or later
 Text Domain: openidconnect plugin
*/

if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

if (!defined('ABSPATH')) die('No direct access allowed');

use fusionauth\openidconnect\src\processors;
require_once (__DIR__.'/src/processors/ActivationProcessor.php');
require_once (__DIR__.'/src/processors/DeactivationProcessor.php');
require_once (__DIR__.'/src/processors/AdminMenuProcessor.php');
require_once (__DIR__.'/src/processors/EnqueueScriptsProcessor.php');
require_once (__DIR__.'/src/processors/AuthenticationProcessor.php');

$activationProcessor = new processors\ActivationProcessor();
$deactivationProcessor = new processors\DeactivationProcessor();
$adminProcessor = new processors\AdminMenuProcessor();
$enqueueScriptsProcessor = new processors\EnqueueScriptsProcessor();
$authenticationProcessor = new processors\AuthenticationProcessor();

register_activation_hook( __FILE__, array( $activationProcessor, 'Process' ) );
register_deactivation_hook( __FILE__, array( $deactivationProcessor, 'Process' ) );

$enqueueScriptsProcessor->Process();
$adminProcessor->Process();
add_action( 'init', array($authenticationProcessor, 'LoginIfUserHasOauthSession'), 10);
add_action('init', array($authenticationProcessor, 'ProcessLoggedInUser'), 10);
add_action( 'login_init', array($authenticationProcessor, 'ProcessLogin'), 10);
add_action('login_form', array($authenticationProcessor, 'EnForceWPLogin'), 10);
add_action('wp_logout', array($authenticationProcessor, 'ProcessLogout'),1);



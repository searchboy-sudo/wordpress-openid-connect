<?php


namespace fusionauth\openidconnect\src\processors;

use fusionauth\openidconnect\src\processors\interfaces as pi;
use fusionauth\openidconnect\src\constants as cs;

require_once (__DIR__.'/interfaces/IProcessor.php');

class EnqueueScriptsProcessor implements pi\IProcessor
{
    public function Process()
    {
       $this->runEnqueueScripts();
    }

    public function runEnqueueScripts()
    {
      add_action('admin_enqueue_scripts', array($this, 'EnqueueCss'));
      add_action('admin_enqueue_scripts', array($this, 'EnqueueJavascript'));
    }

    public function EnqueueCss()
    {
        wp_register_style( 'fusionauthbootstrapstyle', cs\PluginConstants::pluginRoot.'/src/assets/bootstrap/css/bootstrap.min.css', false, '4.3.1' );
        wp_register_style( 'fusionauthstyle', cs\PluginConstants::pluginRoot.'/src/assets/css/fusionauth_style.css', false, '1.0.0' );

        wp_enqueue_style('fusionauthbootstrapstyle');
        wp_enqueue_style('fusionauthstyle');
    }

    public function EnqueueJavascript()
    {
        wp_register_script('fusionauthjqueryscript', cs\PluginConstants::pluginRoot.'/src/assets/jquery/jquery-3.4.1.min.js',false,'3.4.1');
        wp_register_script('fusionauthbootstrapscript', cs\PluginConstants::pluginRoot.'/src/assets/bootstrap/js/bootstrap.min.js', false,'4.3.1');
        wp_register_script('fusionauthscript', cs\PluginConstants::pluginRoot.'/src/assets/js/fusionauth_script.js', false, '1.0.1');

        wp_enqueue_script('fusionauthjqueryscript');
        wp_enqueue_script('fusionauthbootstrapscript');
        wp_enqueue_script('fusionauthscript');
    }
}
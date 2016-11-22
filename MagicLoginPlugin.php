<?php

/**
 * MagicLogin Main File
 *
 * Author: Aaron Berkowitz <aaron.berkowitz@me.com>
 *
 */

namespace Craft;

class MagicLoginPlugin extends BasePlugin
{
    public function getName()
    {
        return Craft::t('Magic Login');
    }

    public function getVersion()
    {
        return '1.0.0';
    }

    public function getSchemaVersion()
    {
        return '1.0.0';
    }

    public function getDeveloper()
    {
        return 'Aaron Berkowitz';
    }

    public function getDeveloperUrl()
    {
        return 'https://github.com/aberkie/magiclogin';
    }

    public function getDescription()
    {
        return 'Simple password-less login for Craft CMS.';
    }

    public function registerSiteRoutes()
    {
        return array(
            'magiclogin/auth/(?P<publicKey>\w+)/(?P<timestamp>\d+)/(?P<signature>\w+)'
                => array('action' => 'magicLogin/authentication/authenticate'),
            'magiclogin/login' => array('action' => 'magicLogin/authentication/loginForm')
        );
    }

    protected function defineSettings()
    {
        return array(
            'linkExpirationTime' => array(AttributeType::Number, 'default' => 5),
            'redirectAfterLogin' => array(AttributeType::String, 'default' => '/admin')
        );
    }

    public function getSettingsHtml()
    {
        return craft()->templates->render(
            'magiclogin/settings',
            array(
                'settings' => $this->getSettings()
            )
        );
    }

    public function init()
    {
        require CRAFT_PLUGINS_PATH.'magiclogin/vendor/autoload.php';
    }
}

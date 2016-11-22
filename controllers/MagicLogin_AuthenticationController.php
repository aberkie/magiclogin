<?php

/**
 * MagicLogin_AuthenticationController
 *
 * Author: Aaron Berkowitz <aaron.berkowitz@me.com>
 *
 */

namespace Craft;

class MagicLogin_AuthenticationController extends BaseController
{

    protected $allowAnonymous = true;

    public function actionAuthenticate()
    {

        $settings = craft()->plugins->getPlugin('magiclogin')->getSettings();

        $routeParams = craft()->urlManager->getRouteParams();

        $publicKey = $routeParams['variables']['publicKey'];

        $timestamp = $routeParams['variables']['timestamp'];

        $signature = $routeParams['variables']['signature'];

        //Check for record with public key
        $authorizationRecord = craft()->magicLogin_auth->getAuthorization($publicKey);

        if ($authorizationRecord === null) {
            $this->redirect('/magiclogin/login', true);
        }

        // Check if the signatures match
        $generatedSignature = craft()->magicLogin_auth->generateSignature(
            $authorizationRecord->privateKey,
            $publicKey,
            $timestamp
        );

        if ($signature != $generatedSignature) {
            $this->redirect('/magiclogin/login', true);
        }

        // Check if timestamp is within bounds
        $timelimit = $authorizationRecord->timestamp + ($settings['linkExpirationTime'] * 60);

        if (time() > $timelimit) {
            $this->redirect('/magiclogin/login', true);
        }

        //If all this has been valid, login the user
        craft()->userSession->loginByUserId($authorizationRecord->userId);

        $authorizationRecord->delete();

        $this->redirect($settings['redirectAfterLogin']);
    }

    public function actionLogin()
    {
        $this->requirePostRequest();

        $emailAddress = craft()->request->getPost('email');

        $link = craft()->magicLogin_auth->createMagicLogin($emailAddress);

        if ($link) {
            $emailSent = craft()->magicLogin_auth->sendEmail($emailAddress, $link);

            craft()->urlManager->setRouteVariables(array(
               'message' => 'Success! Check your email for your magic link.',
               'status' => 'success'
            ));
        } else {
            craft()->urlManager->setRouteVariables(array(
               'message' => 'Oops! Something went wrong. Please try your email address again.',
               'status' => 'fail'
            ));
        }
    }

    public function actionLoginForm()
    {
        $settings = craft()->plugins->getPlugin('magiclogin')->getSettings();
        
        $oldPath = craft()->path->getTemplatesPath();
        
        $newPath = craft()->path->getPluginsPath().'magiclogin/templates';
        
        craft()->path->setTemplatesPath($newPath);

        $params = craft()->urlManager->getRouteParams();
        
        $message = (isset($params['variables']['message']) ? $params['variables']['message']: '');

        $status = (isset($params['variables']['status']) ? $params['variables']['status']: '');

        $html = craft()->templates->render('login', array(
            'settings' => $settings,
            'message' => $message,
            'status' => $status
        ));
        
        craft()->path->setTemplatesPath($oldPath);

        echo $html;
    }
}

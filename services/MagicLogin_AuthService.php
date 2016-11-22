<?php

/**
 * MagicLogin_AuthService
 *
 * Author: Aaron Berkowitz <aaron.berkowitz@me.com>
 *
 */

namespace Craft;

use RandomLib;
use SecurityLib;

class MagicLogin_AuthService extends BaseApplicationComponent
{
    public function createMagicLogin($email)
    {
        // Look up user
        $user = craft()->users->getUserByEmail($email);

        if ($user === null) {
            return false;
        }

        // Create random tokens
        $factory = new RandomLib\Factory();
        
        $generator = $factory->getHighStrengthGenerator();

        $publicKey = $generator->generateString(64, 'abcdefghjkmnpqrstuvwxyz23456789');

        $privateKey = $generator->generateString(128, 'abcdefghjkmnpqrstuvwxyz23456789');

        $timestamp = time();

        // Populate Record
        $record = new MagicLogin_AuthRecord();

        $record->userId = $user->id;

        $record->publicKey = $publicKey;

        $record->privateKey = $privateKey;

        $record->timestamp = $timestamp;

        $record->save();

        $signature = $this->generateSignature($privateKey, $publicKey, $timestamp);

        $magicLogin = craft()->getSiteUrl()."magiclogin/auth/$publicKey/$timestamp/$signature";

        return $magicLogin;
    }

    public function generateSignature($privateKey, $publicKey, $timestamp)
    {
        $stringToHash = implode('-', array($publicKey, $timestamp));

        $signature = hash_hmac('sha1', $stringToHash, $privateKey);

        return $signature;
    }

    public function getAuthorization($publicKey)
    {
        $record = new MagicLogin_AuthRecord();

        $authRecord = $record->findByAttributes(array('publicKey'=>$publicKey));

        return $authRecord;
    }

    public function sendEmail($emailAddress, $link)
    {
        $settings = craft()->plugins->getPlugin('magiclogin')->getSettings();

        $email = new EmailModel();
        
        $email->toEmail = $emailAddress;
        
        $email->subject = craft()->getSiteName().' - Magic Login';
        
        $email->body    =
        "Here is your Magic Login! 

It can only be used once and will expire in $settings->linkExpirationTime minutes.

$link

Powered by [MagicLogin](https://github.com/aberkie/magiclogin)";

        try {
            $success = craft()->email->sendEmail($email);

            return $success;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}

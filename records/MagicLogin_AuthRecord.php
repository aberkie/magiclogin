<?php

/**
 * MagicLogin_AuthRecord
 *
 * Author: Aaron Berkowitz <aaron.berkowitz@me.com>
 *
 */

namespace Craft;

class MagicLogin_AuthRecord extends BaseRecord
{
    public function getTableName()
    {
        return 'magiclogin_authorizations';
    }

    protected function defineAttributes()
    {
        return array(
            'userId' => AttributeType::Number,
            'publicKey' => AttributeType::String,
            'timestamp' => AttributeType::String,
            'privateKey' => AttributeType::String
        );
    }
}

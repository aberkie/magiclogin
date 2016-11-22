<?php

/**
 * MagicLogin_AuthModel
 *
 * Author: Aaron Berkowitz <aaron.berkowitz@me.com>
 *
 */

namespace Craft;

class MagicLogin_AuthModel extends BaseModel
{
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
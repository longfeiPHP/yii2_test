<?php

namespace app\models;

use yii\base\Model;

class Redis extends Model
{
    public static function connect()
    {
        return 'connect';
    }
}

<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Redis;

class RedisController extends Controller{
    public function actionIndex()
    {
        $r = Redis::connect();
        return $r;
    }
    public function actionList()
    {
        $redis = Yii::$app->redis;
    }
}
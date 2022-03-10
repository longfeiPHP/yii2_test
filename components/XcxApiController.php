<?php
/**
 * Created by PhpStorm.
 * User: lihai
 * Date: 2022/3/4
 * Time: 16:04
 */

namespace app\components;

use Yii;
use yii\web\Controller;

Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

class XcxApiController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * 核验签名的正确性
     * @param string $sign
     * @param int $time
     * @return boolean
     */
    public static function checkSign($sign, $time)
    {
        $authkey = Yii::$app->params['authkey'];
        $tempmd5 = md5($authkey . $time);
        $value = md5($tempmd5 . $tempmd5 . substr($time, -6));
        return strcasecmp($value, $sign) === 0 ? true : false;
    }
}
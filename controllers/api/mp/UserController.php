<?php
/**
 * Created by PhpStorm.
 * User: lihai
 * Date: 2022/3/4
 * Time: 16:02
 */

class UserController extends \yii\web\Controller //\app\components\XcxApiController
{
    public function actionGetToken()
    {
        $request    = Yii::$app->request;
        $timeStamp      = $request->get('time', '');
        $sign = $request->get('sign', '');
    }
    public function actionA(){
        echo 'a';
    }
}
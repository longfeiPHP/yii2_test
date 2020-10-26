<?php
/**
 * Created by PhpStorm.
 * User: lihai
 * Date: 2020/7/7
 * Time: 14:48
 */

namespace app\controllers;

use Yii;

class UserController extends WebController
{
    public $enableCsrfValidation = false;
    public function actionLogin()
    {
        $param = static::getParas();
        if (!empty($param) && $param['id']==10268 && $param['mobile']==13261880292){
            $session = Yii::$app->session;
            $session['userId'] = $param['id'];
            $this->redirect('/tool/pass');
        }
        return $this->render('login');
    }
    public function actionLogout()
    {
        $session = Yii::$app->session;
        if (!empty($session['userId'])) unset($session['userId']);
        $this->redirect('/user/login');
    }
}
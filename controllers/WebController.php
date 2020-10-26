<?php
/**
 * Created by PhpStorm.
 * User: lihai
 * Date: 2020/7/2
 * Time: 10:35
 */

namespace app\controllers;

use yii\web\Controller;
use Yii;

class WebController extends Controller
{
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)){
            $session = Yii::$app->session;
            $pathInfo = Yii::$app->request->pathInfo;
            $exceptionUrl = Yii::$app->params['exceptionUrl'];
            if (in_array($pathInfo,$exceptionUrl)) return true;/*url例外中直接访问*/
            if (empty($session['userId'])){
                $this->redirect("/user/login");/*跳到登录页面*/
            }
            return true;
        }
        return false;
    }

    public static function getParas($METH='')
    {
        $params = [];
        if ($METH == 'POST') {
            return Yii::$app->request->post();
        }
        if (Yii::$app->request->isGet) {
            $params = Yii::$app->request->get();
        } else {
            $params = Yii::$app->request->post();
        }
        return $params;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: lihai
 * Date: 2020/7/22
 * Time: 16:37
 */

namespace app\controllers;

use Yii;

class VueController extends WebController
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionVueForm()
    {
        return $this->render('vue-form');
    }
}
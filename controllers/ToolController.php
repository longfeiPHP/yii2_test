<?php
/**
 * Created by PhpStorm.
 * User: lihai
 * Date: 2020/7/2
 * Time: 10:12
 */

namespace app\controllers;

use app\models\UserThird;
use Yii;
use app\components\Response;

class ToolController extends WebController
{
    public $enableCsrfValidation = false;
    /**
     * 工具页面
     */
    public function actionPass()
    {
        /*本地项目数据库配置*/
        $db = require 'D:/workspace/zhiliao/db.php';
        $method = ['setUserNumber','getUserNumber','setIdNumber','getIdNumber','setDocid','getDocid','setPatientid','getPatientid','setSalt','getSalt','setFaceId','getFaceId'];
        /*读取apache配置文件*/
        $fileName = 'E:/Apache24/conf/httpd.conf';
        $apacheConf = file_get_contents($fileName);
        $apacheConfArr = explode('#php version',$apacheConf);
        $phpVersionArr = explode('/',$apacheConfArr[1]);
        $phpVersion = str_replace('.conf','',$phpVersionArr[2]);
        $filename = scandir('E:/Apache24/conf/phpversion');
        $phpAll = [];
        foreach ($filename as $k=>$v){
            if ($v=='.'||$v=='..') continue;
            $phpAll[] = str_replace('.conf','',$v);
        }
        return $this->render('passId',[
            'method' => $method,
            'db' => $db,
            'phpVersion' => $phpVersion,/*php当前版本*/
            'phpAll' => $phpAll,/*php所有可选版本*/
        ]);
    }

    /************************************************以下是接口***************************************************************/
    /**
     * 加密解密接口
     */
    public function actionUnpass()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $param = static::getParas();
        $num = isset($param['num']) ? $param['num'] : 0;
        $method = !empty($param['method']) ? $param['method'] : '';
        if (empty($num) || empty($method)) {
            return Response::show(400, 'param error', []);
        }
        $method = 'app\components\Cipher::'.$method;
        $unPass = call_user_func_array($method,[$num]);
        return Response::show(200, 'success', ['rs'=>$unPass]);
    }
    /**
     * 获取用户openid接口
     */
    public function actionOpenid()
    {
        $param = static::getParas();
        $userId = $param['userId'];
        $openidStr = '';
        $thirdInfo = UserThird::find()->where(['userid'=>$userId])->asArray()->all();
        if (!empty($thirdInfo)) {
            foreach ($thirdInfo as $k=>$v){
                $openidStr .= $v['third_account'].'<br />';
            }
        }
        return Response::show(200, 'success', ['openid'=>$openidStr]);
    }
    /**
     * json格式化
     */
    public function actionJsonFormat()
    {
        $param = static::getParas();
        if (empty($param['jsonVal'])) return Response::show(400, 'param empty', []);
        $jsonVal = $param['jsonVal'];
        $jsonFormat = json_decode($jsonVal);
        $jsonFormat = json_encode($jsonFormat,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        return Response::show(200, 'success', ['json'=>$jsonFormat]);
    }
    /**
     * 切换本地项目数据库
     */
    public function actionChangeDb()
    {
        /*1/本地开发库*/
        $db1 = <<<db1
        <?php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=gyy',
    'username' => 'root',
    'password' => '123456',
    'charset' => 'utf8mb4',
];
db1;
        /*2/本地测试库*/
        $db2 = <<<db2
        <?php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=gyy_testing',
    'username' => 'root',
    'password' => '123456',
    'charset' => 'utf8mb4',
];
db2;
        /*3/线上开发库*/
        $db3 = <<<db3
        <?php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=59.110.168.78;dbname=gyy',
    'username' => 'root',
    'password' => '500lxtop',
    'charset' => 'utf8mb4',
];
db3;
        /*4/本地测试库*/
        $db4 = <<<db4
        <?php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=59.110.168.78;dbname=gyy_testing',
    'username' => 'root',
    'password' => '500lxtop',
    'charset' => 'utf8mb4',
];
db4;
        $dbArr = [
            1=>$db1,
            2=>$db2,
            3=>$db3,
            4=>$db4,
        ];
        $param = static::getParas();
        $keys = array_keys($dbArr);
        if (in_array($param['db'],$keys)){
            $res = file_put_contents('D:/workspace/zhiliao/db.php',$dbArr[$param['db']]);
            $redis = Yii::$app->redis;
            $redis->flushdb();/*刷新本地redis缓存*/
        }
        $db = require 'D:/workspace/zhiliao/db.php';
        return Response::show(200, 'success', ['dbDSN'=>$db['dsn']]);
    }
    /**
     * 切换php版本
     */
    public function actionChangePhp()
    {
        $param = static::getParas();
        if (empty($param['version'])) return Response::show(400,'param error');
        $version = $param['version'];

        $fileName = 'E:/Apache24/conf/httpd.conf';
        $apacheConf = file_get_contents($fileName);
        $apacheConfArr = explode('#php version',$apacheConf);
        $phpVersionArr = explode('/',$apacheConfArr[1]);
        $phpVersion = str_replace('.conf','',$phpVersionArr[2]);
        if (trim($version)==trim($phpVersion)) return Response::show(200,'success');/*版本相等*/
        $newVersion = $version.".conf\r\n";
        $phpVersionArr[2] = $newVersion;
        $apacheConfArr[1] = implode('/',$phpVersionArr);
        $apacheConf = implode('#php version',$apacheConfArr);
        $puts = file_put_contents($fileName,$apacheConf);
        if ($puts){
            return Response::show(200,'success');
        }
    }
    /************************************************以上是接口***************************************************************/
}
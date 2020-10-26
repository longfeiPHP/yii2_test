<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\components\Cipher;
use app\components\Response;

class myClass{
    public static function test($msg1, $msg2){
        echo $msg1.'||'.$msg2;
    }
}
class SiteController extends Controller
{
    public $layout=false;
    public $enableCsrfValidation = false;
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function test(...$b){
        echo '<pre>';
        print_r($b);
        echo '</pre>';
    }
    public function actionIfram(){
        return $this->render('ifram');
    }
    public function king($m, $n)
    {
        if ($n==0 || $m==0) return false;
        $arr = range(1, $n);
        $i=0;
        while (count($arr)>1){
            if (($i+1)%$m==0){
                unset($arr[$i]);
            }else {
                array_push($arr, $arr[$i]);
                unset($arr[$i]);
            }
            $i++;
        }
        return $arr;
    }
    public function niu($n)
    {
        //有一母牛，到4岁可生育，每年一头，所生均是一样的母牛，到15岁绝育，不再能生，20岁死亡，问n年后有多少头牛。
        
    }
    /**
     * 递归
     */
    public function factorial($n){
        if ($n==0 || $n==1){
            return 1;
        }else {
            return $this->factorial($n-1)*$n;
        }
    }
    /**
     * 反转（逆转）字符串
     */
    public function reverse($arr)
    {
        $len = count($arr);
        if ($len>1){
            $tmp = $arr[$len-1];
            $rs[] = $tmp;
        }
    }
    public function twoSum($nums, $target) {
        foreach($nums as $k=>$v){
            $diff = $target-$v;
            unset($nums[$k]);
            if(in_array($diff,$nums) && $diff!=$v){
                return [$k,array_search($diff,$nums)];
            }
        }
    }
    public function getM()
    {
        $wm = microtime();
        $timeArr = explode(' ', $wm);
        return $timeArr[0]*1000000;
    }
    /**
     * 快速排序
     */
    public function quickSort($arr)
    {
        $len = count($arr);
        if ($len <= 1) return $arr;
        $middle = $arr[0];//中间值
        $left = [];//小于中间的值
        $right = [];//大于中间的值
        /*循环比较*/
        for ($i=1; $i<$len; $i++){
            if ($middle < $arr[$i]){
                $right[] = $arr[$i];
            }else {
                $left[] = $arr[$i];
            }
        }
        $left = $this->quickSort($left);
        $right = $this->quickSort($right);
        return array_merge($left,array($middle),$right);
    }
    /**
     * 冒泡排序
     */
    public function blueSort($numbers)
    {
        $cnt = count($numbers);
        for ($i = 0; $i < $cnt - 1; $i++) {
            for ($j = 0; $j < $cnt - $i - 1; $j++) {
                if ($numbers[$j] > $numbers[$j + 1]) {
                    $temp = $numbers[$j];
                    $numbers[$j] = $numbers[$j + 1];
                    $numbers[$j + 1] = $temp;
                }
            }
        }
        return $numbers;
    }
    /**
     * 交换排序
     */
    public function swapSort($arr)
    {
        $len = count($arr);
        for ($i = 0; $i < $len-1; $i++){
            for ($j = $i+1; $j < $len; $j++){
                if ($arr[$i] > $arr[$j]){
                    $tmp = $arr[$i];
                    $arr[$i] = $arr[$j];
                    $arr[$j] = $tmp;
                }
            }
        }
        return $arr;
    }
    /**
     * 交换排序selef
     */
    public function swapSortSelf($arr)
    {
        $len = count($arr);
        for ($i=0;$i<$len-1;$i++){
            for ($j = $i+1; $j<$len;$j++){
                if ($arr[$i] > $arr[$j]){
                    $tmp = $arr[$i];
                    $arr[$i] = $arr[$j];
                    $arr[$j] = $tmp;
                }
            }
        }
    }
    /**
     * 插入排序
     */
    public function insertSort($arr){
        for($i=1;$i<count($arr);$i++){
            $tmp=$arr[$i];
            $key=$i-1;
            while($key>=0&&$tmp<$arr[$key]){
                $arr[$key+1]=$arr[$key];
                $key--;
            }
            if(($key+1)!=$i)
                $arr[$key+1]=$tmp;
        }
        return $arr;
    }

    /**
     * 斐波那契数列
     */
    public function fei($n){
        if ($n<=2){
            return $n;
        }
        /*递归实现*/
//        return $this->fei($n-1) + $this->fei($n-2);
        /*循环实现*/
        $i1 = 1;
        $i2 = 2;
        for ($i=3;$i<=$n;$i++){
            $tmp = $i1 + $i2;
            $i1 = $i2;
            $i2 = $tmp;
        }
        return $i2;
    }
    public function actionIndex()
    {
        /*斐波那契数列*/
        echo $this->fei(3);
        /*判断括号的有效性*/
        $s = "(])";
        $map = ['(',')','{','}','[',']'];
        $leftFlag = ['(','{','['];
        $rightFlag = [')','}',']'];
        $strack = [];
        if (strlen($s) == 1){
            return false;
        }
        for ($i=0;$i<strlen($s);$i++){
            $thisStr = substr($s,$i,1);
            if (!in_array($thisStr,$map)){
                return false;
            }
            if (in_array($thisStr,$leftFlag)){
                array_push($strack,$thisStr);
            }else{
                $rightKey = array_search($thisStr,$map);
                if ($strack[count($strack)-1]==$map[$rightKey-1]){
                    array_pop($strack);
                }else{
                    return false;
                }
            }
        }
        if (empty($strack)){
            return true;
        }else{
            return false;
        }
        return false;
        /*目标与和*/
        $nums=[3,2,4];
        $target = 6;
        $length = count($nums);

        $result = [];
        for ($i=0;$i<$length;$i++){
            $diff = $target-$nums[$i];
            if (in_array($diff,$nums)){
                $key2 = array_search($diff,$nums);
                if ($i!=$key2){
                    $result[0] = $i;
                    $result[1] = $key2;
                    break;
                }
            }
        }
        print_r($result);
        return false;
        /*插入排序*/
        /*$arr = [200,12,5,3];
        $res = $this->insertSort($arr);
        print_r($res);
        return false;*/
        /*交换排序*/
        $arr = [12,5,3,200];
        $res = $this->swapSort($arr);
        print_r($res);
        return false;
        /*快速排序*/
        $arr = array(25,133,452,364,5876,293,607,365,8745,534,18,33,78,545,545,2431,5645,5546,2,545,78,9,4564,61,56,5456,1,34,954,56,456,4,654,3,165,5456,4,65,456,4,564,56,4,54,98,15,64,54,56,456,4,5,4,564,56,456,541,56,456,41,565,
            56456121,54,545,44,53
        );
        $stime = $this->getM();
        $sortArr = $this->quickSort($arr);
        $etime = $this->getM();
        $diff = $etime-$stime;
        echo '<pre>';
        echo $diff;
        print_r($sortArr);
        echo '</pre>';
        echo '<hr />';
        /*冒泡排序*/
        $stime = $this->getM();
        $sortArr = $this->blueSort($arr);
        $etime = $this->getM();
        $diff = $etime-$stime;
        echo '<pre>';
        echo $diff;
        print_r($sortArr);
        echo '</pre>';
        return false;
        $arr = [3,2,4];
        $this->twoSum($arr, 6);
        return false;
        /*反转（逆转）字符串*/
        $this->reverse([1,2,3,4,5]);
        return false;
        /*递归*/
        $this->factorial(5);
        return false;
        /*母牛问题*/
        $this->niu($n);
        /*数猴子问题*/
        $this->king(3,5);
        return false;
        $a = 'b';
        if ($a=='a') return true;
        $b = '';
        return $this->render('parent');
        $t = strtotime("2019-11-11 09:00");
        echo $t;
        return false;
        /*大数据排序*/
        $stime = microtime();
        $bitStr = file_get_contents("D:\workspace\yiitest\basic\bigdata.txt");
        $bigArr = explode(",", $bitStr);
        $arr = $bigArr;
        
//         $arrRes = [];
//         foreach ($arr as $k=>$v){
//             $arrRes[$v] = $v;
//         }
//         asort($arrRes);
//         $aa = array_slice($arrRes,0,5);
//         print_r($aa);

        $arrD = [];
        foreach ($arr as $k=>$v){
            $m = $v%5;
            if (!empty($arrD[$m])){
                if ($v<$arrD[$m]){
                    $arrD[$m] = $v;
                }
            }else {
                $arrD[$m] = $v;
            }
        }
        asort($arrD);
        print_r($arrD);

        
        echo '<hr />';
        $etime = microtime();
        $s = explode(" ", $stime); $st = $s[0]+$s[1];
        $e = explode(" ", $etime); $et = $e[0]+$e[1];
        $difTime = $et-$st;
        echo $difTime;
        exit;
        /*调用类中的方法*/
        call_user_func_array(array(new myClass(),'test'), ['hello', 'world']);
        exit;
        $this->test('b','c');
        exit;
        /*算法题*/
        for($i=1; ;$i++) {
            if($i % 5 == 1) {
                //第一次
                $t = $i - round($i/5) - 1;
                if($t % 5 == 1) {
                    //第二次
                    $r = $t - round($t/5) - 1;
                    if($r % 5 == 1) {
                        //第三次
                        $x = $r - round($r/5) - 1;
                        if($x % 5 == 1) {
                            //第四次
                            $y = $x - round($x/5) - 1;
                            if($y % 5 == 1) {
                                //第五次
                                $s = $y - round($y/5) - 1;
                                if($s % 5 == 1) {
                                    echo $i;
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        }
        exit;
        /*算法题*/
        $arr = [1,2,3,4];
        $arrResult = [];
        for ($i=0;$i<count($arr);$i++){
            for ($j=0;$j<count($arr);$j++){
                for ($k=0;$k<count($arr);$k++){
                    $num = $arr[$i].$arr[$j].$arr[$k];
                    if (!in_array($num, $arrResult)){
                        array_push($arrResult, $num);
                    }
                }
            }
        }
        echo '<pre>';
        print_r($arrResult);
        echo '</pre>';
        exit;
        /*处理数组*/
        $a = [
            ['name'=>'xx','type'=>2,'addr'=>'bbb'],
            ['name'=>'xx','type'=>1,'addr'=>'aaa'],
            ['name'=>'xx','type'=>2,'addr'=>'bsbc'],
        ];
        $typeAddr = '';
        foreach ($a as $k=>$v){
            if ($v['type']==1){
                $typeAddr = $v['addr'];
                $a[$k]['addr'] = '';
                foreach ($a as $k1=>$v1){
                    if($v1['type']!=1){
                        $a[$k1]['addr'] = $typeAddr;
                    }
                }
            }
        }
        var_dump($a);
        exit;
        /*乘法口决*/
        echo '乘法口决';
        for ($i=0; $i<10; $i++){
            for ($j=0; $j<$i; $j++) {
                echo ($j+1).'x'.$i.'='.($j+1)*$i." ";
            }
            echo '<br />';
        }
        echo '<hr />';
        
        return false;
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
    /**
     * hello world
     */
    public function actionSay($message = 'Hello')
    {
        return $this->render('say', ['message' => $message]);
    }
    public static function getParas($METH='')
    {
        $params=[];
        if ($METH=='POST') {
            return Yii::$app->request->post();
        }
        if(Yii::$app->request->isGet)
        {
            $params    = Yii::$app->request->get();
            
        }else{
            $params    = Yii::$app->request->post();
            
        }
        return $params;
    }
    public function actionWebStory()
    {
        return $this->render('WebStory');
    }
}

<?php
/**
 * User: lixiaobo
 * Date: 18-4-13 
 * Description: 通用方法类
 */

namespace app\components;

use Yii;
use yii\helpers\FileHelper;
use app\components\Cipher;

class Tool
{

  //生成mongoID
  public static function generateUid()
  {
      static $index = 0;
      $time = microtime();
      $times = explode(' ',$time);
      // $ip = gethostbyname(gethostname());
      $ip =static::getIp();
      $ips = explode('.',$ip);
      $ip = str_pad(dechex($ips[1]),2,'0',STR_PAD_LEFT).str_pad(dechex($ips[2]),2,'0',STR_PAD_LEFT).str_pad(dechex($ips[3]),2,'0',STR_PAD_LEFT);
      $id = dechex($times[1]).
            $ip.
            str_pad(dechex(getmypid()),4,'0',STR_PAD_LEFT).
            str_pad(dechex($times[0]*1000000),5,'0',STR_PAD_LEFT).
            dechex($index++);
      return $id;
  }

  public static function getIp()
  {

      if(!empty($_SERVER["HTTP_CLIENT_IP"]))
      {
          $cip = $_SERVER["HTTP_CLIENT_IP"];
      }
      else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
      {
          $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
      }
      else if(!empty($_SERVER["REMOTE_ADDR"]))
      {
          $cip = $_SERVER["REMOTE_ADDR"];
      }
      else
      {
          $cip = '';
      }
      preg_match("/[\d\.]{7,15}/", $cip, $cips);
      $cip = isset($cips[0]) ? $cips[0] : 'unknown';
      unset($cips);

      return $cip;
  }

    /**
     * @desc 格式化用户头像url
     * @param $ImgUrl
     * @param int $userFlag 是否是用户头像，需返回默认头像 0用户/1其他
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
  public static  function FormatImgUrl($ImgUrl,$userFlag=0)
  {
      // $host =  isset(Yii::$app->params['imgcdn']) ? Yii::$app->params['imgcdn'] : '';

      $cdn = Yii::$app->get('cdn');
      $host = $cdn->host;

      if(empty($ImgUrl)) {
        if ($userFlag==0) {
          return '';
        }
        return $host.'logo.png';
      }else if (strpos($ImgUrl, 'http:') === false && strpos($ImgUrl, 'https:') === false) {
          return $host. ltrim($ImgUrl,"/");
      }else{
        return $ImgUrl;
      }
  }

    /**
     * 通过出生日期获取年龄
     *
     * @param $birthday
     * @return false|string
     */
    public static function getAge($birthday){

        if (empty($birthday)) {
            return '未知';
        }
        // 将格式化的时间转换为时间戳
        if (!is_numeric($birthday)) {
            $birthday = strtotime($birthday);
        }

        $age=floor((time()-$birthday)/31536000);

        return $age < 1 ? 1 : $age;

    }

    /**
     * @desc 校验是否为手机号
     * @param $phoneNum
     * @return bool
     */
  public static function checkPhone($phoneNum)
  {
      // $myreg = "/^1[34578]{1}\d{9}$/"; 
      if (mb_strlen($phoneNum) != 11) {
          return false;
      };
      if (!preg_match("/^1[3456789]{1}\d{9}$/", $phoneNum)) {
          return false;
      }
      return true;
  }

    /**
     * 写日志
     * @param string $file 文件名称及路径
     * @param string $content 内容
     * @param string $rootPath @console @frontend @webroot @app
     * @throws \yii\base\Exception
     */
  public static function logToFile($file, $content,$rootPath)
  {
    $pathinfo = pathinfo($file);
    if ($pathinfo["basename"] == $file) {
      $pathinfo["dirname"] ='';
    }
    $filepath = static::createLogPath($pathinfo["dirname"],$rootPath).$pathinfo["basename"];
    if (is_array($content)) {
      $content = var_export($content,true);
    }
    @file_put_contents($filepath, "\n" . date('Ymd H:i:s ') . $content, FILE_APPEND);
  }

    /**
     * 创建文件夹
     * @param $filePath
     * @param $rootPath
     * @return string
     * @throws \yii\base\Exception
     */
  public static function createLogPath($filePath,$rootPath)
  {
      $rootPath = \Yii::getAlias($rootPath);
      $nginxLog = 'nginx/';
      if (isset($_SERVER['SWOOLE_FLAG'])) {
          $nginxLog = '';
      }
      if (empty(trim($filePath, '/'))) {
          $path = '/runtime/logs/'.$nginxLog.date('Ym/d/');
      }else{
          $path = '/runtime/logs/'.$nginxLog.date('Ym/d/').trim($filePath, '/').'/';
      }
      
      if(!is_dir($rootPath.$path)){
          FileHelper::createDirectory($rootPath.$path);
      }
      return $rootPath.$path;
  }

    /**
     * 通过经纬度返回城市信息 包括区域码 不存在返回空
     * @param $location
     * @param bool $baidures
     * @return array|bool|mixed|string
     */
  public static function getCurrentPlace($location, $baidures = false)
  {
      if (!is_array($location)) {
          return [];
      }

      $baidu = "http://api.map.baidu.com/geocoder/v2/";
      $strLocation = $location[1] . ',' . $location[0];
      $akTemp = \yii::$app->params['baidu_ak'];
      $url = "?ak={$akTemp}&location={$strLocation}&output=json&pois=1";
      $rtstr = file_get_contents($baidu . $url);
      $rt = json_decode($rtstr, true);
      if ($baidures) return $rtstr;
      if ($rt['status'] === 0) {
          $address = $rt['result']['addressComponent'];
          //省市区
      
          $rt = [
              'district' => $address['district'],
              'district_code' => $address['adcode'],
              'city' => $address['city'],
              'province' => $address['province'],
          ];
          return $rt;
      }else{
        \yii::error('BAIDU_LOCATE error:'.$rtstr);
      }

      return [];
  }

  /**
  * 获取接口签名及时间
  * @param array $params 用户的传入的参数
  * @return array[$t,$sign]
  */
  public static function Sign($params)
  {
    $nowTime = time();
    return [$nowTime,static::makeSign($params,$nowTime)];
  }

  /**
  * 签名 所有参数按key字母排序，使用urlget参数形式如 id=1&token=2
  * 再 做md5 ，返回小写32位
  * @param int $t 时间
  * @param array $params 用户的传入的参数
  * @return string $sign
  */
  public static function makeSign($params,$t)
  {      
    if (!is_array($params))
        $params = array();
    unset($params['time'],$params['sign']);
    ksort($params);
    $query_array = array();
    foreach ($params as $k => $v) {
        array_push($query_array, $k . '=' . $v);
    }
    $query_string = join('&', $query_array);
    $tempmd5=md5($query_string);
    $key = \Yii::$app->params['backendKey'];
    return  md5($tempmd5.$tempmd5.substr($t,-6).$key);
  }

  /**
  * 检查sign是否合法
  * @param array $params 用户的传入的参数
  * @return boolean
  */
  public static function checkSign($params)
  {
      $authTimeDiff = \Yii::$app->params['authtimediff'];
      if (empty($params['time']) || empty($params['sign'])) {
        return false;
      }
      $ptime =$params['time'];
      $sign =$params['sign'];
      $nowTime = time();

      if ($nowTime + $authTimeDiff < $ptime  || $ptime < $nowTime - $authTimeDiff )  {
          return false;
      }

      if(strcasecmp($sign,static::makeSign($params,$ptime)) === 0){
          return true;
      }
      else{
          return false;
      }        
  }

  public static function jsonEncode($value, $options = 0, $depth = 512)
  {
      $data = json_encode($value, $options, $depth);

      $severity = json_last_error();
      if ($severity !== JSON_ERROR_NONE) {
          Yii::error($severity . json_last_error_msg(), 'Tool::jsonEncode');
      }

      return $data;
  }

  public static function jsonDecode($json, $assoc = false, $depth = 512, $options = 0)
  {
      $data = json_decode($json, $assoc, $depth, $options);

      $severity = json_last_error();
      if ($severity !== JSON_ERROR_NONE) {
          Yii::error($severity . json_last_error_msg(), 'Tool::jsonDecode');
      }

      return $data;
  }

    /**生成随机长度字串
     * @param int $len 随机长度
     * @param string $format 字串格式
     * @return string
     */
      public static function  randStr( $len=6,$format='NUMBER') {
        switch($format) {
          case 'NUMBER':
            $chars='0123456789';
            break;
          case 'ALL':
            $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'; break;
          case 'CHAR':
            $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; break;
          default :
            $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
          break;
        }
        mt_srand((double)microtime()*1000000*getmypid());
        $password="";
        while(strlen($password)<$len)
        $password.=substr($chars,(mt_rand()%strlen($chars)),1);
        return $password;
     }

    /**
     * 根据时间返回统计时间，未选择 返回昨天20点-今天20点
     * @param string $datetime 2018-05-10 格式
     * @return array
     *
     */
    public static function  getStatisticTime( $datetime='') {
        $tempDay =[];
        if (!empty($datetime)){
            $tempDay = explode('-',$datetime);
            if (count($tempDay) !=3) {
                $datetime = '';
            }
        }
        $d = empty($datetime) ? date('d') : $tempDay[2];
        $m = empty($datetime) ? date('m') : $tempDay[1];
        $y = empty($datetime) ? date('Y') : $tempDay[0];
        $beginToday=mktime(20,0,0,$m,$d-1,$y);
        $endToday=mktime(21,0,0,$m,$d,$y)-1;
        return [$beginToday,$endToday];
    }

  /**
   * @param $url
   * @param array $paramsArr
   * @param bool $post
   * @param bool $https
   * @param array $header
   * @param array $timeout [all,connect,dns]单位秒eg:['connect'=>0.2]
   * @return mixed
   */
  public static function curl($url, $paramsArr = [], $post = false, $https = false, $header = [], $timeout = [])
  {
      if (is_array($paramsArr)) {
          $paramStr = http_build_query($paramsArr);
      } else {
          $paramStr = $paramsArr;
      }
      $ch = curl_init();
      if ($post) {
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $paramStr);
      } else {
          if (strlen($paramStr) > 1) {
              $url .= strpos($url, '?') ? '&' . $paramStr : '?' . $paramStr;
          }
          curl_setopt($ch, CURLOPT_URL, $url);
      }
      curl_setopt($ch, CURLOPT_HEADER, false);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      if (!empty($header)) {
          curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
      }
      if (!empty($timeout)) {
          if (!empty($timeout['all'])) {
              curl_setopt($ch, CURLOPT_TIMEOUT_MS, 1000 * $timeout['all']);
          }
          if (!empty($timeout['connect'])) {
              curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 1000 * $timeout['connect']);
          }
          if (!empty($timeout['dns'])) {
              curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 1000 * $timeout['dns']);
          }
      }

      if ($https) {
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
          curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
      }

      $ret = curl_exec($ch);
      $err = curl_error($ch);
      if (!empty($err)) {
          $info = curl_getinfo($ch);
          $info = static::jsonEncode($info);
          \Yii::error("error:{$err} {$info}", "CURL");
      }
      curl_close($ch);
      return $ret;
  }
    /**
     * 根据药名返回药名/规格
     * @author Hailong
     * @create by 2019.04.06 13:56
     * @param $drugName string 药名
     */
  public static function drugNameFormat($drugName)
  {
      /*格式化中文括号*/
      $drugName = str_replace("（","(",$drugName);
      $drugName = str_replace("）",")",$drugName);
      $drugRule = '';
      if (strpos($drugName,'(')!==false){
          $posOne = strpos($drugName,'(')+1;
          $posTwo = strlen($drugName)-1-$posOne;
          $drugRule = substr($drugName,$posOne,$posTwo);/*规格*/
      }
      
      $drugName = strpos($drugName,'(')!==false ? substr($drugName,0,strpos($drugName,'(')) : $drugName;/*药名*/
      return [$drugName,$drugRule];
  }
  /**
   * 药 单位字典
   * @author Hailong
   * @create by 2019.04.11
   */
  public static function unitData()
  {
      $unitFlag = ['g'=>'克','条'=>'条','对'=>'对','个'=>'个','box'=>'盒','bottle'=>'瓶','bag'=>'袋'];
      return $unitFlag;
  }
  /**
   * 药 单位字典合解析
   * @author Hailong
   * @create by 2019.04.11
   * @param $unit string 数据库中的unit字段
   * @return string format unit
   */
  public static function unitFormat($unit)
  {
      $unitFlag = static::unitData();
      if (!empty($unitFlag[$unit])) {
          return $unitFlag[$unit];
      } else {
          return $unit;
      }
  }

    /**
     * 验证数据是否为正整数
     * @param $value
     * @return bool
     */
    public static function isPositiveInteger($value)
    {
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 计算当前至24点的剩余秒数
     * $day 计算日 为空计算当天
     * @return false|float|int
     */
    public static function to24LeftTime($h12=true,$day ="")
    {

        if (empty($day)){
            $maxSec = 12*60*60;
            $endTime = strtotime(date("Y-m-d",strtotime("+1 day")));
            if ($h12 && $endTime - time() < $maxSec){
                $maxSec = $endTime - time();
            }
        }else{
            $endTime = strtotime(date("Y-m-d 23:59:59",strtotime($day)));
            $maxSec = $endTime - time();
        }
        if ($maxSec < 0) $maxSec =1;
        return $maxSec;
    }
    
    /**
     * 根据状态获取性别
     */
    public static function getSex($gender = 0)
    {
    	//性别 2女/1男/0未知
    	switch ($gender){
    		case 1:
    			$sex = "男";
    			break;
    		case 2:
    			$sex = "女";
    			break;
    		case 0:
    		default:
    			$sex = "未知";
    			break;
    	}
    	return $sex;
    }
    public static function checkAppVersion($version)
    {
        $vArr = explode(".",$version);
        $currVersion = Yii::$app->params['appVersion'];
        $tempV = [
            'major'=>isset($vArr[0]) && is_numeric($vArr[0]) ? $vArr[0]: 0,
            'minor'=>isset($vArr[1]) && is_numeric($vArr[1]) ? $vArr[1]: 0,
            'patch'=>isset($vArr[2]) && is_numeric($vArr[2]) ? $vArr[1]: 0
        ];
        $flag = false;
        foreach ($tempV as $key => $val){
            if ($val < $currVersion[$key]) {
                $flag =true;
                break;
            }
        }
        if ($flag){
            if (in_array(self::getOS(),['mac','ipod','iphone','ipad'])){
                $currVersion['downUrl']=$currVersion['downUrl']['ios'];
            }else{
                $currVersion['downUrl']=$currVersion['downUrl']['android'];
            }
            return $currVersion;
        }

        return [];
    }

    public static function getOS(){
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);

        if(strpos($agent, 'windows nt')) {
            $platform = 'windows';
        } elseif(strpos($agent, 'macintosh')) {
            $platform = 'mac';
        } elseif(strpos($agent, 'ipod')) {
            $platform = 'ipod';
        } elseif(strpos($agent, 'ipad')) {
            $platform = 'ipad';
        } elseif(strpos($agent, 'iphone')) {
            $platform = 'iphone';
        } elseif (strpos($agent, 'android')) {
            $platform = 'android';
        } elseif(strpos($agent, 'unix')) {
            $platform = 'unix';
        } elseif(strpos($agent, 'linux')) {
            $platform = 'linux';
        } else {
            $platform = 'other';
        }

        return $platform;
    }
    /**
     * 数据处理APP接口
     * @author hailong 20191115
     * @param array $array 要处理的数组(最大为二维数组)['id'=>1,'name'=>'abc'] 或 [['id'=>1,'name'=>'abc'],['id'=>2,'name'=>'bcd']]
     * @param array 要取出的字段
     * @param array $passFieldMethod 加密字段及方法(最大为二维数组)['id'=>'setIdNumber'] 或 [['id','oid'],'setIdNumber']
     * @param array ['unit_price'=>['map'=>'unit_price','handle'=>'*100']]
     */
    public static function dataFilter($array=[],$fields=[],$passFieldMethod=[],$map=[])
    {
        $returnData = [];
        if (empty($array)) return $returnData;
        if (empty($fields)) return $returnData;
        if (count($array) == count($array,1)){/*一维数组*/
            foreach ($fields as $v){
                $vPass = $array[$v];
                if (!empty($passFieldMethod)){
                    if (count($passFieldMethod) == count($passFieldMethod,1)){
                        if (in_array($v, array_keys($passFieldMethod))){
                            $method = 'frontend\components\Cipher::'.$passFieldMethod[$v];
                            $rm = method_exists('frontend\components\Cipher',$passFieldMethod[$v]);
                            if ($rm){
                                $vPass = call_user_func_array($method,[$array[$v]]);
                            }
                        }
                    } else {
                        if (in_array($v, $passFieldMethod[0])){
                            $method = 'frontend\components\Cipher::'.$passFieldMethod[1];
                            $rm = method_exists('frontend\components\Cipher',$passFieldMethod[1]);
                            if ($rm){
                                $vPass = call_user_func_array($method,[$array[$v]]);
                            }
                        }
                    }
                }
                if (in_array($v, array_keys($map))){
                    $vPass = static::filterMap([$v=>$vPass],$map);
                    $returnData[key($vPass)] = $vPass[key($vPass)];
                }else {
                    $returnData[$v] = $vPass;
                }
            }
        }else {
            foreach ($array as $k=>$val){
                foreach ($fields as $v){
                    $vPass = $val[$v];
                    if (!empty($passFieldMethod)){
                        if (count($passFieldMethod) == count($passFieldMethod,1)){
                            if (in_array($v, array_keys($passFieldMethod))){
                                $method = 'frontend\components\Cipher::'.$passFieldMethod[$v];
                                $rm = method_exists('frontend\components\Cipher',$passFieldMethod[$v]);
                                if ($rm){
                                    $vPass = call_user_func_array($method,[$val[$v]]);
                                }
                            }
                        } else {
                            if (in_array($v, $passFieldMethod[0])){
                                $method = 'frontend\components\Cipher::'.$passFieldMethod[1];
                                $rm = method_exists('frontend\components\Cipher',$passFieldMethod[1]);
                                if ($rm){
                                    $vPass = call_user_func_array($method,[$val[$v]]);
                                }
                            }
                        }
                    }
                    if (in_array($v, array_keys($map))){
                        $vPass = static::filterMap([$v=>$vPass],$map);
                        $returnData[$k][key($vPass)] = $vPass[key($vPass)];
                    }else {
                        $returnData[$k][$v] = $vPass;
                    }
                }
            }
        }
        return $returnData;
    }
    /**
     * 字段处理
     */
    public static function filterMap($val,$map)
    {
        $data = [];
        if (!empty($map)){
            $mapVal = $map[key($val)];
            if (!empty($mapVal)){
                $keyName = !empty($mapVal['map']) ? $mapVal['map'] : key($val);
                if (!empty($mapVal['handle'])){
                    $handle = substr($mapVal['handle'],0,1);
                    $handleNum = substr($mapVal['handle'],1,strlen($mapVal['handle']));
                    if ($handle=='*'){
                        $data[$keyName] = round($val[key($val)]*$handleNum);
                    }else {
                        $data[$keyName] = $val[key($val)];
                    }
                }else {
                    $data[$keyName] = $val[key($val)];
                }
            }
        }
        return $data;
    }
}

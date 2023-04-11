<?php
/**
 * User: yuzhu
 * Date: 2018/11/17
 */

namespace app\components;


class Cipher
{
    public static $authkey= '';
    public static $prescriptionKey='';
    const CI = [
        '0123456789abcdefghijklmnopqrstuv',
        'kn7ythd2uf5a1vxes3pmczq6wr8bg94j',
        '3xhgn7usb95mtfvec1z4jw2kr6p8dayq',
        't42dncye63pbh5fx971gjwsrk8vaqzum',
        '2bzsgrutxdafqh4jcev51w38nm96kyp7',
        '7wpbrack4qyumxd3ftvegz1j2h6n8s59',
    ];
    const NUM = [
        0 => 1867830,
        1 => 1438670,
        2 => 1258960,
        3 => 2347890,
        4 => 2456780,
        5 => 1454320,
        6 => 1566660,
        7 => 1457880,
        8 => 1784640,
        9 => 1121110,
    ];

    function __construct(){
        static::$authkey = \Yii::$app->params['authkey'];
        static::$prescriptionKey = \Yii::$app->params['prescriptionKey'];
    }

    //数字加密密钥
    private static $pwdKey="4drMj6nFs2we53CmDWlRJLPxqVH7ao0fNpIiOBUyA8zEh9SutbkcYvG1gKQZXT";

    //62位进制key
    private static $baseKey= '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    //数字加密密钥
    private static $pwdKeyArray= [
        4 => 0,
        'd' => 1,
        'r' => 2,
        'M' => 3,
        'j' => 4,
        6 => 5,
        'n' => 6,
        'F' => 7,
        's' => 8,
        2 => 9,
        'w' => 10,
        'e' => 11,
        5 => 12,
        3 => 13,
        'C' => 14,
        'm' => 15,
        'D' => 16,
        'W' => 17,
        'l' => 18,
        'R' => 19,
        'J' => 20,
        'L' => 21,
        'P' => 22,
        'x' => 23,
        'q' => 24,
        'V' => 25,
        'H' => 26,
        7 => 27,
        'a' => 28,
        'o' => 29,
        0 => 30,
        'f' => 31,
        'N' => 32,
        'p' => 33,
        'I' => 34,
        'i' => 35,
        'O' => 36,
        'B' => 37,
        'U' => 38,
        'y' => 39,
        'A' => 40,
        8 => 41,
        'z' => 42,
        'E' => 43,
        'h' => 44,
        9 => 45,
        'S' => 46,
        'u' => 47,
        't' => 48,
        'b' => 49,
        'k' => 50,
        'c' => 51,
        'Y' => 52,
        'v' => 53,
        'G' => 54,
        1 => 55,
        'g' => 56,
        'K' => 57,
        'Q' => 58,
        'Z' => 59,
        'X' => 60,
        'T' => 61,
    ];

    public static function setAuthKey ($key){
        static::$authkey = $key;
    }

    private static function getDict($type, $decode = false)
    {
        $ci = static::CI;
        $c0 = $ci[0];
        if (isset($ci[$type])) {
            $ci = $ci[$type];
        } else {
            $ci = $c0;
        }
        if ($decode) {
            $tmp = $c0;
            $c0 = $ci;
            $ci = $tmp;
        }
        return array_combine(str_split($c0), str_split($ci));
    }

    public static function encode($int, $type = 0)
    {
        $dict = static::getDict($type);
        $str = base_convert($int, 10, 32);
        $strArr = str_split($str);
        $res = '';
        foreach ($strArr as $char) {
            $res .= $dict[$char];
        }
        return $res;
    }

    public static function decode($str, $type = 0)
    {
        $str = str_replace(['o', 'l', 'i', '0'], '', strtolower($str));
        $dict = static::getDict($type, true);
        $strArr = str_split($str);
        $res = '';
        foreach ($strArr as $char) {
            $res .= $dict[$char];
        }
        return base_convert($res, 32, 10);
    }

    /*
    *用户id 生成扰码
    *id 用户id
    */
    public static function setSalt($int)
    {
        return static::encode($int + 120622, 2);
    }

    /*
    *扰码 解码为用户id 
    *id 用户id
    */
    public static function getSalt($str)
    {
        return static::decode($str, 2) - 120622;
    }

    /*
    *加密用户id
    *id 用户id
    */
    public static function setUserNumber($id)
    {
        if (!is_numeric($id)){
            return '';
        }
        $rest = $id % 10;
        return $id + static::NUM[$rest];
    }

    /*
    *解密用户id
    *id 用户id
    */
    public static function getUserNumber($id)
    {
        if (!is_numeric($id)){
            return '';
        }
        $rest = $id % 10;
        return $id - static::NUM[$rest];
    }
    /*
    *加密表id hailong 20191115
    *id 表id
    */
    public static function setIdNumber($id)
    {
        if (!is_numeric($id)){
            return '';
        }
        $rest = $id % 10;
        return $id + static::NUM[$rest];
    }

    /*
    *解密表id hailong 20191115
    *id 表id
    */
    public static function getIdNumber($id)
    {
        if (!is_numeric($id)){
            return '';
        }
        $rest = $id % 10;
        return $id - static::NUM[$rest];
    }

    /*
    *加密医生id
    *id 医生用户id
    */
    public static function setDocid($int)
    {
        if (!is_numeric($int)){
            return '';
        }
        return static::encode($int + 102481888, 1);
    }

    /*
    *解密医生id
    *id 医生用户id
    */
    public static function getDocid($str)
    {
        if (empty($str) || is_numeric($str)){
            return '';
        }
        return static::decode($str, 1) - 102481888;
    }

    /*
    *加密患者id
    *id 加密id
    */
    public static function setPatientid($id)
    {
        if (empty($id) || !is_numeric($id)){
            return '';
        }
        $rest = $id % 10;
        return $id + static::NUM[$rest]*2;
    }

    /*
    *解密患者id
    *id 患者加密后id
    */
    public static function getPatientid($id)
    {
        if(empty($id) || !is_numeric($id)){
            return 0;
        }
        $rest = $id % 10;
        return $id - static::NUM[$rest]*2;
    }

    /**
     * 加密医生开方密码
     * @param $psw
     * @return bool|string
     */
    public static function getPrescriptionPSW($psw){
        $key = static::$prescriptionKey;
        return substr(md5($psw.$key.'gyy15412018'),8,16);
    }

    /*
    *可逆加密算法（加密）
    *data 解密字符串
    */
    public function encrypt($data)
    {
        $AuthKey =static::$authkey;
        $key    =    md5($AuthKey);
        $x        =    0;
        $len    =    strlen($data);
        $l        =    strlen($key);
        $char='';
        $str='';
        for ($i = 0; $i < $len; $i++)
        {
            if ($x == $l) 
            {
                $x = 0;
            }
            $char .= $key{$x};
            $x++;
        }
        for ($i = 0; $i < $len; $i++)
        {
            $str .= chr(ord($data{$i}) + (ord($char{$i})) % 256);
        }
        return base64_encode($str);
    }

    /**
    * 可逆加密算法（解密）
    * @param data 加密字符串
    */
    public function decrypt($data)
    {
        $AuthKey =static::$authkey;
        $key = md5($AuthKey);
        $x = 0;
        $data = base64_decode($data);
        $len = strlen($data);
        $l = strlen($key);
        $char='';
        $str='';
        for ($i = 0; $i < $len; $i++)
        {
            if ($x == $l) 
            {
                $x = 0;
            }
            $char .= substr($key, $x, 1);
            $x++;
        }
        for ($i = 0; $i < $len; $i++)
        {
            if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1)))
            {
                $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
            }
            else
            {
                $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
            }
        }
        return $str;
    }

    /**
    * 对id进行可逆加密
    * @param string $id  
    *  格式 t,id
    * @return string $token
    */
    public function encodeMpToken($id)
    {
        return $this->encrypt(time().','.$id);
    }

    /**
    * 解密可逆token
    * @param string $token  
    * 解密后的格式 t,id
    * @return string $id
    */   
    public function decodeMpToken($token)
    {
        $authTimeDiff = 24*60*60;
        $tokenArr =explode(',', $this->decrypt($token));
        $ptime=0;
        if (!is_numeric($tokenArr[0]))
        {
            return false;
        }else{
            $ptime = $tokenArr[0];
        }
        $nowTime = time();
        if ($nowTime + $authTimeDiff < $ptime  || $ptime < $nowTime - $authTimeDiff )  {
            return false;
        }
        return $tokenArr[1];
    }

    /*
    *生成处方id-code
    *id 用户id
    */
    public static function encodePrescriptionId($id)
    {
        return base64_encode(static::encode($id + 8723673, 2));
    }
    /**
     * 生成处方编号
     *
     * @param string $separator
     * @param string $prefix
     * @return string
     */
    public static function createPrescriptionNumber(){
        return static::get_order_sn();
    }

    /**
     * 随机生成订单号
     **/
    public static function get_order_sn() {
        return date('ymd') . substr(time(), -4) . str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /*
    *处方code 解码为处方id 
    *id 用户id
    */
    public static function decodePrescriptionId($str)
    {
        return static::decode(base64_decode($str), 2) - 8723673;
    }


    /**
    * 生成uuid
    * @param string $prefix 前缀
    * @param string $separator 分隔符    
    * @return string $uuid
    */
    public static function createUUid($separator = "-",$prefix = "") 
    {
        $data=$_SERVER['REQUEST_TIME']; 
        $data.=$_SERVER['HTTP_USER_AGENT'];
        $data.=$_SERVER['SERVER_ADDR'];
        $data.=$_SERVER['SERVER_PORT'];
        $data.=$_SERVER['REMOTE_ADDR'];
        $data.=$_SERVER['REMOTE_PORT'];

        $str = md5(uniqid(mt_rand(), true).$data);

        $accessToken = substr($str, 0, 8) . $separator;
        $accessToken .= substr($str, 8, 4) . $separator;
        $accessToken .= substr($str, 12, 4) . $separator;
        $accessToken .= substr($str, 16, 4) . $separator;
        $accessToken .= substr($str, 20, 12);
        return $prefix . $accessToken;
    }

    /**
    * 获取接口签名及时间
    * @param array $params 用户的传入的参数
    * @return array[$t,$sign]
    */
    public function Sign($params)
    {
      $nowTime = time();
      return [$nowTime,$this->makeSign($params,$nowTime)];
    }

    /**
    * 签名 所有参数按key字母排序，使用urlget参数形式如 id=1&token=2
    * 再 做md5 ，返回小写32位
    * @param int $t 时间
    * @param array $params 用户的传入的参数
    * @return string $sign
    */
    public function makeSign($params,$t)
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
      $key = static::$authkey;
      return  md5($tempmd5.$tempmd5.substr($t,-6).$key);
    }

    /**
    * 检查sign是否合法
    * @param array $params 用户的传入的参数
    * @return boolean
    */
    public function checkSign($params)
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

        if(strcasecmp($sign,$this->makeSign($params,$ptime)) === 0){
            return true;
        }
        else{
            return false;
        }        
    }

    /**
     * 生成token
     * @param string $length 生成的token长度
     * @return string
     */
    public function make_token( $length = 16 )
    {
        // 密码字符集，可任意添加你需要的字符
        $chars = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h',
            'i', 'j', 'k', 'l','m', 'n', 'o', 'p', 'q', 'r', 's',
            't', 'u', 'v', 'w', 'x', 'y','z', 'A', 'B', 'C', 'D',
            'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L','M', 'N', 'O',
            'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y','Z',
            '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'p', 'q', 'r', 's',
            't', 'u', 'v', 'w', 'x', 'y','z', 'A', 'B', 'C', 'D',
            'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L','M', 'N', 'O',
            'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y','Z',
            '0', '1', '2', '3', '4', '5', '6', '7', '8');
        // 在 $chars 中随机取 $length 个数组元素键名
        $keys = array_rand($chars, $length);
        $token = '';
        for($i = 0; $i < $length; $i++)
        {
            // 将 $length 个数组元素连接成字符串
            $token .= $chars[$keys[$i]];
        }
        return $this->encrypt(time().','.$token);

    }


    /**
     * 计算年龄
     * @param $birthday string 生日
     * @return false|string
     */
    public static function getAge ($birthday) {

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
     *面诊开方id 生成扰码
     *id 用户id
     * @param $int
     * @return string
     */
    public static function setFaceId($int)
    {
        return static::encode($int + 102481888, 2);
    }

    /**
     *扰码 解码面诊开方id
     *id 用户id
     * @param $str
     * @return int|string
     */
    public static function getFaceId($str)
    {
        return static::decode($str, 2) - 102481888;
    }

    /**
     * 加密数字
     * @param $num int 需加密的整型数字
     * @return string 加密值
     */
    public static function setPwdEncode($num)
    {
        if (!is_int($num)){
            return "";
        }
        $hexArr = str_split(static::ConversionFrom10($num));
        $keyArr = str_split(self::$pwdKey);
        $keyLen = count($keyArr);
        //加密校验位随机数字
        $rand = mt_rand(0, $keyLen - 1);
        //加密随机位
        $res = $keyArr[$rand];
        $verifyKey =ord($res);//校验位
        foreach ($hexArr as $char) {
            //使用62进制+加扰码替换数据
            $offset = static::$pwdKeyArray[$char] + $rand;
            $res .= $keyArr[$offset % $keyLen];
            $verifyKey +=ord($keyArr[$offset % $keyLen]);
        }
        //补位校验位 校验位=除校验位的所有ASCII码值和
        return $res .$keyArr[$verifyKey%61];
    }

    /**
     * 解密数字
     * @return string 加密值
     */
    public static function getPwdDecode($str)
    {
        if (! preg_match('/^[0-9a-zA-Z]{2,10}$/', $str)) {
            return 0;
        }
        $firstChar = substr( $str, 0, 1 );//随机位
        $verify = substr($str,-1);//校验位
        $pwd=substr($str,1,strlen($str)-2);//密码数据
        $hexArr =str_split($pwd);
        $keyArr = str_split(self::$pwdKey);
        $keyLen = count($keyArr);
        $verifyKey =ord($firstChar); //校验值
        $rand = static::$pwdKeyArray[$firstChar];
        $hex = '';
        foreach ($hexArr as $key =>$char){
            $verifyKey +=ord($char);
            if (static::$pwdKeyArray[$char] >= $rand) {
                $pos = static::$pwdKeyArray[$char] - $rand;
            } else {
                $pos = $keyLen +static::$pwdKeyArray[$char] - $rand;
            }
            $hex .= $keyArr[$pos];
        }
        if ($keyArr[$verifyKey % 61] != $verify){
            return 0;
        }
        $num = static::ConversionTo10($hex);
        return $num;
    }
    /**
     * 将任意进制转为10进制
     * @param $str string 数
     * @param int $from 进制
     * @return int|string
     */
    public static function ConversionTo10($str,$from = 62){
        if ($from <2 || $from > 62) return 0;
        $str = strval($str);
        $dict = self::$baseKey;
        $len = strlen($str);
        $dec = 0;
        for($i = 0; $i < $len; $i++) {
            $pos = strpos($dict, $str[$i]);
            $dec = bcadd(bcmul(bcpow($from, $len - $i - 1), $pos), $dec);
        }
        return $dec;
    }

    /**
     * 将10进制转为任何进制
     * @param $num int 数字
     * @param int $to 进制
     * @return string
     */
    public static function ConversionFrom10($num,$to = 62) {
        if ($to <2 || $to > 62) return "";
        $dict = self::$baseKey;
        $ret = '';
        do {
            $ret = $dict[bcmod($num, $to)] . $ret; //bcmod取得高精确度数字的余数。
            $num = bcdiv($num, $to);  //bcdiv将二个高精确度数字相除。
        } while ($num > 0);
        return $ret;
    }

    /**
     * aes加密
     * @author hailong 20221025
     * @param string $data 要加密的字符串
     * @return string 加密后的字符串
     */
    public static function setAes($data)
    {
        $key = 'skdidhjdksle2345';
        $iv = "skdidhjdksle2345";
        return base64_encode(openssl_encrypt($data, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv));
    }
    /**
     * aes解密
     * @author hailong 20221025
     * @param $s 加密后的字符串
     * @return string
     */
    public static function getAes($s)
    {
        $key = 'skdidhjdksle2345';
        $iv = "skdidhjdksle2345";
        return openssl_decrypt(base64_decode($s), "AES-128-CBC", $key, OPENSSL_RAW_DATA, $iv);
    }
}
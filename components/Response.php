<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/11/17
 * Time: 10:56
 */

namespace app\components;

use Yii;
use yii\web\Response as YiiResponse;

class Response
{
    const JSON = 'json';

    /**
     * 通用接口方法
     * @param $code
     * @param string $message
     * @param array $data
     * @param string $type
     * @return boolean
     */

    public static function show($code , $message = '', $data = array(), $type = self::JSON ) {
        if(!is_numeric($code)) {
            return;
        }

//        header("Content-type:application/json");
        $result = array(
            'code' => $code,
            'msg' => $message,
            'data' => $data,
        );

        $response=Yii::$app->response;
        $response->format=YiiResponse::FORMAT_JSON;
        $response->data=$result;
        /*
        if($type == 'json') {
            self::json($code, $message, $data);
            return;
        } else if($type == 'array') {
            var_dump($result);
            //print_r($result, true);
        } else if($type == 'xml'){
            self::xmlEncode($code, $message, $data);
            return;
        }else {
            //TODO
        }*/
    }


    /**
     *
     * 按json方式返回
     * @param $code  返回代码
     * @param string $message 提示信息
     * @param array $data  数据
     * @return string|void
     */
    public static function json($code, $message = '', $data = array()) {
        if(!is_numeric($code)) {
            return;
        }

        $result = array (
            'code' => $code,
            'msg' => $message,
            'data' => $data,
        );

        switch($code){
            case 200:
            case 201:
            case 204:
            case 400:
            case 401:
            case 402:
            case 403:
            case 404:
            case 500:
            case 510:
                http_response_code(200);
                break;
            default:
                http_response_code(400);
                break;
        }
        echo json_encode($result);
        // exit;
    }

    /**
     * 原样输出数据
     * @param string $message
     */
    public static function RawFormat( $message = '') {
        $response=Yii::$app->response;
        $response->format=YiiResponse::FORMAT_RAW;
        $response->data=$message;
    }

    /**
     *
     * 按xml方式返回
     * 有三种方式转化为xml
     * @param $code
     * @param string $message
     * @param array $data
     */
    public static function xmlEncode($code, $message = '', $data = array()) {
        if(!is_numeric($code)) {
            return ;
        }

        $result = array(
            'code' => $code,
            'msg' => $message,
            'data'  => $data,
        );
        header("Content-Type:text/xml");
        $xml = "<?xml version='1.0' encoding='UTF_8'?>";
        $xml .= "<root>";

        $xml .= self::xmlToEncode($result);

        $xml .="</root>";
        echo $xml;
        // exit;
    }

    public static function xmlToEncode($data) {
        $xml = $attr = "";
        foreach($data as $key => $value) {
            //处理xml不识别数字节点
            if(is_numeric($key)) {
                $attr = " id='{$key}'";
                $key = "item";
            }
            $xml .= "<{$key}{$attr}>";
            $xml .= is_array($value) ? self::xmlToEncode($value) : $value; //递推处理
            $xml .= "</{$key}>\n";
        }
        return $xml;
    }

}
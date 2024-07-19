<?php
/**
 * php 使用数据模拟集合数据类型
 */

namespace app\components;

class Set
{
    /**
     * 数组转换为集合数据类型(数组去重)
     * @param array|mixed $data 数组
     * @return void
     */
    public static function set($data)
    {
        $set = [];
        /*数组转集合*/
        if (is_array($data)) {
            if (empty($data)) return $set;/*空*/
            foreach ($data as $value) {
                if (!in_array($value, $set)) $set[] = $value;
            }
            return $set;
        }
    }

    /**
     * @param string|int $element
     * @param $set array 集合
     * @return array
     */
    public static function add($element, array $set): array
    {
        if (!in_array($element, $set)) {
            $set[] = $element;
        }
        return $set;
    }

    /**
     * 删除元素
     * @param string|int $element
     * @param $set array 集合
     * @return array
     */
    public static function remove($element, array $set): array
    {
        if (in_array($element, $set)) {
            foreach ($set as $key => $value) {
                if ($value == $element) {
                    unset($set[$key]);
                }
            }
        }
        return array_values($set);
    }

    /**
     * 随机弹出一个元素
     * @param $set array
     * @return void
     */
    public static function pop(array $set)
    {
        if (empty($set)) return $set;
        $len = count($set);
        $key = mt_rand(0, $len - 1);
        unset($set[$key]);
        return array_values($set);
    }
}
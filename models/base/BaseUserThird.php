<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "gyy_user_third_t".
 *
 * @property int $id id
 * @property int $third_type 类型 0wechat/1QQ/2weibo
 * @property string $third_account 第三方帐号
 * @property string $union_id union_id
 * @property string $refresh_id refresh_id
 * @property int $apptype 1用户公众号/2医生公众号
 * @property int $userid user表id
 * @property int $status 状态
 * @property int $created 创建日期
 * @property int $updated 更新日期
 */
class BaseUserThird extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gyy_user_third_t';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['third_type', 'apptype', 'userid', 'status', 'created', 'updated'], 'integer'],
            [['third_account', 'union_id'], 'string', 'max' => 32],
            [['refresh_id'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'third_type' => 'Third Type',
            'third_account' => 'Third Account',
            'union_id' => 'Union ID',
            'refresh_id' => 'Refresh ID',
            'apptype' => 'Apptype',
            'userid' => 'Userid',
            'status' => 'Status',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }
}

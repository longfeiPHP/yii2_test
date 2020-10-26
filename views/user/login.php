<?php
/**
 * Created by PhpStorm.
 * User: lihai
 * Date: 2020/7/7
 * Time: 14:55
 */
$this->title = \Yii::$app->params['site']['title'].'-登录页面';
?>
<div>
    <form method="post" action="/user/login">
        用户名：<input type="text" name="id" />
        密码：<input type="password" name="mobile" />
        <input type="submit" value="登录">
    </form>
</div>

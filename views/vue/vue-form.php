<?php
/**
 * Created by PhpStorm.
 * User: lihai
 * Date: 2020/7/23
 * Time: 15:30
 */
$this->title = \Yii::$app->params['site']['title'].'-vue表单';
?>
<div id="app">
    <form>
        <input type="text" name="userName" placeholder="input user name" v-model="userName" /><br />
        <textarea v-model="textarea"></textarea><br />
        <input type="checkbox" value="A" v-model="checkbox" />
        <input type="checkbox" value="B" v-model="checkbox" /><br />
        <input type="radio" value="One" v-model="radio" />One
        <input type="radio" value="Two" v-model="radio" />Twe
    </form>
</div>
<script>
    var app = new Vue({
        el:'#app',
        data:{
            userName:"",
            textarea:"",
            checkbox:[],
            radio:''
        }
    });
</script>

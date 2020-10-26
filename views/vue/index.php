<?php
/**
 * Created by PhpStorm.
 * User: hailong
 * Date: 2020/7/22
 * Time: 14:55
 */
$this->title = \Yii::$app->params['site']['title'].'-vue页面初试';
?>
<style>
    .active{color: red;}
</style>
<div id="app">
    <span>{{message}}</span><br /><!--v-once 数据改变不更新-->
    <span>{{fullName}}</span><br /><!--计算属性-->
    <!--属性title-->
    <span v-bind:title="titleMsg">鼠标悬停几秒钟查看此处动态绑定的提示信息！</span><br /><!-- v-bind 缩写用 : v-on 缩写用 @-->
    <!--控制显示 条件判断-->
    <span v-if="seen">现在你看到我了</span><br />
    <!--循环 列表-->
    <ul>
        <li v-for="todo in todos">{{todo.text}}</li>
    </ul>
    <!--事件-->
    <button v-on:click="reverseMessage">反转消息</button>
    <!--表单输入实时显示-->
    <p>{{inputData}}</p>
    <input v-model="inputData" />
    <!--组件-->
    <ol>
        <todo-item v-for="item in groceryList" v-bind:todo="item" v-bind:key="item.id" v-bind:id="item.id"></todo-item>
    </ol>
    <!--控制某个class-->
    <span class="normal" :class="{active:isActive}">active line</span><br />
    <!--控制渲染-->
    <div v-if="type=='a'">a</div>
    <div v-else-if="type=='b'">b</div>
    <div v-else="type='c'">c</div>
</div>

<script>
    /* todo-item 组件 */
    Vue.component('todo-item', {
        props: ['todo'],
        template: '<li>{{ todo.text }}</li>'
    });
    var app = new Vue({
        el:'#app',
        data:{
            message: 'Hello Vue!',
            titleMsg:'页面加载于' + new Date().toDateString(),
            seen:true,
            firstName: '李',
            lastName: '海龙',
            type:'a',
            todos:[
                {text:'学习 JavaScript'},
                {text:'学习 Vue'},
                {text:'整个牛项目'},
            ],
            inputData:'Hello Vue!',
            groceryList: [
                { id: 0, text: '蔬菜' },
                { id: 1, text: '奶酪' },
                { id: 2, text: '随便其它什么人吃的东西' }
            ],
            isActive:true,
        },
        methods:{
            reverseMessage:function () {
                this.message = this.message.split('').reverse().join('');
            }
        },
        /*钩子函数*/
        beforeCreate:function(){
            console.log('beforeCreate');
        },
        created:function () {
            console.log('created');
        },
        beforeAmounted:function () {
            console.log('beforeAmounted');
        },
        amounted:function () {
            console.log('amounted');
        },
        beforeUpdated:function()
        {
            console.log('beforeUpdated');
        },
        updated:function () {/*这可以监听不同数据的变化*/
            console.log('updated');
        },
        beforeDestroyed:function () {
            console.log('beforeDestroyed');
        },
        destroyed:function () {
            console.log('destroyed');
        },
        /*监听数据变化*/
        watch:{
            seen:function (val) {/*val 变化后的值*/
                console.log('ws'+val);
            }
        },
        /*计算属性*/
        computed:{
            fullName:function () {
                return this.firstName + '' + this.lastName;
            }
        }
    });
    /*监听数据变化*/
    app.$watch('seen',function (newValue, oldValue) {
        console.log(newValue,oldValue);
    });
</script>


<?php
$this->title = \Yii::$app->params['site']['title'];
?>
<script type="text/javascript" src="/jquery.min.js"></script>
<style>
    .box{ border: 1px solid #bbb; padding:10px; width: auto;float: left;margin-right: 10px;}
    .rows{margin-top: 10px; width: 100%;}
    .threePercent{width: 33.33%;float: left;}/*三分之一*/
</style>
<div style="width: 100%"><a style="float: right" href="/user/logout">退出</a> </div>
<div class="rows">
    <!--加密与解密-->
    <div class="box">
        <input type="text" name="num" /><br />
        <button style="width: 120px;">加密</button>
        <button style="width: 120px;">解密</button><br />
        <?php foreach ($method as $k=>$v){?>
            <button style="width: 120px;" onclick="opera('<?php echo $v;?>')"><?php echo $v;?></button>
            <?php
            if ($k%2!=0) echo '<br />';
            ?>
        <?php }?>
    </div>

    <!--用户id查询openid-->
    <div class="box" style="width: 300px;">
        <div>
            <div>根据用户id查询openid(本地开发环境)</div>
            <input type="text" name="userid" />
            <button onclick="getOpenId()">查询</button>
            <div class="openid"></div>
        </div>
        <div style="margin-top: 10px;">
            <div>切换php版本(<span style="color: red">当前:<span id="curVersion"><?=$phpVersion?></span></span>)</div>
            <div>
                <?php
//                var_dump($phpAll); exit;
                ?>
                <?php if (!empty($phpAll)){ ?>
                <?php foreach ($phpAll as $kp=>$vp) {?>
                        <button onclick="changePHP('<?=$vp?>')"><?php echo $vp;?></button>
                <?php }?>
                <?php }?>
            </div>
        </div>
    </div>
    <div class="box" style="width: 300px;">
        <div>切换本地环境数据库</div>
        <div style="font-size: 8px;color: red;">当前（<span class="dbDsn"><?=$db['dsn']?></span>）</div>
        <button onclick="changeDb(1)">本地开发库</button>
        <button onclick="changeDb(2)">本地测试库</button>
        <button onclick="changeDb(3)">线上开发库</button>
        <button onclick="changeDb(4)">线上测试库</button>
    </div>
    <!--时间戳-->
    <div class="box">
        <div>当前时间戳:<span class="curTime"><?php echo time();?></span><button style="margin-left: 4px;" class="stopTime">暂停</button></div>
        <div>时间戳:<input type="text" name="curTime" style="width: 88px;"/><button style="margin-left: 4px;" class="changeTime">转换</button></div>
        <div>时间:<span class="curDate"></span></div>
    </div>
    <div style="clear:both;"></div>
</div>
<div class="rows">
    <!--蓝湖相关地址-->
    <div class="box">
        <a href="https://www.yuque.com/docs/share/d21f1af8-3f16-45a9-82bc-48361373ba77" target="_blank">语雀--技术文档</a><br />
        <a href="https://lanhuapp.com/url/ZqenX" target="_blank">蓝湖--业务后台</a><br />
        <a href="https://lanhuapp.com/url/lNE7I" target="_blank">蓝湖--知了有方</a><br />
        <a href="https://lanhuapp.com/url/2yz0x" target="_blank">蓝湖--知了小助手</a><br />
        <a href="https://lanhuapp.com/url/J8ejf" target="_blank">蓝湖--知了有方app</a><br />
        <a href="https://lanhuapp.com/url/eR829" target="_blank">蓝湖--义诊项目</a><br />
        <a href="https://lanhuapp.com/url/KHml7" target="_blank">蓝湖--知了诊室完整版</a><br />
        <a href="https://lanhuapp.com/url/n0Qzk" target="_blank">蓝湖--云诊室迭代</a><br />
        <a href="https://lanhuapp.com/url/qJPnI" target="_blank">蓝湖--知了有方网站</a><br />
        <a href="http://doctor.test.xlyhw.com/app_interface.html" target="_blank">APP接口文档</a><br />
        <a href="https://www.cnblogs.com/longfeiPHP/p/12859465.html" target="_blank">Git常用命令总结</a><br />
        <a href="https://www.cnblogs.com/longfeiPHP/p/13864433.html" target="_blank">Vim常用命令总结c</a><br />
        开发环境医生端:<br />
        消息 <span>http://doctor.test.xlyhw.com/im/session-list</span><br />
        我的 <span>http://doctor.test.xlyhw.com/personal-info/list</span><br />
        开发环境患者端:<br />
        我的 <span>http://patient.test.xlyhw.com/ucenter</span><br />
        我的医生 <span>http://patient.test.xlyhw.com/doctor/list</span><br />
        购药订单 <span>http://patient.test.xlyhw.com/order/index</span><br />
        服务订单 <span>http://patient.test.xlyhw.com/order/order-tcm-project-list</span><br />
    </div>
    <div class="box">
        <div>json格式化</div>
        <div style="width: 800px;">
            <textarea id="jsonContent" style="width: 100%;height: 200px;"></textarea>
            <button id="jsonFormat">格式化</button>
        </div>
    </div>
    <!--一到12月英文-->
    <div class="box" style="width: 380px;">
        <div class="threePercent">一月:January</div><div class="threePercent">二月:February</div><div class="threePercent">三月:March</div>
        <div class="threePercent">四月:April</div><div class="threePercent">五月:May</div><div class="threePercent">六月:June</div>
        <div class="threePercent">七月:July</div><div class="threePercent">八月:August</div><div class="threePercent">九月:September</div>
        <div class="threePercent">十月:October</div><div class="threePercent">十一月:November</div><div class="threePercent">十二月:December</div>
    </div>

    <div class="box" style="width: 380px;margin-top: 10px;">
        <div>sql中的数据转换成逗号分隔的字符串&nbsp;&nbsp;<button onclick="changeNum(this)">转换</button><button style="margin-left: 2px;" onclick="clearNum(this)">清空</button></div>
        <textarea style="width: 100%;height: 103px; margin-top: 5px;"></textarea>
    </div>


    <div style="clear:both;"></div>
    <hr />
</div>



<script>
    $(function () {
        /*当前时间戳*/
        var curTime = $(".curTime").html();
        var timeT = setInterval(function () {
            curTime++;
            $(".curTime").html(curTime);
        },1000);
        $(".stopTime").click(function (e) {
           clearInterval(timeT);
        });
        $(".changeTime").click(function (e) {
            var thisTime = $("input[name='curTime']").val();
            if(thisTime!=""){
                var d=new Date(thisTime*1000);
                $(".curDate").html(formatDate(d));
            }
        });
        $("#jsonFormat").click(function (e) {
            var jsonVal = $("#jsonContent").val();
            if (jsonVal=='') return false;
            $.ajax({
                url:"/tool/json-format",
                type:"POST",
                data:{jsonVal:jsonVal},
                success:function(res){
                    if (res.code==200){
                        $("#jsonContent").val(res.data.json);
                    }
                }
            });
        });
    });
    /**
     * 时间戳转换成时间
     */
    function formatDate(now) {
        var year=now.getFullYear();
        var month=now.getMonth()+1;
        var date=now.getDate();
        var hour=now.getHours();
        var minute=now.getMinutes();
        var second=now.getSeconds();
        return year+"-"+fixZero(month,2)+"-"+fixZero(date,2)+" "+fixZero(hour,2)+":"+fixZero(minute,2)+":"+fixZero(second,2);
    }
    /**
     * 前导0
     */
    function fixZero(num,length){
        var str=""+num;
        var len=str.length;     var s="";
        for(var i=length;i-->len;){
            s+="0";
        }
        return s+str;
    }
    /**
     * 加密解密
     * @param method
     */
    function opera(method){
        var num = $("input[name='num']").val();
        $.ajax({
            url:"/tool/unpass",
            type:"POST",
            data:{num:num,method:method},
            success:function(res){
                if(res.code==200){
                    $("input[name='num']").val(res.data['rs']);
                }
            }
        });
    }

    /**
     * 根据用户id查询openid
     */
    function getOpenId() {
        var userId = $("input[name='userid']").val();
        if (userId=='') return false;
        $.ajax({
            url:"/tool/openid",
            type:"POST",
            data: {userId:userId},
            success:function(res){
                if(res.code==200){
                    console.log(res);
                    $(".openid").html(res.data.openid);
                }
            }
        });
    }

    /**
     * 切换数据库
     * @param db 1/本地开发库 2/本地测试库 3/线上开发库 4/本地测试库
     */
    function changeDb(db){
        var confirms = true;
        if (db==3 || db==4) confirms = confirm("确定要切换到线上库吗，有可能会修改线上数据");
        if (confirms) {
            $.ajax({
                url:"/tool/change-db",
                type:"POST",
                data: {db:db},
                success:function(res){
                    if (res.code==200){
                        $(".dbDsn").html(res.data.dbDSN);
                    }
                }
            });
        }
    }

    /**
     * 切换php版本
     */
    function changePHP(version){
        var curVersion = $("#curVersion").html();
        if (curVersion==version) return true;/*版本相等*/
        $.ajax({
            url:"/tool/change-php",
            type:"POST",
            data:{version:version},
            success:function (res) {
                if(res.code==200){
                    alert("切换成功 请重启apache服务");
                    $("#curVersion").html(version);
                }
            }
        });
    }

    /**
     * 数据转换
     */
    function changeNum(obj) {
        let textArea = $(obj).parent().next();
        let numList = textArea.val();
        numList=numList.replace(/\n/g,",");
        textArea.val(numList);
    }

    /**
     * 清空数据
     */
    function clearNum(obj) {
        $(obj).parent().next().val("")
    }
</script>
<script type="text/javascript">
    /*web SQL 只有谷歌支持 几乎不用*/
    /*var db = openDatabase('mydb', '1.0', 'Test DB', 2 * 1024 * 1024);
    db.transaction(function (tx) {
        tx.executeSql('CREATE TABLE IF NOT EXISTS LOGS (id unique ,log)');
        tx.executeSql('INSERT INTO LOGS (id, log) VALUES (1, "菜鸟教程")');
        tx.executeSql('INSERT INTO LOGS (id, log) VALUES (2, "www.runoob.com")');
    });
    db.transaction(function (tx) {
        tx.executeSql('SELECT * FROM LOGS', [], function (tx,result) {
            var len = result.rows.length, i;
            for (i = 0; i < len; i++) {
                console.log(result.rows.item(i).log);
            }
        }, null);
    });*/
    /**
     * IndexedDB
     * */
    var db;/*数据库对象*/
    var objectStore;/*仓库(表)*/
    /**
     * 创建数据库
     */
    var request = window.indexedDB.open('myIndex', 3);/*该域中的数据库myIndex*/
    request.onerror = function (event) {
        console.log('open database error');
    };
    /**
     * 业务代码
     */
    request.onsuccess = function (event) {
        db = request.result;/*数据库对象*/
        // add();
        // read();
        // update();
        // remove();
        readAll();
        // console.log(db);
    };
    /**
     * 创建表
     */
    request.onupgradeneeded = function (event) {
        db = event.target.result;/*数据库对象*/
        if (!db.objectStoreNames.contains('person')){
            objectStore = db.createObjectStore('person',{keyPath:'id'});/*创建person仓库(表) 主键*/
            // objectStore = db.createObjectStore('person',{autoIncrement:true});/*自动创建主键*/
            objectStore.createIndex('name', 'name', {unique:false});
            objectStore.createIndex('email', 'email', {unique:true});
        }
        console.log(db);
    };
    /**
     * 插入数据
     */
    function add() {
        var request = db.transaction(['person'], 'readwrite')
            .objectStore('person')
            .add({ id: 1, name: '张三', age: 24, email: 'zhangsan@example.com' });
        request.onsuccess = function (event) {
            console.log('数据写入成功');
        };
        request.onerror = function (event) {
            console.log('数据写入失败');
        };
    }
    /**
     * 读取数据
     */
    function read() {
        var transaction = db.transaction(['person']);
        var objectStore = transaction.objectStore('person');
        var request = objectStore.get(1);
        request.onerror = function (event) {
            console.log('事物失败');
        };
        request.onsuccess = function (event) {
            if (request.result) {
                console.log('Name' + request.result.name);
                console.log('Age' + request.result.age);
                console.log('Email' + request.result.email);
            }else{
                console.log('未获得数据记录');
            }
        };
    }

    /**
     * 遍历数据
     */
    function readAll() {
        var objectStore = db.transaction(['person']).objectStore('person');
        objectStore.openCursor().onsuccess = function (event) {
            var cursor = event.target.result;
            if (cursor){
                console.log('Id:' + cursor.key);
                console.log('Name:' + cursor.value.name);
                console.log('Age:' + cursor.value.age);
                console.log('Email:' + cursor.value.email);
            } else {
                console.log('没有更多数据了');
            }
        }
    }

    /**
     * 更新数据
     */
    function update() {
        var request = db.transaction(['person'], 'readwrite')
            .objectStore('person')
            .put({ id: 1, name: '李四', age: 35, email: 'lisi@example.com' });
        request.onsuccess = function (event) {
            console.log('数据更新成功');
        };
        request.onerror = function (event) {
            console.log('数据更新失败');
        };
    }

    /**
     * 删除数据
     */
    function remove()
    {
        var request = db.transaction(['person'], 'readwrite')
            .objectStore('person')
            .delete(1);
        request.onsuccess = function (event) {
            console.log('数据删除成功');
        };
    }
</script>
<html>
<body>
    <style type="text/css">
        #div1 {width:198px; height:66px;padding:10px;border:1px solid #aaaaaa;}
    </style>
    <div id="div1" ondrop="drop(event)" ondragover="allowDrop(event)"></div>
    <img id="drag1" draggable="true" ondragstart="drag(event)" src="https://www.w3school.com.cn/i/eg_dragdrop_w3school.gif" />
    <p>计数:<output id="result"></output></p>
    <button onclick="startWorker()">开始 Worker</button>
    <button onclick="stopWorker()">停止 Worker</button>
</body>
</html>
<script type="text/javascript">
    /**
     * 拖动相关
     */
    function drag(event) {
        event.dataTransfer.setData("Text", event.target.id);
    }
    function drop(ev) {
        ev.preventDefault();
        var data = ev.dataTransfer.getData('Text');
        ev.target.appendChild(document.getElementById(data));
    }
    function allowDrop(ev) {
        ev.preventDefault();
    }

    /**
     * web worker
     */
    var w;
    function startWorker(){
        if (typeof(Worker)!=="undefined"){
            if (typeof(w)=="undefined"){
                w = new Worker("/js/demo_workers.js");
            }
            w.onmessage = function (event) {
                alert('a');
                document.getElementById("result").innerHTML = event.data;
            }
        } else {
            document.getElementById("result").innerHTML = "Sorry, your browser does not support Web Workers...";
        }
        alert('b');
    }
    function stopWorker() {
        w.terminate();
    }
</script>

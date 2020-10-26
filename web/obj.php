<?php
abstract class BaseT{
    protected $products = [];
    abstract public function write();
}
class T extends BaseT{
    public function write(){
        echo 'write';
    }
}
interface BaseT2{
    public function write();
}
class T2 implements BaseT2{
    public function write(){
        echo 'write';
    }
}

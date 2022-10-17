<?php
class site{

    //成员变量
    var $url;
    var $title;


    // 成员函数
    function setUrl($url){
        $this->url = $url;
    }

    function getUrl(){
        echo $this->url.PHP_EOL;
    }

    function setTitle($title){
        $this->title = $title;
    }
    function getTitle(){
        echo $this->title.PHP_EOL;
    }


}
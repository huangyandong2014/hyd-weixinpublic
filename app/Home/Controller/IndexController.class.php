<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function indexAction(){
    	$e = D('PublicAccountInfo')->getAllAccountList();
    	
    	var_dump($e);
    	$this->display('index');
    }
}
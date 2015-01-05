<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function indexAction(){
    	echo 'Hello World!';  
    	var_dump(C('LOG_LEVEL'));
    	
    }
}
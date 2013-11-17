<?php

App::uses('Controller', 'Controller');

class FacebookCpsController extends UsermgmtAppController {

    public $name = 'FacebookCps';
    public $uses=array();


    public function beforeFilter(){
        parent::beforeFilter();
        $this->Auth->allow('login','facebook_connect');
    }




    public function index(){
        $this->layout=false;
        echo 'hello'; exit;
    }




}

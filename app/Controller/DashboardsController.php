<?php
App::uses('AppController', 'Controller');
/**
 * Dashboards Controller
 *
 */
class DashboardsController extends AppController {

/**
 * Scaffold
 *
 * @var mixed
 */

    public function beforeFilter(){
        parent::beforeFilter();
        //$this->Auth->allow('index');
    }


	public function index(){

    }

}

<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Controller', 'Controller');
App::uses('UsersController', 'Usermgmt.Controller');
/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

    public $helpers = array('Js' => array('Jquery'));

    public $components = array(
        //'DebugKit.Toolbar' => array(/* array of settings */),
        'Session','RequestHandler','Paginator',
        'Auth' => array(
            'loginRedirect' => array('plugin'=>false,'controller' => 'dashboards', 'action' => 'index'),
            'logoutRedirect' => array('plugin'=>'usermgmt','controller' => 'users', 'action' => 'login'),
            'loginAction' => array('plugin'=>'usermgmt','controller' => 'users', 'action' => 'login'),
            'authenticate' => array(
                'Form' => array(
                    //'fields' => array('username' => 'email_address'),
                    'scope'  => array('User.is_active' => 1)
                )
            )
        ),

    );


    public function beforeFilter(){
        parent::beforeFilter();
        if($this->RequestHandler->responseType() == 'json'){
            $this->RequestHandler->setContent('json', 'application/json' );
        }
    }

}

<?php
App::uses('UsermgmtAppController', 'Usermgmt.Controller');
App::uses('AuthComponent','Controller/Component');

App::build(array('Vendor' => array(APP . 'Plugin' . DS .  'Usermgmt' . DS . 'Vendor' . DS )));
App::import('Vendor', 'base_facebook');
App::import('Vendor', 'facebook');

/**
 * Users Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 */
class UsersController extends UsermgmtAppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

    public function beforeFilter(){
        parent::beforeFilter();
        $this->Auth->allow('fbLogin','facebook_connect');
    }


    public function login() {

        $this->layout='login';
        if ($this->request->is('post')) {

            if ($this->Auth->login()) {

                $this->redirect($this->Auth->redirect());
            } else {
                $this->Session->setFlash(__('Invalid username or password, try again'));
            }
        }
    }


    public function logout() {
        $this->Session->destroy();
        $this->Session->delete('User');
        $this->redirect($this->Auth->logout());

    }



    function fbLogin(){

        $appId=Configure::read('Facebook.appId');
        $app_secret=Configure::read('Facebook.secret');
        $facebook = new Facebook(array(
            'appId'     =>  $appId,
            'secret'    => $app_secret,
        ));

        $loginUrl = $facebook->getLoginUrl(array(
            'scope'         => 'email,read_stream, publish_stream, user_birthday, user_location, user_work_history, user_hometown, user_photos',
            'redirect_uri'  =>  Configure::read('Facebook.redirect_uri'),
            'display'=>'popup'
        ));
        $this->redirect($loginUrl);
    }


    function facebook_connect(){

        $appId=Configure::read('Facebook.appId');
        $app_secret=Configure::read('Facebook.secret');

        $facebook = new Facebook(array(
            'appId'     =>  $appId,
            'secret'    => $app_secret,
        ));

        $user = $facebook->getUser();

        if($user){

            try{
                $user_profile = $facebook->api('/me');
                $this->_fbLogin($user_profile);
            }
            catch(FacebookApiException $e){
                error_log($e);
                $user = NULL;
            }
        }
        else
        {
            $this->Session->setFlash('Sorry.Please try again','default',array('class'=>'msg_req'));
            $this->redirect(array('action'=>'index'));
        }
    }





    function _fbLogin($fbData){

        $user = $this->User->find('first',array(
            'conditions'=>array('email_address'=>$fbData['email']),
            'contain'=>false
        ));
        if(empty($user)){
            $this->_fbRegister($fbData);
        }else{
            unset($user['User']['password']);
            $this->Auth->login($user['User']);
            $this->Session->setFlash(__('login successfull using facebook'),'default',array('class'=>'success'));
            $this->redirect(array('plugin'=>null,'controller'=>'dashboards','action'=>'index'));
        }
    }



    function _fbRegister($fbData){

        $userData['User'] = array(
            'facebookid'=>$fbData['id'],
            'is_active'=>1,
            'role_id'=>3,
            'first_name'=>$fbData['first_name'],
            'last_name'=>$fbData['last_name'],
            'username'=>$fbData['username'],
            'gender'=>$fbData['gender'],
            'password'=>$fbData['username'],
            'email_address'=>$fbData['email'],
            'date_of_birth'=>date('Y-m-d',strtotime($fbData['birthday'])),
        );

        if($this->User->save($userData)){
            $id = $this->User->id;
            $userData['User'] = array_merge($userData['User'], array('id' => $id));
            $this->Auth->login($userData['User']);
            $this->Session->setFlash(__('Registration successfull'),'default',array('class'=>'success'));
            $this->redirect(array('plugin'=>null,'controller'=>'dashboards','action'=>'index'));
        }else{
            $this->Session->setFlash(__('Registration failed'),'default',array('class'=>'error'));
            $this->Auth->loginAction();
        }
    }
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->User->recursive = 0;
		$this->set('users', $this->Paginator->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
		$this->set('user', $this->User->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved.'),'default',array('class'=>'success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'),'default',array('class'=>'error'));
			}
		}
		$roles = $this->User->Role->find('list',array('fields'=>array('id','role')));
		$this->set(compact('roles'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is(array('post', 'put'))) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved.'),'default',array('class'=>'success'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'),'default',array('class'=>'error'));
			}
		} else {
			$options = array('conditions' => array('User.' . $this->User->primaryKey => $id));
			$this->request->data = $this->User->find('first', $options);
		}
        $roles = $this->User->Role->find('list',array('fields'=>array('id','role')));
		$this->set(compact('roles'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->User->delete()) {
			$this->Session->setFlash(__('The user has been deleted.'));
		} else {
			$this->Session->setFlash(__('The user could not be deleted. Please, try again.'));
		}
		return $this->redirect(array('action' => 'index'));
	}}

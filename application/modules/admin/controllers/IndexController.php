<?php

class Admin_IndexController extends Zend_Controller_Action
{

    private $usersMapper;
    
    public function init()
    {
        /* Initialize action controller here */
        $this->_helper->layout->setLayout('admin-panel');
        $this->usersMapper = new Application_Model_DbTable_Users();
    }

    public function indexAction()
    {
        
    }
    
    public function loginAction()
    {
        $this->_helper->layout->disableLayout();
        
        // redirect logged in users
        if (Zend_Registry::getInstance()->get('auth')->hasIdentity()) {
            $this->_redirect('/admin');
        }

        $request = $this->getRequest();

        $form = new My_Forms_Login();
        
        // if POST data has been submitted
        if ($request->isPost() && $form->isValid($request->getPost())) {
            // prepare a database adapter for Zend_Auth
            $adapter = new Zend_Auth_Adapter_DbTable();
            $adapter->setTableName('users');
            $adapter->setIdentityColumn('nickname');
            $adapter->setCredentialColumn('password');
            $adapter->setCredentialTreatment('SHA1(?)');
            $adapter->setIdentity($form->getValue('nickname'));
            $adapter->setCredential($form->getValue('password'));

            // try to authenticate a user
            $auth = Zend_Registry::get('auth');
            $result = $auth->authenticate($adapter);

            // is the user valid one?
            switch ($result->getCode()) {

                case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND:
                    $this->view->error = 'Nie ma takiego użytkownika';
                    break;

                case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:
                    $this->view->error = 'Błędne hasło';
                    break;

                case Zend_Auth_Result::SUCCESS:
                    // get user object (ommit password_hash)
                    $user = $adapter->getResultRowObject(null, 'password');
                    if($user->is_banned){
                        $auth->clearIdentity();
                        $this->view->error = 'Nie zostałeś jeszcze zweryfikowany albo masz bana';
                        break;
                    }
                    // to help thwart session fixation/hijacking
                    if ($form->getValue('rememberMe') == 1) {
                        // remember the session for 604800s = 7 days
                        Zend_Session::rememberMe(604800);
                    } else {
                        // do not remember the session
                        Zend_Session::forgetMe();
                    }
                    // store user object in the session
                    $authStorage = $auth->getStorage();
                    $authStorage->write($user);
                    // save loggin info in database
                    $date = new DateTime();
                    $this->usersMapper->update(array('last_login' => $date->format('Y-m-d H:i:s')), "id=".$user->id);
                    $this->_redirect('/admin');
                    break;

                default:
                    $this->view->error = 'Błędny użytkownik lub/i hasło';
                    break;
            }
        }

        $this->view->form = $form;
    }
    
    public function logoutAction()
    {
        Zend_Registry::get('auth')->clearIdentity();
        $this->_redirect('/');
    }
    
    public function registerAction()
    {
        $this->_helper->layout->disableLayout();
        
        // redirect logged in users
        if (Zend_Registry::getInstance()->get('auth')->hasIdentity()) {
            $this->_redirect('/admin');
        }

        $request = $this->getRequest();

        $form = new My_Forms_Register();
        
        // if POST data has been submitted
        if ($request->isPost() && $form->isValid($request->getPost())) {
            $data = $form->getValues();

            if ($this->usersMapper->getSingleWithEmail($data['email']) != null) {
                // if the email already exists in the database
                $this->view->error = 'Adres e-mail jest zajęty';
            } else if ($this->usersMapper->getSingleWithUsername($data['nickname']) != null) {
                // if the username already exists in the database
                $this->view->error = 'Login zajęty';
            } else if ($data['password'] != $data['passwordAgain']) {
                // if both passwords do not match
                $this->view->error = 'Wprowadzone hasła różnią się';
            } else {
                $data['permissions'] = 'USER';
                $data['is_banned'] = 1;
                $date = new DateTime();
                $data['registration_date'] = $date->format('Y-m-d H:i:s');
                $this->usersMapper->add($data);
                $this->view->success = 'Zarejestrowano! Musisz poczekać na weryfikację'
                        . ' zanim będziesz mógł się zalogować.';
            }
        }

        $this->view->form = $form;
    }


}


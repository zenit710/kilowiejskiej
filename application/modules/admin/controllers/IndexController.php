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
        // redirect logged in users
        if (Zend_Registry::getInstance()->get('auth')->hasIdentity()) {
            $this->_redirect('/');
        }

        $request = $this->getRequest();
        $users = $this->_getTable('Users');

        $form = $this->_getForm('Register',
                                $this->_helper->url('register'));
        // if POST data has been submitted
        if ($request->isPost()) {
            // if the Register form has been submitted and the submitted data is valid
            if (isset($_POST['register']) && $form->isValid($_POST)) {

                $data = $form->getValues();

                if ($users->getSingleWithEmail($data['email']) != null) {
                    // if the email already exists in the database
                    $this->view->error = 'Email already taken';
                } else if ($users->getSingleWithUsername($data['username']) != null) {
                    // if the username already exists in the database
                    $this->view->error = 'Username already taken';
                } else if ($data['email'] != $data['emailAgain']) {
                    // if both emails do not match
                    $this->view->error = 'Both emails must be same';
                } else if ($data['password'] != $data['passwordAgain']) {
                    // if both passwords do not match
                    $this->view->error = 'Both passwords must be same';
                } else {

                    // everything is OK, let's send email with a verification string
                    // the verifications string is an sha1 hash of the email
                    $mail = new Zend_Mail();
                    $mail->setFrom('your@name.com', 'Your Name');
                    $mail->setSubject('Thank you for registering');
                    $mail->setBodyText('Dear Sir or Madam,

    Thank You for registering at yourwebsite.com. In order for your account to be
    activated please click on the following URI:

    http://yourwebsite.com/admin/login/email-verification?str=' . sha1($data['email'])
    . '
    Best Regards,
    Your Name and yourwebsite.com staff');
                    $mail->addTo($data['email'],
                                 $data['first_name'] . ' ' . $data['last_name']);

                    if (!$mail->send()) {
                        // email sending failed
                        $this->view->error = 'Failed to send email to the address you provided';
                    } else {

                        // email sent successfully, let's add the user to the database
                        unset($data['emailAgain'], $data['passwordAgain'],
                              $data['register']);
                        $data['salt'] = $this->_helper->RandomString(40);
                        $data['role'] = 'user';
                        $data['status'] = 'pending';
                        $users->add($data);
                        $this->view->success = 'Successfully registered';

                    }

                }

            }
        }

        $this->view->form = $form;
    }


}


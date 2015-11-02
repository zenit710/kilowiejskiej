<?php

class Admin_CategoryController extends Zend_Controller_Action
{

    const MAX_FILE_SIZE = 1048576;
    private $categoriesMapper;
    
    public function init()
    {
        $this->categoriesMapper = new Application_Model_DbTable_Categories();
        $this->_helper->layout->setLayout('admin-panel');
    }

    public function indexAction()
    {
        $this->view->categories = $this->categoriesMapper->fetchAll();
    }

    public function addAction()
    {
        $form = new My_Forms_Category();
        echo $form->render();
        
        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $values = $form->getValues();
            $values['slug'] = $this->generateSlug($values['name']);
            $values['picture'] = $this->preparePicture($values['slug']);
            $this->categoriesMapper->insert($values);
        }
    }
    
    public function editAction()
    {
        $id = $this->getParam('id');
        $category = $this->categoriesMapper->getById($id);
        if(!$category){
            return;
        }
        $form = new My_Forms_Category();
        $form->populate($category->toArray());
        echo $form->render();
        
        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $values = $form->getValues();
            $values['slug'] = $this->generateSlug($values['name']);
            $values['picture'] = $this->preparePicture($values['slug']);
            $this->categoriesMapper->update($values, 'id = '.$id);
        }
    }
    
    public function deleteAction()
    {
        $id = $this->getParam('id');
        $this->categoriesMapper->delete('id = '.$id);
        $this->redirect('/admin/category');
    }

    private function generateSlug($string)
    {
        $string = strtr($string, 'ĘęÓóĄąŚśŁłŹźŻżĆćŃń', 'EeOoAaSsLlZzZzCcNn');
        $string = strtr($string, 'ˇ¦¬±¶Ľ','ASZasz');
        $string = preg_replace("'[[:punct:][:space:]]'",'-',$string);
        $string = strtolower($string);
        $znaki = '-'; 
        $powtorzen = 1;
        $string = preg_replace_callback('#(['.$znaki.'])\1{'.$powtorzen.',}#', create_function('$a', 'return substr($a[0], 0,'.$powtorzen.');'), $string);
        return $string;
    }
    
    private function preparePicture($name)
    {
        $tmp = $_FILES['picture']['tmp_name'];
        $size = $_FILES['picture']['size'];
        $picName = $_FILES['picture']['name'];
        //$type = $_FILES['picture']['type'];
        $ext = substr($picName,strpos($picName,'.'));
        $newPicName = $name.$ext;
        if (is_uploaded_file($tmp)) {
            if ($size > self::MAX_FILE_SIZE) {
                echo 'Błąd! Plik jest za duży!';
            } else {
                move_uploaded_file($tmp, $_SERVER['DOCUMENT_ROOT'].'/img/kw/cat/'.$newPicName);
                return $newPicName;
            }
        } else {
           echo 'Błąd przy przesyłaniu danych!';
        }
    }

}


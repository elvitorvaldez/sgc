<?php
namespace Application\Controller;
 
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Authentication\AuthenticationService;
//use Zend\Authentication\Adapter\Ldap as AuthAdapter;
use Zend\Session\Container;
//use Zend\Config\Reader\Ini as ConfigReader;
//use Zend\Config\Config;
//use Zend\Ldap\Ldap;
use Application\Form\LoginForm;
use Zend\View\Model\ViewModel;
 
class IndexController extends AbstractActionController {
 
    /**
     * Form Authenticate
     * @return type
     */
    public function indexAction() {
        $auth = new AuthenticationService();
        $flashMessenger = $this->flashMessenger();
        $this->layout("layout/layout_scg.phtml");
        $user_session = new Container('user');
       
        if (!$user_session->username) {
            
            if ($flashMessenger->hasMessages()) {
                $flashMessenger->getMessages();  
            }
        } else {
             return $this->redirect()->toRoute('dashboard');
        }
        
        //$form = new LoginForm();
       // return array('form' => $form);
        /*return new ViewModel(array(
            'form' => $form
        ));*/
        
    }
 
    
}

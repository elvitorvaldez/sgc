<?php
namespace Application\Controller;
 
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\Plugin\Layout;

 
class NotificacionesController extends AbstractActionController {
 
    /**
     * Form Authenticate
     * @return type
     */
    public function indexAction() {
       $this->layout("layout/layout_dashboard.phtml");
        $user_session = new Container('user');
     
 
        if(isset($user_session->username))
         {
           $plugin = $this->controlAccess();            
           $user_session=$plugin->getTiempo("$this->redirect()->toRoute('home')");
         }
        else
        {
            return $this->redirect()->toRoute('home');
        }
    }
 
    
}

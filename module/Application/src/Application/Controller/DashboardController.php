<?php
namespace Application\Controller;
 
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Zend\Db\Adapter\Adapter;


 
class DashboardController extends AbstractActionController {
    
    /**
     * Form Authenticate
     * @return type
     */
    public function indexAction() {
        $this->layout("layout/layout_dashboard.phtml");
        $user_session = new Container('user');   
        
		$plugin = $this->controlAccess();
        $user_session=$plugin->getPerfil();   
		       
        if(isset($user_session->username))
         {
           
		   $user_session=$plugin->getTiempo("$this->redirect()->toRoute('home')");   
           /* die(print_r($this->controller()));z
            
            $sql = new \Zend\Db\Sql\Sql($this->getAdapter());
            $select = $sql->select();
            $select->from('COMPANYM1');
            $statement = $sql->prepareStatementForSqlObject($select);
            $results = $statement->execute();
            print_r($results);
            die();
            
            */
         
   
            

         }
        else
        {
            return $this->redirect()->toRoute('home');
        }
    }
 
    
}

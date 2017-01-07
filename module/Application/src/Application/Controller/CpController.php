<?php
namespace Application\Controller;
 
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\Plugin\Layout;
use Zend\Db\Adapter\Adapter;
    use Zend\Db\TableGateway\AbstractTableGateway;
    use Zend\Db\Sql\Select; 

    use Application\Model\CpTable;
 
class CpController extends AbstractActionController {
 
    /**
     * Form Authenticate
     * @return type
     */
     protected $cpTable;      
     
        
    public function __construct(CpTable $cpTable) {
        $this->cpTable = $cpTable;
    }
    
    
     
     public function indexAction()
     {
        //$this->layout("layout/layout_tables.phtml");
       $cp="";
             return new ViewModel(array(
             'colonias' => $this->getCpTable()->getSuburbByCp($cp),
         ));
     
     }
     
       public function getCpTable()
     {
           
          
         if (!$this->cpTable) {
              
             //$sm = $this->getServiceLocator();
             $sm = $this->serviceLocator;
             //ahora el error es aqui
             $this->cpTable = $sm->get('Application\Model\CpTable');
         }
      
         return $this->cpTable;
         
         
            
     }
    
}

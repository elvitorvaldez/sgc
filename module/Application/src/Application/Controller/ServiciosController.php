<?php
namespace Application\Controller;
 
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\Plugin\Layout;
use Zend\Db\Adapter\Adapter;
    use Zend\Db\TableGateway\AbstractTableGateway;
    use Zend\Db\Sql\Select; 

    use Application\Model\ServiciosTable;
 
class ServiciosController extends AbstractActionController {
 
    /**
     * Form Authenticate
     * @return type
     */
     protected $serviciosTable;      
        
    public function __construct(ServiciosTable $serviciosTable) {
        $this->serviciosTable = $serviciosTable;
    }
    
    
     
     public function indexAction()
     {
        $this->layout("layout/layout_tables.phtml");
        
       
             return new ViewModel(array(
             'catServicios' => $this->getServiciosTable()->fetchAll(),
         ));
     
     }
     
        public function getServiciosTable()
     {
           
          
         if (!$this->serviciosTable) {
              
             //$sm = $this->getServiceLocator();
             $sm = $this->serviceLocator;
             //ahora el error es aqui
             $this->serviciosTable = $sm->get('Application\Model\ServiciosTable');
         }
      
         return $this->serviciosTable;
         
         
            
     }
    
}

<?php
namespace Application\Controller;
 
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\Plugin\Layout;
use Zend\Db\Adapter\Adapter;
    use Zend\Db\TableGateway\AbstractTableGateway;
    use Zend\Db\Sql\Select; 

    use Application\Model\CatServiciosTable;
 
class CatServiciosController extends AbstractActionController {
 
    /**
     * Form Authenticate
     * @return type
     */
     protected $catServiciosTable;      
     
        
    public function __construct(CatServiciosTable $catServiciosTable) {
        $this->catServiciosTable = $catServiciosTable;
    }
    
    
     
     public function indexAction()
     {
        $this->layout("layout/layout_tables.phtml");
        
       
             return new ViewModel(array(
             'catServicios' => $this->getCatServiciosTable()->fetchAll(),
         ));
     
     }
     
       public function getCatServiciosTable()
     {
           
          
         if (!$this->catServiciosTable) {
              
             //$sm = $this->getServiceLocator();
             $sm = $this->serviceLocator;
             //ahora el error es aqui
             $this->catServiciosTable = $sm->get('Application\Model\CatServiciosTable');
         }
      
         return $this->catServiciosTable;
         
         
            
     }
    
}

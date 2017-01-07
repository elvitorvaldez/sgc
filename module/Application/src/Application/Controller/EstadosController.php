<?php
namespace Application\Controller;
 
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\Plugin\Layout;
use Zend\Db\Adapter\Adapter;
    use Zend\Db\TableGateway\AbstractTableGateway;
    use Zend\Db\Sql\Select; 

    use Application\Model\EstadosTable;
 
class EstadosController extends AbstractActionController {
 
    /**
     * Form Authenticate
     * @return type
     */
     protected $estadosTable;      
     
        
    public function __construct(EstadosTable $estadosTable) {
        $this->estadosTable = $estadosTable;
    }
    
    
     
     public function indexAction()
     {
        //$this->layout("layout/layout_tables.phtml");
       $idEstado="";
             return new ViewModel(array(
             'estado' => $this->getEstadosTable()->getEstadoById($idEstado),
         ));
     
     }
     
       public function getEstadosTable()
     {
           
          
         if (!$this->estadosTable) {
              
             //$sm = $this->getServiceLocator();
             $sm = $this->serviceLocator;
             //ahora el error es aqui
             $this->estadosTable = $sm->get('Application\Model\EstadosTable');
         }
      
         return $this->estadosTable;
         
         
            
     }
    
}

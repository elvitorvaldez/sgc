<?php
namespace Application\Controller;
 
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\Plugin\Layout;
use Zend\Db\Adapter\Adapter;
    use Zend\Db\TableGateway\AbstractTableGateway;
    use Zend\Db\Sql\Select; 

    use Application\Model\MunicipiosTable;
 
class MunicipiosController extends AbstractActionController {
 
    /**
     * Form Authenticate
     * @return type
     */
     protected $municipiosTable;  
     protected $estadosTable;   
     
        
    public function __construct(MunicipiosTable $municipiosTable) {
        $this->municipiosTable = $municipiosTable;
    }
    
    
     
     public function indexAction()
     {
        //$this->layout("layout/layout_tables.phtml");
       $idMunicipio="";
             return new ViewModel(array(
             'estado' => $this->getMunicipiosTable()->getSuburbById($idMunicipio),
         ));
     
     }
     
     
    
     
     
     
       public function getMunicipiosTable()
     {
           
          
         if (!$this->municipiosTable) {
              
             //$sm = $this->getServiceLocator();
             $sm = $this->serviceLocator;
             //ahora el error es aqui
             $this->municipiosTable = $sm->get('Application\Model\MunicipiosTable');
         }
      
         return $this->municipiosTable;
     
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

<?php   
namespace Application\ControllerFactory;
use \Zend\ServiceManager\FactoryInterface;
use \Zend\ServiceManager\ServiceLocatorInterface;
use Application\Controller\EmpresasController as EmpresasController;

class EmpresasFactory implements FactoryInterface
{
    protected $adapter;
    public function createService(ServiceLocatorInterface $serviceLocator) { 
       //if (!$this->adapter) {
       $sm   = $serviceLocator->getServiceLocator();  
       
       $EmpresasTable= $sm->get('Application\Model\EmpresasTable');  
           
      
       $EmpresasController = new EmpresasController($EmpresasTable);   
       
        return $EmpresasController;
       // }
    }
}
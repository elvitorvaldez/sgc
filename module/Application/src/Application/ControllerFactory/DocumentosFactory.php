<?php   

namespace Application\ControllerFactory;
use \Zend\ServiceManager\FactoryInterface;
use \Zend\ServiceManager\ServiceLocatorInterface;
use Application\Controller\FilepmController as FilepmController;  

class DocumentosFactory implements FactoryInterface
{
    protected $adapter;
    public function createService(ServiceLocatorInterface $serviceLocator) { 
       //if (!$this->adapter) {
       $sm   = $serviceLocator->getServiceLocator();  
       
       $DocumentosTable= $sm->get('Application\Model\DocumentosTable');     
           
        
       $FilepmController = new FilepmController($DocumentosTable);   
       
        return $FilepmController;    
       // }
    }
}
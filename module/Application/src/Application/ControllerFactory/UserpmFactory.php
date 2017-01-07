<?php   

namespace Application\ControllerFactory;
use \Zend\ServiceManager\FactoryInterface;
use \Zend\ServiceManager\ServiceLocatorInterface;
use Application\Controller\UserpmController as UserpmController;

class UserpmFactory implements FactoryInterface
{
    protected $adapter;
    public function createService(ServiceLocatorInterface $serviceLocator) { 
       //if (!$this->adapter) {
       $sm   = $serviceLocator->getServiceLocator();  
       
       $UserpmTable= $sm->get('Application\Model\UserpmTable');  
           
        
       $UserpmController = new UserPMController($UserpmTable);   
       
        return $UserpmController;  
       // }
    }
}
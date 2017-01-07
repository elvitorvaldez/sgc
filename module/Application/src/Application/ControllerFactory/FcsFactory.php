<?php

namespace Application\ControllerFactory;
use \Zend\ServiceManager\FactoryInterface;
use \Zend\ServiceManager\ServiceLocatorInterface;
use Application\Controller\FcsController as FcsController;

class FcsFactory implements FactoryInterface
{
    protected $adapter;
    public function createService(ServiceLocatorInterface $serviceLocator) { 
       //if (!$this->adapter) {
       $sm   = $serviceLocator->getServiceLocator();  
       
       $FcsTable= $sm->get('Application\Model\FcsTable');    
      
       $FcsController = new FcsController($FcsTable);   
       
        return $FcsController;
       // }
    }
}
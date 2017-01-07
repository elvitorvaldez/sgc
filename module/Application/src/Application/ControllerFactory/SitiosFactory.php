<?php

namespace Application\ControllerFactory;
use \Zend\ServiceManager\FactoryInterface;
use \Zend\ServiceManager\ServiceLocatorInterface;
use Application\Controller\SitiosController as SitiosController;

class SitiosFactory implements FactoryInterface
{
    protected $adapter;
    public function createService(ServiceLocatorInterface $serviceLocator) { 
       //if (!$this->adapter) {
       $sm   = $serviceLocator->getServiceLocator();  
       
       $SitiosTable= $sm->get('Application\Model\SitiosTable');    
      
       $SitiosController = new SitiosController($SitiosTable);   
       
        return $SitiosController;
       // }
    }
}
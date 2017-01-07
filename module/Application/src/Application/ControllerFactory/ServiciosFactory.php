<?php

namespace Application\ControllerFactory;
use \Zend\ServiceManager\FactoryInterface;
use \Zend\ServiceManager\ServiceLocatorInterface;
use Application\Controller\ServiciosController as ServiciosController;

class ServiciosFactory implements FactoryInterface
{
    protected $adapter;
    public function createService(ServiceLocatorInterface $serviceLocator) { 
       //if (!$this->adapter) {
       $sm   = $serviceLocator->getServiceLocator();  
       $ServiciosTable= $sm->get('Application\Model\ServiciosTable');
       $ServiciosController = new ServiciosController($ServiciosTable);
       
       //$AlbumControllerAdapter=$this->getServiceLocator()->get('Album\Model\AlbumTable');
        return $ServiciosController;
       // }
    }
}
<?php

namespace Application\ControllerFactory;
use \Zend\ServiceManager\FactoryInterface;
use \Zend\ServiceManager\ServiceLocatorInterface;
use Application\Controller\CatServiciosController as CatServiciosController;

class CatServiciosFactory implements FactoryInterface
{
    protected $adapter;
    public function createService(ServiceLocatorInterface $serviceLocator) { 
       //if (!$this->adapter) {
       $sm   = $serviceLocator->getServiceLocator();  
       $CatServiciosTable= $sm->get('Application\Model\CatServiciosTable');
       $CatServiciosController = new CatServiciosController($CatServiciosTable);
       
       //$AlbumControllerAdapter=$this->getServiceLocator()->get('Album\Model\AlbumTable');
        return $CatServiciosController;
       // }
    }
}
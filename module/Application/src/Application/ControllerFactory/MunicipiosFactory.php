<?php

namespace Application\ControllerFactory;
use \Zend\ServiceManager\FactoryInterface;
use \Zend\ServiceManager\ServiceLocatorInterface;
use Application\Controller\MunicipiosController as MunicipiosController;

class MunicipiosFactory implements FactoryInterface
{
    protected $adapter;
    public function createService(ServiceLocatorInterface $serviceLocator) { 
       //if (!$this->adapter) {
       $sm   = $serviceLocator->getServiceLocator();
       $MunicipiosTable= $sm->get('Application\Model\MunicipiosTable');
       $municipiosController = new CpController($MunicipiosTable);
       
       //$AlbumControllerAdapter=$this->getServiceLocator()->get('Album\Model\AlbumTable');
        
        return $municipiosController;
       // }
    }
}
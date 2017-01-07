<?php

namespace Application\ControllerFactory;
use \Zend\ServiceManager\FactoryInterface;
use \Zend\ServiceManager\ServiceLocatorInterface;
use Application\Controller\EstadosController as EstadosController;

class EstadosFactory implements FactoryInterface
{
    protected $adapter;
    public function createService(ServiceLocatorInterface $serviceLocator) { 
       //if (!$this->adapter) {
       $sm   = $serviceLocator->getServiceLocator();
       $EstadosTable= $sm->get('Application\Model\EstadosTable');
       $estadosController = new CpController($EstadosTable);
       
       //$AlbumControllerAdapter=$this->getServiceLocator()->get('Album\Model\AlbumTable');
        
        return $estadosController;
       // }
    }
}
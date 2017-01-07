<?php

namespace Application\ControllerFactory;
use \Zend\ServiceManager\FactoryInterface;
use \Zend\ServiceManager\ServiceLocatorInterface;
use Application\Controller\AlbumController as AlbumController;

class AlbumFactory implements FactoryInterface
{
    protected $adapter;
    public function createService(ServiceLocatorInterface $serviceLocator) { 
       //if (!$this->adapter) {
       $sm   = $serviceLocator->getServiceLocator();
       $AlbumTable= $sm->get('Application\Model\AlbumTable');
       $albumController = new AlbumController($AlbumTable);
       
       //$AlbumControllerAdapter=$this->getServiceLocator()->get('Album\Model\AlbumTable');
        
        return $albumController;
       // }
    }
}
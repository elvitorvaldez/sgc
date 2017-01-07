<?php

namespace Application\ControllerFactory;
use \Zend\ServiceManager\FactoryInterface;
use \Zend\ServiceManager\ServiceLocatorInterface;
use Application\Controller\CatCapController as CatCapController;

class CatCapFactory implements FactoryInterface
{
    protected $adapter;
    public function createService(ServiceLocatorInterface $serviceLocator) { 
       //if (!$this->adapter) {
       $sm   = $serviceLocator->getServiceLocator();  
       $CatCapTable= $sm->get('Application\Model\CatCapTable');
       $CatCapController = new CatCapController($CatCapTable);
       
       //$AlbumControllerAdapter=$this->getServiceLocator()->get('Album\Model\AlbumTable');
        return $CatCapController;       
       // }
    }
}
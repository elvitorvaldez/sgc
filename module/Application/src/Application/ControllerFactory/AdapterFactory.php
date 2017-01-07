<?php     
namespace Application\ControllerFactory;
use \Zend\ServiceManager\FactoryInterface;
use \Zend\ServiceManager\ServiceLocatorInterface;
use Application\Controller\AlbumController;
 
class AdapterFactory implements FactoryInterface
{
    protected $adapter;
    public function createService(ServiceLocatorInterface $serviceLocator) {
        /* @var $serviceLocator \Zend\Mvc\Controller\ControllerManager */
       
       if (!$this->adapter) {
       //$sm = $this->getServiceLocator();
           
       $sm   = $serviceLocator->getServiceLocator();
      // die(print_r($sm));
       // Y aqui el error!!
       //$AlbumControllerAdapter=$this->getServiceLocator()->get('Application\Controller\‌AlbumController');
       $this->adapter = $sm->get('Zend\Db\Adapter\Adapter');
       
   
        //$controller = new AlbumController($AlbumControllerAdapter);
        return $this->adapter;
    }
    }
}
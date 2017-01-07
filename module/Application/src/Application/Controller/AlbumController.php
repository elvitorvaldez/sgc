<?php
namespace Application\Controller;
 
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\Plugin\Layout;
use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select; 
use Application\Model\AlbumTable;


class AlbumController extends AbstractActionController {
 
    /**
     * Form Authenticate
     * @return type
     */
     protected $albumTable;      
     
     
    
   public function __construct(AlbumTable $albumTable) {
        $this->albumTable = $albumTable;
    }
     
     public function indexAction()
     {
         $this->layout("layout/layout_tables.phtml");
     return new ViewModel(array(
             'albums' => $this->getAlbumTable()->fetchAll(),
         ));
     }
     
       public function getAlbumTable()
     {
         if (!$this->albumTable) {
             //$sm = $this->getServiceLocator();
             $sm = $this->serviceLocator;
             //ahora el error es aqui
             $this->albumTable = $sm->get('Application\Model\AlbumTable');
         }
         return $this->albumTable;
     }
    
}
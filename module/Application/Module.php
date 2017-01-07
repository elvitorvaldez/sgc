<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
/*
 *  Adaptadores disponibles
    //Application\Db\AdapterSM   (adaptador de services manager)
    //Zend\Db\Adapter\Adapter     (adaptador default SGC)
 */
  
namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Session\SessionManager;
use Zend\Session\Container;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Application\Model\Album;
use Application\Model\AlbumTable;

use Application\Model\CatServicios;
use Application\Model\CatServiciosTable;

use Application\Model\Servicios;
use Application\Model\ServiciosTable;

use Application\Model\Empresas;
use Application\Model\EmpresasTable;

use Application\Model\Sitios;
use Application\Model\SitiosTable;

use Application\Model\Cp;
use Application\Model\CpTable;

use Application\Model\Estados;
use Application\Model\EstadosTable;

use Application\Model\Municipios;
use Application\Model\MunicipiosTable;

use Application\Model\Fcs;
use Application\Model\FcsTable;

use Application\Model\CatCap;
use Application\Model\CatCapTable;  

use Application\Model\Userpm;  
use Application\Model\UserpmTable;  

use Application\Model\Documentos;  
use Application\Model\DocumentosTable;  

use Application\Model\DeleteEmpresas;
use Application\Model\DeleteEmpresasTable;

class Module {

    protected $adapter;

    public function onBootstrap(MvcEvent $e) {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $this->bootstrapSession($e);
    }

    public function bootstrapSession($e) {
        $session = $e->getApplication()
                ->getServiceManager()
                ->get('Zend\Session\SessionManager');
        $session->start();

        $container = new Container('initialized');
        if (!isset($container->init)) {
            $serviceManager = $e->getApplication()->getServiceManager();
            $request = $serviceManager->get('Request');

            $session->regenerateId(true);
            $container->init = 1;
            $container->remoteAddr = $request->getServer()->get('REMOTE_ADDR');
            $container->httpUserAgent = $request->getServer()->get('HTTP_USER_AGENT');

            $config = $serviceManager->get('Config');
            if (!isset($config['session'])) {
                return;
            }

            $sessionConfig = $config['session'];
            if (isset($sessionConfig['validators'])) {
                $chain = $session->getValidatorChain();

                foreach ($sessionConfig['validators'] as $validator) {
                    switch ($validator) {
                        case 'Zend\Session\Validator\HttpUserAgent':
                            $validator = new $validator($container->httpUserAgent);
                            break;
                        case 'Zend\Session\Validator\RemoteAddr':
                            $validator = new $validator($container->remoteAddr);
                            break;
                        default:
                            $validator = new $validator();
                    }

                    $chain->attach('session.validate', array($validator, 'isValid'));
                }
            }
        }
    }

    public function getControllerConfig() {
        return array(
            'factories' => array(
            ),
        );

        //The getConfig() and getAutoloaderConfig() methods are omitted for brevity
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /*  public function getServiceConfiguration()
      {
      return array(
      'factories' => array(
      'db-adapter' => function($sm) {
      $config = $sm->get('config');
      $config = $config['db'];
      $dbAdapter = new DbAdapter($config);
      return $dbAdapter;
      },
      ),
      );
      } */

    public function getServiceConfig() {
        return array(
            'factories' => array(
                'Application\Model\AlbumTable' => function($sm) {
                    $tableGateway = $sm->get('AlbumTableGateway');
                    $table = new AlbumTable($tableGateway);
                    return $table;
                },
                'AlbumTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Album());
                    return new TableGateway('album', $dbAdapter, null, $resultSetPrototype);
                },
                        
                        
                'Application\Model\CatServiciosTable' => function($sm) {
                    $tableGateway = $sm->get('CatServiciosTableGateway');
                    $table = new CatServiciosTable($tableGateway);
                    return $table;
                },
                'CatServiciosTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new CatServicios());
                    return new TableGateway('cat_servicio', $dbAdapter, null, $resultSetPrototype);
                },        
                        
                      
                'Application\Model\EmpresasTable' => function($sm) {
                	$tableGateway = $sm->get('EmpresasTableGateway');
                    $table = new EmpresasTable($tableGateway);
                    return $table;
                    
                },
                'EmpresasTableGateway' => function ($sm) {
                	     
					$dbAdapter = $sm->get("Application\Db\AdapterSM");       
					
					$resultSetPrototype = new ResultSet();                    
                    $resultSetPrototype->setArrayObjectPrototype(new Empresas());
					                  
                    return new TableGateway('COMPANYM1', $dbAdapter, null, $resultSetPrototype);   
                },                
                        
                'Application\Model\SitiosTable' => function($sm) {
                    $tableGateway = $sm->get('SitiosTableGateway'); 
                    $table = new SitiosTable($tableGateway);  
                    return $table;
                },
                        
                'SitiosTableGateway' => function ($sm) {                         
                    $dbAdapter = $sm->get("Application\Db\AdapterSM");      
                    $resultSetPrototype = new ResultSet();                    
                    $resultSetPrototype->setArrayObjectPrototype(new Sitios());

                    return new TableGateway('LOCM1', $dbAdapter, null, $resultSetPrototype);    
                    
                   // return new TableGateway('[10.1.17.19].SM9.dbo.LOCM1', $dbAdapter, null, $resultSetPrototype);
                },   
                        
                       
                'Application\Model\CpTable' => function($sm) {
                    $tableGateway = $sm->get('CpTableGateway'); 
                    $table = new CpTable($tableGateway);  
                    return $table;
                },
                        
                'CpTableGateway' => function ($sm) {                    
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();                    
                    $resultSetPrototype->setArrayObjectPrototype(new Cp());
                    return new TableGateway('CAT_CODIGOPOSTAL', $dbAdapter, null, $resultSetPrototype);    
                    
                   // return new TableGateway('[10.1.17.19].SM9.dbo.LOCM1', $dbAdapter, null, $resultSetPrototype);
                },          
                     
                        
               'Application\Model\EstadosTable' => function($sm) {
                    $tableGateway = $sm->get('EstadosTableGateway'); 
                    $table = new EstadosTable($tableGateway);  
                    return $table;
                },
                        
                'EstadosTableGateway' => function ($sm) {                    
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();                    
                    $resultSetPrototype->setArrayObjectPrototype(new Estados());

                    return new TableGateway('CAT_ESTADO', $dbAdapter, null, $resultSetPrototype);    
                    
                   // return new TableGateway('[10.1.17.19].SM9.dbo.LOCM1', $dbAdapter, null, $resultSetPrototype);
                },    
                     
                        
                'Application\Model\MunicipiosTable' => function($sm) {
                    $tableGateway = $sm->get('MunicipiosTableGateway'); 
                    $table = new MunicipiosTable($tableGateway);  
                    return $table;
                },
                        
                'MunicipiosTableGateway' => function ($sm) {                    
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();                    
                    $resultSetPrototype->setArrayObjectPrototype(new Municipios());

                    return new TableGateway('CAT_MUNICIPIO', $dbAdapter, null, $resultSetPrototype);    
                    
                   // return new TableGateway('[10.1.17.19].SM9.dbo.LOCM1', $dbAdapter, null, $resultSetPrototype);
                },          
                        
                        
                 'Application\Model\FcsTable' => function($sm) {
                    $tableGateway = $sm->get('FcsTableGateway'); 
                    $table = new FcsTable($tableGateway);  
                    return $table;
                },
                        
                'FcsTableGateway' => function ($sm) {                    
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();                    
                    $resultSetPrototype->setArrayObjectPrototype(new Fcs());

                    return new TableGateway('SOLICITUD', $dbAdapter, null, $resultSetPrototype);    
                    
                   // return new TableGateway('[10.1.17.19].SM9.dbo.LOCM1', $dbAdapter, null, $resultSetPrototype);
                },      
                                    
                'Application\Model\ServiciosTable' => function($sm) {
                    $tableGateway = $sm->get('ServiciosTableGateway'); 
                    $table = new ServiciosTable($tableGateway);  
                    return $table;
                },
                        
                'ServiciosTableGateway' => function ($sm) {   
 
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();     
                    $resultSetPrototype->setArrayObjectPrototype(new Servicios());

                    return new TableGateway('SERVICIOS', $dbAdapter, null, $resultSetPrototype);    
                    
                   // return new TableGateway('[10.1.17.19].SM9.dbo.LOCM1', $dbAdapter, null, $resultSetPrototype);
                },          
                     
				'Application\Model\CatCapTable' => function($sm) {
                    $tableGateway = $sm->get('CatCapTableGateway');
                    $table = new CatCapTable($tableGateway);
                    return $table;
                },
                'CatCapTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new CatCap());  
                    return new TableGateway('CAT_CAP', $dbAdapter, null, $resultSetPrototype);   
                },	        
                
				'Application\Model\UserpmTable' => function($sm) {
                    $tableGateway = $sm->get('UserpmTableGateway');
                    $table = new UserpmTable($tableGateway);
                    return $table;
                },
                'UserpmTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Userpm());        
                    return new TableGateway('PM_EMPRESAS', $dbAdapter, null, $resultSetPrototype);     
                },	        
                        
                'Application\Model\DocumentosTable' => function($sm) {
                    $tableGateway = $sm->get('DocumentosTableGateway');
                    $table = new DocumentosTable($tableGateway);
                    return $table;
                },
                'DocumentosTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Documentos());        
                    return new TableGateway('DOCUMENTOS', $dbAdapter, null, $resultSetPrototype);          
                },	     
                
				'Application\Model\DeleteEmpresasTable' => function($sm) {
                	$tableGateway = $sm->get('DeleteEmpresasTableGateway');
                    $table = new DeleteEmpresasTable($tableGateway);
                    return $table;
                    
                },
                'DeleteEmpresasTableGateway' => function ($sm) {
                	     
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');   
					    
					$resultSetPrototype = new ResultSet();                    
                    $resultSetPrototype->setArrayObjectPrototype(new DeleteEmpresas());
					                  
                    return new TableGateway('COMPANYM1', $dbAdapter, null, $resultSetPrototype);   
                },           
                        
                        
                'Zend\Session\SessionManager' => function ($sm) {
                    $config = $sm->get('config');
					   if (isset($config['session'])) {
                        $session = $config['session'];  

                        $sessionConfig = null;
                        if (isset($session['config'])) {
                            $class = isset($session['config']['class']) ? $session['config']['class'] : 'Zend\Session\Config\SessionConfig';
                            $options = isset($session['config']['options']) ? $session['config']['options'] : array();
                            $sessionConfig = new $class();
                            $sessionConfig->setOptions($options);
                        }

                        $sessionStorage = null;
                        if (isset($session['storage'])) {
                            $class = $session['storage'];
                            $sessionStorage = new $class();
                        }

                        $sessionSaveHandler = null;
                        if (isset($session['save_handler'])) {
                            // class should be fetched from service manager since it will require constructor arguments
                            $sessionSaveHandler = $sm->get($session['save_handler']);  
                        }

                        $sessionManager = new SessionManager($sessionConfig, $sessionStorage, $sessionSaveHandler);
                    } else {
                        $sessionManager = new SessionManager();
                    }
                    Container::setDefaultManager($sessionManager);
                    return $sessionManager;
                },
                    ), 
                );
            }

        }
        
<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Application;
  
return array(
	'view_helper_config' => array(
		'flashmessenger' => array(
			'message_open_format' => '< div%s >',
			'message_separator_string' => '< br >',
			'message_close_string' => '<  /div >',
		) ,
	) ,
	'router' => array(
		'routes' => array(
			'login' => array(
				'type' => 'Zend\Mvc\Router\Http\Literal',
				'options' => array(
					'route' => '/login',
					'defaults' => array(
						'controller' => 'Login',
						'action' => 'login',
					) ,
				) ,
			) ,
			'dashboard' => array(
				'type' => 'Zend\Mvc\Router\Http\Literal',
				'options' => array(
					'route' => '/dashboard',
					'defaults' => array(
						'controller' => 'Dashboard',
						'action' => 'index',
					) ,
				) ,
			) ,
			'home' => array(
				'type' => 'Zend\Mvc\Router\Http\Literal',
				'options' => array(
					'route' => '/',
					'defaults' => array(
						'controller' => 'Index',
						'action' => 'index',
					) ,
				) ,
			) ,
			'logout' => array(
				'type' => 'Zend\Mvc\Router\Http\Literal',
				'options' => array(
					'route' => '/logout',
					'defaults' => array(
						'controller' => 'Login',
						'action' => 'logout',
					) ,
				) ,
			) ,
			'album' => array(
				'type' => 'Zend\Mvc\Router\Http\Literal',
				'options' => array(
					'route' => '/album',
					'defaults' => array(
						'controller' => 'Album',
						'action' => 'index',
					) ,
				) ,
			) ,
			'catservicios' => array(
				'type' => 'Zend\Mvc\Router\Http\Literal',
				'options' => array(
					'route' => '/catservicios',
					'defaults' => array(
						'controller' => 'CatServicios',
						'action' => 'index',
					) ,
				) ,
			) ,
			
			'catcap' => array(
				'type' => 'Zend\Mvc\Router\Http\Literal',
				'options' => array(
					'route' => '/catcap',
					'defaults' => array(
						'controller' => 'CatCap',  
						'action' => 'index',
					) ,
				) ,
			) ,
                    
                    
                    
                    'servicios' => array(
				'type' => 'Zend\Mvc\Router\Http\Literal',
				'options' => array(
					'route' => '/servicios',
					'defaults' => array(
						'controller' => 'Servicios',
						'action' => 'index',
					) ,
				) ,
			) ,
                    
                    
                    
                       'cp' => array(
				'type' => 'Zend\Mvc\Router\Http\Literal',
				'options' => array(
					'route' => '/cp',
					'defaults' => array(
						'controller' => 'Cp',
						'action' => 'index',
					) ,
				) ,
			) ,
                    
                    
                    'estados' => array(
				'type' => 'Zend\Mvc\Router\Http\Literal',
				'options' => array(
					'route' => '/estados',
					'defaults' => array(
						'controller' => 'Estados',
						'action' => 'index',
					) ,
				) ,
			) ,
                    
                    
                    
                    'municipios' => array(
				'type' => 'Zend\Mvc\Router\Http\Literal',
				'options' => array(
					'route' => '/municipios',
					'defaults' => array(
						'controller' => 'Municipios',
						'action' => 'index',
					) ,
				) ,
			) ,
                    
                    
                    'sitios' => array(
				'type' => 'Zend\Mvc\Router\Http\Literal',
				'options' => array(
					'route' => '/sitios',
					'defaults' => array(
						'controller' => 'Sitios',
						'action' => 'index',
					) ,
				) ,
			) ,    
                    
			'sites' => array(
				'type' => 'segment',
				'options' => array(
					'route' =>'/sites[/:action][/:id][/:kind]',    
					'constraints' => array(

						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
                        'kind' => '[a-zA-Z0-9_-]*'
					) ,
					'defaults' => array(
						'controller' => 'Sitios',
						'action' => ''
					) ,
				) ,
			) ,
                    
                    
              'fcss' => array(
				'type' => 'Zend\Mvc\Router\Http\Literal',
				'options' => array(
					'route' => '/fcss',
					'defaults' => array(
						'controller' => 'Fcs',
						'action' => 'index',
					) ,
				) ,
			) ,                  
                    
			'fcs' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/fcs[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',     
						'id' => '[a-zA-Z0-9_-]*',     
					) ,         
					'defaults' => array(
						'controller' => 'Fcs',
						'action' => 'index'
					) ,
				) ,
			) ,
                    
              'rse' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/rse[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[0-9]+',
					) ,
					'defaults' => array(
						'controller' => 'Rse',
						'action' => 'index'
					) ,
				) ,
			) ,
                    
            
			 'userpm' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/userpm[/:id]',      
					'constraints' => array(
						//'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z][a-zA-Z0-9_-]*',  
					) ,
					'defaults' => array(
						'controller' => 'Userpm',    
						'action' => 'index'
					) ,
				) ,
			) ,
			
			'pmuser' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/pmuser[/:action]',      
					'constraints' => array(
					'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						//'id' => '[a-zA-Z][a-zA-Z0-9_-]*',  
					) ,
					'defaults' => array(
						'controller' => 'Userpm',    
						'action' => 'index'
					) ,       
				) ,
			) ,
			        
            
			'filepm' => array(  
				'type' => 'segment',
				'options' => array(           
					'route' => '/filepm[/:action]',      
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						//'id' => '[a-zA-Z][a-zA-Z0-9_-]*',  
					) ,
					'defaults' => array(
						'controller' => 'Filepm',              
						'action' => 'index'   
					) ,
				) ,
			) ,
			        
			'notificaciones' => array(
				'type' => 'Zend\Mvc\Router\Http\Literal',
				'options' => array(
					'route' => '/notificaciones',
					'defaults' => array(
						'controller' => 'Notificaciones',
						'action' => 'index',
					) ,
				) ,
			) ,
			
			
			'empresas' => array(
				'type' => 'Zend\Mvc\Router\Http\Literal',
				'options' => array(
					'route' => '/empresas',
					'defaults' => array(
						'controller' => 'Empresas',
						'action' => 'index',
					) ,
				) ,
			) ,
			'enterprise' => array(
				'type' => 'segment',
				'options' => array(
					'route' => '/enterprise[/:action][/:id]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
						'id' => '[a-zA-Z0-9_-]*',
					) ,
					
						
					'defaults' => array(
						'controller' => 'Empresas',
						'action' => 'index'
					) ,
				) ,   
			) ,
		) ,
	) ,
	'service_manager' => array(
		'abstract_factories' => array(
			'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
			'Zend\Log\LoggerAbstractServiceFactory',
			'Zend\Db\Adapter\AdapterAbstractServiceFactory',
		) ,
		'factories' => array(
			'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
			'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
		) ,
	) ,
	'translator' => array(
		'locale' => 'es_MX',
		'translation_file_patterns' => array(
			array(
				'type' => 'gettext',
				'base_dir' => __DIR__ . '/../language',
				'pattern' => '%s.mo',
			) ,
		) ,
	) ,
	'controllers' => array(
		'factories' => array(
			'Album' => 'Application\ControllerFactory\AlbumFactory',
			'CatServicios' => 'Application\ControllerFactory\CatServiciosFactory',
            'Servicios' => 'Application\ControllerFactory\ServiciosFactory',
			'Empresas' => 'Application\ControllerFactory\EmpresasFactory',
            'Sitios' => 'Application\ControllerFactory\SitiosFactory',
            'Fcs' => 'Application\ControllerFactory\FcsFactory',
            'CatCap' => 'Application\ControllerFactory\CatCapFactory',  
            'UserPM ' => 'Application\ControllerFactory\UserpmFactory',
            'Documentos' => 'Application\ControllerFactory\DocumentosFactory',     
		) ,       
		'invokables' => array(
		     //'CreateTableInstance' => 'Application\Service\ManageDatabaseAdapters',
			'Dashboard' => 'Application\Controller\DashboardController',
			'Index' => 'Application\Controller\IndexController',
			'Login' => 'Application\Controller\LoginController',
			'Resource' => 'Application\Controller\ResourceController',
             'Cp' => 'Application\Controller\CpController',
             'Estados' => 'Application\Controller\EstadosController',
            'Municipios' => 'Application\Controller\MunicipiosController',
			// 'Empresas' => 'Application\Controller\EmpresasController',
                        // 'Sitios' => 'Application\Controller\SitiosController',
			//'Fcs' => 'Application\Controller\FcsController',
            'Rse' => 'Application\Controller\RseController',   
			'Notificaciones' => 'Application\Controller\NotificacionesController',
			'Filepm' => 'Application\Controller\FilepmController',  
			//'UserPM' => 'Application\Controller\UserpmController',             
		) ,
	) ,
	
	'controller_plugins' => array(
	    'invokables' => array(
	        'controlAccess' => 'Application\Controller\Plugin\controlAccess',
	        'moneyFormat' => 'Application\Controller\Plugin\moneyFormat',       
	    )
	),
	
	
	'view_manager' => array(
		'display_not_found_reason' => true,
		'display_exceptions' => true,
		'doctype' => 'HTML5',
		'not_found_template' => 'error/404',
		'exception_template' => 'error/index',
               'strategies' => array(
                'ViewJsonStrategy',
                 ),
		'template_map' => array(
			'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
			'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
			'error/404' => __DIR__ . '/../view/error/404.phtml',
			'error/index' => __DIR__ . '/../view/error/index.phtml',
			'resource/header' => __DIR__ . '/../view/application/resource/header.phtml',
			'resource/menu' => __DIR__ . '/../view/application/resource/menu.phtml',
			'resource/footer' => __DIR__ . '/../view/application/resource/footer.phtml',
		) ,
		'template_path_stack' => array(
			__DIR__ . '/../view',
		) ,
	) ,

	// Placeholder for console routes

	'console' => array(
		'router' => array(
			'routes' => array() ,
		) ,
	) ,

	// Doctrine config


    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
            'Zend\Db\Adapter\AdapterAbstractServiceFactory',
        ),
        'factories' => array(
            'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
//    'controllers' => array(
//        'factories'    => array(
//            'Album'           => 'Application\ControllerFactory\AlbumFactory',
//            'CatServicios'    => 'Application\ControllerFactory\CatServiciosFactory',
//            'Empresas'        => 'Application\ControllerFactory\EmpresasFactory', 
//            'Sitios'          => 'Application\ControllerFactory\SitiosFactory', 
//            'Fcs'             => 'Application\ControllerFactory\FcsFactory', 
//        ),
//        
//        
//        'invokables' => array(
//            'Dashboard' => 'Application\Controller\DashboardController',
//            'Index' => 'Application\Controller\IndexController',
//            'Login' => 'Application\Controller\LoginController',
//         
//            'Resource' => 'Application\Controller\ResourceController',
//            //'Empresas' => 'Application\Controller\EmpresasController',
//            //'Sitios' => 'Application\Controller\SitiosController',
//            //'Fcs' => 'Application\Controller\FcsController',
//            'Rse' => 'Application\Controller\FcsController',
//            'Notificaciones' => 'Application\Controller\NotificacionesController',
//            
//        ),
//    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
            'resource/header'           => __DIR__ . '/../view/application/resource/header.phtml', 
            'resource/menu'           => __DIR__ . '/../view/application/resource/menu.phtml', 
            'resource/footer'           => __DIR__ . '/../view/application/resource/footer.phtml', 
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
 
);

        

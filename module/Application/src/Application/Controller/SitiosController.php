<?php
namespace Application\Controller;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
//use Zend\Mvc\Controller\Plugin\Layout;
//use Zend\Db\Adapter\Adapter;

use Zend\Http\Request;
use Application\Model\SitiosTable;     
//use Application\Model\EmpresasTable;
use Zend\Json\Json;  
use Zend\View\Model\JsonModel;



class SitiosController extends AbstractActionController
{
    
    /**
     * Form Authenticate
     * @return type
     */
  
    protected $sitiosTable;
    protected $empresasTable;

    public function getSitiosTable()
    {
        if (!$this->sitiosTable) {
            $sm                = $this->serviceLocator;
            $this->sitiosTable = $sm->get('Application\Model\SitiosTable');
        }
        return $this->sitiosTable;
    }
    
    
    public function getEmpresasTable()
    {
        if (!$this->empresasTable) {
            $sm                  = $this->serviceLocator;
            $this->empresasTable = $sm->get('Application\Model\EmpresasTable');
        }
        return $this->empresasTable;
    }
	
	public function getFcsTable()  
    {
        if (!$this->FcsTable) {
            $sm             = $this->serviceLocator;
            $this->FcsTable = $sm->get('Application\Model\FcsTable');
        }
        return $this->FcsTable;
    }
    
    public function __construct(SitiosTable $sitiosTable)
    {
        $this->sitiosTable = $sitiosTable;
    }
    
        public function searchsiteAction()
    {
        
         $location = $this->request->getPost("LOCATION");
        
         $existe=$this->getSitiosTable()->getSiteByLocationCode($location);
        
         $url =  '/sgc/public/sites/altas';
      
         if (isset($existe->LOCATION))
         {
           $jsonModel = array(
                    'url' => $url,   
                    'location'=>$location,
                    'success' => false
                );
         }
         else
         {
             $jsonModel = array(
                    'url' => $url, 
                   'location'=>$location,
                    'success' => true
                );
         }
          
                $response  = $this->getResponse();
                $response->getHeaders()->addHeaderLine('Content-Type', 'application/json');
                $response->setContent(json_encode($jsonModel));
               
               
                return $response;
              
            
            
    }
    
    
    
    public function indexAction()
    {
    	
        $this->layout("layout/layout_tables.phtml");
        $user_session = new Container('user');
        
        //infoSitios
          
        if (isset($user_session->username)) {
        
		$plugin = $this->controlAccess();            
        $user_session=$plugin->getTiempo("$this->redirect()->toRoute('home')");	
			
          $kind = $this->params('kind');  
           $idEmpresa = $this->params('id');
		   
           $nombreEmpresa= $this->getEmpresasTable()->getEnterpriseById($idEmpresa);
		   
		         
		     
		             
             if (isset($kind) and $kind=="find")
                 {
                    	
                 	  
                    return new ViewModel(array(
                    
                    'sitios' => $this->getSitiosTable()->listSitesByEnterpriseName($nombreEmpresa->COMPANY),
                    'idEmpresa' => $idEmpresa,
                    'nomEmpresa' => $nombreEmpresa,
                    'empresas' => $this->getEmpresasTable()->fetchAll()     
                    
                ));    
			
                 }
            else
            {
                
                return new ViewModel(array(
                'sitios' => $this->getSitiosTable()->fetchAll(),
                'empresas' => $this->getEmpresasTable()->fetchAll()   
            )); 
            }
            
                  
            
        } else {
            return $this->redirect()->toRoute('home');
        }
    }
    
    
    
    //    En éste controlador, se manejan por separado las funciones de 
    //    altas y editar, ya que altas requiere el id de empresa
    //    y editar requiere el id del sitio
    
    public function altasAction()
    {
        //abrir el formulario de alta de sitios
        $this->layout("layout/layout_dashboard.phtml");
        $user_session = new Container('user');
        
        
        if (isset($user_session->username)) {
        	
			$plugin = $this->controlAccess();            
            $user_session=$plugin->getTiempo("$this->redirect()->toRoute('home')");
			
            $idEmpresa = $this->params('id');
            
            if (!empty($idEmpresa)) {

                return new ViewModel(array(
                    'empresa' => $this->getEmpresasTable()->getEnterpriseById($idEmpresa)
                ));
    
            } else {
                
                return new ViewModel(array(
                    'empresas' => $this->getEmpresasTable()->fetchAll()
                ));
            }
        } else {
            return $this->redirect()->toRoute('home');
        }
    }
    
    
    public function editarAction()
    {
        //abrir el formulario de edición de sitios
        $this->layout("layout/layout_dashboard.phtml");
        $user_session = new Container('user');
        
        
        if (isset($user_session->username)) {
        	
			$plugin = $this->controlAccess();            
            $user_session=$plugin->getTiempo("$this->redirect()->toRoute('home')");
			
            $idSitio = $this->params('id');
            if (!empty($idSitio)) {
            	
				
                return new ViewModel(array(
                    'sitio' => $this->getSitiosTable()->getSiteByLocationCode($idSitio)
                ));
            }
        } else {
            return $this->redirect()->toRoute('home');
        }
    }
    
    
    
    public function saveAction()
    {
        $request = new Request();
        $request->setMethod(Request::METHOD_POST);
        
        $user_session = new Container('user');
        $msg          = "";
        
        if (isset($user_session->username)) {
            $idEmpresa = $this->params('id');
        
            try {
                
                $data = new \Application\Model\Sitios;
                
                $option=explode("$",$this->params()->fromPost('COMPANY'));  
				              
				$data->CUSTOMER_ID   = mb_strtoupper($option[0],'UTF-8');
				$data->COMPANY       = mb_strtoupper($option[1],'UTF-8');
                $data->LOCATION_NAME = mb_strtoupper( $this->params()->fromPost('LOCATION_NAME'),'UTF-8');
                $data->LOCATION      = $this->params()->fromPost('LOCATION');   
                //$data->LOCATION_CODE = $this->params()->fromPost('LOCATION');
                $data->IDTELMEX = $this->params()->fromPost('LOCATION');                  
                $data->ADDRESS       = mb_strtoupper( $this->params()->fromPost('ADDRESS'),'UTF-8');
                $data->ZIP           = $this->params()->fromPost('ZIP');
                $data->CITY          = mb_strtoupper( $this->params()->fromPost('CITY'),'UTF-8');
                $data->STATE         = mb_strtoupper( $this->params()->fromPost('STATE'),'UTF-8');
                $data->PHONE         = $this->params()->fromPost('PHONE');
                $data->SYSMODTIME    = date("Y-m-d H:i:s ");
                $data->SYSMODUSER    = $user_session->mail;
                  
                
                if (trim($data->LOCATION_NAME) == "") {
                    $msg = "El nombre del sitio es obligatorio";
                }
                
                if (trim($data->COMPANY) == "") {
                    $msg = "El nombre de la empresa es obligatorio";
                }
                
                if ($msg) {
                    
                    $jsonModel = array(
                        'message' => $msg,
                        'success' => false
                    );
                   
                }
                
                
                $this->getSitiosTable()->saveAlbum($data, '', $idEmpresa);
              
                 $company=$this->getEmpresasTable()->getEnterpriseIdByName($idEmpresa);
          
                if (isset($idEmpresa))
                {$url =  '../../sites/index/'.$idEmpresa.'/find';}    
                else{$url =  '../sitios';}  
                $jsonModel = array(   
                    'url' => $url,   
                    'message' => 'Sitio registrado exitosamente',
                    'success' => true
                );
                $response  = $this->getResponse();
                $response->getHeaders()->addHeaderLine('Content-Type', 'application/json');
                $response->setContent(json_encode($jsonModel));
                return $response;
                
            }
            catch (Exception $e) {
                echo 'Error al guardar ', $e->getMessage(), "\n";
            }
            
        } else {
            return $this->redirect()->toRoute('home');
        }
        
    }
    
    
    public function editingAction()
    {
        
        $request = new Request();
        $request->setMethod(Request::METHOD_POST);
        
        $user_session = new Container('user');
        $msg          = "";
        
        //print_r($user_session);
       
        if (isset($user_session->username)) {
            $idSitio = $this->params('id');
            
            try {
                
                $data = new \Application\Model\Sitios;
                
                $data->COMPANY       = $this->params()->fromPost('COMPANY');
                $data->LOCATION_NAME = $this->params()->fromPost('LOCATION_NAME');
                $data->LOCATION = $this->params()->fromPost('LOCATION');
                $data->LOCATION_CODE = $this->params()->fromPost('LOCATION');
                $data->ADDRESS       = $this->params()->fromPost('ADDRESS');
                $data->ZIP           = $this->params()->fromPost('ZIP');
                $data->CITY          = $this->params()->fromPost('CITY');
                $data->STATE         = $this->params()->fromPost('STATE');
                $data->PHONE         = $this->params()->fromPost('PHONE');
                $data->SYSMODTIME    = date("Y-m-d H:i:s ");
                $data->SYSMODUSER    = $user_session->mail;
                
                
                if (trim($data->LOCATION_NAME) == "") {
                    $msg = "El Nombre del sitio es obligatorio";
                }
                
                if (trim($data->COMPANY) == "") {
                    $msg = "El nombre de la empresa es obligatorio";
                }
                
                if ($msg) {
                    
                    $jsonModel = array(
                        'message' => $msg,
                        'success' => false
                    );
                    
                    
                }
                
                
                $this->getSitiosTable()->saveAlbum($data, $idSitio, $data->COMPANY);
                
                //$company=$this->getEmpresasTable()->getEnterpriseIdByName($idEmpresa);
                $company=$this->getSitiosTable()->getEnterpriseBySiteId($idSitio);
          
                if (isset($company))
                {$url =  '../../sites/index/'.$company.'/find';}
                else{$url =  '../../sitios';}  
                
                $jsonModel = array(
                    'url' => $url,
                    'message' => 'Sitio editado exitosamente',
                    'success' => true
                );
                $response  = $this->getResponse();
                $response->getHeaders()->addHeaderLine('Content-Type', 'application/json');
                $response->setContent(json_encode($jsonModel));
                return $response;
            }
            catch (Exception $e) {
                echo 'Error al guardar ', $e->getMessage(), "\n";
            }
            
        } else {
            return $this->redirect()->toRoute('home');
        }
        
    }
    
    public function detalleAction()
    {
        $this->layout("layout/layout_simple.phtml");    
        $user_session = new Container('user');        
        
        
        if (isset($user_session->username)) {
        	
			$plugin = $this->controlAccess();            
            $user_session=$plugin->getTiempo("$this->redirect()->toRoute('home')");
			
            $idSitio = $this->params('id');
			
			//echo $idSitio;
			  
            if (!empty($idSitio)) {
            	
				
				$sites=$this->getSitiosTable()->findSite($idSitio);				     
				$fcsAll= $this->getFcsTable()->fetchAllFcsBySitio($idSitio);
				
				if(!empty($fcsAll)){       
				 foreach ($fcsAll as $fcsAll){
						$sitesDetail[]=array(
						  'LOCATION_NAME'=>$sites->LOCATION_NAME,
						  'LOCATION'=>$sites->LOCATION,      
						  'SERVICIO_ID' => $fcsAll->SERVICIO_ID,           
								              								    
						);     
				 }
                         
				}else{
					$sitesDetail[]=array(
						  'LOCATION_NAME'=>$sites->LOCATION_NAME,
						  'LOCATION'=>$sites->LOCATION,             								    
						);     
				}
				   
                return new ViewModel(array(
                    'sitio' => $sitesDetail  
                ));
            }
        } else {
            return $this->redirect()->toRoute('home');
        }
    }
    
    public function borrarAction()
    {   
        
        $request = new Request();
        $request->setMethod(Request::METHOD_POST);
		 
        
        $this->layout("layout/layout_dashboard.phtml");
        $user_session = new Container('user');
        $msg          = "";
        
        if (isset($user_session->username)) {
            $idSitio = $this->params()->fromPost('idSitio');     
			        
            $company=$this->getSitiosTable()->getEnterpriseBySiteId($idSitio);
          //die($company);  
            try {   
                
                $this->getSitiosTable()->deleteAlbum($idSitio);
				
               if (isset($company))
			   
                {$url =  '../../../sites/index/'.$company.'/find';}      
                else{$url =  'sitios';}
				
                $jsonModel = array(
                    'url' => $url,
                    'message' => 'Sitio eliminado exitosamente',
                    'success' => true  
                );
				
                $response  = $this->getResponse();
                $response->getHeaders()->addHeaderLine('Content-Type', 'application/json');
                $response->setContent(json_encode($jsonModel));
                return $response;
            }
            catch (Exception $e) {
                echo 'Error al eliminar ', $e->getMessage(), "\n";
            }
            
        } else {
            return $this->redirect()->toRoute('home');
        }
    }
    
    
    
    
    
    
    
}

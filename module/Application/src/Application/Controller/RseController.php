<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\Plugin\Layout;
use Application\Model\FcsTable;
use Zend\View\Model\JsonModel;
use Application\Model\CatServiciosTable;
use Zend\Http\Request;
use PHPMailer;
use Zend\Json\Json;


class RseController extends AbstractActionController
{
    protected $FcsTable;
    protected $empresasTable;
    protected $sitiosTable;
    protected $catServiciosTable;
    protected $serviciosTable;
    /**
     * Form Authenticate
     * @return type
     */
    
    
    public function getServiciosTable()
     {
           
          
         if (!$this->serviciosTable) {
              
             //$sm = $this->getServiceLocator();
             $sm = $this->serviceLocator;
             //ahora el error es aqui
             $this->serviciosTable = $sm->get('Application\Model\ServiciosTable');
         }
      
         return $this->serviciosTable;
         
         
            
     }
    
    public function indexAction()
    {
    	 $this->layout("layout/layout_tables.phtml");
         $user_session = new Container('user');  
        
        
        if (isset($user_session->username)) {
        	
			$plugin = $this->controlAccess();            
            $user_session=$plugin->getTiempo("$this->redirect()->toRoute('home')");
			
		
		 $empresas = $this->getEmpresasTable()->fetchAll();
		   
		 foreach ($empresas as $empresas){
              if(!empty($empresas->CUSTOMER_ID)){  
              	
              	$rseAll=$this->getFcsTable()->fetchAllRse($empresas->CUSTOMER_ID);
				  
				   foreach ($rseAll as $rseAll){
                    if(!empty($rseAll->CUSTOMER_ID) && ($empresas->CUSTOMER_ID)==($rseAll->CUSTOMER_ID)){  
                        $rses[]=array('CUSTOMER_ID'=>$empresas->CUSTOMER_ID,
                                      'COMPANY'=>$empresas->COMPANY,
                                      'SOLICITUD_ID'=>$rseAll->SOLICITUD_ID,
                                      'SERVICIO_ID'=>$rseAll->SERVICIO_ID,
                                      'FOLIO'=>$rseAll->FOLIO,
                                      'LOCATION_ID'=>$rseAll->LOCATION_ID,
                                      'TIPOMOVIMIENTO'=>$rseAll->TIPOMOVIMIENTO,
                                      'FECHAEMISION'=>$rseAll->FECHAEMISION,                                                  
                                     ); 
                    } 
                 }				  
			  }
		  } 
                 
			 $rses=(object)$rses;  
            return new ViewModel(array(
                'rses' => $rses  
            ));
          
		 } else {  
            return $this->redirect()->toRoute('home');
        } 	
		 	
    }
    
    
    public function altasAction()
    {
        //abrir el formulario de alta de empresas
        $idFcs = $this->params('id');
		$search="u";  
        
        $this->layout("layout/layout_dashboard.phtml");
        $user_session = new Container('user');
        
        
        if (isset($user_session->username)) {
        	
			$plugin = $this->controlAccess();            
            $user_session=$plugin->getTiempo("$this->redirect()->toRoute('home')");
            
            if (isset($idFcs)) {
            	
				
				 $fcss = $this->getFcsTable()->fetchFcsById($idFcs);
				  
				 
				foreach ($fcss as $fcss ){
				         
					if(!empty($fcss->CUSTOMER_ID)){
						  						  
						$empresa= $this->getEmpresasTable()->getCompanyByRfc($fcss->CUSTOMER_ID); 
						$sitio = $this->getSitiosTable()->findSite($fcss->LOCATION_ID);   
						
						$fcssbyId=array('COMPANY'=>$empresa->COMPANY,
        								'LOCATION_NAME'=>$sitio->LOCATION_NAME,     
		                                'SERVICIO_ID'=>$fcss->SERVICIO_ID,
        								'CAP_ID'=>$fcss->CAP_ID,
           								'FOLIO'=>$fcss->FOLIO,
        								'CUSTOMER_ID'=>$fcss->CUSTOMER_ID,
        								'LOCATION_ID'=>$fcss->LOCATION_ID,
                                        'TIPOMOVIMIENTO'=>$fcss->TIPOMOVIMIENTO,
								        'FECHAEMISION'=>$fcss->FECHAEMISION,
								        'QO'=>$fcss->QO,
								        'ORDENAPROVISIONAMIENTO'=>$fcss->ORDENAPROVISIONAMIENTO,
								        'COTIZACION'=>$fcss->COTIZACION,
								        'RESPONSABLEINSTALACION'=>$fcss->RESPONSABLEINSTALACION,
        								'CASOINSTALACION'=>$fcss->CASOINSTALACION,
        								'TELRESPSITIO'=>$fcss->TELRESPSITIO,
        								'RSENOMSOLICITO'=>$fcss->RSENOMSOLICITO,
        								'RSENOMAUTORIZO'=>$fcss->RSENOMAUTORIZO,  	   										    
						); 
					}
				}	  

                $fcssbyId= (object)$fcssbyId;  
				
                $serviciosAsoc = $this->getServiciosTable()->getServicesByFcs($idFcs);  
				
				$serviciosAsoc= (object)$serviciosAsoc;
				       
				   
				if(!empty($serviciosAsoc)){
				  
													   				     	     
				foreach ($serviciosAsoc as  $serviciosAsoc){
					        
					$catServicio=$this->getCatServiciosTable()->getCatServicio($serviciosAsoc->CATSERVICIO_ID);
					 
				  
					if(!empty($catServicio)){   		   				
						$codigo=$catServicio->CODIGO;	  
						foreach ($catServicio as  $catServicio){   
							$codigo=$catServicio->CODIGO;
						}	
					}   
					 
					$getServices[]=array(
					   'ID_COMPONENTE'=>$serviciosAsoc->ID_COMPONENTE,
					   'CANTIDAD'=>$serviciosAsoc->CANTIDAD,
					   'PRECIO'=>$serviciosAsoc->PRECIO,
					   'SERVICIO_ID'=>$serviciosAsoc->SERVICIO_ID,
					   'CATSERVICIO_ID'=>$serviciosAsoc->CATSERVICIO_ID,					   
					   'CODIGO'=>$codigo, 								  	   										    
					); 
					                         
				   } //end foreach 
				//} //else size  
				$getServices=(object)$getServices;   
				}   
				
				
				
                return new ViewModel(array(
                    'fcss' => $fcssbyId,  
                    'servicios' => $this->getCatServiciosTable()->fetchAll($search),
                    
                ));
                
            } else {
     
                return new ViewModel(array(
                    'empresas' => $this->getEmpresasTable()->getCompaniesForCombo(),    
                    'servicios' => $this->getCatServiciosTable()->fetchAll($search)  
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
            $idFCS  = $this->params('id');
            $status = $this->params('controller');
            
            try {
                
                $data  = new \Application\Model\Fcs;
                $data2 = new \Application\Model\Servicios;
                
                $lasPartes = $this->params()->fromPost('numparte');
                
                foreach ($lasPartes as $numparte => $valor) {
                    if (trim($valor) == "" && $numparte > 0) {
                        $msg = "Favor de agregar un numero de parte para el servicio " . $numparte;
                    }
                    
                }
                
                
                $losComponentes = $this->params()->fromPost('idComponente');
                
                foreach ($losComponentes as $componente => $valor) {
                    if (trim($valor) == "" && $componente > 0) {
                        $msg = "Favor de agregar un id de parte para el servicio " . $componente;
                    }
                    
                }
                
                
                $lasCantidades = $this->params()->fromPost('cantidad');
                
                foreach ($lasCantidades as $cantidad => $valor) {
                    if (trim($valor) == "" && $cantidad > 0) {
                        $msg = "Favor de agregar cantidad para el servicio " . $cantidad;
                    }
                    
                }
                
                $losPrecios = $this->params()->fromPost('precio');
                
                foreach ($losPrecios as $precio => $valor) {
                    if (trim($valor) == "" && $precio > 0) {
                        $msg = "Favor de agregar cantidad para el servicio " . $precio;
                    }
                    
                }
                
                
                $data->CUSTOMER_ID = $this->params()->fromPost('CUSTOMER_ID');
                  
                    
                   $fechaEm = date('Y-m-d H:i:s');
                   
                $nombreEmpresa = $this->params()->fromPost('COMPANY');
                  if (isset($nombreEmpresa))
                  {
                $customerId = $this->getEmpresasTable()->getEnterpriseIdByName($nombreEmpresa);
                $data->CUSTOMER_ID            = $customerId->CUSTOMER_ID;
                  }
                $getlastRow = $this->getFcsTable()->getLastFcs();
                
                $lastRow = $getlastRow->SOLICITUD_ID;
                
                
                if (isset($idFCS)) {
                    $data->SOLICITUD_ID = $idFCS;
                } else {
                    // $ultimo = $lastRow;
                    $data->SOLICITUD_ID = $lastRow;
                }

            
                
                $data->SERVICIO_ID            = $this->params()->fromPost('SERVICIO_ID');
                $data->FECHAEMISION           = $fechaEm;
                $data->STATUS                 = (int)1;
                $data->LOCATION_ID               = $this->params()->fromPost('LOCATION');
                $data->LOCATION               = $this->params()->fromPost('LOCATION_ID');
                $data->FOLIO                  = $this->params()->fromPost('FOLIO');
                $data->TIPOMOVIMIENTO         = "ALTA";
               
                $data->RSEFLAG                = (int)1;
                $data->RSENOMSOLICITO        = $this->params()->fromPost('RSENOMSOLICITO');
                $data->RSENOMAUTORIZO        = $this->params()->fromPost('RSENOMAUTORIZO');
               
                if (trim($data->LOCATION_ID) == "" || trim($data->COMPANY) == "") {
                    $msg = "Campo obligatorio";
                }
                
                
                if ($msg) {
                    
                    $jsonModel = array(
                        'message' => $msg,
                        'success' => false
                    );
                }

                $this->getFcsTable()->saveAlbumRse($data, $lastRow, $idFCS);
                
                $repetir = sizeof($this->params()->fromPost('idComponente'));
                
                
                for ($a = 1; $a < $repetir; $a++) {
                    
                    $getlR       = $this->getServiciosTable()->getLastService();
                    $lastService = $getlR->SERVICIO_ID;
                    
                    $data2->SERVICIO_ID    = $lastService;
                    $data2->CATSERVICIO_ID = $losComponentes[$a];
                    $data2->CANTIDAD       = $lasCantidades[$a];
                    $data2->PRECIO         = $losPrecios[$a];
                    //si es fcs que actualice, si es lastRow, que inserte
                    
                    
                    $this->getServiciosTable()->saveServicio($data2, $lastRow, $idFCS);
                    
                }
                
                $arregloDias  = array(
                    "Domingo",
                    "Lunes",
                    "Martes",
                    "Miércoles",
                    "Jueves",
                    "Viernes",
                    "Sábado"
                );
                $arregloMeses = array(
                    "",
                    "Enero",
                    "Febrero",
                    "Marzo",
                    "Abril",
                    "Mayo",
                    "Junio",
                    "Julio",
                    "Agosto",
                    "Septiembre",
                    "Octubre",
                    "Noviembre",
                    "Diciembre"
                );
                $diaNum       = date("w");
                $mesNum       = date("n");
                $fecha        = $arregloDias[$diaNum] . " " . date('d') . " de " . $arregloMeses[$mesNum] . " de " . date('Y') . " a las " . date('H:i') . " hrs";
                
                
                
                //Envio de correo
                $mail          = new PHPMailer();
                $mail->CharSet = 'UTF­8';
                $mail->isSMTP();
                $mail->Host     = "10.3.18.64";
                $mail->From     = "desarrollo@vsys.com";
                $mail->FromName = "Desarrollo Vsys";
                $mail->Subject  = "Sistema de Gestion CUAD";
                $mail->AddAddress("elvitorvaldez@gmail.com", "Victor Valdez");
                $mail->AddAddress("vvaldez@vsys.com", "Victor Valdez Vsys");
                $mail->AddCC("daguilar@vsys.com");
                $mail->AddBCC("besquer@vsys.com");
                $mail->isHTML(true);
                
                $kindSerc = "";
                if ($data->SERVICIO_ID == 1) {
                    $kindSerc = "CISCO";
                } else if ($data->SERVICIO_ID == 2) {
                    $kindSerc = "BROADSOFT";
                }
                $nombreSitio = $this->getSitiosTable()->findSite($data->LOCATION_ID);
                
                $logoApp  = "http://$_SERVER[SERVER_ADDR]/sgc/public/images/logoSGCaplicativo.png";
                $logoVsys = "http://$_SERVER[SERVER_ADDR]/sgc/public/images/logoVsys.png";
               
                
                ob_start(); # apertura de bufer
                include '/var/www/html/sgc/module/Application/view/application/mails/altasFCS.phtml';
                $mensaje = ob_get_contents();
                ob_end_clean(); # cierre de bufer
                
                
                
                
                $mail->Body = $mensaje;
                if (!(isset($idFCS))) {
                $mail->send();
                }
                $url = "../../public/rse";  
                
                $jsonModel = array(  
                    'url' => $url,
                    'message' => 'RSE registrado exitosamente',
                    'success' => true
                );
                $response  = $this->getResponse();
                $response->getHeaders()->addHeaderLine('Content-Type', 'application/json');
                $response->setContent(json_encode($jsonModel));
                return $response;
            }
            catch (Exception $e) {
                echo 'Error al guardar: ', $e->getMessage(), "\n";
            }
            
        } else {
            
            return $this->redirect()->toRoute('home');
        }
        
    }
    
    public function buscarseAction()
    {
        $request = new Request();
        $request->setMethod(Request::METHOD_POST);
        $idRSE = $this->request->getPost("idRSE");
        //$idRSE = 61;
        $elRse=$this->getFcsTable()->getRseById($idRSE);
        $laEmpresa = $this->getEmpresasTable()->getEnterpriseById($elRse[0]['CUSTOMER_ID']);
        $elSitio = $this->getSitiosTable()->getSiteByLocationCode($elRse[0]['LOCATION_ID']);
        
 
        
        $jsonModel = array(
                 'message' => $this->getFcsTable()->getRseById($idRSE),
                 'nombreEmpresa' => $laEmpresa->COMPANY,
                 'nombreSitio'   => $elSitio->LOCATION_NAME,
                 'serviciosasoc' => $this->getServiciosTable()->getServicesByFcs($idRSE),
                 'success' => true
                );
                $response  = $this->getResponse();
                $response->getHeaders()->addHeaderLine('Content-Type', 'application/json');
                $response->setContent(json_encode($jsonModel));

                return $response;

               
              
    }    
    
    public function borrarAction()
    {
        $request = new Request();
        $request->setMethod(Request::METHOD_POST);
        
        $this->layout("layout/layout_dashboard.phtml");
        $user_session = new Container('user');
        $msg          = "";
        
        if (isset($user_session->username)) {
            $idSolicitud = $this->params('id');
            
            try {
                
                $this->getFcsTable()->deleteAlbum($idSolicitud);
                
                $url = "http://$_SERVER[SERVER_ADDR]./sgc/public/rse";
                
                
                
                $jsonModel = array(
                    'url' => $url,
                    'message' => 'FCS eliminado exitosamente',
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

    public function getserviciosAction() 
    {
    	$request = new Request();
        $request->setMethod(Request::METHOD_POST);
		
    	$search=  $this->request->getPost("query");     
    	$servicio = $this->params('id');  
		if (!empty($servicio)){
			if($servicio==1){
			$servicio="CISCO";	
			}      
			if($servicio==2){
			$servicio="BROADSOFT";	
			}
		  	
		 $servicios=$this->getCatServiciosTable()->getServiciosByTipo($servicio,$search);  			
		}else{
		 $servicios=$this->getCatServiciosTable()->fetchAll($search);
		}
         
		 $servicios=(object)$servicios;    
        return new JsonModel(array(
             'servicios' => $servicios
        ));
       
    }  
    
    public function getsitescomboAction()
    {
        $company = $this->request->getPost("empresa");
        
        
        return new JsonModel(array(
            'sitios' => $this->getSitiosTable()->getSitesComboByCompany($company),
            'empresa' => $company
        ));
        die();
    }
    
    
    public function getlocationnumberAction()
    {
        $sitio = $this->request->getPost("sitio");
        return new JsonModel(array(
            'locNumber' => $this->getSitiosTable()->getLocationNumberByLocationName($sitio)
        ));
        die();
    }
    
    
    
    public function getFcsTable()
    {
        if (!$this->FcsTable) {
            $sm             = $this->serviceLocator;
            $this->FcsTable = $sm->get('Application\Model\Fcs\Table');
        }
        return $this->FcsTable;
    }
    
    
    public function getEmpresasTable()
    {
        if (!$this->empresasTable) {
            $sm                  = $this->serviceLocator;
            $this->empresasTable = $sm->get('Application\Model\EmpresasTable');
        }
        return $this->empresasTable;
    }
    
    
    public function getSitiosTable()
    {
        if (!$this->sitiosTable) {
            $sm                = $this->serviceLocator;
            $this->sitiosTable = $sm->get('Application\Model\Sitios\Table');
        }
        return $this->sitiosTable;
    }
    
    
    public function getCatServiciosTable()
    {
        
        
        if (!$this->catServiciosTable) {
            
            //$sm = $this->getServiceLocator();
            $sm                      = $this->serviceLocator;
            //ahora el error es aqui
            $this->catServiciosTable = $sm->get('Application\Model\CatServiciosTable');
        }
        
        return $this->catServiciosTable;
        
        
        
    }
    
    
    
    
}

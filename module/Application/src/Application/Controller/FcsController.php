<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\Plugin\Layout;
use Application\Model\FcsTable;
use Zend\View\Model\JsonModel;
use Zend\Http\Request;

use Zend\Json\Json;

use PHPMailer;




class FcsController extends AbstractActionController
{
    protected $FcsTable;
    protected $empresasTable;
    protected $sitiosTable;
    protected $catServiciosTable;
    protected $serviciosTable;
	protected $catCapTable;     
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
    
    public function getCatServiciosTable()
    {
        
        
        if (!$this->catServiciosTable) {
            
            //$sm = $this->getServiceLocator();
            $sm = $this->serviceLocator;  
            //ahora el error es aqui
            $this->catServiciosTable = $sm->get('Application\Model\CatServiciosTable');
        }
        
        return $this->catServiciosTable;
        
    }
	
	public function getCatCapTable()
    {
        
        
        if (!$this->CatCapTable) {  
            
            $sm= $this->serviceLocator;
            //ahora el error es aqui
            $this->catCapTable = $sm->get('Application\Model\CatCapTable');  
        }
        
        return $this->catCapTable;
        
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
            $this->sitiosTable = $sm->get('Application\Model\SitiosTable');   
        }
        return $this->sitiosTable;
    }
    
    
    
    public function indexAction()
    {
        $this->layout("layout/layout_tables.phtml");
        $user_session = new Container('user');
        
        $idSitio = $this->params('id');   
        //$idSitio=$this->params()->fromRoute("id",null);     
		
         
        if (isset($user_session->username)) {
        	
			$plugin = $this->controlAccess();            
            $user_session=$plugin->getTiempo("$this->redirect()->toRoute('home')");
             
		 if (isset($idSitio)) {	 
               
			    $nombreSitio = $this->getSitiosTable()->getSiteByLocationCode($idSitio);   
			 
			  //$fcsAll = $this->getFcsTable()->fetchAllFcs();        
            $empresas = $this->getEmpresasTable()->fetchAll();  
			      
			foreach ($empresas as $empresas){
			  if(!empty($empresas->CUSTOMER_ID)){
			  	$fcsAll= $this->getFcsTable()->fetchAllFcsBySitio($idSitio);   
				//$fcsAll = $this->getFcsTable()->fetchAllFcs($empresas->CUSTOMER_ID);
			   if(!empty($fcsAll)){	
				foreach ($fcsAll as $fcsAll){
					if(!empty($fcsAll->CUSTOMER_ID) && ($empresas->CUSTOMER_ID)==($fcsAll->CUSTOMER_ID)){  
						$fcss[]=array('CUSTOMER_ID'=>$empresas->CUSTOMER_ID,
						              'COMPANY'=>$empresas->COMPANY,
						              'SOLICITUD_ID'=>$fcsAll->SOLICITUD_ID,
									  'SERVICIO_ID'=>$fcsAll->SERVICIO_ID,
									  'FOLIO'=>$fcsAll->FOLIO,
									  'LOCATION_ID'=>$fcsAll->LOCATION_ID,
									  'TIPOMOVIMIENTO'=>$fcsAll->TIPOMOVIMIENTO,
									  'FECHAEMISION'=>$fcsAll->FECHAEMISION,
									  'VALIDADO'=>$fcsAll->VALIDADO,	   										    
									 ); 
					} 
				 }
			   }//if vacio FCSS   
			   } 	    	  			           
			}    
			    
			    return new ViewModel(array(
                'fcss' => $fcss,  
                'nombreSitio' => $nombreSitio,
            ));        
          
		  }else{  
		  	
			 //$fcsAll = $this->getFcsTable()->fetchAllFcs();        
            $empresas = $this->getEmpresasTable()->fetchAll();  
			      
			foreach ($empresas as $empresas){
			  if(!empty($empresas->CUSTOMER_ID)){  
				$fcsAll = $this->getFcsTable()->fetchAllFcs($empresas->CUSTOMER_ID);
				foreach ($fcsAll as $fcsAll){
					if(!empty($fcsAll->CUSTOMER_ID) && ($empresas->CUSTOMER_ID)==($fcsAll->CUSTOMER_ID)){  
						$fcss[]=array('CUSTOMER_ID'=>$empresas->CUSTOMER_ID,
						              'COMPANY'=>$empresas->COMPANY,
						              'SOLICITUD_ID'=>$fcsAll->SOLICITUD_ID,
									  'SERVICIO_ID'=>$fcsAll->SERVICIO_ID,
									  'FOLIO'=>$fcsAll->FOLIO,
									  'LOCATION_ID'=>$fcsAll->LOCATION_ID,
									  'TIPOMOVIMIENTO'=>$fcsAll->TIPOMOVIMIENTO,
									  'FECHAEMISION'=>$fcsAll->FECHAEMISION,
									  'VALIDADO'=>$fcsAll->VALIDADO,	   										    
									 ); 
					} 
				 }
			   } 	    	  			           
			}    
			    
	      
            return new ViewModel(array(
                'fcss' => $fcss,
                              
            ));
		 	
		  }	
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
						  						  
						$empresa = $this->getEmpresasTable()->getCompanyByRfc($fcss->CUSTOMER_ID); 
						$sitio   = $this->getSitiosTable()->findSite($fcss->LOCATION_ID);   
						$catCap  = $this->getCatCapTable()->fetchById($fcss->CAP_ID);
						
						   						
						
						$fcssbyId=array('COMPANY'=>$empresa->COMPANY,
        								'LOCATION_NAME'=>$sitio->LOCATION_NAME,     
		                                'SERVICIO_ID'=>$fcss->SERVICIO_ID,
        								'CAP_ID'=>$fcss->CAP_ID,
        								'CAP_NOMBRE'=>$catCap->NOMBRE,
        								'CAP_CORREO'=>$catCap->CORREO,
        								'CAP_TELEFONO'=>$catCap->TELEFONO,
        								'CAP_EXTENSION'=>$catCap->EXTENSION,
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
				  
				//print_r($serviciosAsoc);      	   
				   
				if(!empty($serviciosAsoc)){   
				  
													   				     	     
				foreach ($serviciosAsoc as  $serviciosAsoc){
					        
					$catServicio=$this->getCatServiciosTable()->getCatServicio($serviciosAsoc["CATSERVICIO_ID"]);  
					 
				  
					if(!empty($catServicio)){   		   				
						$codigo=$catServicio->CODIGO;	     
						
						foreach ($catServicio as  $catServicio){      
							$codigo=$catServicio->CODIGO;
						}  	
					}   
					   
					$getServices[]=array(
					   'ID_COMPONENTE'=>$serviciosAsoc["ID_COMPONENTE"],
					   'CANTIDAD'=>$serviciosAsoc["CANTIDAD"],
					   'PRECIO'=>$serviciosAsoc["PRECIO"],
					   'SERVICIO_ID'=>$serviciosAsoc["SERVICIO_ID"],
					   'CATSERVICIO_ID'=>$serviciosAsoc["CATSERVICIO_ID"],   					   
					   'CODIGO'=>$codigo, 								  	   										    
					); 
					                          
				   } //end foreach 
				//} //else size  
				//$getServices=(object)$getServices;     
				}   
				      
            	     			         
            	
                return new ViewModel(array(
                    'fcss' => $fcssbyId,  
                    'servicios' => $this->getCatServiciosTable()->fetchAll($search),
                    'serviciosasoc' => $getServices        
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
    
    
    public function detalleAction()
    {
        
        $this->layout("layout/layout_dashboard.phtml");
        $user_session = new Container('user');
       
       // $plugin = $this->moneyFormat();
        //$moneyformat=$plugin->getMoneyFormat();         
        
        if (isset($user_session->username)) {
        	
			$plugin = $this->controlAccess();            
            $user_session=$plugin->getTiempo("$this->redirect()->toRoute('home')");
			
            $idFcs = $this->params('id');
            
            
            if (isset($idFcs)) {
            	
				
				 $fcss = $this->getFcsTable()->fetchFcsById($idFcs);
				  
				 
				foreach ($fcss as $fcss ){
				         
					if(!empty($fcss->CUSTOMER_ID)){
						  						  
						$empresa = $this->getEmpresasTable()->getCompanyByRfc($fcss->CUSTOMER_ID); 
						$sitio   = $this->getSitiosTable()->findSite($fcss->LOCATION_ID);   
						$catCap  = $this->getCatCapTable()->fetchById($fcss->CAP_ID);
						
						   						
						
						$fcssbyId=array('idFcs'=>$idFcs,
						                'COMPANY'=>$empresa->COMPANY,
        								'LOCATION_NAME'=>$sitio->LOCATION_NAME,     
		                                'SERVICIO_ID'=>$fcss->SERVICIO_ID,
        								'CAP_ID'=>$fcss->CAP_ID,
        								'CAP_NOMBRE'=>$catCap->NOMBRE,
        								'CAP_CORREO'=>$catCap->CORREO,
        								'CAP_TELEFONO'=>$catCap->TELEFONO,
        								'CAP_EXTENSION'=>$catCap->EXTENSION,
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
				  
				//print_r($serviciosAsoc);      	   
				   
				if(!empty($serviciosAsoc)){   
				  
													   				     	     
				foreach ($serviciosAsoc as  $serviciosAsoc){
					        
					$catServicio=$this->getCatServiciosTable()->getCatServicio($serviciosAsoc["CATSERVICIO_ID"]);  
					 
				  
					if(!empty($catServicio)){   		   				
						$codigo=$catServicio->CODIGO;	     
						
						foreach ($catServicio as  $catServicio){      
							$codigo=$catServicio->CODIGO;
						}  	
					}   
					   
					$getServices[]=array(
					   'ID_COMPONENTE'=>$serviciosAsoc["ID_COMPONENTE"],
					   'CANTIDAD'=>$serviciosAsoc["CANTIDAD"],
					   'PRECIO'=>$serviciosAsoc["PRECIO"],
					   'SERVICIO_ID'=>$serviciosAsoc["SERVICIO_ID"],
					   'CATSERVICIO_ID'=>$serviciosAsoc["CATSERVICIO_ID"],   					   
					   'CODIGO'=>$codigo, 								  	   										    
					); 
					                          
				   } //end foreach 
				//} //else size  
				//$getServices=(object)$getServices;     
				}   
             
            return new ViewModel(array(
                    'serviciosAsociados' =>$getServices,  
                    'fcs' => $fcssbyId,
                    'plugin'=>$this->moneyFormat(),        
                    
                ));
            }
            
        }
        else {
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
            
			if(!empty($idFCS)){				
				$idFCS=$idFCS;				
			}
			
            try {
                
                $data  = new \Application\Model\Fcs;
                $data2 = new \Application\Model\Servicios;
				$dataCap = new \Application\Model\CatCap;  
                
                $lasPartes = $this->params()->fromPost('numParte');   
                        
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
                $FECHAEMISION      = $this->params()->fromPost('FECHAEMISION');
                
                if (trim($FECHAEMISION) != "") {
                    
                    $day                = substr($FECHAEMISION, 0, 2);
                    $month              = substr($FECHAEMISION, 3, 2);
                    $year               = substr($FECHAEMISION, 6, 4);
                    $fechaEm            = date("Y-m-d H:i:s", mktime(0,0,0, $month, $day, $year));
                } else {
                    $fechaEm = date('Y-m-d H:i:s');
                }
                
              
                $nombreEmpresa = $this->params()->fromPost('COMPANY');
                  if (isset($nombreEmpresa))
                  {
                $customerId = $this->getEmpresasTable()->getEnterpriseIdByName($nombreEmpresa);
                $data->CUSTOMER_ID = $customerId->CUSTOMER_ID;
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
                $data->STATUS                 = $status;
                $data->LOCATION               = $this->params()->fromPost('LOCATION');
                $data->SERVICIO_ID            = $this->params()->fromPost('SERVICIO_ID');
                $data->FOLIO                  = $this->params()->fromPost('FOLIO');
                $data->TIPOMOVIMIENTO         = "ALTA";
                $data->QO                     = $this->params()->fromPost('QO');
                $data->COTIZACION             = $this->params()->fromPost('COTIZACION');
                $data->ORDENAPROVISIONAMIENTO = $this->params()->fromPost('ORDENAPROVISIONAMIENTO');
                $data->CAP_ID                 = $this->params()->fromPost('CAP_ID');
                $data->RESPONSABLEINSTALACION = $this->params()->fromPost('RESPONSABLEINSTALACION');
                $data->TELRESPSITIO           = $this->params()->fromPost('TELRESPSITIO');
                $data->CASOINSTALACION        = $this->params()->fromPost('CASOINSTALACION');
                 
				/*No existe el cap en el catalogo se guarda*/    
				if(empty($data->CAP_ID)){
				 	
				if(!empty($this->params()->fromPost('NOMBRECAP'))){
					  $getlastRowCatCap = $this->getCatCapTable()->getLastCatCap();                
                      $lastIdCatCap = $getlastRowCatCap->CAP_ID;
					  	        
					  $dataCap->NOMBRE     = $this->params()->fromPost('NOMBRECAP');
	                  $dataCap->CORREO     = $this->params()->fromPost('EMAILCAP');
	                  $dataCap->TELEFONO   = $this->params()->fromPost('TELCAP'); 
					  $dataCap->EXTENSION  = $this->params()->fromPost('EXTCAP');  
					  
					  $caps=$this->getCatCapTable()->saveCatCap($dataCap,$lastIdCatCap);
					  $data->CAP_ID = $caps;      
				  }
				         	
				}   
				   
				                    
                if (trim($data->LOCATION) == "" || trim($data->COMPANY) == "") {
                    $msg = "Campo obligatorio";
                }
                
                
                if ($msg) {
                    
                    $jsonModel = array(
                        'message' => $msg,
                        'success' => false
                    );
                }
                
				if (!isset($idFCS)){   
				
			   $existe=$this->getFcsTable()->getFcsByFolio($data->FOLIO);  
			   
			    if (isset($existe))       
	               {
	               	$jsonModel = array(
		                    'message' => 'Este Folio ya fue usado ',
		                    'success' => false
		                );
						
						 $response  = $this->getResponse();
	 	                 $response->getHeaders()->addHeaderLine('Content-Type', 'application/json');
		                 $response->setContent(json_encode($jsonModel));
		                 return $response;   
	            	   die();
			       }
				}       	
                $this->getFcsTable()->saveAlbum($data, $lastRow, $idFCS);    
                
				
				   
				 $losServiciosId = $this->params()->fromPost('idParte');   	   
                 $repetir = sizeof($this->params()->fromPost('numParte'));
               
				
				if (isset($idFCS)) {  //se esta editando el fcs
                	
                	if(!empty($data->CAP_ID)){ //comprueba si hay que editar campos del catalogo cap   
                	  
                	  $getCatCap  = $this->getCatCapTable()->fetchById($data->CAP_ID);
					  
					  $nameCap = $this->params()->fromPost('NOMBRECAP');
                  	  $mailCap = $this->params()->fromPost('EMAILCAP');
                  	  $telCap = $this->params()->fromPost('TELCAP'); 
				  	  $extCap = $this->params()->fromPost('EXTCAP');
					  
					 if($nameCap != $getCatCap->NOMBRE){  
						
						$dataCap->NOMBRE = $nameCap;						  
						$this->getCatCapTable()->editCatCap($dataCap, "NOMBRE", $data->CAP_ID);      										
					 }
					 if($mailCap != $getCatCap->CORREO){  
						
						$dataCap->CORREO = $mailCap;						  
						$this->getCatCapTable()->editCatCap($dataCap, "CORREO", $data->CAP_ID);      										
					 }
					 if($telCap != $getCatCap->TELEFONO){  
						
						$dataCap->TELEFONO = $telCap;						  
						$this->getCatCapTable()->editCatCap($dataCap, "TELEFONO", $data->CAP_ID);      										
					 }
					 if($extCap != $getCatCap->EXTENSION){  
						
						$dataCap->EXTENSION = $extCap;						  
						$this->getCatCapTable()->editCatCap($dataCap, "EXTENSION", $data->CAP_ID);      										
					 }
					 
					}
                	 
                	
                	
                	
                	
                   $serviciosAsoc =  sizeof($this->getServiciosTable()->getServicesByFcs($idFCS));
				        
					      
					  if($serviciosAsoc != $repetir){
					  	   
					    for ($a = 0; $a < $repetir; $a++) {
					  		$checkEdit=$this->getServiciosTable()->checkServicesEdit($losServiciosId[$a],$idFCS);
							  
							//if(!empty($checkEdit)){
								
								if($losServiciosId[$a] == $checkEdit->CATSERVICIO_ID){  
									$idEditServices=$checkEdit->SERVICIO_ID;
									
								    if($losComponentes[$a] != $checkEdit->ID_COMPONENTE){  
										 $idComponenteEditServices=$losComponentes[$a];
										 $data2->ID_COMPONENTE    = $idComponenteEditServices;
										 $this->getServiciosTable()->editServices($data2, "ID_COMPONENTE", $idEditServices );    
										
									}
									if($lasCantidades[$a] != $checkEdit->CANTIDAD){  
										$cantidadEditServices=$lasCantidades[$a];
										$data2->CANTIDAD    = $cantidadEditServices;
										$this->getServiciosTable()->editServices($data2, "CANTIDAD", $idEditServices );  
									}
									if($losPrecios[$a] != $checkEdit->PRECIO){  
										$precioEditServices=$losPrecios[$a];
										$data2->PRECIO    = $precioEditServices;
										$this->getServiciosTable()->editServices($data2, "PRECIO", $idEditServices );  
									}
									
									         
				                   }else{
								    //echo "ddd".$lastRow; die();  
									$getlR       = $this->getServiciosTable()->getLastService();
				                    $lastService = $getlR->SERVICIO_ID;
				                    
				                    $data2->SERVICIO_ID    = $lastService;
				                    $data2->CATSERVICIO_ID = $losServiciosId[$a];
									$data2->ID_COMPONENTE  = $losComponentes[$a];
				                    $data2->CANTIDAD       = $lasCantidades[$a];
				                    $data2->PRECIO         = $losPrecios[$a];  
				                    //si es fcs que actualice, si es lastRow, que inserte
				                    
				                    $this->getServiciosTable()->saveServicio($data2, $lastRow, $idFCS);
				                  }	
							//}//end if
						}//for
						
					  }else{
					  	for ($a = 0; $a < $repetir; $a++) {
					  		$checkEdit=$this->getServiciosTable()->checkServicesEdit($losServiciosId[$a],$idFCS);
							if(!empty($checkEdit)){
								
								if($losServiciosId[$a] == $checkEdit->CATSERVICIO_ID){  
									$idEditServices=$checkEdit->SERVICIO_ID;
									
								    if($losComponentes[$a] != $checkEdit->ID_COMPONENTE){  
										 $idComponenteEditServices=$losComponentes[$a];
										 $data2->ID_COMPONENTE    = $idComponenteEditServices;
										 $this->getServiciosTable()->editServices($data2, "ID_COMPONENTE", $idEditServices );    
										
									}
									if($lasCantidades[$a] != $checkEdit->CANTIDAD){  
										$cantidadEditServices=$lasCantidades[$a];
										$data2->CANTIDAD    = $cantidadEditServices;
										$this->getServiciosTable()->editServices($data2, "CANTIDAD", $idEditServices );  
									}
									if($losPrecios[$a] != $checkEdit->PRECIO){  
										$precioEditServices=$losPrecios[$a];
										$data2->PRECIO    = $precioEditServices;
										$this->getServiciosTable()->editServices($data2, "PRECIO", $idEditServices );  
									}
									
									
								}
								
							}
						}	
					  }//end if 
					  
                    
                } else {    //se agrega el arreglo que viene del formulario 
                 
				
				 for ($a = 0; $a < $repetir; $a++) {
                      
                    $getlR       = $this->getServiciosTable()->getLastService();
                    $lastService = $getlR->SERVICIO_ID;
                    
                    $data2->SERVICIO_ID    = $lastService;
                    $data2->CATSERVICIO_ID = $losServiciosId[$a];
					$data2->ID_COMPONENTE  = $losComponentes[$a];
                    $data2->CANTIDAD       = $lasCantidades[$a];
                    $data2->PRECIO         = $losPrecios[$a];  
                    //si es fcs que actualice, si es lastRow, que inserte
                    
                    $this->getServiciosTable()->saveServicio($data2, $lastRow, $idFCS);                  
                   
                  }
                }
				
                
				/*Guarda el servicio en relación con la empresa*/
				
				 $comprueba=$this->getFcsTable()->checkRelationship($data->SERVICIO_ID,$data->CUSTOMER_ID); 
				   
				 if (!isset($comprueba)){
				 	
				 	$getID       = $this->getFcsTable()->getLastEnterpriseService();  
                    $lastIdService = $getID->EMP_ID;          
				 	  
					$this->getFcsTable()->saveEmpresaServicio($data->SERVICIO_ID,$data->CUSTOMER_ID,$lastIdService);  					
				 	
				 }    
				
				 die();
				
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
                $mail->AddAddress("david_aguilar_19@hotmail.com", "David Aguilar");
                $mail->AddAddress("daguilar@vsys.com", "David Aguilar");
                $mail->AddCC("daguilar@vsys.com");
                //$mail->AddBCC("besquer@vsys.com");  
                $mail->isHTML(true);
                
                $kindSerc = "";
                if ($data->SERVICIO_ID == 1) {
                    $kindSerc = "CISCO";  
                } else if ($data->SERVICIO_ID == 2) {
                    $kindSerc = "BROADSOFT";  
                }
                $nombreSitio = $this->getSitiosTable()->findSite($data->LOCATION);
                
                $logoApp  = "http://$_SERVER[HTTP_HOST]/sgc/public/images/logoSGCaplicativo.png";
                $logoVsys = "http://$_SERVER[HTTP_HOST]/sgc/public/images/logoVsys.png";  
                
                
                ob_start(); # apertura de bufer  
                
                include $_SERVER['DOCUMENT_ROOT'].'/sgc/module/Application/view/application/mails/altasFCS.phtml';
                $mensaje = ob_get_contents();
                ob_end_clean(); # cierre de bufer
                
                $mail->Body = $mensaje;
                if (!(isset($idFCS))) {
                $mail->send();
                }
				
				$idUpdate = $this->params('id');
				if(!empty($idUpdate)){
				$url = "../../fcs";  	
				}else{
                $url = "../fcs";
				}      
                
                $jsonModel = array(    
                    'url' => $url,   
                    'message' => 'FCS registrado exitosamente',
                    'success' => true
                );
                       // die();     
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
    
    
	public function saveCatServicesAction()
    {
    	$request = new Request();
        $request->setMethod(Request::METHOD_POST);  
		
		$CODIGO       =   strtoupper($this->request->getPost("CODIGO"));     
		$DESCRIPCION  =  $this->request->getPost("DESCRIPCION");  
		$TIPOSERVICIO =  $this->request->getPost("TIPOSERVICIO");  
		 
		  
		if (!empty($TIPOSERVICIO)){
			if($TIPOSERVICIO==1){
			$TIPOSERVICIO="CISCO";	
			}      
			if($TIPOSERVICIO==2){
			$TIPOSERVICIO="BROADSOFT";	
			}
		}else{
			$jsonModel = array(
		      'message' => 'No se agregó un tipo de servicio.',
		      'success' => false
		    );		 
		}
		
		if(empty($CODIGO)){
			$jsonModel = array(
		      'message' => 'No se agregó un código.',
		      'success' => false
		    );	
			
		}
		if(empty($DESCRIPCION)){
			$jsonModel = array(
		      'message' => 'No se agregó una descripción.',
		      'success' => false
		    );	
			
		}
          
		  $data = new \Application\Model\CatServicios;   
		  
		   $data->CODIGO         = $CODIGO;
		   $data->DESCRIPCION    = $DESCRIPCION;
		   $data->TIPOSERVICIO   = $TIPOSERVICIO; 

		//echo $CODIGO.$DESCRIPCION.$TIPOSERVICIO;
		   $getlastRowCatServicios = $this->getCatServiciosTable()->getLastCatServicios();                
           $lastIdCatServicios = $getlastRowCatServicios->CATSERVICIO_ID;
		 
		   $idCatService= $this->getCatServiciosTable()->saveCatServicios($data, $lastIdCatServicios);    
           
           
		   $jsonModel = array(  
		                      
		        'message' => 'Se Guardó el servicio con éxito',
		        'id'      => $idCatService,     
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
                
                $url = "http://$_SERVER[SERVER_ADDR]./sgc/public/fcss";
                
                
                
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
    
	
	public function validarAction()
    {
        $request = new Request();
        $request->setMethod(Request::METHOD_POST);
        
        $this->layout("layout/layout_dashboard.phtml");
        $user_session = new Container('user');
        $msg          = "";
        
        if (isset($user_session->username)) {
            $idSolicitud = $this->params('id');
            
            try {
                
                $this->getFcsTable()->validateFCS($idSolicitud);    
                
                $url = "fcs";       
                
                
                
                $jsonModel = array(
                    'url' => $url,
                    'message' => 'FCS fue validado exitosamente',
                    'success' => true
                );
                $response  = $this->getResponse();
                $response->getHeaders()->addHeaderLine('Content-Type', 'application/json');
                $response->setContent(json_encode($jsonModel));
                return $response;
            }
            catch (Exception $e) {
                echo 'Error al validar ', $e->getMessage(), "\n";
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
	
	 public function getcapAction() 
    {
    	$request = new Request();
        $request->setMethod(Request::METHOD_POST);
		
		$search=  $this->request->getPost("query");     
  		
    	$caps=$this->getCatCapTable()->fetchAll($search);  			
		 
	      return new JsonModel(array(
	             'caps' => $caps
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
    
    
    public function getServiceCatalog()
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
    
    
        public function searchfolioAction()
    {
         $retorno="";
		 
         $folio = $this->request->getPost("folio");
      
         $existe=$this->getFcsTable()->getFcsByFolio($folio);
         
         if (isset($existe))
         {
           die($existe->CUSTOMER_ID);
            
             $retorno = $existe->CUSTOMER_ID;  
         }
         
         
             die($retorno);
            
            
    }
    
    
    
    
}

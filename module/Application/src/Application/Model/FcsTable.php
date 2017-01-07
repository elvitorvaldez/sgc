<?php

 namespace Application\Model;

 use Zend\Db\TableGateway\TableGateway;
 use Zend\Db\Sql\Sql;
 use Application\Model\FcsTable;
 use Zend\Db\ResultSet\ResultSet;

 class FcsTable
 {
     //protected $tableGateway;
     protected $tableGateway="SOLICITUD";

     public function __construct(TableGateway $tableGateway)
     {
         $this->tableGateway = $tableGateway;
     }


     
     
     public function fetchAllFcs($customerID) {
         //ESTATUS VALE 0 PARA ELIMINADOS, 1 PARA ACTIVOS
         $adapter = $this->tableGateway->getAdapter();
         $sql     = new Sql($adapter);
         $select = $sql->select();
         $select->from('SOLICITUD');       
         //$select->join('COMPANYM1', "SOLICITUD.CUSTOMER_ID = COMPANYM1.CUSTOMER_ID", array('COMPANY'), 'inner'); 
         $select->where(array('ESTATUS' => (int)1, 'FCSFLG' => (int)1,'SOLICITUD.CUSTOMER_ID' => $customerID));           
         //echo "<br>el query ".$select->getSqlString();die();      
         $resultSet = $this->tableGateway->selectWith($select);   
         return $resultSet;
         }
         
		 
	public function fetchAllFcsBySitio($id) {
            
         $adapter = $this->tableGateway->getAdapter();
         $sql     = new Sql($adapter);
         $select = $sql->select();
         $select->from('SOLICITUD');
         // $select->join('COMPANYM1', "SOLICITUD.CUSTOMER_ID = COMPANYM1.CUSTOMER_ID", array('COMPANY'), 'inner');    
         $select->where(array('LOCATION_ID'=>$id,'ESTATUS' => (int)1, 'FCSFLG' => (int)1));    
         //echo "<br>el query ".$select->getSqlString();die();  
         //echo "<br>el query ".$select->getSqlString();  
         //$resultSet = $this->tableGateway->selectWith($select); 
         //return $resultSet;
         $resultSet = $this->tableGateway->selectWith($select);
         $row = $resultSet->current();
	         if ($row) {
	              return $row;       
	         }
	       
         }	 
         
         public function fetchAllRse($customerID) {
         //ESTATUS VALE 0 PARA ELIMINADOS, 1 PARA ACTIVOS
         $adapter = $this->tableGateway->getAdapter();
         $sql     = new Sql($adapter);
         $select = $sql->select();
         $select->from('SOLICITUD');
        // $select->join('COMPANYM1', "SOLICITUD.CUSTOMER_ID = COMPANYM1.CUSTOMER_ID", array('COMPANY'), 'inner');   
          $select->where(array('ESTATUS' => (int)1, 'RSEFLG' => (int)1,'SOLICITUD.CUSTOMER_ID' => $customerID));  
         //echo "<br>el query ".$select->getSqlString();die();
         $resultSet = $this->tableGateway->selectWith($select); 
         return $resultSet;
         }
         
         
         
       public function getLastFcs() {   
         $adapter = $this->tableGateway->getAdapter();
         $sql     = new Sql($adapter);
         $select = $sql->select();
         $select->from('SOLICITUD');   
         $select->columns(array('SOLICITUD_ID'));
         $select->order('SOLICITUD_ID DESC');
         
        // echo "<br>el query ".$select->getSqlString();
         $resultSet = $this->tableGateway->selectWith($select); 
         $row = $resultSet->current();
         if (!$row) {
             throw new \Exception("Could not find row");
         }
         return $row;
       }
         
         public function fetchFcsById($id) {
            
         $adapter = $this->tableGateway->getAdapter();
         $sql     = new Sql($adapter);
         $select = $sql->select();
         $select->from('SOLICITUD');
        // $select->join('COMPANYM1', "SOLICITUD.CUSTOMER_ID = COMPANYM1.CUSTOMER_ID", array('COMPANY'), 'inner');
         //$select->join('LOCM1', "LOCM1.LOCATION = SOLICITUD.LOCATION_ID", array('LOCATION_NAME'), 'inner');  
         $select->where(array('SOLICITUD.SOLICITUD_ID' => (int)$id));
         //echo "<br>el query ".$select->getSqlString();       
         $resultSet = $this->tableGateway->selectWith($select);   
         return $resultSet; 
        /* $row = $resultSet->current();
         if (!$row) {
             throw new \Exception("Could not find row $id");
         }
         
         return $row;
         */  
         }
     
     
         
     public function getRseById($id)    
     {
         $adapter = $this->tableGateway->getAdapter();
         $sql     = new Sql($adapter);         
         $select = $sql->select();
         
         $select->from('SOLICITUD');  

         $select->where(array('SOLICITUD_ID' => $id));
         // print_r($select->getSqlString());
         $statement = $sql->prepareStatementForSqlObject($select); 
         $results = $statement->execute();

         //convertir el resultado a objeto ResultSet
         $resultSet = new ResultSet();
         //covertir el objeto ResultSet a arreglo
         $resultSet->initialize($results);
         return $resultSet->toArray();
         
     }
     
   public function getFcsById($id)
     {
 
         $rowset = $this->tableGateway->select(array('SOLICITUD_ID' => $id));
         $row = $rowset->current();
 
         if ($row) {
            return $row;
         }
        
     }

      public function saveAlbumRse(Fcs $fcs, $lastRow, $idFCS)
      {
         $RSEflag=1;
         $servicioID=$fcs->SERVICIO_ID;
         $status=1;
		 
         
         $data = array(
            'SOLICITUD_ID'                => $fcs->SOLICITUD_ID,
            'CUSTOMER_ID'                 => $fcs->CUSTOMER_ID,
            'LOCATION_ID'                 => $fcs->LOCATION_ID,
            'FECHAEMISION'                 => $fcs->FECHAEMISION,
            'RSENOMSOLICITO'=> $fcs->RSENOMSOLICITO,
            'RSENOMAUTORIZO'=> $fcs->RSENOMAUTORIZO,
             'TIPOMOVIMIENTO'=> $fcs->TIPOMOVIMIENTO,
            'ESTATUS'                      => $status,
            'SERVICIO_ID'                 => (int)$servicioID,            
            'RSEFLG'                     => $RSEflag, 
         );
         
         $data['SOLICITUD_ID']=(int)$lastRow+1;     
          
             $this->tableGateway->insert($data);
         
      }
     
     public function saveAlbum(Fcs $fcs, $lastRow, $idFCS)
     {

         if ($fcs->STATUS=="Fcs")
         {
             $FCSflag=1;
             $RSEflag=0;    
			       
         }
         else
         {
            $FCSflag=0;
            $RSEflag=1;  
         }
         
         $servicioID=$fcs->SERVICIO_ID;
         $status=1;
		 $validate=0;     
         
          $data = array(
            'SOLICITUD_ID'                => $fcs->SOLICITUD_ID,
            'CUSTOMER_ID'                 => $fcs->CUSTOMER_ID,
            'LOCATION_ID'                 => $fcs->LOCATION,
            'ESTATUS'                     => $status,
            'SERVICIO_ID'                 => (int)$servicioID,
            'FECHAEMISION'                => $fcs->FECHAEMISION,
            'FOLIO'                       => $fcs->FOLIO,
            'TIPOMOVIMIENTO'              => $fcs->TIPOMOVIMIENTO,
            'QO'                          => $fcs->QO,           
            'COTIZACION'                  => $fcs->COTIZACION,          
            'TIPOMOVIMIENTO'              => $fcs->TIPOMOVIMIENTO,              
            'ORDENAPROVISIONAMIENTO'      => $fcs->ORDENAPROVISIONAMIENTO,           
            'CAP_ID'                      => $fcs->CAP_ID,
            'RESPONSABLEINSTALACION'	  => $fcs->RESPONSABLEINSTALACION,
            'TELRESPSITIO'                => $fcs->TELRESPSITIO,
            'CASOINSTALACION'             => $fcs->CASOINSTALACION,
            'FCSFLG'                     => $FCSflag,
            'RSEFLG'                     => $RSEflag, 
            'VALIDADO'                   => $validate,
         );
            
          
  
         $id = (int) $fcs->SOLICITUD_ID;
      
         if (trim($idFCS)=="") {   
      
            $data['SOLICITUD_ID']=(int)$lastRow+1;     
          
             $this->tableGateway->insert($data);
         } else {
             
            // if ($this->getFcsById($id)) {
  
             
             $data = array(            
            'ESTATUS'                      => $status,
            'SERVICIO_ID'                 => (int)$servicioID,
            'FOLIO'                       => $fcs->FOLIO,
            'TIPOMOVIMIENTO'              => $fcs->TIPOMOVIMIENTO,
            'FECHAEMISION'                => $fcs->FECHAEMISION,
            'QO'                          => $fcs->QO,           
            'COTIZACION'                  => $fcs->COTIZACION,          
            'TIPOMOVIMIENTO'              => $fcs->TIPOMOVIMIENTO,              
            'ORDENAPROVISIONAMIENTO'      => $fcs->ORDENAPROVISIONAMIENTO,           
            'CAP_ID'                      => $fcs->CAP_ID,
            'RESPONSABLEINSTALACION'	  => $fcs->RESPONSABLEINSTALACION,
            'TELRESPSITIO'                => $fcs->TELRESPSITIO,
            'CASOINSTALACION'             => $fcs->CASOINSTALACION,
            'FCSFLG'                     => $FCSflag,
            'RSEFLG'                     => $RSEflag, 
         );
             
                 $this->tableGateway->update($data, array('SOLICITUD_ID' => $id));                 
         }
     }

     public function getFcsByFolio($id)
     {
         //$id  = (int) $id;
      
         $rowset = $this->tableGateway->select(array('FOLIO' => $id,'FCSFLG'=>1));   
         $row = $rowset->current();
         
         if ($row) {
            return $row;
         }
        
     }

     public function deleteAlbum($id)
     {
         $status=0;         
         $this->tableGateway->update(array ('ESTATUS' => (int)$status ), array ('SOLICITUD_ID' => (int)$id));
     }
	 
	 
	 public function checkRelationship($idService,$idEnterprise)
     {
     	$adapter = $this->tableGateway->getAdapter();
         $sql     = new Sql($adapter);
         $select = $sql->select();
		 $select->columns(array('CUSTOMER_ID','TIPOSERVICIO'));  		 
         $select->from('EMPRESA_SERVICIOS');        
         $select->where(array('EMPRESA_SERVICIOS.CUSTOMER_ID' => $idEnterprise,'EMPRESA_SERVICIOS.TIPOSERVICIO' => $idService ));
		 //echo "<br>el query ".$select->getSqlString();                 
         $resultSet = $this->tableGateway->selectWith($select);
		 $row = $resultSet->current(); 
		 if ($row) {
            return $row;
         }     
         //return $resultSet; 
        
     }
     
	  public function saveEmpresaServicio($idService,$idEnterprise,$idEmp)
      {
      	 
		   $idEmp=(int)$idEmp+1;
		       
		  
          $adapter = $this->tableGateway->getAdapter();
          $sql     = new Sql($adapter);  
          $insert = $sql->insert('EMPRESA_SERVICIOS');		     
		  $data = array(
		    'EMP_ID'       =>$idEmp, 
		    'CUSTOMER_ID'  =>$idEnterprise,
		    'TIPOSERVICIO' =>$idService,    
		    );  
		  $insert->values($data);   
		  $statement = $sql->prepareStatementForSqlObject($insert);     
          $results = $statement->execute();
		 
         
      }

	   public function getRelationship($idEnterprise)
	     { 
	     	 $adapter = $this->tableGateway->getAdapter();
	         $sql     = new Sql($adapter);	 
	         $select = $sql->select();
			 $select->columns(array('CUSTOMER_ID','TIPOSERVICIO'));		 
	         $select->from('EMPRESA_SERVICIOS');        
	         $select->where(array('EMPRESA_SERVICIOS.CUSTOMER_ID' => $idEnterprise));
			 //echo "<br>el query ".$select->getSqlString();                          
	         $resultSet = $this->tableGateway->selectWith($select);
			    
			 return $resultSet;  
			 /*$row = $resultSet->current();
              if (!$row) {             	
                return null;      
              }else{                     
                return $row;			 
			  }*/
			 	    
            /* $row = $select->execute();  
	         if ($row) {   
	             return $row;  
	         } */   
	         
	           
	     }
		 
	   public function getLastEnterpriseService() {     
         $adapter = $this->tableGateway->getAdapter();
         $sql     = new Sql($adapter);
         $select = $sql->select();
         $select->from('EMPRESA_SERVICIOS');        
         $select->columns(array('EMP_ID'));
         $select->order('EMP_ID DESC');
           
        // echo "<br>el query ".$select->getSqlString();
         $resultSet = $this->tableGateway->selectWith($select); 
         $row = $resultSet->current();
         if (!$row) {
             throw new \Exception("Could not find row");
         }
         return $row;
       } 
		  
	    public function validateFCS($id)  
	     {
	         $validado=1;    
	         $this->tableGateway->update(array ('VALIDADO' => (int)$validado ), array ('SOLICITUD_ID' => (int)$id));
	     }	  
	 
	 
 }
<?php   
 namespace Application\Model;
 use Zend\Db\TableGateway\TableGateway;
 use Zend\Db\Sql\Sql;
 use Zend\Db\ResultSet\ResultSet;

 class DocumentosTable  
 {
 	
     //protected $tableGateway;
     
     protected $tableGateway="DOCUMENTOS";    
 
     public function __construct(TableGateway $tableGateway)
     {
         $this->tableGateway = $tableGateway; 
     }

      public function saveDocumentpm(Documentos $documentpm, $lastRow)
      {
         
         $status=1;
		 $pmId=(int)$lastRow+1;		   
		     
         $data = array(
            'DOCUMENTO_ID'      => $pmId,
            'NOMBRE'            => $documentpm->NOMBRE,
            'FECHA'             => $documentpm->FECHA,                     
            'FECHAULTMOD'       => $documentpm->FECHA,
            'AREA'              => 'PM',
            'CUSTOMER_ID'       => $documentpm->CUSTOMER_ID,    
            'EXTENSION'         => $documentpm->EXTENSION,
            'TIPO'              => $documentpm->TIPO, 
            'RUTA'              => $documentpm->RUTA,
            'CARGADO_POR'       => $documentpm->CARGADO_POR,   
           
         );       
                 
             $this->tableGateway->insert($data);           
      }  
	  
	  
	   public function getLastIdDocument()
      {
     	    
         $adapter = $this->tableGateway->getAdapter();
         $sql     = new Sql($adapter);
         $select = $sql->select();
         $select->from('DOCUMENTOS');   
         $select->columns(array('DOCUMENTO_ID'));
         $select->order('DOCUMENTO_ID DESC');     
         
         //echo "<br>el query ".$select->getSqlString();           
         $resultSet = $this->tableGateway->selectWith($select); 
         $row = $resultSet->current();
         if (!$row) {
             throw new \Exception("Could not find row");
         }
         return $row;
         
      }   

       public function getDocumentsPmById($idEmpresa)
      {
     	  
         $adapter = $this->tableGateway->getAdapter();
         $sql     = new Sql($adapter);
         $select = $sql->select();
         $select->from('DOCUMENTOS');
         
         $select->where(array('CUSTOMER_ID'=>$idEmpresa));       
         //echo "<br>el query ".$select->getSqlString();die();  
         //echo "<br>el query ".$select->getSqlString();  
         $resultSet = $this->tableGateway->selectWith($select); 
         return $resultSet;      
                  
      }   
	  
 }
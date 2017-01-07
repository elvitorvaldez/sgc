<?php

 namespace Application\Model;

 use Zend\Db\TableGateway\TableGateway;
  use Zend\Db\Sql\Sql;
 use Application\Model\ServiciosTable;
 use Zend\Db\ResultSet\AbstractResultSet;
 use Zend\Db\ResultSet\ResultSet;

 class ServiciosTable
 {
     protected $tableGateway="SERVICIOS";
     
     public function __construct(TableGateway $tableGateway)
     {
         $this->tableGateway = $tableGateway;
         }

     public function fetchAll()
     {
         $resultSet = $this->tableGateway->select();        
         return $resultSet;
     }

     
     public function getServicesByFcs($id)
     {
         $id  = (int) $id;     
         $adapter = $this->tableGateway->getAdapter();
         $sql     = new Sql($adapter);         
         $select = $sql->select();         
         $select->from('SERVICIOS');   
         //$select->columns(array('SERVICIO_ID','ID_COMPONENTE','CANTIDAD','PRECIO','CATSERVICIO_ID'));    
         $select->order('SERVICIO_ID');  
         $select->where(array('SOLICITUD_ID' => $id));
         //echo "<br>el query ".$select->getSqlString();           
		 //$resultSet = $this->tableGateway->selectWith($select);
		 $statement = $sql->prepareStatementForSqlObject($select);   
		 $results = $statement->execute();

         //convertir el resultado a objeto ResultSet
         $resultSet = new ResultSet();
         //covertir el objeto ResultSet a arreglo
         $resultSet->initialize($results);
         return $resultSet->toArray();
         return $row;                
     }
     
     public function getCatServicio($id)
     {
         $id  = (int) $id; 
		 $adapter = $this->tableGateway->getAdapter();
         $sql     = new Sql($adapter);
         $select = $sql->select();
         $select->from('SERVICIOS');  
		 $select->where(array('SERVICIO_ID' => $id));        
         //$rowset = $this->tableGateway->select(array('SERVICIO_ID' => $id));
		  //echo "<br>el query ".$select->getSqlString();         
		$resultSet = $this->tableGateway->selectWith($select);         
         return $resultSet;   
        /*$row = $rowset->current();
         if (!$row) {
             throw new \Exception("Could not find row $id");
         }
         return $row;*/   
     }

     public function getLastService() {   
         $adapter = $this->tableGateway->getAdapter();
         $sql     = new Sql($adapter);
         $select = $sql->select();
         $select->from('SERVICIOS');   
         $select->columns(array('SERVICIO_ID'));
         $select->order('SERVICIO_ID DESC');
         
        // echo "<br>el query ".$select->getSqlString();
         $resultSet = $this->tableGateway->selectWith($select); 
         $row = $resultSet->current();
         if (!$row) {
             throw new \Exception("Could not find row");
         }
         return $row;
       }  
     
    public function saveServicio(Servicios $servicio, $lastRow, $idFcs){
        
		    
		if(!empty($idFcs)){   
		  $solicitud=$idFcs;  			
		}else{
		  $solicitud=(int)$lastRow +1;		     		
		}    
                       
		
        $data = array(
            'SERVICIO_ID'         => (int)$servicio->SERVICIO_ID +1,
            'SOLICITUD_ID'        => $solicitud,
            'CATSERVICIO_ID'      => $servicio->CATSERVICIO_ID,
            'ID_COMPONENTE'       => $servicio->ID_COMPONENTE,  
            'CANTIDAD'            => $servicio->CANTIDAD,
            'PRECIO'              => $servicio->PRECIO   
         );
		         
        $this->tableGateway->insert($data);
    }
    
     public function checkServicesEdit($idServices, $idFcs){
        //echo $idServices."-". $idFcs;   
		 $adapter = $this->tableGateway->getAdapter();
         $sql     = new Sql($adapter);
         $select = $sql->select();
         $select->from('SERVICIOS');        
         $select->where(array('CATSERVICIO_ID'=>$idServices,'SOLICITUD_ID'=>$idFcs));    
         //echo "<br>el query ".$select->getSqlString();         
         $resultSet = $this->tableGateway->selectWith($select); 
         //return $resultSet;
         $row = $resultSet->current();  
         if (!$row) {
             $row=FALSE;  
         }
         return $row;        
    }
       
     public function editServices(Servicios $servicio, $field, $idServices)
     {
            
			  $data = array(
                "$field" => $servicio->$field
              );
			  
			 $adapter = $this->tableGateway->getAdapter();
             $sql = new Sql($adapter);
             $update = $sql->update();
             $update->table('SERVICIOS');
             $update->set($data);
             $update->where('SERVICIO_ID = '.$idServices.'');      
             
			 //echo "<br>el query ".$update->getSqlString(); 
			    
             $statement = $sql->prepareStatementForSqlObject($update);
             $result = $statement->execute();
		     
             $flag = $result->getAffectedRows();
             if(!($flag)){
                     return false;
             }else{
                     return true;
             }
		                
        
     }
    
    
    
 }
<?php

 namespace Application\Model;

 use Zend\Db\TableGateway\TableGateway;
 use \Zend\Db\Sql\Sql;
  use Application\Model\CatCapTable;    
 use Zend\Db\ResultSet\ResultSet; 
   
 class CatCapTable
 {
     protected $tableGateway="CAT_CAP";
     
     public function __construct(TableGateway $tableGateway)
     {
         $this->tableGateway = $tableGateway;
         }

     public function fetchAll($search)
     {
     	     
         $adapter = $this->tableGateway->getAdapter();
         $sql     = new Sql($adapter);
         $select = $sql->select();
		 //$select->columns(array('CATSERVICIO_ID','CODIGO'));     
		 $select->from('CAT_CAP');
		 $where = new \Zend\Db\Sql\Where();		
         $where->like('NOMBRE', "%$search%"); // Alternatively, a shortcut.	
         $select->where($where);
        // echo "<br>el query ".$select->getSqlString();     
		 $resultSet = $this->tableGateway->selectWith($select);
		 return $resultSet; 
         /*$row = $resultSet->current();
         if (!$row) {
             throw new \Exception("Could not find row $id");
         }
         return $row;*/      
     }

      public function saveCatCap(CatCap $cap,$lastIdCatCap)   
     {
     	 $adapter = $this->tableGateway->getAdapter();   	
		 $sql     = new \Zend\Db\Sql\Sql($adapter);  
		
     	   $data = array(
      	    'CAP_ID'    =>  (int)$lastIdCatCap+1,  
            'NOMBRE'    => $cap->NOMBRE,
            'CORREO'    => $cap->CORREO,
            'TELEFONO'  => $cap->TELEFONO,
            'EXTENSION' => $cap->EXTENSION,                    
         );   
		   
          $insert = $sql->insert('CAT_CAP');			   
		  $insert->values($data);
	                       
		  $selectString = $sql->prepareStatementForSqlObject($insert);   
		  $result= $selectString->execute();  
		            
		 return $insert->CAP_ID;      
	     
     }
     
	 public function getLastCatCap()   
     {
     	 $adapter = $this->tableGateway->getAdapter();
         $sql     = new Sql($adapter);
         $select = $sql->select();
         $select->from('CAT_CAP');   
         $select->columns(array('CAP_ID'));
         $select->order('CAP_ID DESC');   
         
        // echo "<br>el query ".$select->getSqlString();
         $resultSet = $this->tableGateway->selectWith($select); 
         $row = $resultSet->current();
         if (!$row) {
             throw new \Exception("Could not find row");
         }
         return $row;
     }     
	 
	 public function fetchById($id)
     {
     	     
         $adapter = $this->tableGateway->getAdapter();
         $sql     = new Sql($adapter);
         $select = $sql->select();
		 //$select->columns(array('CATSERVICIO_ID','CODIGO'));     
		 $select->from('CAT_CAP');				
         $select->where(array('CAP_ID' => $id));      	
         //$select->where($where);  
         //echo "<br>el query ".$select->getSqlString();            
		 $resultSet = $this->tableGateway->selectWith($select);
		 /*return $resultSet;*/      
         $row = $resultSet->current();
         if (!$row) {
             throw new \Exception("Could not find row $id");
         }
         return $row;      
     }
	 
	 public function editCatCap(CatCap $cap, $field, $idCatCap)
     {
            
			  $data = array(
                "$field" => $cap->$field  
              );
			  
			 $adapter = $this->tableGateway->getAdapter();
             $sql = new Sql($adapter);
             $update = $sql->update();
             $update->table('CAT_CAP');
             $update->set($data);
             $update->where('CAP_ID = '.$idCatCap.'');      
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
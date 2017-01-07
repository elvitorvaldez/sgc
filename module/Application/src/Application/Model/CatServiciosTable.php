<?php

 namespace Application\Model;

 use Zend\Db\TableGateway\TableGateway;
 use \Zend\Db\Sql\Sql;
 use Zend\Db\ResultSet\ResultSet; 
   
 class CatServiciosTable
 {
     protected $tableGateway="CAT_SERVICIO";
     
     public function __construct(TableGateway $tableGateway)
     {
         $this->tableGateway = $tableGateway;
         }

     public function fetchAll($search)
     {
     	     
         $adapter = $this->tableGateway->getAdapter();
         $sql     = new Sql($adapter);
         $select = $sql->select();
		 $select->columns(array('CATSERVICIO_ID','CODIGO'));     
		 $select->from('CAT_SERVICIO');
		 $where = new \Zend\Db\Sql\Where();		
         $where->like('CODIGO', "%$search%"); // Alternatively, a shortcut.	
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
	 
	  public function saveCatServicios(CatServicios $catServicios,$lastIdCatServicios)   
     {
     	 $adapter = $this->tableGateway->getAdapter();   	
		 $sql     = new \Zend\Db\Sql\Sql($adapter);  
		
     	   $data = array(
      	    'CATSERVICIO_ID' =>  (int)$lastIdCatServicios+1,      
            'CODIGO'         => $catServicios->CODIGO,
            'DESCRIPCION'    => $catServicios->DESCRIPCION,
            'TIPOSERVICIO'   => $catServicios->TIPOSERVICIO,
                               
         );     
		      
          $insert = $sql->insert('CAT_SERVICIO');			   
		  $insert->values($data);  	                              
		  $selectString = $sql->prepareStatementForSqlObject($insert);   
		  $result= $selectString->execute();  
		            
		 return $insert->CATSERVICIO_ID;        
	     
     }

     public function getCatServicio($id)
     {
         $id  = (int) $id;
		 $adapter = $this->tableGateway->getAdapter();    
         $sql     = new Sql($adapter);
         $select = $sql->select();
		 $select->from('CAT_SERVICIO');
		 $select->where(array('CATSERVICIO_ID' => $id));  
         //echo "<br>el query ".$select->getSqlString();                         
         $resultSet = $this->tableGateway->selectWith($select);   
         return $resultSet; 
     }   

     public function getServiciosByTipo($servicio,$search)
     {
         $id  = (int) $id;
		 $adapter = $this->tableGateway->getAdapter();
         $sql     = new Sql($adapter);
         $select = $sql->select();
		 $select->columns(array('CATSERVICIO_ID','CODIGO'));   
		 $select->from('CAT_SERVICIO');
		 $where = new \Zend\Db\Sql\Where();
		 $select->where(array('TIPOSERVICIO' => $servicio))		
         ->where->like('CODIGO', "%$search%"); // Alternatively, a shortcut.	
         //$select->where($where);  
		 //$select->where(array('TIPOSERVICIO' => $servicio));
         //$rowset = $this->tableGateway->select(array('TIPOSERVICIO' => $servicio));
		 //echo "<br>el query ".$select->getSqlString();             
		 $resultSet = $this->tableGateway->selectWith($select);
		 return $resultSet; 
		  
         /*$row = $resultSet->current();
         if (!$row) {
             throw new \Exception("Could not find row $id");
         }
         return $row;*/
     }    
	 
	 public function getLastCatServicios()   
     {
     	 $adapter = $this->tableGateway->getAdapter();
         $sql     = new Sql($adapter);
         $select = $sql->select();
         $select->from('CAT_SERVICIO');   
         $select->columns(array('CATSERVICIO_ID'));
         $select->order('CATSERVICIO_ID DESC');       
         
         //echo "<br>el query ".$select->getSqlString();
         $resultSet = $this->tableGateway->selectWith($select); 
         $row = $resultSet->current();
         if (!$row) {
             throw new \Exception("Could not find row");
         }
         return $row;
     }     
 }
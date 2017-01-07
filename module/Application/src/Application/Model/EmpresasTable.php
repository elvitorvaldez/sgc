<?php
 namespace Application\Model;    
 use Zend\Db\TableGateway\TableGateway;
 use \Zend\Db\Sql\Sql;
 use Zend\Db\ResultSet\ResultSet;
 /*
 use Zend\ServiceManager\ServiceLocatorAwareInterface;  
 use Zend\ServiceManager\ServiceLocatorInterface;     
 implements ServiceLocatorAwareInterface 

   protected $services; 

        public function setServiceLocator(ServiceLocatorInterface $locator) 
        { 
            $this->services = $locator; 
        } 

        public function getServiceLocator() 
        { 
            return $this->services; 
        }       

  * */
 class EmpresasTable 
 {
 	
	
	 
     //protected $tableGateway="[10.1.17.19].SM9.dbo.COMPANYM1";
     protected $tableGateway="COMPANYM1";
     public function __construct(TableGateway $tableGateway)
     {    
         $this->tableGateway = $tableGateway;
		 
         }

     public function fetchAll()
     {
         $adapter = $this->tableGateway->getAdapter(); 
		 $sql = new Sql($adapter);
         $select = $sql->select();
         $select->columns(array('COMPANY','CUSTOMER_ID','CUSTOMER_SINCE','ADDRESS1','ADDRESS2','ADDRESS3','STATE','CODE','PHONE'));                    
         $select->from('COMPANYM1');    
		 $select->where(array('DELFLAG' => 1));      
		 //echo "<br>el query ".$select->getSqlString();  
		 $resultSet = $this->tableGateway->selectWith($select); 
         return $resultSet;    
		   
		// $resultSet = $this->tableGateway->select(array('DELFLAG' => 1)); 
		 //return $resultSet;    
         
     }
	  public function getEnterpriseByIdAll($id)
     {
         //$id  = (int) $id;
         die("die");    
         $rowset = $this->tableGateway->select(array('CUSTOMER_ID' => $id));   
         $row = $rowset->current();
   
         if ($row) {
            return $row;
         }
        
     }
         
    public function getEnterpriseById($id)
     {
         //$id  = (int) $id;
         
        /* $adapter = $this->tableGateway->getAdapter();
         $sql     = new Sql($adapter);
         $select = $sql->select();
         $select->from('COMPANYM1');   
         
         $select->where(array('CUSTOMER_ID' => $id,'DELFLAG'=>1));*/       
          
         $rowset = $this->tableGateway->select(array('CUSTOMER_ID' => $id,'DELFLAG'=>1));   
         $row = $rowset->current();
   
        /*$resultSet = $this->tableGateway->selectWith($select);    
         return $resultSet;*/         
   
         if ($row) {
            return $row;
         }  
        
     }
	 
	
     
    public function getEnterpriseByIdDel($id)
     {
         //$id  = (int) $id;
      
         $rowset = $this->tableGateway->select(array('CUSTOMER_ID' => $id,'DELFLAG'=>2));   
         $row = $rowset->current();
   
         if ($row) {
            return $row;
         }
        
     }
	 
        public function getEnterpriseByCompany($id)
     {
         //$id  = (int) $id;
      
         $rowset = $this->tableGateway->select(array('COMPANY' => $id));
         $row = $rowset->current();
   
         if ($row) {
            return $row;
         }
        
     }
     
     
      public function getEnterpriseIdByName($company)
      {
         $adapter = $this->tableGateway->getAdapter();
         $sql     = new Sql($adapter);
         $select = $sql->select();
         $select->columns(array('CUSTOMER_ID'));
         $select->from('COMPANYM1');         
         $select->where(array('COMPANY' => "$company"));  
         //echo "<br>el query ".$select->getSqlString();
         //die();
         $resultSet = $this->tableGateway->selectWith($select); 
         
          $row = $resultSet->current();

         if ($row) {
            return $row;
         }
      }
     
     
    public function getCompanyByRfc($rfc)
     {
       //  $resultSet = $this->tableGateway->select(array('CUSTOMER_ID'=>$rfc));  
         $adapter = $this->tableGateway->getAdapter();
         $sql     = new Sql($adapter);
         $select = $sql->select();
         //$select->columns(array('COMPANY'));    
         $select->from('COMPANYM1');         
         $select->where(array('CUSTOMER_ID' => "$rfc"));  
         //echo "<br>el query ".$select->getSqlString();        
         //die();    
         $resultSet = $this->tableGateway->selectWith($select); 
               		  
         return $resultSet;         
          //$row = $resultSet->current();
    
         /*if ($row) {
            return $row;
         } */   
        
         //return $resultSet;
     }
     
     
      public function getCompaniesForCombo()
     {
         $adapter = $this->tableGateway->getAdapter();        
         $sql     = new Sql($adapter);  
         $select = $sql->select();
         $select->columns(array('CUSTOMER_ID','COMPANY'));
         $select->from('COMPANYM1');    
         $select->order('COMPANY');
		 $select->where(array('DELFLAG' => 1)); 
         
		 $resultSet = $this->tableGateway->selectWith($select); 
         return $resultSet;         
         
         /*
         $statement = $sql->prepareStatementForSqlObject($select); 
         $results = $statement->execute();

         //convertir el resultado a objeto ResultSet
         $resultSet = new ResultSet();     
         //covertir el objeto ResultSet a arreglo
         $resultSet->initialize($results);
         return $resultSet->toArray();*/
     }
     
     
     

     public function saveAlbum(Empresas $empresas,$idEmpresa)
     {
       
		
		$adapter = $this->tableGateway->getAdapter();   	
		$sql     = new \Zend\Db\Sql\Sql($adapter);    
	    //$sql = new Sql($adapter);   
		   
		 
		 	
		  	
		  	
         $data = array(
            'CUSTOMER_ID'	    => $empresas->CUSTOMER_ID,
            'CUSTOMER_SINCE'	=> $empresas->CUSTOMER_SINCE,
            'COMPANY'           => $empresas->COMPANY,
            'COMPANY_FULL_NAME' => $empresas->COMPANY_FULL_NAME,           
            'ADDRESS1'  	    => $empresas->ADDRESS1,
            'ADDRESS2'          => $empresas->ADDRESS2,
            'ADDRESS3'      	=> $empresas->ADDRESS3,           
            'STATE'             => $empresas->STATE,          
            'CODE'              => $empresas->CODE,            
            'PHONE'             => $empresas->PHONE,             
            'LAST_UPDATE'	=> $empresas->LAST_UPDATE,
            'UPDATED_BY'	=> $empresas->UPDATED_BY,
            'DELFLAG' => "1",
         );
 
         //$id = (int) $empresas->id;
         //if ($id ==0)
         $id = $idEmpresa;
      
         if ($id =="") {
            									   
	            //$this->tableGateway->insert($data); last  
	            $insert = $sql->insert('COMPANYM1');			   
			    $insert->values($data);
	                   
				$selectString = $sql->prepareStatementForSqlObject($insert);   
			    $result= $selectString->execute();    
                
			 
         } else {
             if ($this->getEnterpriseByIdAll($id)) {  
                 $this->tableGateway->update($data, array('CUSTOMER_ID' => $id));
             } 
         }
     }

     public function deleteAlbum($id)
     {
     	
     	 $data = array(
            'DELFLAG' => "2"       
         );
		 
     	 $this->tableGateway->update($data, array('CUSTOMER_ID' => $id));
		 
         //$this->tableGateway->delete(array('CUSTOMER_ID' => $id));
        
     }
 }
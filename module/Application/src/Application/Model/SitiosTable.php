<?php

 namespace Application\Model;

 use Zend\Db\TableGateway\TableGateway;
 use Zend\Db\Sql\Sql;
  use Zend\Db\ResultSet\ResultSet;   
 
 class SitiosTable
 {
     //protected $tableGateway="[10.1.17.19].SM9.dbo.LOCM1";
     protected $tableGateway="LOCM1";
     public function __construct(TableGateway $tableGateway)
     {
         $this->tableGateway = $tableGateway;
         }

     public function fetchAll()
     {
      
//         $resultSet = $this->tableGateway->select();       
//         return $resultSet;
         $adapter = $this->tableGateway->getAdapter();        
         $sql = new Sql($adapter);
         $select = $sql->select();
         $select->columns(array('COMPANY','LOCATION_NAME','ADDRESS','ZIP','STATE','PHONE','LOCATION','ID'));               
         $select->from('LOCM1');    
		 $select->join('COMPANYM1', "COMPANYM1.CUSTOMER_ID = LOCM1.ID", array('DELFLAG'), 'inner');     
         $select->where(array('COMPANYM1.DELFLAG' => 1,'LOCM1.DELFLAG' => 1));    
         //echo "<br>el query ".$select->getSqlString();        
         $statement = $sql->prepareStatementForSqlObject($select);   
         $results = $statement->execute();

         //convertir el resultado a objeto ResultSet
         $resultSet = new ResultSet();   
         //covertir el objeto ResultSet a arreglo
         $resultSet->initialize($results);
         return $resultSet->toArray();      
         //$resultSet = $this->tableGateway->selectWith($select); 
         //return $resultSet;   
         
         
     }

     public function listSitesByEnterpriseName($id)
     {
         
         $adapter = $this->tableGateway->getAdapter();        
         $sql = new Sql($adapter);
         $select = $sql->select();
         $select->columns(array('COMPANY','LOCATION_NAME','LOCATION','ADDRESS','ZIP','STATE','PHONE'));
         $select->from('LOCM1');  
         $select->where(array('COMPANY' => $id,'LOCM1.DELFLAG' => 1));          
         $select->order('LOCATION_NAME');           
         //echo "<br>el query ".$select->getSqlString();          
         //$resultSet = $this->tableGateway->selectWith($select); 
         //return $resultSet;
         $statement = $sql->prepareStatementForSqlObject($select); 
         $results = $statement->execute();   

         //convertir el resultado a objeto ResultSet
         $resultSet = new ResultSet();     
         //covertir el objeto ResultSet a arreglo
         $resultSet->initialize($results);
         return $resultSet->toArray();        
     }
     
   
     
      public function listSitesByRFC($rfc)
     {
         
         $resultSet = $this->tableGateway->select(array('CUSTOMER_ID'=>$rfc)); 
         $row = $resultSet->current();
         die($rfc);
  
         //return $resultSet;
     }


     

     public function getSiteByLocationCode($id)
     {
         //$id  = (int) $id;
      
         $rowset = $this->tableGateway->select(array('LOCATION' => "$id"));
         $row = $rowset->current();
         
         if ($row) {
            return $row;
         }
        
     }
     
     
     
     public function getSitesComboByCompany($company)
     {
         $adapter = $this->tableGateway->getAdapter();        
         $sql     = new \Zend\Db\Sql\Sql($adapter);
         $select = $sql->select();
         $select->columns(array('LOCATION_NAME'));
         $select->from('LOCM1');  
         $select->where(array('COMPANY' => $company));      
         $select->order('LOCATION_NAME');           
         //echo "<br>el query ".$select->getSqlString();        
         $resultSet = $this->tableGateway->selectWith($select); 
         return $resultSet;
     }
     
     
     public function getEnterpriseBySiteId($location) 
     {
      $adapter = $this->tableGateway->getAdapter();
         $sql     = new Sql($adapter);
         $select = $sql->select();
         $select->from('LOCM1');
         $select->join('COMPANYM1', "COMPANYM1.CUSTOMER_ID = LOCM1.ID", array('CUSTOMER_ID'), 'inner'); 
         $select->where(array('LOCM1.LOCATION' => $location));    
         //echo "<br>el query ".$select->getSqlString();die();
         $resultSet = $this->tableGateway->selectWith($select); 
         $row = $resultSet->current();
    
         if ($row) {
           return $row->CUSTOMER_ID;
         }
     }
     
     public function getLocationNumberByLocationName($location) 
     {
//        $adapter = $this->tableGateway->getAdapter();        
//         $sql     = new \Zend\Db\Sql\Sql($adapter);
//         $select = $sql->select();
//         $select->columns(array('LOCATION'));
//         $select->from('LOCM1');  
//         $select->where(array('LOCATION_NAME' => $location));      
//         
//            
//         $resultSet = $this->tableGateway->selectWith($select); 
//          $row = $resultSet->current();
//         
//         if ($row) {
//            return $row['LOCATION'];
//         }
         
          $rowset = $this->tableGateway->select(array('LOCATION_NAME' => $location));
         $row = $rowset->current();
    
         if ($row) {
           return $row->LOCATION;
         }
         
         
     }
     
    public function getSitesByEnterpriseId($id)
     {
         //$id  = (int) $id;
      
         $rowset = $this->tableGateway->select(array('COMPANY' => $id));
         $row = $rowset->current();
   
         if ($row) {
            return $row;
         }
        
     }
     
     
     public function findSite($id)
     {
         //$id  = (int) $id;
      
         $rowset = $this->tableGateway->select(array('LOCATION' => $id));
         $row = $rowset->current();
   
         if ($row) {
            return $row;
         }
        
     }
     
     

     public function saveAlbum(Sitios $album,$idSitio,$idEmpresa)
     {
         
         $id = $idSitio;
         
         if (trim($id) =="") {
            $data = array(
         
            'COMPANY'                   => $album->COMPANY,
            'LOCATION_NAME'             => $album->LOCATION_NAME,
            'LOCATION'                  => $album->LOCATION,
            'LOCATION_CODE'             => $album->LOCATION_CODE,
            'ADDRESS'                   => $album->ADDRESS,            
            'ZIP'                       => $album->ZIP,        
            'CITY'                      => $album->CITY,
            'STATE'                     => $album->STATE,
            'PHONE'                     => $album->PHONE,
            'SYSMODTIME'                => $album->SYSMODTIME,            
            'SYSMODUSER'                => $album->SYSMODUSER,
            'ID'                        => $album->CUSTOMER_ID,
            'DELFLAG'                   => 1,       

         );
         }
         else
         {
         $data = array(
         
              
            'LOCATION_NAME'             => $album->LOCATION_NAME,
            'LOCATION'                  => $album->LOCATION,
            'LOCATION_CODE'             => $album->LOCATION_CODE,
            'ADDRESS'                   => $album->ADDRESS,            
            'ZIP'                       => $album->ZIP,        
            'CITY'                      => $album->CITY,
            'STATE'                     => $album->STATE,
            'PHONE'                     => $album->PHONE,
            'SYSMODTIME'                => $album->SYSMODTIME,            
            'SYSMODUSER'                => $album->SYSMODUSER,              
           

         );
         }
         
         
         
       
         if (trim($id) =="") {
      
                 $this->tableGateway->insert($data);
                 
         } else {
             
             if ($this->findSite($idSitio)) {  
                 //print_r($data);
                 $this->tableGateway->update($data, array('LOCATION' => $idSitio));                   
             } 
         }
     }

     public function deleteAlbum($id)
     {
         
		 $data = array(  	  
            'DELFLAG' => "2"       
         );
		 
     	 $this->tableGateway->update($data, array('LOCATION' => $id));   
         //$this->tableGateway->delete(array('LOCATION' => $id));
    
     }
 }
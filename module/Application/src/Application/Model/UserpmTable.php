<?php   
 namespace Application\Model;
 use Zend\Db\TableGateway\TableGateway;
 use Zend\Db\Sql\Sql;
 use Zend\Db\ResultSet\ResultSet;

 class UserpmTable  
 {
     //protected $tableGateway;
     protected $tableGateway="PM_EMPRESAS";

     public function __construct(TableGateway $tableGateway)
     {
         $this->tableGateway = $tableGateway;
     }

      public function saveAddUser(Userpm $userpm, $lastRow)
      {
         
         $status=1;
		 $pmId=(int)$lastRow+1;		   
		     
         $data = array(
            //'PM_ID'                       => $pmId,
            'CORREO'                      => $userpm->CORREO,
            'CUSTOMER_ID'                 => $userpm->CUSTOMER_ID,                     
            'ESTATUS'                     => $status,
           
         );   
         
        
             $this->tableGateway->insert($data);
         
      }   
      
	  public function updateAddUser($CORREO, $PMID)
      {
         
        $this->tableGateway->update(array ('CORREO' => $CORREO ), array ('PM_ID' => (int)$PMID));    
            
         
      } 
	  
	  public function getLastPmuser() {   
         $adapter = $this->tableGateway->getAdapter();
         $sql     = new Sql($adapter);
         $select = $sql->select();
         $select->from('PM_EMPRESAS');     
         $select->columns(array('PM_ID'));
         $select->order('PM_ID DESC');
         
        // echo "<br>el query ".$select->getSqlString();
         $resultSet = $this->tableGateway->selectWith($select); 
         $row = $resultSet->current();
         if (!$row) {
             throw new \Exception("Could not find row");
         }
         return $row;
       }
     
	   public function checkUser($CUSTOMER_ID,$CORREO)
     {
         //$id  = (int) $id;
      
         $rowset = $this->tableGateway->select(array('CUSTOMER_ID' => $CUSTOMER_ID,'CORREO'=>$CORREO,'ESTATUS'=>1));        
         $row = $rowset->current();
         
         if ($row) {
            return $row;
         }
        
     }   
     
       public function checkById($CUSTOMER_ID)
     {
         //$id  = (int) $id;
      
         $rowset = $this->tableGateway->select(array('CUSTOMER_ID' => $CUSTOMER_ID,'ESTATUS'=>1));        
         $row = $rowset->current();
         
         if ($row) {
            return $row;  
         }   
        
     }
	 
	  public function getPmForCompanies($CORREO)  
     {
         $adapter = $this->tableGateway->getAdapter();        
         $sql     = new Sql($adapter);  
         $select = $sql->select();
         $select->columns(array('CORREO','CUSTOMER_ID'));
         $select->from('PM_EMPRESAS');    
         $select->order('PM_ID');
		 $select->where(array('CORREO' => $CORREO)); 
         
		 $resultSet = $this->tableGateway->selectWith($select); 
         return $resultSet;         
         
     }   
	
 }
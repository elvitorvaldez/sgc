<?php
 namespace Application\Model;    
 use Zend\Db\TableGateway\TableGateway;
 use \Zend\Db\Sql\Sql;
 use Zend\Db\ResultSet\ResultSet;
 
 class DeleteEmpresasTable 
 {
 		
     protected $tableGateway="COMPANYM1";
     public function __construct(TableGateway $tableGateway)
     {    
         $this->tableGateway = $tableGateway;
		 
         }
   
     public function saveAlbum(DeleteEmpresas $deleteempresas)     
     {
       //Empresas $empresas,$idEmpresa
       //die(":D");  
		
		$adapter = $this->tableGateway->getAdapter();   	
		$sql     = new \Zend\Db\Sql\Sql($adapter);    
	        
		  	
         $data = array(
            'CUSTOMER_ID'	    => $deleteempresas->CUSTOMER_ID,
            'CUSTOMER_SINCE'	=> $deleteempresas->CUSTOMER_SINCE,
            'COMPANY'           => $deleteempresas->COMPANY,
            'ADDRESS1'  	    => $deleteempresas->ADDRESS1,
            'ADDRESS2'          => $deleteempresas->ADDRESS2,
            'ADDRESS3'      	=> $deleteempresas->ADDRESS3,           
            'STATE'             => $deleteempresas->STATE,          
            'CODE'              => $deleteempresas->CODE,            
            'PHONE'             => $deleteempresas->PHONE,             
            'LAST_UPDATE'	    => $deleteempresas->LAST_UPDATE,
            'UPDATED_BY'	    => $deleteempresas->UPDATED_BY,
            //'DELFLAG'           => "1",
         );
 								   
	            //$this->tableGateway->insert($data); last  
	            $insert = $sql->insert('COMPANYM1');			   
			    $insert->values($data);   
	                   
				$selectString = $sql->prepareStatementForSqlObject($insert);   
			    $result= $selectString->execute();    
     
	  }   

         
 }
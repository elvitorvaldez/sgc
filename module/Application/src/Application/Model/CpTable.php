<?php

 namespace Application\Model;

 use Zend\Db\TableGateway\TableGateway;

 class CpTable
 {
     protected $tableGateway="CAT_CODIGOPOSTAL";

     public function __construct(TableGateway $tableGateway)
     {
         $this->tableGateway = $tableGateway;
         }

     public function fetchAll()
     {
         $resultSet = $this->tableGateway->select();        
         return $resultSet;
     }

     public function getSuburbByCp($cp)
     {
         $resultSet = $this->tableGateway->select(array('CP' => $cp));
         return $resultSet;
     }
     
     
     
     

     
 }
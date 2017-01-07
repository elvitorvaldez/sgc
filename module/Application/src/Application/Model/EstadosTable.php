<?php

 namespace Application\Model;

 use Zend\Db\TableGateway\TableGateway;

 class EstadosTable
 {
     protected $tableGateway="CAT_ESTADO";

     public function __construct(TableGateway $tableGateway)
     {
         $this->tableGateway = $tableGateway;
         }

     public function fetchAll()
     {
         $resultSet = $this->tableGateway->select();        
         return $resultSet;
     }

     public function getEstadoById($id)
     {
         $id  = (int) $id;
         $rowset = $this->tableGateway->select(array('ID_ESTADO' => $id));
         $row = $rowset->current();
         if (!$row) {
             throw new \Exception("No existe el estado $id");
         }
         return $row;
     }

     
 }
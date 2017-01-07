<?php

 namespace Application\Model;

 use Zend\Db\TableGateway\TableGateway;
 use Zend\Db\Sql\Sql;

 class MunicipiosTable
 {
     protected $tableGateway="CAT_MUNICIPIO";

     public function __construct(TableGateway $tableGateway)
     {
         $this->tableGateway = $tableGateway;
         }

     public function fetchAll()
     {
         $resultSet = $this->tableGateway->select();        
         return $resultSet;
     }

     public function getMunicipio($idEstado, $idMunicipio)
     {
         $id  = (int) $id;
         $rowset = $this->tableGateway->select(array('ID_MUN' => $idMunicipio, 'ID_ESTADO' => $idEstado));
         $row = $rowset->current();
         if (!$row) {
             throw new \Exception("No existe el municipio $idMunicipio en el estado $idEstado");
         }
         return $row;
     }

     
          public function getMunAndState($mun,$state)
        {
//           $mun=2;
//           $state=1;
            $adapter = $this->tableGateway->getAdapter();
            $sql     = new Sql($adapter);
            $select = $sql->select();
            $select->columns(array('DESC_MUN_DEL'));
            $select->from('CAT_MUNICIPIO');
            $select->join('CAT_ESTADO', "CAT_MUNICIPIO.ID_ESTADO = CAT_ESTADO.ID_ESTADO", array('DESC_ESTADO'), 'inner'); 
         
            $select->where(array('CAT_MUNICIPIO.ID_MUN' => (int)$mun, 'CAT_ESTADO.ID_ESTADO' => (int)$state));         
            //echo "<br>el query ".$select->getSqlString();
            $resultSet = $this->tableGateway->selectWith($select);
            $row = $resultSet->current();
            if (!$row) {
             throw new \Exception("No existe el municipio $mun en el estado $state");
         }
         return $row;
     
        }

 }
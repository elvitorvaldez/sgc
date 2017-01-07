<?php

namespace Application\Model;

 class CatServicios
 {
public $CATSERVICIO_ID;
public $CODIGO;
public $DESCRIPCION;
public $TIPOSERVICIO;
public $CANTIDAD;
public $PRECIO;



     public function exchangeArray($data)
     {
        $this->CATSERVICIO_ID	=(!empty($data['CATSERVICIO_ID'])) ? $data['CATSERVICIO_ID'] : null;
        $this->CODIGO           =(!empty($data['CODIGO'])) ? $data['CODIGO'] : null;
        $this->DESCRIPCION	=(!empty($data['DESCRIPCION'])) ? $data['DESCRIPCION'] : null;
        $this->TIPOSERVICIO	=(!empty($data['TIPOSERVICIO'])) ? $data['TIPOSERVICIO'] : null;
        $this->CANTIDAD         =(!empty($data['CANTIDAD'])) ? $data['CANTIDAD'] : null;
        $this->PRECIO       	=(!empty($data['PRECIO'])) ? $data['PRECIO'] : null;

     }
 }
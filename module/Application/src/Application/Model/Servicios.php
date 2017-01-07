<?php

namespace Application\Model;

 class Servicios
 {
public $SOLICITUD_ID;
public $SERVICIO_ID;
public $CATSERVICIO_ID;
public $CANTIDAD;
public $PRECIO;
public $ID_COMPONENTE;


     public function exchangeArray($data)
     {
        $this->SOLICITUD_ID	=(!empty($data['SOLICITUD_ID'])) ? $data['SOLICITUD_ID'] : null;
        $this->SERVICIO_ID	=(!empty($data['SERVICIO_ID'])) ? $data['SERVICIO_ID'] : null;
        $this->CATSERVICIO_ID	=(!empty($data['CATSERVICIO_ID'])) ? $data['CATSERVICIO_ID'] : null;
        $this->CANTIDAD	=(!empty($data['CANTIDAD'])) ? $data['CANTIDAD'] : null;
        $this->PRECIO	=(!empty($data['PRECIO'])) ? $data['PRECIO'] : null;
		$this->ID_COMPONENTE	=(!empty($data['ID_COMPONENTE'])) ? $data['ID_COMPONENTE'] : null;        

     }
 }
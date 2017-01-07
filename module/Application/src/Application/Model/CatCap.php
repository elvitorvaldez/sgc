<?php

namespace Application\Model;

 class CatCap
 {
public $CAP_ID;
public $NOMBRE;
public $CORREO;
public $TELEFONO;
public $EXTENSION;

  


     public function exchangeArray($data)
     {
        $this->CAP_ID	 =(!empty($data['CAP_ID'])) ? $data['CAP_ID'] : null;
        $this->NOMBRE    =(!empty($data['NOMBRE'])) ? $data['NOMBRE'] : null;
        $this->CORREO	 =(!empty($data['CORREO'])) ? $data['CORREO'] : null;
        $this->TELEFONO	 =(!empty($data['TELEFONO'])) ? $data['TELEFONO'] : null;
        $this->EXTENSION =(!empty($data['EXTENSION'])) ? $data['EXTENSION'] : null;
        

     }
 }
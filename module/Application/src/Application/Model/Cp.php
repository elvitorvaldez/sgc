<?php

namespace Application\Model;

 class Cp
 {
    public $ID_CODIGO_POSTAL;
    public $CP;
    public $DESC_COLONIA;
    public $ID_MUN;
    public $ID_ESTADO;



     public function exchangeArray($data)
     {
        $this->ID_CODIGO_POSTAL	=(!empty($data['ID_CODIGO_POSTAL'])) ? $data['ID_CODIGO_POSTAL'] : null;
        $this->CP               =(!empty($data['CP'])) ? $data['CP'] : null;
        $this->DESC_COLONIA	=(!empty($data['DESC_COLONIA'])) ? $data['DESC_COLONIA'] : null;
        $this->ID_MUN           =(!empty($data['ID_MUN'])) ? $data['ID_MUN'] : null;
        $this->ID_ESTADO	=(!empty($data['ID_ESTADO'])) ? $data['ID_ESTADO'] : null;


     }
 }
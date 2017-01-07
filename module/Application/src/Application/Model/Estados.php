<?php

namespace Application\Model;

 class Estados
 {
    public $ID_ESTADO;
    public $DESC_ESTADO;



     public function exchangeArray($data)
     {
        $this->ID_ESTADO	=(!empty($data['ID_ESTADO'])) ? $data['ID_ESTADO'] : null;
        $this->DESC_ESTADO  	=(!empty($data['DESC_ESTADO  '])) ? $data['DESC_ESTADO  '] : null;
     }
 }
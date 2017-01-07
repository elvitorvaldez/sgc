<?php

namespace Application\Model;

 class Municipios
 {
    public $ID_CVE_MUN;
    public $ID_MUN;
    public $DESC_MUN_DEL;
    public $ID_ESTADO;
    public $DESC_ESTADO;



     public function exchangeArray($data)
     {
        $this->DESC_ESTADO	=(!empty($data['DESC_ESTADO'])) ? $data['DESC_ESTADO'] : null;
        $this->ID_CVE_MUN	=(!empty($data['ID_CVE_MUN'])) ? $data['ID_CVE_MUN'] : null;
        $this->ID_MUN           =(!empty($data['ID_MUN'])) ? $data['ID_MUN'] : null;
        $this->DESC_MUN_DEL	=(!empty($data['DESC_MUN_DEL'])) ? $data['DESC_MUN_DEL'] : null;
        $this->ID_ESTADO	=(!empty($data['ID_ESTADO'])) ? $data['ID_ESTADO'] : null;

     }
 }
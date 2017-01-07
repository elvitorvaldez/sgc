<?php

namespace Application\Model;

 class Userpm
 {
        public $PM_ID;
        public $CORREO;     
        public $CUSTOMER_ID;
        public $ESTATUS;
          

     public function exchangeArray($data)
     {
            $this->PM_ID                  =(!empty($data['PM_ID'])) ? $data['PM_ID'] : null;  
            $this->CORREO                 =(!empty($data['CORREO'])) ? $data['CORREO'] : null;
            $this->CUSTOMER_ID            =(!empty($data['CUSTOMER_ID'])) ? $data['CUSTOMER_ID'] : null;
            $this->ESTATUS                =(!empty($data['ESTATUS'])) ? $data['ESTATUS'] : null;
     }
 }
<?php

namespace Application\Model;

 class Documentos
 {
        public $DOCUMENTO_ID;
        public $NOMBRE;     
        public $FECHA;
        public $FECHAULTMOD;		
		public $AREA;
        public $SOLICITUD_ID;     
        public $CUSTOMER_ID;
        public $MINUTA_ID;
		public $CARTACEPTACION_ID;
        public $MEMORIATECNICA_ID;
		
		public $EXTENSION;
		public $TIPO;     
		public $RUTA;
        public $CARGADO_POR;
          

     public function exchangeArray($data)
     {
            $this->DOCUMENTO_ID           =(!empty($data['DOCUMENTO_ID'])) ? $data['DOCUMENTO_ID'] : null;  
            $this->NOMBRE                 =(!empty($data['NOMBRE'])) ? $data['NOMBRE'] : null;
            $this->FECHA                  =(!empty($data['FECHA'])) ? $data['FECHA'] : null;
            $this->FECHAULTMOD            =(!empty($data['FECHAULTMOD'])) ? $data['FECHAULTMOD'] : null;
			$this->AREA                   =(!empty($data['AREA'])) ? $data['AREA'] : null;  
            $this->SOLICITUD_ID           =(!empty($data['SOLICITUD_ID'])) ? $data['SOLICITUD_ID'] : null;
            $this->CUSTOMER_ID            =(!empty($data['CUSTOMER_ID'])) ? $data['CUSTOMER_ID'] : null;
            $this->MINUTA_ID              =(!empty($data['MINUTA_ID'])) ? $data['MINUTA_ID'] : null;
			$this->CARTACEPTACION_ID      =(!empty($data['CARTACEPTACION_ID'])) ? $data['CARTACEPTACION_ID'] : null;  
            $this->MEMORIATECNICA_ID      =(!empty($data['MEMORIATECNICA_ID'])) ? $data['MEMORIATECNICA_ID'] : null;
            
			$this->EXTENSION              =(!empty($data['EXTENSION'])) ? $data['EXTENSION'] : null;  
            $this->TIPO                   =(!empty($data['TIPO'])) ? $data['TIPO'] : null;
			$this->RUTA                   =(!empty($data['RUTA'])) ? $data['RUTA'] : null;
			$this->CARGADO_POR            =(!empty($data['CARGADO_POR'])) ? $data['CARGADO_POR'] : null;     
     }
 }
<?php

namespace Application\Model;

 class Fcs
 {
        public $COMPANY;
        public $LOCATION_NAME;     
        public $SOLICITUD_ID;
        public $SERVICIO_ID;
        public $CAP_ID;
        public $FOLIO;
        public $CUSTOMER_ID;
        public $LOCATION_ID;
        public $TIPOMOVIMIENTO;
        public $FECHAEMISION;
        public $QO;
        public $ORDENAPROVISIONAMIENTO;
        public $COTIZACION;
        public $FCSID;
        public $FCSFECHAALTA;
        public $FCSFECHAMOD;
        public $FCSFECHATERMINO;
        public $FCSFLG;
        public $RSEID;
        public $RSEFECHAALTA;
        public $RSEFECHAMOD;
        public $RSEFECHATERMINO;
        public $RSEFLG;
        public $RESPONSABLEINSTALACION;
        public $CASOINSTALACION;
        public $TELRESPSITIO;
        public $ESTATUS;
        public $RSENOMSOLICITO;
        public $RSENOMAUTORIZO;
        public $VALIDADO;
		/*Relación EMPRESA_SERVICIOS*/  
		public $EMP_ID;	
		public $TIPOSERVICIO;		
		
		


     public function exchangeArray($data)
     {
            $this->COMPANY                =(!empty($data['COMPANY'])) ? $data['COMPANY'] : null;
            $this->LOCATION_NAME          =(!empty($data['COMPANY'])) ? $data['COMPANY'] : null;
            $this->SOLICITUD_ID           =(!empty($data['SOLICITUD_ID'])) ? $data['SOLICITUD_ID'] : null;
            $this->SERVICIO_ID            =(!empty($data['SERVICIO_ID'])) ? $data['SERVICIO_ID'] : null;
            $this->CAP_ID                 =(!empty($data['CAP_ID'])) ? $data['CAP_ID'] : null;
            $this->FOLIO                  =(!empty($data['FOLIO'])) ? $data['FOLIO'] : null;
            $this->CUSTOMER_ID  	  =(!empty($data['CUSTOMER_ID'])) ? $data['CUSTOMER_ID'] : null;
            $this->LOCATION_ID            =(!empty($data['LOCATION_ID'])) ? $data['LOCATION_ID'] : null;
            $this->TIPOMOVIMIENTO	  =(!empty($data['TIPOMOVIMIENTO'])) ? $data['TIPOMOVIMIENTO'] : null;
            $this->FECHAEMISION           =(!empty($data['FECHAEMISION'])) ? $data['FECHAEMISION'] : null;
            $this->QO                     =(!empty($data['QO'])) ? $data['QO'] : null;
            $this->ORDENAPROVISIONAMIENTO =(!empty($data['ORDENAPROVISIONAMIENTO'])) ? $data['ORDENAPROVISIONAMIENTO'] : null;
            $this->COTIZACION             =(!empty($data['COTIZACION'])) ? $data['COTIZACION'] : null;
            $this->FCSID                  =(!empty($data['FCSID'])) ? $data['FCSID'] : null;
            $this->FCSFECHAALTA           =(!empty($data['FCSFECHAALTA'])) ? $data['FCSFECHAALTA'] : null;
            $this->FCSFECHAMOD            =(!empty($data['FCSFECHAMOD'])) ? $data['FCSFECHAMOD'] : null;
            $this->FCSFECHATERMINO	  =(!empty($data['FCSFECHATERMINO'])) ? $data['FCSFECHATERMINO'] : null;
            $this->FCSFLG                 =(!empty($data['FCSFLG'])) ? $data['FCSFLG'] : null;
            $this->RSEID                  =(!empty($data['RSEID'])) ? $data['RSEID'] : null;
            $this->RSEFECHAALTA           =(!empty($data['RSEFECHAALTA'])) ? $data['RSEFECHAALTA'] : null;
            $this->RSEFECHAMOD            =(!empty($data['RSEFECHAMOD'])) ? $data['RSEFECHAMOD'] : null;
            $this->RSEFECHATERMINO	  =(!empty($data['RSEFECHATERMINO'])) ? $data['RSEFECHATERMINO'] : null;
            $this->RSEFLG                 =(!empty($data['RSEFLG'])) ? $data['RSEFLG'] : null;
            $this->RESPONSABLEINSTALACION =(!empty($data['RESPONSABLEINSTALACION'])) ? $data['RESPONSABLEINSTALACION'] : null;
            $this->CASOINSTALACION	  =(!empty($data['CASOINSTALACION'])) ? $data['CASOINSTALACION'] : null;
            $this->TELRESPSITIO           =(!empty($data['TELRESPSITIO'])) ? $data['TELRESPSITIO'] : null;
            $this->ESTATUS                =(!empty($data['ESTATUS'])) ? $data['ESTATUS'] : null;
            $this->RSENOMSOLICITO         =(!empty($data['RSENOMSOLICITO'])) ? $data['RSENOMSOLICITO'] : null;
            $this->RSENOMAUTORIZO         =(!empty($data['RSENOMAUTORIZO'])) ? $data['RSENOMAUTORIZO'] : null;
            $this->VALIDADO               =(!empty($data['VALIDADO'])) ? $data['VALIDADO'] : null;    
			
			$this->EMP_ID               =(!empty($data['EMP_ID'])) ? $data['EMP_ID'] : null;
			$this->TIPOSERVICIO         =(!empty($data['TIPOSERVICIO'])) ? $data['TIPOSERVICIO'] : null;    
     }
 }
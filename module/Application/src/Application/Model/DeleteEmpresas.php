<?php
    
namespace Application\Model;

 class DeleteEmpresas   
 {
    public $CUSTOMER_ID;
    public $CUSTOMER_SINCE;
    public $COMPANY;
    public $ADDRESS1;
    public $ADDRESS2;
    public $ADDRESS3;
    public $CITY;
    public $STATE;
    public $COUNTRY;
    public $CODE;
    public $SLA_NO;
    public $PHONE;
    public $FAX;   
    public $LAST_UPDATE;
    public $UPDATED_BY;
    public $SYSMODCOUNT;
    public $SYSMODUSER;
    public $SYSMODTIME;
    public $SHOW_COMPANY;
    public $MANDATORY_ASSET;
    public $ALWAYS_SHOW;
    public $SRVC_MANAGER;
    public $SRVC_DEL_MANAGER;
    public $COMPANY_FULL_NAME;
    public $COMPANYOLD;
    public $DELFLAG;
    public $DEFAULT_SLA;
    public $UCMDB_SYNCED;
    public $UCMDB_CUSTOMER_ID;
    public $UCMDB_USERID;
    public $UCMDB_PASSWORD;
    public $RC_SYNCED;
    public $NUMERO_EXTERIOR;
    public $NUMERO_INTERIOR;  

  
     public function exchangeArray($data)
     {
        $this->CUSTOMER_ID               =(!empty($data['CUSTOMER_ID'])) ? $data['CUSTOMER_ID'] : null;
        $this->CUSTOMER_SINCE            =(!empty($data['CUSTOMER_SINCE'])) ? $data['CUSTOMER_SINCE'] : null;
        $this->COMPANY                   =(!empty($data['COMPANY'])) ? $data['COMPANY'] : null;
        $this->ADDRESS1                  =(!empty($data['ADDRESS1'])) ? $data['ADDRESS1'] : null;
        $this->ADDRESS2                  =(!empty($data['ADDRESS2'])) ? $data['ADDRESS2'] : null;
        $this->ADDRESS3                  =(!empty($data['ADDRESS3'])) ? $data['ADDRESS3'] : null;
        $this->CITY                      =(!empty($data['CITY'])) ? $data['CITY'] : null;
        $this->STATE                     =(!empty($data['STATE'])) ? $data['STATE'] : null;
        $this->COUNTRY                   =(!empty($data['COUNTRY'])) ? $data['COUNTRY'] : null;
        $this->CODE                      =(!empty($data['CODE'])) ? $data['CODE'] : null;
        $this->SLA_NO                    =(!empty($data['SLA_NO'])) ? $data['SLA_NO'] : null;
        $this->PHONE                     =(!empty($data['PHONE'])) ? $data['PHONE'] : null;
        $this->FAX                       =(!empty($data['FAX'])) ? $data['FAX'] : null;
		$this->DELFLAG                   =(!empty($data['DELFLAG'])) ? $data['DELFLAG'] : null;
        $this->LAST_UPDATE               =(!empty($data['LAST_UPDATE'])) ? $data['LAST_UPDATE'] : null;
        $this->UPDATED_BY                =(!empty($data['UPDATED_BY'])) ? $data['UPDATED_BY'] : null;
        $this->SYSMODCOUNT               =(!empty($data['SYSMODCOUNT'])) ? $data['SYSMODCOUNT'] : null;
        $this->SYSMODUSER                =(!empty($data['SYSMODUSER'])) ? $data['SYSMODUSER'] : null;
        $this->SYSMODTIME                =(!empty($data['SYSMODTIME'])) ? $data['SYSMODTIME'] : null;
        $this->SHOW_COMPANY              =(!empty($data['SHOW_COMPANY'])) ? $data['SHOW_COMPANY'] : null;
        $this->MANDATORY_ASSET           =(!empty($data['MANDATORY_ASSET'])) ? $data['MANDATORY_ASSET'] : null;
        $this->ALWAYS_SHOW               =(!empty($data['ALWAYS_SHOW'])) ? $data['ALWAYS_SHOW'] : null;
        $this->SRVC_MANAGER              =(!empty($data['SRVC_MANAGER'])) ? $data['SRVC_MANAGER'] : null;
        $this->SRVC_DEL_MANAGER          =(!empty($data['SRVC_DEL_MANAGER'])) ? $data['SRVC_DEL_MANAGER'] : null;
        $this->COMPANY_FULL_NAME	     =(!empty($data['COMPANY_FULL_NAME'])) ? $data['COMPANY_FULL_NAME'] : null;
        $this->COMPANYOLD                =(!empty($data['COMPANYOLD'])) ? $data['COMPANYOLD'] : null;       
        $this->DEFAULT_SLA               =(!empty($data['DEFAULT_SLA'])) ? $data['DEFAULT_SLA'] : null;
        $this->UCMDB_SYNCED              =(!empty($data['UCMDB_SYNCED'])) ? $data['UCMDB_SYNCED'] : null;
        $this->UCMDB_CUSTOMER_ID	     =(!empty($data['UCMDB_CUSTOMER_ID'])) ? $data['UCMDB_CUSTOMER_ID'] : null;
        $this->UCMDB_USERID              =(!empty($data['UCMDB_USERID'])) ? $data['UCMDB_USERID'] : null;
        $this->UCMDB_PASSWORD            =(!empty($data['UCMDB_PASSWORD'])) ? $data['UCMDB_PASSWORD'] : null;
        $this->RC_SYNCED                 =(!empty($data['RC_SYNCED'])) ? $data['RC_SYNCED'] : null;
        $this->NUMERO_EXTERIOR           =(!empty($data['NUMERO_EXTERIOR'])) ? $data['NUMERO_EXTERIOR'] : null;
        $this->NUMERO_INTERIOR           =(!empty($data['NUMERO_INTERIOR'])) ? $data['NUMERO_INTERIOR'] : null;

     }
 }
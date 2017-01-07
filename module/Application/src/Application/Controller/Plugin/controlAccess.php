<?php 

namespace Application\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

use Zend\Session\Container;



class controlAccess extends AbstractPlugin
{
    public function getPerfil()
    {
       $user_session = new Container('user');
	   
	   return $user_session;
        
    }
	
	
	
	
	public function getTiempo($url)
    {
       	   
	   $inactivo=900;      
	           
       $user_session = new Container('user');
	   $tiempo=$user_session->tiempo;   
	     
		if(isset($user_session) ) {
		 	
	     $vida_session=time()- $tiempo;
			   
		if($vida_session>$inactivo){
		   
            foreach ($user_session->getArrayCopy() as $key => $item) {
             //unset ($user_session->$key);              
           }  
			
           //  return $url;    
			
		}else{
			$user_session->tiempo  = time();     
		}
	  } 
	}     	   
   
}
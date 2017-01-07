<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */   
 
  return array(
    /**
     * Database Connection One
     */
    'db' => array(
      'driver'    => 'Pdo',
      'dsn'       => 'dblib:host=10.3.18.72:1433;dbname=vsys_sgc',
      'charset'        =>  'UTF-8',
      'username'=>'sa',
      'password'=>'qW9.xa7N#JTt58w3!bF42$E-L',
      'pdotype'       => 'dblib'
    ),
    /**    
     * Database Connection Two
     */
     
     'smdb' => array(
      'driver'    => 'Pdo',   
      'dsn'       => 'dblib:host=10.3.18.72;dbname=SM9DEV',      
      'charset'        =>  'UTF-8',
      'username'=>'sa',
      'password'=>'qW9.xa7N#JTt58w3!bF42$E-L',    
      'pdotype'       => 'dblib'
     ),   
     
   /* 'smdb' => array(
      'driver'    => 'Pdo',   
      'dsn'       => 'dblib:host=10.1.17.19:1433;dbname=SM9',  
      'charset'        =>  'UTF-8',
      'username'=>'sgc',
      'password'=>'G1gibran',      
      'pdotype'       => 'dblib'
    ),*/      
       	
);
 
//SM9DEV qW9.xa7N#JTt58w3!bF42$E-L  
 
 

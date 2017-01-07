<?php

namespace Application\Form;
 
use Zend\Form\Form;
 
class LoginForm extends Form{
 
    public function __construct($name = null) {
        parent::__construct('login');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'username',
            'type' => 'Text',
            'options' => array(
                'label' => 'Usuario:',
            ),
        ));
 
        $this->add(array(
           'name' => 'password',
            'type' => 'password',
            'options' => array(
                'label' => 'Contraseña:',
            )
        ));
 
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Enviar',
                'id' => 'submitbutton',
                'class'=> 'btn btn-primary btn-md',
            ),
        ));
    }
 
}

<?php namespace TT\Auth\Admin\Domain;

use Sentry;

use Aura\Payload\PayloadFactory;

use TT\Auth\AuthFormFactory;

use TT\Auth\AbstractLoginService;

use Aura\Payload_Interface\PayloadStatus;

class LoginService extends AbstractLoginService {
    public function __construct(PayloadFactory $payload_factory, AuthFormFactory $form_factory ) {
        $this->payload_factory = $payload_factory;
        $this->form_factory = $form_factory;
    }

    public function login(array $credentials) {
        $payload = $this->payload_factory->newInstance();
        
        $form = $this->form_factory->newAdminAuthForm();

        if( ! $form->isValid($credentials) ) {
            $messages = $form->getErrors();

            $payload->setStatus(PayloadStatus::NOT_AUTHENTICATED);
            $payload->setOutput(['response'=>['messages'=>$messages]]);
            return $payload;
        }

        try {
            $user = Sentry::authenticate($credentials,true);
            
            $payload->setStatus(PayloadStatus::AUTHENTICATED);
            $payload->setOutput(['response'=>[]]);
            return $payload;
        }

        catch(\Cartalyst\Sentry\Users\WrongPasswordException $e) {
            $payload->setStatus(PayloadStatus::NOT_AUTHENTICATED);
            $messages = [$this->getMsg('messages.password_required')];
            $payload->setOutput(['response'=>['messages'=>$messages]]);
            return $payload;
        }   
    }

    public function getData() {
    
    }
}

?>

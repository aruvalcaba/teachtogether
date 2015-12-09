<?php namespace TT\PWD\Responder;

use Session;

use TT\Support\AbstractResponder;

use Aura\Payload_Interface\PayloadStatus;

class PwdResponder extends AbstractResponder {
	protected $views_path = __DIR__;
    protected $payload_method = [ PayloadStatus::SUCCESS=>'getPwdChange', PayloadStatus::ACCEPTED=>'accepted', PayloadStatus::NOT_VALID => 'notValid' ];

    protected function init() {
        parent::init();

        $view_names = ['pwd','pwd.json'];

        $view_registry = $this->view->getViewRegistry();

        foreach( $view_names as $name ) {
            $path = sprintf('%s/views/%s.php',$this->views_path,$name);
			$view_registry->set($name,$path);
        } 
    }

    protected function getPwdChange() {
        if( $this->negotiateMediaType() ) {
            if( $this->payload ) {
                $this->renderView('pwd');
            }
        }    
    }

    protected function accepted() {
            if( $this->payload ) {
				$alerts = $this->payload->getOutput()['alerts'];
       			Session::flash('alerts',$alerts);
                $this->renderView('pwd.json');
            }
        
    }

    protected function notValid() {
        if( $this->negotiateMediaType() ) {
            if( $this->payload ) {
                $this->response->status->setCode('401');
                $this->renderView('pwd.json');
            }
        }
    }
}

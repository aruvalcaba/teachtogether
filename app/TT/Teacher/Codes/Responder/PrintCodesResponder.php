<?php namespace TT\Teacher\Codes\Responder;

use TT\Support\AbstractResponder;

use Aura\Payload_Interface\PayloadStatus;

class PrintCodesResponder extends AbstractResponder {
    protected $payload_method = [ PayloadStatus::NOT_ACCEPTED => 'notAccepted', PayloadStatus::SUCCESS => 'success' ];
    protected $views_path = __DIR__;

    protected function init() {
        parent::init();

        $view_names = ['response.json'];

        $view_registry = $this->view->getViewRegistry();

        foreach($view_names as $name) {
            $path = sprintf('%s/views/%s.php',$this->views_path,$name);
            $view_registry->set($name,$path);
        }
    }

    public function notAccepted() {
        if( $this->negotiateMediaType() ) {
            if( $this->payload ) {
                $this->response->status->setCode('422');
                $this->renderView('response.json');
            }
        }
    }

    public function success() {
         if( $this->negotiateMediaType() ) {
            if( $this->payload ) {
                $this->renderView('response.json');
            }
        }
    }
}
<?php namespace TT\Home\Admin\Domain;

use Sentry;

use TT\Support\AbstractService;

use TT\Service\ParentService;

use TT\Service\TeacherService;

use TT\Service\ActivityService;

use Aura\Payload\PayloadFactory;

class HomeService extends AbstractService {
    public function __construct(
                                PayloadFactory $payload_factory, 
                                ActivityService $activity_service, 
                                ParentService $parent_service,
                                TeacherService $teacher_service) {
        $this->activity_service = $activity_service;
        $this->parent_service = $parent_service;
        $this->teacher_service = $teacher_service;
        $this->payload_factory = $payload_factory;
    }

    public function home() {
        try {
            $payload = $this->success();

            if( Sentry::check() ) {
                $output = $payload->getOutput();
                $user = Sentry::getUser();
                $output['user'] = $user;
                $output['activities'] = $this->activity_service->all();
                $output['parents'] = $this->parent_service->all();
                $output['teachers'] = $this->teacher_service->all();
				$output['data'] = $this->getData();
                $payload->setOutput($output);
            }
            
            return $payload;
        }

        catch(Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function getData() {
        return [
				'ace_family_link' => ['val' =>$this->getMsg('constants.ace_family_link')],
				'parents' => ['val'=>$this->getMsg('constants.parents')],
				'teachers' => ['val'=>$this->getMsg('constants.teachers')],
				'changed_pwd' => $this->getMsg('constants.change_password'),
				'logout' => $this->getMsg('constants.logout'),
				'activites' => $this->getMsg('constants.activities'),
				'upload_activity' => $this->getmsg('messages.upload_activity'),
];
    }
}

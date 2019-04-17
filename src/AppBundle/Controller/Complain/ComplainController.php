<?php
namespace AppBundle\Controller\Complain;

use AppBundle\Controller\BaseController;
use Biz\User\UserException;
use Symfony\Component\HttpFoundation\Request;

class ComplainController extends BaseController {

    public function indexAction()
    {
        $user = $this->getUser();
        if (!$user->isLogin()) {
            return $this->createMessageResponse('error', '请先登录！', '', 5, $this->generateUrl('login'));
        }

        $title = "商家自定义投诉文案"; // 商家自定义投诉文案
        return $this->render(
            'complain/index.html.twig',
            array(
                'title' => $title,
            )
        );
    }

    public function submitAction(Request $request) {
        if ($request->getMethod() == 'POST') {
            $user = $this->getUser();
            if (!$user->isLogin()) {
                return $this->createJsonResponse(array('result' => false, 'message' => 'noLogin'));
            }

            $complain['context'] = $request->get("context");
            $this->getComplainService()->submit($complain);
            return $this->createJsonResponse(array('result' => true, 'message' => ''));
        }
    }

    protected function getComplainService()
    {
        return $this->createService('Complain:ComplainService');
    }

}
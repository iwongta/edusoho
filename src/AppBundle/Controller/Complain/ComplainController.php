<?php
namespace AppBundle\Controller\Complain;

use AppBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;

class ComplainController extends BaseController {

    public function indexAction()
    {
        $title = "商家自定义投诉文案"; // 商家自定义投诉文案
        return $this->render(
            'complain/index.html.twig',
            array(
                'title' => $title,
            )
        );
    }

    public function submitAction(Request $request) {
        $complain['context'] = $request->get("context");
        $this->getComplainService()->submit($complain);
        var_dump($request);
        echo 111;exit;
    }

    protected function getComplainService()
    {
        return $this->createService('Complain:ComplainService');
    }

}
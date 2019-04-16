<?php
namespace AppBundle\Controller\Complain;

use AppBundle\Controller\BaseController;

class ComplainController extends BaseController {

    public function indexAction()
    {
        $title = ""; // 商家自定义投诉文案
        return $this->render(
            'complain/index.html.twig',
            array(
                'title' => $title,
            )
        );
    }

}
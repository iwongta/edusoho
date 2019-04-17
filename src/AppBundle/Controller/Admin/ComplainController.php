<?php
namespace AppBundle\Controller\Admin;

use AppBundle\Common\ArrayToolkit;
use AppBundle\Common\Paginator;
use Symfony\Component\HttpFoundation\Request;

class ComplainController extends BaseController {

    public function indexAction(Request $request) {
        $conditions = array();
        $paginator = new Paginator(
            $request,
            $this->getComplainService()->countComplains($conditions),
            20
        );
        $complains = $this->getComplainService()->searchComplains(
            $conditions,
            array("createdTime" => "desc"),
            $paginator->getOffsetCount(),
            $paginator->getPerPageCount()
        );
        $users = $this->getUserService()->findUsersByIds(ArrayToolkit::column($complains, 'adminId'));
        return $this->render('admin/complain/index.html.twig', array(
            'paginator'=>$paginator,
            'complains' => $complains,
            'users' => $users
        ));
    }

    protected function getComplainService() {
        return $this->createService("Complain:ComplainService");
    }
}
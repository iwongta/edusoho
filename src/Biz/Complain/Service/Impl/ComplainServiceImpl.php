<?php
namespace Biz\Complain\Service\Impl;

use AppBundle\Common\ArrayToolkit;
use Biz\Complain\Service\ComplainService;
use Biz\BaseService;


class ComplainServiceImpl extends BaseService implements ComplainService {

    public function submit($complain)
    {
        $complain = ArrayToolkit::parts(
            $complain,
            array(
                'context',
            )
        );
        $complain['adminId'] = $this->getCurrentUser()->getId();
        $created = $this->getComplainDao()->create($complain);

        return $created;
    }

    public function searchComplains(array $conditions, $orderBys, $start, $limit)
    {
        return $this->getComplainDao()->search($conditions, $orderBys, $start, $limit);
    }

    public function countComplains(array $conditions)
    {
        return $this->getComplainDao()->count($conditions);
    }

    protected function getComplainDao()
    {
        return $this->createDao('Complain:ComplainDao');
    }
}
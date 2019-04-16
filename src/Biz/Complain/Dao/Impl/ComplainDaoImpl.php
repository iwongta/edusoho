<?php
namespace Biz\Complain\Dao\Impl;

use Biz\Complain\Dao\ComplainDao;
use Codeages\Biz\Framework\Dao\AdvancedDaoImpl;

class ComplainDaoImpl extends AdvancedDaoImpl implements ComplainDao {
    protected $table = 'complain';


    public function declares()
    {
        return array(
            'timestamps' => array('createdTime'),
            'orderbys' => array('replayId', 'createdTime'),
            'conditions' => array(
                'courseId = :courseId',
                'lessonId = :lessonId',
                'hidden = :hidden',
                'copyId = :copyId',
                'type = :type',
            ),
        );
    }

}
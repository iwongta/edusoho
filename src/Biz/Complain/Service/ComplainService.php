<?php
namespace Biz\Complain\Service;
/**
 * 投诉服务
 * Interface ComplainService
 * @package Biz\Complain\Service
 */
interface ComplainService {

    /**
     * 提交投诉
     * @param $complain
     * @return mixed
     */
    public function submit($complain);

    /**
     * 查询投诉列表
     * @param array $conditions
     * @param $orderBys
     * @param $start
     * @param $limit
     * @return mixed
     */
    public function searchComplains(array $conditions, $orderBys, $start, $limit);

    /**
     * 查询投诉记录总数
     * @param array $conditions
     * @return mixed
     */
    public function countComplains(array $conditions);
}
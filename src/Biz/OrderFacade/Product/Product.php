<?php

namespace Biz\OrderFacade\Product;

use AppBundle\Common\StringToolkit;
use Biz\Order\Service\OrderService;
use Biz\OrderFacade\Command\Deduct\PickedDeductWrapper;
use Biz\Sms\Service\SmsService;
use Biz\System\Service\LogService;
use Codeages\Biz\Framework\Context\BizAware;
use AppBundle\Common\MathToolkit;
use Codeages\Biz\Framework\Order\Status\OrderStatusCallback;

abstract class Product extends BizAware implements OrderStatusCallback
{
    /**
     * 商品ID
     *
     * @var int
     */
    public $targetId;

    /**
     * 商品类型
     *
     * @var string
     */
    public $targetType;

    /**
     * 商品名称
     *
     * @var string
     */
    public $title;

    /**
     * 商品价格
     *
     * @var float
     */
    public $originPrice;

    /**
     * 可使用的折扣
     *
     * @var array
     */
    public $availableDeducts = array();

    /**
     * 使用到的折扣
     *
     * @var array
     */
    public $pickedDeducts = array();

    /**
     * 返回的链接
     *
     * @var string
     */
    public $backUrl = '';

    /**
     * 成功支付返回链接
     *
     * @var string
     */
    public $successUrl = '';

    /**
     * 最大虚拟币抵扣百分比
     *
     * @var int
     */
    public $maxRate = 100;

    /**
     * 商品数量
     *
     * @var int
     */
    public $num = 1;

    /**
     * 商品单位
     *
     * @var string
     */
    public $unit = '';

    abstract public function init(array $params);

    abstract public function validate();

    public function setAvailableDeduct($params = array())
    {
        /** @var $pickedDeductWrapper PickedDeductWrapper */
        $availableDeductWrapper = $this->biz['order.product.available_deduct_wrapper'];

        $availableDeductWrapper->wrapper($this, $params);
    }

    public function setPickedDeduct($params)
    {
        /** @var $pickedDeductWrapper PickedDeductWrapper */
        $pickedDeductWrapper = $this->biz['order.product.picked_deduct_wrapper'];

        $pickedDeductWrapper->wrapper($this, $params);
    }

    public function getPayablePrice()
    {
        $payablePrice = $this->originPrice;
        foreach ($this->pickedDeducts as $deduct) {
            $payablePrice -= $deduct['deduct_amount'];
        }

        return $payablePrice > 0 ? $payablePrice : 0;
    }

    protected function smsCallback($orderItem)
    {
        $smsType = 'sms_'.$this->targetType.'_buy_notify';

        if ($this->getSmsService()->isOpen($smsType)) {
            $userId = $orderItem['user_id'];
            $parameters = array();
            $parameters['order_title'] = $orderItem['title'];
            $parameters['order_title'] = StringToolkit::cutter($parameters['order_title'], 20, 15, 4);
            $price = MathToolkit::simple($orderItem['order']['pay_amount'], 0.01);
            $parameters['totalPrice'] = $price.'元';

            $description = $parameters['order_title'].'成功回执';

            $this->getSmsService()->smsSend($smsType, array($userId), $description, $parameters);
        }
    }

    public function getCreateExtra()
    {
        return array();
    }

    /**
     * @return SmsService
     */
    private function getSmsService()
    {
        return $this->biz->service('Sms:SmsService');
    }

    /**
     * @return LogService
     */
    protected function getLogService()
    {
        return $this->biz->service('System:LogService');
    }

    /**
     * @return OrderService
     */
    protected function getOrderService()
    {
        return $this->biz->service('Order:OrderService');
    }
}
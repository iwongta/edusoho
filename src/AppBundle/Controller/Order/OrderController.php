<?php

namespace AppBundle\Controller\Order;

use AppBundle\Controller\BaseController;
use Biz\OrderFacade\Product\Product;
use Biz\OrderFacade\Service\OrderFacadeService;
use Symfony\Component\HttpFoundation\Request;

class OrderController extends BaseController
{
    public function showAction(Request $request)
    {
        $targetType = $request->query->get('targetType');
        $fields = $request->query->all();
        
        $product = $this->getProduct($targetType, $fields);
        $product = $this->getOrderFacadeService()->show($product);

        return $this->render('order/show/index.html.twig', array(
            'product' => $product,
        ));
    }

    public function createAction()
    {
        $product = $this->getProduct('course', array());

        $order = $this->getOrderFacadeService()->create($product);
    }

    public function priceAction()
    {
        $product = $this->getProduct('course', array());

        $price = $this->getOrderFacadeService()->getPrice($product);
    }

    private function getProduct($targetType, $params)
    {
        $biz = $this->getBiz();

        /* @var $product Product */
        $product = $biz['order.product.'.$targetType];
        $product->setBiz($biz);

        $product->init($params);

        return $product;
    }

    /**
     * @return OrderFacadeService
     */
    private function getOrderFacadeService()
    {
        return $this->createService('OrderFacade:OrderFacadeService');
    }

    protected function getCashAccountService()
    {
        return $this->getBiz()->service('Cash:CashAccountService');
    }
}

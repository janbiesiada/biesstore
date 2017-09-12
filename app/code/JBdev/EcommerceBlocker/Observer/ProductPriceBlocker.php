<?php
/**
 * Created by PhpStorm.
 * User: jbiesiada
 * Date: 03.11.16
 * Time: 12:54
 */

namespace JBdev\EcommerceBlocker\Observer;


use Magento\Framework\Event\ObserverInterface;

class ProductPriceBlocker extends AbstractBlocker implements ObserverInterface
{



    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if($this->getHelper()->isEcommerceBlocked()) {
            $observer->getData('product')->setCanShowPrice(false);
        }
    }
}
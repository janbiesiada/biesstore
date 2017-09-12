<?php

namespace JBdev\EcommerceBlocker\Observer;



use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

class ProductSalableBlocker extends AbstractBlocker  implements ObserverInterface
{
    public function execute(EventObserver $observer)
    {
        if($this->getHelper()->isEcommerceBlocked()) {
            $observer->getData('salable')->setData('is_salable',false);
        }
    }
}
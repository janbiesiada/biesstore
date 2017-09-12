<?php

namespace JBdev\EcommerceBlocker\Observer;


use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class BlockBlocker extends AbstractBlocker implements ObserverInterface
{
    protected $blockedBlocks = [
        "minicart",
        "product.price.render.default",
        "product.price.final",
        "product.info.addtocart",
        "product.info.simple",
        "top.search",
        "header.links",
    ];

    public function execute(Observer $observer)
    {
        $block = $observer->getData('block');
        if( $this->isEcommerce($block) && $this->getHelper()->isEcommerceBlocked() ){
            $block->setData("module_name","Blocker");
        }
    }

    public function isEcommerce($block)
    {
        return in_array(trim($block->getNameInLayout()), $this->blockedBlocks);
    }

}
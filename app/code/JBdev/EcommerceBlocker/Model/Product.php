<?php
namespace JBdev\EcommerceBlocker\Model;

use Magento\Framework\App\ObjectManager;

class Product extends \Magento\Catalog\Model\Product
{

    public function getCanShowPrice()
    {
        return $this->getBlockerHelper()->isEcommerceEnable() && !$this->getBlockerHelper()->isCustomerNotAllowed();
    }

    public function getBlockerHelper()
    {
        $objectManager = ObjectManager::getInstance();
        return $objectManager->create('JBdev\EcommerceBlocker\Helper\Data');
    }
}
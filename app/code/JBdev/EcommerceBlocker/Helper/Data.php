<?php
/**
 * Copyright © 2015 JBdev . All rights reserved.
 */
namespace JBdev\EcommerceBlocker\Helper;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\Customer\Model\Session;

class Data extends AbstractHelper
{
    protected $_scopeConfig;
    const ECOMMERCE_ENABLE = 'ecommerce_blocker_section/blocker/enable';
    protected $_customerSession;

    /**
     * @param Context $context
     * @param Session $customerSession
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->_scopeConfig = $scopeConfig;
        $this->_customerSession = $customerSession;
        parent::__construct($context);
    }

    public function isEcommerceEnable()
    {
        return $this->_scopeConfig->getValue(self::ECOMMERCE_ENABLE, ScopeInterface::SCOPE_STORE)==='1';
    }

    public function isEcommerceBlocked()
    {
        return ! $this->isEcommerceEnable();
    }

    public function isCustomerNotAllowed()
    {
        return true;
    }
}
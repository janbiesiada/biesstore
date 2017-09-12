<?php

namespace JBdev\EcommerceBlocker\Observer;


use JBdev\EcommerceBlocker\Helper\Data;

class AbstractBlocker
{
    protected $_helper;

    /**
     * AbstractBlocker constructor.
     * @param Data $helper
     */
    public function __construct(
        Data $helper
    )
    {
        $this->_helper = $helper;
    }

    /**
     * @return Data
     */
    public function getHelper()
    {
        return $this->_helper;
    }

}
<?php


namespace JBdev\EcommerceBlocker\Setup;


use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\App\Config\Storage\WriterInterface;


/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{
    /**
     * Config writer
     *
     * @var \Magento\Framework\App\Config\Storage\WriterInterface
     */
    private $_configWriter;
    /**
     * EAV setup factory
     *
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    public function __construct( WriterInterface $configWriter,EavSetupFactory $eavSetupFactory)
    {
        $this->_configWriter = $configWriter;
        $this->eavSetupFactory = $eavSetupFactory;
    }


    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $this->_configWriter->save('advanced/modules_disable_output/Blocker', "1");
    }


}
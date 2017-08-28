<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace JBdev\DataPopulate\Setup;

use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Api\SimpleDataObjectConverter;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Store\Model\Group as GroupModel;
use Magento\Store\Model\GroupFactory;
use Magento\Store\Model\Store;
use Magento\Store\Model\Store as StoreModel;
use Magento\Store\Model\StoreFactory;


class UpgradeData implements UpgradeDataInterface
{
    /** @var GroupModel */
    protected $groupModel;

    /** @var GroupFactory */
    protected $groupFactory;

    /** @var StoreModel */
    protected $storeModel;

    /** @var StoreFactory */
    protected $storeFactory;
    /**
     * @var WriterInterface
     */
    private $writer;

    public function __construct(
        WriterInterface $writer,
        GroupModel $groupModel,
        GroupFactory $groupFactory,
        StoreModel $storeModel,
        StoreFactory $storeFactory
    )
    {
        $this->groupModel = $groupModel;
        $this->groupFactory = $groupFactory;
        $this->storeModel = $storeModel;
        $this->storeFactory = $storeFactory;
        $this->writer = $writer;
    }


    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        if ($this->checkVersion('1.0.1', $context)) {
            foreach ($this->getConfigData() as $config) {
                $storeViews = [];
                $storeViews[] = $this->createStoreWithView('bies','en', 2, true);
                $storeViews[] = $this->createStoreWithView('bies','pl', 2);

                $c = (object)$config;
                $this->writer->save($c->path, $c->value, $c->scope, $c->scope_id);

            }
        }


        $setup->endSetup();
    }


    public function checkVersion($version, ModuleContextInterface $context)
    {
        return $context->getVersion()
            && version_compare($context->getVersion(), $version) < 0;
    }

    public function getConfigData()
    {
        return [
            ['scope' => 'default', 'scope_id' => '0', 'path' => 'catalog/category/root_id', 'value' => '2'],
            ['scope' => 'stores', 'scope_id' => '2', 'path' => 'currency/options/allow', 'value' => 'EUR,PLN'],
            ['scope' => 'stores', 'scope_id' => '2', 'path' => 'currency/options/default', 'value' => 'PLN'],
            ['scope' => 'default', 'scope_id' => '0', 'path' => 'general/locale/code', 'value' => 'pl_PL'],
            ['scope' => 'stores', 'scope_id' => '1', 'path' => 'general/locale/code', 'value' => 'en_US'],
            ['scope' => 'stores', 'scope_id' => '2', 'path' => 'general/locale/code', 'value' => 'pl_PL'],
            ['scope' => 'stores', 'scope_id' => '2', 'path' => 'general/locale/firstday', 'value' => '1'],
            ['scope' => 'default', 'scope_id' => '0', 'path' => 'general/locale/timezone', 'value' => 'America/Los_Angeles'],
            ['scope' => 'stores', 'scope_id' => '2', 'path' => 'general/locale/weight_unit', 'value' => 'kgs'],
            ['scope' => 'default', 'scope_id' => '0', 'path' => 'general/region/display_all', 'value' => '1'],
            ['scope' => 'default', 'scope_id' => '0', 'path' => 'general/region/state_required', 'value' => 'AT,BR,CA,EE,FI,LV,LT,RO,ES,CH,US'],
            ['scope' => 'default', 'scope_id' => '0', 'path' => 'general/single_store_mode/enabled', 'value' => '0'],
            ['scope' => 'default', 'scope_id' => '0', 'path' => 'general/store_information/city', 'value' => 'NULL'],
            ['scope' => 'default', 'scope_id' => '0', 'path' => 'general/store_information/country_id', 'value' => 'NULL'],
            ['scope' => 'default', 'scope_id' => '0', 'path' => 'general/store_information/hours', 'value' => 'NULL'],
            ['scope' => 'default', 'scope_id' => '0', 'path' => 'general/store_information/merchant_vat_number', 'value' => 'NULL'],
            ['scope' => 'default', 'scope_id' => '0', 'path' => 'general/store_information/name', 'value' => 'NULL'],
            ['scope' => 'default', 'scope_id' => '0', 'path' => 'general/store_information/phone', 'value' => 'NULL'],
            ['scope' => 'default', 'scope_id' => '0', 'path' => 'general/store_information/postcode', 'value' => 'NULL'],
            ['scope' => 'default', 'scope_id' => '0', 'path' => 'general/store_information/region_id', 'value' => 'NULL'],
            ['scope' => 'default', 'scope_id' => '0', 'path' => 'general/store_information/street_line1', 'value' => 'NULL'],
            ['scope' => 'default', 'scope_id' => '0', 'path' => 'general/store_information/street_line2', 'value' => 'NULL'],
            ['scope' => 'default', 'scope_id' => '0', 'path' => 'web/unsecure/base_url', 'value' => 'http://localhost/bies/'],
            ['scope' => 'stores', 'scope_id' => '1', 'path' => 'web/unsecure/base_url', 'value' => 'http://localhost/bies.en/'],
            ['scope' => 'stores', 'scope_id' => '2', 'path' => 'web/unsecure/base_url', 'value' => 'http://localhost/bies.pl/']
        ];


    }

    protected function createStoreWithView($code, $storeViewName, $rootCategoryId, $isFirst = false)
    {
        $store = $this->groupModel->load(1);
        $store
            ->setWebsiteId(1)
            ->setRootCategoryId($rootCategoryId)
            ->save();

        $storeView = ($isFirst) ? $this->storeModel->load(1) : $this->storeFactory->create();
        $storeView
            ->setName($storeViewName)
            ->setWebsiteId(1)
            ->setGroupId($store->getGroupId())
            ->setIsActive(true);

        if (!$isFirst) {
            $storeView->setCode("{$code}_{$storeViewName}");
        }

        $storeView->save();
        $this->writer->save('catalog/category/root_id', $rootCategoryId, 'stores', $store->getId());

        return $storeView;
    }

}

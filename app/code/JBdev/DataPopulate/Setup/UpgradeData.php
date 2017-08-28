<?php

namespace JBdev\DataPopulate\Setup;

use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Store\Model\Group as GroupModel;
use Magento\Store\Model\Store;
use Magento\Store\Model\Store as StoreModel;
use Magento\Store\Model\StoreFactory;
use Magento\Store\Model\Website as WebsiteModel;


class UpgradeData implements UpgradeDataInterface
{
    /** @var GroupModel */
    protected $groupModel;

    /** @var StoreModel */
    protected $storeModel;

    /** @var StoreFactory */
    protected $storeFactory;
    /**
     * @var WriterInterface
     */
    private $writer;
    /**
     * @var WebsiteModel
     */
    private $websiteModel;

    public function __construct(
        WriterInterface $writer,
        WebsiteModel $websiteModel,
        GroupModel $groupModel,
        StoreModel $storeModel,
        StoreFactory $storeFactory
    )
    {
        $this->groupModel = $groupModel;
        $this->storeModel = $storeModel;
        $this->storeFactory = $storeFactory;
        $this->writer = $writer;
        $this->websiteModel = $websiteModel;
    }


    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        if (version_compare($context->getVersion(), '1.0.1') < 0) {
                $storeViews = [];
                $storeViews[] = $this->createStoreWithView('bies','en', 2, 1,true);
                $storeViews[] = $this->createStoreWithView('bies','pl', 2,2);

            foreach ($this->getConfigData() as $config) {
                $c = (object)$config;
                $this->writer->save($c->path, $c->value, $c->scope, $c->scope_id);

            }
        }
        $setup->endSetup();
    }


    public function getConfigData()
    {
        return [
            ['scope' => 'default', 'scope_id' => '0', 'path' => 'catalog/category/root_id', 'value' => '2'],
            ['scope' => 'stores', 'scope_id' => '2', 'path' => 'currency/options/allow', 'value' => 'PLN'],
            ['scope' => 'stores', 'scope_id' => '2', 'path' => 'currency/options/default', 'value' => 'PLN'],
            ['scope' => 'stores', 'scope_id' => '1', 'path' => 'currency/options/allow', 'value' => 'USD'],
            ['scope' => 'stores', 'scope_id' => '1', 'path' => 'currency/options/default', 'value' => 'USD'],
            ['scope' => 'default', 'scope_id' => '0', 'path' => 'general/locale/code', 'value' => 'pl_PL'],
            ['scope' => 'stores', 'scope_id' => '1', 'path' => 'general/locale/code', 'value' => 'en_US'],
            ['scope' => 'stores', 'scope_id' => '2', 'path' => 'general/locale/code', 'value' => 'pl_PL'],
            ['scope' => 'stores', 'scope_id' => '2', 'path' => 'general/locale/firstday', 'value' => '1'],
            ['scope' => 'default', 'scope_id' => '0', 'path' => 'general/locale/timezone', 'value' => 'America/Los_Angeles'],
            ['scope' => 'stores', 'scope_id' => '2', 'path' => 'general/locale/weight_unit', 'value' => 'kgs'],
            ['scope' => 'default', 'scope_id' => '0', 'path' => 'web/unsecure/base_url', 'value' => 'http://localhost/bies/'],
            ['scope' => 'stores', 'scope_id' => '1', 'path' => 'web/unsecure/base_url', 'value' => 'http://localhost/bies.en/'],
            ['scope' => 'stores', 'scope_id' => '2', 'path' => 'web/unsecure/base_url', 'value' => 'http://localhost/bies.pl/'],
            ['scope' => 'websites', 'scope_id' => '1', 'path' => 'design/theme/theme_id', 'value' => '4']
        ];


    }

    protected function createStoreWithView($code, $storeViewName, $rootCategoryId, $sortOrder, $isFirst = false)
    {
        $website = $this->websiteModel->load(1);
        $website->setName(ucfirst($code));
        $website->save();
        $storeGroup = $this->groupModel->load(1);
        $storeGroup
            ->setWebsiteId(1)
            ->setRootCategoryId($rootCategoryId)
            ->setName(ucfirst($code))
            ->save();

        $store = ($isFirst) ? $this->storeModel->load(1) : $this->storeFactory->create();
        $store
            ->setName($storeViewName)
            ->setWebsiteId(1)
            ->setGroupId($storeGroup->getGroupId())
            ->setSortOrder($sortOrder)
            ->setIsActive(true);

        $store->setCode("{$code}_{$storeViewName}");
        $store->save();
        $this->writer->save('catalog/category/root_id', $rootCategoryId, 'stores', $storeGroup->getId());


        return $store;
    }

}

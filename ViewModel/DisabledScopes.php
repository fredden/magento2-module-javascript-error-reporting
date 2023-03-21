<?php

namespace Fredden\JavaScriptErrorReporting\ViewModel;

use Magento\Config\Model\ResourceModel\Config\Data\CollectionFactory;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class DisabledScopes implements ArgumentInterface
{
    public function __construct(
        private readonly CollectionFactory $configCollectionFactory,
    ) {
    }

    public function getDisabledScopes(): array
    {
        $collection = $this->configCollectionFactory->create();
        $collection->addFieldToFilter('path', ['eq' => 'system/fredden_javascript_error_reporting/enabled']);
        $collection->addFieldToFilter('value', ['eq' => '0']);

        $result = [];

        foreach ($collection as $configValue) {
            /** @var \Magento\Framework\App\Config\Value $configValue */
            $scope = rtrim($configValue->getScope(), 's');
            $scopeId = $configValue->getScopeId();

            $result[] = [$scope, $scopeId];
        }

        return $result;
    }
}

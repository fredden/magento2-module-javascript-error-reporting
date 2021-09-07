<?php
namespace Fredden\JavaScriptErrorReporting\Scope;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Config
{
    const XML_PATH_DAYS_TO_KEEP = 'system/fredden_javascript_error_reporting/days_to_keep';

    protected $scopeConfig;

    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    public function getDaysToKeep(): int
    {
        return (int) $this->scopeConfig->getValue(self::XML_PATH_DAYS_TO_KEEP);
    }
}

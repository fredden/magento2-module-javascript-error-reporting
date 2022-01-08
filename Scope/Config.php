<?php
namespace Fredden\JavaScriptErrorReporting\Scope;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\FlagManager;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class Config implements ArgumentInterface
{
    const XML_PATH_DAYS_TO_KEEP = 'system/fredden_javascript_error_reporting/days_to_keep';
    protected const FLAG_NAME = 'fredden_js_ignored_hashes';

    protected $flagManager;
    protected $scopeConfig;

    public function __construct(
        FlagManager $flagManager,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->flagManager = $flagManager;
        $this->scopeConfig = $scopeConfig;
    }

    public function getDaysToKeep(): int
    {
        return (int) $this->scopeConfig->getValue(self::XML_PATH_DAYS_TO_KEEP);
    }

    public function getIgnoredHashes(): array
    {
        $data = (string) $this->flagManager->getFlagData(self::FLAG_NAME);

        if ($data) {
            return explode(',', $data);
        }

        return [];
    }

    public function addHashToIgnore(string $hashToAdd): void
    {
        $data = (string) $this->flagManager->getFlagData(self::FLAG_NAME);

        if ($data) {
            $hashToAdd .= ',';
        }

        $this->flagManager->saveFlag(self::FLAG_NAME, $hashToAdd . $data);
    }

    public function removeHashFromIgnore(string $hashToRemove): void
    {
        $data = (string) $this->flagManager->getFlagData(self::FLAG_NAME);
        $data = str_replace(",$hashToRemove,", ',', ",$data,");
        $data = trim($data, ',');
        $this->flagManager->saveFlag(self::FLAG_NAME, $data);
    }

    public function resetHashIgnoreList(): void
    {
        $this->flagManager->deleteFlag(self::FLAG_NAME);
    }

    public function replaceIgnoreList(string $newList): void
    {
        $this->flagManager->saveFlag(self::FLAG_NAME, $newList);
    }
}

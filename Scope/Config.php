<?php

namespace Fredden\JavaScriptErrorReporting\Scope;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\FlagManager;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class Config implements ArgumentInterface
{
    const XML_PATH_DAYS_TO_KEEP = 'system/fredden_javascript_error_reporting/days_to_keep';
    protected const FLAG_NAME = 'fredden_js_ignored_hashes';
    private const XML_PATH_CHART_SHOW_HOURS = 'system/fredden_javascript_error_reporting/chart_show_hours';
    private const XML_PATH_CHART_SHOW_DAYS = 'system/fredden_javascript_error_reporting/chart_show_days';
    private const XML_PATH_CHART_SHOW_WEEKS = 'system/fredden_javascript_error_reporting/chart_show_weeks';

    public function __construct(
        private readonly FlagManager $flagManager,
        private readonly ScopeConfigInterface $scopeConfig,
    ) {
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

    public function getChartDisplayHowManyHours(): int
    {
        $value = (int) $this->scopeConfig->getValue(self::XML_PATH_CHART_SHOW_HOURS);
        $keep = $this->getDaysToKeep();

        return $keep ? min($value, $keep * 24) : $value;
    }

    public function getChartDisplayHowManyDays(): int
    {
        $value = (int) $this->scopeConfig->getValue(self::XML_PATH_CHART_SHOW_DAYS);
        $keep = $this->getDaysToKeep();

        return $keep ? min($value, $keep) : $value;
    }

    public function getChartDisplayHowManyWeeks(): int
    {
        $value = (int) $this->scopeConfig->getValue(self::XML_PATH_CHART_SHOW_WEEKS);
        $keep = $this->getDaysToKeep();

        return $keep ? min($value, ceil($keep / 7)) : $value;
    }
}

<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "FAQs" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace RemoteDevs\RdFaq\Hooks;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Localization\LanguageService;

class ItemsProcFunc
{
    /**
     * Itemsproc function to extend the selection of templateLayouts in the plugin
     */
    public function userTemplateLayout(array &$config): void
    {
        $pid = $config['flexParentDatabaseRow']['pid'] ?? 0;
        if ($pid === 0) {
            return;
        }
        $templateLayouts = $this->getTemplateLayoutsFromTsConfig($pid);
        foreach ($templateLayouts as $index => $layout) {
            $additionalLayout = [
                $this->getLanguageService()->sL($layout),
                $index,
            ];
            $config['items'][] = $additionalLayout;
        }
    }

    /**
     * Get template layouts defined in TsConfig
     */
    protected function getTemplateLayoutsFromTsConfig(int $pageUid): array
    {
        $templateLayouts = [];
        $pagesTsConfig = BackendUtility::getPagesTSconfig($pageUid);
        if (isset($pagesTsConfig['tx_rdfaq.']['templateLayouts.']) &&
            is_array($pagesTsConfig['tx_rdfaq.']['templateLayouts.'])
        ) {
            $templateLayouts = $pagesTsConfig['tx_rdfaq.']['templateLayouts.'];
        }

        return $templateLayouts;
    }

    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}

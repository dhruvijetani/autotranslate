<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "FAQs" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace RemoteDevs\RdFaq\EventListener;

use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\View\Event\PageContentPreviewRenderingEvent;
use TYPO3\CMS\Core\Attribute\AsEventListener;
use RemoteDevs\RdFaq\EventListener\AbstractPluginPreview;

final class FaqlistPluginContentPreview extends AbstractPluginPreview
{
    #[AsEventListener('rdfaq/faqlist-preview')]
    public function __invoke(PageContentPreviewRenderingEvent $event): void
    {
        if ($event->getTable() !== 'tt_content' ||
            ($event->getRecord()['CType'] ?? '') !== 'rdfaq_faqlist'
        ) {
            return;
        }

        $previewContent = $this->renderPreviewContent($event->getRecord());

        $event->setPreviewContent($previewContent);
    }

    private function renderPreviewContent(array $record): string
    {
        $data = [];
        $flexFormData = $this->getFlexFormData($record['pi_flexform']);
        $pluginName = $this->getPluginName($record);


        $this->setStoragePage($data, $flexFormData, 'settings.storagePage');

        $this->setOrderSettings($data, $flexFormData, 'settings.orderField', 'settings.orderDirection');
        $this->setOverrideDemandSettings($data, $flexFormData);

        $this->setCategoryConjuction($data, $flexFormData);
        $this->setCategorySettings($data, $flexFormData);

        $this->setPluginPaginationConfig($data, $flexFormData, 'settings.enablePagination', 'settings.displayItemsPerPage','settings.maxPagesNum','pagination');

        return $this->renderAsTable($data, $pluginName);
    }
}

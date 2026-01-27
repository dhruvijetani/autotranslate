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
/**
 * This file is part of the "FAQs" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * (c) 2025 Solanki Payal 
 *
 */
final class FaqdetailPluginContentPreview extends AbstractPluginPreview
{
    #[AsEventListener('rdfaq/faqdetail-preview')]
    public function __invoke(PageContentPreviewRenderingEvent $event): void
    {
        if ($event->getTable() !== 'tt_content' ||
            ($event->getRecord()['CType'] ?? '') !== 'rdfaq_faqdetail'
        ) {
            return;
        }

        $previewContent = $this->renderPreviewContent($event->getRecord());

        $event->setPreviewContent($previewContent);
    }
    private function renderPreviewContent(array $record, ServerRequestInterface $request): string
    {
        $data = [];
        $flexFormData = $this->getFlexFormData($record['pi_flexform']);
        $pluginName = $this->getPluginName($record);

        $this->setPluginPidConfig($data, $flexFormData, 'listpageid');

        return $this->renderAsTable($request, $data, $pluginName);
    }
}

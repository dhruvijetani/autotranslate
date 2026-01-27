<?php

declare(strict_types=1);

/*
 * This file is part of the Extension "FAQs" for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

namespace RemoteDevs\RdFaq\Event;

use RemoteDevs\RdFaq\Controller\FAQController;
use Psr\Http\Message\ServerRequestInterface;

/**
 * This event is triggered before the list view is rendered
 */
final class ModifyListViewVariablesEvent
{
    public function __construct(
        private readonly ServerRequestInterface $request,
        private array $variables,
        private readonly FAQController $faqController
    ) {
    }

    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }

    public function getVariables(): array
    {
        return $this->variables;
    }

    public function setVariables(array $variables): void
    {
        $this->variables = $variables;
    }

    public function getFaqController(): FAQController
    {
        return $this->faqController;
    }
}

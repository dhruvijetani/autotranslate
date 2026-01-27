<?php

declare(strict_types=1);

namespace RD\Autotranslate\Tests\Unit\Controller;

use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;
use TYPO3Fluid\Fluid\View\ViewInterface;

/**
 * Test case
 */
class AutotranslateControllerTest extends UnitTestCase
{
    /**
     * @var \RD\Autotranslate\Controller\AutotranslateController|MockObject|AccessibleObjectInterface
     */
    protected $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = $this->getMockBuilder($this->buildAccessibleProxy(\RD\Autotranslate\Controller\AutotranslateController::class))
            ->onlyMethods(['redirect', 'forward', 'addFlashMessage'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function listActionFetchesAllAutotranslatesFromRepositoryAndAssignsThemToView(): void
    {
        $allAutotranslates = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->disableOriginalConstructor()
            ->getMock();

        $autotranslateRepository = $this->getMockBuilder(\RD\Autotranslate\Domain\Repository\AutotranslateRepository::class)
            ->onlyMethods(['findAll'])
            ->disableOriginalConstructor()
            ->getMock();
        $autotranslateRepository->expects(self::once())->method('findAll')->will(self::returnValue($allAutotranslates));
        $this->subject->_set('autotranslateRepository', $autotranslateRepository);

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $view->expects(self::once())->method('assign')->with('autotranslates', $allAutotranslates);
        $this->subject->_set('view', $view);

        $this->subject->listAction();
    }

    /**
     * @test
     */
    public function showActionAssignsTheGivenAutotranslateToView(): void
    {
        $autotranslate = new \RD\Autotranslate\Domain\Model\Autotranslate();

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $this->subject->_set('view', $view);
        $view->expects(self::once())->method('assign')->with('autotranslate', $autotranslate);

        $this->subject->showAction($autotranslate);
    }

    /**
     * @test
     */
    public function createActionAddsTheGivenAutotranslateToAutotranslateRepository(): void
    {
        $autotranslate = new \RD\Autotranslate\Domain\Model\Autotranslate();

        $autotranslateRepository = $this->getMockBuilder(\RD\Autotranslate\Domain\Repository\AutotranslateRepository::class)
            ->onlyMethods(['add'])
            ->disableOriginalConstructor()
            ->getMock();

        $autotranslateRepository->expects(self::once())->method('add')->with($autotranslate);
        $this->subject->_set('autotranslateRepository', $autotranslateRepository);

        $this->subject->createAction($autotranslate);
    }

    /**
     * @test
     */
    public function editActionAssignsTheGivenAutotranslateToView(): void
    {
        $autotranslate = new \RD\Autotranslate\Domain\Model\Autotranslate();

        $view = $this->getMockBuilder(ViewInterface::class)->getMock();
        $this->subject->_set('view', $view);
        $view->expects(self::once())->method('assign')->with('autotranslate', $autotranslate);

        $this->subject->editAction($autotranslate);
    }

    /**
     * @test
     */
    public function updateActionUpdatesTheGivenAutotranslateInAutotranslateRepository(): void
    {
        $autotranslate = new \RD\Autotranslate\Domain\Model\Autotranslate();

        $autotranslateRepository = $this->getMockBuilder(\RD\Autotranslate\Domain\Repository\AutotranslateRepository::class)
            ->onlyMethods(['update'])
            ->disableOriginalConstructor()
            ->getMock();

        $autotranslateRepository->expects(self::once())->method('update')->with($autotranslate);
        $this->subject->_set('autotranslateRepository', $autotranslateRepository);

        $this->subject->updateAction($autotranslate);
    }

    /**
     * @test
     */
    public function deleteActionRemovesTheGivenAutotranslateFromAutotranslateRepository(): void
    {
        $autotranslate = new \RD\Autotranslate\Domain\Model\Autotranslate();

        $autotranslateRepository = $this->getMockBuilder(\RD\Autotranslate\Domain\Repository\AutotranslateRepository::class)
            ->onlyMethods(['remove'])
            ->disableOriginalConstructor()
            ->getMock();

        $autotranslateRepository->expects(self::once())->method('remove')->with($autotranslate);
        $this->subject->_set('autotranslateRepository', $autotranslateRepository);

        $this->subject->deleteAction($autotranslate);
    }
}

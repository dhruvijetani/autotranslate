<?php

declare(strict_types=1);

namespace RD\Autotranslate\Tests\Unit\Domain\Model;

use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\TestingFramework\Core\AccessibleObjectInterface;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

/**
 * Test case
 */
class AutotranslateTest extends UnitTestCase
{
    /**
     * @var \RD\Autotranslate\Domain\Model\Autotranslate|MockObject|AccessibleObjectInterface
     */
    protected $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = $this->getAccessibleMock(
            \RD\Autotranslate\Domain\Model\Autotranslate::class,
            ['dummy']
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getPageUidReturnsInitialValueForInt(): void
    {
        self::assertSame(
            0,
            $this->subject->getPageUid()
        );
    }

    /**
     * @test
     */
    public function setPageUidForIntSetsPageUid(): void
    {
        $this->subject->setPageUid(12);

        self::assertEquals(12, $this->subject->_get('pageUid'));
    }

    /**
     * @test
     */
    public function getSourceLangReturnsInitialValueForInt(): void
    {
        self::assertSame(
            0,
            $this->subject->getSourceLang()
        );
    }

    /**
     * @test
     */
    public function setSourceLangForIntSetsSourceLang(): void
    {
        $this->subject->setSourceLang(12);

        self::assertEquals(12, $this->subject->_get('sourceLang'));
    }

    /**
     * @test
     */
    public function getTargetLangReturnsInitialValueForInt(): void
    {
        self::assertSame(
            0,
            $this->subject->getTargetLang()
        );
    }

    /**
     * @test
     */
    public function setTargetLangForIntSetsTargetLang(): void
    {
        $this->subject->setTargetLang(12);

        self::assertEquals(12, $this->subject->_get('targetLang'));
    }

    /**
     * @test
     */
    public function getRecordsTranslatedReturnsInitialValueForInt(): void
    {
        self::assertSame(
            0,
            $this->subject->getRecordsTranslated()
        );
    }

    /**
     * @test
     */
    public function setRecordsTranslatedForIntSetsRecordsTranslated(): void
    {
        $this->subject->setRecordsTranslated(12);

        self::assertEquals(12, $this->subject->_get('recordsTranslated'));
    }

    /**
     * @test
     */
    public function getStatusReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getStatus()
        );
    }

    /**
     * @test
     */
    public function setStatusForStringSetsStatus(): void
    {
        $this->subject->setStatus('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('status'));
    }

    /**
     * @test
     */
    public function getMessageReturnsInitialValueForString(): void
    {
        self::assertSame(
            '',
            $this->subject->getMessage()
        );
    }

    /**
     * @test
     */
    public function setMessageForStringSetsMessage(): void
    {
        $this->subject->setMessage('Conceived at T3CON10');

        self::assertEquals('Conceived at T3CON10', $this->subject->_get('message'));
    }
}

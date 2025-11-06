<?php

namespace MediaWiki\Tests\Unit\Language;

use MediaWiki\Context\IContextSource;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Language\FormatterFactory;
use MediaWiki\Language\MessageParser;
use MediaWiki\Languages\LanguageFactory;
use MediaWiki\Title\TitleFormatter;
use MediaWiki\User\UserIdentityUtils;
use MediaWikiUnitTestCase;
use MessageLocalizer;
use Psr\Log\NullLogger;

/**
 * @covers \MediaWiki\Language\FormatterFactory
 */
class FormatterFactoryTest extends MediaWikiUnitTestCase {

	private function getFactory() {
		return new FormatterFactory(
			$this->createNoOpMock( MessageParser::class ),
			$this->createNoOpMock( TitleFormatter::class ),
			$this->createNoOpMock( HookContainer::class ),
			$this->createNoOpMock( UserIdentityUtils::class ),
			$this->createNoOpMock( LanguageFactory::class ),
			new NullLogger()
		);
	}

	public function testGetStatusFormatter() {
		$factory = $this->getFactory();
		$factory->getStatusFormatter(
			$this->createNoOpMock( MessageLocalizer::class )
		);

		// Just make sure the getter works.
		// This protects against constructor signature changes.
		$this->addToAssertionCount( 1 );
	}

	public function testGetBlockErrorFormatter() {
		$factory = $this->getFactory();
		$factory->getBlockErrorFormatter(
			$this->createNoOpMock( IContextSource::class )
		);

		// Just make sure the getter works.
		// This protects against constructor signature changes.
		$this->addToAssertionCount( 1 );
	}
}

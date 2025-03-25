<?php

use MediaWiki\Block\AbstractBlock;
use MediaWiki\Block\BlockErrorFormatter;
use MediaWiki\Context\RequestContext;
use MediaWiki\Exception\UserBlockedError;
use MediaWiki\Language\FormatterFactory;
use MediaWiki\Language\Language;
use MediaWiki\Language\RawMessage;
use MediaWiki\User\UserIdentity;

/**
 * @covers \MediaWiki\Exception\UserBlockedError
 */
class UserBlockedErrorTest extends MediaWikiIntegrationTestCase {

	private function setUpMockBlockFormatter(
		$expectedBlock, $expectedUser, $expectedLanguage, $expectedIp, $returnMessages
	) {
		$mockBlockErrorFormatter = $this->createMock( BlockErrorFormatter::class );
		$mockBlockErrorFormatter->expects( $this->once() )
			->method( 'getMessages' )
			->with( $expectedBlock, $expectedUser, $expectedIp )
			->willReturn( $returnMessages );

		$formatterFactory = $this->createNoOpMock( FormatterFactory::class, [ 'getBlockErrorFormatter' ] );
		$formatterFactory->method( 'getBlockErrorFormatter' )->willReturn( $mockBlockErrorFormatter );

		$this->setService( 'FormatterFactory', $formatterFactory );
	}

	public function testConstructionProvidedOnlyBlockParameter() {
		$context = RequestContext::getMain();
		$block = $this->createMock( AbstractBlock::class );
		$this->setUpMockBlockFormatter(
			$block, $context->getUser(), $context->getLanguage(), $context->getRequest()->getIP(),
			[ new RawMessage( 'testing' ) ]
		);
		$e = new UserBlockedError( $block );
		$this->assertSame(
			( new RawMessage( 'testing' ) )->text(),
			$e->getMessageObject()->text(),
			'UserBlockedError did not use the expected message.'
		);
	}

	public function testConstructionProvidedAllParametersWithMultipleBlockMessages() {
		$mockUser = $this->createMock( UserIdentity::class );
		$mockLanguage = $this->createMock( Language::class );
		$block = $this->createMock( AbstractBlock::class );
		$this->setUpMockBlockFormatter(
			$block, $mockUser, $mockLanguage, '1.2.3.4',
			[ new RawMessage( 'testing' ), new RawMessage( 'testing2' ) ]
		);
		$e = new UserBlockedError( $block, $mockUser, $mockLanguage, '1.2.3.4' );
		$this->assertSame(
			"* testing\n* testing2",
			$e->getMessageObject()->text(),
			'UserBlockedError did not use the expected message.'
		);
	}
}

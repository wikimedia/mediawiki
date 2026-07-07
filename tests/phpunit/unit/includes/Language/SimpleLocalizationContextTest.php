<?php

declare( strict_types = 1 );

namespace MediaWiki\Tests\Unit\Language;

use MediaWiki\Language\MessageLocalizer;
use MediaWiki\Language\SimpleLocalizationContext;
use MediaWiki\Message\Message;
use MediaWikiUnitTestCase;
use Wikimedia\Bcp47Code\Bcp47Code;

/**
 * @covers \MediaWiki\Language\SimpleLocalizationContext
 * @group Language
 */
class SimpleLocalizationContextTest extends MediaWikiUnitTestCase {

	public function testUsesProvidedInstancesFromConstructor(): void {
		$mockMessage = $this->createMock( Message::class );
		$messageLocalizer = $this->createMock( MessageLocalizer::class );
		$messageLocalizer->method( 'msg' )
			->with( 'key', 'param' )
			->willReturn( $mockMessage );
		$bcp47Code = $this->createMock( Bcp47Code::class );

		$simpleLocalizationContext = new SimpleLocalizationContext( $messageLocalizer, $bcp47Code );

		$this->assertSame( $bcp47Code, $simpleLocalizationContext->getLanguageCode() );
		$this->assertSame( $mockMessage, $simpleLocalizationContext->msg( 'key', 'param' ) );
	}
}

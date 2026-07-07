<?php

namespace MediaWiki\Tests\Unit\ChangeTags;

use MediaWiki\ChangeTags\ChangeTagsFormatter;
use MediaWiki\ChangeTags\ChangeTagsStore;
use MediaWiki\Config\HashConfig;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\Context\IContextSource;
use MediaWiki\Language\LanguageFactory;
use MediaWiki\Language\MessageLocalizer;
use MediaWiki\MainConfigNames;
use MediaWiki\Message\Message;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWikiUnitTestCase;
use Wikimedia\ObjectCache\WANObjectCache;

/**
 * @covers \MediaWiki\ChangeTags\ChangeTagsFormatter
 */
class ChangeTagsFormatterTest extends MediaWikiUnitTestCase {

	use MockAuthorityTrait;

	private function getObjectUnderTest(
		ChangeTagsStore $changeTagsStore,
		array $options = []
	): ChangeTagsFormatter {
		return new ChangeTagsFormatter(
			new ServiceOptions(
				ChangeTagsFormatter::CONSTRUCTOR_OPTIONS,
				new HashConfig( $options + [ MainConfigNames::UseTagFilter => true ] )
			),
			$changeTagsStore,
			$this->createMock( WANObjectCache::class ),
			$this->createMock( LanguageFactory::class )
		);
	}

	/** @dataProvider provideFormatTagsAsSummaryListForNoTags */
	public function testFormatTagsAsSummaryListForNoTags( ?string $tags ): void {
		$mockChangeTagsStore = $this->createMock( ChangeTagsStore::class );
		$mockChangeTagsStore->method( 'listDefinedTags' )
			->willReturn( [ 'mw-reverted' ] );

		$this->assertSame(
			[ '', [] ],
			$this->getObjectUnderTest( $mockChangeTagsStore )->formatTagsAsSummaryList(
				$tags,
				$this->createMock( MessageLocalizer::class ),
				$this->mockRegisteredUltimateAuthority()
			)
		);
	}

	public static function provideFormatTagsAsSummaryListForNoTags(): array {
		return [
			'Tags argument is null' => [ null ],
			'Tags argument is empty string' => [ '' ],
			'Tags argument is a single comma' => [ ',' ],
		];
	}

	public function testFormatTagsAsSummaryListForMissingShortDescriptionMessage(): void {
		$mockMessage = $this->createMock( Message::class );
		$mockMessage->method( 'isDisabled' )
			->willReturn( true );
		$mockMessage->method( 'exists' )
			->willReturn( false );
		$mockMessage->method( 'inContentLanguage' )
			->willReturnSelf();

		$localizer = $this->createMock( MessageLocalizer::class );
		$localizer->method( 'msg' )
			->willReturnCallback( static fn () => $mockMessage );

		$mockChangeTagsStore = $this->createMock( ChangeTagsStore::class );
		$mockChangeTagsStore->method( 'listDefinedTags' )
			->willReturn( [ 'test' ] );
		$mockChangeTagsStore->method( 'filterViewableTags' )
			->willReturnArgument( 0 );

		$this->assertSame(
			[
				'',
				[ 'mw-tag-test' ]
			],
			$this->getObjectUnderTest( $mockChangeTagsStore )->formatTagsAsSummaryList(
				'test',
				$localizer,
				$this->mockRegisteredUltimateAuthority()
			)
		);
	}

	public function testBuildTagFilterWhenFilterConfigDisabled(): void {
		$objectUnderTest = $this->getObjectUnderTest(
			$this->createMock( ChangeTagsStore::class ),
			[ MainConfigNames::UseTagFilter => false ]
		);
		$this->assertNull( $objectUnderTest->buildTagFilter(
			'test',
			true,
			$this->createMock( IContextSource::class ) )
		);
	}

	public function testBuildTagFilterWhenNoDefinedTags(): void {
		$mockChangeTagsStore = $this->createMock( ChangeTagsStore::class );
		$mockChangeTagsStore->method( 'listDefinedTags' )
			->willReturn( [] );

		$objectUnderTest = $this->getObjectUnderTest( $mockChangeTagsStore );
		$this->assertNull( $objectUnderTest->buildTagFilter(
			'test',
			true,
			$this->createMock( IContextSource::class ) )
		);
	}
}

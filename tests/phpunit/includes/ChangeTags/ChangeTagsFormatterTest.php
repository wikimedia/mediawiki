<?php

namespace MediaWiki\Tests\ChangeTags;

use MediaWiki\ChangeTags\ChangeTagsStore;
use MediaWiki\Context\DerivativeContext;
use MediaWiki\Context\RequestContext;
use MediaWiki\Language\MessageLocalizer;
use MediaWiki\Language\RawMessage;
use MediaWiki\Language\SimpleLocalizationContext;
use MediaWiki\Message\Message;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWikiIntegrationTestCase;
use MockMessageLocalizer;
use OOUI\BlankTheme;
use OOUI\Theme;

/**
 * @covers \MediaWiki\ChangeTags\ChangeTagsFormatter
 * @group Database
 */
class ChangeTagsFormatterTest extends MediaWikiIntegrationTestCase {

	use MockAuthorityTrait;
	use RestrictedTagTestTrait;

	/** @dataProvider provideFormatTagsAsSummaryList */
	public function testFormatTagsAsSummaryList( string $tags, array $authorityRights, array $expected ): void {
		$this->setRestrictedTags( [ 'mw-private-test' => 'patrol' ] );

		$qqx = new MockMessageLocalizer();
		$localizer = $this->createMock( MessageLocalizer::class );
		$localizer->method( 'msg' )
			->willReturnCallback( static function ( $key, ...$params ) use ( $qqx ) {
				if ( $key === 'tag-hidden-tag' ) {
					return new RawMessage( '-' );
				}
				if ( $key === 'tag-tag-with-help-helppage' ) {
					return new RawMessage( 'https://example.com/wiki/Test' );
				}
				return $qqx->msg( $key, ...$params );
			} );

		$this->assertSame(
			$expected,
			$this->getServiceContainer()->getChangeTagsFormatter()->formatTagsAsSummaryList(
				$tags,
				$localizer,
				$this->mockRegisteredAuthorityWithPermissions( $authorityRights )
			)
		);
	}

	public static function provideFormatTagsAsSummaryList(): array {
		return [
			'Valid single tag' => [
				'tags' => 'tag1',
				'authorityRights' => [],
				'expected' => [
					'<span class="mw-tag-markers">(tag-list-wrapper: 1, '
					. '<span class="mw-tag-marker mw-tag-marker-tag1">(tag-tag1)</span>'
					. ')</span>',
					[ 'mw-tag-tag1' ]
				],
			],
			'"0" tag' => [
				'tags' => '0',
				'authorityRights' => [],
				'expected' => [
					'<span class="mw-tag-markers">(tag-list-wrapper: 1, '
					. '<span class="mw-tag-marker mw-tag-marker-0">(tag-0)</span>'
					. ')</span>',
					[ 'mw-tag-0' ]
				],
			],
			'Single hidden tag' => [
				'tags' => 'hidden-tag',
				'authorityRights' => [],
				'expected' => [
					'',
					[ 'mw-tag-hidden-tag' ]
				],
			],
			'Multiple tags' => [
				'tags' => 'tag1,0,,hidden-tag',
				'authorityRights' => [],
				'expected' => [
					'<span class="mw-tag-markers">(tag-list-wrapper: 2, '
					. '<span class="mw-tag-marker mw-tag-marker-tag1">(tag-tag1)</span>'
					. ' <span class="mw-tag-marker mw-tag-marker-0">(tag-0)</span>'
					. ')</span>',
					[ 'mw-tag-tag1', 'mw-tag-0', 'mw-tag-hidden-tag' ]
				],
			],
			'Defined tags appear first' => [
				'tags' => 'tag1,hidden-tag,mw-ipblock-appeal,mw-reverted',
				'authorityRights' => [],
				'expected' => [
					'<span class="mw-tag-markers">(tag-list-wrapper: 3, '
					. '<span class="mw-tag-marker mw-tag-marker-mw-reverted">(tag-mw-reverted)</span>'
					. ' <span class="mw-tag-marker mw-tag-marker-mw-ipblock-appeal">(tag-mw-ipblock-appeal)</span>'
					. ' <span class="mw-tag-marker mw-tag-marker-tag1">(tag-tag1)</span>'
					. ')</span>',
					[ 'mw-tag-mw-reverted', 'mw-tag-mw-ipblock-appeal', 'mw-tag-tag1', 'mw-tag-hidden-tag' ]
				],
			],
			'One tag has a help page' => [
				'tags' => 'tag1,tag-with-help',
				'authorityRights' => [],
				'expected' => [
					'<span class="mw-tag-markers">(tag-list-wrapper: 2, '
					. '<span class="mw-tag-marker mw-tag-marker-tag1">(tag-tag1)</span>'
					. ' <span class="mw-tag-marker mw-tag-marker-tag-with-help">' .
					'<a href="https://example.com/wiki/Test">(tag-tag-with-help)</a></span>)</span>',
					[ 'mw-tag-tag1', 'mw-tag-tag-with-help' ]
				],
			],
			'Private tags are hidden from users without the needed right' => [
				'tags' => 'mw-private-test,tag1',
				'authorityRights' => [],
				'expected' => [
					'<span class="mw-tag-markers">(tag-list-wrapper: 1, '
					. '<span class="mw-tag-marker mw-tag-marker-tag1">(tag-tag1)</span>'
					. ')</span>',
					[ 'mw-tag-tag1' ]
				],
			],
			'Private tags are shown to users with the needed right' => [
				'tags' => 'mw-private-test,tag1',
				'authorityRights' => [ 'patrol' ],
				'expected' => [
					'<span class="mw-tag-markers">(tag-list-wrapper: 2, '
					. '<span class="mw-tag-marker mw-tag-marker-mw-private-test">(tag-mw-private-test)</span>'
					. ' <span class="mw-tag-marker mw-tag-marker-tag1">(tag-tag1)</span>'
					. ')</span>',
					[ 'mw-tag-mw-private-test', 'mw-tag-tag1' ]
				],
			],
		];
	}

	/** @dataProvider provideGetChangeTagListSummary */
	public function testGetChangeTagListSummary(
		bool $activeOnly,
		bool $useAllTags,
		array $expectedTags
	): void {
		$mockChangeTagsStore = $this->createMock( ChangeTagsStore::class );
		$mockChangeTagsStore->method( 'listDefinedTags' )
			->willReturn( [ 'mw-reverted', 'mw-test', 'mw-testing', 'mw-new-redirect' ] );
		$mockChangeTagsStore->method( 'getCoreDefinedTags' )
			->willReturn( [ 'mw-reverted', 'mw-new-redirect' ] );
		$mockChangeTagsStore->method( 'tagUsageStatistics' )
			->willReturn( [ 'mw-reverted' => 1, 'mw-test' => 2 ] );
		$this->setService( 'ChangeTagsStore', $mockChangeTagsStore );

		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setLanguage( 'qqx' );
		$actualReturnValue = $this->getServiceContainer()->getChangeTagsFormatter()->getChangeTagListSummary(
			$context,
			$activeOnly,
			$useAllTags
		);

		$this->assertArrayEquals(
			array_map( static fn ( $tag ) => [
				'name' => $tag,
				'labelMsg' => true,
				'label' => "(tag-$tag)",
				'descriptionMsg' => true,
				'description' => "(tag-$tag-description)",
				'helpLink' => null,
				'cssClass' => "mw-tag-$tag"
			], $expectedTags ),
			$actualReturnValue
		);
	}

	public static function provideGetChangeTagListSummary(): array {
		return [
			'All tags including inactive' => [
				'activeOnly' => false,
				'useAllTags' => true,
				'expectedTags' => [ 'mw-reverted', 'mw-test', 'mw-testing', 'mw-new-redirect' ],
			],
			'All active tags' => [
				'activeOnly' => true,
				'useAllTags' => true,
				'expectedTags' => [ 'mw-reverted', 'mw-test' ],
			],
			'Only core defined tags' => [
				'activeOnly' => false,
				'useAllTags' => false,
				'expectedTags' => [ 'mw-reverted', 'mw-new-redirect' ],
			],
			'Only active core defined tags' => [
				'activeOnly' => true,
				'useAllTags' => false,
				'expectedTags' => [ 'mw-reverted' ],
			],
		];
	}

	public function testBuildTagFilterForActiveOnlyWhenNoHits(): void {
		Theme::setSingleton( new BlankTheme() );

		$this->assertSame(
			[
				'<label for="tagfilter"><a href="/wiki/Special:Tags" title="Special:Tags">Tag</a> filter:</label>',
				'<input class="mw-tagfilter-input" size="20" id="tagfilter" list="tagfilter-datalist" name="tagfilter"><datalist id="tagfilter-datalist"></datalist>'
			],
			$this->getServiceContainer()->getChangeTagsFormatter()->buildTagFilter(
				'', false, RequestContext::getMain()
			)
		);

		$oouiActualTagFilter = $this->getServiceContainer()->getChangeTagsFormatter()->buildTagFilter(
			'', true, RequestContext::getMain()
		);
		$this->assertCount( 2, $oouiActualTagFilter );
		$this->assertSame(
			'<label for="tagfilter"><a href="/wiki/Special:Tags" title="Special:Tags">Tag</a> filter:</label>',
			$oouiActualTagFilter[0]
		);
		$this->assertStringContainsString( 'tagfilter', $oouiActualTagFilter[1] );
		$this->assertStringNotContainsString(
			'option',
			$oouiActualTagFilter[1],
			'No options should have been present in the OOUI dropdown filter'
		);
	}

	public function testBuildTagFilterForActiveWhenOneTagHasHits(): void {
		$this->setTemporaryHook(
			'ListDefinedTags',
			static function ( array &$tags ) {
				$tags[] = 'mw-test';
			}
		);

		$editStatus = $this->editPage( $this->getNonexistingTestPage(), 'test' );
		$this->assertStatusGood( $editStatus );

		$rcId = null;
		$revId = $editStatus->getNewRevision()->getId();
		$this->getServiceContainer()->getChangeTagsStore()->updateTags(
			[ 'mw-reverted', 'mw-test' ],
			[],
			$rcId,
			$revId
		);

		$this->assertEquals(
			[
				'<label for="tagfilter"><a href="/wiki/Special:Tags" title="Special:Tags">Tag</a> filter:</label>',
				'<input class="mw-tagfilter-input" size="20" id="tagfilter" list="tagfilter-datalist" name="tagfilter"><datalist id="tagfilter-datalist"><option value="mw-test">mw-test</option><option value="mw-reverted">Reverted</option></datalist>'
			],
			$this->getServiceContainer()->getChangeTagsFormatter()->buildTagFilter(
				'', false, RequestContext::getMain()
			),
			'Tag filter HTML was not as expected'
		);
	}

	public function testGetChangeTagListWithLabels(): void {
		$mockChangeTagsStore = $this->createMock( ChangeTagsStore::class );
		$mockChangeTagsStore->method( 'listDefinedTags' )
			->willReturn( [ 'hidden-tag', 'wikitext-tag' ] );
		$this->setService( 'ChangeTagsStore', $mockChangeTagsStore );

		// To avoid using database messages, mock the MessageLocalizer to return controllable
		// messages for both defined tags
		$localizer = $this->createMock( MessageLocalizer::class );
		$localizer->method( 'msg' )
			->willReturnCallback( function ( $key ) {
				if ( $key === 'tag-hidden-tag' ) {
					return new RawMessage( '-' );
				} elseif ( $key === 'tag-hidden' ) {
					return new RawMessage( 'Tag hidden' );
				} elseif ( $key === 'tag-wikitext-tag' ) {
					return new RawMessage( '[[Test|Testing]]' );
				} elseif ( $key === 'tag-hidden-tag-description' ) {
					return new RawMessage( '-' );
				} elseif ( $key === 'tag-wikitext-tag-description' ) {
					return new RawMessage( '[[Test|Testing]]' . str_repeat( 'abc', 75 ) );
				} elseif ( $key === 'tag-hidden-tag-helppage' || $key === 'tag-wikitext-tag-helppage' ) {
					return new RawMessage( '-' );
				} elseif ( $key instanceof Message ) {
					return $key;
				} else {
					$this->fail( "Unhandled message key $key" );
				}
			} );

		$this->assertSame(
			[
				[
					'name' => 'hidden-tag',
					'label' => 'Tag hidden',
					'description' => '',
					'helpLink' => null,
					'cssClass' => 'mw-tag-hidden-tag',
				],
				[
					'name' => 'wikitext-tag',
					'label' => 'Testing',
					// This should be the description when truncated to 120 chars
					'description' => 'Testing' . str_repeat( 'abc', 36 ) . 'ab...',
					'helpLink' => null,
					'cssClass' => 'mw-tag-wikitext-tag',
				],
			],
			$this->getServiceContainer()->getChangeTagsFormatter()->getChangeTagList(
				new SimpleLocalizationContext( $localizer, RequestContext::getMain()->getLanguage() ),
				false
			),
			'Change tag list was not as expected'
		);
	}
}

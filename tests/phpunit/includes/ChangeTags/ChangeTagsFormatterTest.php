<?php

namespace MediaWiki\Tests\ChangeTags;

use MediaWiki\Language\MessageLocalizer;
use MediaWiki\Language\RawMessage;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWikiIntegrationTestCase;
use MockMessageLocalizer;

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
}

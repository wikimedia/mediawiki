<?php

namespace MediaWiki\Tests\Unit\ChangeTags;

use MediaWiki\ChangeTags\ChangeTags;
use MediaWiki\Tests\Unit\Permissions\MockAuthorityTrait;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\ChangeTags\ChangeTags
 */
class ChangeTagsTest extends MediaWikiUnitTestCase {

	use MockAuthorityTrait;

	public function testUpdateTagsWithChecksForNoChange(): void {
		$actualStatus = ChangeTags::updateTagsWithChecks(
			[],
			[],
			null,
			null,
			null,
			null,
			'',
			$this->mockRegisteredAuthorityWithPermissions( [] )
		);

		$this->assertStatusGood( $actualStatus );
		$this->assertArrayEquals(
			[
				'logId' => null,
				'addedTags' => [],
				'removedTags' => [],
			],
			(array)$actualStatus->getValue(),
			false,
			true
		);
	}

	public function testUpdateTagsWithChecksForPermissionError(): void {
		$actualStatus = ChangeTags::updateTagsWithChecks(
			[ 'test' ],
			[],
			null,
			null,
			null,
			null,
			'',
			$this->mockRegisteredNullAuthority()
		);

		$this->assertStatusError( 'tags-update-no-permission', $actualStatus );
		$this->assertNull( $actualStatus->getValue() );
	}

}

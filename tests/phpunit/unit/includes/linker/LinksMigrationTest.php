<?php

use MediaWiki\Config\HashConfig;
use MediaWiki\Linker\LinksMigration;
use MediaWiki\Linker\LinkTargetLookup;
use MediaWiki\MainConfigNames;
use MediaWiki\Title\TitleValue;

/**
 * @covers \MediaWiki\Linker\LinksMigration
 */
class LinksMigrationTest extends MediaWikiUnitTestCase {

	public static function provideReadNew() {
		yield [ SCHEMA_COMPAT_READ_NEW ];
		yield [ SCHEMA_COMPAT_NEW ];
	}

	/**
	 * @dataProvider provideReadNew
	 * @covers \MediaWiki\Linker\LinksMigration::getLinksConditions
	 */
	public function testGetLinksConditionsReadNew( $configValue ) {
		$title = new TitleValue( NS_USER, 'Someuser' );
		$linkTargetStore = $this->createMock( LinkTargetLookup::class );
		$linkTargetStore->method( 'getLinkTargetId' )
			->with( $title )
			->willReturn( 1 );
		$linkTargetStore->expects( $this->never() )->method( 'acquireLinkTargetId' );

		$config = new HashConfig(
			[
				MainConfigNames::PageLinksSchemaMigrationStage => $configValue
			]
		);
		$linksMigration = new LinksMigration( $config, $linkTargetStore );
		$this->assertSame(
			[ 'pl_target_id' => 1 ],
			$linksMigration->getLinksConditions( 'pagelinks', $title )
		);
	}

	public static function provideReadOld() {
		yield [ SCHEMA_COMPAT_READ_OLD ];
	}

	/**
	 * @dataProvider provideReadOld
	 * @covers \MediaWiki\Linker\LinksMigration::getLinksConditions
	 */
	public function testGetLinksConditionsReadOld( $configValue ) {
		$this->markTestSkipped( 'No table currently with support for read old' );
		$title = new TitleValue( NS_USER, 'Someuser' );
		$linkTargetStore = $this->createMock( LinkTargetLookup::class );
		$linkTargetStore->expects( $this->never() )->method( 'getLinkTargetId' );
		$linkTargetStore->expects( $this->never() )->method( 'acquireLinkTargetId' );

		$config = new HashConfig(
			[
				MainConfigNames::PageLinksSchemaMigrationStage => $configValue
			]
		);
		$linksMigration = new LinksMigration( $config, $linkTargetStore );
		$this->assertSame(
			[ 'pl_namespace' => 2, 'pl_title' => 'Someuser' ],
			$linksMigration->getLinksConditions( 'pagelinks', $title )
		);
	}

}

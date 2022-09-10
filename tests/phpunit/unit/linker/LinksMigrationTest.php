<?php

use MediaWiki\Linker\LinksMigration;
use MediaWiki\Linker\LinkTargetLookup;
use MediaWiki\MainConfigNames;

/**
 * @covers MediaWiki\Linker\LinksMigration
 */
class LinksMigrationTest extends MediaWikiUnitTestCase {

	public function provideReadNew() {
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
				MainConfigNames::TemplateLinksSchemaMigrationStage => $configValue
			]
		);
		$linksMigration = new LinksMigration( $config, $linkTargetStore );
		$this->assertSame(
			[ 'tl_target_id' => 1 ],
			$linksMigration->getLinksConditions( 'templatelinks', $title )
		);
	}

	public function provideReadOld() {
		yield [ SCHEMA_COMPAT_READ_OLD ];
	}

	/**
	 * @dataProvider provideReadOld
	 * @covers \MediaWiki\Linker\LinksMigration::getLinksConditions
	 */
	public function testGetLinksConditionsReadOld( $configValue ) {
		$title = new TitleValue( NS_USER, 'Someuser' );
		$linkTargetStore = $this->createMock( LinkTargetLookup::class );
		$linkTargetStore->expects( $this->never() )->method( 'getLinkTargetId' );
		$linkTargetStore->expects( $this->never() )->method( 'acquireLinkTargetId' );

		$config = new HashConfig(
			[
				MainConfigNames::TemplateLinksSchemaMigrationStage => $configValue
			]
		);
		$linksMigration = new LinksMigration( $config, $linkTargetStore );
		$this->assertSame(
			[ 'tl_namespace' => 2, 'tl_title' => 'Someuser' ],
			$linksMigration->getLinksConditions( 'templatelinks', $title )
		);
	}

}

<?php

use MediaWiki\Config\HashConfig;
use MediaWiki\Linker\LinksMigration;
use MediaWiki\Linker\LinkTargetLookup;
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
		$this->markTestSkipped( 'There is currently no xxxlinks table which has a migration config.' );

		$title = new TitleValue( NS_CATEGORY, 'Somecategory' );
		$linkTargetStore = $this->createMock( LinkTargetLookup::class );
		$linkTargetStore->method( 'getLinkTargetId' )
			->with( $title )
			->willReturn( 1 );
		$linkTargetStore->expects( $this->never() )->method( 'acquireLinkTargetId' );

		$config = new HashConfig(
			[
				'SchemaMigrationStage' => $configValue
			]
		);
		$linksMigration = new LinksMigration( $config, $linkTargetStore );
		$this->assertSame(
			[ 'cl_target_id' => 1 ],
			$linksMigration->getLinksConditions( 'categorylinks', $title )
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
		$this->markTestSkipped( 'There is currently no xxxlinks table which has a migration config.' );

		$title = new TitleValue( NS_CATEGORY, 'Somecategory' );
		$linkTargetStore = $this->createMock( LinkTargetLookup::class );
		$linkTargetStore->expects( $this->never() )->method( 'getLinkTargetId' );
		$linkTargetStore->expects( $this->never() )->method( 'acquireLinkTargetId' );

		$config = new HashConfig(
			[
				'SchemaMigrationStage' => $configValue
			]
		);
		$linksMigration = new LinksMigration( $config, $linkTargetStore );
		$this->assertSame(
			[ 14 => NS_CATEGORY, 'cl_to' => 'Somecategory' ],
			$linksMigration->getLinksConditions( 'categorylinks', $title )
		);
	}

}

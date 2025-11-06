<?php

namespace MediaWiki\Tests\Unit\Actions;

use MediaWiki\Actions\ActionInfo;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\Actions\ActionInfo
 *
 * @author Daniel Kinzler
 */
class ActionInfoTest extends MediaWikiUnitTestCase {

	public static function provideSpec() {
		yield 'true values' => [ [
			'name' => 'testing',
			'restriction' => 'foo',
			'requiresUnblock' => true,
			'requiresWrite' => true,
			'needsReadRights' => true,
		] ];

		yield 'false values' => [ [
			'name' => 'testing',
			'restriction' => null,
			'requiresUnblock' => false,
			'requiresWrite' => false,
			'needsReadRights' => false,
		] ];
	}

	/**
	 * @dataProvider provideSpec
	 */
	public function testGetters( array $spec ) {
		$info = new ActionInfo( $spec );

		$this->assertSame( $spec['name'], $info->getName() );
		$this->assertSame( $spec['restriction'], $info->getRestriction() );
		$this->assertSame( $spec['requiresUnblock'], $info->requiresUnblock() );
		$this->assertSame( $spec['requiresWrite'], $info->requiresWrite() );
		$this->assertSame( $spec['needsReadRights'], $info->needsReadRights() );
	}

}

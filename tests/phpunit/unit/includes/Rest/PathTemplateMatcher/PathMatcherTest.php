<?php

namespace MediaWiki\Tests\Rest\PathTemplateMatcher;

use MediaWiki\Rest\PathTemplateMatcher\PathConflict;
use MediaWiki\Rest\PathTemplateMatcher\PathMatcher;

/**
 * @covers \MediaWiki\Rest\PathTemplateMatcher\PathMatcher
 * @covers \MediaWiki\Rest\PathTemplateMatcher\PathConflict
 */
class PathMatcherTest extends \MediaWikiUnitTestCase {
	private static $normalRoutes = [
		'/a/b',
		'/b/{x}',
		'/c/{x}/d',
		'/c/{x}/e',
		'/c/{x}/{y}/d',
	];

	public static function provideConflictingRoutes() {
		return [
			[ '/a/b', 0, '/a/b' ],
			[ '/a/{x}', 0, '/a/b' ],
			[ '/{x}/c', 1, '/b/{x}' ],
			[ '/b/a', 1, '/b/{x}' ],
			[ '/b/{x}', 1, '/b/{x}' ],
			[ '/{x}/{y}/d', 2, '/c/{x}/d' ],
		];
	}

	public static function provideMatch() {
		return [
			[ '', false ],
			[ '/a/b', [ 'params' => [], 'userData' => 0 ] ],
			[ '/b', false ],
			[ '/b/1', [ 'params' => [ 'x' => '1' ], 'userData' => 1 ] ],
			[ '/c/1/d', [ 'params' => [ 'x' => '1' ], 'userData' => 2 ] ],
			[ '/c/1/e', [ 'params' => [ 'x' => '1' ], 'userData' => 3 ] ],
			[ '/c/000/e', [ 'params' => [ 'x' => '000' ], 'userData' => 3 ] ],
			[ '/c/1/f', false ],
			[ '/c//e', [ 'params' => [ 'x' => '' ], 'userData' => 3 ] ],
			[ '/c///e', false ],
		];
	}

	public function createNormalRouter() {
		$pm = new PathMatcher;
		foreach ( self::$normalRoutes as $i => $route ) {
			$pm->add( $route, $i );
		}
		return $pm;
	}

	/** @dataProvider provideConflictingRoutes */
	public function testAddConflict( $attempt, $expectedUserData, $expectedTemplate ) {
		$pm = $this->createNormalRouter();
		$actualTemplate = null;
		$actualUserData = null;
		try {
			$pm->add( $attempt, 'conflict' );
		} catch ( PathConflict $pc ) {
			$actualTemplate = $pc->existingTemplate;
			$actualUserData = $pc->existingUserData;
		}
		$this->assertSame( $expectedUserData, $actualUserData );
		$this->assertSame( $expectedTemplate, $actualTemplate );
	}

	/** @dataProvider provideMatch */
	public function testMatch( $path, $expectedResult ) {
		$pm = $this->createNormalRouter();
		$result = $pm->match( $path );
		$this->assertSame( $expectedResult, $result );
	}
}

<?php
/**
 * @covers MediaWiki\Interwiki\ClassicInterwikiLookup
 *
 * @group MediaWiki
 * @group Database
 */

use MediaWiki\Interwiki\ClassicInterwikiLookup;
use MediaWiki\Interwiki\CdbInterwikiLookup;
use MediaWiki\Interwiki\HashInterwikiLookup;
use MediaWiki\Interwiki\DatabaseInterwikiLookup;

class ClassicInterwikiLookupTest extends MediaWikiTestCase {
	public static function provideArgs() {
		return [
			'database' => [
				[
					Language::factory( 'en' ),
					WANObjectCache::newEmpty(),
					60*60,
					false,
					3,
					'en'
				],
				DatabaseInterwikiLookup::class,
			],
			'hash' => [
				[
					Language::factory( 'en' ),
					WANObjectCache::newEmpty(),
					60*60,
					[],
					3,
					'en'
				],
				HashInterwikiLookup::class,
			],
			'cdb' => [
				[
					Language::factory( 'en' ),
					WANObjectCache::newEmpty(),
					60*60,
					'/path/to/cdbfile.cdb',
					3,
					'en'
				],
				CdbInterwikiLookup::class,
			]
		];
	}
	/**
	 * @dataProvider provideArgs
	 */
	public function testClassicInerwikiLookup( array $args, $expectedClass ) {
		// TODO: use argument unpacking (PHP 5.6)
		// $lookup = new ClassicInterwikiLookup( ...$args );
		$r = new \ReflectionClass( ClassicInterwikiLookup::class );
		$lookup = $r->newInstanceArgs( $args );
		$this->assertEquals( $expectedClass, get_class( $lookup->getWrapped() ) );
	}
}

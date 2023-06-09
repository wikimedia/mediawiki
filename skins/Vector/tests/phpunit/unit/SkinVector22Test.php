<?php
namespace MediaWiki\Skins\Vector\Tests\Unit;

use MediaWiki\Skins\Vector\SkinVector22;
use ReflectionMethod;

/**
 * Class VectorTemplateTest
 * @package MediaWiki\Skins\Vector\Tests\Unit
 * @coversDefaultClass \MediaWiki\Skins\Vector\SkinVector22
 * @group Vector
 * @group Skins
 */
class SkinVector22Test extends \MediaWikiUnitTestCase {
	private const MAIN = [
		'id' => 'p-navigation',
	];
	private const SUPPORT = [
		'id' => 'p-support',
	];
	private const TOOLBOX = [
		'id' => 'p-tb',
	];
	private const WIKIBASE = [
		'id' => 'p-wikibase-otherprojects',
	];

	public static function provideExtractPageToolsFromSidebar() {
		return [
			[
				[],
				[], [],
				'No change if sidebar is missing keys'
			],
			[
				[
					'data-portlets-first' => self::MAIN,
					'array-portlets-rest' => [
						self::SUPPORT
					],
				],
				[
					'data-portlets-first' => self::MAIN,
					'array-portlets-rest' => [
						self::SUPPORT
					],
				],
				[],
				'No change if no toolbox found'
			],
			[
				[
					'data-portlets-first' => self::TOOLBOX,
					'array-portlets-rest' => [ self::SUPPORT ],
				],
				[
					'data-portlets-first' => self::TOOLBOX,
					'array-portlets-rest' => [ self::SUPPORT ],
				],
				[],
				'A toolbox in first part of sidebar is ignored.'
			],

			[
				[
					'data-portlets-first' => self::MAIN,
					'array-portlets-rest' => [ self::SUPPORT, self::TOOLBOX, self::WIKIBASE ],
				],
				// new expected sidebar
				[
					'data-portlets-first' => self::MAIN,
					'array-portlets-rest' => [
						self::SUPPORT
					],
				],
				// new expected page tools menu
				[
					self::TOOLBOX, self::WIKIBASE
				],
				'Toolbox and any items after it are pulled out.'
			],
		];
	}

	/**
	 * @covers ::extractPageToolsFromSidebar
	 * @dataProvider provideExtractPageToolsFromSidebar
	 */
	public function testExtractPageToolsFromSidebar( $sidebar, $expectedSidebar, $expectedPageTools, $msg ) {
		$pageTools = [];
		$extractPageToolsFromSidebar = new ReflectionMethod(
			SkinVector22::class,
			'extractPageToolsFromSidebar'
		);
		$extractPageToolsFromSidebar->setAccessible( true );
		$extractPageToolsFromSidebar->invokeArgs( null, [ &$sidebar, &$pageTools ] );
		$this->assertEquals( $expectedSidebar, $sidebar );
		$this->assertEquals( $expectedPageTools, $pageTools, $msg );
	}
}

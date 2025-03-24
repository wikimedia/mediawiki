<?php
namespace MediaWiki\Tests\Unit\Output;

use MediaWiki\Output\OutputPage;
use MediaWiki\ResourceLoader\Module;
use MediaWiki\ResourceLoader\ResourceLoader;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\Output\OutputPage
 */
class OutputPageUnitTest extends MediaWikiUnitTestCase {
	/**
	 * @dataProvider provideGetModules
	 *
	 * @param string|string[] $addedModules Module name or list of module names to add to the output via
	 * addModules()
	 * @param string|string[] $addedStyleModules Module name or list of module names to add to the output via
	 * addModuleStyles()
	 * @param int|null $maxAllowedLevel Maximum allowed origin level to set via reduceAllowedModules(),
	 * given as a Module::ORIGIN_* constant, or `null` for no restriction.
	 * @param bool $filter Whether getModules() should filter the returned module list based on active origin
	 * restrictions
	 * @param string[] $expectedModules Expected list of module names to be returned by getModules()
	 * @param string[] $expectedStyleModules Expected list of style module names to be returned by getModules()
	 */
	public function testGetModules(
		$addedModules,
		$addedStyleModules,
		?int $maxAllowedLevel,
		bool $filter,
		array $expectedModules,
		array $expectedStyleModules
	): void {
		$moduleMapping = [];

		$moduleOrigins = [
			'test.user.js' => Module::ORIGIN_USER_INDIVIDUAL,
			'test.site.js' => Module::ORIGIN_USER_SITEWIDE,
			'test.ext.js' => Module::ORIGIN_CORE_SITEWIDE,

			'test.user.css' => Module::ORIGIN_USER_INDIVIDUAL,
			'test.site.css' => Module::ORIGIN_USER_SITEWIDE,
			'test.ext.css' => Module::ORIGIN_CORE_SITEWIDE,
		];

		foreach ( $moduleOrigins as $moduleName => $moduleOrigin ) {
			$module = $this->createMock( Module::class );
			$module->method( 'getOrigin' )
				->willReturn( $moduleOrigin );

			$moduleMapping[] = [ $moduleName, $module ];
		}

		$resourceLoader = $this->createMock( ResourceLoader::class );
		$resourceLoader->method( 'getModule' )
			->willReturnMap( $moduleMapping );

		$outputPage = $this->getMockBuilder( OutputPage::class )
			->onlyMethods( [ 'getResourceLoader' ] )
			->disableOriginalConstructor()
			->getMock();
		$outputPage->method( 'getResourceLoader' )
			->willReturn( $resourceLoader );

		$outputPage->addModules( $addedModules );
		$outputPage->addModuleStyles( $addedStyleModules );

		if ( $maxAllowedLevel !== null ) {
			$outputPage->reduceAllowedModules( Module::TYPE_STYLES, $maxAllowedLevel );
			$outputPage->reduceAllowedModules( Module::TYPE_SCRIPTS, $maxAllowedLevel );
		}

		$modules = $outputPage->getModules( $filter );
		$styleModules = $outputPage->getModuleStyles( $filter );

		$this->assertSame( $expectedModules, $modules );
		$this->assertSame( $expectedStyleModules, $styleModules );
	}

	public static function provideGetModules(): iterable {
		yield 'single module' => [
			'test.ext.js',
			[],
			null,
			false,
			[ 'test.ext.js' ],
			[]
		];

		yield 'single user-level module' => [
			'test.user.js',
			[],
			null,
			false,
			[ 'test.user.js' ],
			[]
		];

		yield 'duplicate additions of the same module' => [
			[ 'test.ext.js', 'test.ext.js' ],
			[],
			null,
			false,
			[ 'test.ext.js' ],
			[]
		];

		yield 'duplicate modules and style modules' => [
			[ 'test.ext.js', 'test.ext.js', 'test.user.js' ],
			[ 'test.ext.css', 'test.ext.css' ],
			null,
			false,
			[ 'test.ext.js', 'test.user.js' ],
			[ 'test.ext.css' ]
		];

		yield 'filtered module listing without module level restrictions' => [
			[ 'test.user.js', 'test.site.js', 'test.ext.js' ],
			[ 'test.user.css', 'test.site.css', 'test.ext.css' ],
			null,
			true,
			[ 'test.user.js', 'test.site.js', 'test.ext.js' ],
			[ 'test.user.css', 'test.site.css', 'test.ext.css' ],
		];

		yield 'filtered module listing with user-level modules restricted' => [
			[ 'test.user.js', 'test.site.js', 'test.ext.js' ],
			[ 'test.user.css', 'test.site.css', 'test.ext.css' ],
			Module::ORIGIN_CORE_SITEWIDE,
			true,
			[ 'test.ext.js' ],
			[ 'test.ext.css' ]
		];

		yield 'unfiltered module listing with user-level modules restricted' => [
			[ 'test.user.js', 'test.site.js', 'test.ext.js' ],
			[ 'test.user.css', 'test.site.css', 'test.ext.css' ],
			Module::ORIGIN_CORE_SITEWIDE,
			false,
			[ 'test.user.js', 'test.site.js', 'test.ext.js' ],
			[ 'test.user.css', 'test.site.css', 'test.ext.css' ],
		];
	}
}

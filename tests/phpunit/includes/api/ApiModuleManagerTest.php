<?php

use MediaWiki\MediaWikiServices;
use Wikimedia\ObjectFactory;

/**
 * @covers ApiModuleManager
 *
 * @group API
 * @group Database
 * @group medium
 */
class ApiModuleManagerTest extends MediaWikiIntegrationTestCase {

	private function getModuleManager() {
		$request = new FauxRequest();
		$main = new ApiMain( $request );

		return new ApiModuleManager( $main, MediaWikiServices::getInstance()->getObjectFactory() );
	}

	public function newApiLogin( $main, $action ) {
		return new ApiLogin( $main, $action );
	}

	public function addModuleProvider() {
		return [
			'plain class' => [
				'login',
				'action',
				ApiLogin::class,
				null,
			],

			'with class and factory' => [
				'login',
				'action',
				ApiLogin::class,
				[ $this, 'newApiLogin' ],
			],

			'with spec (class only)' => [
				'login',
				'action',
				[
					'class' => ApiLogin::class
				],
				null,
			],

			'with spec' => [
				'login',
				'action',
				[
					'class' => ApiLogin::class,
					'factory' => [ $this, 'newApiLogin' ],
				],
				null,
			],

			'with spec (using services)' => [
				'logout',
				'action',
				[
					'class' => ApiLogout::class,
					'factory' => function ( ApiMain $main, $action, ObjectFactory $objectFactory ) {
						return new ApiLogout( $main, $action );
					},
					'services' => [
						'ObjectFactory'
					],
				],
				null,
			]
		];
	}

	/**
	 * @dataProvider addModuleProvider
	 */
	public function testAddModule( $name, $group, $spec, $factory ) {
		if ( $factory ) {
			$this->hideDeprecated(
				ApiModuleManager::class . '::addModule with $class and $factory'
			);
		}

		$moduleManager = $this->getModuleManager();
		$moduleManager->addModule( $name, $group, $spec, $factory );

		$this->assertTrue( $moduleManager->isDefined( $name, $group ), 'isDefined' );
		$this->assertNotNull( $moduleManager->getModule( $name, $group, true ), 'getModule' );
	}

	public function addModulesProvider() {
		return [
			'empty' => [
				[],
				'action',
			],

			'simple' => [
				[
					'login' => ApiLogin::class,
					'logout' => ApiLogout::class,
				],
				'action',
			],

			'with factories' => [
				[
					'login' => [
						'class' => ApiLogin::class,
						'factory' => [ $this, 'newApiLogin' ],
					],
					'logout' => [
						'class' => ApiLogout::class,
						'factory' => function ( ApiMain $main, $action ) {
							return new ApiLogout( $main, $action );
						},
					],
				],
				'action',
			],
		];
	}

	/**
	 * @dataProvider addModulesProvider
	 */
	public function testAddModules( array $modules, $group ) {
		$moduleManager = $this->getModuleManager();
		$moduleManager->addModules( $modules, $group );

		foreach ( array_keys( $modules ) as $name ) {
			$this->assertTrue( $moduleManager->isDefined( $name, $group ), 'isDefined' );
			$this->assertNotNull( $moduleManager->getModule( $name, $group, true ), 'getModule' );
		}

		$this->assertTrue( true ); // Don't mark the test as risky if $modules is empty
	}

	public function getModuleProvider() {
		$modules = [
			'feedrecentchanges' => ApiFeedRecentChanges::class,
			'feedcontributions' => [ 'class' => ApiFeedContributions::class ],
			'login' => [
				'class' => ApiLogin::class,
				'factory' => [ $this, 'newApiLogin' ],
			],
			'logout' => [
				'class' => ApiLogout::class,
				'factory' => function ( ApiMain $main, $action ) {
					return new ApiLogout( $main, $action );
				},
			],
		];

		return [
			'legacy entry' => [
				$modules,
				'feedrecentchanges',
				ApiFeedRecentChanges::class,
			],

			'just a class' => [
				$modules,
				'feedcontributions',
				ApiFeedContributions::class,
			],

			'with factory' => [
				$modules,
				'login',
				ApiLogin::class,
			],

			'with closure' => [
				$modules,
				'logout',
				ApiLogout::class,
			],
		];
	}

	/**
	 * @covers ApiModuleManager::getModule
	 * @dataProvider getModuleProvider
	 */
	public function testGetModule( $modules, $name, $expectedClass ) {
		$moduleManager = $this->getModuleManager();
		$moduleManager->addModules( $modules, 'test' );

		// should return the right module
		$module1 = $moduleManager->getModule( $name, null, false );
		$this->assertInstanceOf( $expectedClass, $module1 );

		// should pass group check (with caching disabled)
		$module2 = $moduleManager->getModule( $name, 'test', true );
		$this->assertNotNull( $module2 );

		// should use cached instance
		$module3 = $moduleManager->getModule( $name, null, false );
		$this->assertSame( $module1, $module3 );

		// should not use cached instance if caching is disabled
		$module4 = $moduleManager->getModule( $name, null, true );
		$this->assertNotSame( $module1, $module4 );
	}

	/**
	 * @covers ApiModuleManager::getModule
	 */
	public function testGetModule_null() {
		$modules = [
			'login' => ApiLogin::class,
			'logout' => ApiLogout::class,
		];

		$moduleManager = $this->getModuleManager();
		$moduleManager->addModules( $modules, 'test' );

		$this->assertNull( $moduleManager->getModule( 'quux' ), 'unknown name' );
		$this->assertNull( $moduleManager->getModule( 'login', 'bla' ), 'wrong group' );
	}

	/**
	 * @covers ApiModuleManager::getNames
	 */
	public function testGetNames() {
		$fooModules = [
			'login' => ApiLogin::class,
			'logout' => ApiLogout::class,
		];

		$barModules = [
			'feedcontributions' => [ 'class' => ApiFeedContributions::class ],
			'feedrecentchanges' => [ 'class' => ApiFeedRecentChanges::class ],
		];

		$moduleManager = $this->getModuleManager();
		$moduleManager->addModules( $fooModules, 'foo' );
		$moduleManager->addModules( $barModules, 'bar' );

		$fooNames = $moduleManager->getNames( 'foo' );
		$this->assertArrayEquals( array_keys( $fooModules ), $fooNames );

		$allNames = $moduleManager->getNames();
		$allModules = array_merge( $fooModules, $barModules );
		$this->assertArrayEquals( array_keys( $allModules ), $allNames );
	}

	/**
	 * @covers ApiModuleManager::getNamesWithClasses
	 */
	public function testGetNamesWithClasses() {
		$fooModules = [
			'login' => ApiLogin::class,
			'logout' => ApiLogout::class,
		];

		$barModules = [
			'feedcontributions' => [ 'class' => ApiFeedContributions::class ],
			'feedrecentchanges' => [ 'class' => ApiFeedRecentChanges::class ],
		];

		$moduleManager = $this->getModuleManager();
		$moduleManager->addModules( $fooModules, 'foo' );
		$moduleManager->addModules( $barModules, 'bar' );

		$fooNamesWithClasses = $moduleManager->getNamesWithClasses( 'foo' );
		$this->assertArrayEquals( $fooModules, $fooNamesWithClasses );

		$allNamesWithClasses = $moduleManager->getNamesWithClasses();
		$allModules = array_merge( $fooModules, [
			'feedcontributions' => ApiFeedContributions::class,
			'feedrecentchanges' => ApiFeedRecentChanges::class,
		] );
		$this->assertArrayEquals( $allModules, $allNamesWithClasses );
	}

	/**
	 * @covers ApiModuleManager::getModuleGroup
	 */
	public function testGetModuleGroup() {
		$fooModules = [
			'login' => ApiLogin::class,
			'logout' => ApiLogout::class,
		];

		$barModules = [
			'feedcontributions' => [ 'class' => ApiFeedContributions::class ],
			'feedrecentchanges' => [ 'class' => ApiFeedRecentChanges::class ],
		];

		$moduleManager = $this->getModuleManager();
		$moduleManager->addModules( $fooModules, 'foo' );
		$moduleManager->addModules( $barModules, 'bar' );

		$this->assertEquals( 'foo', $moduleManager->getModuleGroup( 'login' ) );
		$this->assertEquals( 'bar', $moduleManager->getModuleGroup( 'feedrecentchanges' ) );
		$this->assertNull( $moduleManager->getModuleGroup( 'quux' ) );
	}

	/**
	 * @covers ApiModuleManager::getGroups
	 */
	public function testGetGroups() {
		$fooModules = [
			'login' => ApiLogin::class,
			'logout' => ApiLogout::class,
		];

		$barModules = [
			'feedcontributions' => [ 'class' => ApiFeedContributions::class ],
			'feedrecentchanges' => [ 'class' => ApiFeedRecentChanges::class ],
		];

		$moduleManager = $this->getModuleManager();
		$moduleManager->addModules( $fooModules, 'foo' );
		$moduleManager->addModules( $barModules, 'bar' );

		$groups = $moduleManager->getGroups();
		$this->assertArrayEquals( [ 'foo', 'bar' ], $groups );
	}

	/**
	 * @covers ApiModuleManager::getClassName
	 */
	public function testGetClassName() {
		$fooModules = [
			'login' => ApiLogin::class,
			'logout' => ApiLogout::class,
		];

		$barModules = [
			'feedcontributions' => [ 'class' => ApiFeedContributions::class ],
			'feedrecentchanges' => [ 'class' => ApiFeedRecentChanges::class ],
		];

		$moduleManager = $this->getModuleManager();
		$moduleManager->addModules( $fooModules, 'foo' );
		$moduleManager->addModules( $barModules, 'bar' );

		$this->assertEquals(
			ApiLogin::class,
			$moduleManager->getClassName( 'login' )
		);
		$this->assertEquals(
			ApiLogout::class,
			$moduleManager->getClassName( 'logout' )
		);
		$this->assertEquals(
			ApiFeedContributions::class,
			$moduleManager->getClassName( 'feedcontributions' )
		);
		$this->assertEquals(
			ApiFeedRecentChanges::class,
			$moduleManager->getClassName( 'feedrecentchanges' )
		);
		$this->assertFalse(
			$moduleManager->getClassName( 'nonexistentmodule' )
		);
	}

	public function testAddModuleWithIncompleteSpec() {
		$moduleManager = $this->getModuleManager();

		$this->expectException( \InvalidArgumentException::class );
		$this->expectExceptionMessage( '$spec must define a class name' );
		$moduleManager->addModule(
			'logout',
			'action',
			[
				'factory' => function ( ApiMain $main, $action ) {
					return new ApiLogout( $main, $action );
				},
			]
		);
	}
}

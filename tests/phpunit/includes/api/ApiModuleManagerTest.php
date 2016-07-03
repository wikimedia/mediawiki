<?php

/**
 * @covers ApiModuleManager
 *
 * @group API
 * @group Database
 * @group medium
 */
class ApiModuleManagerTest extends MediaWikiTestCase {

	private function getModuleManager() {
		$request = new FauxRequest();
		$main = new ApiMain( $request );
		return new ApiModuleManager( $main );
	}

	public function newApiLogin( $main, $action ) {
		return new ApiLogin( $main, $action );
	}

	public function addModuleProvider() {
		return [
			'plain class' => [
				'login',
				'action',
				'ApiLogin',
				null,
			],

			'with factory' => [
				'login',
				'action',
				'ApiLogin',
				[ $this, 'newApiLogin' ],
			],

			'with closure' => [
				'logout',
				'action',
				'ApiLogout',
				function ( ApiMain $main, $action ) {
					return new ApiLogout( $main, $action );
				},
			],
		];
	}

	/**
	 * @dataProvider addModuleProvider
	 */
	public function testAddModule( $name, $group, $class, $factory = null ) {
		$moduleManager = $this->getModuleManager();
		$moduleManager->addModule( $name, $group, $class, $factory );

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
					'login' => 'ApiLogin',
					'logout' => 'ApiLogout',
				],
				'action',
			],

			'with factories' => [
				[
					'login' => [
						'class' => 'ApiLogin',
						'factory' => [ $this, 'newApiLogin' ],
					],
					'logout' => [
						'class' => 'ApiLogout',
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
			'feedrecentchanges' => 'ApiFeedRecentChanges',
			'feedcontributions' => [ 'class' => 'ApiFeedContributions' ],
			'login' => [
				'class' => 'ApiLogin',
				'factory' => [ $this, 'newApiLogin' ],
			],
			'logout' => [
				'class' => 'ApiLogout',
				'factory' => function ( ApiMain $main, $action ) {
					return new ApiLogout( $main, $action );
				},
			],
		];

		return [
			'legacy entry' => [
				$modules,
				'feedrecentchanges',
				'ApiFeedRecentChanges',
			],

			'just a class' => [
				$modules,
				'feedcontributions',
				'ApiFeedContributions',
			],

			'with factory' => [
				$modules,
				'login',
				'ApiLogin',
			],

			'with closure' => [
				$modules,
				'logout',
				'ApiLogout',
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
			'login' => 'ApiLogin',
			'logout' => 'ApiLogout',
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
			'login' => 'ApiLogin',
			'logout' => 'ApiLogout',
		];

		$barModules = [
			'feedcontributions' => [ 'class' => 'ApiFeedContributions' ],
			'feedrecentchanges' => [ 'class' => 'ApiFeedRecentChanges' ],
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
			'login' => 'ApiLogin',
			'logout' => 'ApiLogout',
		];

		$barModules = [
			'feedcontributions' => [ 'class' => 'ApiFeedContributions' ],
			'feedrecentchanges' => [ 'class' => 'ApiFeedRecentChanges' ],
		];

		$moduleManager = $this->getModuleManager();
		$moduleManager->addModules( $fooModules, 'foo' );
		$moduleManager->addModules( $barModules, 'bar' );

		$fooNamesWithClasses = $moduleManager->getNamesWithClasses( 'foo' );
		$this->assertArrayEquals( $fooModules, $fooNamesWithClasses );

		$allNamesWithClasses = $moduleManager->getNamesWithClasses();
		$allModules = array_merge( $fooModules, [
			'feedcontributions' => 'ApiFeedContributions',
			'feedrecentchanges' => 'ApiFeedRecentChanges',
		] );
		$this->assertArrayEquals( $allModules, $allNamesWithClasses );
	}

	/**
	 * @covers ApiModuleManager::getModuleGroup
	 */
	public function testGetModuleGroup() {
		$fooModules = [
			'login' => 'ApiLogin',
			'logout' => 'ApiLogout',
		];

		$barModules = [
			'feedcontributions' => [ 'class' => 'ApiFeedContributions' ],
			'feedrecentchanges' => [ 'class' => 'ApiFeedRecentChanges' ],
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
			'login' => 'ApiLogin',
			'logout' => 'ApiLogout',
		];

		$barModules = [
			'feedcontributions' => [ 'class' => 'ApiFeedContributions' ],
			'feedrecentchanges' => [ 'class' => 'ApiFeedRecentChanges' ],
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
			'login' => 'ApiLogin',
			'logout' => 'ApiLogout',
		];

		$barModules = [
			'feedcontributions' => [ 'class' => 'ApiFeedContributions' ],
			'feedrecentchanges' => [ 'class' => 'ApiFeedRecentChanges' ],
		];

		$moduleManager = $this->getModuleManager();
		$moduleManager->addModules( $fooModules, 'foo' );
		$moduleManager->addModules( $barModules, 'bar' );

		$this->assertEquals(
			'ApiLogin',
			$moduleManager->getClassName( 'login' )
		);
		$this->assertEquals(
			'ApiLogout',
			$moduleManager->getClassName( 'logout' )
		);
		$this->assertEquals(
			'ApiFeedContributions',
			$moduleManager->getClassName( 'feedcontributions' )
		);
		$this->assertEquals(
			'ApiFeedRecentChanges',
			$moduleManager->getClassName( 'feedrecentchanges' )
		);
		$this->assertFalse(
			$moduleManager->getClassName( 'nonexistentmodule' )
		);
	}
}

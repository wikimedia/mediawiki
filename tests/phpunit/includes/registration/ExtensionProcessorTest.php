<?php

class ExtensionProcessorTest extends MediaWikiTestCase {

	private $dir;

	public function setUp() {
		parent::setUp();
		$this->dir = __DIR__ . '/FooBar/extension.json';
	}
	/**
	 * @param string $name
	 * @return ScopedCallback
	 */
	protected function stashGlobal( $name ) {
		if ( isset( $GLOBALS[$name] ) ) {
			$old = $GLOBALS[$name];
			return new ScopedCallback( function() use ( $name, $old ) {
				$GLOBALS[$name] = $old;
			} );
		} else {
			return new ScopedCallback( function() use ( $name ) {
				if ( isset( $GLOBALS[$name] ) ) {
					unset( $GLOBALS[$name] );
				}
			} );
		}
	}

	public static function provideRegisterHooks() {
		return array(
			// No hooks
			array(
				array(),
				array(),
				array(),
			),
			// No current hooks, adding one for "FooBaz"
			array(
				array(),
				array( 'Hooks' => array( 'FooBaz' => 'FooBazCallback' ) ),
				array( 'FooBaz' => array( 'FooBazCallback' ) ),
			),
			// Hook for "FooBaz", adding another one
			array(
				array( 'FooBaz' => array( 'PriorCallback' ) ),
				array( 'Hooks' => array( 'FooBaz' => 'FooBazCallback' ) ),
				array( 'FooBaz' => array( 'PriorCallback', 'FooBazCallback' ) ),
			),
			// Hook for "BarBaz", adding one for "FooBaz"
			array(
				array( 'BarBaz' => array( 'BarBazCallback' ) ),
				array( 'Hooks' => array( 'FooBaz' => 'FooBazCallback' ) ),
				array(
					'BarBaz' => array( 'BarBazCallback' ),
					'FooBaz' => array( 'FooBazCallback' ),
				),
			),
		);
	}

	/**
	 * @covers ExtensionProcessor::registerHooks
	 * @dataProvider provideRegisterHooks
	 */
	public function testRegisterHooks( $pre, $info, $expected ) {
		$stash = $this->stashGlobal( 'wgHooks' );
		$GLOBALS['wgHooks'] = $pre;
		$processor = new ExtensionProcessor;
		$processor->processInfo( $this->dir, $info );
		$this->assertEquals( $expected, $GLOBALS['wgHooks'] );
	}

	/**
	 * @covers ExtensionProcessor::setConfig
	 */
	public function testSetConfig() {
		$stash = $this->stashGlobal( 'wgBar' );
		$stash2 = $this->stashGlobal( 'wgFoo' );
		$processor = new ExtensionProcessor;
		$info = array(
			'config' => array(
				'Bar' => 'somevalue',
				'Foo' => 10,
			),
		);
		$processor->processInfo( $this->dir, $info );
		$this->assertEquals( 'somevalue', $GLOBALS['wgBar'] );
		$this->assertEquals( 10, $GLOBALS['wgFoo'] );
	}

	public static function provideSetToGlobal() {
		return array(
			array(
				array( 'wgAPIModules', 'wgAvailableRights' ),
				array(),
				array(
					'APIModules' => array( 'foobar' => 'ApiFooBar' ),
					'AvailableRights' => array( 'foobar', 'unfoobar' ),
				),
				array(
					'wgAPIModules' => array( 'foobar' => 'ApiFooBar' ),
					'wgAvailableRights' => array( 'foobar', 'unfoobar' ),
				),
			),
			array(
				array( 'wgAPIModules', 'wgAvailableRights' ),
				array(
					'wgAPIModules' => array( 'barbaz' => 'ApiBarBaz' ),
					'wgAvailableRights' => array( 'barbaz' )
				),
				array(
					'APIModules' => array( 'foobar' => 'ApiFooBar' ),
					'AvailableRights' => array( 'foobar', 'unfoobar' ),
				),
				array(
					'wgAPIModules' => array( 'barbaz' => 'ApiBarBaz', 'foobar' => 'ApiFooBar' ),
					'wgAvailableRights' => array( 'barbaz', 'foobar', 'unfoobar' ),
				),
			),
			array(
				array( 'wgGroupPermissions' ),
				array(
					'wgGroupPermissions' => array( 'sysop' => array( 'delete' ) ),
				),
				array(
					'GroupPermissions' => array( 'sysop' => array( 'undelete' ), 'user' => array( 'edit' ) ),
				),
				array(
					'wgGroupPermissions' => array( 'sysop' => array( 'delete', 'undelete' ), 'user' => array( 'edit' ) ),
				)
			)
		);
	}

	/**
	 * @covers ExtensionProcessor::setToGlobal
	 * @dataProvider provideSetToGlobal
	 */
	public function testSetToGlobal( $stash, $pre, $info, $expected ) {
		$stashes = array();
		foreach ( $stash as $var ) {
			$stashes[] = $this->stashGlobal( $var );
			$GLOBALS[$var] = array();
		}

		foreach ( $pre as $name => $value ) {
			$GLOBALS[$name] = $value;
		}

		$processor = new ExtensionProcessor;
		$processor->processInfo( $this->dir, $info );
		foreach ( $expected as $name => $value ) {
			$this->assertEquals( $value, $GLOBALS[$name] );
		}
	}
}
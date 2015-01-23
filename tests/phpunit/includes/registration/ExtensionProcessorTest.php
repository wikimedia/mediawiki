<?php

class ExtensionProcessorTest extends MediaWikiTestCase {

	private $dir;

	public function setUp() {
		parent::setUp();
		$this->dir = __DIR__ . '/FooBar/extension.json';
	}

	/**
	 * 'name' is absolutely required
	 *
	 * @var array
	 */
	static $default = array(
		'name' => 'FooBar',
	);

	/**
	 * @covers ExtensionProcessor::extractInfo
	 */
	public function testExtractInfo() {
		// Test that attributes that begin with @ are ignored
		$processor = new ExtensionProcessor();
		$processor->extractInfo( $this->dir, self::$default + array(
			'@metadata' => array( 'foobarbaz' ),
			'AnAttribute' => array( 'omg' ),
		) );

		$extracted = $processor->getExtractedInfo();
		$attributes = $extracted['attributes'];
		$this->assertArrayHasKey( 'AnAttribute', $attributes );
		$this->assertArrayNotHasKey( '@metadata', $attributes );
	}

	public static function provideRegisterHooks() {
		return array(
			// No hooks
			array(
				array(),
				self::$default,
				array(),
			),
			// No current hooks, adding one for "FooBaz"
			array(
				array(),
				array( 'Hooks' => array( 'FooBaz' => 'FooBazCallback' ) ) + self::$default,
				array( 'FooBaz' => array( 'FooBazCallback' ) ),
			),
			// Hook for "FooBaz", adding another one
			array(
				array( 'FooBaz' => array( 'PriorCallback' ) ),
				array( 'Hooks' => array( 'FooBaz' => 'FooBazCallback' ) ) + self::$default,
				array( 'FooBaz' => array( 'PriorCallback', 'FooBazCallback' ) ),
			),
			// Hook for "BarBaz", adding one for "FooBaz"
			array(
				array( 'BarBaz' => array( 'BarBazCallback' ) ),
				array( 'Hooks' => array( 'FooBaz' => 'FooBazCallback' ) ) + self::$default,
				array(
					'BarBaz' => array( 'BarBazCallback' ),
					'FooBaz' => array( 'FooBazCallback' ),
				),
			),
		);
	}

	/**
	 * @covers ExtensionProcessor::extractHooks
	 * @dataProvider provideRegisterHooks
	 */
	public function testRegisterHooks( $pre, $info, $expected ) {
		$processor = new MockExtensionProcessor( array( 'wgHooks' => $pre ) );
		$processor->extractInfo( $this->dir, $info );
		$extracted = $processor->getExtractedInfo();
		$this->assertEquals( $expected, $extracted['globals']['wgHooks'] );
	}

	/**
	 * @covers ExtensionProcessor::extractConfig
	 */
	public function testExtractConfig() {
		$processor = new ExtensionProcessor;
		$info = array(
			'config' => array(
				'Bar' => 'somevalue',
				'Foo' => 10,
			),
		) + self::$default;
		$processor->extractInfo( $this->dir, $info );
		$extracted = $processor->getExtractedInfo();
		$this->assertEquals( 'somevalue', $extracted['globals']['wgBar'] );
		$this->assertEquals( 10, $extracted['globals']['wgFoo'] );
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
}


/**
 * Allow overriding the default value of $this->globals
 * so we can test merging
 */
class MockExtensionProcessor extends ExtensionProcessor {
	public function __construct( $globals = array() ) {
		$this->globals = $globals + $this->globals;
	}
}

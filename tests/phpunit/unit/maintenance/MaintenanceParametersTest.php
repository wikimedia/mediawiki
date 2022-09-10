<?php

use MediaWiki\Maintenance\MaintenanceParameters;
use PHPUnit\Framework\TestCase;

/**
 * @covers MaintenanceParametersFilter
 */
class MaintenanceParametersTest extends TestCase {

	public function testOption() {
		$params = new MaintenanceParameters();

		$params->addOption( 'test', 'Test Option' );

		$this->assertTrue( $params->supportsOption( 'test' ) );
		$this->assertFalse( $params->supportsOption( 'xyzzy' ) );

		$this->assertFalse( $params->hasOption( 'test' ) );
		$this->assertNull( $params->getOption( 'test' ) );
		$this->assertSame( 'default', $params->getOption( 'test', 'default' ) );

		$this->assertSame( [], $params->getOptions() );
		$this->assertSame( [], $params->getOptionsSequence() );
		$this->assertSame( [ 'test' ], $params->getOptionNames() );

		// Provide option value
		$params->setOptionsAndArgs( [ 'test' => 'foo' ], [] );

		$this->assertTrue( $params->hasOption( 'test' ) );
		$this->assertSame( 'foo', $params->getOption( 'test' ) );
		$this->assertSame( 'foo', $params->getOption( 'test', 'default' ) );

		$this->assertSame( [ 'test' => 'foo' ], $params->getOptions() );
		$this->assertSame( [ [ 'test', 'foo' ] ], $params->getOptionsSequence() );
		$this->assertSame( [ 'test' ], $params->getOptionNames() );

		// Delete option
		$params->deleteOption( 'test' );

		$this->assertFalse( $params->hasOption( 'test' ) );
		$this->assertFalse( $params->supportsOption( 'test' ) );
		$this->assertNull( $params->getOption( 'test' ) );

		$this->assertSame( [], $params->getOptions() );
		$this->assertSame( [], $params->getOptionsSequence() );
		$this->assertSame( [], $params->getOptionNames() );
	}

	public function testOptionSequence() {
		$params = new MaintenanceParameters();

		$params->addOption( 'foo', 'Foo', false, false );
		$params->addOption( 'bar', 'Bar', false, true );

		$params->loadWithArgv( [ '--bar', 'A', '--bar=B', '--foo', '--bar', 'C' ] );

		$this->assertSame(
			[
				[ 'bar', 'A' ],
				[ 'bar', 'B' ],
				[ 'foo', 1 ],
				[ 'bar', 'C' ],
			],
			$params->getOptionsSequence()
		);

		$params->setOptionsAndArgs( [ 'bar' => [ 'x', 'y' ], 'foo' => 1 ], [] );

		$this->assertSame(
			[
				[ 'bar', 'x' ],
				[ 'bar', 'y' ],
				[ 'foo', 1 ],
			],
			$params->getOptionsSequence()
		);
	}

	public function testArg() {
		$params = new MaintenanceParameters();

		$this->assertSame( 0, $params->addArg( 'test', 'Test arg' ) );
		$this->assertSame( 1, $params->addArg( 'best', 'Best arg' ) );

		$this->assertSame( 'test', $params->getArgName( 0 ) );
		$this->assertSame( 'best', $params->getArgName( 1 ) );
		$this->assertNull( $params->getArgName( 2 ) );

		$this->assertFalse( $params->hasArg( 0 ) );
		$this->assertNull( $params->getArg( 0 ) );

		$this->assertSame( [], $params->getArgs() );

		// Provide argument values
		$params->setOptionsAndArgs( [], [ 'foo', 'bar', 'cuzz' ] );

		$this->assertTrue( $params->hasArg( 0 ) );
		$this->assertTrue( $params->hasArg( 'test' ) );
		$this->assertTrue( $params->hasArg( 1 ) );
		$this->assertTrue( $params->hasArg( 'best' ) );
		$this->assertTrue( $params->hasArg( 2 ) );
		$this->assertFalse( $params->hasArg( 3 ) );

		$this->assertSame( 'foo', $params->getArg( 0 ) );
		$this->assertSame( 'foo', $params->getArg( 'test' ) );
		$this->assertSame( 'bar', $params->getArg( 1 ) );
		$this->assertSame( 'bar', $params->getArg( 'best' ) );
		$this->assertSame( 'cuzz', $params->getArg( 2 ) );
		$this->assertNull( $params->getArg( 3 ) );

		$this->assertSame( [ 'foo', 'bar', 'cuzz' ], $params->getArgs() );
	}

	public function testClear() {
		$params = new MaintenanceParameters();

		$params->addOption( 'test', 'Test Option' );
		$params->addArg( 'jest', 'Jest arg' );

		$params->setOptionsAndArgs( [ 'test' => 'foo' ], [ 'x', 'y' ] );

		$this->assertTrue( $params->hasOption( 'test' ) );
		$this->assertTrue( $params->hasArg( 'jest' ) );

		$params->clear();

		$this->assertFalse( $params->hasOption( 'test' ) );
		$this->assertFalse( $params->hasArg( 'jest' ) );

		$this->assertSame( 'jest', $params->getArgName( 0 ) );
		$this->assertTrue( $params->supportsOption( 'test' ) );
	}

	public function provideArgv() {
		yield 'nothing' => [
			[], [], []
		];

		yield 'simple' => [
			[ '--simple', 'test' ], [ 'simple' => 1 ], [ 'test' ]
		];

		yield 'trailing option' => [
			[ 'test', '--simple' ], [ 'simple' => 1 ], [ 'test' ]
		];

		yield 'not a trailing option' => [
			[ 'test', '--', '--simple' ], [], [ 'test', '--simple' ]
		];

		yield 'option value' => [
			[ '--value', 'foo' ], [ 'value' => 'foo' ], []
		];

		yield 'option value swallows option' => [
			[ '--value', '--simple' ], [ 'value' => '--simple' ], []
		];

		yield 'option value swallows delimiter' => [
			[ '--value', '--', '--simple' ], [ 'value' => '--', 'simple' => 1 ], []
		];

		yield 'option value assigned' => [
			[ '--value=foo', 'test' ], [ 'value' => 'foo' ], [ 'test' ]
		];

		yield 'option value short' => [
			[ '-sv', 'foo' ], [ 'simple' => 1, 'value' => 'foo' ], []
		];

		yield 'short option and lonely dash' => [
			[ '-s', '-', 'foo' ], [ 'simple' => 1 ], [ '-', 'foo' ]
		];

		yield 'multi value' => [
			[ '--multi', 'foo', 'test', '--multi', 'bar' ],
			[ 'multi' => [ 'foo', 'bar' ] ],
			[ 'test' ]
		];

		yield 'multi value short' => [
			[ '-mm', 'foo', 'bar', 'test' ],
			[ 'multi' => [ 'foo', 'bar' ] ],
			[ 'test' ]
		];

		yield 'extra option' => [
			[ '--extra', 'test' ],
			[ 'extra' => 1 ],
			[ 'test' ]
		];

		yield 'extra with assignment' => [
			[ '--extra=foo', 'test' ],
			[ 'extra' => 'foo' ],
			[ 'test' ]
		];
	}

	/**
	 * @dataProvider provideArgv
	 */
	public function testLoad( $argv, $expectedOptions, $expectedArgs ) {
		$params = new MaintenanceParameters();
		$params->setAllowUnregisteredOptions( true );

		$params->addOption( 'simple', 'simple option', false, false, 's' );
		$params->addOption( 'value', 'value option', false, true, 'v' );
		$params->addOption( 'multi', 'multi option', false, true, 'm', true );
		$params->addArg( 'foo', 'Foozels', false );

		$params->loadWithArgv( $argv );

		$params->validate();
		$this->assertFalse( $params->hasErrors() );
		$this->assertEmpty( $params->getErrors() );

		$this->assertSame( $expectedOptions, $params->getOptions() );
		$this->assertSame( $expectedArgs, $params->getArgs() );
	}

	public function provideBadArgv() {
		yield 'nothing' => [
			[],
			[
				'Option --simple is required!',
				'Argument <foo> is required!'
			]
		];

		yield 'missing option' => [
			[ 'arg' ],
			[ 'Option --simple is required!' ]
		];

		yield 'missing arg' => [
			[ '--simple' ],
			[ 'Argument <foo> is required!' ]
		];

		yield 'option given twice' => [
			[ '--simple', 'arg', '-s' ],
			[ 'Option --simple given twice' ]
		];

		yield 'Unexpected option' => [
			[ '--extra', 'arg', '-s' ],
			[ 'Unexpected option --extra!' ]
		];

		yield 'Missing value' => [
			[ '-s', 'arg', '--value' ],
			[ 'Option --value needs a value after it!' ]
		];

		yield 'Missing value short' => [
			[ '-s', 'arg', '-v' ],
			[ 'Option --value needs a value after it!' ]
		];
	}

	/**
	 * @dataProvider provideBadArgv
	 */
	public function testValidationErrors( $argv, $errors ) {
		$params = new MaintenanceParameters();

		$params->addOption( 'simple', 'simple option', true, false, 's' );
		$params->addOption( 'value', 'value option', false, true, 'v' );
		$params->addArg( 'foo', 'Foozels', true );

		$params->loadWithArgv( $argv );

		$params->validate();
		$this->assertTrue( $params->hasErrors() );
		$this->assertSame( $errors, $params->getErrors() );
	}

	private function findInLines( array $lines, $regex, $start = 0 ) {
		for ( $i = $start; $i < count( $lines ); $i++ ) {
			if ( strpos( $lines[ $i ], $regex ) !== false ) {
				return $i;
			}
		}

		return false;
	}

	public function testHelp() {
		$params = new MaintenanceParameters();

		$params->setName( 'Foo' );
		$params->setDescription( 'Frobs the foo' );

		$params->addArg( 'one', 'First arg', true );
		$params->addArg( 'two', 'Second arg', false );

		$params->addOption( 'flag', 'First flag', false, false, 'f' );
		$params->addOption( 'value', 'Some value', true, true );

		$params->assignGroup( 'Test flags', [ 'flag' ] );

		$help = $params->getHelp();
		$lines = preg_split( '/\s*[\r\n]\s*/', $help );
		$lines = array_values( array_filter( $lines ) );

		$this->assertNotFalse( $this->findInLines( $lines, 'Frobs the foo' ) );
		$this->assertNotFalse( $this->findInLines( $lines, 'Usage: php Foo [--flag|--value] <one> [two]' ) );
		$this->assertNotFalse( $this->findInLines( $lines, '--flag (-f): First flag' ) );
		$this->assertNotFalse( $this->findInLines( $lines, '--value: Some value' ) );
		$this->assertNotFalse( $this->findInLines( $lines, '<one>: First arg' ) );
		$this->assertNotFalse( $this->findInLines( $lines, '[two]: Second arg' ) );
		$this->assertNotFalse( $this->findInLines( $lines, 'Test flags' ) );
		$this->assertNotFalse( $this->findInLines( $lines, 'Script specific parameters' ) );

		$sectionOffset = $this->findInLines( $lines, 'Script specific parameters' );
		$this->assertGreaterThan(
			$sectionOffset,
			$this->findInLines( $lines, '--value', $sectionOffset + 1 )
		);

		$sectionOffset = $this->findInLines( $lines, 'Test flags' );
		$this->assertGreaterThan(
			$sectionOffset,
			$this->findInLines( $lines, '--flag', $sectionOffset + 1 )
		);
	}

	public function testMergeOptions() {
		$params = new MaintenanceParameters();
		$params->setName( 'Foo' );
		$params->setDescription( 'Frobs the foo' );

		$params->addOption( 'flag', 'First flag', false, false, 'f' );

		$params->setOptionsAndArgs( [ 'flag' => 1 ], [] );

		$other = new MaintenanceParameters();
		$other->setName( 'Bar' );
		$other->setDescription( 'Mars the bar' );

		$other->addOption( 'value', 'Some value', true, true );

		$params->mergeOptions( $other );

		// declarations from both objects are presetn
		$this->assertTrue( $params->supportsOption( 'flag' ) );
		$this->assertTrue( $params->supportsOption( 'value' ) );

		// values are reset
		$this->assertFalse( $params->hasOption( 'flag' ) );
	}

	public function testSetOptionsAndArgs() {
		$params = new MaintenanceParameters();

		$params->setOptionsAndArgs( [ 'foo' => 'X' ], [ 'one' ] );

		$this->assertTrue( $params->hasOption( 'foo' ) );
		$this->assertTrue( $params->hasArg( 0 ) );
		$this->assertSame( [ 'one' ], $params->getArgs() );
		$this->assertSame( [ 'foo' => 'X' ], $params->getOptions() );

		$params->setOptionsAndArgs( [ 'bar' => 'Y' ], [] );

		$this->assertTrue( $params->hasOption( 'bar' ) );
		$this->assertFalse( $params->hasOption( 'foo' ) );
		$this->assertFalse( $params->hasArg( 0 ) );
		$this->assertSame( [], $params->getArgs() );
		$this->assertSame( [ 'bar' => 'Y' ], $params->getOptions() );
	}

}

<?php

namespace MediaWiki\Tests\Maintenance;

use GetConfiguration;
use MediaWiki\Json\FormatJson;
use MediaWiki\MainConfigNames;
use NullJob;

/**
 * @covers \GetConfiguration
 * @author Dreamy Jazz
 */
class GetConfigurationTest extends MaintenanceBaseTestCase {

	protected function getMaintenanceClass() {
		return GetConfiguration::class;
	}

	/** @dataProvider provideExecuteForFatalError */
	public function testExecuteForFatalError(
		$options, $expectedOutputRegex, $configName = null, $configValue = null
	) {
		if ( $configName !== null ) {
			$this->overrideConfigValue( $configName, $configValue );
		}
		foreach ( $options as $name => $value ) {
			$this->maintenance->setOption( $name, $value );
		}
		$this->expectCallToFatalError();
		// ::maybeShowHelp uses ->mName which is null unless we call this.
		$this->maintenance->setName( 'getConfiguration.php' );
		$this->maintenance->validateParamsAndArgs();
		$this->maintenance->execute();
		$this->expectOutputRegex( $expectedOutputRegex );
	}

	public static function provideExecuteForFatalError() {
		return [
			'Config could not be encoded as JSON' => [
				[ 'format' => 'json' ], '/Failed to serialize the requested settings/',
				MainConfigNames::AutoCreateTempUser, [ 'enabled' => true, 'expiryAfterDays' => INF ]
			],
			'Undefined format' => [ [ 'format' => 'invalid-format' ], '/--format set to an unrecognized format/' ],
			'Using both iregex and regex' => [
				[ 'iregex' => 'wgAuto', 'regex' => 'wgAuto' ],
				'/Can only use either --regex or --iregex/',
			],
			'Setting does not begin with wg' => [
				[ 'settings' => 'wgAutoCreateTempUser InvalidConfig' ],
				'/Variable \'InvalidConfig\' does start with \'wg\'/'
			],
			'Setting is undefined' => [
				[ 'settings' => 'wgAutoCreateTempUser wgInvalidConfigForGetConfigurationTest' ],
				'/Variable \'wgInvalidConfigForGetConfigurationTest\' is not set/'
			],
			'Config that is referenced by --settings has non-array and non-scalar items' => [
				[ 'settings' => 'wgAutoCreateTempUser' ],
				'/Variable wgAutoCreateTempUser includes non-array, non-scalar, items/',
				MainConfigNames::AutoCreateTempUser, [ 'enabled' => true, 'invalid' => new NullJob( [] ) ]
			],
			'Config referenced by --settings is an object' => [
				[ 'settings' => 'wgTestConfig' ],
				'/Variable wgTestConfig includes non-array, non-scalar, items/',
				'TestConfig', new NullJob( [] ),
			],
		];
	}

	/** @dataProvider provideConfigVars */
	public function testExecuteForJSONFormat( $configName, $configValue ) {
		$this->overrideConfigValue( $configName, $configValue );
		$this->maintenance->setOption( 'format', 'json' );
		$this->maintenance->setOption( 'settings', 'wg' . $configName );
		$this->maintenance->validateParamsAndArgs();
		$this->maintenance->execute();
		$this->expectOutputString( FormatJson::encode( [ 'wg' . $configName => $configValue ] ) . "\n" );
	}

	public static function provideConfigVars() {
		return [
			'Config var set to boolean value' => [ MainConfigNames::NewUserLog, true ],
			'Config var set to an integer' => [ MainConfigNames::RCMaxAge, 12345 ],
			'Config var set to a string' => [ MainConfigNames::ServerName, 'test' ],
			'Config var set to an array' => [
				MainConfigNames::AutoCreateTempUser,
				[ 'enabled' => true, 'genPattern' => '~$1' ],
			],
		];
	}

	public function testExecuteForJSONFormatWithJSONPartialOutputOnError() {
		$this->overrideConfigValue(
			MainConfigNames::AutoCreateTempUser, [ 'enabled' => true, 'expireAfterDays' => INF ]
		);
		$this->maintenance->setOption( 'format', 'json' );
		$this->maintenance->setOption( 'settings', 'wgAutoCreateTempUser' );
		$this->maintenance->setOption( 'json-partial-output-on-error', 1 );
		$this->maintenance->validateParamsAndArgs();
		$this->maintenance->execute();
		$expectedJson = FormatJson::encode( [
			'wgAutoCreateTempUser' => [ 'enabled' => true, 'expireAfterDays' => 0 ],
			'wgGetConfigurationJsonErrorOccurred' => true,
		] );
		$this->expectOutputString( $expectedJson . "\n" );
	}

	public function testExecuteForJSONFormatWithRegexOption() {
		// Create three testing configuration values which will allow testing using regex.
		$this->overrideConfigValue( 'GetConfigurationTestabc1', true );
		$this->overrideConfigValue( 'GetconfigurationTestdef2', false );
		$this->overrideConfigValue( 'GetConfigurationTestxzy3', "test" );
		$this->maintenance->setOption( 'format', 'json' );
		$this->maintenance->setOption( 'iregex', 'getconfigurationtest.*\d' );
		$this->maintenance->validateParamsAndArgs();
		$this->maintenance->execute();
		// Validate that the JSON output is as expected.
		$actualJson = $this->getActualOutputForAssertion();
		$actualItems = FormatJson::decode( $actualJson, true );
		$this->assertArrayEquals(
			[
				'wgGetConfigurationTestabc1' => true,
				'wgGetConfigurationTestxzy3' => "test",
				'wgGetconfigurationTestdef2' => false,
			],
			$actualItems,
			false,
			true
		);
	}

	/** @dataProvider provideConfigVars */
	public function testForPHPFormat( $configName, $configValue ) {
		$this->overrideConfigValue( $configName, $configValue );
		$this->maintenance->setOption( 'format', 'php' );
		$this->maintenance->setOption( 'settings', 'wg' . $configName );
		$this->maintenance->validateParamsAndArgs();
		$this->maintenance->execute();
		$this->expectOutputString( serialize( [ 'wg' . $configName => $configValue ] ) . "\n" );
	}

	/** @dataProvider provideConfigVars */
	public function testForVarDumpFormat( $configName, $configValue ) {
		$this->overrideConfigValue( $configName, $configValue );
		$this->maintenance->setOption( 'format', 'vardump' );
		$this->maintenance->setOption( 'settings', 'wg' . $configName );
		$this->maintenance->validateParamsAndArgs();
		$this->maintenance->execute();
		$expectedOutputString = '$wg' . $configName . ' = ';
		// Get the config value in var_dump format
		ob_start();
		var_dump( $configValue );
		$expectedOutputString .= trim( ob_get_clean() );

		$this->expectOutputString( $expectedOutputString . ";\n" );
	}
}

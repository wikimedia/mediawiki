<?php

/**
 * @group API
 * @group Database
 * @group medium
 */
class ApiBaseTest extends ApiTestCase {

	/**
	 * @covers ApiBase::requireOnlyOneParameter
	 */
	public function testRequireOnlyOneParameterDefault() {
		$mock = new MockApi();
		$mock->requireOnlyOneParameter(
			[ "filename" => "foo.txt", "enablechunks" => false ],
			"filename", "enablechunks"
		);
		$this->assertTrue( true );
	}

	/**
	 * @expectedException UsageException
	 * @covers ApiBase::requireOnlyOneParameter
	 */
	public function testRequireOnlyOneParameterZero() {
		$mock = new MockApi();
		$mock->requireOnlyOneParameter(
			[ "filename" => "foo.txt", "enablechunks" => 0 ],
			"filename", "enablechunks"
		);
	}

	/**
	 * @expectedException UsageException
	 * @covers ApiBase::requireOnlyOneParameter
	 */
	public function testRequireOnlyOneParameterTrue() {
		$mock = new MockApi();
		$mock->requireOnlyOneParameter(
			[ "filename" => "foo.txt", "enablechunks" => true ],
			"filename", "enablechunks"
		);
	}

	/**
	 * @dataProvider provideGetParameterFromSettings
	 * @param string|null $input
	 * @param array $paramSettings
	 * @param mixed $expected
	 * @param string[] $warnings
	 */
	public function testGetParameterFromSettings( $input, $paramSettings, $expected, $warnings ) {
		$mock = new MockApi();
		$wrapper = TestingAccessWrapper::newFromObject( $mock );

		$context = new DerivativeContext( $mock );
		$context->setRequest( new FauxRequest( $input !== null ? [ 'foo' => $input ] : [] ) );
		$wrapper->mMainModule = new ApiMain( $context );

		if ( $expected instanceof UsageException ) {
			try {
				$wrapper->getParameterFromSettings( 'foo', $paramSettings, true );
			} catch ( UsageException $ex ) {
				$this->assertEquals( $expected, $ex );
			}
		} else {
			$result = $wrapper->getParameterFromSettings( 'foo', $paramSettings, true );
			$this->assertSame( $expected, $result );
			$this->assertSame( $warnings, $mock->warnings );
		}
	}

	public static function provideGetParameterFromSettings() {
		$warnings = [
			'The value passed for \'foo\' contains invalid or non-normalized data. Textual data should ' .
			'be valid, NFC-normalized Unicode without C0 control characters other than ' .
			'HT (\\t), LF (\\n), and CR (\\r).'
		];

		$c0 = '';
		$enc = '';
		for ( $i = 0; $i < 32; $i++ ) {
			$c0 .= chr( $i );
			$enc .= ( $i === 9 || $i === 10 || $i === 13 )
				? chr( $i )
				: '�';
		}

		return [
			'Basic param' => [ 'bar', null, 'bar', [] ],
			'Basic param, C0 controls' => [ $c0, null, $enc, $warnings ],
			'String param' => [ 'bar', '', 'bar', [] ],
			'String param, defaulted' => [ null, '', '', [] ],
			'String param, empty' => [ '', 'default', '', [] ],
			'String param, required, empty' => [
				'',
				[ ApiBase::PARAM_DFLT => 'default', ApiBase::PARAM_REQUIRED => true ],
				new UsageException( 'The foo parameter must be set', 'nofoo' ),
				[]
			],
			'Multi-valued parameter' => [
				'a|b|c',
				[ ApiBase::PARAM_ISMULTI => true ],
				[ 'a', 'b', 'c' ],
				[]
			],
			'Multi-valued parameter, alternative separator' => [
				"\x1fa|b\x1fc|d",
				[ ApiBase::PARAM_ISMULTI => true ],
				[ 'a|b', 'c|d' ],
				[]
			],
			'Multi-valued parameter, other C0 controls' => [
				$c0,
				[ ApiBase::PARAM_ISMULTI => true ],
				[ $enc ],
				$warnings
			],
			'Multi-valued parameter, other C0 controls (2)' => [
				"\x1f" . $c0,
				[ ApiBase::PARAM_ISMULTI => true ],
				[ substr( $enc, 0, -3 ), '' ],
				$warnings
			],
		];
	}

}

<?php

use Wikimedia\TestingAccessWrapper;

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
	 * @expectedException ApiUsageException
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
	 * @expectedException ApiUsageException
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

		if ( $expected instanceof ApiUsageException ) {
			try {
				$wrapper->getParameterFromSettings( 'foo', $paramSettings, true );
			} catch ( ApiUsageException $ex ) {
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
			[ 'apiwarn-badutf8', 'foo' ],
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
				ApiUsageException::newWithMessage( null, [ 'apierror-missingparam', 'foo' ] ),
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

	public function testErrorArrayToStatus() {
		$mock = new MockApi();

		// Sanity check empty array
		$expect = Status::newGood();
		$this->assertEquals( $expect, $mock->errorArrayToStatus( [] ) );

		// No blocked $user, so no special block handling
		$expect = Status::newGood();
		$expect->fatal( 'blockedtext' );
		$expect->fatal( 'autoblockedtext' );
		$expect->fatal( 'systemblockedtext' );
		$expect->fatal( 'mainpage' );
		$expect->fatal( 'parentheses', 'foobar' );
		$this->assertEquals( $expect, $mock->errorArrayToStatus( [
			[ 'blockedtext' ],
			[ 'autoblockedtext' ],
			[ 'systemblockedtext' ],
			'mainpage',
			[ 'parentheses', 'foobar' ],
		] ) );

		// Has a blocked $user, so special block handling
		$user = $this->getMutableTestUser()->getUser();
		$block = new \Block( [
			'address' => $user->getName(),
			'user' => $user->getID(),
			'reason' => __METHOD__,
			'expiry' => time() + 100500,
		] );
		$block->insert();
		$blockinfo = [ 'blockinfo' => ApiQueryUserInfo::getBlockInfo( $block ) ];

		$expect = Status::newGood();
		$expect->fatal( ApiMessage::create( 'apierror-blocked', 'blocked', $blockinfo ) );
		$expect->fatal( ApiMessage::create( 'apierror-autoblocked', 'autoblocked', $blockinfo ) );
		$expect->fatal( ApiMessage::create( 'apierror-systemblocked', 'blocked', $blockinfo ) );
		$expect->fatal( 'mainpage' );
		$expect->fatal( 'parentheses', 'foobar' );
		$this->assertEquals( $expect, $mock->errorArrayToStatus( [
			[ 'blockedtext' ],
			[ 'autoblockedtext' ],
			[ 'systemblockedtext' ],
			'mainpage',
			[ 'parentheses', 'foobar' ],
		], $user ) );
	}

}

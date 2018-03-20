<?php

use Wikimedia\TestingAccessWrapper;

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers ApiBase
 */
class ApiBaseTest extends ApiTestCase {
	/**
	 * This covers a variety of stub methods that return a fixed value.
	 *
	 * @param string|array $method Name of method, or [ name, params... ]
	 * @param string $value Expected value
	 *
	 * @dataProvider provideStubMethods
	 */
	public function testStubMethods( $method, $expected ) {
		$method = (array)$method;
		// Some of these are protected
		$mock = TestingAccessWrapper::newFromObject( new MockApi() );
		$result = call_user_func_array( [ $mock, array_shift( $method ) ],
			$method );
		$this->assertSame( $expected, $result );
	}

	public function provideStubMethods() {
		return [
			[ 'getModuleManager', null ],
			[ 'getCustomPrinter', null ],
			[ 'getHelpUrls', [] ],
			// XXX This is actually overriden by MockApi
			// [ 'getAllowedParams', [] ],
			[ 'shouldCheckMaxLag', true ],
			[ 'isReadMode', true ],
			[ 'isWriteMode', false ],
			[ 'mustBePosted', false ],
			[ 'isDeprecated', false ],
			[ 'isInternal', false ],
			[ 'needsToken', false ],
			[ [ 'getWebUITokenSalt', [] ], null ],
			[ [ 'getConditionalRequestData', 'etag' ], null ],
			[ 'dynamicParameterDocumentation', null ],
		];
	}

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
	 */
	public function testRequireOnlyOneParameterTrue() {
		$mock = new MockApi();
		$mock->requireOnlyOneParameter(
			[ "filename" => "foo.txt", "enablechunks" => true ],
			"filename", "enablechunks"
		);
	}

	public function testRequireOnlyOneParameterMissing() {
		$this->setExpectedException( ApiUsageException::class,
			'One of the parameters "filename" and "enablechunks" is required.' );
		$mock = new MockApi();
		$mock->requireOnlyOneParameter(
			[ "filenmae" => "foo.txt", "enbalechunks" => false ],
			"filename", "enablechunks" );
	}

	public function testRequireMaxOneParameterZero() {
		$mock = new MockApi();
		$mock->requireMaxOneParameter(
			[ 'foo' => 'bar', 'baz' => 'quz' ],
			'squirrel' );
		$this->assertTrue( true );
	}

	public function testRequireMaxOneParameterOne() {
		$mock = new MockApi();
		$mock->requireMaxOneParameter(
			[ 'foo' => 'bar', 'baz' => 'quz' ],
			'foo', 'squirrel' );
		$this->assertTrue( true );
	}

	public function testRequireMaxOneParameterTwo() {
		$this->setExpectedException( ApiUsageException::class,
			'The parameters "foo" and "baz" can not be used together.' );
		$mock = new MockApi();
		$mock->requireMaxOneParameter(
			[ 'foo' => 'bar', 'baz' => 'quz' ],
			'foo', 'baz' );
	}

	public function testRequireAtLeastOneParameterZero() {
		$this->setExpectedException( ApiUsageException::class,
			'At least one of the parameters "foo" and "bar" is required.' );
		$mock = new MockApi();
		$mock->requireAtLeastOneParameter(
			[ 'a' => 'b', 'c' => 'd' ],
			'foo', 'bar' );
	}

	public function testRequireAtLeastOneParameterOne() {
		$mock = new MockApi();
		$mock->requireAtLeastOneParameter(
			[ 'a' => 'b', 'c' => 'd' ],
			'foo', 'a' );
		$this->assertTrue( true );
	}

	public function testRequireAtLeastOneParameterTwo() {
		$mock = new MockApi();
		$mock->requireAtLeastOneParameter(
			[ 'a' => 'b', 'c' => 'd' ],
			'a', 'c' );
		$this->assertTrue( true );
	}

	public function testGetTitleOrPageIdBadParams() {
		$this->setExpectedException( ApiUsageException::class,
			'The parameters "title" and "pageid" can not be used together.' );
		$mock = new MockApi();
		$mock->getTitleOrPageId( [ 'title' => 'a', 'pageid' => 7 ] );
	}

	public function testGetTitleOrPageIdTitle() {
		$expected = WikiPage::factory( Title::newFromText( 'Aaaaaaaaaa' ) );
		$mock = new MockApi();
		$actual = $mock->getTitleOrPageId( [ 'title' => 'Aaaaaaaaaa' ] );
		$this->assertEquals( $expected, $actual );
	}

	public function testGetTitleOrPageIdInvalidTitle() {
		$this->setExpectedException( ApiUsageException::class,
			'Bad title "|".' );
		$mock = new MockApi();
		$mock->getTitleOrPageId( [ 'title' => '|' ] );
	}

	public function testGetTitleOrPageIdSpecialTitle() {
		$this->setExpectedException( ApiUsageException::class,
			"Namespace doesn't allow actual pages." );
		$mock = new MockApi();
		$mock->getTitleOrPageId( [ 'title' => 'Special:RandomPage' ] );
	}

	public function testGetTitleOrPageIdPageId() {
		// Presumably an id of 1 will always exist.
		$expected = WikiPage::newFromId( 1 );
		$actual = ( new MockApi() )->getTitleOrPageId( [ 'pageid' => 1 ] );
		$this->assertEquals( $expected, $actual );
	}

	public function testGetTitleOrPageIdInvalidPageId() {
		$this->setExpectedException( ApiUsageException::class,
			'There is no page with ID 298401643.' );
		$mock = new MockApi();
		$mock->getTitleOrPageId( [ 'pageid' => 298401643 ] );
	}

	public function testGetTitleFromTitleOrPageIdBadParams() {
		$this->setExpectedException( ApiUsageException::class,
			'The parameters "title" and "pageid" can not be used together.' );
		$mock = new MockApi();
		$mock->getTitleFromTitleOrPageId( [ 'title' => 'a', 'pageid' => 7 ] );
	}

	public function testGetTitleFromTitleOrPageIdTitle() {
		$expected = Title::newFromText( 'Aaaaaaaaaa' );
		$mock = new MockApi();
		$actual = $mock->getTitleFromTitleOrPageId( [ 'title' => 'Aaaaaaaaaa' ] );
		$this->assertEquals( $expected, $actual );
	}

	public function testGetTitleFromTitleOrPageIdInvalidTitle() {
		$this->setExpectedException( ApiUsageException::class,
			'Bad title "|".' );
		$mock = new MockApi();
		$mock->getTitleFromTitleOrPageId( [ 'title' => '|' ] );
	}

	public function testGetTitleFromTitleOrPageIdPageId() {
		// Presumably an id of 1 will always exist.
		$expected = Title::newFromId( 1 );
		$actual = ( new MockApi() )->getTitleFromTitleOrPageId( [ 'pageid' => 1 ] );
		$this->assertEquals( $expected, $actual );
	}

	public function testGetTitleFromTitleOrPageIdInvalidPageId() {
		$this->setExpectedException( ApiUsageException::class,
			'There is no page with ID 298401643.' );
		$mock = new MockApi();
		$mock->getTitleFromTitleOrPageId( [ 'pageid' => 298401643 ] );
	}

	/**
	 * @dataProvider provideGetParameterFromSettings
	 * @param string|null $input
	 * @param array $paramSettings
	 * @param mixed $expected
	 * @param string[] $warnings
	 */
	public function testGetParameterFromSettings( $input, $paramSettings,
		$expected, $warnings, $options = []
	) {
		$mock = new MockApi();
		$wrapper = TestingAccessWrapper::newFromObject( $mock );

		$context = new DerivativeContext( $mock );
		$context->setRequest( new FauxRequest(
			$input !== null ? [ 'myParam' => $input ] : [] ) );
		$wrapper->mMainModule = new ApiMain( $context );

		$parseLimits = isset( $options['parseLimits'] ) ?
			$options['parseLimits'] : true;

		// If we're testing tags, set up some tags
		if ( isset( $paramSettings[ApiBase::PARAM_TYPE] ) &&
			$paramSettings[ApiBase::PARAM_TYPE] === 'tags'
		) {
			ChangeTags::defineTag( 'tag1' );
			ChangeTags::defineTag( 'tag2' );
		}

		if ( $expected instanceof Exception ) {
			try {
				$wrapper->getParameterFromSettings( 'myParam', $paramSettings,
					$parseLimits );
				$this->fail();
			} catch ( Exception $ex ) {
				$this->assertEquals( $expected, $ex );
			}
		} else {
			$result = $wrapper->getParameterFromSettings( 'myParam',
				$paramSettings, $parseLimits );
			if ( isset( $paramSettings[ApiBase::PARAM_TYPE] ) &&
				$paramSettings[ApiBase::PARAM_TYPE] === 'timestamp' &&
				$expected === 'now'
			) {
				// Allow one second of fuzziness.  Make sure the formats are
				// correct!
				$this->assertRegExp( '/^\d{14}$/', $result );
				$this->assertLessThanOrEqual( 1,
					abs( wfTimestamp( TS_UNIX, $result ) - time() ),
					"Result $result differs from expected $expected by " .
					'more than one second' );
			} else {
				$this->assertSame( $expected, $result );
			}
			if ( count( array_filter( $warnings,
					function ( $warning ) {
						return $warning instanceof ApiMessage;
					}
				) ) > 0
			) {
				// Clumsy, but assertSame doesn't work for Messages
				$this->assertEquals( $warnings, $mock->warnings );
			} else {
				$this->assertSame( $warnings, $mock->warnings );
			}
		}

		if ( !empty( $paramSettings[ApiBase::PARAM_SENSITIVE] ) ||
			( isset( $paramSettings[ApiBase::PARAM_TYPE] ) &&
			$paramSettings[ApiBase::PARAM_TYPE] === 'password' )
		) {
			$mainWrapper = TestingAccessWrapper::newFromObject( $wrapper->getMain() );
			$this->assertSame( [ 'myParam' ],
				$mainWrapper->getSensitiveParams() );
		}
	}

	public static function provideGetParameterFromSettings() {
		$warnings = [
			[ 'apiwarn-badutf8', 'myParam' ],
		];

		$c0 = '';
		$enc = '';
		for ( $i = 0; $i < 32; $i++ ) {
			$c0 .= chr( $i );
			$enc .= ( $i === 9 || $i === 10 || $i === 13 )
				? chr( $i )
				: '�';
		}

		$returnArray = [
			'Basic param' => [ 'bar', null, 'bar', [] ],
			'Basic param, C0 controls' => [ $c0, null, $enc, $warnings ],
			'String param' => [ 'bar', '', 'bar', [] ],
			'String param, defaulted' => [ null, '', '', [] ],
			'String param, empty' => [ '', 'default', '', [] ],
			'String param, required, empty' => [
				'',
				[ ApiBase::PARAM_DFLT => 'default', ApiBase::PARAM_REQUIRED => true ],
				ApiUsageException::newWithMessage( null,
					[ 'apierror-missingparam', 'myParam' ] ),
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
			'Multi-valued parameter with limits' => [
				'a|b|c',
				[ ApiBase::PARAM_ISMULTI => true,
					ApiBase::PARAM_ISMULTI_LIMIT1 => 3 ],
				[ 'a', 'b', 'c' ],
				[],
			],
			'Multi-valued parameter with exceeded limits' => [
				'a|b|c',
				[ ApiBase::PARAM_ISMULTI => true,
					ApiBase::PARAM_ISMULTI_LIMIT1 => 2 ],
				[ 'a', 'b' ],
				[ [ 'apiwarn-toomanyvalues', 'myParam', 2 ] ],
			],
			'Multi-valued parameter with prohibited duplicates' => [
				'a|b|a|c',
				[ ApiBase::PARAM_ISMULTI => true ],
				// Note that the keys are not sequential!  This matches
				// array_unique, but might be unexpected.
				[ 0 => 'a', 1 => 'b', 3 => 'c' ],
				[],
			],
			'Multi-valued parameter with allowed duplicates' => [
				'a|a',
				[ ApiBase::PARAM_ISMULTI => true,
					ApiBase::PARAM_ALLOW_DUPLICATES => true ],
				[ 'a', 'a' ],
				[],
			],
			// @todo How to get apihighlimits here?
			'Empty boolean param' => [
				'',
				[ ApiBase::PARAM_TYPE => 'boolean' ],
				true,
				[],
			],
			'Boolean param 0' => [
				'0',
				[ ApiBase::PARAM_TYPE => 'boolean' ],
				true,
				[],
			],
			'Boolean param false' => [
				'false',
				[ ApiBase::PARAM_TYPE => 'boolean' ],
				true,
				[],
			],
			'Boolean multi-param' => [
				'true|false',
				[ ApiBase::PARAM_TYPE => 'boolean',
					ApiBase::PARAM_ISMULTI => true ],
				new MWException(
					'Internal error in ApiBase::getParameterFromSettings: ' .
					'Multi-values not supported for myParam' ),
				[],
			],
			'Empty boolean param with non-false default' => [
				'',
				[ ApiBase::PARAM_TYPE => 'boolean',
					ApiBase::PARAM_DFLT => true ],
				new MWException(
					'Internal error in ApiBase::getParameterFromSettings: ' .
					"Boolean param myParam's default is set to '1'. " .
					'Boolean parameters must default to false.' ),
				[],
			],
			'Deprecated parameter' => [
				'foo',
				[ ApiBase::PARAM_DEPRECATED => true ],
				'foo',
				[ [ 'apiwarn-deprecation-parameter', 'myParam' ] ],
			],
			'Deprecated parameter value' => [
				'a',
				[ ApiBase::PARAM_DEPRECATED_VALUES => [ 'a' => true ] ],
				'a',
				[ [ 'apiwarn-deprecation-parameter', 'myParam=a' ] ],
			],
			'Multiple deprecated parameter values' => [
				'a|b|c|d',
				[ ApiBase::PARAM_DEPRECATED_VALUES =>
					[ 'b' => true, 'd' => true ],
					ApiBase::PARAM_ISMULTI => true ],
				[ 'a', 'b', 'c', 'd' ],
				[ [ 'apiwarn-deprecation-parameter', 'myParam=b' ],
					[ 'apiwarn-deprecation-parameter', 'myParam=d' ] ],
			],
			'Deprecated parameter value with custom warning' => [
				'a',
				[ ApiBase::PARAM_DEPRECATED_VALUES => [ 'a' => 'my-msg' ] ],
				'a',
				[ 'my-msg' ],
			],
			'"*" when wildcard not allowed' => [
				'*',
				[ ApiBase::PARAM_ISMULTI => true,
					ApiBase::PARAM_TYPE => [ 'a', 'b', 'c' ] ],
				[],
				[ [ 'apiwarn-unrecognizedvalues', 'myParam',
					[ 'list' => [ '&#42;' ], 'type' => 'comma' ], 1 ] ],
			],
			'Wildcard "*"' => [
				'*',
				[ ApiBase::PARAM_ISMULTI => true,
					ApiBase::PARAM_TYPE => [ 'a', 'b', 'c' ],
					ApiBase::PARAM_ALL => true ],
				[ 'a', 'b', 'c' ],
				[],
			],
			'Wildcard "*" with multiples not allowed' => [
				'*',
				[ ApiBase::PARAM_TYPE => [ 'a', 'b', 'c' ],
					ApiBase::PARAM_ALL => true ],
				ApiUsageException::newWithMessage( null,
					[ 'apierror-unrecognizedvalue', 'myParam', '&#42;' ],
					'unknown_myParam' ),
				[],
			],
			'Wildcard "*" with unrestricted type' => [
				'*',
				[ ApiBase::PARAM_ISMULTI => true,
					ApiBase::PARAM_ALL => true ],
				[ '*' ],
				[],
			],
			'Wildcard "x"' => [
				'x',
				[ ApiBase::PARAM_ISMULTI => true,
					ApiBase::PARAM_TYPE => [ 'a', 'b', 'c' ],
					ApiBase::PARAM_ALL => 'x' ],
				[ 'a', 'b', 'c' ],
				[],
			],
			'Wildcard conflicting with allowed value' => [
				'a',
				[ ApiBase::PARAM_ISMULTI => true,
					ApiBase::PARAM_TYPE => [ 'a', 'b', 'c' ],
					ApiBase::PARAM_ALL => 'a' ],
				new MWException(
					'Internal error in ApiBase::getParameterFromSettings: ' .
					'For param myParam, PARAM_ALL collides with a possible ' .
					'value' ),
				[],
			],
			'Namespace with wildcard' => [
				'*',
				[ ApiBase::PARAM_ISMULTI => true,
					ApiBase::PARAM_TYPE => 'namespace' ],
				MWNamespace::getValidNamespaces(),
				[],
			],
			// PARAM_ALL is ignored with namespace types.
			'Namespace with wildcard suppressed' => [
				'*',
				[ ApiBase::PARAM_ISMULTI => true,
					ApiBase::PARAM_TYPE => 'namespace',
					ApiBase::PARAM_ALL => false ],
				MWNamespace::getValidNamespaces(),
				[],
			],
			'Namespace with wildcard "x"' => [
				'x',
				[ ApiBase::PARAM_ISMULTI => true,
					ApiBase::PARAM_TYPE => 'namespace',
					ApiBase::PARAM_ALL => 'x' ],
				// @todo Is this behavior desired, or should PARAM_ALL be
				// honored?
				[],
				[ [ 'apiwarn-unrecognizedvalues', 'myParam',
					[ 'list' => [ 'x' ], 'type' => 'comma' ], 1 ] ],
			],
			'Password' => [
				'dDy+G?e?txnr.1:(@[Ru',
				[ ApiBase::PARAM_TYPE => 'password' ],
				'dDy+G?e?txnr.1:(@[Ru',
				[],
			],
			'Sensitive field' => [
				'I am fond of pineapples',
				[ ApiBase::PARAM_SENSITIVE => true ],
				'I am fond of pineapples',
				[],
			],
			'Upload with default' => [
				'',
				[ ApiBase::PARAM_TYPE => 'upload',
					ApiBase::PARAM_DFLT => '' ],
				new MWException(
					'Internal error in ApiBase::getParameterFromSettings: ' .
					"File upload param myParam's default is set to ''. " .
					'File upload parameters may not have a default.'),
				[],
			],
			'Multiple upload' => [
				'',
				[ ApiBase::PARAM_TYPE => 'upload',
					ApiBase::PARAM_ISMULTI => true ],
				new MWException(
					'Internal error in ApiBase::getParameterFromSettings: ' .
					'Multi-values not supported for myParam'),
				[],
			],
			// @todo Test actual upload
			'Namespace -1' => [
				'-1',
				[ ApiBase::PARAM_TYPE => 'namespace' ],
				ApiUsageException::newWithMessage( null,
					[ 'apierror-unrecognizedvalue', 'myParam', '-1' ],
					'unknown_myParam' ),
				[],
			],
			'Extra namespace -1' => [
				'-1',
				[ ApiBase::PARAM_TYPE => 'namespace',
					ApiBase::PARAM_EXTRA_NAMESPACES => [ '-1' ] ],
				'-1',
				[],
			],
			// @todo Test with PARAM_SUBMODULE_MAP unset, need
			// getModuleManager() to return something real
			'Nonexistent module' => [
				'not-a-module-name',
				[ ApiBase::PARAM_TYPE => 'submodule',
					ApiBase::PARAM_SUBMODULE_MAP =>
					[ 'foo' => 'Foo.php', 'bar' => 'bar/Bar.php' ] ],
				ApiUsageException::newWithMessage( null,
					[ 'apierror-unrecognizedvalue', 'myParam',
					'not-a-module-name' ], 'unknown_myParam' ),
				[],
			],
			'\\x1f with multiples not allowed' => [
				"\x1f",
				[],
				ApiUsageException::newWithMessage( null,
					'apierror-badvalue-notmultivalue',
					'badvalue_notmultivalue' ),
				[],
			],
			'integer with unenforced min' => [
				'-2',
				[ ApiBase::PARAM_TYPE => 'integer',
					ApiBase::PARAM_MIN => -1 ],
				-1,
				[ ApiMessage::create(
					[ 'apierror-integeroutofrange-belowminimum', 'myParam',
					'-1', '-2' ], 'integeroutofrange',
					[ 'min' => -1, 'max' => null, 'botMax' => null ] ) ],
			],
			'integer with enforced min' => [
				'-2',
				[ ApiBase::PARAM_TYPE => 'integer',
					ApiBase::PARAM_MIN => -1,
					ApiBase::PARAM_RANGE_ENFORCE => true ],
				ApiUsageException::newWithMessage( null,
					[ 'apierror-integeroutofrange-belowminimum', 'myParam',
					'-1', '-2' ], 'integeroutofrange',
					[ 'min' => -1, 'max' => null, 'botMax' => null ] ),
				[],
			],
			// @todo We're running in internal mode, so max is not honored.
			// Need to test in non-internal mode also.
			'integer with unenforced max (internal mode)' => [
				'8',
				[ ApiBase::PARAM_TYPE => 'integer',
					ApiBase::PARAM_MAX => 7 ],
				8,
				[],
			],
			'integer with enforced max (internal mode)' => [
				'8',
				[ ApiBase::PARAM_TYPE => 'integer',
					ApiBase::PARAM_MAX => 7,
					ApiBase::PARAM_RANGE_ENFORCE => true ],
				8,
				[],
			],
			//'integer with unenforced max' => [
			//	'8',
			//	[ ApiBase::PARAM_TYPE => 'integer',
			//		ApiBase::PARAM_MAX => 7 ],
			//	7,
			//	[ ApiMessage::create(
			//		[ 'apierror-integeroutofrange-belowminimum', 'myParam',
			//		'7', '8' ], 'integeroutofrange',
			//		[ 'min' => null, 'max' => 7, 'botMax' => 7 ] ) ],
			//],
			//'integer with enforced max' => [
			//	'8',
			//	[ ApiBase::PARAM_TYPE => 'integer',
			//		ApiBase::PARAM_MAX => 7,
			//		ApiBase::PARAM_RANGE_ENFORCE => true ],
			//	ApiUsageException::newWithMessage( null,
			//		[ 'apierror-integeroutofrange-belowminimum', 'myParam',
			//		'7', '8' ], 'integeroutofrange',
			//		[ 'min' => null, 'max' => 7, 'botMax' => null ] ),
			//	[],
			//],
			'limit with parseLimits false' => [
				'100',
				[ ApiBase::PARAM_TYPE => 'limit' ],
				// @todo We don't even convert to integer, is this right?
				'100',
				[],
				[ 'parseLimits' => false ],
			],
			'limit with no max' => [
				'100',
				[ ApiBase::PARAM_TYPE => 'limit',
					ApiBase::PARAM_MAX2 => 10,
					ApiBase::PARAM_ISMULTI => true],
				new MWException(
					'Internal error in ApiBase::getParameterFromSettings: ' .
					'MAX1 or MAX2 are not defined for the limit myParam' ),
				[],
			],
			'limit with no max2' => [
				'100',
				[ ApiBase::PARAM_TYPE => 'limit',
					ApiBase::PARAM_MAX => 10,
					ApiBase::PARAM_ISMULTI => true ],
				new MWException(
					'Internal error in ApiBase::getParameterFromSettings: ' .
					'MAX1 or MAX2 are not defined for the limit myParam' ),
				[],
			],
			'limit with no max2' => [
				'100',
				[ ApiBase::PARAM_TYPE => 'limit',
					ApiBase::PARAM_MAX => 10,
					ApiBase::PARAM_MAX2 => 10,
					ApiBase::PARAM_ISMULTI => true ],
				new MWException(
					'Internal error in ApiBase::getParameterFromSettings: ' .
					'Multi-values not supported for myParam' ),
				[],
			],
			// @todo How to test max2 separately?
			// @todo Perhaps we should have an internal error for min > max?
			'valid limit' => [
				'100',
				[ ApiBase::PARAM_TYPE => 'limit',
					ApiBase::PARAM_MIN => 100,
					ApiBase::PARAM_MAX => 100,
					ApiBase::PARAM_MAX2 => 99 ],
				100,
				[],
			],
			'limit max' => [
				'max',
				[ ApiBase::PARAM_TYPE => 'limit',
					ApiBase::PARAM_MAX => 100,
					ApiBase::PARAM_MAX2 => 99 ],
				100,
				[],
			],
			// @todo Need to test too-large values in non-internal mode
			'limit too large (internal mode)' => [
				'101',
				[ ApiBase::PARAM_TYPE => 'limit',
					ApiBase::PARAM_MIN => 101,
					ApiBase::PARAM_MAX => 100,
					ApiBase::PARAM_MAX2 => 99 ],
				101,
				[],
			],
			'limit too small' => [
				'-2',
				[ ApiBase::PARAM_TYPE => 'limit',
					ApiBase::PARAM_MIN => -1,
					ApiBase::PARAM_MAX => -3,
					ApiBase::PARAM_MAX2 => -3 ],
				-1,
				[ ApiMessage::create(
					[ 'apierror-integeroutofrange-belowminimum', 'myParam',
					'-1', '-2' ], 'integeroutofrange',
					[ 'min' => -1, 'max' => -3, 'botMax' => -3 ] ) ],
			],
			'timestamp' => [
				wfTimestamp( TS_UNIX, '20211221122112' ),
				[ ApiBase::PARAM_TYPE => 'timestamp' ],
				'20211221122112',
				[],
			],
			'timestamp 0' => [
				'0',
				[ ApiBase::PARAM_TYPE => 'timestamp' ],
				// Magic keyword
				'now',
				[ [ 'apiwarn-unclearnowtimestamp', 'myParam', '0' ] ],
			],
			'timestamp empty' => [
				'',
				[ ApiBase::PARAM_TYPE => 'timestamp' ],
				'now',
				[ [ 'apiwarn-unclearnowtimestamp', 'myParam', '' ] ],
			],
			// wfTimestamp() interprets this as Unix time
			'timestamp 00' => [
				'00',
				[ ApiBase::PARAM_TYPE => 'timestamp' ],
				'19700101000000',
				[],
			],
			'timestamp now' => [
				'now',
				[ ApiBase::PARAM_TYPE => 'timestamp' ],
				'now',
				[],
			],
			'invalid timestamp' => [
				'squirrel',
				[ ApiBase::PARAM_TYPE => 'timestamp' ],
				ApiUsageException::newWithMessage( null,
					[ 'apierror-badtimestamp', 'myParam',
					'squirrel' ], 'badtimestamp_myParam' ),
				[],
			],
			'timestamp array' => [
				'100|101',
				[ ApiBase::PARAM_TYPE => 'timestamp',
					ApiBase::PARAM_ISMULTI => 1 ],
				[ wfTimestamp( TS_MW, 100 ), wfTimestamp( TS_MW, 101 ) ],
				[],
			],
			// @todo External usernames
			'user' => [
				'foo_bar',
				[ ApiBase::PARAM_TYPE => 'user' ],
				'Foo bar',
				[],
			],
			'Invalid username' => [
				'|',
				[ ApiBase::PARAM_TYPE => 'user' ],
				ApiUsageException::newWithMessage( null,
					[ 'apierror-baduser', 'myParam', '&#124;' ],
					'baduser_myParam' ),
				[],
			],
			'Array of usernames' => [
				'foo|bar',
				[ ApiBase::PARAM_TYPE => 'user',
					ApiBase::PARAM_ISMULTI => true ],
				[ 'Foo', 'Bar' ],
				[],
			],
			'tag' => [
				'tag1',
				[ ApiBase::PARAM_TYPE => 'tags' ],
				[ 'tag1' ],
				[],
			],
			'Array of one tag' => [
				'tag1',
				[ ApiBase::PARAM_TYPE => 'tags',
					ApiBase::PARAM_ISMULTI => true ],
				[ 'tag1' ],
				[],
			],
			'Array of tags' => [
				'tag1|tag2',
				[ ApiBase::PARAM_TYPE => 'tags',
					ApiBase::PARAM_ISMULTI => true ],
				[ 'tag1', 'tag2' ],
				[],
			],
			'Invalid tag' => [
				'invalid tag',
				[ ApiBase::PARAM_TYPE => 'tags' ],
				new ApiUsageException( null,
					Status::newFatal( 'tags-apply-not-allowed-one',
					'invalid tag', 1 ) ),
				[],
			],
			'Unrecognized type' => [
				'foo',
				[ ApiBase::PARAM_TYPE => 'nonexistenttype' ],
				new MWException(
					'Internal error in ApiBase::getParameterFromSettings: ' .
					"Param myParam's type is unknown - nonexistenttype" ),
				[],
			],
			'Too many bytes' => [
				'1',
				[ ApiBase::PARAM_MAX_BYTES => 0,
					ApiBase::PARAM_MAX_CHARS => 0],
				ApiUsageException::newWithMessage( null,
					[ 'apierror-maxbytes', 'myParam', 0 ] ),
				[],
			],
			'Too many chars' => [
				'§§',
				[ ApiBase::PARAM_MAX_BYTES => 4,
					ApiBase::PARAM_MAX_CHARS => 1 ],
				ApiUsageException::newWithMessage( null,
					[ 'apierror-maxchars', 'myParam', 1 ] ),
				[],
			],
			'Omitted required param' => [
				null,
				[ ApiBase::PARAM_REQUIRED => true ],
				ApiUsageException::newWithMessage( null,
					[ 'apierror-missingparam', 'myParam' ] ),
				[],
			],
		];

		// The following really just test PHP's string-to-int conversion.
		$integerTests = [
			[ '+1', 1 ],
			[ '-1', -1 ],
			[ '1e3', 1 ],
			[ '1.5', 1 ],
			[ '-1.5', -1 ],
			[ '1abc', 1 ],
			[ ' 1', 1 ],
			[ "\t1", 1, '\t1' ],
			[ "\r1", 1, '\r1' ],
			// @todo Why are these bad UTF-8?  They're ASCII!
			[ "\f1", 0, '\f1', 'badutf-8' ],
			[ "\n1", 1, '\n1' ],
			[ "\v1", 0, '\v1', 'badutf-8' ],
			[ "\e1", 0, '\e1', 'badutf-8' ],
			[ "\x001", 0, '\x001', 'badutf-8' ],
		];

		foreach ( $integerTests as $test ) {
			$desc = isset( $test[2] ) ? $test[2] : $test[0];
			$warnings = isset( $test[3] ) ?
				[ [ 'apiwarn-badutf8', 'myParam' ] ] : [];
			$returnArray["\"$desc\" as integer"] = [
				$test[0],
				[ ApiBase::PARAM_TYPE => 'integer' ],
				$test[1],
				$warnings,
			];
		}

		return $returnArray;
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
			'by' => $this->getTestSysop()->getUser()->getId(),
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

	public function testDieStatus() {
		$mock = new MockApi();

		$status = StatusValue::newGood();
		$status->error( 'foo' );
		$status->warning( 'bar' );
		try {
			$mock->dieStatus( $status );
			$this->fail( 'Expected exception not thrown' );
		} catch ( ApiUsageException $ex ) {
			$this->assertTrue( ApiTestCase::apiExceptionHasCode( $ex, 'foo' ), 'Exception has "foo"' );
			$this->assertFalse( ApiTestCase::apiExceptionHasCode( $ex, 'bar' ), 'Exception has "bar"' );
		}

		$status = StatusValue::newGood();
		$status->warning( 'foo' );
		$status->warning( 'bar' );
		try {
			$mock->dieStatus( $status );
			$this->fail( 'Expected exception not thrown' );
		} catch ( ApiUsageException $ex ) {
			$this->assertTrue( ApiTestCase::apiExceptionHasCode( $ex, 'foo' ), 'Exception has "foo"' );
			$this->assertTrue( ApiTestCase::apiExceptionHasCode( $ex, 'bar' ), 'Exception has "bar"' );
		}

		$status = StatusValue::newGood();
		$status->setOk( false );
		try {
			$mock->dieStatus( $status );
			$this->fail( 'Expected exception not thrown' );
		} catch ( ApiUsageException $ex ) {
			$this->assertTrue( ApiTestCase::apiExceptionHasCode( $ex, 'unknownerror-nocode' ),
				'Exception has "unknownerror-nocode"' );
		}
	}

}

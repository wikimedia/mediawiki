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
	public function testStubMethods( $expected, $method, $args = [] ) {
		// Some of these are protected
		$mock = TestingAccessWrapper::newFromObject( new MockApi() );
		$result = call_user_func_array( [ $mock, $method ], $args );
		$this->assertSame( $expected, $result );
	}

	public function provideStubMethods() {
		return [
			[ null, 'getModuleManager' ],
			[ null, 'getCustomPrinter' ],
			[ [], 'getHelpUrls' ],
			// @todo This is actually overriden by MockApi
			// [ [], 'getAllowedParams' ],
			[ true, 'shouldCheckMaxLag' ],
			[ true, 'isReadMode' ],
			[ false, 'isWriteMode' ],
			[ false, 'mustBePosted' ],
			[ false, 'isDeprecated' ],
			[ false, 'isInternal' ],
			[ false, 'needsToken' ],
			[ null, 'getWebUITokenSalt', [ [] ] ],
			[ null, 'getConditionalRequestData', [ 'etag' ] ],
			[ null, 'dynamicParameterDocumentation' ],
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
			'One of the parameters "foo" and "bar" is required.' );
		$mock = new MockApi();
		$mock->requireOnlyOneParameter(
			[ "filename" => "foo.txt", "enablechunks" => false ],
			"foo", "bar" );
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
		$mock = new MockApi();
		$result = $mock->getTitleOrPageId( [ 'title' => 'Foo' ] );
		$this->assertInstanceOf( WikiPage::class, $result );
		$this->assertSame( 'Foo', $result->getTitle()->getPrefixedText() );
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
		$page = $this->getExistingTestPage();
		$result = ( new MockApi() )->getTitleOrPageId(
			[ 'pageid' => $page->getId() ] );
		$this->assertInstanceOf( WikiPage::class, $result );
		$this->assertSame(
			$page->getTitle()->getPrefixedText(),
			$result->getTitle()->getPrefixedText()
		);
	}

	public function testGetTitleOrPageIdInvalidPageId() {
		// FIXME: fails under postgres
		$this->markTestSkippedIfDbType( 'postgres' );

		$this->setExpectedException( ApiUsageException::class,
			'There is no page with ID 2147483648.' );
		$mock = new MockApi();
		$mock->getTitleOrPageId( [ 'pageid' => 2147483648 ] );
	}

	public function testGetTitleFromTitleOrPageIdBadParams() {
		$this->setExpectedException( ApiUsageException::class,
			'The parameters "title" and "pageid" can not be used together.' );
		$mock = new MockApi();
		$mock->getTitleFromTitleOrPageId( [ 'title' => 'a', 'pageid' => 7 ] );
	}

	public function testGetTitleFromTitleOrPageIdTitle() {
		$mock = new MockApi();
		$result = $mock->getTitleFromTitleOrPageId( [ 'title' => 'Foo' ] );
		$this->assertInstanceOf( Title::class, $result );
		$this->assertSame( 'Foo', $result->getPrefixedText() );
	}

	public function testGetTitleFromTitleOrPageIdInvalidTitle() {
		$this->setExpectedException( ApiUsageException::class,
			'Bad title "|".' );
		$mock = new MockApi();
		$mock->getTitleFromTitleOrPageId( [ 'title' => '|' ] );
	}

	public function testGetTitleFromTitleOrPageIdPageId() {
		$page = $this->getExistingTestPage();
		$result = ( new MockApi() )->getTitleFromTitleOrPageId(
			[ 'pageid' => $page->getId() ] );
		$this->assertInstanceOf( Title::class, $result );
		$this->assertSame( $page->getTitle()->getPrefixedText(), $result->getPrefixedText() );
	}

	public function testGetTitleFromTitleOrPageIdInvalidPageId() {
		$this->setExpectedException( ApiUsageException::class,
			'There is no page with ID 298401643.' );
		$mock = new MockApi();
		$mock->getTitleFromTitleOrPageId( [ 'pageid' => 298401643 ] );
	}

	public function testGetParameter() {
		$mock = $this->getMockBuilder( MockApi::class )
			->setMethods( [ 'getAllowedParams' ] )
			->getMock();
		$mock->method( 'getAllowedParams' )->willReturn( [
			'foo' => [
				ApiBase::PARAM_TYPE => [ 'value' ],
			],
			'bar' => [
				ApiBase::PARAM_TYPE => [ 'value' ],
			],
		] );
		$wrapper = TestingAccessWrapper::newFromObject( $mock );

		$context = new DerivativeContext( $mock );
		$context->setRequest( new FauxRequest( [ 'foo' => 'bad', 'bar' => 'value' ] ) );
		$wrapper->mMainModule = new ApiMain( $context );

		// Even though 'foo' is bad, getParameter( 'bar' ) must not fail
		$this->assertSame( 'value', $wrapper->getParameter( 'bar' ) );

		// But getParameter( 'foo' ) must throw.
		try {
			$wrapper->getParameter( 'foo' );
			$this->fail( 'Expected exception not thrown' );
		} catch ( ApiUsageException $ex ) {
			$this->assertTrue( $this->apiExceptionHasCode( $ex, 'unknown_foo' ) );
		}

		// And extractRequestParams() must throw too.
		try {
			$mock->extractRequestParams();
			$this->fail( 'Expected exception not thrown' );
		} catch ( ApiUsageException $ex ) {
			$this->assertTrue( $this->apiExceptionHasCode( $ex, 'unknown_foo' ) );
		}
	}

	/**
	 * @param string|null $input
	 * @param array $paramSettings
	 * @param mixed $expected
	 * @param array $options Key-value pairs:
	 *   'parseLimits': true|false
	 *   'apihighlimits': true|false
	 *   'internalmode': true|false
	 *   'prefix': true|false
	 * @param string[] $warnings
	 */
	private function doGetParameterFromSettings(
		$input, $paramSettings, $expected, $warnings, $options = []
	) {
		$mock = new MockApi();
		$wrapper = TestingAccessWrapper::newFromObject( $mock );
		if ( $options['prefix'] ) {
			$wrapper->mModulePrefix = 'my';
			$paramName = 'Param';
		} else {
			$paramName = 'myParam';
		}

		$context = new DerivativeContext( $mock );
		$context->setRequest( new FauxRequest(
			$input !== null ? [ 'myParam' => $input ] : [] ) );
		$wrapper->mMainModule = new ApiMain( $context );

		$parseLimits = $options['parseLimits'] ?? true;

		if ( !empty( $options['apihighlimits'] ) ) {
			$context->setUser( self::$users['sysop']->getUser() );
		}

		if ( isset( $options['internalmode'] ) && !$options['internalmode'] ) {
			$mainWrapper = TestingAccessWrapper::newFromObject( $wrapper->mMainModule );
			$mainWrapper->mInternalMode = false;
		}

		// If we're testing tags, set up some tags
		if ( isset( $paramSettings[ApiBase::PARAM_TYPE] ) &&
			$paramSettings[ApiBase::PARAM_TYPE] === 'tags'
		) {
			ChangeTags::defineTag( 'tag1' );
			ChangeTags::defineTag( 'tag2' );
		}

		if ( $expected instanceof Exception ) {
			try {
				$wrapper->getParameterFromSettings( $paramName, $paramSettings,
					$parseLimits );
				$this->fail( 'No exception thrown' );
			} catch ( Exception $ex ) {
				$this->assertEquals( $expected, $ex );
			}
		} else {
			$result = $wrapper->getParameterFromSettings( $paramName,
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
			$actualWarnings = array_map( function ( $warn ) {
				return $warn instanceof Message
					? array_merge( [ $warn->getKey() ], $warn->getParams() )
					: $warn;
			}, $mock->warnings );
			$this->assertSame( $warnings, $actualWarnings );
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

	/**
	 * @dataProvider provideGetParameterFromSettings
	 * @see self::doGetParameterFromSettings()
	 */
	public function testGetParameterFromSettings_noprefix(
		$input, $paramSettings, $expected, $warnings, $options = []
	) {
		$options['prefix'] = false;
		$this->doGetParameterFromSettings( $input, $paramSettings, $expected, $warnings, $options );
	}

	/**
	 * @dataProvider provideGetParameterFromSettings
	 * @see self::doGetParameterFromSettings()
	 */
	public function testGetParameterFromSettings_prefix(
		$input, $paramSettings, $expected, $warnings, $options = []
	) {
		$options['prefix'] = true;
		$this->doGetParameterFromSettings( $input, $paramSettings, $expected, $warnings, $options );
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
				[
					ApiBase::PARAM_ISMULTI => true,
					ApiBase::PARAM_ISMULTI_LIMIT1 => 3,
				],
				[ 'a', 'b', 'c' ],
				[],
			],
			'Multi-valued parameter with exceeded limits' => [
				'a|b|c',
				[
					ApiBase::PARAM_ISMULTI => true,
					ApiBase::PARAM_ISMULTI_LIMIT1 => 2,
				],
				ApiUsageException::newWithMessage(
					null, [ 'apierror-toomanyvalues', 'myParam', 2 ], 'too-many-myParam'
				),
				[]
			],
			'Multi-valued parameter with exceeded limits for non-bot' => [
				'a|b|c',
				[
					ApiBase::PARAM_ISMULTI => true,
					ApiBase::PARAM_ISMULTI_LIMIT1 => 2,
					ApiBase::PARAM_ISMULTI_LIMIT2 => 3,
				],
				ApiUsageException::newWithMessage(
					null, [ 'apierror-toomanyvalues', 'myParam', 2 ], 'too-many-myParam'
				),
				[]
			],
			'Multi-valued parameter with non-exceeded limits for bot' => [
				'a|b|c',
				[
					ApiBase::PARAM_ISMULTI => true,
					ApiBase::PARAM_ISMULTI_LIMIT1 => 2,
					ApiBase::PARAM_ISMULTI_LIMIT2 => 3,
				],
				[ 'a', 'b', 'c' ],
				[],
				[ 'apihighlimits' => true ],
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
				[
					ApiBase::PARAM_ISMULTI => true,
					ApiBase::PARAM_ALLOW_DUPLICATES => true,
				],
				[ 'a', 'a' ],
				[],
			],
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
				[
					ApiBase::PARAM_TYPE => 'boolean',
					ApiBase::PARAM_ISMULTI => true,
				],
				new MWException(
					'Internal error in ApiBase::getParameterFromSettings: ' .
					'Multi-values not supported for myParam'
				),
				[],
			],
			'Empty boolean param with non-false default' => [
				'',
				[
					ApiBase::PARAM_TYPE => 'boolean',
					ApiBase::PARAM_DFLT => true,
				],
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
			'Deprecated parameter with default, unspecified' => [
				null,
				[ ApiBase::PARAM_DEPRECATED => true, ApiBase::PARAM_DFLT => 'foo' ],
				'foo',
				[],
			],
			'Deprecated parameter with default, specified' => [
				'foo',
				[ ApiBase::PARAM_DEPRECATED => true, ApiBase::PARAM_DFLT => 'foo' ],
				'foo',
				[ [ 'apiwarn-deprecation-parameter', 'myParam' ] ],
			],
			'Deprecated parameter value' => [
				'a',
				[ ApiBase::PARAM_DEPRECATED_VALUES => [ 'a' => true ] ],
				'a',
				[ [ 'apiwarn-deprecation-parameter', 'myParam=a' ] ],
			],
			'Deprecated parameter value as default, unspecified' => [
				null,
				[ ApiBase::PARAM_DEPRECATED_VALUES => [ 'a' => true ], ApiBase::PARAM_DFLT => 'a' ],
				'a',
				[],
			],
			'Deprecated parameter value as default, specified' => [
				'a',
				[ ApiBase::PARAM_DEPRECATED_VALUES => [ 'a' => true ], ApiBase::PARAM_DFLT => 'a' ],
				'a',
				[ [ 'apiwarn-deprecation-parameter', 'myParam=a' ] ],
			],
			'Multiple deprecated parameter values' => [
				'a|b|c|d',
				[ ApiBase::PARAM_DEPRECATED_VALUES =>
					[ 'b' => true, 'd' => true ],
					ApiBase::PARAM_ISMULTI => true ],
				[ 'a', 'b', 'c', 'd' ],
				[
					[ 'apiwarn-deprecation-parameter', 'myParam=b' ],
					[ 'apiwarn-deprecation-parameter', 'myParam=d' ],
				],
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
				[
					ApiBase::PARAM_ISMULTI => true,
					ApiBase::PARAM_TYPE => [ 'a', 'b', 'c' ],
					ApiBase::PARAM_ALL => true,
				],
				[ 'a', 'b', 'c' ],
				[],
			],
			'Wildcard "*" with multiples not allowed' => [
				'*',
				[
					ApiBase::PARAM_TYPE => [ 'a', 'b', 'c' ],
					ApiBase::PARAM_ALL => true,
				],
				ApiUsageException::newWithMessage( null,
					[ 'apierror-unrecognizedvalue', 'myParam', '&#42;' ],
					'unknown_myParam' ),
				[],
			],
			'Wildcard "*" with unrestricted type' => [
				'*',
				[
					ApiBase::PARAM_ISMULTI => true,
					ApiBase::PARAM_ALL => true,
				],
				[ '*' ],
				[],
			],
			'Wildcard "x"' => [
				'x',
				[
					ApiBase::PARAM_ISMULTI => true,
					ApiBase::PARAM_TYPE => [ 'a', 'b', 'c' ],
					ApiBase::PARAM_ALL => 'x',
				],
				[ 'a', 'b', 'c' ],
				[],
			],
			'Wildcard conflicting with allowed value' => [
				'a',
				[
					ApiBase::PARAM_ISMULTI => true,
					ApiBase::PARAM_TYPE => [ 'a', 'b', 'c' ],
					ApiBase::PARAM_ALL => 'a',
				],
				new MWException(
					'Internal error in ApiBase::getParameterFromSettings: ' .
					'For param myParam, PARAM_ALL collides with a possible ' .
					'value' ),
				[],
			],
			'Namespace with wildcard' => [
				'*',
				[
					ApiBase::PARAM_ISMULTI => true,
					ApiBase::PARAM_TYPE => 'namespace',
				],
				MWNamespace::getValidNamespaces(),
				[],
			],
			// PARAM_ALL is ignored with namespace types.
			'Namespace with wildcard suppressed' => [
				'*',
				[
					ApiBase::PARAM_ISMULTI => true,
					ApiBase::PARAM_TYPE => 'namespace',
					ApiBase::PARAM_ALL => false,
				],
				MWNamespace::getValidNamespaces(),
				[],
			],
			'Namespace with wildcard "x"' => [
				'x',
				[
					ApiBase::PARAM_ISMULTI => true,
					ApiBase::PARAM_TYPE => 'namespace',
					ApiBase::PARAM_ALL => 'x',
				],
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
				[
					ApiBase::PARAM_TYPE => 'upload',
					ApiBase::PARAM_DFLT => '',
				],
				new MWException(
					'Internal error in ApiBase::getParameterFromSettings: ' .
					"File upload param myParam's default is set to ''. " .
					'File upload parameters may not have a default.' ),
				[],
			],
			'Multiple upload' => [
				'',
				[
					ApiBase::PARAM_TYPE => 'upload',
					ApiBase::PARAM_ISMULTI => true,
				],
				new MWException(
					'Internal error in ApiBase::getParameterFromSettings: ' .
					'Multi-values not supported for myParam' ),
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
				[
					ApiBase::PARAM_TYPE => 'namespace',
					ApiBase::PARAM_EXTRA_NAMESPACES => [ '-1' ],
				],
				'-1',
				[],
			],
			// @todo Test with PARAM_SUBMODULE_MAP unset, need
			// getModuleManager() to return something real
			'Nonexistent module' => [
				'not-a-module-name',
				[
					ApiBase::PARAM_TYPE => 'submodule',
					ApiBase::PARAM_SUBMODULE_MAP =>
						[ 'foo' => 'foo', 'bar' => 'foo+bar' ],
				],
				ApiUsageException::newWithMessage(
					null,
					[
						'apierror-unrecognizedvalue',
						'myParam',
						'not-a-module-name',
					],
					'unknown_myParam'
				),
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
			'Integer with unenforced min' => [
				'-2',
				[
					ApiBase::PARAM_TYPE => 'integer',
					ApiBase::PARAM_MIN => -1,
				],
				-1,
				[ [ 'apierror-integeroutofrange-belowminimum', 'myParam', -1,
					-2 ] ],
			],
			'Integer with enforced min' => [
				'-2',
				[
					ApiBase::PARAM_TYPE => 'integer',
					ApiBase::PARAM_MIN => -1,
					ApiBase::PARAM_RANGE_ENFORCE => true,
				],
				ApiUsageException::newWithMessage( null,
					[ 'apierror-integeroutofrange-belowminimum', 'myParam',
					'-1', '-2' ], 'integeroutofrange',
					[ 'min' => -1, 'max' => null, 'botMax' => null ] ),
				[],
			],
			'Integer with unenforced max (internal mode)' => [
				'8',
				[
					ApiBase::PARAM_TYPE => 'integer',
					ApiBase::PARAM_MAX => 7,
				],
				8,
				[],
			],
			'Integer with enforced max (internal mode)' => [
				'8',
				[
					ApiBase::PARAM_TYPE => 'integer',
					ApiBase::PARAM_MAX => 7,
					ApiBase::PARAM_RANGE_ENFORCE => true,
				],
				8,
				[],
			],
			'Integer with unenforced max (non-internal mode)' => [
				'8',
				[
					ApiBase::PARAM_TYPE => 'integer',
					ApiBase::PARAM_MAX => 7,
				],
				7,
				[ [ 'apierror-integeroutofrange-abovemax', 'myParam', 7, 8 ] ],
				[ 'internalmode' => false ],
			],
			'Integer with enforced max (non-internal mode)' => [
				'8',
				[
					ApiBase::PARAM_TYPE => 'integer',
					ApiBase::PARAM_MAX => 7,
					ApiBase::PARAM_RANGE_ENFORCE => true,
				],
				ApiUsageException::newWithMessage(
					null,
					[ 'apierror-integeroutofrange-abovemax', 'myParam', '7', '8' ],
					'integeroutofrange',
					[ 'min' => null, 'max' => 7, 'botMax' => 7 ]
				),
				[],
				[ 'internalmode' => false ],
			],
			'Array of integers' => [
				'3|12|966|-1',
				[
					ApiBase::PARAM_ISMULTI => true,
					ApiBase::PARAM_TYPE => 'integer',
				],
				[ 3, 12, 966, -1 ],
				[],
			],
			'Array of integers with unenforced min/max (internal mode)' => [
				'3|12|966|-1',
				[
					ApiBase::PARAM_ISMULTI => true,
					ApiBase::PARAM_TYPE => 'integer',
					ApiBase::PARAM_MIN => 0,
					ApiBase::PARAM_MAX => 100,
				],
				[ 3, 12, 966, 0 ],
				[ [ 'apierror-integeroutofrange-belowminimum', 'myParam', 0, -1 ] ],
			],
			'Array of integers with enforced min/max (internal mode)' => [
				'3|12|966|-1',
				[
					ApiBase::PARAM_ISMULTI => true,
					ApiBase::PARAM_TYPE => 'integer',
					ApiBase::PARAM_MIN => 0,
					ApiBase::PARAM_MAX => 100,
					ApiBase::PARAM_RANGE_ENFORCE => true,
				],
				ApiUsageException::newWithMessage(
					null,
					[ 'apierror-integeroutofrange-belowminimum', 'myParam', 0, -1 ],
					'integeroutofrange',
					[ 'min' => 0, 'max' => 100, 'botMax' => 100 ]
				),
				[],
			],
			'Array of integers with unenforced min/max (non-internal mode)' => [
				'3|12|966|-1',
				[
					ApiBase::PARAM_ISMULTI => true,
					ApiBase::PARAM_TYPE => 'integer',
					ApiBase::PARAM_MIN => 0,
					ApiBase::PARAM_MAX => 100,
				],
				[ 3, 12, 100, 0 ],
				[
					[ 'apierror-integeroutofrange-abovemax', 'myParam', 100, 966 ],
					[ 'apierror-integeroutofrange-belowminimum', 'myParam', 0, -1 ]
				],
				[ 'internalmode' => false ],
			],
			'Array of integers with enforced min/max (non-internal mode)' => [
				'3|12|966|-1',
				[
					ApiBase::PARAM_ISMULTI => true,
					ApiBase::PARAM_TYPE => 'integer',
					ApiBase::PARAM_MIN => 0,
					ApiBase::PARAM_MAX => 100,
					ApiBase::PARAM_RANGE_ENFORCE => true,
				],
				ApiUsageException::newWithMessage(
					null,
					[ 'apierror-integeroutofrange-abovemax', 'myParam', 100, 966 ],
					'integeroutofrange',
					[ 'min' => 0, 'max' => 100, 'botMax' => 100 ]
				),
				[],
				[ 'internalmode' => false ],
			],
			'Limit with parseLimits false' => [
				'100',
				[ ApiBase::PARAM_TYPE => 'limit' ],
				'100',
				[],
				[ 'parseLimits' => false ],
			],
			'Limit with no max' => [
				'100',
				[
					ApiBase::PARAM_TYPE => 'limit',
					ApiBase::PARAM_MAX2 => 10,
					ApiBase::PARAM_ISMULTI => true,
				],
				new MWException(
					'Internal error in ApiBase::getParameterFromSettings: ' .
					'MAX1 or MAX2 are not defined for the limit myParam' ),
				[],
			],
			'Limit with no max2' => [
				'100',
				[
					ApiBase::PARAM_TYPE => 'limit',
					ApiBase::PARAM_MAX => 10,
					ApiBase::PARAM_ISMULTI => true,
				],
				new MWException(
					'Internal error in ApiBase::getParameterFromSettings: ' .
					'MAX1 or MAX2 are not defined for the limit myParam' ),
				[],
			],
			'Limit with multi-value' => [
				'100',
				[
					ApiBase::PARAM_TYPE => 'limit',
					ApiBase::PARAM_MAX => 10,
					ApiBase::PARAM_MAX2 => 10,
					ApiBase::PARAM_ISMULTI => true,
				],
				new MWException(
					'Internal error in ApiBase::getParameterFromSettings: ' .
					'Multi-values not supported for myParam' ),
				[],
			],
			'Valid limit' => [
				'100',
				[
					ApiBase::PARAM_TYPE => 'limit',
					ApiBase::PARAM_MAX => 100,
					ApiBase::PARAM_MAX2 => 100,
				],
				100,
				[],
			],
			'Limit max' => [
				'max',
				[
					ApiBase::PARAM_TYPE => 'limit',
					ApiBase::PARAM_MAX => 100,
					ApiBase::PARAM_MAX2 => 101,
				],
				100,
				[],
			],
			'Limit max for apihighlimits' => [
				'max',
				[
					ApiBase::PARAM_TYPE => 'limit',
					ApiBase::PARAM_MAX => 100,
					ApiBase::PARAM_MAX2 => 101,
				],
				101,
				[],
				[ 'apihighlimits' => true ],
			],
			'Limit too large (internal mode)' => [
				'101',
				[
					ApiBase::PARAM_TYPE => 'limit',
					ApiBase::PARAM_MAX => 100,
					ApiBase::PARAM_MAX2 => 101,
				],
				101,
				[],
			],
			'Limit okay for apihighlimits (internal mode)' => [
				'101',
				[
					ApiBase::PARAM_TYPE => 'limit',
					ApiBase::PARAM_MAX => 100,
					ApiBase::PARAM_MAX2 => 101,
				],
				101,
				[],
				[ 'apihighlimits' => true ],
			],
			'Limit too large for apihighlimits (internal mode)' => [
				'102',
				[
					ApiBase::PARAM_TYPE => 'limit',
					ApiBase::PARAM_MAX => 100,
					ApiBase::PARAM_MAX2 => 101,
				],
				102,
				[],
				[ 'apihighlimits' => true ],
			],
			'Limit too large (non-internal mode)' => [
				'101',
				[
					ApiBase::PARAM_TYPE => 'limit',
					ApiBase::PARAM_MAX => 100,
					ApiBase::PARAM_MAX2 => 101,
				],
				100,
				[ [ 'apierror-integeroutofrange-abovemax', 'myParam', 100, 101 ] ],
				[ 'internalmode' => false ],
			],
			'Limit okay for apihighlimits (non-internal mode)' => [
				'101',
				[
					ApiBase::PARAM_TYPE => 'limit',
					ApiBase::PARAM_MAX => 100,
					ApiBase::PARAM_MAX2 => 101,
				],
				101,
				[],
				[ 'internalmode' => false, 'apihighlimits' => true ],
			],
			'Limit too large for apihighlimits (non-internal mode)' => [
				'102',
				[
					ApiBase::PARAM_TYPE => 'limit',
					ApiBase::PARAM_MAX => 100,
					ApiBase::PARAM_MAX2 => 101,
				],
				101,
				[ [ 'apierror-integeroutofrange-abovebotmax', 'myParam', 101, 102 ] ],
				[ 'internalmode' => false, 'apihighlimits' => true ],
			],
			'Limit too small' => [
				'-2',
				[
					ApiBase::PARAM_TYPE => 'limit',
					ApiBase::PARAM_MIN => -1,
					ApiBase::PARAM_MAX => 100,
					ApiBase::PARAM_MAX2 => 100,
				],
				-1,
				[ [ 'apierror-integeroutofrange-belowminimum', 'myParam', -1,
					-2 ] ],
			],
			'Timestamp' => [
				wfTimestamp( TS_UNIX, '20211221122112' ),
				[ ApiBase::PARAM_TYPE => 'timestamp' ],
				'20211221122112',
				[],
			],
			'Timestamp 0' => [
				'0',
				[ ApiBase::PARAM_TYPE => 'timestamp' ],
				// Magic keyword
				'now',
				[ [ 'apiwarn-unclearnowtimestamp', 'myParam', '0' ] ],
			],
			'Timestamp empty' => [
				'',
				[ ApiBase::PARAM_TYPE => 'timestamp' ],
				'now',
				[ [ 'apiwarn-unclearnowtimestamp', 'myParam', '' ] ],
			],
			// wfTimestamp() interprets this as Unix time
			'Timestamp 00' => [
				'00',
				[ ApiBase::PARAM_TYPE => 'timestamp' ],
				'19700101000000',
				[],
			],
			'Timestamp now' => [
				'now',
				[ ApiBase::PARAM_TYPE => 'timestamp' ],
				'now',
				[],
			],
			'Invalid timestamp' => [
				'a potato',
				[ ApiBase::PARAM_TYPE => 'timestamp' ],
				ApiUsageException::newWithMessage(
					null,
					[ 'apierror-badtimestamp', 'myParam', 'a potato' ],
					'badtimestamp_myParam'
				),
				[],
			],
			'Timestamp array' => [
				'100|101',
				[
					ApiBase::PARAM_TYPE => 'timestamp',
					ApiBase::PARAM_ISMULTI => 1,
				],
				[ wfTimestamp( TS_MW, 100 ), wfTimestamp( TS_MW, 101 ) ],
				[],
			],
			'User' => [
				'foo_bar',
				[ ApiBase::PARAM_TYPE => 'user' ],
				'Foo bar',
				[],
			],
			'User prefixed with "User:"' => [
				'User:foo_bar',
				[ ApiBase::PARAM_TYPE => 'user' ],
				'Foo bar',
				[],
			],
			'Invalid username "|"' => [
				'|',
				[ ApiBase::PARAM_TYPE => 'user' ],
				ApiUsageException::newWithMessage( null,
					[ 'apierror-baduser', 'myParam', '&#124;' ],
					'baduser_myParam' ),
				[],
			],
			'Invalid username "300.300.300.300"' => [
				'300.300.300.300',
				[ ApiBase::PARAM_TYPE => 'user' ],
				ApiUsageException::newWithMessage( null,
					[ 'apierror-baduser', 'myParam', '300.300.300.300' ],
					'baduser_myParam' ),
				[],
			],
			'IP range as username' => [
				'10.0.0.0/8',
				[ ApiBase::PARAM_TYPE => 'user' ],
				'10.0.0.0/8',
				[],
			],
			'IPv6 as username' => [
				'::1',
				[ ApiBase::PARAM_TYPE => 'user' ],
				'0:0:0:0:0:0:0:1',
				[],
			],
			'Obsolete cloaked usemod IP address as username' => [
				'1.2.3.xxx',
				[ ApiBase::PARAM_TYPE => 'user' ],
				'1.2.3.xxx',
				[],
			],
			'Invalid username containing IP address' => [
				'This is [not] valid 1.2.3.xxx, ha!',
				[ ApiBase::PARAM_TYPE => 'user' ],
				ApiUsageException::newWithMessage(
					null,
					[ 'apierror-baduser', 'myParam', 'This is &#91;not&#93; valid 1.2.3.xxx, ha!' ],
					'baduser_myParam'
				),
				[],
			],
			'External username' => [
				'M>Foo bar',
				[ ApiBase::PARAM_TYPE => 'user' ],
				'M>Foo bar',
				[],
			],
			'Array of usernames' => [
				'foo|bar',
				[
					ApiBase::PARAM_TYPE => 'user',
					ApiBase::PARAM_ISMULTI => true,
				],
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
				[
					ApiBase::PARAM_TYPE => 'tags',
					ApiBase::PARAM_ISMULTI => true,
				],
				[ 'tag1' ],
				[],
			],
			'Array of tags' => [
				'tag1|tag2',
				[
					ApiBase::PARAM_TYPE => 'tags',
					ApiBase::PARAM_ISMULTI => true,
				],
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
				[
					ApiBase::PARAM_MAX_BYTES => 0,
					ApiBase::PARAM_MAX_CHARS => 0,
				],
				ApiUsageException::newWithMessage( null,
					[ 'apierror-maxbytes', 'myParam', 0 ] ),
				[],
			],
			'Too many chars' => [
				'§§',
				[
					ApiBase::PARAM_MAX_BYTES => 4,
					ApiBase::PARAM_MAX_CHARS => 1,
				],
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
			'Empty multi-value' => [
				'',
				[ ApiBase::PARAM_ISMULTI => true ],
				[],
				[],
			],
			'Multi-value \x1f' => [
				"\x1f",
				[ ApiBase::PARAM_ISMULTI => true ],
				[],
				[],
			],
			'Allowed non-multi-value with "|"' => [
				'a|b',
				[ ApiBase::PARAM_TYPE => [ 'a|b' ] ],
				'a|b',
				[],
			],
			'Prohibited multi-value' => [
				'a|b',
				[ ApiBase::PARAM_TYPE => [ 'a', 'b' ] ],
				ApiUsageException::newWithMessage( null,
					[
						'apierror-multival-only-one-of',
						'myParam',
						Message::listParam( [ '<kbd>a</kbd>', '<kbd>b</kbd>' ] ),
						2
					],
					'multival_myParam'
				),
				[],
			],
		];

		// The following really just test PHP's string-to-int conversion.
		$integerTests = [
			[ '+1', 1 ],
			[ '-1', -1 ],
			[ '1.5', 1 ],
			[ '-1.5', -1 ],
			[ '1abc', 1 ],
			[ ' 1', 1 ],
			[ "\t1", 1, '\t1' ],
			[ "\r1", 1, '\r1' ],
			[ "\f1", 0, '\f1', 'badutf-8' ],
			[ "\n1", 1, '\n1' ],
			[ "\v1", 0, '\v1', 'badutf-8' ],
			[ "\e1", 0, '\e1', 'badutf-8' ],
			[ "\x001", 0, '\x001', 'badutf-8' ],
		];

		foreach ( $integerTests as $test ) {
			$desc = $test[2] ?? $test[0];
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

		$msg = new Message( 'mainpage' );

		// Sanity check empty array
		$expect = Status::newGood();
		$this->assertEquals( $expect, $mock->errorArrayToStatus( [] ) );

		// No blocked $user, so no special block handling
		$expect = Status::newGood();
		$expect->fatal( 'blockedtext' );
		$expect->fatal( 'autoblockedtext' );
		$expect->fatal( 'systemblockedtext' );
		$expect->fatal( 'mainpage' );
		$expect->fatal( $msg );
		$expect->fatal( $msg, 'foobar' );
		$expect->fatal( 'parentheses', 'foobar' );
		$this->assertEquals( $expect, $mock->errorArrayToStatus( [
			[ 'blockedtext' ],
			[ 'autoblockedtext' ],
			[ 'systemblockedtext' ],
			'mainpage',
			$msg,
			[ $msg, 'foobar' ],
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
		$expect->fatal( $msg );
		$expect->fatal( $msg, 'foobar' );
		$expect->fatal( 'parentheses', 'foobar' );
		$this->assertEquals( $expect, $mock->errorArrayToStatus( [
			[ 'blockedtext' ],
			[ 'autoblockedtext' ],
			[ 'systemblockedtext' ],
			'mainpage',
			$msg,
			[ $msg, 'foobar' ],
			[ 'parentheses', 'foobar' ],
		], $user ) );
	}

	public function testAddBlockInfoToStatus() {
		$mock = new MockApi();

		$msg = new Message( 'mainpage' );

		// Sanity check empty array
		$expect = Status::newGood();
		$test = Status::newGood();
		$mock->addBlockInfoToStatus( $test );
		$this->assertEquals( $expect, $test );

		// No blocked $user, so no special block handling
		$expect = Status::newGood();
		$expect->fatal( 'blockedtext' );
		$expect->fatal( 'autoblockedtext' );
		$expect->fatal( 'systemblockedtext' );
		$expect->fatal( 'mainpage' );
		$expect->fatal( $msg );
		$expect->fatal( $msg, 'foobar' );
		$expect->fatal( 'parentheses', 'foobar' );
		$test = clone $expect;
		$mock->addBlockInfoToStatus( $test );
		$this->assertEquals( $expect, $test );

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
		$expect->fatal( $msg );
		$expect->fatal( $msg, 'foobar' );
		$expect->fatal( 'parentheses', 'foobar' );
		$test = Status::newGood();
		$test->fatal( 'blockedtext' );
		$test->fatal( 'autoblockedtext' );
		$test->fatal( 'systemblockedtext' );
		$test->fatal( 'mainpage' );
		$test->fatal( $msg );
		$test->fatal( $msg, 'foobar' );
		$test->fatal( 'parentheses', 'foobar' );
		$mock->addBlockInfoToStatus( $test, $user );
		$this->assertEquals( $expect, $test );
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

	/**
	 * @covers ApiBase::extractRequestParams
	 */
	public function testExtractRequestParams() {
		$request = new FauxRequest( [
			'xxexists' => 'exists!',
			'xxmulti' => 'a|b|c|d|{bad}',
			'xxempty' => '',
			'xxtemplate-a' => 'A!',
			'xxtemplate-b' => 'B1|B2|B3',
			'xxtemplate-c' => '',
			'xxrecursivetemplate-b-B1' => 'X',
			'xxrecursivetemplate-b-B3' => 'Y',
			'xxrecursivetemplate-b-B4' => '?',
			'xxemptytemplate-' => 'nope',
			'foo' => 'a|b|c',
			'xxfoo' => 'a|b|c',
			'errorformat' => 'raw',
		] );
		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setRequest( $request );
		$main = new ApiMain( $context );

		$mock = $this->getMockBuilder( ApiBase::class )
			->setConstructorArgs( [ $main, 'test', 'xx' ] )
			->setMethods( [ 'getAllowedParams' ] )
			->getMockForAbstractClass();
		$mock->method( 'getAllowedParams' )->willReturn( [
			'notexists' => null,
			'exists' => null,
			'multi' => [
				ApiBase::PARAM_ISMULTI => true,
			],
			'empty' => [
				ApiBase::PARAM_ISMULTI => true,
			],
			'template-{m}' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TEMPLATE_VARS => [ 'm' => 'multi' ],
			],
			'recursivetemplate-{m}-{t}' => [
				ApiBase::PARAM_TEMPLATE_VARS => [ 't' => 'template-{m}', 'm' => 'multi' ],
			],
			'emptytemplate-{m}' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TEMPLATE_VARS => [ 'm' => 'empty' ],
			],
			'badtemplate-{e}' => [
				ApiBase::PARAM_TEMPLATE_VARS => [ 'e' => 'exists' ],
			],
			'badtemplate2-{e}' => [
				ApiBase::PARAM_TEMPLATE_VARS => [ 'e' => 'badtemplate2-{e}' ],
			],
			'badtemplate3-{x}' => [
				ApiBase::PARAM_TEMPLATE_VARS => [ 'x' => 'foo' ],
			],
		] );

		$this->assertEquals( [
			'notexists' => null,
			'exists' => 'exists!',
			'multi' => [ 'a', 'b', 'c', 'd', '{bad}' ],
			'empty' => [],
			'template-a' => [ 'A!' ],
			'template-b' => [ 'B1', 'B2', 'B3' ],
			'template-c' => [],
			'template-d' => null,
			'recursivetemplate-a-A!' => null,
			'recursivetemplate-b-B1' => 'X',
			'recursivetemplate-b-B2' => null,
			'recursivetemplate-b-B3' => 'Y',
		], $mock->extractRequestParams() );

		$used = TestingAccessWrapper::newFromObject( $main )->getParamsUsed();
		sort( $used );
		$this->assertEquals( [
			'xxempty',
			'xxexists',
			'xxmulti',
			'xxnotexists',
			'xxrecursivetemplate-a-A!',
			'xxrecursivetemplate-b-B1',
			'xxrecursivetemplate-b-B2',
			'xxrecursivetemplate-b-B3',
			'xxtemplate-a',
			'xxtemplate-b',
			'xxtemplate-c',
			'xxtemplate-d',
		], $used );

		$warnings = $mock->getResult()->getResultData( 'warnings', [ 'Strip' => 'all' ] );
		$this->assertCount( 1, $warnings );
		$this->assertSame( 'ignoring-invalid-templated-value', $warnings[0]['code'] );
	}

}

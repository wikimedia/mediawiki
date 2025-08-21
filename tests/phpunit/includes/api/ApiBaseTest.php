<?php

namespace MediaWiki\Tests\Api;

use DomainException;
use Exception;
use MediaWiki\Api\ApiBase;
use MediaWiki\Api\ApiMain;
use MediaWiki\Api\ApiUsageException;
use MediaWiki\Api\Validator\SubmoduleDef;
use MediaWiki\Context\DerivativeContext;
use MediaWiki\Context\RequestContext;
use MediaWiki\Exception\MWException;
use MediaWiki\MediaWikiServices;
use MediaWiki\Message\Message;
use MediaWiki\Page\WikiPage;
use MediaWiki\ParamValidator\TypeDef\NamespaceDef;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Title\Title;
use StatusValue;
use Wikimedia\Message\ListType;
use Wikimedia\Message\MessageSpecifier;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\EnumDef;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;
use Wikimedia\ParamValidator\TypeDef\StringDef;
use Wikimedia\TestingAccessWrapper;

/**
 * @group API
 * @group Database
 * @group medium
 *
 * @covers \MediaWiki\Api\ApiBase
 */
class ApiBaseTest extends ApiTestCase {

	protected function setUp(): void {
		parent::setUp();
		$this->setGroupPermissions( [
			'*' => [
				'read' => true,
				'edit' => true,
				'apihighlimits' => false,
			],
		] );
	}

	/**
	 * This covers a variety of stub methods that return a fixed value.
	 *
	 * @dataProvider provideStubMethods
	 */
	public function testStubMethods( $expected, $method, $args = [] ) {
		// Some of these are protected
		$mock = TestingAccessWrapper::newFromObject( new MockApi() );
		$result = $mock->$method( ...$args );
		$this->assertSame( $expected, $result );
	}

	public static function provideStubMethods() {
		return [
			[ null, 'getModuleManager' ],
			[ null, 'getCustomPrinter' ],
			[ [], 'getHelpUrls' ],
			// @todo This is actually overridden by MockApi
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

	public function testRequireOnlyOneParameterZero() {
		$mock = new MockApi();
		$this->expectException( ApiUsageException::class );
		$mock->requireOnlyOneParameter(
			[ "filename" => "foo.txt", "enablechunks" => 0 ],
			"filename", "enablechunks"
		);
	}

	public function testRequireOnlyOneParameterTrue() {
		$mock = new MockApi();
		$this->expectException( ApiUsageException::class );
		$mock->requireOnlyOneParameter(
			[ "filename" => "foo.txt", "enablechunks" => true ],
			"filename", "enablechunks"
		);
	}

	public function testRequireOnlyOneParameterMissing() {
		$this->expectApiErrorCodeFromCallback( 'missingparam', static function () {
			$mock = new MockApi();
			$mock->requireOnlyOneParameter(
				[ "filename" => "foo.txt", "enablechunks" => false ],
				"foo", "bar" );
		} );
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
		$this->expectApiErrorCodeFromCallback( 'invalidparammix', static function () {
			$mock = new MockApi();
			$mock->requireMaxOneParameter(
				[ 'foo' => 'bar', 'baz' => 'quz' ],
				'foo', 'baz' );
		} );
	}

	public function testRequireAtLeastOneParameterZero() {
		$this->expectApiErrorCodeFromCallback( 'missingparam', static function () {
			$mock = new MockApi();
			$mock->requireAtLeastOneParameter(
				[ 'a' => 'b', 'c' => 'd' ],
				'foo', 'bar' );
		} );
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
		$this->expectApiErrorCodeFromCallback( 'invalidparammix', static function () {
			$mock = new MockApi();
			$mock->getTitleOrPageId( [ 'title' => 'a', 'pageid' => 7 ] );
		} );
	}

	public function testGetTitleOrPageIdTitle() {
		$mock = new MockApi();
		$result = $mock->getTitleOrPageId( [ 'title' => 'Foo' ] );
		$this->assertInstanceOf( WikiPage::class, $result );
		$this->assertSame( 'Foo', $result->getTitle()->getPrefixedText() );
	}

	public function testGetTitleOrPageIdInvalidTitle() {
		$this->expectApiErrorCodeFromCallback( 'invalidtitle', static function () {
			$mock = new MockApi();
			$mock->getTitleOrPageId( [ 'title' => '|' ] );
		} );
	}

	public function testGetTitleOrPageIdSpecialTitle() {
		$this->expectApiErrorCodeFromCallback( 'pagecannotexist', static function () {
			$mock = new MockApi();
			$mock->getTitleOrPageId( [ 'title' => 'Special:RandomPage' ] );
		} );
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
		$this->expectApiErrorCodeFromCallback( 'nosuchpageid', static function () {
			$mock = new MockApi();
			$mock->getTitleOrPageId( [ 'pageid' => 2147483648 ] );
		} );
	}

	public function testGetTitleFromTitleOrPageIdBadParams() {
		$this->expectApiErrorCodeFromCallback( 'invalidparammix', static function () {
			$mock = new MockApi();
			$mock->getTitleFromTitleOrPageId( [ 'title' => 'a', 'pageid' => 7 ] );
		} );
	}

	public function testGetTitleFromTitleOrPageIdTitle() {
		$mock = new MockApi();
		$result = $mock->getTitleFromTitleOrPageId( [ 'title' => 'Foo' ] );
		$this->assertInstanceOf( Title::class, $result );
		$this->assertSame( 'Foo', $result->getPrefixedText() );
	}

	public function testGetTitleFromTitleOrPageIdInvalidTitle() {
		$this->expectApiErrorCodeFromCallback( 'invalidtitle', static function () {
			$mock = new MockApi();
			$mock->getTitleFromTitleOrPageId( [ 'title' => '|' ] );
		} );
	}

	public function testGetTitleFromTitleOrPageIdPageId() {
		$page = $this->getExistingTestPage();
		$result = ( new MockApi() )->getTitleFromTitleOrPageId(
			[ 'pageid' => $page->getId() ] );
		$this->assertInstanceOf( Title::class, $result );
		$this->assertSame( $page->getTitle()->getPrefixedText(), $result->getPrefixedText() );
	}

	public function testGetTitleFromTitleOrPageIdInvalidPageId() {
		$this->expectApiErrorCodeFromCallback( 'nosuchpageid', static function () {
			$mock = new MockApi();
			$mock->getTitleFromTitleOrPageId( [ 'pageid' => 298401643 ] );
		} );
	}

	public function testGetParameter() {
		$mock = $this->getMockBuilder( MockApi::class )
			->onlyMethods( [ 'getAllowedParams' ] )
			->getMock();
		$mock->method( 'getAllowedParams' )->willReturn( [
			'foo' => [
				ParamValidator::PARAM_TYPE => [ 'value' ],
			],
			'bar' => [
				ParamValidator::PARAM_TYPE => [ 'value' ],
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
			$this->assertApiErrorCode( 'badvalue', $ex );
		}

		// And extractRequestParams() must throw too.
		try {
			$mock->extractRequestParams();
			$this->fail( 'Expected exception not thrown' );
		} catch ( ApiUsageException $ex ) {
			$this->assertApiErrorCode( 'badvalue', $ex );
		}
	}

	/**
	 * @param string|null $input
	 * @param array $paramSettings
	 * @param mixed $expected
	 * @param string[] $warnings
	 * @param array $options Key-value pairs:
	 *   'parseLimits': true|false
	 *   'apihighlimits': true|false
	 *   'prefix': true|false
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
			$context->setUser( $this->getTestSysop()->getUser() );
		}

		// If we're testing tags, set up some tags
		if ( isset( $paramSettings[ParamValidator::PARAM_TYPE] ) &&
			$paramSettings[ParamValidator::PARAM_TYPE] === 'tags'
		) {
			$changeTagsStore = $this->getServiceContainer()->getChangeTagsStore();
			$changeTagsStore->defineTag( 'tag1' );
			$changeTagsStore->defineTag( 'tag2' );
		}

		if ( $expected instanceof Exception ) {
			try {
				$wrapper->getParameterFromSettings( $paramName, $paramSettings,
					$parseLimits );
				$this->fail( 'No exception thrown' );
			} catch ( Exception $ex ) {
				$this->assertInstanceOf( get_class( $expected ), $ex );
				if ( $ex instanceof ApiUsageException ) {
					$this->assertEquals( $expected->getModulePath(), $ex->getModulePath() );
					$this->assertEquals( $expected->getStatusValue(), $ex->getStatusValue() );
				} else {
					$this->assertEquals( $expected->getMessage(), $ex->getMessage() );
					$this->assertEquals( $expected->getCode(), $ex->getCode() );
				}
			}
		} else {
			$result = $wrapper->getParameterFromSettings( $paramName,
				$paramSettings, $parseLimits );
			if ( isset( $paramSettings[ParamValidator::PARAM_TYPE] ) &&
				$paramSettings[ParamValidator::PARAM_TYPE] === 'timestamp' &&
				$expected === 'now'
			) {
				// Allow one second of fuzziness.  Make sure the formats are
				// correct!
				$this->assertMatchesRegularExpression( '/^\d{14}$/', $result );
				$this->assertLessThanOrEqual( 1,
					abs( wfTimestamp( TS_UNIX, $result ) - time() ),
					"Result $result differs from expected $expected by " .
					'more than one second' );
			} else {
				$this->assertSame( $expected, $result );
			}
			$actualWarnings = array_map( static function ( $warn ) {
				return $warn instanceof MessageSpecifier
					? [ $warn->getKey(), ...$warn->getParams() ]
					: $warn;
			}, $mock->warnings );
			$this->assertEquals( $warnings, $actualWarnings );
		}

		if ( !empty( $paramSettings[ParamValidator::PARAM_SENSITIVE] ) ||
			( isset( $paramSettings[ParamValidator::PARAM_TYPE] ) &&
			$paramSettings[ParamValidator::PARAM_TYPE] === 'password' )
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

		$namespaces = MediaWikiServices::getInstance()->getNamespaceInfo()->getValidNamespaces();

		$returnArray = [
			'Basic param' => [ 'bar', null, 'bar', [] ],
			'Basic param, C0 controls' => [ $c0, null, $enc, $warnings ],
			'String param' => [ 'bar', '', 'bar', [] ],
			'String param, defaulted' => [ null, '', '', [] ],
			'String param, empty' => [ '', 'default', '', [] ],
			'String param, required, empty' => [
				'',
				[ ParamValidator::PARAM_DEFAULT => 'default', ParamValidator::PARAM_REQUIRED => true ],
				ApiUsageException::newWithMessage( null, [
					'paramvalidator-missingparam',
					Message::plaintextParam( 'myParam' ),
					Message::plaintextParam( '' ),
				], 'missingparam' ),
				[]
			],
			'Multi-valued parameter' => [
				'a|b|c',
				[ ParamValidator::PARAM_ISMULTI => true ],
				[ 'a', 'b', 'c' ],
				[]
			],
			'Multi-valued parameter, alternative separator' => [
				"\x1fa|b\x1fc|d",
				[ ParamValidator::PARAM_ISMULTI => true ],
				[ 'a|b', 'c|d' ],
				[]
			],
			'Multi-valued parameter, other C0 controls' => [
				$c0,
				[ ParamValidator::PARAM_ISMULTI => true ],
				[ $enc ],
				$warnings
			],
			'Multi-valued parameter, other C0 controls (2)' => [
				"\x1f" . $c0,
				[ ParamValidator::PARAM_ISMULTI => true ],
				[ substr( $enc, 0, -3 ), '' ],
				$warnings
			],
			'Multi-valued parameter with limits' => [
				'a|b|c',
				[
					ParamValidator::PARAM_ISMULTI => true,
					ParamValidator::PARAM_ISMULTI_LIMIT1 => 3,
				],
				[ 'a', 'b', 'c' ],
				[],
			],
			'Multi-valued parameter with exceeded limits' => [
				'a|b|c',
				[
					ParamValidator::PARAM_ISMULTI => true,
					ParamValidator::PARAM_ISMULTI_LIMIT1 => 2,
				],
				ApiUsageException::newWithMessage( null, [
					'paramvalidator-toomanyvalues',
					Message::plaintextParam( 'myParam' ),
					Message::numParam( 2 ),
				], 'toomanyvalues', [
					'parameter' => 'myParam',
					'limit' => 2,
					'lowlimit' => 2,
					'highlimit' => 500,
				] ),
				[]
			],
			'Multi-valued parameter with exceeded limits for non-bot' => [
				'a|b|c',
				[
					ParamValidator::PARAM_ISMULTI => true,
					ParamValidator::PARAM_ISMULTI_LIMIT1 => 2,
					ParamValidator::PARAM_ISMULTI_LIMIT2 => 3,
				],
				ApiUsageException::newWithMessage( null, [
					'paramvalidator-toomanyvalues',
					Message::plaintextParam( 'myParam' ),
					Message::numParam( 2 ),
				], 'toomanyvalues', [
					'parameter' => 'myParam',
					'limit' => 2,
					'lowlimit' => 2,
					'highlimit' => 3,
				] ),
				[]
			],
			'Multi-valued parameter with non-exceeded limits for bot' => [
				'a|b|c',
				[
					ParamValidator::PARAM_ISMULTI => true,
					ParamValidator::PARAM_ISMULTI_LIMIT1 => 2,
					ParamValidator::PARAM_ISMULTI_LIMIT2 => 3,
				],
				[ 'a', 'b', 'c' ],
				[],
				[ 'apihighlimits' => true ],
			],
			'Multi-valued parameter with prohibited duplicates' => [
				'a|b|a|c',
				[ ParamValidator::PARAM_ISMULTI => true ],
				[ 'a', 'b', 'c' ],
				[],
			],
			'Multi-valued parameter with allowed duplicates' => [
				'a|a',
				[
					ParamValidator::PARAM_ISMULTI => true,
					ParamValidator::PARAM_ALLOW_DUPLICATES => true,
				],
				[ 'a', 'a' ],
				[],
			],
			'Empty boolean param' => [
				'',
				[ ParamValidator::PARAM_TYPE => 'boolean' ],
				true,
				[],
			],
			'Boolean param 0' => [
				'0',
				[ ParamValidator::PARAM_TYPE => 'boolean' ],
				true,
				[],
			],
			'Boolean param false' => [
				'false',
				[ ParamValidator::PARAM_TYPE => 'boolean' ],
				true,
				[],
			],
			'Deprecated parameter' => [
				'foo',
				[ ParamValidator::PARAM_DEPRECATED => true ],
				'foo',
				[ [
					'paramvalidator-param-deprecated',
					Message::plaintextParam( 'myParam' ),
					Message::plaintextParam( 'foo' )
				] ],
			],
			'Deprecated parameter with default, unspecified' => [
				null,
				[ ParamValidator::PARAM_DEPRECATED => true, ParamValidator::PARAM_DEFAULT => 'foo' ],
				'foo',
				[],
			],
			'Deprecated parameter with default, specified' => [
				'foo',
				[ ParamValidator::PARAM_DEPRECATED => true, ParamValidator::PARAM_DEFAULT => 'foo' ],
				'foo',
				[ [
					'paramvalidator-param-deprecated',
					Message::plaintextParam( 'myParam' ),
					Message::plaintextParam( 'foo' )
				] ],
			],
			'Deprecated parameter value' => [
				'a',
				[ ParamValidator::PARAM_TYPE => [ 'a' ], EnumDef::PARAM_DEPRECATED_VALUES => [ 'a' => true ] ],
				'a',
				[ [
					'paramvalidator-deprecated-value',
					Message::plaintextParam( 'myParam' ),
					Message::plaintextParam( 'a' )
				] ],
			],
			'Deprecated parameter value as default, unspecified' => [
				null,
				[
					ParamValidator::PARAM_TYPE => [ 'a' ],
					EnumDef::PARAM_DEPRECATED_VALUES => [ 'a' => true ],
					ParamValidator::PARAM_DEFAULT => 'a'
				],
				'a',
				[],
			],
			'Deprecated parameter value as default, specified' => [
				'a',
				[
					ParamValidator::PARAM_TYPE => [ 'a' ],
					EnumDef::PARAM_DEPRECATED_VALUES => [ 'a' => true ],
					ParamValidator::PARAM_DEFAULT => 'a'
				],
				'a',
				[ [
					'paramvalidator-deprecated-value',
					Message::plaintextParam( 'myParam' ),
					Message::plaintextParam( 'a' )
				] ],
			],
			'Multiple deprecated parameter values' => [
				'a|b|c|d',
				[
					ParamValidator::PARAM_TYPE => [ 'a', 'b', 'c', 'd' ],
					EnumDef::PARAM_DEPRECATED_VALUES => [ 'b' => true, 'd' => true ],
					ParamValidator::PARAM_ISMULTI => true,
				],
				[ 'a', 'b', 'c', 'd' ],
				[
					[
						'paramvalidator-deprecated-value',
						Message::plaintextParam( 'myParam' ),
						Message::plaintextParam( 'b' )
					],
					[
						'paramvalidator-deprecated-value',
						Message::plaintextParam( 'myParam' ),
						Message::plaintextParam( 'd' )
					],
				],
			],
			'Deprecated parameter value with custom warning' => [
				'a',
				[ ParamValidator::PARAM_TYPE => [ 'a' ], EnumDef::PARAM_DEPRECATED_VALUES => [ 'a' => 'my-msg' ] ],
				'a',
				[ [ 'my-msg' ] ],
			],
			'"*" when wildcard not allowed' => [
				'*',
				[
					ParamValidator::PARAM_ISMULTI => true,
					ParamValidator::PARAM_TYPE => [ 'a', 'b', 'c' ],
				],
				[],
				[ [
					'paramvalidator-unrecognizedvalues',
					Message::plaintextParam( 'myParam' ),
					Message::plaintextParam( '*' ),
					Message::listParam( [ Message::plaintextParam( '*' ) ], ListType::COMMA ),
					Message::numParam( 1 ),
				] ],
			],
			'Wildcard "*"' => [
				'*',
				[
					ParamValidator::PARAM_ISMULTI => true,
					ParamValidator::PARAM_TYPE => [ 'a', 'b', 'c' ],
					ParamValidator::PARAM_ALL => true,
				],
				[ 'a', 'b', 'c' ],
				[],
			],
			'Wildcard "*" with multiples not allowed' => [
				'*',
				[
					ParamValidator::PARAM_TYPE => [ 'a', 'b', 'c' ],
					ParamValidator::PARAM_ALL => true,
				],
				ApiUsageException::newWithMessage( null, [
					'paramvalidator-badvalue-enumnotmulti',
					Message::plaintextParam( 'myParam' ),
					Message::plaintextParam( '*' ),
					Message::listParam( [
						Message::plaintextParam( 'a' ),
						Message::plaintextParam( 'b' ),
						Message::plaintextParam( 'c' ),
					] ),
					Message::numParam( 3 ),
				], 'badvalue' ),
				[],
			],
			'Wildcard "*" with unrestricted type' => [
				'*',
				[
					ParamValidator::PARAM_ISMULTI => true,
					ParamValidator::PARAM_ALL => true,
				],
				[ '*' ],
				[],
			],
			'Wildcard "x"' => [
				'x',
				[
					ParamValidator::PARAM_ISMULTI => true,
					ParamValidator::PARAM_TYPE => [ 'a', 'b', 'c' ],
					ParamValidator::PARAM_ALL => 'x',
				],
				[ 'a', 'b', 'c' ],
				[],
			],
			'Namespace with wildcard' => [
				'*',
				[
					ParamValidator::PARAM_ISMULTI => true,
					ParamValidator::PARAM_TYPE => 'namespace',
				],
				$namespaces,
				[],
			],
			// PARAM_ALL is ignored with namespace types.
			'Namespace with wildcard suppressed' => [
				'*',
				[
					ParamValidator::PARAM_ISMULTI => true,
					ParamValidator::PARAM_TYPE => 'namespace',
					ParamValidator::PARAM_ALL => false,
				],
				$namespaces,
				[],
			],
			'Namespace with wildcard "x"' => [
				'x',
				[
					ParamValidator::PARAM_ISMULTI => true,
					ParamValidator::PARAM_TYPE => 'namespace',
					ParamValidator::PARAM_ALL => 'x',
				],
				[],
				[ [
					'paramvalidator-unrecognizedvalues',
					Message::plaintextParam( 'myParam' ),
					Message::plaintextParam( 'x' ),
					Message::listParam( [ Message::plaintextParam( 'x' ) ], ListType::COMMA ),
					Message::numParam( 1 ),
				] ],
			],
			'Password' => [
				'dDy+G?e?txnr.1:(@Ru',
				[ ParamValidator::PARAM_TYPE => 'password' ],
				'dDy+G?e?txnr.1:(@Ru',
				[],
			],
			'Sensitive field' => [
				'I am fond of pineapples',
				[ ParamValidator::PARAM_SENSITIVE => true ],
				'I am fond of pineapples',
				[],
			],
			// @todo Test actual upload
			'Namespace -1' => [
				'-1',
				[ ParamValidator::PARAM_TYPE => 'namespace' ],
				ApiUsageException::newWithMessage( null, [
					'paramvalidator-badvalue-enumnotmulti',
					Message::plaintextParam( 'myParam' ),
					Message::plaintextParam( '-1' ),
					Message::listParam( array_map( [ Message::class, 'plaintextParam' ], $namespaces ) ),
					Message::numParam( count( $namespaces ) ),
				], 'badvalue' ),
				[],
			],
			'Extra namespace -1' => [
				'-1',
				[
					ParamValidator::PARAM_TYPE => 'namespace',
					NamespaceDef::PARAM_EXTRA_NAMESPACES => [ -1 ],
				],
				-1,
				[],
			],
			// @todo Test with PARAM_SUBMODULE_MAP unset, need
			// getModuleManager() to return something real
			'Nonexistent module' => [
				'not-a-module-name',
				[
					ParamValidator::PARAM_TYPE => 'submodule',
					SubmoduleDef::PARAM_SUBMODULE_MAP =>
						[ 'foo' => 'foo', 'bar' => 'foo+bar' ],
				],
				ApiUsageException::newWithMessage( null, [
					'paramvalidator-badvalue-enumnotmulti',
					Message::plaintextParam( 'myParam' ),
					Message::plaintextParam( 'not-a-module-name' ),
					Message::listParam( [
						Message::plaintextParam( 'foo' ),
						Message::plaintextParam( 'bar' ),
					] ),
					Message::numParam( 2 ),
				], 'badvalue' ),
				[],
			],
			'\\x1f with multiples not allowed' => [
				"\x1f",
				[],
				ApiUsageException::newWithMessage( null, [
					'paramvalidator-notmulti',
					Message::plaintextParam( 'myParam' ),
					Message::plaintextParam( "\x1f" ),
				], 'badvalue' ),
				[],
			],
			'Integer with unenforced min' => [
				'-2',
				[
					ParamValidator::PARAM_TYPE => 'integer',
					IntegerDef::PARAM_MIN => -1,
				],
				-1,
				[ [
					'paramvalidator-outofrange-min',
					Message::plaintextParam( 'myParam' ),
					Message::plaintextParam( '-2' ),
					Message::numParam( -1 ),
					Message::numParam( '' ),
				] ],
			],
			'Integer with enforced min' => [
				'-2',
				[
					ParamValidator::PARAM_TYPE => 'integer',
					IntegerDef::PARAM_MIN => -1,
					ApiBase::PARAM_RANGE_ENFORCE => true,
				],
				ApiUsageException::newWithMessage( null, [
					'paramvalidator-outofrange-min',
					Message::plaintextParam( 'myParam' ),
					Message::plaintextParam( '-2' ),
					Message::numParam( -1 ),
					Message::numParam( '' ),
				], 'outofrange', [ 'min' => -1, 'curmax' => null, 'max' => null, 'highmax' => null ] ),
				[],
			],
			'Integer with unenforced max' => [
				'8',
				[
					ParamValidator::PARAM_TYPE => 'integer',
					IntegerDef::PARAM_MAX => 7,
				],
				7,
				[ [
					'paramvalidator-outofrange-max',
					Message::plaintextParam( 'myParam' ),
					Message::plaintextParam( '8' ),
					Message::numParam( '' ),
					Message::numParam( 7 ),
				] ],
			],
			'Integer with enforced max' => [
				'8',
				[
					ParamValidator::PARAM_TYPE => 'integer',
					IntegerDef::PARAM_MAX => 7,
					ApiBase::PARAM_RANGE_ENFORCE => true,
				],
				ApiUsageException::newWithMessage( null, [
					'paramvalidator-outofrange-max',
					Message::plaintextParam( 'myParam' ),
					Message::plaintextParam( '8' ),
					Message::numParam( '' ),
					Message::numParam( 7 ),
				], 'outofrange', [ 'min' => null, 'curmax' => 7, 'max' => 7, 'highmax' => 7 ] ),
				[],
			],
			'Array of integers' => [
				'3|12|966|-1',
				[
					ParamValidator::PARAM_ISMULTI => true,
					ParamValidator::PARAM_TYPE => 'integer',
				],
				[ 3, 12, 966, -1 ],
				[],
			],
			'Array of integers with unenforced min/max' => [
				'3|12|966|-1',
				[
					ParamValidator::PARAM_ISMULTI => true,
					ParamValidator::PARAM_TYPE => 'integer',
					IntegerDef::PARAM_MIN => 0,
					IntegerDef::PARAM_MAX => 100,
				],
				[ 3, 12, 100, 0 ],
				[
					[
						'paramvalidator-outofrange-minmax',
						Message::plaintextParam( 'myParam' ),
						Message::plaintextParam( '966' ),
						Message::numParam( 0 ),
						Message::numParam( 100 ),
					],
					[
						'paramvalidator-outofrange-minmax',
						Message::plaintextParam( 'myParam' ),
						Message::plaintextParam( '-1' ),
						Message::numParam( 0 ),
						Message::numParam( 100 ),
					],
				],
			],
			'Array of integers with enforced min/max' => [
				'3|12|966|-1',
				[
					ParamValidator::PARAM_ISMULTI => true,
					ParamValidator::PARAM_TYPE => 'integer',
					IntegerDef::PARAM_MIN => 0,
					IntegerDef::PARAM_MAX => 100,
					ApiBase::PARAM_RANGE_ENFORCE => true,
				],
				ApiUsageException::newWithMessage( null, [
					'paramvalidator-outofrange-minmax',
					Message::plaintextParam( 'myParam' ),
					Message::plaintextParam( '966' ),
					Message::numParam( 0 ),
					Message::numParam( 100 ),
				], 'outofrange', [ 'min' => 0, 'curmax' => 100, 'max' => 100, 'highmax' => 100 ] ),
				[],
			],
			'Limit with parseLimits false (numeric)' => [
				'100',
				[ ParamValidator::PARAM_TYPE => 'limit' ],
				100,
				[],
				[ 'parseLimits' => false ],
			],
			'Limit with parseLimits false (max)' => [
				'max',
				[ ParamValidator::PARAM_TYPE => 'limit' ],
				'max',
				[],
				[ 'parseLimits' => false ],
			],
			'Limit with parseLimits false (invalid)' => [
				'kitten',
				[ ParamValidator::PARAM_TYPE => 'limit' ],
				ApiUsageException::newWithMessage( null, [
					'paramvalidator-badinteger',
					Message::plaintextParam( 'myParam' ),
					Message::plaintextParam( 'kitten' ),
				], 'badinteger' ),
				[],
				[ 'parseLimits' => false ],
			],
			'Limit with no max, supplied "max"' => [
				'max',
				[
					ParamValidator::PARAM_TYPE => 'limit',
				],
				PHP_INT_MAX,
				[],
			],
			'Valid limit' => [
				'100',
				[
					ParamValidator::PARAM_TYPE => 'limit',
					IntegerDef::PARAM_MAX => 100,
					IntegerDef::PARAM_MAX2 => 100,
				],
				100,
				[],
			],
			'Limit max' => [
				'max',
				[
					ParamValidator::PARAM_TYPE => 'limit',
					IntegerDef::PARAM_MAX => 100,
					IntegerDef::PARAM_MAX2 => 101,
				],
				100,
				[],
			],
			'Limit max for apihighlimits' => [
				'max',
				[
					ParamValidator::PARAM_TYPE => 'limit',
					IntegerDef::PARAM_MAX => 100,
					IntegerDef::PARAM_MAX2 => 101,
				],
				101,
				[],
				[ 'apihighlimits' => true ],
			],
			'Limit too large' => [
				'101',
				[
					ParamValidator::PARAM_TYPE => 'limit',
					IntegerDef::PARAM_MAX => 100,
					IntegerDef::PARAM_MAX2 => 101,
				],
				100,
				[ [
					'paramvalidator-outofrange-minmax',
					Message::plaintextParam( 'myParam' ),
					Message::plaintextParam( '101' ),
					Message::numParam( 0 ),
					Message::numParam( 100 ),
				] ],
			],
			'Limit okay for apihighlimits' => [
				'101',
				[
					ParamValidator::PARAM_TYPE => 'limit',
					IntegerDef::PARAM_MAX => 100,
					IntegerDef::PARAM_MAX2 => 101,
				],
				101,
				[],
				[ 'apihighlimits' => true ],
			],
			'Limit too large for apihighlimits (non-internal mode)' => [
				'102',
				[
					ParamValidator::PARAM_TYPE => 'limit',
					IntegerDef::PARAM_MAX => 100,
					IntegerDef::PARAM_MAX2 => 101,
				],
				101,
				[ [
					'paramvalidator-outofrange-minmax',
					Message::plaintextParam( 'myParam' ),
					Message::plaintextParam( '102' ),
					Message::numParam( 0 ),
					Message::numParam( 101 ),
				] ],
				[ 'apihighlimits' => true ],
			],
			'Limit too small' => [
				'-2',
				[
					ParamValidator::PARAM_TYPE => 'limit',
					IntegerDef::PARAM_MIN => -1,
					IntegerDef::PARAM_MAX => 100,
					IntegerDef::PARAM_MAX2 => 100,
				],
				-1,
				[ [
					'paramvalidator-outofrange-minmax',
					Message::plaintextParam( 'myParam' ),
					Message::plaintextParam( '-2' ),
					Message::numParam( -1 ),
					Message::numParam( 100 ),
				] ],
			],
			'Timestamp' => [
				wfTimestamp( TS_UNIX, '20211221122112' ),
				[ ParamValidator::PARAM_TYPE => 'timestamp' ],
				'20211221122112',
				[],
			],
			'Timestamp 0' => [
				'0',
				[ ParamValidator::PARAM_TYPE => 'timestamp' ],
				// Magic keyword
				'now',
				[ [
					'paramvalidator-unclearnowtimestamp',
					Message::plaintextParam( 'myParam' ),
					Message::plaintextParam( '0' ),
				] ],
			],
			'Timestamp empty' => [
				'',
				[ ParamValidator::PARAM_TYPE => 'timestamp' ],
				'now',
				[ [
					'paramvalidator-unclearnowtimestamp',
					Message::plaintextParam( 'myParam' ),
					Message::plaintextParam( '' ),
				] ],
			],
			// wfTimestamp() interprets this as Unix time
			'Timestamp 00' => [
				'00',
				[ ParamValidator::PARAM_TYPE => 'timestamp' ],
				'19700101000000',
				[],
			],
			'Timestamp now' => [
				'now',
				[ ParamValidator::PARAM_TYPE => 'timestamp' ],
				'now',
				[],
			],
			'Invalid timestamp' => [
				'a potato',
				[ ParamValidator::PARAM_TYPE => 'timestamp' ],
				ApiUsageException::newWithMessage( null, [
					'paramvalidator-badtimestamp',
					Message::plaintextParam( 'myParam' ),
					Message::plaintextParam( 'a potato' ),
				], 'badtimestamp' ),
				[],
			],
			'Timestamp array' => [
				'100|101',
				[
					ParamValidator::PARAM_TYPE => 'timestamp',
					ParamValidator::PARAM_ISMULTI => 1,
				],
				[ wfTimestamp( TS_MW, 100 ), wfTimestamp( TS_MW, 101 ) ],
				[],
			],
			'Expiry array' => [
				'99990123123456|8888-01-23 12:34:56|indefinite',
				[
					ParamValidator::PARAM_TYPE => 'expiry',
					ParamValidator::PARAM_ISMULTI => 1,
				],
				[ '9999-01-23T12:34:56Z', '8888-01-23T12:34:56Z', 'infinity' ],
				[],
			],
			'User' => [
				'foo_bar',
				[ ParamValidator::PARAM_TYPE => 'user' ],
				'Foo bar',
				[],
			],
			'User prefixed with "User:"' => [
				'User:foo_bar',
				[ ParamValidator::PARAM_TYPE => 'user' ],
				'Foo bar',
				[],
			],
			'Invalid username "|"' => [
				'|',
				[ ParamValidator::PARAM_TYPE => 'user' ],
				ApiUsageException::newWithMessage( null, [
					'paramvalidator-baduser',
					Message::plaintextParam( 'myParam' ),
					Message::plaintextParam( '|' ),
				], 'baduser' ),
				[],
			],
			'Invalid username "300.300.300.300"' => [
				'300.300.300.300',
				[ ParamValidator::PARAM_TYPE => 'user' ],
				ApiUsageException::newWithMessage( null, [
					'paramvalidator-baduser',
					Message::plaintextParam( 'myParam' ),
					Message::plaintextParam( '300.300.300.300' ),
				], 'baduser' ),
				[],
			],
			'IP range as username' => [
				'10.0.0.0/8',
				[ ParamValidator::PARAM_TYPE => 'user' ],
				'10.0.0.0/8',
				[],
			],
			'IPv6 as username' => [
				'::1',
				[ ParamValidator::PARAM_TYPE => 'user' ],
				'0:0:0:0:0:0:0:1',
				[],
			],
			'Obsolete cloaked usemod IP address as username' => [
				'1.2.3.xxx',
				[ ParamValidator::PARAM_TYPE => 'user' ],
				'1.2.3.xxx',
				[],
			],
			'Invalid username containing IP address' => [
				'This is [not] valid 1.2.3.xxx, ha!',
				[ ParamValidator::PARAM_TYPE => 'user' ],
				ApiUsageException::newWithMessage( null, [
					'paramvalidator-baduser',
					Message::plaintextParam( 'myParam' ),
					Message::plaintextParam( 'This is [not] valid 1.2.3.xxx, ha!' ),
				], 'baduser' ),
				[],
			],
			'External username' => [
				'M>Foo bar',
				[ ParamValidator::PARAM_TYPE => 'user' ],
				'M>Foo bar',
				[],
			],
			'Array of usernames' => [
				'foo|bar',
				[
					ParamValidator::PARAM_TYPE => 'user',
					ParamValidator::PARAM_ISMULTI => true,
				],
				[ 'Foo', 'Bar' ],
				[],
			],
			'tag' => [
				'tag1',
				[ ParamValidator::PARAM_TYPE => 'tags' ],
				[ 'tag1' ],
				[],
			],
			'Array of one tag' => [
				'tag1',
				[
					ParamValidator::PARAM_TYPE => 'tags',
					ParamValidator::PARAM_ISMULTI => true,
				],
				[ 'tag1' ],
				[],
			],
			'Array of tags' => [
				'tag1|tag2',
				[
					ParamValidator::PARAM_TYPE => 'tags',
					ParamValidator::PARAM_ISMULTI => true,
				],
				[ 'tag1', 'tag2' ],
				[],
			],
			'Invalid tag' => [
				'invalid tag',
				[ ParamValidator::PARAM_TYPE => 'tags' ],
				ApiUsageException::newWithMessage(
					null,
					[ 'tags-apply-not-allowed-one', 'invalid tag', 1 ],
					'badtags',
					[ 'disallowedtags' => [ 'invalid tag' ] ]
				),
				[],
			],
			'Unrecognized type' => [
				'foo',
				[ ParamValidator::PARAM_TYPE => 'nonexistenttype' ],
				new DomainException( "Param myParam's type is unknown - nonexistenttype" ),
				[],
			],
			'Too many bytes' => [
				'1',
				[
					StringDef::PARAM_MAX_BYTES => 0,
					StringDef::PARAM_MAX_CHARS => 0,
				],
				ApiUsageException::newWithMessage( null, [
					'paramvalidator-maxbytes',
					Message::plaintextParam( 'myParam' ),
					Message::plaintextParam( '1' ),
					Message::numParam( 0 ),
					Message::numParam( 1 ),
				], 'maxbytes', [ 'maxbytes' => 0, 'maxchars' => 0 ] ),
				[],
			],
			'Too many chars' => [
				'§§',
				[
					StringDef::PARAM_MAX_BYTES => 4,
					StringDef::PARAM_MAX_CHARS => 1,
				],
				ApiUsageException::newWithMessage( null, [
					'paramvalidator-maxchars',
					Message::plaintextParam( 'myParam' ),
					Message::plaintextParam( '§§' ),
					Message::numParam( 1 ),
					Message::numParam( 2 ),
				], 'maxchars', [ 'maxbytes' => 4, 'maxchars' => 1 ] ),
				[],
			],
			'Omitted required param' => [
				null,
				[ ParamValidator::PARAM_REQUIRED => true ],
				ApiUsageException::newWithMessage( null, [
					'paramvalidator-missingparam',
					Message::plaintextParam( 'myParam' )
				], 'missingparam' ),
				[],
			],
			'Empty multi-value' => [
				'',
				[ ParamValidator::PARAM_ISMULTI => true ],
				[],
				[],
			],
			'Multi-value \x1f' => [
				"\x1f",
				[ ParamValidator::PARAM_ISMULTI => true ],
				[],
				[],
			],
			'Allowed non-multi-value with "|"' => [
				'a|b',
				[ ParamValidator::PARAM_TYPE => [ 'a|b' ] ],
				'a|b',
				[],
			],
			'Prohibited multi-value' => [
				'a|b',
				[ ParamValidator::PARAM_TYPE => [ 'a', 'b' ] ],
				ApiUsageException::newWithMessage( null, [
					'paramvalidator-badvalue-enumnotmulti',
					Message::plaintextParam( 'myParam' ),
					Message::plaintextParam( 'a|b' ),
					Message::listParam( [ Message::plaintextParam( 'a' ), Message::plaintextParam( 'b' ) ] ),
					Message::numParam( 2 ),
				], 'badvalue' ),
				[],
			],
		];

		$integerTests = [
			[ '+1', 1 ],
			[ '-1', -1 ],
			[ '1.5', null ],
			[ '-1.5', null ],
			[ '1abc', null ],
			[ ' 1', null ],
			[ "\t1", null, '\t1' ],
			[ "\r1", null, '\r1' ],
			[ "\f1", null, '\f1', 'badutf-8' ],
			[ "\n1", null, '\n1' ],
			[ "\v1", null, '\v1', 'badutf-8' ],
			[ "\e1", null, '\e1', 'badutf-8' ],
			[ "\x001", null, '\x001', 'badutf-8' ],
		];

		foreach ( $integerTests as $test ) {
			$desc = $test[2] ?? $test[0];
			$warnings = isset( $test[3] ) ?
				[ [ 'apiwarn-badutf8', 'myParam' ] ] : [];
			$returnArray["\"$desc\" as integer"] = [
				$test[0],
				[ ParamValidator::PARAM_TYPE => 'integer' ],
				$test[1] ?? ApiUsageException::newWithMessage( null, [
					'paramvalidator-badinteger',
					Message::plaintextParam( 'myParam' ),
					Message::plaintextParam( preg_replace( "/[\f\v\e\\0]/", '�', $test[0] ) ),
				], 'badinteger' ),
				$warnings,
			];
		}

		return $returnArray;
	}

	/**
	 * @dataProvider provideGetFinalParamDescription
	 */
	public function testGetFinalParamDescription( $paramSettings, $expectedMessages ) {
		$mock = $this->getMockBuilder( MockApi::class )
			->onlyMethods( [ 'getAllowedParams', 'getModulePath' ] )
			->getMock();
		$mock->method( 'getAllowedParams' )->willReturn( [
			'param' => $paramSettings,
		] );
		$mock->method( 'getModulePath' )->willReturn( 'test' );
		if ( $expectedMessages instanceof Exception ) {
			$this->expectExceptionObject( $expectedMessages );
		}
		$paramDescription = $mock->getFinalParamDescription();
		$this->assertArrayHasKey( 'param', $paramDescription );
		$messages = $paramDescription['param'];
		$messageKeys = array_map( static fn ( MessageSpecifier $m ) => $m->getKey(), $messages );
		$this->assertSame( $expectedMessages, $messageKeys );
	}

	public static function provideGetFinalParamDescription() {
		return [
			'default message' => [
				'settings' => [],
				'messages' => [ 'apihelp-test-param-param' ],
			],
			'custom message' => [
				'settings' => [ ApiBase::PARAM_HELP_MSG => 'foo' ],
				'messages' => [ 'foo' ],
			],
			'default per-value message' => [
				'settings' => [
					ParamValidator::PARAM_TYPE => [ 'a', 'b' ],
					ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
				],
				'messages' => [
					'apihelp-test-param-param',
					'apihelp-test-paramvalue-param-a',
					'apihelp-test-paramvalue-param-b',
				],
			],
			'custom per-value message' => [
				'settings' => [
					ParamValidator::PARAM_TYPE => [ 'a', 'b' ],
					ApiBase::PARAM_HELP_MSG_PER_VALUE => [
						'a' => 'foo',
						'b' => 'bar',
					],
				],
				'messages' => [
					'apihelp-test-param-param',
					'foo',
					'bar',
				],
			],
			'custom per-value message for strings' => [
				'settings' => [
					ParamValidator::PARAM_TYPE => 'string',
					ParamValidator::PARAM_ISMULTI => true,
					ApiBase::PARAM_HELP_MSG_PER_VALUE => [
						'a' => 'foo',
						'b' => 'bar',
					],
				],
				'messages' => [
					'apihelp-test-param-param',
					'foo',
					'bar',
				],
			],
			'must be multi-valued for per-value message' => [
				'settings' => [
					ParamValidator::PARAM_TYPE => 'string',
					ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
				],
				'messages' => new MWException(
					'Internal error in ' . ApiBase::class . '::getFinalParamDescription: '
					. 'ApiBase::PARAM_HELP_MSG_PER_VALUE may only be used when '
					. "ParamValidator::PARAM_TYPE is an array or it is 'string' "
					. 'and ParamValidator::PARAM_ISMULTI is true'
				),
			],
		];
	}

	public static function provideDieStatus() {
		$status = StatusValue::newGood();
		$status->error( 'foo' );
		$status->warning( 'bar' );
		yield [ $status, [ 'foo' => true, 'bar' => false ] ];

		$status = StatusValue::newGood();
		$status->warning( 'foo' );
		$status->warning( 'bar' );
		yield [ $status, [ 'foo' => true, 'bar' => true ] ];

		$status = StatusValue::newGood();
		$status->setOK( false );
		yield [ $status, [ 'unknownerror-nocode' => true ] ];

		$status = PermissionStatus::newEmpty();
		$status->setRateLimitExceeded();
		yield [ $status, [ 'ratelimited' => true ] ];

		$status = StatusValue::newFatal( 'actionthrottledtext' );
		yield [ $status, [ 'ratelimited' => true ] ];

		$status = StatusValue::newFatal( 'actionthrottled' );
		yield [ $status, [ 'ratelimited' => true ] ];

		$status = StatusValue::newFatal( 'blockedtext' );
		yield [ $status, [ 'blocked' => true ] ];

		$status = StatusValue::newFatal( 'autoblockedtext' );
		yield [ $status, [ 'autoblocked' => true ] ];
	}

	/**
	 * @dataProvider provideDieStatus
	 *
	 * @param StatusValue $status
	 * @param array $expected
	 */
	public function testDieStatus( $status, $expected ) {
		$mock = new MockApi();

		try {
			$mock->dieStatus( $status );
			$this->fail( 'Expected exception not thrown' );
		} catch ( ApiUsageException $ex ) {
			foreach ( $expected as $key => $has ) {
				$this->assertSame( $has, ApiTestCase::apiExceptionHasCode( $ex, $key ), "Exception has '$key'" );
			}
		}
	}

	/**
	 * @covers \MediaWiki\Api\ApiBase::extractRequestParams
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
			->onlyMethods( [ 'getAllowedParams' ] )
			->getMockForAbstractClass();
		$mock->method( 'getAllowedParams' )->willReturn( [
			'notexists' => null,
			'exists' => null,
			'multi' => [
				ParamValidator::PARAM_ISMULTI => true,
			],
			'empty' => [
				ParamValidator::PARAM_ISMULTI => true,
			],
			'template-{m}' => [
				ParamValidator::PARAM_ISMULTI => true,
				ApiBase::PARAM_TEMPLATE_VARS => [ 'm' => 'multi' ],
			],
			'recursivetemplate-{m}-{t}' => [
				ApiBase::PARAM_TEMPLATE_VARS => [ 't' => 'template-{m}', 'm' => 'multi' ],
			],
			'emptytemplate-{m}' => [
				ParamValidator::PARAM_ISMULTI => true,
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

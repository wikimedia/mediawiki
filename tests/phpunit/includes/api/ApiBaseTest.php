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
		$result = ( new MockApi() )->getTitleOrPageId(
			[ 'pageid' => Title::newFromText( 'UTPage' )->getArticleId() ] );
		$this->assertInstanceOf( WikiPage::class, $result );
		$this->assertSame( 'UTPage', $result->getTitle()->getPrefixedText() );
	}

	public function testGetTitleOrPageIdInvalidPageId() {
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
		$result = ( new MockApi() )->getTitleFromTitleOrPageId(
			[ 'pageid' => Title::newFromText( 'UTPage' )->getArticleId() ] );
		$this->assertInstanceOf( Title::class, $result );
		$this->assertSame( 'UTPage', $result->getPrefixedText() );
	}

	public function testGetTitleFromTitleOrPageIdInvalidPageId() {
		$this->setExpectedException( ApiUsageException::class,
			'There is no page with ID 298401643.' );
		$mock = new MockApi();
		$mock->getTitleFromTitleOrPageId( [ 'pageid' => 298401643 ] );
	}

	/**
	 * As long as it's still used, let's test that it passes through.
	 * See ParamValidatorTest::testIntegration() for tests that parameters
	 * are still parsed right.
	 */
	public function testGetParameterFromSettings() {
		$mock = TestingAccessWrapper::newFromObject( new MockApi() );
		$dummy = (object)[];
		$validator = $this->getMockBuilder( MediaWiki\Api\ParamValidator::class )
			->disableOriginalConstructor()
			->setMethods( [ 'getValue' ] )
			->getMock();
		$validator->expects( $this->once() )->method( 'getValue' )
			->with(
				$this->identicalTo( 'myParam' ),
				$this->identicalTo( 'setting' ),
				$this->identicalTo( $mock->object ),
				$this->identicalTo( false )
			)
			->willReturn( $dummy );
		$mock->mParamValidator = $validator;
		$this->assertSame( $validator, $mock->getParamValidator(), 'sanity check' );

		$this->assertSame( $dummy, $mock->getParameterFromSettings( 'myParam', 'setting', false ) );
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

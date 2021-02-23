<?php

namespace MediaWiki\Tests\Unit\Api;

use ApiMain;
use ApiResult;
use ApiUnblock;
use Exception;
use MediaWiki\Block\BlockPermissionChecker;
use MediaWiki\Block\BlockPermissionCheckerFactory;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\UnblockUser;
use MediaWiki\Block\UnblockUserFactory;
use MediaWikiUnitTestCase;
use RequestContext;
use Status;
use User;
use UserCache;

/**
 * @covers ApiUnblock
 *
 * @author DannyS712
 */
class ApiUnblockTest extends MediaWikiUnitTestCase {

	private function getConstructorArgs() {
		// So these don't need to be created each time
		$apiMain = $this->createMock( ApiMain::class );

		// ApiBase::__construct sets its context from ApiMain
		$requestContext = new RequestContext();
		$apiMain->method( 'getContext' )
			->willReturn( $requestContext );

		// Needed for $this->getUser()
		$performer = $this->createMock( User::class );
		$requestContext->setUser( $performer );

		$action = 'unblock';
		$blockPermissionCheckerFactory = $this->createMock( BlockPermissionCheckerFactory::class );
		$unblockUserFactory = $this->createMock( UnblockUserFactory::class );
		$userCache = $this->createMock( UserCache::class );

		// Expose requestContext and user so that they can be further modified in the test
		$returnVals = [
			'apiMain' => $apiMain,
			'action' => 'unblock',
			'blockPermissionCheckerFactory' => $blockPermissionCheckerFactory,
			'unblockUserFactory' => $unblockUserFactory,
			'userCache' => $userCache,
			'requestContext' => $requestContext,
			'performer' => $performer,
		];
		return $returnVals;
	}

	private function getMockApiUnblock( $args, $methods, $requestParams ) {
		// Avoid code duplication. Create the mock object with the constructor args
		// in $args, the $methods methods mocked, and with ::extractRequestParams
		// returning the $requestParams
		$apiUnblock = $this->getMockBuilder( ApiUnblock::class )
			->setMethods( $methods )
			->setConstructorArgs( [
				$args['apiMain'],
				$args['action'],
				$args['blockPermissionCheckerFactory'],
				$args['unblockUserFactory'],
				$args['userCache']
			] )
			->getMock();

		$apiUnblock->method( 'extractRequestParams' )
			->willReturn( $requestParams );

		return $apiUnblock;
	}

	private function getBlockPermissionCheckerFactory( $target, $performer, $status ) {
		// Factory returns a checker that returns the result
		$blockPermissionChecker = $this->createMock( BlockPermissionChecker::class );
		$blockPermissionChecker->expects( $this->once() )
			->method( 'checkBlockPermissions' )
			->willReturn( $status );

		$blockPermissionCheckerFactory = $this->createMock( BlockPermissionCheckerFactory::class );
		$blockPermissionCheckerFactory->expects( $this->once() )
			->method( 'newBlockPermissionChecker' )
			->with(
				$this->equalTo( $target ),
				$this->equalTo( $performer )
			)
			->willReturn( $blockPermissionChecker );

		return $blockPermissionCheckerFactory;
	}

	private function getUnblockUserFactory( $target, $performer, $reason, $tags, $status ) {
		// Factory returns an UnblockUser that returns the result
		$unblockUser = $this->createMock( UnblockUser::class );
		$unblockUser->expects( $this->once() )
			->method( 'unblock' )
			->willReturn( $status );

		$unblockUserFactory = $this->createMock( UnblockUserFactory::class );
		$unblockUserFactory->expects( $this->once() )
			->method( 'newUnblockUser' )
			->with(
				$this->equalTo( $target ),
				$this->equalTo( $performer ),
				$this->equalTo( $reason ),
				$this->equalTo( $tags )
			)
			->willReturn( $unblockUser );

		return $unblockUserFactory;
	}

	public function testConstruct() {
		$args = $this->getConstructorArgs();

		$apiUnblock = new ApiUnblock(
			$args['apiMain'],
			$args['action'],
			$args['blockPermissionCheckerFactory'],
			$args['unblockUserFactory'],
			$args['userCache']
		);
		// Ensure everything was created right
		$this->assertInstanceOf(
			ApiUnblock::class,
			$apiUnblock
		);
	}

	public function testDieOnlyOneParameter() {
		// Test that `$this->requireOnlyOneParameter( $params, 'id', 'user', 'userid' );`
		// is properly called; since the actual internals of that are complicated,
		// mock it
		$args = $this->getConstructorArgs();

		$requestParams = [ 'actual contents are not used in this test' ];
		$apiUnblock = $this->getMockApiUnblock(
			$args,
			[ 'extractRequestParams', 'requireOnlyOneParameter' ],
			$requestParams
		);

		$testException = new Exception( 'Error should be thrown in requireOnlyOneParameter' );
		$apiUnblock->method( 'requireOnlyOneParameter' )
			->with(
				$this->equalTo( $requestParams ),
				$this->equalTo( 'id' ),
				$this->equalTo( 'user' ),
				$this->equalTo( 'userid' )
			)
			->will( $this->throwException( $testException ) );

		$this->expectException( Exception::class );
		$this->expectExceptionMessage( 'Error should be thrown in requireOnlyOneParameter' );
		$apiUnblock->execute();
	}

	public function testPermissionDenied() {
		// Test that if the permission manager call fails, dieWithError is called
		// Since the actual internals of that are complicated, mock it
		$args = $this->getConstructorArgs();

		$args['performer']->expects( $this->once() )
			->method( 'isAllowed' )
			->with(
				$this->equalTo( 'block' )
			)
			->willReturn( false );

		$requestParams = [ 'actual contents are not used in this test' ];
		$apiUnblock = $this->getMockApiUnblock(
			$args,
			[ 'extractRequestParams', 'requireOnlyOneParameter', 'dieWithError' ],
			$requestParams
		);

		// No need to do anything with requireOnlyOneParameter

		$testException = new Exception( 'Error should be thrown in dieWithError' );
		$apiUnblock->method( 'dieWithError' )
			->with(
				$this->equalTo( 'apierror-permissiondenied-unblock' ),
				$this->equalTo( 'permissiondenied' )
			)
			->will( $this->throwException( $testException ) );

		$this->expectException( Exception::class );
		$this->expectExceptionMessage( 'Error should be thrown in dieWithError' );
		$apiUnblock->execute();
	}

	public function testNoSuchUserId() {
		// If $params['userid'] is set and the usercache call returns false, there is an error
		$args = $this->getConstructorArgs();

		$args['performer']->method( 'isAllowed' )->willReturn( true );

		$args['userCache']->expects( $this->once() )
			->method( 'getProp' )
			->with(
				$this->equalTo( 'userIdGoesHere' ),
				$this->equalTo( 'name' )
			)
			->willReturn( false );

		$requestParams = [ 'userid' => 'userIdGoesHere' ];
		$apiUnblock = $this->getMockApiUnblock(
			$args,
			[ 'extractRequestParams', 'requireOnlyOneParameter', 'dieWithError' ],
			$requestParams
		);

		// No need to do anything with requireOnlyOneParameter

		$testException = new Exception( 'Error should be thrown in dieWithError' );
		$apiUnblock->method( 'dieWithError' )
			->with(
				$this->equalTo( [ 'apierror-nosuchuserid', 'userIdGoesHere' ] ),
				$this->equalTo( 'nosuchuserid' )
			)
			->will( $this->throwException( $testException ) );

		$this->expectException( Exception::class );
		$this->expectExceptionMessage( 'Error should be thrown in dieWithError' );
		$apiUnblock->execute();
	}

	public function testUnblockBadStatus() {
		// For now, not going to test the BlockPermissionChecker failing, since
		// that requires supplying everything needed for the $this->getBlockDetails()
		// call (method is in a trait, so cannot easily be overridden). Additionally,
		// the trait uses `wfTimestamp` and calls `ApiResult::formatExpiry`, which uses
		// wfGetDB, wfIsInfinity, and wfTimestamp, so it may not be possible to test that part
		// Next potential failure is the actual unblock call failing
		$args = $this->getConstructorArgs();

		$args['performer']->method( 'isAllowed' )->willReturn( true );

		// Return true
		$args['blockPermissionCheckerFactory'] = $this->getBlockPermissionCheckerFactory(
			'userNameOfTargetFromCache',
			$args['performer'],
			true
		);

		// Also includes the test of the codepath where UserCache is used and works
		$args['userCache']->expects( $this->once() )
			->method( 'getProp' )
			->with(
				$this->equalTo( 'userIdGoesHere' ),
				$this->equalTo( 'name' )
			)
			->willReturn( 'userNameOfTargetFromCache' );

		$badStatus = Status::newFatal( 'bad status' );
		$args['unblockUserFactory'] = $this->getUnblockUserFactory(
			'userNameOfTargetFromCache',
			$args['performer'],
			'reasonGoesHere',
			[ 'tag 1' ],
			$badStatus
		);

		$requestParams = [
			'userid' => 'userIdGoesHere',
			'id' => null,
			'reason' => 'reasonGoesHere',
			'tags' => [ 'tag 1' ],
		];
		$apiUnblock = $this->getMockApiUnblock(
			$args,
			[ 'extractRequestParams', 'requireOnlyOneParameter', 'dieStatus' ],
			$requestParams
		);

		// No need to do anything with requireOnlyOneParameter

		$testException = new Exception( 'Error should be thrown in dieStatus' );
		$apiUnblock->method( 'dieStatus' )
			->with( $this->equalTo( $badStatus ) )
			->will( $this->throwException( $testException ) );

		$this->expectException( Exception::class );
		$this->expectExceptionMessage( 'Error should be thrown in dieStatus' );
		$apiUnblock->execute();
	}

	public function testSuccessfulUnblock() {
		// Everything works
		$args = $this->getConstructorArgs();

		// ApiBase::getResult just calls ApiMain::getResult, so we can mock it there
		$apiResult = $this->createMock( ApiResult::class );
		$apiResult->expects( $this->once() )
			->method( 'addValue' )
			->with(
				$this->equalTo( null ),
				$this->equalTo( $args['action'] ),
				$this->equalTo( [
					'id' => 678, // block id
					'user' => 'targetNameGoesHere', // target name
					'userid' => 123, // target user id
					'reason' => 'reasonGoesHere', // reason in $requestParams
				] )
			)
			->willReturn( true );
		$args['apiMain']->method( 'getResult' )->willReturn( $apiResult );

		$args['performer']->method( 'isAllowed' )->willReturn( true );
		$args['blockPermissionCheckerFactory'] = $this->getBlockPermissionCheckerFactory(
			'targetNameGoesHere',
			$args['performer'],
			true
		);

		$blockTarget = $this->createMock( User::class );
		$blockTarget->method( 'getName' )->willReturn( 'targetNameGoesHere' );
		$blockTarget->method( 'getId' )->willReturn( 123 );

		$databaseBlock = $this->createMock( DatabaseBlock::class );
		$databaseBlock->method( 'getType' )->willReturn( DatabaseBlock::TYPE_USER );
		$databaseBlock->method( 'getTarget' )->willReturn( $blockTarget );
		$databaseBlock->method( 'getId' )->willReturn( 678 );

		$goodStatus = Status::newGood( $databaseBlock );
		$args['unblockUserFactory'] = $this->getUnblockUserFactory(
			'targetNameGoesHere',
			$args['performer'],
			'reasonGoesHere',
			[ 'tag 2' ],
			$goodStatus
		);

		$requestParams = [
			'user' => 'targetNameGoesHere',
			'userid' => null,
			'id' => null,
			'reason' => 'reasonGoesHere',
			'tags' => [ 'tag 2' ],
		];

		$apiUnblock = $this->getMockApiUnblock(
			$args,
			[ 'extractRequestParams', 'requireOnlyOneParameter' ],
			$requestParams
		);

		// No need to do anything with requireOnlyOneParameter
		$apiUnblock->execute();

		// Make sure we got to the end
		$this->addToAssertionCount( 1 );
	}

}

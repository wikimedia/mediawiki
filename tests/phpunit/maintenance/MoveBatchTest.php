<?php

namespace MediaWiki\Tests\Maintenance;

use MediaWiki\User\UserIdentity;
use MoveBatch;

/**
 * @covers \MoveBatch
 * @group Database
 * @author Dreamy Jazz
 */
class MoveBatchTest extends MaintenanceBaseTestCase {

	private static UserIdentity $testPerformer;

	public function getMaintenanceClass() {
		return MoveBatch::class;
	}

	public function addDBDataOnce() {
		self::$testPerformer = $this->getTestUser()->getUserIdentity();
	}

	private function getFileWithContent( string $content ): string {
		$testFilename = $this->getNewTempFile();
		$testFile = fopen( $testFilename, 'w' );
		fwrite( $testFile, $content );
		fclose( $testFile );
		return $testFilename;
	}

	/** @dataProvider provideInvalidPerformerUsernames */
	public function testExecuteForInvalidPerformer( $performerUsername ) {
		$this->expectCallToFatalError();
		$this->expectOutputRegex( '/Invalid username/' );
		$this->maintenance->setArg( 'listfile', $this->getNewTempFile() );
		$this->maintenance->setOption( 'u', $performerUsername );
		$this->maintenance->execute();
	}

	public static function provideInvalidPerformerUsernames() {
		return [
			'Username is invalid' => [ 'Template:Testing#test' ],
			'Username does not exist' => [ 'Non-existing-test-user-1234' ],
		];
	}

	/** @dataProvider provideExecuteOnFailedMove */
	public function testExecuteOnFailedMove( $fileInputContent, $expectedOutputRegex ) {
		$this->expectOutputRegex( $expectedOutputRegex );
		$this->maintenance->setArg( 'listfile', $this->getFileWithContent( $fileInputContent ) );
		$this->maintenance->execute();
	}

	public static function provideExecuteOnFailedMove() {
		return [
			'Line is missing line break' => [ 'Template:Test#testing', '/Error on line 1, no pipe character/' ],
			'Invalid source title' => [ ':::|Abc', '/Invalid title on line 1/' ],
			'Invalid destination title' => [ 'Abc|:::', '/Invalid title on line 1/' ],
			'Non-existing source title' => [ 'Abc|Def', '/Abc --> Def FAILED[\s\S]*Abc doesn\'t exist/' ],
		];
	}

	/** @dataProvider provideExecuteForGoodMove */
	public function testExecuteForGoodMove(
		$options, $shouldCreateRedirect, $expectedReason, $expectedPerformerUsernameCallback
	) {
		foreach ( $options as $name => $value ) {
			if ( is_callable( $value ) ) {
				$value = $value();
			}
			$this->maintenance->setOption( $name, $value );
		}
		// Get a source page and destination page
		$sourcePage = $this->getExistingTestPage();
		$sourcePageContentBeforeMove = $sourcePage->getContent()->getWikitextForTransclusion();
		$destPage = $this->getNonexistingTestPage();
		// Move the page using the maintenance script
		$this->maintenance->setArg(
			'listfile',
			$this->getFileWithContent( "$sourcePage|$destPage" )
		);
		$this->maintenance->execute();
		// Validate that the move occurred
		$sourcePage->clear();
		$destPage->clear();
		// First check that the source page either is a redirect or is deleted, depending on the
		// --noredirects option being provided.
		if ( $shouldCreateRedirect ) {
			$this->assertTrue( $sourcePage->getContent()->isRedirect() );
		} else {
			$this->assertFalse( $sourcePage->exists() );
		}
		// Next check that the content of the destination page is the same as the source page (as the move should not
		// have modified the content).
		$this->assertSame( $sourcePageContentBeforeMove, $destPage->getContent()->getWikitextForTransclusion() );
		// Check the reason for the move is as expected
		$this->newSelectQueryBuilder()
			->select( 'comment_text' )
			->from( 'logging' )
			->join( 'comment', null, 'log_comment_id=comment_id' )
			->where( [ 'log_type' => 'move' ] )
			->assertFieldValue( $expectedReason );
		// Check the performer of the move is as expected
		$this->newSelectQueryBuilder()
			->select( 'actor_name' )
			->from( 'logging' )
			->join( 'actor', null, 'log_actor=actor_id' )
			->where( [ 'log_type' => 'move' ] )
			->assertFieldValue( $expectedPerformerUsernameCallback() );
		$this->expectOutputString( "$sourcePage --> $destPage\n" );
	}

	public static function provideExecuteForGoodMove() {
		return [
			'No options provided' => [ [], true, '', fn () => 'Move page script' ],
			'--noredirects set, custom reason, and custom performer' => [
				[ 'noredirects' => 1, 'r' => 'Test', 'u' => fn () => static::$testPerformer->getName() ],
				false, 'Test', fn () => static::$testPerformer->getName()
			],
		];
	}
}

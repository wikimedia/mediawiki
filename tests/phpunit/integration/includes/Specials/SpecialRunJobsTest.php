<?php

namespace MediaWiki\Tests\Integration\Specials;

use MediaWiki\JobQueue\Jobs\NullJob;
use MediaWiki\Json\FormatJson;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\FauxRequest;
use MediaWiki\Specials\SpecialRunJobs;
use MediaWiki\Tests\Specials\SpecialPageTestBase;
use Wikimedia\Timestamp\ConvertibleTimestamp;

/**
 * @covers \MediaWiki\Specials\SpecialRunJobs
 * @group Database
 */
class SpecialRunJobsTest extends SpecialPageTestBase {

	public function testGetQuerySignatureWithEmptyQuery(): void {
		$secretKey = 'test-secret-key';
		$signature = SpecialRunJobs::getQuerySignature( [], $secretKey );

		$this->assertIsString( $signature );
		$this->assertSame( 40, strlen( $signature ) );
	}

	public function testExecuteWhenInReadOnlyMode(): void {
		$this->getServiceContainer()->getReadOnlyMode()->setReason( 'Test' );

		[ $html ] = $this->executeSpecialPage();
		$this->assertStringContainsString( 'Wiki is in read-only mode', $html );
	}

	public function testExecuteWhenRequestNotPosted(): void {
		[ $html ] = $this->executeSpecialPage();
		$this->assertStringContainsString( 'Request must be POSTed.', $html );
	}

	public function testExecuteWhenRequestMissingParameters(): void {
		[ $html ] = $this->executeSpecialPage( '', new FauxRequest( [], true ) );
		$this->assertStringContainsString( 'Missing parameters: title, signature, sigexpiry', $html );
	}

	public function testExecuteWhenRequestUsesBadSignature(): void {
		[ $html ] = $this->executeSpecialPage( '', new FauxRequest( [
			'signature' => 'bad',
			'title' => 'Special:RunJobs',
			'sigexpiry' => (string)( ConvertibleTimestamp::now() + 300 ),
		], true ) );
		$this->assertStringContainsString( 'Invalid or stale signature provided.', $html );
	}

	public function testExecuteWithValidSignatureQueuesAndRunsJobs(): void {
		$jobQueueGroup = $this->getServiceContainer()->getJobQueueGroup();
		$jobQueueGroup->push( new NullJob( [] ) );
		$jobQueueGroup->push( new NullJob( [] ) );

		$secretKey = $this->getServiceContainer()->getMainConfig()->get( MainConfigNames::SecretKey );

		$params = [
			'title' => 'Special:RunJobs',
			'maxjobs' => '10',
			'maxtime' => '1',
			'type' => 'null',
			'async' => '0',
			'stats' => '1',
			'sigexpiry' => (string)( ConvertibleTimestamp::now() + 300 ),
		];

		$params['signature'] = SpecialRunJobs::getQuerySignature( $params, $secretKey );

		$request = new FauxRequest( $params, true );

		[ $html ] = $this->executeSpecialPage( '', $request );
		$actualJson = FormatJson::decode( $html, true );
		$this->assertIsArray( $actualJson );
		$this->assertArrayHasKey( 'jobs', $actualJson );
		$this->assertCount( 2, $actualJson['jobs'] );
		$this->assertSame( [ 'null', 'null' ], array_column( $actualJson['jobs'], 'type' ) );

		$this->assertArrayHasKey( 'reached', $actualJson );
		$this->assertSame( 'none-ready', $actualJson['reached'] );
	}

	/** @inheritDoc */
	protected function newSpecialPage() {
		return $this->getServiceContainer()->getSpecialPageFactory()->getPage( 'RunJobs' );
	}
}

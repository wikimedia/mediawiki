<?php

namespace MediaWiki\Tests\Api;

use MediaWiki\Api\ApiContinuationManager;
use MediaWiki\Api\ApiMain;
use MediaWiki\Api\ApiResult;
use MediaWiki\Api\ApiUsageException;
use MediaWiki\Context\DerivativeContext;
use MediaWiki\Context\RequestContext;
use MediaWiki\Request\FauxRequest;
use UnexpectedValueException;

/**
 * @covers \MediaWiki\Api\ApiContinuationManager
 * @group API
 */
class ApiContinuationManagerTest extends ApiTestCase {

	private static function getManager( $continue, $allModules, $generatedModules ) {
		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setRequest( new FauxRequest( [ 'continue' => $continue ] ) );
		$main = new ApiMain( $context );
		return new ApiContinuationManager( $main, $allModules, $generatedModules );
	}

	public function testContinuation() {
		$allModules = [
			new MockApiQueryBase( 'mock1' ),
			new MockApiQueryBase( 'mock2' ),
			new MockApiQueryBase( 'mocklist' ),
		];
		$generator = new MockApiQueryBase( 'generator' );

		$manager = self::getManager( '', $allModules, [ 'mock1', 'mock2' ] );
		$this->assertSame( ApiMain::class, $manager->getSource() );
		$this->assertSame( false, $manager->isGeneratorDone() );
		$this->assertSame( $allModules, $manager->getRunModules() );
		$manager->addContinueParam( $allModules[0], 'm1continue', [ 1, 2 ] );
		$manager->addContinueParam( $allModules[2], 'mlcontinue', 2 );
		$manager->addGeneratorContinueParam( $generator, 'gcontinue', 3 );
		$this->assertSame( [ [
			'mlcontinue' => 2,
			'm1continue' => '1|2',
			'continue' => '||mock2',
		], false ], $manager->getContinuation() );
		$this->assertSame( [
			'mock1' => [ 'm1continue' => '1|2' ],
			'mocklist' => [ 'mlcontinue' => 2 ],
			'generator' => [ 'gcontinue' => 3 ],
		], $manager->getRawContinuation() );

		$result = new ApiResult( 0 );
		$manager->setContinuationIntoResult( $result );
		$this->assertSame( [
			'mlcontinue' => 2,
			'm1continue' => '1|2',
			'continue' => '||mock2',
		], $result->getResultData( 'continue' ) );
		$this->assertSame( null, $result->getResultData( 'batchcomplete' ) );

		$manager = self::getManager( '', $allModules, [ 'mock1', 'mock2' ] );
		$this->assertSame( false, $manager->isGeneratorDone() );
		$this->assertSame( $allModules, $manager->getRunModules() );
		$manager->addContinueParam( $allModules[0], 'm1continue', [ 1, 2 ] );
		$manager->addGeneratorContinueParam( $generator, 'gcontinue', [ 3, 4 ] );
		$this->assertSame( [ [
			'm1continue' => '1|2',
			'continue' => '||mock2|mocklist',
		], false ], $manager->getContinuation() );
		$this->assertSame( [
			'mock1' => [ 'm1continue' => '1|2' ],
			'generator' => [ 'gcontinue' => '3|4' ],
		], $manager->getRawContinuation() );

		$manager = self::getManager( '', $allModules, [ 'mock1', 'mock2' ] );
		$this->assertSame( false, $manager->isGeneratorDone() );
		$this->assertSame( $allModules, $manager->getRunModules() );
		$manager->addContinueParam( $allModules[2], 'mlcontinue', 2 );
		$manager->addGeneratorContinueParam( $generator, 'gcontinue', 3 );
		$this->assertSame( [ [
			'mlcontinue' => 2,
			'gcontinue' => 3,
			'continue' => 'gcontinue||',
		], true ], $manager->getContinuation() );
		$this->assertSame( [
			'mocklist' => [ 'mlcontinue' => 2 ],
			'generator' => [ 'gcontinue' => 3 ],
		], $manager->getRawContinuation() );

		$result = new ApiResult( 0 );
		$manager->setContinuationIntoResult( $result );
		$this->assertSame( [
			'mlcontinue' => 2,
			'gcontinue' => 3,
			'continue' => 'gcontinue||',
		], $result->getResultData( 'continue' ) );
		$this->assertSame( true, $result->getResultData( 'batchcomplete' ) );

		$manager = self::getManager( '', $allModules, [ 'mock1', 'mock2' ] );
		$this->assertSame( false, $manager->isGeneratorDone() );
		$this->assertSame( $allModules, $manager->getRunModules() );
		$manager->addGeneratorContinueParam( $generator, 'gcontinue', 3 );
		$this->assertSame( [ [
			'gcontinue' => 3,
			'continue' => 'gcontinue||mocklist',
		], true ], $manager->getContinuation() );
		$this->assertSame( [
			'generator' => [ 'gcontinue' => 3 ],
		], $manager->getRawContinuation() );

		$manager = self::getManager( '', $allModules, [ 'mock1', 'mock2' ] );
		$this->assertSame( false, $manager->isGeneratorDone() );
		$this->assertSame( $allModules, $manager->getRunModules() );
		$manager->addContinueParam( $allModules[0], 'm1continue', [ 1, 2 ] );
		$manager->addContinueParam( $allModules[2], 'mlcontinue', 2 );
		$this->assertSame( [ [
			'mlcontinue' => 2,
			'm1continue' => '1|2',
			'continue' => '||mock2',
		], false ], $manager->getContinuation() );
		$this->assertSame( [
			'mock1' => [ 'm1continue' => '1|2' ],
			'mocklist' => [ 'mlcontinue' => 2 ],
		], $manager->getRawContinuation() );

		$manager = self::getManager( '', $allModules, [ 'mock1', 'mock2' ] );
		$this->assertSame( false, $manager->isGeneratorDone() );
		$this->assertSame( $allModules, $manager->getRunModules() );
		$manager->addContinueParam( $allModules[0], 'm1continue', [ 1, 2 ] );
		$this->assertSame( [ [
			'm1continue' => '1|2',
			'continue' => '||mock2|mocklist',
		], false ], $manager->getContinuation() );
		$this->assertSame( [
			'mock1' => [ 'm1continue' => '1|2' ],
		], $manager->getRawContinuation() );

		$manager = self::getManager( '', $allModules, [ 'mock1', 'mock2' ] );
		$this->assertSame( false, $manager->isGeneratorDone() );
		$this->assertSame( $allModules, $manager->getRunModules() );
		$manager->addContinueParam( $allModules[2], 'mlcontinue', 2 );
		$this->assertSame( [ [
			'mlcontinue' => 2,
			'continue' => '-||mock1|mock2',
		], true ], $manager->getContinuation() );
		$this->assertSame( [
			'mocklist' => [ 'mlcontinue' => 2 ],
		], $manager->getRawContinuation() );

		$manager = self::getManager( '', $allModules, [ 'mock1', 'mock2' ] );
		$this->assertSame( false, $manager->isGeneratorDone() );
		$this->assertSame( $allModules, $manager->getRunModules() );
		$this->assertSame( [ [], true ], $manager->getContinuation() );
		$this->assertSame( [], $manager->getRawContinuation() );

		$manager = self::getManager( '||mock2', $allModules, [ 'mock1', 'mock2' ] );
		$this->assertSame( false, $manager->isGeneratorDone() );
		$this->assertSame(
			array_values( array_diff_key( $allModules, [ 1 => 1 ] ) ),
			$manager->getRunModules()
		);

		$manager = self::getManager( '-||', $allModules, [ 'mock1', 'mock2' ] );
		$this->assertSame( true, $manager->isGeneratorDone() );
		$this->assertSame(
			array_values( array_diff_key( $allModules, [ 0 => 0, 1 => 1 ] ) ),
			$manager->getRunModules()
		);

		try {
			self::getManager( 'foo', $allModules, [ 'mock1', 'mock2' ] );
			$this->fail( 'Expected exception not thrown' );
		} catch ( ApiUsageException $ex ) {
			$this->assertApiErrorCode( 'badcontinue', $ex,
				'Expected exception'
			);
		}

		$manager = self::getManager(
			'||mock2',
			array_slice( $allModules, 0, 2 ),
			[ 'mock1', 'mock2' ]
		);
		try {
			$manager->addContinueParam( $allModules[1], 'm2continue', 1 );
			$this->fail( 'Expected exception not thrown' );
		} catch ( UnexpectedValueException $ex ) {
			$this->assertSame(
				'Module \'mock2\' was not supposed to have been executed, ' .
					'but it was executed anyway',
				$ex->getMessage(),
				'Expected exception'
			);
		}
		try {
			$manager->addContinueParam( $allModules[2], 'mlcontinue', 1 );
			$this->fail( 'Expected exception not thrown' );
		} catch ( UnexpectedValueException $ex ) {
			$this->assertSame(
				'Module \'mocklist\' called ' . ApiContinuationManager::class . '::addContinueParam ' .
					'but was not passed to ' . ApiContinuationManager::class . '::__construct',
				$ex->getMessage(),
				'Expected exception'
			);
		}
	}

}

<?php

/**
 * @covers ApiContinuationManager
 * @group API
 */
class ApiContinuationManagerTest extends MediaWikiTestCase {

	private static function getManager( $continue, $allModules, $generatedModules ) {
		$context = new DerivativeContext( RequestContext::getMain() );
		$context->setRequest( new FauxRequest( array( 'continue' => $continue ) ) );
		$main = new ApiMain( $context );
		return new ApiContinuationManager( $main, $allModules, $generatedModules );
	}

	public function testContinuation() {
		$allModules = array(
			new MockApiQueryBase( 'mock1' ),
			new MockApiQueryBase( 'mock2' ),
			new MockApiQueryBase( 'mocklist' ),
		);
		$generator = new MockApiQueryBase( 'generator' );

		$manager = self::getManager( '', $allModules, array( 'mock1', 'mock2' ) );
		$this->assertSame( 'ApiMain', $manager->getSource() );
		$this->assertSame( false, $manager->isGeneratorDone() );
		$this->assertSame( $allModules, $manager->getRunModules() );
		$manager->addContinueParam( $allModules[0], 'm1continue', array( 1, 2 ) );
		$manager->addContinueParam( $allModules[2], 'mlcontinue', 2 );
		$manager->addGeneratorContinueParam( $generator, 'gcontinue', 3 );
		$this->assertSame( array( array(
			'mlcontinue' => 2,
			'm1continue' => '1|2',
			'continue' => '||mock2',
		), false ), $manager->getContinuation() );
		$this->assertSame( array(
			'mock1' => array( 'm1continue' => '1|2' ),
			'mocklist' => array( 'mlcontinue' => 2 ),
			'generator' => array( 'gcontinue' => 3 ),
		), $manager->getRawContinuation() );

		$result = new ApiResult( 0 );
		$manager->setContinuationIntoResult( $result );
		$this->assertSame( array(
			'mlcontinue' => 2,
			'm1continue' => '1|2',
			'continue' => '||mock2',
		), $result->getResultData( 'continue' ) );
		$this->assertSame( null, $result->getResultData( 'batchcomplete' ) );

		$manager = self::getManager( '', $allModules, array( 'mock1', 'mock2' ) );
		$this->assertSame( false, $manager->isGeneratorDone() );
		$this->assertSame( $allModules, $manager->getRunModules() );
		$manager->addContinueParam( $allModules[0], 'm1continue', array( 1, 2 ) );
		$manager->addGeneratorContinueParam( $generator, 'gcontinue', array( 3, 4 ) );
		$this->assertSame( array( array(
			'm1continue' => '1|2',
			'continue' => '||mock2|mocklist',
		), false ), $manager->getContinuation() );
		$this->assertSame( array(
			'mock1' => array( 'm1continue' => '1|2' ),
			'generator' => array( 'gcontinue' => '3|4' ),
		), $manager->getRawContinuation() );

		$manager = self::getManager( '', $allModules, array( 'mock1', 'mock2' ) );
		$this->assertSame( false, $manager->isGeneratorDone() );
		$this->assertSame( $allModules, $manager->getRunModules() );
		$manager->addContinueParam( $allModules[2], 'mlcontinue', 2 );
		$manager->addGeneratorContinueParam( $generator, 'gcontinue', 3 );
		$this->assertSame( array( array(
			'mlcontinue' => 2,
			'gcontinue' => 3,
			'continue' => 'gcontinue||',
		), true ), $manager->getContinuation() );
		$this->assertSame( array(
			'mocklist' => array( 'mlcontinue' => 2 ),
			'generator' => array( 'gcontinue' => 3 ),
		), $manager->getRawContinuation() );

		$result = new ApiResult( 0 );
		$manager->setContinuationIntoResult( $result );
		$this->assertSame( array(
			'mlcontinue' => 2,
			'gcontinue' => 3,
			'continue' => 'gcontinue||',
		), $result->getResultData( 'continue' ) );
		$this->assertSame( true, $result->getResultData( 'batchcomplete' ) );

		$manager = self::getManager( '', $allModules, array( 'mock1', 'mock2' ) );
		$this->assertSame( false, $manager->isGeneratorDone() );
		$this->assertSame( $allModules, $manager->getRunModules() );
		$manager->addGeneratorContinueParam( $generator, 'gcontinue', 3 );
		$this->assertSame( array( array(
			'gcontinue' => 3,
			'continue' => 'gcontinue||mocklist',
		), true ), $manager->getContinuation() );
		$this->assertSame( array(
			'generator' => array( 'gcontinue' => 3 ),
		), $manager->getRawContinuation() );

		$manager = self::getManager( '', $allModules, array( 'mock1', 'mock2' ) );
		$this->assertSame( false, $manager->isGeneratorDone() );
		$this->assertSame( $allModules, $manager->getRunModules() );
		$manager->addContinueParam( $allModules[0], 'm1continue', array( 1, 2 ) );
		$manager->addContinueParam( $allModules[2], 'mlcontinue', 2 );
		$this->assertSame( array( array(
			'mlcontinue' => 2,
			'm1continue' => '1|2',
			'continue' => '||mock2',
		), false ), $manager->getContinuation() );
		$this->assertSame( array(
			'mock1' => array( 'm1continue' => '1|2' ),
			'mocklist' => array( 'mlcontinue' => 2 ),
		), $manager->getRawContinuation() );

		$manager = self::getManager( '', $allModules, array( 'mock1', 'mock2' ) );
		$this->assertSame( false, $manager->isGeneratorDone() );
		$this->assertSame( $allModules, $manager->getRunModules() );
		$manager->addContinueParam( $allModules[0], 'm1continue', array( 1, 2 ) );
		$this->assertSame( array( array(
			'm1continue' => '1|2',
			'continue' => '||mock2|mocklist',
		), false ), $manager->getContinuation() );
		$this->assertSame( array(
			'mock1' => array( 'm1continue' => '1|2' ),
		), $manager->getRawContinuation() );

		$manager = self::getManager( '', $allModules, array( 'mock1', 'mock2' ) );
		$this->assertSame( false, $manager->isGeneratorDone() );
		$this->assertSame( $allModules, $manager->getRunModules() );
		$manager->addContinueParam( $allModules[2], 'mlcontinue', 2 );
		$this->assertSame( array( array(
			'mlcontinue' => 2,
			'continue' => '-||mock1|mock2',
		), true ), $manager->getContinuation() );
		$this->assertSame( array(
			'mocklist' => array( 'mlcontinue' => 2 ),
		), $manager->getRawContinuation() );

		$manager = self::getManager( '', $allModules, array( 'mock1', 'mock2' ) );
		$this->assertSame( false, $manager->isGeneratorDone() );
		$this->assertSame( $allModules, $manager->getRunModules() );
		$this->assertSame( array( array(), true ), $manager->getContinuation() );
		$this->assertSame( array(), $manager->getRawContinuation() );

		$manager = self::getManager( '||mock2', $allModules, array( 'mock1', 'mock2' ) );
		$this->assertSame( false, $manager->isGeneratorDone() );
		$this->assertSame(
			array_values( array_diff_key( $allModules, array( 1 => 1 ) ) ),
			$manager->getRunModules()
		);

		$manager = self::getManager( '-||', $allModules, array( 'mock1', 'mock2' ) );
		$this->assertSame( true, $manager->isGeneratorDone() );
		$this->assertSame(
			array_values( array_diff_key( $allModules, array( 0 => 0, 1 => 1 ) ) ),
			$manager->getRunModules()
		);

		try {
			self::getManager( 'foo', $allModules, array( 'mock1', 'mock2' ) );
			$this->fail( 'Expected exception not thrown' );
		} catch ( UsageException $ex ) {
			$this->assertSame(
				'Invalid continue param. You should pass the original value returned by the previous query',
				$ex->getMessage(),
				'Expected exception'
			);
		}

		$manager = self::getManager( '||mock2', array_slice( $allModules, 0, 2 ), array( 'mock1', 'mock2' ) );
		try {
			$manager->addContinueParam( $allModules[1], 'm2continue', 1 );
			$this->fail( 'Expected exception not thrown' );
		} catch ( UnexpectedValueException $ex ) {
			$this->assertSame(
				'Module \'mock2\' was not supposed to have been executed, but it was executed anyway',
				$ex->getMessage(),
				'Expected exception'
			);
		}
		try {
			$manager->addContinueParam( $allModules[2], 'mlcontinue', 1 );
			$this->fail( 'Expected exception not thrown' );
		} catch ( UnexpectedValueException $ex ) {
			$this->assertSame(
				'Module \'mocklist\' called ApiContinuationManager::addContinueParam but was not passed to ApiContinuationManager::__construct',
				$ex->getMessage(),
				'Expected exception'
			);
		}

	}

}

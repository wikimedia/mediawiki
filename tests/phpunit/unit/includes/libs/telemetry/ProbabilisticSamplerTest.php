<?php
namespace Wikimedia\Tests\Telemetry;

use MediaWikiUnitTestCase;
use Wikimedia\Assert\ParameterAssertionException;
use Wikimedia\Telemetry\ProbabilisticSampler;
use Wikimedia\Telemetry\SamplerInterface;
use Wikimedia\Telemetry\SpanContext;

/**
 * @covers \Wikimedia\Telemetry\ProbabilisticSampler
 */
class ProbabilisticSamplerTest extends MediaWikiUnitTestCase {
	private const DO_NOT_SAMPLE_SEED = 12;
	private const SAMPLE_SEED = 5;

	private SamplerInterface $sampler;

	public function setUp(): void {
		parent::setUp();
		$this->sampler = new ProbabilisticSampler( 30 );
	}

	public function testShouldNotSampleSpanWithNoParentBasedOnChance(): void {
		mt_srand( self::DO_NOT_SAMPLE_SEED );

		$sampled = $this->sampler->shouldSample( null );

		$this->assertFalse( $sampled );
	}

	public function testShouldSampleSpanWithNoParentBasedOnChance(): void {
		mt_srand( self::SAMPLE_SEED );

		$sampled = $this->sampler->shouldSample( null );

		$this->assertTrue( $sampled );
	}

	public function testShouldSampleSpanWithParentBasedOnParentDecision(): void {
		mt_srand( self::DO_NOT_SAMPLE_SEED );

		$parentSpanContext = new SpanContext( '', '', null, '', true );

		$sampled = $this->sampler->shouldSample( $parentSpanContext );

		$this->assertTrue( $sampled );
	}

	public function testShouldNotSampleSpanWithParentBasedOnParentDecision(): void {
		mt_srand( self::SAMPLE_SEED );

		$parentSpanContext = new SpanContext( '', '', null, '', false );

		$sampled = $this->sampler->shouldSample( $parentSpanContext );

		$this->assertFalse( $sampled );
	}

	public function testShouldThrowOnInvalidPercentChance(): void {
		$this->expectException( ParameterAssertionException::class );
		$this->expectExceptionMessage( 'Bad value for parameter $percentChance: must be between 0 and 100 inclusive' );

		new ProbabilisticSampler( 900 );
	}
}

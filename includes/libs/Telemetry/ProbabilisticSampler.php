<?php
namespace Wikimedia\Telemetry;

use Wikimedia\Assert\Assert;

/**
 * A {@link SamplerInterface} implementation that samples a given percentage of root spans,
 * while respecting sampling decisions made by other samplers for a given trace.
 *
 * @since 1.43
 * @internal
 */
class ProbabilisticSampler implements SamplerInterface {
	/**
	 * The chance of sampling a root span, as a percentage (0-100).
	 * @var int
	 */
	private int $percentChance;

	public function __construct( int $percentChance ) {
		Assert::parameter(
			$percentChance >= 0 && $percentChance <= 100,
			'$percentChance',
			'must be between 0 and 100 inclusive'
		);
		$this->percentChance = $percentChance;
	}

	/** @inheritDoc */
	public function shouldSample( ?SpanContext $parentSpanContext ): bool {
		if ( $parentSpanContext !== null ) {
			return $parentSpanContext->isSampled();
		}

		return mt_rand( 1, 100 ) <= $this->percentChance;
	}
}

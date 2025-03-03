<?php
namespace Wikimedia\Telemetry;

/**
 * Interface for OTEL span samplers.
 * @since 1.43
 */
interface SamplerInterface {
	/**
	 * Determine whether a newly created span should be sampled based on its parent span data.
	 *
	 * @param SpanContext|null $parentSpanContext Context of the parent span of the newly created span,
	 * or `null` if the newly created span is a root span.
	 * @return bool Whether the newly created span should be sampled.
	 */
	public function shouldSample( ?SpanContext $parentSpanContext ): bool;
}

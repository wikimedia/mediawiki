<?php
namespace Wikimedia\Telemetry;

/**
 * Interface for classes to serialize and deserialize SpanContexts to and from
 * implementation-specific arrays, called carriers.  Usually the carrier will
 * be HTTP request headers or similar.
 * @see https://opentelemetry.io/docs/specs/otel/context/api-propagators/
 * @since 1.44
 */
interface ContextPropagatorInterface {
	/**
	 * Inject the given SpanContext into the given carrier.
	 * SpanContext may be null, in which case the propagator might
	 * still inject non-span-specific data.
	 * @return array carrier with SpanContext injected
	 */
	public function inject( ?SpanContext $spanContext, array $carrier ): array;

	/**
	 * Attempt to extract a SpanContext from the given carrier.
	 */
	public function extract( array $carrier ): ?SpanContext;
}

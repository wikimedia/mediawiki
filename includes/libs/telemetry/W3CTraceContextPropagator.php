<?php
namespace Wikimedia\Telemetry;

/**
 * A {@link ContextPropagatorInterface} implementation for W3C Trace Context.
 *
 * This currently only supports a minimal subset of the spec: just the
 * traceparent header, and only version 00.
 *
 * It will refuse to inject a traceparent that would be invalid according
 * to version 00 of the spec.
 *
 * @see https://www.w3.org/TR/trace-context/
 * @since 1.43
 * @internal
 */
class W3CTraceContextPropagator implements ContextPropagatorInterface {
	/**
	 * @inheritDoc
	 */
	public function inject( ?SpanContext $spanContext, array $carrier ): array {
		if ( $spanContext === null ) {
			return $carrier;
		}
		$traceId = $spanContext->getTraceId();
		$spanId = $spanContext->getSpanId();
		$sampled = $spanContext->isSampled() ? '01' : '00';
		if ( strlen( $traceId ) === 32 && strlen( $spanId ) === 16 ) {
			$carrier['traceparent'] = "00-{$traceId}-{$spanId}-{$sampled}";
		}

		return $carrier;
	}

	/**
	 * @inheritDoc
	 */
	public function extract( array $carrier ): ?SpanContext {
		$carrier = array_change_key_case( $carrier, CASE_LOWER );
		if ( !isset( $carrier['traceparent'] ) ) {
			return null;
		}
		$matches = [];
		if ( !preg_match( '/^00-([0-9a-f]{32})-([0-9a-f]{16})-([0-9a-f]{2})$/', $carrier['traceparent'], $matches ) ) {
			return null;
		}
		return new SpanContext(
			$matches[1],
			$matches[2],
			null,
			'',
			( hexdec( $matches[3] ) & 1 ) === 1
		);
	}
}

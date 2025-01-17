<?php
namespace MediaWiki\Telemetry;

use Wikimedia\Http\TelemetryHeadersInterface;
use Wikimedia\Telemetry\ContextPropagatorInterface;
use Wikimedia\Telemetry\SpanContext;

/**
 * A {@link ContextPropagatorInterface} implementation that injects headers
 * from MediaWiki's request context, and does not extract any context.
 * @since 1.44
 * @internal
 */
class MediaWikiPropagator implements ContextPropagatorInterface {

	private TelemetryHeadersInterface $mwTelemetry;

	public function __construct( TelemetryHeadersInterface $mwTelemetry ) {
		$this->mwTelemetry = $mwTelemetry;
	}

	/**
	 * @inheritDoc
	 */
	public function inject( ?SpanContext $spanContext, array $carrier ): array {
		foreach ( $this->mwTelemetry->getRequestHeaders() as $key => $value ) {
			$carrier[$key] = $value;
		}
		return $carrier;
	}

	/**
	 * @inheritDoc
	 */
	public function extract( array $carrier ): ?SpanContext {
		return null;
	}
}

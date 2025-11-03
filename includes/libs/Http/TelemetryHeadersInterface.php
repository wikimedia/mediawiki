<?php

namespace Wikimedia\Http;

/**
 * Provide Request Telemetry information
 * @unstable
 * @since 1.41
 */
interface TelemetryHeadersInterface {

	/**
	 * Get Headers to be attached to outgoing requests.
	 *
	 * Caution: Telemetry headers should not be attached to requests bound for external/untrusted services.
	 * They are intended and designed to allow correlation of requests from clients, which can
	 * be a privacy concern.  In the future, telemetry might even contain explicit user identifiers or
	 * other sensitive information.
	 *
	 * @return array<string, string> Headers array
	 */
	public function getRequestHeaders(): array;
}

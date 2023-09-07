<?php

namespace Wikimedia\Http;

/**
 * Provide Request Telemetry information
 * @unstable
 * @since 1.41
 */
interface TelemetryHeadersInterface {

	/**
	 * Get Headers to outgoing requests
	 * @return array<string, string> Headers array
	 */
	public function getRequestHeaders(): array;
}

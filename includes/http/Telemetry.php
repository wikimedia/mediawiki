<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */
namespace MediaWiki\Http;

use Wikimedia\Http\TelemetryHeadersInterface;

/**
 * Service for handling telemetry data
 * @unstable
 * @since 1.41
 */
class Telemetry implements TelemetryHeadersInterface {

	/**
	 * @var Telemetry|null
	 */
	private static ?Telemetry $instance = null;

	/**
	 *
	 * @var string|null Request id
	 */
	private ?string $reqId = null;

	/**
	 * Server and execution environment information.
	 *
	 * @see https://www.php.net/manual/en/reserved.variables.server.php
	 * @var array
	 */
	private array $server;

	private ?bool $allowExternalReqID;

	/**
	 * @param array $server Server and execution environment information, most likely the $_SERVER variable
	 */
	public function __construct( array $server, bool $allowExternalReqID = null ) {
		$this->server = $server;
		$this->allowExternalReqID = $allowExternalReqID;
	}

	public static function getInstance(): Telemetry {
		if ( !isset( self::$instance ) ) {
			global $wgAllowExternalReqID;
			self::$instance = new self( $_SERVER, $wgAllowExternalReqID );
		}

		return self::$instance;
	}

	/**
	 * Get the current request ID.
	 *
	 * This is usually based on the `X-Request-Id` header, or the `UNIQUE_ID`
	 * environment variable, falling back to (process cached) randomly-generated string.
	 *
	 * @return string
	 */
	public function getRequestId(): string {
		// This method is called from various error handlers and MUST be kept simple and stateless.
		if ( !isset( $this->reqId ) ) {
			if ( $this->allowExternalReqID ) {
				$id = ( $this->server['HTTP_X_REQUEST_ID'] ?? $this->server['UNIQUE_ID'] ?? wfRandomString( 24 ) );
			} else {
				$id = ( $this->server['UNIQUE_ID'] ?? wfRandomString( 24 ) );
			}

			$this->reqId = $id;
		}

		return $this->reqId;
	}

	/**
	 * Override the unique request ID. This is for sub-requests, such as jobs,
	 * that wish to use the same id but are not part of the same execution context.
	 *
	 * @param string $newId
	 */
	public function overrideRequestId( string $newId ): void {
		$this->reqId = $newId;
	}

	/**
	 * Regenerate the request id by setting it to null, next call to `getRequestId`
	 * will refetch the request id from header/UNIQUE_ID or regenerate it.
	 * @return void
	 */
	public function regenerateRequestId() {
		$this->reqId = null;
	}

	/**
	 * Get the OpenTelemetry tracestate info
	 * Returns null when not present or AllowExternalReqID is set to false
	 *
	 * @return string|null
	 */
	public function getTracestate(): ?string {
		return $this->allowExternalReqID ? $this->server['HTTP_TRACESTATE'] ?? null : null;
	}

	/**
	 * Get the OpenTelemetry traceparent info,
	 * Returns null when not present or AllowExternalReqID is set to false
	 *
	 * @return string|null
	 */
	public function getTraceparent(): ?string {
		return $this->allowExternalReqID ? $this->server['HTTP_TRACEPARENT'] ?? null : null;
	}

	/**
	 * Return Telemetry data in form of request headers
	 * @return array
	 */
	public function getRequestHeaders(): array {
		return array_filter( [
			'tracestate' => $this->getTracestate(),
			'traceparent' => $this->getTraceparent(),
			'X-Request-Id' => $this->getRequestId()
		] );
	}
}

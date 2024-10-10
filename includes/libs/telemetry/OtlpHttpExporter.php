<?php
namespace Wikimedia\Telemetry;

use GuzzleHttp\Psr7\Utils;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Log\LoggerInterface;

/**
 * An {@link ExporterInterface} that exports collected data over HTTP, serialized in OTLP JSON format.
 *
 * @since 1.43
 * @internal
 */
class OtlpHttpExporter implements ExporterInterface {
	private ClientInterface $client;
	private RequestFactoryInterface $requestFactory;
	private LoggerInterface $logger;

	/**
	 * URI of the OTLP receiver endpoint to send data to.
	 * @var string
	 */
	private string $endpoint;

	/**
	 * Descriptive name used to identify this service in traces.
	 * @var string
	 */
	private string $serviceName;

	/**
	 * The host name of this server to be reported in traces.
	 * @var string
	 */
	private string $hostName;

	public function __construct(
		ClientInterface $client,
		RequestFactoryInterface $requestFactory,
		LoggerInterface $logger,
		string $uri,
		string $serviceName,
		string $hostName
	) {
		$this->client = $client;
		$this->requestFactory = $requestFactory;
		$this->logger = $logger;
		$this->endpoint = $uri;
		$this->serviceName = $serviceName;
		$this->hostName = $hostName;
	}

	/** @inheritDoc */
	public function export( TracerState $tracerState ): void {
		$spanContexts = $tracerState->getSpanContexts();
		if ( count( $spanContexts ) === 0 ) {
			return;
		}

		$resourceInfo = array_filter( [
			'service.name' => $this->serviceName,
			'host.name' => $this->hostName,
			"server.socket.address" => $_SERVER['SERVER_ADDR'] ?? null,
		] );

		$data = [
			'resourceSpans' => [
				[
					'resource' => [
						'attributes' => OtlpSerializer::serializeKeyValuePairs( $resourceInfo )
					],
					'scopeSpans' => [
						[
							'scope' => [
								'name' => 'org.wikimedia.telemetry',
							],
							'spans' => $spanContexts
						]
					]
				]
			]
		];

		$request = $this->requestFactory->createRequest( 'POST', $this->endpoint )
			->withHeader( 'Content-Type', 'application/json' )
			->withBody( Utils::streamFor( json_encode( $data ) ) );

		try {
			$response = $this->client->sendRequest( $request );
			if ( $response->getStatusCode() !== 200 ) {
				$this->logger->error( 'Failed to export trace data' );
			}
		} catch ( ClientExceptionInterface $e ) {
			$this->logger->error( 'Failed to connect to exporter', [ 'exception' => $e ] );
		}

		// Clear out finished spans after exporting them.
		$tracerState->clearSpanContexts();
	}
}

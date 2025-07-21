<?php
namespace MediaWiki\Notification;

use Wikimedia\ObjectFactory\ObjectFactory;

/**
 * @since 1.45
 */
class MiddlewareChain {
	/**
	 * @var NotificationMiddlewareInterface[]
	 */
	private array $middleware = [];

	private ObjectFactory $objectFactory;
	private array $middlewareSpecs;
	private bool $loadedFromSpecs = false;

	private bool $isProcessing = false;

	public function __construct( ObjectFactory $objectFactory, array $specs ) {
		$this->objectFactory = $objectFactory;
		$this->middlewareSpecs = $specs;
	}

	public function process( NotificationsBatch $batch ): NotificationsBatch {
		if ( $this->isProcessing ) {
			// If you need to send an additional notification from middleware,
			// use NotificationBatch::add() instead of calling NotificationService from middleware
			throw new MiddlewareException(
				"Middleware cannot re-trigger the notification processing while it is already in progress. "
			);
		}
		$this->isProcessing = true;

		if ( !$this->loadedFromSpecs ) {
			foreach ( $this->middlewareSpecs as $spec ) {
				$this->middleware[] = $this->objectFactory->createObject( $spec, [
					'assertClass' => NotificationMiddlewareInterface::class
				] );
			}
			$this->loadedFromSpecs = true;
		}

		$this->callNext( $batch, 0 );
		$this->isProcessing = false;
		return $batch;
	}

	private function callNext( NotificationsBatch $batch, int $index ): void {
		if ( $index < count( $this->middleware ) ) {
			$this->middleware[$index]->handle(
				$batch,
				function () use ( $batch, $index ) {
					$this->callNext( $batch, $index + 1 );
				}
			);
		}
	}
}

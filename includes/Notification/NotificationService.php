<?php

namespace MediaWiki\Notification;

use MediaWiki\User\UserIdentity;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Wikimedia\Message\MessageSpecifier;
use Wikimedia\ObjectFactory\ObjectFactory;

/**
 * Notify users about things occurring.
 *
 * @since 1.44
 * @unstable
 */
class NotificationService {

	/** @var array<string,NotificationHandler> */
	private array $handlersByType = [];

	private LoggerInterface $logger;
	private ObjectFactory $objectFactory;
	private array $specs;

	public function __construct(
		LoggerInterface $logger,
		ObjectFactory $objectFactory,
		array $specs
	) {
		$this->logger = $logger;
		$this->objectFactory = $objectFactory;
		$this->specs = $specs;
	}

	private function getHandlers() {
		if ( $this->handlersByType === [] ) {
			foreach ( $this->specs as $spec ) {
				$obj = $this->objectFactory->createObject( $spec, [ 'assertClass' => NotificationHandler::class ] );
				foreach ( $spec['types'] as $type ) {
					if ( str_contains( $type, '*' ) && $type !== '*' ) {
						// In the future we may allow more complex wildcards than setting the whole type to '*'
						throw new RuntimeException( "Partial wildcards are not supported, tried to use \"$type\"" );
					}
					if ( isset( $this->handlersByType[$type] ) ) {
						// In the future we may add something to allow overrides
						throw new RuntimeException( "Handler for notification type \"$type\" already present" );
					}
					$this->handlersByType[$type] = $obj;
				}
			}
		}
		return $this->handlersByType;
	}

	/**
	 * Notify users about an event occurring. This method allows providing custom notification data to
	 * be handled by extensions, and defining multiple recipients.
	 */
	public function notify( Notification $notification, RecipientSet $recipients ): void {
		$handlers = $this->getHandlers();
		$handler = $handlers[$notification->getType()] ?? $handlers['*'] ?? null;
		if ( $handler === null ) {
			$this->logger->warning( "No handler defined for notification type {type}", [
				'type' => $notification->getType(),
			] );
			return;
		}
		$handler->notify( $notification, $recipients );
	}

	/**
	 * Notify a user with a message.
	 */
	public function notifySimple(
		MessageSpecifier $message,
		UserIdentity $recipient
	): void {
		$notification = new Types\SimpleNotification( $message );
		$this->notify( $notification, new RecipientSet( [ $recipient ] ) );
	}
}

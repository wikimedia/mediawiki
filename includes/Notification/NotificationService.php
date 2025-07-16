<?php

namespace MediaWiki\Notification;

use MediaWiki\RecentChanges\RecentChangeNotificationHandler;
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

	/**
	 * MediaWiki's notification handler for watchlist, talk page, and admin notification
	 */
	public const RECENT_CHANGE_HANDLER_SPEC = [
		'class' => RecentChangeNotificationHandler::class,
		'services' => [
			'UserFactory',
			'TitleFactory',
		],
		'types' => [ 'mediawiki.recent_change' ]
	];

	/** @var array<string,NotificationHandler> */
	private array $handlersByType = [];

	private LoggerInterface $logger;
	private ObjectFactory $objectFactory;
	private MiddlewareChain $middlewareChain;
	private array $specs;

	public function __construct(
		LoggerInterface $logger,
		ObjectFactory $objectFactory,
		MiddlewareChain $middlewareChain,
		array $specs
	) {
		$this->logger = $logger;
		$this->objectFactory = $objectFactory;
		$this->middlewareChain = $middlewareChain;
		$this->specs = $specs;
	}

	private function getHandlers(): array {
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

		// IDEA - this can queue all notifications and send on POST_SEND
		// The middleware can handle things like filter out duplicates?

		$batch = $this->middlewareChain->process(
			new NotificationsBatch( new NotificationEnvelope( $notification, $recipients ) )
		);

		// TODO $handler could take the entire batch instead of single notification
		// TODO do we want to pick handlers one by one? IMHO it should fan out and let different
		// handlers to handle notifications the way the want, for example IRC handler send irc
		foreach ( $batch as $envelope ) {
			$scheduledNotification = $envelope->getNotification();
			$scheduledRecipients = $envelope->getRecipientSet();
			$handler = $handlers[$scheduledNotification->getType()] ?? $handlers['*'] ?? null;
			if ( $handler === null ) {
				$this->logger->warning( "No handler defined for notification type {type}", [
					'type' => $scheduledNotification->getType(),
				] );
				return;
			}
			$handler->notify( $scheduledNotification, $scheduledRecipients );
		}
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

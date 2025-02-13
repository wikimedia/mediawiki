<?php

namespace MediaWiki\Languages;

use MediaWiki\DomainEvent\EventSubscriberBase;
use MediaWiki\Page\Event\PageUpdatedEvent;
use MediaWiki\Revision\SlotRecord;
use MessageCache;

/**
 * The ingress adapter for the language component. It updates language related
 * state according to domain events coming from other components.
 *
 * @internal
 */
class LanguageEventIngress extends EventSubscriberBase {

	private MessageCache $messageCache;

	/** Object spec intended for use with {@link DomainEventSource::registerSubscriber()} */
	public const OBJECT_SPEC = [
		'class' => self::class,
		'services' => [
			'MessageCache'
		],
		'events' => [
			PageUpdatedEvent::TYPE
		],
	];

	public function __construct( MessageCache $messageCache ) {
		$this->messageCache = $messageCache;
	}

	/**
	 * Listener method for PageUpdatedEvent, to be registered with a DomainEventSource.
	 *
	 * @noinspection PhpUnused
	 */
	public function handlePageUpdatedEventAfterCommit( PageUpdatedEvent $event ) {
		if ( $event->getPage()->getNamespace() === NS_MEDIAWIKI	&&
			( $event->isModifiedSlot( SlotRecord::MAIN )
				|| $event->hasCause( PageUpdatedEvent::CAUSE_MOVE )
				|| $event->isReconciliationRequest()
			)
		) {
			$content = $event->getNewRevision()->getMainContentRaw();
			$this->messageCache->updateMessageOverride( $event->getPage(), $content );
		}
	}

}

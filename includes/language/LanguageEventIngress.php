<?php

namespace MediaWiki\Languages;

use MediaWiki\DomainEvent\EventSubscriberBase;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Storage\PageUpdatedEvent;
use MessageCache;

/**
 * The ingres adapter for the language component. It updates language related
 * state according to domain events coming from other components.
 *
 * @internal
 */
class LanguageEventIngress extends EventSubscriberBase {

	private MessageCache $messageCache;

	/** Object spec intented for use with {@link DomainEventSource::registerSubscriber()} */
	public const OBJECT_SPEC = [
		'class' => self::class,
		'services' => [
			'MessageCache'
		],
		'events' => [
			PageUpdatedEvent::TYPE
		],
	];

	/**
	 * @param MessageCache $messageCache
	 */
	public function __construct( MessageCache $messageCache ) {
		$this->messageCache = $messageCache;
	}

	/**
	 * Listener method for PageUpdatedEvent, to be registered with a DomainEventSource.
	 *
	 * @noinspection PhpUnused
	 */
	public function handlePageUpdatedEventAfterCommit( PageUpdatedEvent $event ) {
		if ( $event->getPage()->getNamespace() === NS_MEDIAWIKI
			&& $event->isModifiedSlot( SlotRecord::MAIN )
		) {
			$slot = $event->getNewRevision()->getSlot( SlotRecord::MAIN, RevisionRecord::RAW );
			$this->messageCache->updateMessageOverride( $event->getPage(), $slot->getContent() );
		}
	}

}

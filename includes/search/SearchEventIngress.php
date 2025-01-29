<?php

namespace MediaWiki\Search;

use MediaWiki\DomainEvent\EventSubscriberBase;
use MediaWiki\Page\Event\PageUpdatedEvent;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;

/**
 * The ingress adapter for the search component. It updates search related state
 * according to domain events coming from other components.
 *
 * @internal
 */
class SearchEventIngress extends EventSubscriberBase {

	/** Object spec intended for use with {@link DomainEventSource::registerSubscriber()} */
	public const OBJECT_SPEC = [
		'class' => self::class,
		'services' => [],
		'events' => [
			PageUpdatedEvent::TYPE
		],
	];

	/**
	 * Listener method for PageUpdatedEvent, to be registered with a DomainEventSource.
	 *
	 * @noinspection PhpUnused
	 */
	public function handlePageUpdatedEventAfterCommit( PageUpdatedEvent $event ) {
		$newRevision = $event->getNewRevision();
		$mainSlot = $newRevision->getSlot( SlotRecord::MAIN );
		if (
			( $event->isModifiedSlot( SlotRecord::MAIN )
				|| $event->hasCause( PageUpdatedEvent::CAUSE_MOVE ) ) &&
			!$newRevision->isDeleted( RevisionRecord::DELETED_TEXT )
		) {
			// NOTE: no need to go through DeferredUpdates,
			// we are already deferred.
			$update = new SearchUpdate(
				$event->getPage()->getId(),
				$event->getPage(),
				$mainSlot->getContent()
			);

			// No need to schedule a DeferredUpdate, listeners use deferred
			// delivery anyway.
			$update->doUpdate();
		}
	}

}

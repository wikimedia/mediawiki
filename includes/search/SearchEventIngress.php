<?php

namespace MediaWiki\Search;

use MediaWiki\Deferred\SearchUpdate;
use MediaWiki\DomainEvent\EventSubscriberBase;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Storage\PageUpdatedEvent;

/**
 * The ingres adapter for the search component. It updates search related state
 * according to domain events coming from other components.
 *
 * @internal
 */
class SearchEventIngress extends EventSubscriberBase {

	/** Object spec intented for use with {@link DomainEventSource::registerSubscriber()} */
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
			!$mainSlot->isInherited() &&
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

<?php

namespace MediaWiki\Search;

use MediaWiki\DomainEvent\EventIngressBase;
use MediaWiki\Page\Event\PageDeletedEvent;
use MediaWiki\Page\Event\PageRevisionUpdatedEvent;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;

/**
 * The ingress adapter for the search component. It updates search related state
 * according to domain events coming from other components.
 *
 * @internal
 */
class SearchEventIngress extends EventIngressBase {

	/** Object spec intended for use with {@link DomainEventSource::registerSubscriber()} */
	public const OBJECT_SPEC = [
		'class' => self::class,
		'services' => [],
		'events' => [
			PageRevisionUpdatedEvent::TYPE,
			PageDeletedEvent::TYPE,
		],
	];

	/**
	 * Listener method for PageRevisionUpdatedEvent, to be registered with a DomainEventSource.
	 *
	 * @noinspection PhpUnused
	 */
	public function handlePageRevisionUpdatedEvent( PageRevisionUpdatedEvent $event ) {
		$newRevision = $event->getLatestRevisionAfter();
		$mainSlot = $newRevision->isDeleted( RevisionRecord::DELETED_TEXT )
			? null : $newRevision->getSlot( SlotRecord::MAIN );

		if (
			$event->isModifiedSlot( SlotRecord::MAIN ) ||
			$event->hasCause( PageRevisionUpdatedEvent::CAUSE_MOVE ) ||
			$event->isReconciliationRequest()
		) {
			$update = new SearchUpdate(
				$event->getPage()->getId(),
				$event->getPage(),
				$mainSlot ? $mainSlot->getContent() : null
			);

			$update->doUpdate();
		}
	}

	/**
	 * Listener method for PageDeletedEvent, to be registered with a DomainEventSource.
	 *
	 * @noinspection PhpUnused
	 */
	public function handlePageDeletedEventAfterCommit( PageDeletedEvent $event ) {
		$update = new SearchUpdate(
			$event->getPageStateBefore()->getId(),
			$event->getPage(),
			null
		);

		$update->doUpdate();
	}

}

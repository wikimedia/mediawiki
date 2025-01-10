<?php

namespace MediaWiki\Search;

use MediaWiki\DomainEvent\EventSubscriberBase;
use MediaWiki\Page\Event\PageDeletedEvent;
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
			PageUpdatedEvent::TYPE,
			PageDeletedEvent::TYPE,
		],
	];

	/**
	 * Listener method for PageUpdatedEvent, to be registered with a DomainEventSource.
	 *
	 * @noinspection PhpUnused
	 */
	public function handlePageUpdatedEventAfterCommit( PageUpdatedEvent $event ) {
		$newRevision = $event->getNewRevision();
		$mainSlot = $newRevision->isDeleted( RevisionRecord::DELETED_TEXT )
			? null : $newRevision->getSlot( SlotRecord::MAIN );

		if (
			$event->isModifiedSlot( SlotRecord::MAIN ) ||
			$event->hasCause( PageUpdatedEvent::CAUSE_MOVE ) ||
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

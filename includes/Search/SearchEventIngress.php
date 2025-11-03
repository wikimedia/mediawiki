<?php

namespace MediaWiki\Search;

use MediaWiki\DomainEvent\DomainEventIngress;
use MediaWiki\Page\Event\PageDeletedEvent;
use MediaWiki\Page\Event\PageDeletedListener;
use MediaWiki\Page\Event\PageLatestRevisionChangedEvent;
use MediaWiki\Page\Event\PageLatestRevisionChangedListener;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;

/**
 * The ingress adapter for the search component. It updates search related state
 * according to domain events coming from other components.
 *
 * @internal
 */
class SearchEventIngress
	extends DomainEventIngress
	implements PageDeletedListener, PageLatestRevisionChangedListener
{

	/** Object spec intended for use with {@link DomainEventSource::registerSubscriber()} */
	public const OBJECT_SPEC = [
		'class' => self::class,
		'services' => [],
		'events' => [
			PageLatestRevisionChangedEvent::TYPE,
			PageDeletedEvent::TYPE,
		],
	];

	/**
	 * Listener method for PageLatestRevisionChangedEvent, to be registered with a DomainEventSource.
	 *
	 * @noinspection PhpUnused
	 */
	public function handlePageLatestRevisionChangedEvent( PageLatestRevisionChangedEvent $event ): void {
		$newRevision = $event->getLatestRevisionAfter();
		$mainSlot = $newRevision->isDeleted( RevisionRecord::DELETED_TEXT )
			? null : $newRevision->getSlot( SlotRecord::MAIN );

		if (
			$event->isModifiedSlot( SlotRecord::MAIN ) ||
			$event->hasCause( PageLatestRevisionChangedEvent::CAUSE_MOVE ) ||
			$event->isReconciliationRequest()
		) {
			$update = new SearchUpdate(
				$event->getPageId(),
				$event->getPageRecordAfter(),
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
	public function handlePageDeletedEvent( PageDeletedEvent $event ): void {
		$update = new SearchUpdate(
			$event->getPageId(),
			$event->getDeletedPage(),
			null
		);

		$update->doUpdate();
	}

}

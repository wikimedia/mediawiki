<?php

namespace MediaWiki\Languages;

use MediaWiki\DomainEvent\DomainEventIngress;
use MediaWiki\Page\Event\PageDeletedEvent;
use MediaWiki\Page\Event\PageDeletedListener;
use MediaWiki\Page\Event\PageRevisionUpdatedEvent;
use MediaWiki\Page\Event\PageRevisionUpdatedListener;
use MediaWiki\Revision\SlotRecord;
use MessageCache;

/**
 * The ingress adapter for the language component. It updates language related
 * state according to domain events coming from other components.
 *
 * @internal
 */
class LanguageEventIngress
	extends DomainEventIngress
	implements PageDeletedListener, PageRevisionUpdatedListener
{

	private MessageCache $messageCache;

	/** Object spec intended for use with {@link DomainEventSource::registerSubscriber()} */
	public const OBJECT_SPEC = [
		'class' => self::class,
		'services' => [
			'MessageCache'
		],
		'events' => [
			PageRevisionUpdatedEvent::TYPE,
			PageDeletedEvent::TYPE
		],
	];

	public function __construct( MessageCache $messageCache ) {
		$this->messageCache = $messageCache;
	}

	/**
	 * Listener method for PageRevisionUpdatedEvent, to be registered with a DomainEventSource.
	 *
	 * @noinspection PhpUnused
	 */
	public function handlePageRevisionUpdatedEvent( PageRevisionUpdatedEvent $event	) {
		if ( $event->getPage()->getNamespace() === NS_MEDIAWIKI	&&
			( $event->isModifiedSlot( SlotRecord::MAIN )
				|| $event->hasCause( PageRevisionUpdatedEvent::CAUSE_MOVE )
				|| $event->isReconciliationRequest()
			)
		) {
			$content = $event->getLatestRevisionAfter()->getMainContentRaw();
			$this->messageCache->updateMessageOverride( $event->getPage(), $content );
		}
	}

	/**
	 * Listener method for PageDeletedEvent, to be registered with a DomainEventSource.
	 *
	 * @noinspection PhpUnused
	 */
	public function handlePageDeletedEvent( PageDeletedEvent $event ) {
		if ( $event->getDeletedPage()->getNamespace() === NS_MEDIAWIKI ) {
			$this->messageCache->updateMessageOverride( $event->getDeletedPage(), null );
		}
	}

}

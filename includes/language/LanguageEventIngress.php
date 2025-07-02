<?php

namespace MediaWiki\Language;

use MediaWiki\DomainEvent\DomainEventIngress;
use MediaWiki\Page\Event\PageDeletedEvent;
use MediaWiki\Page\Event\PageDeletedListener;
use MediaWiki\Page\Event\PageLatestRevisionChangedEvent;
use MediaWiki\Page\Event\PageLatestRevisionChangedListener;
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
	implements PageDeletedListener, PageLatestRevisionChangedListener
{

	private MessageCache $messageCache;

	/** Object spec intended for use with {@link DomainEventSource::registerSubscriber()} */
	public const OBJECT_SPEC = [
		'class' => self::class,
		'services' => [
			'MessageCache'
		],
		'events' => [
			PageLatestRevisionChangedEvent::TYPE,
			PageDeletedEvent::TYPE
		],
	];

	public function __construct( MessageCache $messageCache ) {
		$this->messageCache = $messageCache;
	}

	/**
	 * Listener method for PageLatestRevisionChangedEvent, to be registered with a DomainEventSource.
	 *
	 * @noinspection PhpUnused
	 */
	public function handlePageLatestRevisionChangedEvent( PageLatestRevisionChangedEvent $event	) {
		if ( $event->getPage()->getNamespace() === NS_MEDIAWIKI	&&
			( $event->isModifiedSlot( SlotRecord::MAIN )
				|| $event->hasCause( PageLatestRevisionChangedEvent::CAUSE_MOVE )
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

/** @deprecated class alias since 1.45 */
class_alias( LanguageEventIngress::class, 'MediaWiki\\Languages\\LanguageEventIngress' );

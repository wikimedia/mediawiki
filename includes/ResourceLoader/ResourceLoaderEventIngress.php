<?php

namespace MediaWiki\ResourceLoader;

use MediaWiki\DomainEvent\EventSubscriberBase;
use MediaWiki\Page\Event\PageDeletedEvent;
use MediaWiki\Page\Event\PageRevisionUpdatedEvent;
use MediaWiki\Storage\PageUpdateCauses;
use Wikimedia\Rdbms\LBFactory;

/**
 * The ingres adapter for the resource loader component.
 * It updates resources related state based on domain events coming from
 * other components.
 *
 * @internal
 */
class ResourceLoaderEventIngress extends EventSubscriberBase {

	/** Object spec intended for use with {@link DomainEventSource::registerSubscriber()} */
	public const OBJECT_SPEC = [
		'class' => self::class,
		'services' => [
			'DBLoadBalancerFactory'
		],
		'events' => [
			PageRevisionUpdatedEvent::TYPE,
			PageDeletedEvent::TYPE,
		],
	];

	private string $localDomainId;

	public function __construct( LBFactory $lbFactory ) {
		$this->localDomainId = $lbFactory->getLocalDomainID();
	}

	/**
	 * Listener method for PageRevisionUpdatedEvent, to be registered with a DomainEventSource.
	 *
	 * @noinspection PhpUnused
	 */
	public function handlePageRevisionUpdatedEvent( PageRevisionUpdatedEvent $event ) {
		if (
			$event->isNominalContentChange()
			|| $event->hasCause( PageUpdateCauses::CAUSE_MOVE )
		) {
			WikiModule::invalidateModuleCache(
				$event->getPage(),
				$event->getOldRevision(),
				$event->getNewRevision(),
				$this->localDomainId
			);
		}
	}

	/**
	 * Listener method for PageDeletedEvent, to be registered with a DomainEventSource.
	 *
	 * @noinspection PhpUnused
	 */
	public function handlePageDeletedEventAfterCommit( PageDeletedEvent $event ) {
		WikiModule::invalidateModuleCache(
			$event->getPage(),
			$event->getLatestRevisionBefore(),
			null,
			$this->localDomainId
		);
	}

}

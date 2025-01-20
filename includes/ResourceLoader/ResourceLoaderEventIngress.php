<?php

namespace MediaWiki\ResourceLoader;

use MediaWiki\DomainEvent\EventSubscriberBase;
use MediaWiki\Storage\PageUpdatedEvent;
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
			PageUpdatedEvent::TYPE
		],
	];

	private string $localDomainId;

	public function __construct( LBFactory $lbFactory ) {
		$this->localDomainId = $lbFactory->getLocalDomainID();
	}

	/**
	 * Listener method for PageUpdatedEvent, to be registered with a DomainEventSource.
	 *
	 * @noinspection PhpUnused
	 */
	public function handlePageUpdatedEventAfterCommit( PageUpdatedEvent $event ) {
		WikiModule::invalidateModuleCache(
			$event->getPage(),
			$event->getOldRevision(),
			$event->getNewRevision(),
			$this->localDomainId
		);
	}

}

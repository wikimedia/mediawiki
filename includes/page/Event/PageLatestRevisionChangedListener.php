<?php

namespace MediaWiki\Page\Event;

/**
 * Listener interface for PageLatestRevisionChangedEvents.
 *
 * Implementations of this interface should be registered for the
 * event type 'PageLatestRevisionChanged', see PageLatestRevisionChangedEvent::TYPE.
 *
 * @see PageLatestRevisionChangedEvent
 * @since 1.45
 */
interface PageLatestRevisionChangedListener {

	public function handlePageLatestRevisionChangedEvent( PageLatestRevisionChangedEvent $event ): void;

}

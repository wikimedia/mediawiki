<?php

namespace MediaWiki\Page\Event;

/**
 * Listener interface for PageLatestRevisionChangedEvents.
 *
 * Implementations of this interface should be registered for the
 * event type 'PageLatestRevisionChanged', see PageLatestRevisionChangedEvent::TYPE.
 *
 * @see PageLatestRevisionChangedEvent
 * @unstable until 1.45, should become stable to implement
 */
interface PageLatestRevisionChangedListener {

	public function handlePageLatestRevisionChangedEvent( PageLatestRevisionChangedEvent $event );

}

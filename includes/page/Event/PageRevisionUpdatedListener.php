<?php

namespace MediaWiki\Page\Event;

/**
 * Listener interface for PageRevisionUpdatedEvents.
 *
 * Implementations of this interface should be registered for the
 * event type 'PageRevisionUpdated', see PageRevisionUpdatedEvent::TYPE.
 *
 * @see PageRevisionUpdatedEvent
 */
interface PageRevisionUpdatedListener {

	public function handlePageRevisionUpdatedEvent( PageRevisionUpdatedEvent $event );

}

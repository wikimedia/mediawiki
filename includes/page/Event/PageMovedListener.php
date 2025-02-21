<?php

namespace MediaWiki\Page\Event;

/**
 * Listener interface for PageMovedEvents.
 *
 * Implementations of this interface should be registered for the
 * event type 'PageMoved', see PageMovedEvent::TYPE.
 *
 * @see PageMovedEvent
 */
interface PageMovedListener {

	public function handlePageMovedEvent( PageMovedEvent $event );

}

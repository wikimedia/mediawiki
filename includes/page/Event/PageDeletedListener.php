<?php

namespace MediaWiki\Page\Event;

/**
 * Listener interface for PageDeletedEvents.
 *
 * Implementations of this interface should be registered for the
 * event type 'PageDeleted', see PageDeletedEvent::TYPE.
 *
 * @see PageDeletedEvent
 */
interface PageDeletedListener {

	public function handlePageDeletedEvent( PageDeletedEvent $event );

}

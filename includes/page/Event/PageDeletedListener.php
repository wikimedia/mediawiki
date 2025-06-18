<?php

namespace MediaWiki\Page\Event;

/**
 * Listener interface for PageDeletedEvents.
 *
 * Implementations of this interface should be registered for the
 * event type 'PageDeleted', see PageDeletedEvent::TYPE.
 *
 * @see PageDeletedEvent
 * @unstable until 1.45, should become stable to implement
 */
interface PageDeletedListener {

	public function handlePageDeletedEvent( PageDeletedEvent $event ): void;

}

<?php

namespace MediaWiki\Page\Event;

/**
 * Listener interface for PageDeletedEvents.
 *
 * Implementations of this interface should be registered for the
 * event type 'PageDeleted', see PageDeletedEvent::TYPE.
 *
 * @see PageDeletedEvent
 * @since 1.45
 */
interface PageDeletedListener {

	public function handlePageDeletedEvent( PageDeletedEvent $event ): void;

}

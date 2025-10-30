<?php

namespace MediaWiki\Page\Event;

/**
 * Listener interface for PageMovedEvents.
 *
 * Implementations of this interface should be registered for the
 * event type 'PageMoved', see PageMovedEvent::TYPE.
 *
 * @see PageMovedEvent
 * @since 1.45
 */
interface PageMovedListener {

	public function handlePageMovedEvent( PageMovedEvent $event ): void;

}

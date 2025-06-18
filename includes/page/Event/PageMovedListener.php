<?php

namespace MediaWiki\Page\Event;

/**
 * Listener interface for PageMovedEvents.
 *
 * Implementations of this interface should be registered for the
 * event type 'PageMoved', see PageMovedEvent::TYPE.
 *
 * @see PageMovedEvent
 * @unstable until 1.45, should become stable to implement
 */
interface PageMovedListener {

	public function handlePageMovedEvent( PageMovedEvent $event ): void;

}

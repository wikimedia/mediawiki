<?php

namespace MediaWiki\Page\Event;

/**
 * Listener interface for PageCreatedEvent.
 *
 * Implementations of this interface should be registered for the
 * event type 'PageCreated', see PageCreatedEvent::TYPE.
 *
 * @see PageCreatedEvent
 * @unstable until 1.45, should become stable to implement
 */
interface PageCreatedListener {

	public function handlePageCreatedEvent( PageCreatedEvent $event ): void;

}

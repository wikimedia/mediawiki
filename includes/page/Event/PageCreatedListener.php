<?php

namespace MediaWiki\Page\Event;

/**
 * Listener interface for PageCreatedEvent.
 *
 * Implementations of this interface should be registered for the
 * event type 'PageCreated', see PageCreatedEvent::TYPE.
 *
 * @see PageCreatedEvent
 * @since 1.45
 */
interface PageCreatedListener {

	public function handlePageCreatedEvent( PageCreatedEvent $event ): void;

}

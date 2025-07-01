<?php

namespace MediaWiki\Page\Event;

/**
 * Listener interface for PageEvent.
 *
 * Implementations of this interface should be registered for the
 * event type 'Page', see PageEvent::TYPE.
 *
 * @see PageEvent
 * @since 1.45
 */
interface PageListener {

	public function handlePageEvent( PageEvent $event ): void;

}

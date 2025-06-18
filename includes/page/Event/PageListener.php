<?php

namespace MediaWiki\Page\Event;

/**
 * Listener interface for PageEvent.
 *
 * Implementations of this interface should be registered for the
 * event type 'Page', see PageEvent::TYPE.
 *
 * @see PageEvent
 * @unstable until 1.45, should become stable to implement
 */
interface PageListener {

	public function handlePageEvent( PageEvent $event ): void;

}

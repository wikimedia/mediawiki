<?php

namespace MediaWiki\Page\Event;

/**
 * Listener interface for PageHistoryVisibilityChangedEvent.
 *
 * Implementations of this interface should be registered for the
 * event type 'PageHistoryVisibilityChanged',
 * see PageHistoryVisibilityChangedEvent::TYPE.
 *
 * @see PageHistoryVisibilityChangedEvent
 * @unstable until 1.45, should become stable to implement
 */
interface PageHistoryVisibilityChangedListener {

	public function handlePageHistoryVisibilityChangedEvent( PageHistoryVisibilityChangedEvent $event ): void;

}

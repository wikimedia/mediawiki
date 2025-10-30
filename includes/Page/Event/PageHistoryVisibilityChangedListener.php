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
 * @since 1.45
 */
interface PageHistoryVisibilityChangedListener {

	public function handlePageHistoryVisibilityChangedEvent( PageHistoryVisibilityChangedEvent $event ): void;

}

<?php

namespace MediaWiki\Page\Event;

/**
 * Listener interface for PageProtectionChangedEvents.
 *
 * Implementations of this interface should be registered for the
 * event type 'PageProtectionChanged', see PageProtectionChangedEvent::TYPE.
 *
 * @see PageProtectionChangedEvent
 * @since 1.45
 */
interface PageProtectionChangedListener {

	public function handlePageProtectionChangedEvent( PageProtectionChangedEvent $event ): void;

}

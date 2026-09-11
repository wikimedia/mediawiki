<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\ChangeTags\Event;

/**
 * Listener interface for LogTagsUpdatedEvents.
 *
 * Implementations of this interface should be registered for the
 * event type 'LogTagsUpdated', see LogTagsUpdatedEvent::TYPE.
 *
 * @see LogTagsUpdatedEvent
 * @since 1.47
 * @unstable
 */
interface LogTagsUpdatedListener {

	public function handleLogTagsUpdatedEvent( LogTagsUpdatedEvent $event ): void;

}

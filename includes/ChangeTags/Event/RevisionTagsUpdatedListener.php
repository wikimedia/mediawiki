<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\ChangeTags\Event;

/**
 * Listener interface for RevisionTagsUpdatedEvents.
 *
 * Implementations of this interface should be registered for the
 * event type 'RevisionTagsUpdated', see RevisionTagsUpdatedEvent::TYPE.
 *
 * @see RevisionTagsUpdatedEvent
 * @since 1.47
 * @unstable
 */
interface RevisionTagsUpdatedListener {

	public function handleRevisionTagsUpdatedEvent( RevisionTagsUpdatedEvent $event ): void;

}

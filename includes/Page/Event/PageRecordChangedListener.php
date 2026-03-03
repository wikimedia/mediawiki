<?php

namespace MediaWiki\Page\Event;

/**
 * Listener interface for PageRecordChangedEvent.
 *
 * Implementations of this interface should be registered for the
 * event type 'PageRecordChanged', see PageRecordChangedEvent::TYPE.
 *
 * @see PageRecordChangedEvent
 * @since 1.45
 */
interface PageRecordChangedListener {

	public function handlePageRecordChangedEvent( PageRecordChangedEvent $event ): void;

}

/**
 * @deprecated since 1.44; use PageRecordChangedListener instead
 * @warning Removal planned in 1.47
 */
class_alias( PageRecordChangedListener::class, 'MediaWiki\Page\Event\PageStateListener' );

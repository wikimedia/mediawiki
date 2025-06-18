<?php

namespace MediaWiki\Page\Event;

/**
 * Listener interface for PageRecordChangedEvent.
 *
 * Implementations of this interface should be registered for the
 * event type 'PageRecordChanged', see PageRecordChangedEvent::TYPE.
 *
 * @see PageRecordChangedEvent
 * @unstable until 1.45, should become stable to implement
 */
interface PageRecordChangedListener {

	public function handlePageRecordChangedEvent( PageRecordChangedEvent $event ): void;

}

// @deprecated temporary alias, remove before 1.45 release
class_alias( PageRecordChangedListener::class, 'MediaWiki\Page\Event\PageStateListener' );

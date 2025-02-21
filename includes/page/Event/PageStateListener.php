<?php

namespace MediaWiki\Page\Event;

/**
 * Listener interface for PageStateEvent.
 *
 * Implementations of this interface should be registered for the
 * event type 'PageState', see PageStateEvent::TYPE.
 *
 * @see PageStateEvent
 */
interface PageStateListener {

	public function handlePageStateEvent( PageStateEvent $event );

}

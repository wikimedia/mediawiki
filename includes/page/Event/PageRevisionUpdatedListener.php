<?php

namespace MediaWiki\Page\Event;

/**
 * @deprecated since 1.44, use PageLatestRevisionChangedListener instead
 */
interface PageRevisionUpdatedListener {

	public function handlePageRevisionUpdatedEvent( PageRevisionUpdatedEvent $event );

}

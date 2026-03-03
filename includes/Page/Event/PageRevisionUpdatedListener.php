<?php

namespace MediaWiki\Page\Event;

/**
 * @deprecated since 1.44; use PageLatestRevisionChangedListener instead
 * @warning Removal planned in 1.47
 */
interface PageRevisionUpdatedListener {

	public function handlePageRevisionUpdatedEvent( PageLatestRevisionChangedEvent $event ): void;

}

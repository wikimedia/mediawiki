<?php

namespace MediaWiki\Notification;

use MediaWiki\Page\PageIdentity;

/**
 * Marker interface for Notifications aware of the PageIdentity those refer to
 *
 * @since 1.44
 * @unstable
 */
interface TitleAware {

	/**
	 * Get the PageIdentity of the related page
	 */
	public function getTitle(): PageIdentity;

}

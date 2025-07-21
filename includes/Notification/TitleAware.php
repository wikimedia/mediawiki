<?php

namespace MediaWiki\Notification;

use MediaWiki\Page\PageIdentity;

/**
 * Marker interface for Notifications aware of the PageIdentity those refer to
 *
 * @stable to implement
 * @since 1.45
 */
interface TitleAware {

	/**
	 * Get the PageIdentity of the related page
	 */
	public function getTitle(): PageIdentity;

}

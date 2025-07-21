<?php

namespace MediaWiki\Notification\Middleware;

/**
 * @since 1.45
 */
enum FilterMiddlewareAction {
	case KEEP;
	case REMOVE;
}

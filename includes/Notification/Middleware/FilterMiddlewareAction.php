<?php

namespace MediaWiki\Notification\Middleware;

enum FilterMiddlewareAction {
	case KEEP;
	case REMOVE;
}

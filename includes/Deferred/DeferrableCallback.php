<?php

namespace MediaWiki\Deferred;

/**
 * Callback wrapper that has an originating method
 *
 * @stable to implement
 *
 * @since 1.28
 */
interface DeferrableCallback {
	/**
	 * @return string Originating method name
	 */
	public function getOrigin();
}

/** @deprecated class alias since 1.42 */
class_alias( DeferrableCallback::class, 'DeferrableCallback' );

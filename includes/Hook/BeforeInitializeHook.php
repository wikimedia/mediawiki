<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface BeforeInitializeHook {
	/**
	 * Before anything is initialized in
	 * MediaWiki::performRequest().
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $title Title being used for request
	 * @param ?mixed $unused null
	 * @param ?mixed $output OutputPage object
	 * @param ?mixed $user User
	 * @param ?mixed $request WebRequest object
	 * @param ?mixed $mediaWiki Mediawiki object
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onBeforeInitialize( $title, $unused, $output, $user, $request,
		$mediaWiki
	);
}

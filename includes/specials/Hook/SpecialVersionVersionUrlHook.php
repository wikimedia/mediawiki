<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface SpecialVersionVersionUrlHook {
	/**
	 * Called when building the URL for Special:Version.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $version Current MediaWiki version
	 * @param ?mixed &$versionUrl Raw url to link to (eg: release notes)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialVersionVersionUrl( $version, &$versionUrl );
}

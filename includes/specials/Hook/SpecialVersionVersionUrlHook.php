<?php

namespace MediaWiki\Hook;

/**
 * @stable to implement
 * @ingroup Hooks
 */
interface SpecialVersionVersionUrlHook {
	/**
	 * This hook is called when building the URL for Special:Version.
	 *
	 * @since 1.35
	 *
	 * @param string $version Current MediaWiki version
	 * @param string &$versionUrl Raw url to link to (eg: release notes)
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onSpecialVersionVersionUrl( $version, &$versionUrl );
}

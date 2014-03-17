<?php

/**
 * @author Sam Smith <samsmith@wikimedia.org>
 */
class CoreLessTestSuite {
	public static function suite() {
		global $IP;

		$resourcesPath = "{$IP}/resources";

		return new LessTestSuite( array(
			"{$resourcesPath}/mediawiki",
			"{$resourcesPath}/mediawiki.less",
			"{$resourcesPath}/mediawiki.ui/components",
			"{$resourcesPath}/mediawiki.ui/mixins",
			"{$resourcesPath}/mediawiki.ui/settings",
			"{$resourcesPath}/mediawiki.less",
			"{$resourcesPath}/styleguide-template/public",
			"{$IP}/skins/vector/components",
			"{$IP}/skins/vector",
		) );
	}
}

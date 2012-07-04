<?php

namespace MW;
use MWException;

/**
 * Class representing a MediaWiki site.
 *
 * @since 1.20
 *
 * @file
 * @ingroup Sites
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class MediaWikiSite extends SiteRow {

	/**
	 * Returns if transclusions of templates from this wiki should be allowed.
	 *
	 * @since 1.20
	 *
	 * @return boolean
	 */
	public function getAllowTransclusion() {
		$config = $this->getConfig()->getExtraInfo();
		return array_key_exists( 'allow_transclusion', $config ) ? $config['allow_transclusion'] : false;
	}

	/**
	 * Compatibility helper.
	 * Can be used by code that still needs the old Interwiki class,
	 * but this should be updated, as this method will eventually be removed.
	 *
	 * @since 1.20
	 * @deprecated since 0.1
	 *
	 * @return \Interwiki
	 */
	public function toInterwiki() {
		return new \Interwiki(
			$this->getConfig()->getLocalId(),
			$this->getUrl(),
			$this->getFilePath( 'api.php' ),
			'',
			$this->getConfig()->getForward(),
			$this->getAllowTransclusion()
		);
	}

}
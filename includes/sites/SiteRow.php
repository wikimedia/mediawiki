<?php

/**
 * Class representing a single site that can be linked to.
 * TODO: investigate if we should not use wfUrlencode instead of rawurlencode.
 *
 * @since 1.20
 *
 * @file
 * @ingroup Sites
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 * @author Daniel Werner
 */
class SiteRow extends \ORMRow implements Site {

	/**
	 * @see Site::getConfig()
	 *
	 * @since 1.20
	 *
	 * @return SiteConfig
	 */
	public function getConfig() {
		return new SiteConfigObject(
			$this->getField( 'local_key' ),
			$this->getField( 'link_inline' ),
			$this->getField( 'link_navigation' ),
			$this->getField( 'forward' )
		);
	}

	/**
	 * @see Site::getGlobalId()
	 *
	 * @since 1.20
	 *
	 * @return integer
	 */
	public function getGlobalId() {
		return $this->getField( 'global_key' );
	}

	/**
	 * @see Site::getType()
	 *
	 * @since 1.20
	 *
	 * @return integer
	 */
	public function getType() {
		return $this->getField( 'type' );
	}


	/**
	 * @see Site::getGroup()
	 *
	 * @since 1.20
	 *
	 * @return integer
	 */
	public function getGroup() {
		return $this->getField( 'group' );
	}

	/**
	 * @see Site::getUrl()
	 *
	 * @since 1.20
	 *
	 * @return string
	 */
	public function getUrl() {
		return $this->getField( 'url' );
	}

	/**
	 * @see Site::getPagePath()
	 *
	 * @since 1.20
	 *
	 * @param string|false $pageName
	 *
	 * @return string
	 */
	public function getPagePath( $pageName = false ) {
		$pagePath = $this->getField( 'url' ) . $this->getField( 'page_path' );

		if ( $pageName !== false ) {
			$pagePath = str_replace( '$1', rawurlencode( $pageName ), $pagePath );
		}

		return $pagePath;
	}

	/**
	 * @see Site::getFilePath()
	 *
	 * @since 1.20
	 *
	 * @param string|false $path
	 *
	 * @return string
	 */
	public function getFilePath( $path = false ) {
		$filePath = $this->getField( 'url' ) . $this->getField( 'file_path' );

		if ( $filePath !== false ) {
			$filePath = str_replace( '$1', $path, $filePath );
		}

		return $filePath;
	}

	/**
	 * @see Site::getRelativePagePath()
	 *
	 * @since 1.20
	 *
	 * @return string
	 */
	public function getRelativePagePath() {
		return $this->getField( 'page_path' );
	}

	/**
	 * @see Site::getRelativeFilePath()
	 *
	 * @since 1.20
	 *
	 * @return string
	 */
	public function getRelativeFilePath() {
		return $this->getField( 'file_path' );
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
			0
		);
	}

	/**
	 * @see Site::getExtraData()
	 *
	 * @since 1.20
	 *
	 * @return array
	 */
	public function getExtraData() {
		return $this->getField( 'data' );
	}

	/**
	 * @see Site::getLanguage()
	 *
	 * @since 1.20
	 *
	 * @return string
	 */
	public function getLanguage() {
		return $this->getField( 'language' );
	}

}
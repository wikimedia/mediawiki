<?php
/**
 * MediaWiki page data importer.
 *
 * Copyright © 2003,2005 Brooke Vibber <bvibber@wikimedia.org>
 * https://www.mediawiki.org/
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup SpecialPage
 */

use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Status\Status;
use Wikimedia\AtEase\AtEase;

/**
 * Imports a XML dump from a file (either from file upload, files on disk, or HTTP)
 * @ingroup SpecialPage
 */
class ImportStreamSource implements ImportSource {
	/** @var resource */
	private $mHandle;

	/**
	 * @param resource $handle
	 */
	public function __construct( $handle ) {
		$this->mHandle = $handle;
	}

	/**
	 * @return bool
	 */
	public function atEnd() {
		return feof( $this->mHandle );
	}

	/**
	 * @return string
	 */
	public function readChunk() {
		return fread( $this->mHandle, 32768 );
	}

	/**
	 * @return bool
	 */
	public function isSeekable() {
		return stream_get_meta_data( $this->mHandle )['seekable'] ?? false;
	}

	/**
	 * @param int $offset
	 * @return int
	 */
	public function seek( int $offset ) {
		return fseek( $this->mHandle, $offset );
	}

	/**
	 * @param string $filename
	 * @return Status
	 */
	public static function newFromFile( $filename ) {
		AtEase::suppressWarnings();
		$file = fopen( $filename, 'rt' );
		AtEase::restoreWarnings();
		if ( !$file ) {
			return Status::newFatal( "importcantopen" );
		}
		return Status::newGood( new ImportStreamSource( $file ) );
	}

	/**
	 * @param string $fieldname
	 * @return Status
	 */
	public static function newFromUpload( $fieldname = "xmlimport" ) {
		// phpcs:ignore MediaWiki.Usage.SuperGlobalsUsage.SuperGlobals
		$upload =& $_FILES[$fieldname];

		if ( $upload === null || !$upload['name'] ) {
			return Status::newFatal( 'importnofile' );
		}
		if ( !empty( $upload['error'] ) ) {
			switch ( $upload['error'] ) {
				case UPLOAD_ERR_INI_SIZE:
					// The uploaded file exceeds the upload_max_filesize directive in php.ini.
					return Status::newFatal( 'importuploaderrorsize' );
				case UPLOAD_ERR_FORM_SIZE:
					// The uploaded file exceeds the MAX_FILE_SIZE directive that
					// was specified in the HTML form.
					// FIXME This is probably never used since that directive was removed in 8e91c520?
					return Status::newFatal( 'importuploaderrorsize' );
				case UPLOAD_ERR_PARTIAL:
					// The uploaded file was only partially uploaded
					return Status::newFatal( 'importuploaderrorpartial' );
				case UPLOAD_ERR_NO_TMP_DIR:
					// Missing a temporary folder.
					return Status::newFatal( 'importuploaderrortemp' );
				// Other error codes get the generic 'importnofile' error message below
			}

		}
		$fname = $upload['tmp_name'];
		if ( is_uploaded_file( $fname ) || defined( 'MW_PHPUNIT_TEST' ) ) {
			return self::newFromFile( $fname );
		} else {
			return Status::newFatal( 'importnofile' );
		}
	}

	/**
	 * @param string $url
	 * @param string $method
	 * @return Status
	 */
	public static function newFromURL( $url, $method = 'GET' ) {
		$httpImportTimeout = MediaWikiServices::getInstance()->getMainConfig()->get(
			MainConfigNames::HTTPImportTimeout );
		wfDebug( __METHOD__ . ": opening $url" );
		# Use the standard HTTP fetch function; it times out
		# quicker and sorts out user-agent problems which might
		# otherwise prevent importing from large sites, such
		# as the Wikimedia cluster, etc.
		$data = MediaWikiServices::getInstance()->getHttpRequestFactory()->request(
			$method,
			$url,
			[
				'followRedirects' => true,
				'timeout' => $httpImportTimeout
			],
			__METHOD__
		);
		if ( $data !== null ) {
			$file = tmpfile();
			fwrite( $file, $data );
			fflush( $file );
			fseek( $file, 0 );
			return Status::newGood( new ImportStreamSource( $file ) );
		} else {
			return Status::newFatal( 'importcantopen' );
		}
	}

	/**
	 * @param string $interwiki
	 * @param string $page
	 * @param bool $history
	 * @param bool $templates
	 * @param int $pageLinkDepth
	 * @return Status
	 */
	public static function newFromInterwiki( $interwiki, $page, $history = false,
		$templates = false, $pageLinkDepth = 0
	) {
		if ( $page == '' ) {
			return Status::newFatal( 'import-noarticle' );
		}

		# Look up the first interwiki prefix, and let the foreign site handle
		# subsequent interwiki prefixes
		$firstIwPrefix = strtok( $interwiki, ':' );
		$interwikiLookup = MediaWikiServices::getInstance()->getInterwikiLookup();
		$firstIw = $interwikiLookup->fetch( $firstIwPrefix );
		if ( !$firstIw ) {
			return Status::newFatal( 'importbadinterwiki' );
		}

		$additionalIwPrefixes = strtok( '' );
		if ( $additionalIwPrefixes ) {
			$additionalIwPrefixes .= ':';
		}
		# Have to do a DB-key replacement ourselves; otherwise spaces get
		# URL-encoded to +, which is wrong in this case. Similar to logic in
		# Title::getLocalURL
		$link = $firstIw->getURL( strtr( "{$additionalIwPrefixes}Special:Export/$page",
			' ', '_' ) );

		$params = [];
		if ( $history ) {
			$params['history'] = 1;
		}
		if ( $templates ) {
			$params['templates'] = 1;
		}
		if ( $pageLinkDepth ) {
			$params['pagelink-depth'] = $pageLinkDepth;
		}

		$url = wfAppendQuery( $link, $params );
		# For interwikis, use POST to avoid redirects.
		return self::newFromURL( $url, "POST" );
	}
}

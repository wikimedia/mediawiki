<?php

namespace MediaWiki\Site;

use InvalidArgumentException;
use OutOfBoundsException;

/**
 * FIXME
 *
 * @since 1.32
 *
 * @file
 * @ingroup Site
 *
 * @license GNU GPL v2+
 */
class FileSiteInfoLookup extends HashSiteInfoLookup {

	/**
	 * @var array[]
	 */
	private $files;

	/**
	 * @var bool
	 */
	private $loaded = false;

	/**
	 * HashSiteInfoLookup constructor.
	 *
	 * @param string $localId ID of the wiki for which this lookup is valid.
	 * @param array[] $files A list of files to load. The files must be in JSON format, or
	 * includable PHP files that return an array. See class level documentation for details.
	 * The files' syntax must be indicated by the file extension in the path: ".php" for
	 * includable php files, or ".json" for JSON files.
	 */
	public function __construct( $localId, array $files ) {
		parent::__construct( $localId, [], [] );

		// FIXME: assert
		$this->files = $files;
	}

	private function load() {
		if ( $this->loaded ) {
			return;
		}

		$data = [];
		foreach ( $this->files as $file ) {
			$data = array_merge_recursive( $data, $this->loadFile( $file ) );
		}

		$this->sites = $data['sites']; // FIXME: check existance
		$this->aliases = $data['ids']; // FIXME: check existance

		$this->loaded = true;
	}

	/**
	 * @param string $path
	 * @param string $suffix
	 *
	 * @return bool
	 */
	private function hasSuffix( $path, $suffix ) {
		return substr_compare( $path, $suffix, -strlen( $suffix ) ) === 0;
	}

	/**
	 * @param string $path
	 *
	 * @return array
	 */
	private function loadFile( $path ) {
		if ( $this->hasSuffix( $path, '.php' ) ) {
			$data = include $path; // FIXME: check return value
		} elseif ( $this->hasSuffix( $path, '.json' ) ) {
			$json = file_get_contents( $path ); // FIXME: check return value
			$data = json_decode( $json, JSON_OBJECT_AS_ARRAY ); // FIXME: check return value
		} else {
			// FIXME: IAE is a pretty hard failure mode for a configuration error. Be nicer.
			throw new InvalidArgumentException( 'Unsupported file type: ' . $path );
		}

		// FIXME: check structure??
		return $data;
	}

	/**
	 * @return array[]
	 */
	protected function getSitesArray() {
		$this->load();
		return parent::getSitesArray();
	}

	/**
	 * @return array[]
	 */
	protected function getAliasArray() {
		$this->load();
		return parent::getAliasArray();
	}

}

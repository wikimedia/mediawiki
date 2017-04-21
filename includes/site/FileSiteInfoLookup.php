<?php

namespace MediaWiki\Site;

use OutOfBoundsException;

/**
 * FIXME
 *
 * @since 1.29
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
	 * @param array[] $files
	 */
	public function __construct( array $files ) {
		parent::__construct( [], [], []);

		//FIXME: assert
		$this->files = $files;
	}

	private function load() {
		if ( $this->loaded ) {
			return;
		}

		$data = [];
		foreach ( $this->files as $file ) {
			$data = array_merge( $data, $this->loadFile( $file ) );
		}

		$this->sites = $data['sites']; //FIXME: check existance
		$this->ids = $data['ids']; //FIXME: check existance
		$this->groups = $data['groups']; //FIXME: check existance

		$this->loaded = true;
	}

	private function loadFile( $file ) {
		//FIXME: support json
		//FIXME: check readable??
		//FIXME: assert return type
		return include( $file );
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
	protected function getIdArray() {
		$this->load();
		return parent::getIdArray();
	}

	/**
	 * @return array[]
	 */
	protected function getGroupArray() {
		$this->load();
		return parent::getGroupArray();
	}

}

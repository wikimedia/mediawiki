<?php
/**
 * Dump output filter to include or exclude pages in a given set of namespaces.
 *
 * Copyright © 2003, 2005, 2006 Brooke Vibber <bvibber@wikimedia.org>
 * https://www.mediawiki.org/
 *
 * @license GPL-2.0-or-later
 * @file
 */

/**
 * @ingroup Dump
 */
class DumpNamespaceFilter extends DumpFilter {
	/** @var bool */
	public $invert = false;

	/** @var array */
	public $namespaces = [];

	/**
	 * @param DumpOutput &$sink
	 * @param string $param
	 */
	public function __construct( &$sink, $param ) {
		parent::__construct( $sink );

		$constants = [
			"NS_MAIN"           => NS_MAIN,
			"NS_TALK"           => NS_TALK,
			"NS_USER"           => NS_USER,
			"NS_USER_TALK"      => NS_USER_TALK,
			"NS_PROJECT"        => NS_PROJECT,
			"NS_PROJECT_TALK"   => NS_PROJECT_TALK,
			"NS_FILE"           => NS_FILE,
			"NS_FILE_TALK"      => NS_FILE_TALK,
			"NS_MEDIAWIKI"      => NS_MEDIAWIKI,
			"NS_MEDIAWIKI_TALK" => NS_MEDIAWIKI_TALK,
			"NS_TEMPLATE"       => NS_TEMPLATE,
			"NS_TEMPLATE_TALK"  => NS_TEMPLATE_TALK,
			"NS_HELP"           => NS_HELP,
			"NS_HELP_TALK"      => NS_HELP_TALK,
			"NS_CATEGORY"       => NS_CATEGORY,
			"NS_CATEGORY_TALK"  => NS_CATEGORY_TALK ];

		if ( $param[0] == '!' ) {
			$this->invert = true;
			$param = substr( $param, 1 );
		}

		foreach ( explode( ',', $param ) as $key ) {
			$key = trim( $key );
			if ( isset( $constants[$key] ) ) {
				$ns = $constants[$key];
				$this->namespaces[$ns] = true;
			} elseif ( is_numeric( $key ) ) {
				$ns = intval( $key );
				$this->namespaces[$ns] = true;
			} else {
				throw new InvalidArgumentException( "Unrecognized namespace key '$key'\n" );
			}
		}
	}

	/**
	 * @param stdClass $page
	 * @return bool
	 */
	protected function pass( $page ) {
		$match = isset( $this->namespaces[$page->page_namespace] );
		return $this->invert xor $match;
	}
}

<?php
/**
 * Dump output filter to include or exclude pages in a given set of namespaces.
 *
 * Copyright © 2003, 2005, 2006 Brion Vibber <brion@pobox.com>
 * https://www.mediawiki.org/
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
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
	 * @throws MWException
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
			"NS_IMAGE"          => NS_FILE, // NS_IMAGE is an alias for NS_FILE
			"NS_IMAGE_TALK"     => NS_FILE_TALK,
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
				throw new MWException( "Unrecognized namespace key '$key'\n" );
			}
		}
	}

	/**
	 * @param object $page
	 * @return bool
	 */
	protected function pass( $page ) {
		$match = isset( $this->namespaces[$page->page_namespace] );
		return $this->invert xor $match;
	}
}

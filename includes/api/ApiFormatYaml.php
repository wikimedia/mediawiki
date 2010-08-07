<?php
/**
 * API for MediaWiki 1.8+
 *
 * Created on Sep 19, 2006
 *
 * Copyright © 2006 Yuri Astrakhan <Firstname><Lastname>@gmail.com
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

if ( !defined( 'MEDIAWIKI' ) ) {
	// Eclipse helper - will be ignored in production
	require_once( 'ApiFormatBase.php' );
}

/**
 * API YAML output formatter
 * @ingroup API
 */
class ApiFormatYaml extends ApiFormatBase {

	public function __construct( $main, $format ) {
		parent::__construct( $main, $format );
	}

	public function getMimeType() {
		return 'application/yaml';
	}

	public function execute() {
		$this->printText( Spyc::YAMLDump( $this->getResultData() ) );
	}

	public function getDescription() {
		return 'Output data in YAML format' . parent::getDescription();
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}

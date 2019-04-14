<?php
/**
 * Copyright © 2006 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
 * Copyright © 2008 Brion Vibber <brion@wikimedia.org>
 * Copyright © 2014 Wikimedia Foundation and contributors
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
 * @ingroup API
 */
class ApiOpenSearchFormatJson extends ApiFormatJson {
	private $warningsAsError = false;

	public function __construct( ApiMain $main, $fm, $warningsAsError ) {
		parent::__construct( $main, "json$fm" );
		$this->warningsAsError = $warningsAsError;
	}

	public function execute() {
		$result = $this->getResult();
		if ( !$result->getResultData( 'error' ) && !$result->getResultData( 'errors' ) ) {
			// Ignore warnings or treat as errors, as requested
			$warnings = $result->removeValue( 'warnings', null );
			if ( $this->warningsAsError && $warnings ) {
				$this->dieWithError(
					'apierror-opensearch-json-warnings',
					'warnings',
					[ 'warnings' => $warnings ]
				);
			}

			// Ignore any other unexpected keys (e.g. from $wgDebugToolbar)
			$remove = array_keys( array_diff_key(
				$result->getResultData(),
				[ 0 => 'search', 1 => 'terms', 2 => 'descriptions', 3 => 'urls' ]
			) );
			foreach ( $remove as $key ) {
				$result->removeValue( $key, null );
			}
		}

		parent::execute();
	}
}

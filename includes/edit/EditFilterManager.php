<?php
/**
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


namespace MediaWiki\Edit;

use Hooks;
use StatusValue;

class EditFilterManager {

	/**
	 * @var EditFilterBase[]
	 */
	private $filters = [];

	public function __construct() {
		// register core filters..
		$this->filters[] = new SimpleAntiSpamFilter();

		// register extension filters

		// usort based on priority
	}

	/**
	 * @param EditAttempt $attempt
	 * @return StatusValue
	 */
	public function filter( EditAttempt $attempt ) {
		$status = new StatusValue();
		foreach ( $this->filters as $filter ) {
			$status->merge( $filter->filter( $attempt ) );
			if ( !$status->isOK() ) {
				return $status;
			}
		}

		return $status;
	}

}

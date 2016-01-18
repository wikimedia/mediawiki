<?php
/**
 *
 *
 * Created on Oct 22, 2006
 *
 * Copyright © 2006 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
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
 * API Serialized PHP output formatter
 * @ingroup API
 */
class ApiFormatPhp extends ApiFormatBase {

	public function getMimeType() {
		return 'application/vnd.php.serialized';
	}

	public function execute() {
		$text = serialize( $this->getResultData() );

		// Bug 66776: wfMangleFlashPolicy() is needed to avoid a nasty bug in
		// Flash, but what it does isn't friendly for the API. There's nothing
		// we can do here that isn't actively broken in some manner, so let's
		// just be broken in a useful manner.
		if ( $this->getConfig()->get( 'MangleFlashPolicy' ) &&
			in_array( 'wfOutputHandler', ob_list_handlers(), true ) &&
			preg_match( '/\<\s*cross-domain-policy(?=\s|\>)/i', $text )
		) {
			$this->dieUsage(
				'This response cannot be represented using format=php. See https://bugzilla.wikimedia.org/show_bug.cgi?id=66776',
				'internalerror'
			);
		}

		$this->printText( $text );
	}

	public function getDescription() {
		return 'Output data in serialized PHP format' . parent::getDescription();
	}
}

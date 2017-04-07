<?php
/**
 * Implements Special:Version
 *
 * Copyright © 2017 Ævar Arnfjörð Bjarmason
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
 * @ingroup SpecialPage
 */

/**
 * Give information about the version of MediaWiki, PHP, the DB and extensions
 *
 * @ingroup SpecialPage
 */
class SpecialSystemInfo extends SpecialPage {
	protected $firstExtOpened = false;

	/**
	 * Stores the current rev id/SHA hash of MediaWiki core
	 */
	protected $coreId = '';

	protected static $extensionTypes = false;

	public function __construct() {
		parent::__construct( 'SystemInfo' );
	}

	/**
	 * main()
	 * @param string|null $par
	 */
	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();
		$out = $this->getOutput();
		$out->allowClickjacking();

		// Explode the sub page information into useful bits
		$parts = explode( '/', (string)$par );
		$extName = 'MediaWiki';

		// Now figure out what to do
		switch ( strtolower( $parts[0] ) ) {
			default:
				$out->addModuleStyles( 'mediawiki.special.version' );
				$out->addWikiText(
					SpecialVersion::softwareInformation()
				);
				$out->addHTML( $this->IPInfo() );

				break;
		}
	}

	protected function getGroupName() {
		return 'wiki';
	}
}

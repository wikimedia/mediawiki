<?php
/**
 * Implements Special:Credits
 *
 * Copyright © 2012 Marius Hoch
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
 * Lists the people who took part in creating this wonderful piece of software
 * The list is taken from <root>/CREDITS
 *
 * @ingroup SpecialPage
 */
class SpecialCredits extends SpecialPage {
	public function __construct(){
		parent::__construct( 'Credits' );
	}
	/**
	 * main()
	 */
	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();
		$out = $this->getOutput();
		$out->allowClickjacking();

		wfSuppressWarnings();
		$wikiText = file_get_contents( __DIR__ . '../../../CREDITS' );
		wfRestoreWarnings();

		// Take everything from the first section onwards, to remove the (not localized) header
		$wikiText = preg_replace( '/^[^=]*/s', '', $wikiText);

		$out->addWikiText( $wikiText );

	}
}

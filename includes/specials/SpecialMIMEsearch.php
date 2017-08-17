<?php
/**
 * Implements Special:MIMESearch
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
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 */

/**
 * Searches the database for files of the requested MIME type, comparing this with the
 * 'img_major_mime' and 'img_minor_mime' fields in the image table.
 * @ingroup SpecialPage
 */
class MIMEsearchPage extends SpecialPage {

	function __construct( $name = 'MIMEsearch' ) {
		parent::__construct( $name );
	}

	public function execute( $par ) {
		$query = [
			'ilmime' => $this->getRequest()->getText( 'mime', '' )
		];
		$url = SpecialPage::getTitleFor( 'ListFiles' )->getFullURL( $query );

		$this->getOutput()->redirect( $url );
	}

	protected function getGroupName() {
		return 'media';
	}
}

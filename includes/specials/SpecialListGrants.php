<?php
/**
 * Implements Special:Listgrants
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
 * This special page lists all defined rights grants and the associated rights.
 * See also @ref $wgGrantPermissions and @ref $wgGrantPermissionGroups.
 *
 * @ingroup SpecialPage
 */
class SpecialListGrants extends SpecialPage {
	public function __construct() {
		parent::__construct( 'Listgrants' );
	}

	/**
	 * Show the special page
	 * @param string|null $par
	 */
	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();

		$out = $this->getOutput();
		$out->addModuleStyles( 'mediawiki.special' );

		$out->addHTML(
			\Html::openElement( 'table',
				[ 'class' => 'wikitable mw-listgrouprights-table' ] ) .
				'<tr>' .
				\Html::element( 'th', null, $this->msg( 'listgrants-grant' )->text() ) .
				\Html::element( 'th', null, $this->msg( 'listgrants-rights' )->text() ) .
				'</tr>'
		);

		foreach ( $this->getConfig()->get( 'GrantPermissions' ) as $grant => $rights ) {
			$descs = [];
			$rights = array_filter( $rights ); // remove ones with 'false'
			foreach ( $rights as $permission => $granted ) {
				$descs[] = $this->msg(
					'listgrouprights-right-display',
					\User::getRightDescription( $permission ),
					'<span class="mw-listgrants-right-name">' . $permission . '</span>'
				)->parse();
			}
			if ( $descs === [] ) {
				$grantCellHtml = '';
			} else {
				sort( $descs );
				$grantCellHtml = '<ul><li>' . implode( "</li>\n<li>", $descs ) . '</li></ul>';
			}

			$id = Sanitizer::escapeIdForAttribute( $grant );
			$out->addHTML( \Html::rawElement( 'tr', [ 'id' => $id ],
				"<td>" .
				$this->msg(
					"listgrants-grant-display",
					\User::getGrantName( $grant ),
					"<span class='mw-listgrants-grant-name'>" . $id . "</span>"
				)->parse() .
				"</td>" .
				"<td>" . $grantCellHtml . "</td>"
			) );
		}

		$out->addHTML( \Html::closeElement( 'table' ) );
	}

	protected function getGroupName() {
		return 'users';
	}
}

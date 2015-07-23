<?php
/**
 * Implements Special:Blankpage
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
 * Special page designed for basic benchmarking of
 * MediaWiki since it doesn't really do much.
 *
 * @ingroup SpecialPage
 */
class SpecialBlankpage extends UnlistedSpecialPage {
	public function __construct() {
		parent::__construct( 'Blankpage' );
	}

	public function execute( $par ) {
		$this->setHeaders();
		$this->getOutput()->addWikiMsg( 'intentionallyblankpage' );

		$script = <<<EOF
mw.loader.using( 'mediawiki.widgets' ).done( function () {
	$( function () {
		$( '#mw-content-text' ).empty().append(
			new OO.ui.FieldsetLayout( {
				items: [
					new OO.ui.FieldLayout(
						new mw.widgets.DateInputWidget(),
						{
							align: 'top',
							label: 'Select date:'
						}
					),
					new OO.ui.FieldLayout(
						new mw.widgets.DateInputWidget( { value: '2011-05-07' } ),
						{
							align: 'top',
							label: 'Select date (initially selected):'
						}
					),
					new OO.ui.FieldLayout(
						new mw.widgets.DateInputWidget( { inputFormat: 'DD.MM.YYYY', displayFormat: 'Do [of] MMMM [anno Domini] YYYY' } ),
						{
							align: 'top',
							label: 'Select date (custom formats):'
						}
					),
					new OO.ui.FieldLayout(
						new mw.widgets.DateInputWidget( { disabled: true } ),
						{
							align: 'top',
							label: 'Select date (disabled):'
						}
					),
					new OO.ui.FieldLayout(
						new mw.widgets.DateInputWidget( { precision: 'month' } ),
						{
							align: 'top',
							label: 'Select month:'
						}
					)
				]
			} ).\$element
		);
	} );
} );
EOF;

		$this->getOutput()->addInlineScript( $script );
	}
}

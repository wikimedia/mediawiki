<?php
/**
 * Class for generating clickable toggle links for a list of checkboxes.
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
 * Class for generating clickable toggle links for a list of checkboxes.
 */
class ListToggle {
	/** @var IContextSource */
	private $mContext;

	public function __construct( $context ) {
		$this->mContext = $context;

		$context->getOutput()->addModules( 'mediawiki.checkboxtoggle' );
		$context->getOutput()->addModuleStyles( 'mediawiki.checkboxtoggle.styles' );
	}

	/**
	 * @return string
	 */
	public function getHTML() {
		// Select: All, None, Invert
		$links = array();
		$links[] = Html::element(
			'a', array( 'href' => '#', 'class' => 'mw-checkbox-all' ),
			$this->mContext->msg( 'checkbox-all' )->text()
		);
		$links[] = Html::element(
			'a', array( 'href' => '#', 'class' => 'mw-checkbox-none' ),
			$this->mContext->msg( 'checkbox-none' )->text()
		);
		$links[] = Html::element(
			'a', array( 'href' => '#', 'class' => 'mw-checkbox-invert' ),
			$this->mContext->msg( 'checkbox-invert' )->text()
		);

		return Html::rawElement( 'p',
			array(
				'class' => "mw-checkbox-toggle-controls"
			),
			$this->mContext->msg( 'checkbox-select' )
				->rawParams( $this->mContext->getLanguage()->commaList( $links ) )->escaped()
		);
	}
}

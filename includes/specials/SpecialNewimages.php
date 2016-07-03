<?php
/**
 * Implements Special:Newimages
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

class SpecialNewFiles extends IncludableSpecialPage {
	public function __construct() {
		parent::__construct( 'Newimages' );
	}

	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();

		$out = $this->getOutput();
		$this->addHelpLink( 'Help:New images' );

		$pager = new NewFilesPager( $this->getContext(), $par );

		if ( !$this->including() ) {
			$this->setTopText();
			$form = $pager->getForm();
			$form->prepareForm();
			$form->displayForm( '' );
		}

		$out->addHTML( $pager->getBody() );
		if ( !$this->including() ) {
			$out->addHTML( $pager->getNavigationBar() );
		}
	}

	protected function getGroupName() {
		return 'changes';
	}

	/**
	 * Send the text to be displayed above the options
	 */
	function setTopText() {
		global $wgContLang;

		$message = $this->msg( 'newimagestext' )->inContentLanguage();
		if ( !$message->isDisabled() ) {
			$this->getOutput()->addWikiText(
				Html::rawElement( 'p',
					[ 'lang' => $wgContLang->getHtmlCode(), 'dir' => $wgContLang->getDir() ],
					"\n" . $message->plain() . "\n"
				),
				/* $lineStart */ false,
				/* $interface */ false
			);
		}
	}
}

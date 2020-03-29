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
 * @ingroup Installer
 */

namespace MediaWiki\Installer;

use MediaWiki\Html\Html;

/**
 * Abstract class to define pages for the web installer.
 *
 * @ingroup Installer
 * @since 1.17
 */
abstract class WebInstallerPage {

	/**
	 * The WebInstaller object this WebInstallerPage belongs to.
	 *
	 * @var WebInstaller
	 */
	public $parent;

	/**
	 * @return string
	 */
	abstract public function execute();

	public function __construct( WebInstaller $parent ) {
		$this->parent = $parent;
	}

	/**
	 * Is this a slow-running page in the installer? If so, WebInstaller will
	 * set_time_limit(0) before calling execute(). Right now this only applies
	 * to Install and Upgrade pages
	 *
	 * @return bool Always false in this default implementation.
	 */
	public function isSlow() {
		return false;
	}

	/**
	 * @param string $html
	 */
	public function addHTML( $html ) {
		$this->parent->output->addHTML( $html );
	}

	public function startForm() {
		$this->addHTML(
			"<div class=\"config-section\">\n" .
			Html::openElement(
				'form',
				[
					'method' => 'post',
					'action' => $this->parent->getUrl( [ 'page' => $this->getName() ] )
				]
			) . "\n"
		);
	}

	/**
	 * @param string|bool $continue
	 * @param string|bool $back
	 */
	public function endForm( $continue = 'continue', $back = 'back' ) {
		$s = "<div class=\"config-submit\">\n";
		$id = $this->getId();

		if ( $id === false ) {
			$s .= Html::hidden( 'lastPage', $this->parent->request->getVal( 'lastPage' ) );
		}

		if ( $continue ) {
			// Fake submit button for enter keypress (T28267)
			// Messages: config-continue, config-restart, config-regenerate
			$s .= Html::submitButton(
				wfMessage( "config-$continue" )->text(),
				[
					'name' => "enter-$continue",
					'style' => 'width:0;border:0;height:0;padding:0'
				]
			) . "\n";
		}

		if ( $back ) {
			// Message: config-back
			$s .= Html::submitButton(
				wfMessage( "config-$back" )->text(),
				[
					'name' => "submit-$back",
					'tabindex' => $this->parent->nextTabIndex(),
					'class' => [ 'cdx-button', 'cdx-button--action-default' ]
				]
			) . "\n";
		}

		if ( $continue ) {
			// Messages: config-continue, config-restart, config-regenerate
			$s .= Html::submitButton(
				wfMessage( "config-$continue" )->text(),
				[
					'name' => "submit-$continue",
					'tabindex' => $this->parent->nextTabIndex(),
					'class' => [ 'cdx-button', 'cdx-button--action-progressive' ]
				]
			) . "\n";
		}

		$s .= "</div></form></div>\n";
		$this->addHTML( $s );
	}

	/**
	 * @return string
	 */
	public function getName() {
		return str_replace( 'MediaWiki\\Installer\\WebInstaller', '', static::class );
	}

	/**
	 * @return string
	 */
	protected function getId() {
		return array_search( $this->getName(), $this->parent->pageSequence );
	}

	/**
	 * @param string $var
	 * @param mixed|null $default
	 *
	 * @return mixed
	 */
	public function getVar( $var, $default = null ) {
		return $this->parent->getVar( $var, $default );
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 */
	public function setVar( $name, $value ) {
		$this->parent->setVar( $name, $value );
	}

	/**
	 * Get the starting tags of a fieldset.
	 *
	 * @param string $legend Message name
	 *
	 * @return string
	 */
	protected function getFieldsetStart( $legend ) {
		return "\n<div class=\"cdx-card\"><div class=\"cdx-card__text\"><div class=\"cdx-card__text__title\">" .
			wfMessage( $legend )->escaped() . "</div><div class=\"cdx-card__text__description\">\n";
	}

	/**
	 * Get the end tag of a fieldset.
	 *
	 * @return string
	 */
	protected function getFieldsetEnd() {
		return "</div></div></div>\n";
	}

	/**
	 * Opens a textarea used to display the progress of a long operation
	 */
	protected function startLiveBox() {
		$this->addHTML(
			'<div id="config-spinner" style="display:none;">' .
			'<img src="images/ajax-loader.gif" /></div>' .
			$this->inlineScript( 'jQuery( "#config-spinner" ).show();' ) .
			'<div id="config-live-log">' .
			'<textarea name="LiveLog" rows="10" cols="30" readonly="readonly">'
		);
		$this->parent->output->flush();
	}

	/**
	 * Opposite to WebInstallerPage::startLiveBox
	 */
	protected function endLiveBox() {
		$this->addHTML(
			'</textarea></div>' .
			$this->inlineScript( 'jQuery( "#config-spinner" ).hide()' )
		);
		$this->parent->output->flush();
	}

	/**
	 * Javascript to include inline. Handles addings CSP nonce.
	 *
	 * @since 1.45
	 * @param string $script Script, not including <script> tag
	 * @return string HTML to output
	 */
	protected function inlineScript( $script ) {
		return Html::inlineScript( $script, $this->parent->output->getCSPNonce() );
	}
}

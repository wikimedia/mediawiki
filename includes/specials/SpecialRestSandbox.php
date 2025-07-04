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

namespace MediaWiki\Specials;

use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\MainConfigNames;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Utils\UrlUtils;

/**
 * A special page showing a Swagger UI for exploring REST APIs.
 *
 * @ingroup SpecialPage
 * @since 1.43
 */
class SpecialRestSandbox extends SpecialPage {

	private UrlUtils $urlUtils;

	public function __construct( UrlUtils $urlUtils ) {
		parent::__construct( 'RestSandbox' );

		$this->urlUtils = $urlUtils;
	}

	/**
	 * Returns the available choices for APIs to explore.
	 *
	 * @see MainConfigSchema::RestSandboxSpecs for the structure of the array
	 *
	 * @return array[]
	 */
	private function getApiSpecs(): array {
		return $this->getConfig()->get( MainConfigNames::RestSandboxSpecs );
	}

	/** @inheritDoc */
	public function isListed() {
		// Hide the special pages if there are no APIs to explore.
		return $this->getApiSpecs() !== [];
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'wiki';
	}

	private function getSpecUrl( ?string $apiId ): ?string {
		$apiSpecs = $this->getApiSpecs();

		if ( $apiId !== null && $apiId !== '' ) {
			$spec = $apiSpecs[$apiId] ?? null;
		} else {
			$spec = reset( $apiSpecs ) ?: null;
		}

		if ( !$spec ) {
			return null;
		}

		return $this->urlUtils->expand( $spec['url'] );
	}

	/** @inheritDoc */
	public function execute( $sub ) {
		$this->setHeaders();
		$out = $this->getOutput();
		$this->addHelpLink( 'Help:RestSandbox' );

		$apiId = $this->getRequest()->getText( 'api', $sub ?? '' );
		$specUrl = $this->getSpecUrl( $apiId );

		$apiSpecs = $this->getApiSpecs();

		$out->addJsConfigVars( [
			'specUrl' => $specUrl
		] );

		$out->addModuleStyles( [
			'mediawiki.special',
			'mediawiki.hlist',
			'mediawiki.special.restsandbox.styles'
		] );

		if ( !$apiSpecs ) {
			$out->addHTML( Html::errorBox(
				$out->msg( 'restsandbox-no-specs-configured' )->parse()
			) );
			return;
		}

		if ( $out->getLanguage()->getCode() !== 'en' ) {
			$out->addHTML( Html::noticeBox( $out->msg( 'restsandbox-disclaimer' )->parse(), '' ) );
		}

		$this->showForm( $apiSpecs );

		if ( !$specUrl ) {
			$out->addHTML( Html::errorBox(
				$out->msg( 'restsandbox-no-such-api' )->params( $apiId )->parse()
			) );
			return;
		}

		$out->addModules( [
			'mediawiki.codex.messagebox.styles',
			'mediawiki.special.restsandbox'
		] );

		$out->addHTML( Html::openElement( 'div', [ 'id' => 'mw-restsandbox' ] ) );

		// Hidden when JS is available
		$out->addHTML( Html::errorBox(
			$out->msg( 'restsandbox-jsonly' )->parse(),
			'',
			'mw-restsandbox-client-nojs'
		) );

		// To be replaced by Swagger UI.
		$out->addElement( 'div', [
			'id' => 'mw-restsandbox-swagger-ui',
			// Force direction to "LTR" with swagger-ui.
			// Since the swagger content is not internationalized, the information is always in English.
			// We have to force the direction to "LTR" to avoid the content (specifically json strings)
			// from being mangled.
			'dir' => 'ltr',
			'lang' => 'en',
			// For dark mode compatibility
			'class' => 'skin-invert'
		] );

		$out->addHTML( Html::closeElement( 'div' ) ); // #mw-restsandbox
	}

	private function showForm( array $apiSpecs ) {
		$apis = [];

		foreach ( $apiSpecs as $key => $spec ) {
			if ( isset( $spec['msg'] ) ) {
				$text = $this->msg( $spec['msg'] )->plain();
			} elseif ( isset( $spec['name'] ) ) {
				$text = $spec['name'];
			} else {
				$text = $key;
			}

			$apis[$text] = $key;
		}

		$formDescriptor = [
			'intro' => [
				'type' => 'info',
				'raw' => true,
				'default' => $this->msg( 'restsandbox-text' )->parseAsBlock()
			],
			'api' => [
				'type' => 'select',
				'name' => 'api',
				'label-message' => 'restsandbox-select-api',
				'options' => $apis
			],
			'title' => [
				'type' => 'hidden',
				'name' => 'title',
				'default' => $this->getPageTitle()->getPrefixedDBkey()
			],
		];

		$action = $this->getPageTitle()->getLocalURL( [ 'action' => 'submit' ] );

		$htmlForm = HTMLForm::factory( 'ooui', $formDescriptor, $this->getContext() );
		$htmlForm->setAction( $action );
		$htmlForm->setMethod( 'GET' );
		$htmlForm->setId( 'mw-restsandbox-form' );
		$htmlForm->prepareForm()->displayForm( false );
	}
}

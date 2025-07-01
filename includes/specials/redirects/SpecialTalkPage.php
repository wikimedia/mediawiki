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

namespace MediaWiki\Specials\Redirects;

use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\MainConfigNames;
use MediaWiki\SpecialPage\FormSpecialPage;
use MediaWiki\Status\Status;
use MediaWiki\Title\MalformedTitleException;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleParser;

/**
 * Redirect to the talk page of a given page.
 *
 * @ingroup SpecialPage
 */
class SpecialTalkPage extends FormSpecialPage {

	private TitleParser $titleParser;

	public function __construct( TitleParser $titleParser ) {
		parent::__construct( 'TalkPage' );
		$this->titleParser = $titleParser;
	}

	protected function getFormFields() {
		return [
			'target' => [
				'type' => 'title',
				'name' => 'target',
				'label-message' => 'special-talkpage-target',
				'default' => $this->par,
			],
		];
	}

	protected function alterForm( HTMLForm $form ) {
		if ( $this->par ) { // immediately submit with subpage value
			$form->setMethod( 'get' );
		}
		$form->setSubmitTextMsg( 'special-talkpage-submit' );
	}

	public function onSubmit( array $formData ) {
		$target = $formData['target'];
		try {
			$title = $this->titleParser->parseTitle( $target );
		} catch ( MalformedTitleException $e ) {
			return Status::newFatal( $e->getMessageObject() );
		}
		$title = Title::newFromLinkTarget( $title );
		$talk = $title->getTalkPageIfDefined();
		if ( $talk === null ) {
			return Status::newFatal( 'title-invalid-talk-namespace' );
		}

		// HTTP 302: Found; cache for the Parser Cache length, as an appropriate long time
		$this->getOutput()->redirect( $talk->getFullUrlForRedirect(), '302' );
		$this->getOutput()->enableClientCache();
		$this->getOutput()->setCdnMaxage(
			$this->getConfig()->get( MainConfigNames::ParserCacheExpireTime )
		);
		return true;
	}

	protected function getDisplayFormat() {
		return 'ooui';
	}

	public function requiresWrite() {
		return false;
	}

	public function requiresUnblock() {
		return false;
	}

	public function isListed() {
		return false;
	}

	protected function getMessagePrefix() {
		return 'special-talkpage';
	}

	public function getDescription() {
		// "talkpage" is already taken by CologneBlue
		return $this->msg( 'special-talkpage' );
	}

}

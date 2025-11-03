<?php
/**
 * @license GPL-2.0-or-later
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

	/** @inheritDoc */
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

	/** @inheritDoc */
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

	/** @inheritDoc */
	protected function getDisplayFormat() {
		return 'ooui';
	}

	/** @inheritDoc */
	public function requiresWrite() {
		return false;
	}

	/** @inheritDoc */
	public function requiresUnblock() {
		return false;
	}

	/** @inheritDoc */
	public function isListed() {
		return false;
	}

	/** @inheritDoc */
	protected function getMessagePrefix() {
		return 'special-talkpage';
	}

	/** @inheritDoc */
	public function getDescription() {
		// "talkpage" is already taken by CologneBlue
		return $this->msg( 'special-talkpage' );
	}

}

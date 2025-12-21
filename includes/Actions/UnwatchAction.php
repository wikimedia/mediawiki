<?php
/**
 * Performs the unwatch actions on a page
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Actions
 */

namespace MediaWiki\Actions;

use MediaWiki\Context\IContextSource;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Page\Article;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\Watchlist\WatchedItemStore;
use MediaWiki\Watchlist\WatchlistManager;

/**
 * Page removal from a user's watchlist
 *
 * @ingroup Actions
 */
class UnwatchAction extends WatchAction {

	public function __construct(
		Article $article,
		IContextSource $context,
		private readonly WatchlistManager $watchlistManager,
		WatchedItemStore $watchedItemStore,
		UserOptionsLookup $userOptionsLookup,
	) {
		parent::__construct( $article, $context, $watchlistManager, $watchedItemStore, $userOptionsLookup );
	}

	/** @inheritDoc */
	public function getName() {
		return 'unwatch';
	}

	/** @inheritDoc */
	public function onSubmit( $data ) {
		$this->watchlistManager->removeWatch(
			$this->getAuthority(),
			$this->getTitle()
		);

		return true;
	}

	/** @inheritDoc */
	protected function getFormFields() {
		return [
			'intro' => [
				'type' => 'info',
				'raw' => true,
				'default' => $this->msg( 'confirm-unwatch-top' )->parse()
			]
		];
	}

	protected function alterForm( HTMLForm $form ) {
		parent::alterForm( $form );
		$form->setWrapperLegendMsg( 'removewatch' );
		$form->setSubmitTextMsg( 'confirm-unwatch-button' );
	}

	/** @inheritDoc */
	public function onSuccess() {
		$msgKey = $this->getTitle()->isTalkPage() ? 'removedwatchtext-talk' : 'removedwatchtext';
		$this->getOutput()->addWikiMsg( $msgKey, $this->getTitle()->getPrefixedText() );
	}

	/** @inheritDoc */
	public function doesWrites() {
		return true;
	}
}

/** @deprecated class alias since 1.44 */
class_alias( UnwatchAction::class, 'UnwatchAction' );

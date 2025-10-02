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

	private WatchlistManager $watchlistManager;

	/**
	 * @param Article $article
	 * @param IContextSource $context
	 * @param WatchlistManager $watchlistManager
	 * @param WatchedItemStore $watchedItemStore
	 * @param UserOptionsLookup $userOptionsLookup
	 */
	public function __construct(
		Article $article,
		IContextSource $context,
		WatchlistManager $watchlistManager,
		WatchedItemStore $watchedItemStore,
		UserOptionsLookup $userOptionsLookup
	) {
		parent::__construct( $article, $context, $watchlistManager, $watchedItemStore, $userOptionsLookup );
		$this->watchlistManager = $watchlistManager;
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

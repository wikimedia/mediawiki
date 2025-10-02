<?php
/**
 * Performs the watch actions on a page
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Actions
 */

namespace MediaWiki\Actions;

use MediaWiki\Context\IContextSource;
use MediaWiki\Exception\PermissionsError;
use MediaWiki\Exception\ReadOnlyError;
use MediaWiki\Exception\UserBlockedError;
use MediaWiki\Exception\UserNotLoggedIn;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\Article;
use MediaWiki\Status\Status;
use MediaWiki\User\User;
use MediaWiki\User\UserOptionsLookup;
use MediaWiki\Watchlist\WatchedItem;
use MediaWiki\Watchlist\WatchedItemStoreInterface;
use MediaWiki\Watchlist\WatchlistManager;
use MediaWiki\Xml\XmlSelect;
use MessageLocalizer;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\TypeDef\ExpiryDef;

/**
 * Page addition to a user's watchlist
 *
 * @ingroup Actions
 */
class WatchAction extends FormAction {

	/** @var bool The value of the $wgWatchlistExpiry configuration variable. */
	protected $watchlistExpiry;

	/** @var string */
	protected $expiryFormFieldName = 'expiry';

	/** @var false|WatchedItem */
	protected $watchedItem = false;

	private WatchlistManager $watchlistManager;
	private UserOptionsLookup $userOptionsLookup;

	/**
	 * Only public since 1.21
	 *
	 * @param Article $article
	 * @param IContextSource $context
	 * @param WatchlistManager $watchlistManager
	 * @param WatchedItemStoreInterface $watchedItemStore
	 * @param UserOptionsLookup $userOptionsLookup
	 */
	public function __construct(
		Article $article,
		IContextSource $context,
		WatchlistManager $watchlistManager,
		WatchedItemStoreInterface $watchedItemStore,
		UserOptionsLookup $userOptionsLookup
	) {
		parent::__construct( $article, $context );
		$this->watchlistExpiry = $this->getContext()->getConfig()->get( MainConfigNames::WatchlistExpiry );
		if ( $this->watchlistExpiry ) {
			// The watchedItem is only used in this action's form if $wgWatchlistExpiry is enabled.
			$this->watchedItem = $watchedItemStore->getWatchedItem(
				$this->getUser(),
				$this->getTitle()
			);
		}
		$this->watchlistManager = $watchlistManager;
		$this->userOptionsLookup = $userOptionsLookup;
	}

	/** @inheritDoc */
	public function getName() {
		return 'watch';
	}

	/** @inheritDoc */
	public function requiresUnblock() {
		return false;
	}

	/** @inheritDoc */
	protected function getDescription() {
		return '';
	}

	/** @inheritDoc */
	public function onSubmit( $data ) {
		// Even though we're never unwatching here, use WatchlistManager::setWatch()
		// because it also checks for changed expiry.
		$result = $this->watchlistManager->setWatch(
			true,
			$this->getAuthority(),
			$this->getTitle(),
			$this->getRequest()->getVal( 'wp' . $this->expiryFormFieldName )
		);

		return Status::wrap( $result );
	}

	/**
	 * @throws UserNotLoggedIn
	 * @throws PermissionsError
	 * @throws ReadOnlyError
	 * @throws UserBlockedError
	 */
	protected function checkCanExecute( User $user ) {
		if ( !$user->isRegistered()
			|| ( $user->isTemp() && !$user->isAllowed( 'editmywatchlist' ) )
		) {
			throw new UserNotLoggedIn( 'watchlistanontext', 'watchnologin' );
		}

		parent::checkCanExecute( $user );
	}

	/** @inheritDoc */
	public function getRestriction() {
		return 'editmywatchlist';
	}

	/** @inheritDoc */
	protected function usesOOUI() {
		return true;
	}

	/** @inheritDoc */
	protected function getFormFields() {
		// If watchlist expiry is not enabled, return a simple confirmation message.
		if ( !$this->watchlistExpiry ) {
			return [
				'intro' => [
					'type' => 'info',
					'raw' => true,
					'default' => $this->msg( 'confirm-watch-top' )->parse(),
				],
			];
		}

		// Otherwise, use a select-list of expiries, where the default is the user's
		// preferred expiry time (or the existing watch duration if already temporarily watched).
		$default = $this->userOptionsLookup->getOption( $this->getUser(), 'watchstar-expiry' );
		$expiryOptions = static::getExpiryOptions( $this->getContext(), $this->watchedItem, $default );
		return [
			$this->expiryFormFieldName => [
				'type' => 'select',
				'label-message' => 'confirm-watch-label',
				'options' => $expiryOptions['options'],
				'default' => $expiryOptions['default'],
			]
		];
	}

	/**
	 * Get options and default for a watchlist expiry select list. If an expiry time is provided, it
	 * will be added to the top of the list as 'x days left'.
	 *
	 * @since 1.35
	 * @todo Move this somewhere better when it's being used in more than just this action.
	 *
	 * @param MessageLocalizer $msgLocalizer
	 * @param WatchedItem|false $watchedItem
	 * @param string $defaultExpiry The default expiry time to use if $watchedItem isn't already on a watchlist.
	 * @return mixed[] With keys `options` (string[]) and `default` (string).
	 */
	public static function getExpiryOptions(
		MessageLocalizer $msgLocalizer,
		$watchedItem,
		string $defaultExpiry = 'infinite'
	) {
		$expiryOptions = self::getExpiryOptionsFromMessage( $msgLocalizer );

		if ( !in_array( $defaultExpiry, $expiryOptions ) ) {
			$expiryOptions = array_merge( [ $defaultExpiry => $defaultExpiry ], $expiryOptions );
		}

		if ( $watchedItem instanceof WatchedItem && $watchedItem->getExpiry() ) {
			// If it's already being temporarily watched, add the existing expiry as an option in the dropdown.
			$currentExpiry = $watchedItem->getExpiry( TS_ISO_8601 );
			$daysLeft = $watchedItem->getExpiryInDaysText( $msgLocalizer, true );
			$expiryOptions = array_merge( [ $daysLeft => $currentExpiry ], $expiryOptions );

			// Always preselect the existing expiry.
			$defaultExpiry = $currentExpiry;
		}

		return [
			'options' => $expiryOptions,
			'default' => $defaultExpiry,
		];
	}

	/**
	 * Parse expiry options message. Fallback to english options
	 * if translated options are invalid or broken
	 *
	 * @param MessageLocalizer $msgLocalizer
	 * @param string|null $lang
	 * @return string[]
	 * @since 1.45 Method is public
	 */
	public static function getExpiryOptionsFromMessage(
		MessageLocalizer $msgLocalizer, ?string $lang = null
	): array {
		$expiryOptionsMsg = $msgLocalizer->msg( 'watchlist-expiry-options' );
		$optionsText = !$lang ? $expiryOptionsMsg->text() : $expiryOptionsMsg->inLanguage( $lang )->text();
		$options = XmlSelect::parseOptionsMessage(
			$optionsText
		);

		$expiryOptions = [];
		foreach ( $options as $label => $value ) {
			if ( strtotime( $value ) || wfIsInfinity( $value ) ) {
				$expiryOptions[$label] = $value;
			}
		}

		// If message options is invalid try to recover by returning
		// english options (T267611)
		if ( !$expiryOptions && $expiryOptionsMsg->getLanguage()->getCode() !== 'en' ) {
			return self::getExpiryOptionsFromMessage( $msgLocalizer, 'en' );
		}

		return $expiryOptions;
	}

	protected function alterForm( HTMLForm $form ) {
		$msg = $this->watchlistExpiry && $this->watchedItem ? 'updatewatchlist' : 'addwatch';
		$form->setWrapperLegendMsg( $msg );
		$submitMsg = $this->watchlistExpiry ? 'confirm-watch-button-expiry' : 'confirm-watch-button';
		$form->setSubmitTextMsg( $submitMsg );
		$form->setTokenSalt( 'watch' );
	}

	/**
	 * Show one of 8 possible success messages.
	 * The messages are:
	 * 1. addedwatchtext
	 * 2. addedwatchtext-talk
	 * 3. addedwatchindefinitelytext
	 * 4. addedwatchindefinitelytext-talk
	 * 5. addedwatchexpirytext
	 * 6. addedwatchexpirytext-talk
	 * 7. addedwatchexpiryhours
	 * 8. addedwatchexpiryhours-talk
	 */
	public function onSuccess() {
		$submittedExpiry = $this->getContext()->getRequest()->getText( 'wp' . $this->expiryFormFieldName );
		$this->getOutput()->addWikiMsg( $this->makeSuccessMessage( $submittedExpiry ) );
	}

	protected function makeSuccessMessage( string $submittedExpiry ): MessageValue {
		$msgKey = $this->getTitle()->isTalkPage() ? 'addedwatchtext-talk' : 'addedwatchtext';
		$params = [];
		if ( $submittedExpiry ) {
			// We can't use $this->watchedItem to get the expiry because it's not been saved at this
			// point in the request and so its values are those from before saving.
			$expiry = ExpiryDef::normalizeExpiry( $submittedExpiry, TS_ISO_8601 );

			// If the expiry label isn't one of the predefined ones in the dropdown, calculate 'x days'.
			$expiryDays = WatchedItem::calculateExpiryInDays( $expiry );
			$defaultLabels = static::getExpiryOptionsFromMessage( $this->getContext() );
			$localizedExpiry = array_search( $submittedExpiry, $defaultLabels );

			// Determine which message to use, depending on whether this is a talk page or not
			// and whether an expiry was selected.
			$isTalk = $this->getTitle()->isTalkPage();
			if ( wfIsInfinity( $expiry ) ) {
				$msgKey = $isTalk ? 'addedwatchindefinitelytext-talk' : 'addedwatchindefinitelytext';
			} elseif ( $expiryDays >= 1 ) {
				$msgKey = $isTalk ? 'addedwatchexpirytext-talk' : 'addedwatchexpirytext';
				$params[] = $localizedExpiry === false
					? $this->getContext()->msg( 'days', $expiryDays )->text()
					: $localizedExpiry;
			} else {
				// Less than one day.
				$msgKey = $isTalk ? 'addedwatchexpiryhours-talk' : 'addedwatchexpiryhours';
			}
		}
		return MessageValue::new( $msgKey )->params( $this->getTitle()->getPrefixedText(), ...$params );
	}

	/** @inheritDoc */
	public function doesWrites() {
		return true;
	}
}

/** @deprecated class alias since 1.44 */
class_alias( WatchAction::class, 'WatchAction' );

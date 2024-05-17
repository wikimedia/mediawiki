<?php
/**
 * Performs the watch actions on a page
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA
 *
 * @file
 * @ingroup Actions
 */

use MediaWiki\Context\IContextSource;
use MediaWiki\MainConfigNames;
use MediaWiki\Status\Status;
use MediaWiki\User\User;
use MediaWiki\Watchlist\WatchedItem;
use MediaWiki\Watchlist\WatchlistManager;
use MediaWiki\Xml\XmlSelect;
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

	/**
	 * Only public since 1.21
	 *
	 * @param Article $article
	 * @param IContextSource $context
	 * @param WatchlistManager $watchlistManager
	 * @param WatchedItemStore $watchedItemStore
	 */
	public function __construct(
		Article $article,
		IContextSource $context,
		WatchlistManager $watchlistManager,
		WatchedItemStore $watchedItemStore
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
	}

	public function getName() {
		return 'watch';
	}

	public function requiresUnblock() {
		return false;
	}

	protected function getDescription() {
		return '';
	}

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

	public function getRestriction() {
		return 'editmywatchlist';
	}

	protected function usesOOUI() {
		return true;
	}

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

		// Otherwise, use a select-list of expiries.
		$expiryOptions = static::getExpiryOptions( $this->getContext(), $this->watchedItem );
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
	 *
	 * @return mixed[] With keys `options` (string[]) and `default` (string).
	 */
	public static function getExpiryOptions( MessageLocalizer $msgLocalizer, $watchedItem ) {
		$expiryOptions = self::getExpiryOptionsFromMessage( $msgLocalizer );
		$default = in_array( 'infinite', $expiryOptions )
			? 'infinite'
			: current( $expiryOptions );
		if ( $watchedItem instanceof WatchedItem && $watchedItem->getExpiry() ) {
			// If it's already being temporarily watched,
			// add the existing expiry as the default option in the dropdown.
			$default = $watchedItem->getExpiry( TS_ISO_8601 );
			$daysLeft = $watchedItem->getExpiryInDaysText( $msgLocalizer, true );
			$expiryOptions = array_merge( [ $daysLeft => $default ], $expiryOptions );
		}
		return [
			'options' => $expiryOptions,
			'default' => $default,
		];
	}

	/**
	 * Parse expiry options message. Fallback to english options
	 * if translated options are invalid or broken
	 *
	 * @param MessageLocalizer $msgLocalizer
	 * @param string|null $lang
	 * @return string[]
	 */
	private static function getExpiryOptionsFromMessage(
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
		$msgKey = $this->getTitle()->isTalkPage() ? 'addedwatchtext-talk' : 'addedwatchtext';
		$expiryLabel = null;
		$submittedExpiry = $this->getContext()->getRequest()->getText( 'wp' . $this->expiryFormFieldName );
		if ( $submittedExpiry ) {
			// We can't use $this->watcheditem to get the expiry because it's not been saved at this
			// point in the request and so its values are those from before saving.
			$expiry = ExpiryDef::normalizeExpiry( $submittedExpiry, TS_ISO_8601 );

			// If the expiry label isn't one of the predefined ones in the dropdown, calculate 'x days'.
			$expiryDays = WatchedItem::calculateExpiryInDays( $expiry );
			$defaultLabels = static::getExpiryOptions( $this->getContext(), false )['options'];
			$localizedExpiry = array_search( $submittedExpiry, $defaultLabels );
			$expiryLabel = $expiryDays && $localizedExpiry === false
				? $this->getContext()->msg( 'days', $expiryDays )->text()
				: $localizedExpiry;

			// Determine which message to use, depending on whether this is a talk page or not
			// and whether an expiry was selected.
			$isTalk = $this->getTitle()->isTalkPage();
			if ( wfIsInfinity( $expiry ) ) {
				$msgKey = $isTalk ? 'addedwatchindefinitelytext-talk' : 'addedwatchindefinitelytext';
			} elseif ( $expiryDays > 0 ) {
				$msgKey = $isTalk ? 'addedwatchexpirytext-talk' : 'addedwatchexpirytext';
			} elseif ( $expiryDays < 1 ) {
				$msgKey = $isTalk ? 'addedwatchexpiryhours-talk' : 'addedwatchexpiryhours';
			}
		}
		$this->getOutput()->addWikiMsg( $msgKey, $this->getTitle()->getPrefixedText(), $expiryLabel );
	}

	public function doesWrites() {
		return true;
	}
}

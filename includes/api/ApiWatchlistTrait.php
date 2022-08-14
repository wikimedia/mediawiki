<?php

use MediaWiki\MediaWikiServices;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserOptionsLookup;
use MediaWiki\Watchlist\WatchlistManager;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\ExpiryDef;

/**
 * An ApiWatchlistTrait adds class properties and convenience methods for APIs that allow you to
 * watch a page. This should ONLY be used in API modules that extend ApiBase.
 * Also, it should not be used in ApiWatch, which has its own special handling.
 *
 * Note the class-level properties watchlistExpiryEnabled and watchlistMaxDuration must still be
 * set in the API module's constructor.
 *
 * @ingroup API
 * @since 1.35
 */
trait ApiWatchlistTrait {

	/** @var bool Whether watchlist expiries are enabled. */
	private $watchlistExpiryEnabled;

	/** @var string Relative maximum expiry. */
	private $watchlistMaxDuration;

	/** @var WatchlistManager */
	private $watchlistManager;

	/** @var UserOptionsLookup */
	private $userOptionsLookup;

	private function initServices() {
		if ( $this->watchlistManager !== null && $this->userOptionsLookup !== null ) {
			return;
		}
		// This trait is used outside of core and therefor fallback to global state - T263904
		$services = MediaWikiServices::getInstance();
		if ( $this->watchlistManager === null ) {
			$this->watchlistManager = $services->getWatchlistManager();
		}
		if ( $this->userOptionsLookup === null ) {
			$this->userOptionsLookup = $services->getUserOptionsLookup();
		}
	}

	/**
	 * Get additional allow params specific to watchlisting.
	 * This should be merged in with the result of self::getAllowedParams().
	 *
	 * This purposefully does not include the deprecated 'watch' and 'unwatch'
	 * parameters that some APIs still accept.
	 *
	 * @param string[] $watchOptions
	 * @return array
	 */
	protected function getWatchlistParams( array $watchOptions = [] ): array {
		if ( !$watchOptions ) {
			$watchOptions = [
				'watch',
				'unwatch',
				'preferences',
				'nochange',
			];
		}

		$result = [
			'watchlist' => [
				ParamValidator::PARAM_DEFAULT => 'preferences',
				ParamValidator::PARAM_TYPE => $watchOptions,
			],
		];

		if ( $this->watchlistExpiryEnabled ) {
			$result['watchlistexpiry'] = [
				ParamValidator::PARAM_TYPE => 'expiry',
				ExpiryDef::PARAM_MAX => $this->watchlistMaxDuration,
				ExpiryDef::PARAM_USE_MAX => true,
			];
		}

		return $result;
	}

	/**
	 * Set a watch (or unwatch) based the based on a watchlist parameter.
	 * @param string $watch Valid values: 'watch', 'unwatch', 'preferences', 'nochange'
	 * @param Title $title The article's title to change
	 * @param User $user The user to set watch/unwatch for
	 * @param string|null $userOption The user option to consider when $watch=preferences
	 * @param string|null $expiry Optional expiry timestamp in any format acceptable to wfTimestamp(),
	 *   null will not create expiries, or leave them unchanged should they already exist.
	 */
	protected function setWatch(
		string $watch,
		Title $title,
		User $user,
		?string $userOption = null,
		?string $expiry = null
	): void {
		$value = $this->getWatchlistValue( $watch, $title, $user, $userOption );
		$this->watchlistManager->setWatch( $value, $user, $title, $expiry );
	}

	/**
	 * Return true if we're to watch the page, false if not.
	 * @param string $watchlist Valid values: 'watch', 'unwatch', 'preferences', 'nochange'
	 * @param Title $title The page under consideration
	 * @param User $user The user get the value for.
	 * @param string|null $userOption The user option to consider when $watchlist=preferences.
	 *    If not set will use watchdefault always and watchcreations if $title doesn't exist.
	 * @return bool
	 */
	protected function getWatchlistValue(
		string $watchlist,
		Title $title,
		User $user,
		?string $userOption = null
	): bool {
		$this->initServices();
		$userWatching = $this->watchlistManager->isWatchedIgnoringRights( $user, $title );

		switch ( $watchlist ) {
			case 'watch':
				return true;

			case 'unwatch':
				return false;

			case 'preferences':
				// If the user is already watching, don't bother checking
				if ( $userWatching ) {
					return true;
				}
				// If the user is a bot, act as 'nochange' to avoid big watchlists on single users
				if ( $user->isBot() ) {
					return $userWatching;
				}
				// If no user option was passed, use watchdefault and watchcreations
				if ( $userOption === null ) {
					return $this->userOptionsLookup->getBoolOption( $user, 'watchdefault' ) ||
						$this->userOptionsLookup->getBoolOption( $user, 'watchcreations' ) &&
						!$title->exists();
				}

				// Watch the article based on the user preference
				return $this->userOptionsLookup->getBoolOption( $user, $userOption );

			// case 'nochange':
			default:
				return $userWatching;
		}
	}

	/**
	 * Get formatted expiry from the given parameters, or null if no expiry was provided.
	 * @param array $params Request parameters passed to the API.
	 * @return string|null
	 */
	protected function getExpiryFromParams( array $params ): ?string {
		$watchlistExpiry = null;
		if ( $this->watchlistExpiryEnabled && isset( $params['watchlistexpiry'] ) ) {
			$watchlistExpiry = ApiResult::formatExpiry( $params['watchlistexpiry'] );
		}

		return $watchlistExpiry;
	}

	/**
	 * Get existing expiry from the database.
	 *
	 * @param WatchedItemStoreInterface $store
	 * @param Title $title
	 * @param UserIdentity $user The user to get the expiry for.
	 * @return string|null
	 */
	protected function getWatchlistExpiry(
		WatchedItemStoreInterface $store,
		Title $title,
		UserIdentity $user
	): ?string {
		$watchedItem = $store->getWatchedItem( $user, $title );

		if ( $watchedItem ) {
			$expiry = $watchedItem->getExpiry();

			if ( $expiry !== null ) {
				return ApiResult::formatExpiry( $expiry );
			}
		}

		return null;
	}
}

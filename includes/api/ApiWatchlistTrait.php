<?php

namespace MediaWiki\Api;

use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentity;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\User\User;
use MediaWiki\User\UserIdentity;
use MediaWiki\Watchlist\WatchedItemStoreInterface;
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

	private ?WatchlistManager $watchlistManager = null;
	private ?UserOptionsLookup $userOptionsLookup = null;

	private function initServices() {
		// This trait is used outside of core and therefor fallback to global state - T263904
		$this->watchlistManager ??= MediaWikiServices::getInstance()->getWatchlistManager();
		$this->userOptionsLookup ??= MediaWikiServices::getInstance()->getUserOptionsLookup();
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
	 * @param PageIdentity $page The page to change
	 * @param User $user The user to set watch/unwatch for
	 * @param string|null $userOption The user option to consider when $watch=preferences
	 * @param string|null $expiry Optional expiry timestamp in any format acceptable to wfTimestamp(),
	 *   null will not create expiries, or leave them unchanged should they already exist.
	 */
	protected function setWatch(
		string $watch,
		PageIdentity $page,
		User $user,
		?string $userOption = null,
		?string $expiry = null
	): void {
		$value = $this->getWatchlistValue( $watch, $page, $user, $userOption );
		$this->watchlistManager->setWatch( $value, $user, $page, $expiry );
	}

	/**
	 * Return true if we're to watch the page, false if not.
	 * @param string $watchlist Valid values: 'watch', 'unwatch', 'preferences', 'nochange'
	 * @param PageIdentity $page The page under consideration
	 * @param User $user The user get the value for.
	 * @param string|null $userOption The user option to consider when $watchlist=preferences.
	 *    If not set will use watchdefault always and watchcreations if $page doesn't exist.
	 * @return bool
	 */
	protected function getWatchlistValue(
		string $watchlist,
		PageIdentity $page,
		User $user,
		?string $userOption = null
	): bool {
		$this->initServices();
		$userWatching = $this->watchlistManager->isWatchedIgnoringRights( $user, $page );

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
						( $this->userOptionsLookup->getBoolOption( $user, 'watchcreations' ) && !$page->exists() );
				}

				// Watch the article based on the user preference
				return $this->userOptionsLookup->getBoolOption( $user, $userOption );

			// case 'nochange':
			default:
				return $userWatching;
		}
	}

	/**
	 * Get formatted expiry from the given parameters. If no expiry was provided,
	 * check against user-preferred expiry for this action.
	 * Null is returned if there is no preference, or no user was given.
	 *
	 * @param array $params Request parameters passed to the API.
	 * @param UserIdentity|null $user Leave `null` if this action does not have a watchlist expiry preference.
	 * @param string $userOption The name of the watchlist preference for this action.
	 * @return string|null
	 */
	protected function getExpiryFromParams(
		array $params,
		?UserIdentity $user = null,
		string $userOption = 'watchdefault-expiry'
	): ?string {
		$watchlistExpiry = null;
		if ( $this->watchlistExpiryEnabled ) {
			// At this point, the ParamValidator has already normalized $params['watchlistexpiry'].
			$watchlistExpiry = $params['watchlistexpiry'] ?? null;
			if ( $user && $watchlistExpiry === null ) {
				$watchlistExpiry = ExpiryDef::normalizeExpiry(
					$this->userOptionsLookup->getOption( $user, $userOption )
				);
			} elseif ( $watchlistExpiry === null ) {
				return null;
			}
		}

		return ApiResult::formatExpiry( $watchlistExpiry );
	}

	/**
	 * Get existing expiry from the database.
	 *
	 * @param WatchedItemStoreInterface $store
	 * @param PageIdentity $page
	 * @param UserIdentity $user The user to get the expiry for.
	 * @return string|null
	 */
	protected function getWatchlistExpiry(
		WatchedItemStoreInterface $store,
		PageIdentity $page,
		UserIdentity $user
	): ?string {
		$watchedItem = $store->getWatchedItem( $user, $page );

		if ( $watchedItem ) {
			$expiry = $watchedItem->getExpiry();

			if ( $expiry !== null ) {
				return ApiResult::formatExpiry( $expiry );
			}
		}

		return null;
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiWatchlistTrait::class, 'ApiWatchlistTrait' );

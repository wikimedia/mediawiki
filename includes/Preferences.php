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

use MediaWiki\Auth\AuthManager;
use MediaWiki\MediaWikiServices;
use MediaWiki\Preferences\DefaultPreferencesFactory;

/**
 * This class has been replaced by the PreferencesFactory service.
 *
 * @deprecated since 1.31 use the PreferencesFactory service instead.
 */
class Preferences {

	/**
	 * A shim to maintain backwards-compatibility of this class, basically replicating the
	 * default behaviour of the PreferencesFactory service but not permitting overriding.
	 * @return DefaultPreferencesFactory
	 */
	protected static function getDefaultPreferencesFactory() {
		global $wgContLang;
		$authManager = AuthManager::singleton();
		$linkRenderer = MediaWikiServices::getInstance()->getLinkRenderer();
		$config = MediaWikiServices::getInstance()->getMainConfig();
		$preferencesFactory = new DefaultPreferencesFactory(
			$config, $wgContLang, $authManager, $linkRenderer
		);
		return $preferencesFactory;
	}

	/**
	 * @return array
	 */
	public static function getSaveBlacklist() {
		throw new Exception( __METHOD__ . '() is deprecated and does nothing' );
	}

	/**
	 * @throws MWException
	 * @param User $user
	 * @param IContextSource $context
	 * @return array|null
	 */
	public static function getPreferences( $user, IContextSource $context ) {
		$preferencesFactory = self::getDefaultPreferencesFactory();
		return $preferencesFactory->getFormDescriptor( $user, $context );
	}

	/**
	 * Loads existing values for a given array of preferences
	 * @throws MWException
	 * @param User $user
	 * @param IContextSource $context
	 * @param array &$defaultPreferences Array to load values for
	 * @return array|null
	 */
	public static function loadPreferenceValues( $user, $context, &$defaultPreferences ) {
		throw new Exception( __METHOD__ . '() is deprecated and does nothing' );
	}

	/**
	 * Pull option from a user account. Handles stuff like array-type preferences.
	 *
	 * @param string $name
	 * @param array $info
	 * @param User $user
	 * @return array|string
	 */
	public static function getOptionFromUser( $name, $info, $user ) {
		throw new Exception( __METHOD__ . '() is deprecated and does nothing' );
	}

	/**
	 * @param User $user
	 * @param IContextSource $context
	 * @param array &$defaultPreferences
	 * @return void
	 */
	public static function profilePreferences(
		$user, IContextSource $context, &$defaultPreferences
	) {
		wfDeprecated( __METHOD__, '1.31' );
		$defaultPreferences = self::getPreferences( $user, $context );
	}

	/**
	 * @param User $user
	 * @param IContextSource $context
	 * @param array &$defaultPreferences
	 * @return void
	 */
	public static function skinPreferences( $user, IContextSource $context, &$defaultPreferences ) {
		wfDeprecated( __METHOD__, '1.31' );
		$defaultPreferences = self::getPreferences( $user, $context );
	}

	/**
	 * @param User $user
	 * @param IContextSource $context
	 * @param array &$defaultPreferences
	 */
	public static function filesPreferences(
		$user, IContextSource $context, &$defaultPreferences
	) {
		wfDeprecated( __METHOD__, '1.31' );
		$defaultPreferences = self::getPreferences( $user, $context );
	}

	/**
	 * @param User $user
	 * @param IContextSource $context
	 * @param array &$defaultPreferences
	 * @return void
	 */
	public static function datetimePreferences(
		$user, IContextSource $context, &$defaultPreferences
	) {
		wfDeprecated( __METHOD__, '1.31' );
		$defaultPreferences = self::getPreferences( $user, $context );
	}

	/**
	 * @param User $user
	 * @param IContextSource $context
	 * @param array &$defaultPreferences
	 */
	public static function renderingPreferences(
		$user, IContextSource $context, &$defaultPreferences
	) {
		wfDeprecated( __METHOD__, '1.31' );
		$defaultPreferences = self::getPreferences( $user, $context );
	}

	/**
	 * @param User $user
	 * @param IContextSource $context
	 * @param array &$defaultPreferences
	 */
	public static function editingPreferences(
		$user, IContextSource $context, &$defaultPreferences
	) {
		wfDeprecated( __METHOD__, '1.31' );
		$defaultPreferences = self::getPreferences( $user, $context );
	}

	/**
	 * @param User $user
	 * @param IContextSource $context
	 * @param array &$defaultPreferences
	 */
	public static function rcPreferences( $user, IContextSource $context, &$defaultPreferences ) {
		wfDeprecated( __METHOD__, '1.31' );
		$defaultPreferences = self::getPreferences( $user, $context );
	}

	/**
	 * @param User $user
	 * @param IContextSource $context
	 * @param array &$defaultPreferences
	 */
	static function watchlistPreferences( $user, IContextSource $context, &$defaultPreferences ) {
		$config = $context->getConfig();
		$watchlistdaysMax = ceil( $config->get( 'RCMaxAge' ) / ( 3600 * 24 ) );

		# # Watchlist #####################################
		if ( $user->isAllowed( 'editmywatchlist' ) ) {
			$editWatchlistLinks = '';
			$editWatchlistModes = [
				'edit' => [ 'EditWatchlist', false ],
				'raw' => [ 'EditWatchlist', 'raw' ],
				'clear' => [ 'EditWatchlist', 'clear' ],
			];
			$linkRenderer = MediaWikiServices::getInstance()->getLinkRenderer();
			foreach ( $editWatchlistModes as $editWatchlistMode => $mode ) {
				// Messages: prefs-editwatchlist-edit, prefs-editwatchlist-raw, prefs-editwatchlist-clear
				$editWatchlistLinks .=
					new OOUI\ButtonWidget( [
						'href' => SpecialPage::getTitleFor( $mode[0], $mode[1] )->getLinkURL(),
						'label' => new OOUI\HtmlSnippet(
							$context->msg( "prefs-editwatchlist-{$editWatchlistMode}" )->parse()
						),
					] );
			}

			$defaultPreferences['editwatchlist'] = [
				'type' => 'info',
				'raw' => true,
				'default' => $editWatchlistLinks,
				'label-message' => 'prefs-editwatchlist-label',
				'section' => 'watchlist/editwatchlist',
			];
		}

		$defaultPreferences['watchlistdays'] = [
			'type' => 'float',
			'min' => 0,
			'max' => $watchlistdaysMax,
			'section' => 'watchlist/displaywatchlist',
			'help' => $context->msg( 'prefs-watchlist-days-max' )->numParams(
				$watchlistdaysMax )->escaped(),
			'label-message' => 'prefs-watchlist-days',
		];
		$defaultPreferences['wllimit'] = [
			'type' => 'int',
			'min' => 0,
			'max' => 1000,
			'label-message' => 'prefs-watchlist-edits',
			'help' => $context->msg( 'prefs-watchlist-edits-max' )->escaped(),
			'section' => 'watchlist/displaywatchlist',
		];
		$defaultPreferences['extendwatchlist'] = [
			'type' => 'toggle',
			'section' => 'watchlist/advancedwatchlist',
			'label-message' => 'tog-extendwatchlist',
		];
		$defaultPreferences['watchlisthideminor'] = [
			'type' => 'toggle',
			'section' => 'watchlist/advancedwatchlist',
			'label-message' => 'tog-watchlisthideminor',
		];
		$defaultPreferences['watchlisthidebots'] = [
			'type' => 'toggle',
			'section' => 'watchlist/advancedwatchlist',
			'label-message' => 'tog-watchlisthidebots',
		];
		$defaultPreferences['watchlisthideown'] = [
			'type' => 'toggle',
			'section' => 'watchlist/advancedwatchlist',
			'label-message' => 'tog-watchlisthideown',
		];
		$defaultPreferences['watchlisthideanons'] = [
			'type' => 'toggle',
			'section' => 'watchlist/advancedwatchlist',
			'label-message' => 'tog-watchlisthideanons',
		];
		$defaultPreferences['watchlisthideliu'] = [
			'type' => 'toggle',
			'section' => 'watchlist/advancedwatchlist',
			'label-message' => 'tog-watchlisthideliu',
		];
		$defaultPreferences['watchlistreloadautomatically'] = [
			'type' => 'toggle',
			'section' => 'watchlist/advancedwatchlist',
			'label-message' => 'tog-watchlistreloadautomatically',
		];
		$defaultPreferences['watchlistunwatchlinks'] = [
			'type' => 'toggle',
			'section' => 'watchlist/advancedwatchlist',
			'label-message' => 'tog-watchlistunwatchlinks',
		];

		if ( $config->get( 'RCWatchCategoryMembership' ) ) {
			$defaultPreferences['watchlisthidecategorization'] = [
				'type' => 'toggle',
				'section' => 'watchlist/advancedwatchlist',
				'label-message' => 'tog-watchlisthidecategorization',
			];
		}

		if ( $user->useRCPatrol() ) {
			$defaultPreferences['watchlisthidepatrolled'] = [
				'type' => 'toggle',
				'section' => 'watchlist/advancedwatchlist',
				'label-message' => 'tog-watchlisthidepatrolled',
			];
		}

		$watchTypes = [
			'edit' => 'watchdefault',
			'move' => 'watchmoves',
			'delete' => 'watchdeletion'
		];

		// Kinda hacky
		if ( $user->isAllowed( 'createpage' ) || $user->isAllowed( 'createtalk' ) ) {
			$watchTypes['read'] = 'watchcreations';
		}

		if ( $user->isAllowed( 'rollback' ) ) {
			$watchTypes['rollback'] = 'watchrollback';
		}

		if ( $user->isAllowed( 'upload' ) ) {
			$watchTypes['upload'] = 'watchuploads';
		}

		foreach ( $watchTypes as $action => $pref ) {
			if ( $user->isAllowed( $action ) ) {
				// Messages:
				// tog-watchdefault, tog-watchmoves, tog-watchdeletion, tog-watchcreations, tog-watchuploads
				// tog-watchrollback
				$defaultPreferences[$pref] = [
					'type' => 'toggle',
					'section' => 'watchlist/advancedwatchlist',
					'label-message' => "tog-$pref",
				];
			}
		}

		$defaultPreferences['watchlisttoken'] = [
			'type' => 'api',
		];

		$tokenButton = new OOUI\ButtonWidget( [
			'href' => SpecialPage::getTitleFor( 'ResetTokens' )->getLinkURL( [
				'returnto' => SpecialPage::getTitleFor( 'Preferences' )->getPrefixedText()
			] ),
			'label' => $context->msg( 'prefs-watchlist-managetokens' )->text(),
		] );
		$defaultPreferences['watchlisttoken-info'] = [
			'type' => 'info',
			'section' => 'watchlist/tokenwatchlist',
			'label-message' => 'prefs-watchlist-token',
			'help-message' => 'prefs-help-tokenmanagement',
			'raw' => true,
			'default' => (string)$tokenButton,
		];
	}

	/**
	 * @param User $user
	 * @param IContextSource $context
	 * @param array &$defaultPreferences
	 */
	public static function searchPreferences(
		$user, IContextSource $context, &$defaultPreferences
	) {
		wfDeprecated( __METHOD__, '1.31' );
		$defaultPreferences = self::getPreferences( $user, $context );
	}

	/**
	 * Dummy, kept for backwards-compatibility.
	 * @param User $user
	 * @param IContextSource $context
	 * @param array &$defaultPreferences
	 */
	public static function miscPreferences( $user, IContextSource $context, &$defaultPreferences ) {
	}

	/**
	 * @param User $user
	 * @param IContextSource $context
	 * @return array Text/links to display as key; $skinkey as value
	 */
	public static function generateSkinOptions( $user, IContextSource $context ) {
		wfDeprecated( __METHOD__, '1.31' );
		return self::getPreferences( $user, $context );
	}

	/**
	 * @param IContextSource $context
	 * @return array
	 */
	static function getDateOptions( IContextSource $context ) {
		throw new Exception( __METHOD__ . '() is deprecated and does nothing' );
	}

	/**
	 * @param IContextSource $context
	 * @return array
	 */
	public static function getImageSizes( IContextSource $context ) {
		throw new Exception( __METHOD__ . '() is deprecated and does nothing' );
	}

	/**
	 * @param IContextSource $context
	 * @return array
	 */
	public static function getThumbSizes( IContextSource $context ) {
		throw new Exception( __METHOD__ . '() is deprecated and does nothing' );
	}

	/**
	 * @param string $signature
	 * @param array $alldata
	 * @param HTMLForm $form
	 * @return bool|string
	 */
	public static function validateSignature( $signature, $alldata, $form ) {
		throw new Exception( __METHOD__ . '() is deprecated and does nothing' );
	}

	/**
	 * @param string $signature
	 * @param array $alldata
	 * @param HTMLForm $form
	 * @return string
	 */
	public static function cleanSignature( $signature, $alldata, $form ) {
		throw new Exception( __METHOD__ . '() is deprecated and does nothing now' );
	}

	/**
	 * @param User $user
	 * @param IContextSource $context
	 * @param string $formClass
	 * @param array $remove Array of items to remove
	 * @return PreferencesForm|HTMLForm
	 */
	public static function getFormObject(
		$user,
		IContextSource $context,
		$formClass = PreferencesForm::class,
		array $remove = []
	) {
		$preferencesFactory = self::getDefaultPreferencesFactory();
		return $preferencesFactory->getForm( $user, $context, $formClass, $remove );
	}

	/**
	 * @param IContextSource $context
	 * @return array
	 */
	public static function getTimezoneOptions( IContextSource $context ) {
		throw new Exception( __METHOD__ . '() is deprecated and does nothing' );
	}

	/**
	 * @param string $value
	 * @param array $alldata
	 * @return int
	 */
	public static function filterIntval( $value, $alldata ) {
		throw new Exception( __METHOD__ . '() is deprecated and does nothing' );
	}

	/**
	 * @param string $tz
	 * @param array $alldata
	 * @return string
	 */
	public static function filterTimezoneInput( $tz, $alldata ) {
		throw new Exception( __METHOD__ . '() is deprecated and does nothing' );
	}

	/**
	 * Handle the form submission if everything validated properly
	 *
	 * @deprecated since 1.31, use PreferencesFactory
	 *
	 * @param array $formData
	 * @param PreferencesForm $form
	 * @return bool|Status|string
	 */
	public static function tryFormSubmit( $formData, $form ) {
		$preferencesFactory = self::getDefaultPreferencesFactory();
		return $preferencesFactory->legacySaveFormData( $formData, $form );
	}

	/**
	 * @param array $formData
	 * @param PreferencesForm $form
	 * @return Status
	 */
	public static function tryUISubmit( $formData, $form ) {
		$preferencesFactory = self::getDefaultPreferencesFactory();
		return $preferencesFactory->legacySubmitForm( $formData, $form );
	}

	/**
	 * Get a list of all time zones
	 * @param Language $language Language used for the localized names
	 * @return array A list of all time zones. The system name of the time zone is used as key and
	 *  the value is an array which contains localized name, the timecorrection value used for
	 *  preferences and the region
	 * @since 1.26
	 */
	public static function getTimeZoneList( Language $language ) {
		throw new Exception( __METHOD__ . '() is deprecated and does nothing' );
	}
}

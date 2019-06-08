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
		$services = MediaWikiServices::getInstance();
		$authManager = AuthManager::singleton();
		$linkRenderer = $services->getLinkRenderer();
		$config = $services->getMainConfig();
		$preferencesFactory = new DefaultPreferencesFactory(
			$config, $services->getContentLanguage(), $authManager,
			$linkRenderer
		);
		return $preferencesFactory;
	}

	/**
	 * @throws MWException
	 * @param User $user
	 * @param IContextSource $context
	 * @return array|null
	 */
	public static function getPreferences( $user, IContextSource $context ) {
		wfDeprecated( __METHOD__, '1.31' );
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
	public static function watchlistPreferences(
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
		wfDeprecated( __METHOD__, '1.31' );
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
	 * @return PreferencesFormLegacy|HTMLForm
	 */
	public static function getFormObject(
		$user,
		IContextSource $context,
		$formClass = PreferencesFormLegacy::class,
		array $remove = []
	) {
		wfDeprecated( __METHOD__, '1.31' );
		$preferencesFactory = self::getDefaultPreferencesFactory();
		return $preferencesFactory->getForm( $user, $context, $formClass, $remove );
	}
}

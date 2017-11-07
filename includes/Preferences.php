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
		$preferencesFactory = self::getDefaultPreferencesFactory();
		return $preferencesFactory->getSaveBlacklist();
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
		$preferencesFactory = self::getDefaultPreferencesFactory();
		return $preferencesFactory->loadPreferenceValues( $user, $context, $defaultPreferences );
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
		$preferencesFactory = self::getDefaultPreferencesFactory();
		return $preferencesFactory->getOptionFromUser( $name, $info, $user );
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
		$preferencesFactory = self::getDefaultPreferencesFactory();
		$preferencesFactory->profilePreferences( $user, $context, $defaultPreferences );
	}

	/**
	 * @param User $user
	 * @param IContextSource $context
	 * @param array &$defaultPreferences
	 * @return void
	 */
	public static function skinPreferences( $user, IContextSource $context, &$defaultPreferences ) {
		$preferencesFactory = self::getDefaultPreferencesFactory();
		$preferencesFactory->skinPreferences( $user, $context, $defaultPreferences );
	}

	/**
	 * @param User $user
	 * @param IContextSource $context
	 * @param array &$defaultPreferences
	 */
	public static function filesPreferences(
		$user, IContextSource $context, &$defaultPreferences
	) {
		$preferencesFactory = self::getDefaultPreferencesFactory();
		$preferencesFactory->filesPreferences( $context, $defaultPreferences );
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
		$preferencesFactory = self::getDefaultPreferencesFactory();
		$preferencesFactory->datetimePreferences( $user, $context, $defaultPreferences );
	}

	/**
	 * @param User $user
	 * @param IContextSource $context
	 * @param array &$defaultPreferences
	 */
	public static function renderingPreferences(
		$user, IContextSource $context, &$defaultPreferences
	) {
		$preferencesFactory = self::getDefaultPreferencesFactory();
		$preferencesFactory->renderingPreferences( $context, $defaultPreferences );
	}

	/**
	 * @param User $user
	 * @param IContextSource $context
	 * @param array &$defaultPreferences
	 */
	public static function editingPreferences(
		$user, IContextSource $context, &$defaultPreferences
	) {
		$preferencesFactory = self::getDefaultPreferencesFactory();
		$preferencesFactory->editingPreferences( $user, $context, $defaultPreferences );
	}

	/**
	 * @param User $user
	 * @param IContextSource $context
	 * @param array &$defaultPreferences
	 */
	public static function rcPreferences( $user, IContextSource $context, &$defaultPreferences ) {
		$preferencesFactory = self::getDefaultPreferencesFactory();
		$preferencesFactory->rcPreferences( $user, $context, $defaultPreferences );
	}

	/**
	 * @param User $user
	 * @param IContextSource $context
	 * @param array &$defaultPreferences
	 */
	public static function watchlistPreferences(
		$user, IContextSource $context, &$defaultPreferences
	) {
		$preferencesFactory = self::getDefaultPreferencesFactory();
		$preferencesFactory->watchlistPreferences( $user, $context, $defaultPreferences );
	}

	/**
	 * @param User $user
	 * @param IContextSource $context
	 * @param array &$defaultPreferences
	 */
	public static function searchPreferences(
		$user, IContextSource $context, &$defaultPreferences
	) {
		$preferencesFactory = self::getDefaultPreferencesFactory();
		$preferencesFactory->searchPreferences( $defaultPreferences );
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
	 * @param User $user The User object
	 * @param IContextSource $context
	 * @return array Text/links to display as key; $skinkey as value
	 */
	public static function generateSkinOptions( $user, IContextSource $context ) {
		$preferencesFactory = self::getDefaultPreferencesFactory();
		return $preferencesFactory->generateSkinOptions( $user, $context );
	}

	/**
	 * @param IContextSource $context
	 * @return array
	 */
	static function getDateOptions( IContextSource $context ) {
		$preferencesFactory = self::getDefaultPreferencesFactory();
		return $preferencesFactory->getDateOptions( $context );
	}

	/**
	 * @param IContextSource $context
	 * @return array
	 */
	public static function getImageSizes( IContextSource $context ) {
		$preferencesFactory = self::getDefaultPreferencesFactory();
		return $preferencesFactory->getImageSizes( $context );
	}

	/**
	 * @param IContextSource $context
	 * @return array
	 */
	public static function getThumbSizes( IContextSource $context ) {
		$preferencesFactory = self::getDefaultPreferencesFactory();
		return $preferencesFactory->getThumbSizes( $context );
	}

	/**
	 * @param string $signature
	 * @param array $alldata
	 * @param HTMLForm $form
	 * @return bool|string
	 */
	public static function validateSignature( $signature, $alldata, $form ) {
		$preferencesFactory = self::getDefaultPreferencesFactory();
		return $preferencesFactory->validateSignature( $signature, $alldata, $form );
	}

	/**
	 * @param string $signature
	 * @param array $alldata
	 * @param HTMLForm $form
	 * @return string
	 */
	public static function cleanSignature( $signature, $alldata, $form ) {
		$preferencesFactory = self::getDefaultPreferencesFactory();
		return $preferencesFactory->cleanSignature( $signature, $alldata, $form );
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
		$formClass = 'PreferencesForm',
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
		$preferencesFactory = self::getDefaultPreferencesFactory();
		return $preferencesFactory->getTimezoneOptions( $context );
	}

	/**
	 * @param string $value
	 * @param array $alldata
	 * @return int
	 */
	public static function filterIntval( $value, $alldata ) {
		$preferencesFactory = self::getDefaultPreferencesFactory();
		return $preferencesFactory::filterIntval( $value, $alldata );
	}

	/**
	 * @param string $tz
	 * @param array $alldata
	 * @return string
	 */
	public static function filterTimezoneInput( $tz, $alldata ) {
		$preferencesFactory = self::getDefaultPreferencesFactory();
		return $preferencesFactory::filterTimezoneInput( $tz, $alldata );
	}

	/**
	 * Handle the form submission if everything validated properly
	 *
	 * @param array $formData
	 * @param PreferencesForm $form
	 * @return bool|Status|string
	 */
	public static function tryFormSubmit( $formData, $form ) {
		$preferencesFactory = self::getDefaultPreferencesFactory();
		return $preferencesFactory->saveFormData( $formData, $form );
	}

	/**
	 * @param array $formData
	 * @param PreferencesForm $form
	 * @return Status
	 */
	public static function tryUISubmit( $formData, $form ) {
		$preferencesFactory = self::getDefaultPreferencesFactory();
		return $preferencesFactory->submitForm( $formData, $form );
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
		$preferencesFactory = self::getDefaultPreferencesFactory();
		return $preferencesFactory->getTimeZoneList( $language );
	}
}

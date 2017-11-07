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

use MediaWiki\MediaWikiServices;

/**
 * This class has been replaced by the PreferencesFactory service.
 *
 * @deprecated since 1.31 use the PreferencesFactory service instead.
 */
class Preferences {

	/**
	 * @return array
	 */
	public static function getSaveBlacklist() {
		$preferencesFactory = MediaWikiServices::getInstance()->getPreferencesFactory();
		return $preferencesFactory->getSaveBlacklist();
	}

	/**
	 * @throws MWException
	 * @param User $user
	 * @param IContextSource $context
	 * @return array|null
	 */
	public static function getPreferences( $user, $context ) {
		$preferencesFactory = MediaWikiServices::getInstance()->getPreferencesFactory();
		return $preferencesFactory->getPreferences( $user, $context );
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
		$preferencesFactory = MediaWikiServices::getInstance()->getPreferencesFactory();
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
		$preferencesFactory = MediaWikiServices::getInstance()->getPreferencesFactory();
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
		$preferencesFactory = MediaWikiServices::getInstance()->getPreferencesFactory();
		$preferencesFactory->profilePreferences( $user, $context, $defaultPreferences );
	}

	/**
	 * @param User $user
	 * @param IContextSource $context
	 * @param array &$defaultPreferences
	 * @return void
	 */
	public static function skinPreferences( $user, IContextSource $context, &$defaultPreferences ) {
		$preferencesFactory = MediaWikiServices::getInstance()->getPreferencesFactory();
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
		$preferencesFactory = MediaWikiServices::getInstance()->getPreferencesFactory();
		$preferencesFactory->filesPreferences( $user, $context, $defaultPreferences );
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
		$preferencesFactory = MediaWikiServices::getInstance()->getPreferencesFactory();
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
		$preferencesFactory = MediaWikiServices::getInstance()->getPreferencesFactory();
		$preferencesFactory->renderingPreferences( $user, $context, $defaultPreferences );
	}

	/**
	 * @param User $user
	 * @param IContextSource $context
	 * @param array &$defaultPreferences
	 */
	public static function editingPreferences(
		$user, IContextSource $context, &$defaultPreferences
	) {
		$preferencesFactory = MediaWikiServices::getInstance()->getPreferencesFactory();
		$preferencesFactory->editingPreferences( $user, $context, $defaultPreferences );
	}

	/**
	 * @param User $user
	 * @param IContextSource $context
	 * @param array &$defaultPreferences
	 */
	public static function rcPreferences( $user, IContextSource $context, &$defaultPreferences ) {
		$preferencesFactory = MediaWikiServices::getInstance()->getPreferencesFactory();
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
		$preferencesFactory = MediaWikiServices::getInstance()->getPreferencesFactory();
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
		$preferencesFactory = MediaWikiServices::getInstance()->getPreferencesFactory();
		$preferencesFactory->searchPreferences( $user, $context, $defaultPreferences );
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
		$preferencesFactory = MediaWikiServices::getInstance()->getPreferencesFactory();
		return $preferencesFactory->generateSkinOptions( $user, $context );
	}

	/**
	 * @param IContextSource $context
	 * @return array
	 */
	static function getDateOptions( IContextSource $context ) {
		$preferencesFactory = MediaWikiServices::getInstance()->getPreferencesFactory();
		return $preferencesFactory->getDateOptions( $context );
	}

	/**
	 * @param IContextSource $context
	 * @return array
	 */
	public static function getImageSizes( IContextSource $context ) {
		$preferencesFactory = MediaWikiServices::getInstance()->getPreferencesFactory();
		return $preferencesFactory->getImageSizes( $context );
	}

	/**
	 * @param IContextSource $context
	 * @return array
	 */
	public static function getThumbSizes( IContextSource $context ) {
		$preferencesFactory = MediaWikiServices::getInstance()->getPreferencesFactory();
		return $preferencesFactory->getThumbSizes( $context );
	}

	/**
	 * @param string $signature
	 * @param array $alldata
	 * @param HTMLForm $form
	 * @return bool|string
	 */
	public static function validateSignature( $signature, $alldata, $form ) {
		$preferencesFactory = MediaWikiServices::getInstance()->getPreferencesFactory();
		return $preferencesFactory->validateSignature( $signature, $alldata, $form );
	}

	/**
	 * @param string $signature
	 * @param array $alldata
	 * @param HTMLForm $form
	 * @return string
	 */
	public static function cleanSignature( $signature, $alldata, $form ) {
		$preferencesFactory = MediaWikiServices::getInstance()->getPreferencesFactory();
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
		$preferencesFactory = MediaWikiServices::getInstance()->getPreferencesFactory();
		return $preferencesFactory->getFormObject( $user, $context, $formClass, $remove );
	}

	/**
	 * @param IContextSource $context
	 * @return array
	 */
	public static function getTimezoneOptions( IContextSource $context ) {
		$preferencesFactory = MediaWikiServices::getInstance()->getPreferencesFactory();
		return $preferencesFactory->getTimezoneOptions( $context );
	}

	/**
	 * @param string $value
	 * @param array $alldata
	 * @return int
	 */
	public static function filterIntval( $value, $alldata ) {
		$preferencesFactory = MediaWikiServices::getInstance()->getPreferencesFactory();
		return $preferencesFactory::filterIntval( $value, $alldata );
	}

	/**
	 * @param string $tz
	 * @param array $alldata
	 * @return string
	 */
	public static function filterTimezoneInput( $tz, $alldata ) {
		$preferencesFactory = MediaWikiServices::getInstance()->getPreferencesFactory();
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
		$preferencesFactory = MediaWikiServices::getInstance()->getPreferencesFactory();
		return $preferencesFactory->tryFormSubmit( $formData, $form );
	}

	/**
	 * @param array $formData
	 * @param PreferencesForm $form
	 * @return Status
	 */
	public static function tryUISubmit( $formData, $form ) {
		$preferencesFactory = MediaWikiServices::getInstance()->getPreferencesFactory();
		return $preferencesFactory->tryUISubmit( $formData, $form );
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
		$preferencesFactory = MediaWikiServices::getInstance()->getPreferencesFactory();
		return $preferencesFactory->getTimeZoneList( $language );
	}
}

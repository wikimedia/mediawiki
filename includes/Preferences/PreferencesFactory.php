<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Preferences;

use MediaWiki\Context\IContextSource;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\User\User;
use PreferencesFormOOUI;

/**
 * A PreferencesFactory is a MediaWiki service that provides the definitions of preferences for a
 * given user. These definitions are in the form of an HTMLForm descriptor.
 *
 * PreferencesFormOOUI (a subclass of HTMLForm) is used to generate the Preferences form, and
 * handles generic submission, CSRF protection, layout and other logic in a reusable manner.
 *
 * In order to generate the form, the HTMLForm object needs an array structure detailing the
 * form fields available, and that's what this implementations of this interface provide. Each
 * element of the array is a basic property-list, including the type of field, the label it is to be
 * given in the form, callbacks for validation and 'filtering', and other pertinent information.
 * Note that the 'default' field is named for generic forms, and does not represent the preference's
 * default (which is stored in $wgDefaultUserOptions), but the default for the form field, which
 * should be whatever the user has set for that preference. There is no need to override it unless
 * you have some special storage logic (for instance, those not presently stored as options, but
 * which are best set from the user preferences view).
 *
 * Field types are implemented as subclasses of the generic HTMLFormField object, and typically
 * implement at least getInputHTML, which generates the HTML for the input field to be placed in the
 * table.
 *
 * Once fields have been retrieved and validated, submission logic is handed over to the
 * submitForm() method of this interface.
 *
 * @stable to implement
 */
interface PreferencesFactory {

	/**
	 * Get the preferences form for a given user. This method retrieves the form descriptor for the
	 * user, instantiates a new form using the descriptor and returns the instantiated form object.
	 * @param User $user
	 * @param IContextSource $contextSource
	 * @param string $formClass
	 * @param array $remove
	 * @return HTMLForm
	 */
	public function getForm(
		User $user,
		IContextSource $contextSource,
		$formClass = PreferencesFormOOUI::class,
		array $remove = []
	);

	/**
	 * Get the preferences form descriptor.
	 * @param User $user
	 * @param IContextSource $contextSource
	 * @return mixed[][] An HTMLForm descriptor array.
	 */
	public function getFormDescriptor( User $user, IContextSource $contextSource );

	/**
	 * Get the names of preferences that should never be saved
	 * (such as 'realname' and 'emailaddress').
	 * @return string[]
	 */
	public function getSaveBlacklist();

	/**
	 * Return an associative array mapping preferences keys to the kind of a
	 * preference they're used for. Different kinds are handled differently
	 * when setting or reading preferences.
	 *
	 * See PreferencesFactory::listResetKinds for the list of valid option
	 * types that can be provided.
	 *
	 * @since 1.43
	 * @param User $user
	 * @param IContextSource $context
	 * @param array|null $options Assoc. array with options keys to check as keys.
	 *   Defaults to all user options.
	 * @return string[] The key => kind mapping data
	 */
	public function getResetKinds(
		User $user,
		IContextSource $context,
		$options = null
	): array;

	/**
	 * Return a list of the types of user options currently returned by
	 * getResetKinds().
	 *
	 * Currently, the option kinds are:
	 * - 'registered' - preferences which are registered in core MediaWiki or
	 *                  by extensions using the UserGetDefaultOptions hook.
	 * - 'registered-multiselect' - as above, using the 'multiselect' type.
	 * - 'registered-checkmatrix' - as above, using the 'checkmatrix' type.
	 * - 'userjs' - preferences with names starting with 'userjs-', intended to
	 *              be used by user scripts.
	 * - 'special' - "preferences" that are not accessible via
	 *              UserOptionsLookup::getOptions or UserOptionsManager::setOptions.
	 * - 'unused' - preferences about which MediaWiki doesn't know anything.
	 *              These are usually legacy options, removed in newer versions.
	 *
	 * The API (and possibly others) use this function to determine the possible
	 * option types for validation purposes, so make sure to update this when a
	 * new option kind is added.
	 *
	 * @since 1.43
	 * @return string[] Option kinds
	 */
	public function listResetKinds();

	/**
	 * Get the list of option names which have been saved by the user, thus
	 * having non-default values, which match the specified set of kinds.
	 *
	 * @since 1.43
	 * @param User $user
	 * @param IContextSource $context
	 * @param string|string[] $kinds List of option kinds, which may be any of
	 *   the kinds returned by listResetKinds(), or "all" for all options.
	 * @return string[]
	 */
	public function getOptionNamesForReset(
		User $user,
		IContextSource $context,
		$kinds
	);
}

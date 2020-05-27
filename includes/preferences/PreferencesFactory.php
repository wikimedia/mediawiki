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

namespace MediaWiki\Preferences;

use HTMLForm;
use IContextSource;
use PreferencesFormOOUI;
use User;

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
}

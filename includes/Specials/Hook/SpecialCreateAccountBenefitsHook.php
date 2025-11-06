<?php

namespace MediaWiki\Hook;

use MediaWiki\HTMLForm\HTMLForm;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "SpecialCreateAccountBenefits" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface SpecialCreateAccountBenefitsHook {

	/**
	 * Replace the default signup page content about the benefits of registering an account
	 * ("Wikipedia is made by people like you...") on Special:CreateAccount.
	 *
	 * @param string|null &$html HTML to use instead of the default .mw-createacct-benefits-container
	 *   block. By default, this is null, which means the default content will be used.
	 * @param array $info Array of information:
	 *   - context: (IContextSource) Context object.
	 *   - form: (HTMLForm) The signup form. Read-only - the form HTML has already been generated.
	 * @phan-param array{context:\MediaWiki\Context\IContextSource,form:HTMLForm} $info
	 * @param array &$options Array of modifiable options:
	 *   - beforeForm: (bool, default false) Whether to insert the HTML before the form. This is
	 *     mainly useful on mobile (where the login form might push the benefits out of view; but
	 *     also, a long benefits block might push the form out of view).
	 * @phan-param array{beforeForm:bool} &$options
	 * @return bool|void True or no return value to continue or false to abort.
	 * @since 1.40
	 */
	public function onSpecialCreateAccountBenefits( ?string &$html, array $info, array &$options );

}

<?php
/**
 * Special page which uses an HTMLForm to handle processing.
 *
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
 * @ingroup SpecialPage
 */

namespace MediaWiki\SpecialPage;

use MediaWiki\Context\DerivativeContext;
use MediaWiki\Exception\UserBlockedError;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Request\DerivativeRequest;
use MediaWiki\Status\Status;
use MediaWiki\User\User;

/**
 * Special page which uses an HTMLForm to handle processing.  This is mostly a
 * clone of FormAction.  More special pages should be built this way; maybe this could be
 * a new structure for SpecialPages.
 *
 * @ingroup SpecialPage
 */
abstract class FormSpecialPage extends SpecialPage {
	/**
	 * The subpage of the special page.
	 * @var string|null
	 */
	protected $par = null;

	/**
	 * @var array|null POST data preserved across re-authentication
	 * @since 1.32
	 */
	protected $reauthPostData = null;

	/**
	 * Get an HTMLForm descriptor array
	 * @return array
	 */
	abstract protected function getFormFields();

	/**
	 * Add pre-HTML to the form
	 * @return string HTML which will be sent to $form->addPreHtml()
	 * @since 1.38
	 */
	protected function preHtml() {
		return '';
	}

	/**
	 * Add post-HTML to the form
	 * @return string HTML which will be sent to $form->addPostHtml()
	 * @since 1.38
	 */
	protected function postHtml() {
		return '';
	}

	/**
	 * Play with the HTMLForm if you need to more substantially
	 */
	protected function alterForm( HTMLForm $form ) {
	}

	/**
	 * Get message prefix for HTMLForm
	 *
	 * @since 1.21
	 * @return string
	 */
	protected function getMessagePrefix() {
		return strtolower( $this->getName() );
	}

	/**
	 * Get display format for the form. See HTMLForm documentation for available values.
	 *
	 * @since 1.25
	 * @return string
	 */
	protected function getDisplayFormat() {
		return 'table';
	}

	/**
	 * Get the HTMLForm to control behavior
	 * @return HTMLForm|null
	 */
	protected function getForm() {
		$context = $this->getContext();
		$onSubmit = $this->onSubmit( ... );

		if ( $this->reauthPostData ) {
			// Restore POST data
			$context = new DerivativeContext( $context );
			$oldRequest = $this->getRequest();
			$context->setRequest( new DerivativeRequest(
				$oldRequest, $this->reauthPostData + $oldRequest->getQueryValues(), true
			) );

			// But don't treat it as a "real" submission just in case of some
			// crazy kind of CSRF.
			$onSubmit = static function () {
				return false;
			};
		}

		$form = HTMLForm::factory(
			$this->getDisplayFormat(),
			$this->getFormFields(),
			$context,
			$this->getMessagePrefix()
		);
		if ( !$this->requiresPost() ) {
			$form->setMethod( 'get' );
		}
		$form->setSubmitCallback( $onSubmit );
		if ( $this->getDisplayFormat() !== 'ooui' ) {
			// No legend and wrapper by default in OOUI forms, but can be set manually
			// from alterForm()
			$form->setWrapperLegendMsg( $this->getMessagePrefix() . '-legend' );
		}

		$headerMsg = $this->msg( $this->getMessagePrefix() . '-text' );
		if ( !$headerMsg->isDisabled() ) {
			$form->addHeaderHtml( $headerMsg->parseAsBlock() );
		}

		$form->addPreHtml( $this->preHtml() );
		$form->addPostHtml( $this->postHtml() );

		// Give precedence to subpage syntax
		$field = $this->getSubpageField();
		// cast to string so that "0" is not thrown away
		if ( strval( $this->par ) !== '' && $field ) {
			$this->getRequest()->setVal( $form->getField( $field )->getName(), $this->par );
			$form->setTitle( $this->getPageTitle() );
		}
		$this->alterForm( $form );
		if ( $form->getMethod() == 'post' ) {
			// Retain query parameters (uselang etc) on POST requests
			$params = array_diff_key(
				$this->getRequest()->getQueryValues(), [ 'title' => null ] );
			$form->addHiddenField( 'redirectparams', wfArrayToCgi( $params ) );
		}

		// Give hooks a chance to alter the form, adding extra fields or text etc
		$this->getHookRunner()->onSpecialPageBeforeFormDisplay( $this->getName(), $form );

		return $form;
	}

	/**
	 * Process the form on submission.
	 * @phpcs:disable MediaWiki.Commenting.FunctionComment.ExtraParamComment
	 * @param array $data
	 * @param HTMLForm|null $form
	 * @suppress PhanCommentParamWithoutRealParam Many implementations don't have $form
	 * @return bool|string|array|Status As documented for HTMLForm::trySubmit.
	 * @phpcs:enable MediaWiki.Commenting.FunctionComment.ExtraParamComment
	 */
	abstract public function onSubmit( array $data /* HTMLForm $form = null */ );

	/**
	 * Do something exciting on successful processing of the form, most likely to show a
	 * confirmation message
	 * @since 1.22 Default is to do nothing
	 */
	public function onSuccess() {
	}

	/**
	 * Basic SpecialPage workflow: get a form, send it to the user; get some data back,
	 *
	 * @param string|null $par Subpage string if one was specified
	 */
	public function execute( $par ) {
		$this->setParameter( $par );
		$this->setHeaders();
		$this->outputHeader();

		// This will throw exceptions if there's a problem
		$this->checkExecutePermissions( $this->getUser() );

		$securityLevel = $this->getLoginSecurityLevel();
		if ( $securityLevel !== false && !$this->checkLoginSecurityLevel( $securityLevel ) ) {
			return;
		}

		$form = $this->getForm();
		// GET forms can be set as includable
		if ( !$this->including() ) {
			$result = $this->getShowAlways() ? $form->showAlways() : $form->show();
		} else {
			$result = $form->prepareForm()->tryAuthorizedSubmit();
		}
		if ( $result === true || ( $result instanceof Status && $result->isGood() ) ) {
			$this->onSuccess();
		}
	}

	/**
	 * Whether the form should always be shown despite the success of submission.
	 * @since 1.40
	 * @return bool
	 */
	protected function getShowAlways() {
		return false;
	}

	/**
	 * Maybe do something interesting with the subpage parameter
	 * @param string|null $par
	 */
	protected function setParameter( $par ) {
		$this->par = $par;
	}

	/**
	 * Override this function to set the field name used in the subpage syntax.
	 * @since 1.40
	 * @return false|string
	 */
	protected function getSubpageField() {
		return false;
	}

	/**
	 * Called from execute() to check if the given user can perform this action.
	 * Failures here must throw subclasses of ErrorPageError.
	 * @param User $user
	 * @throws UserBlockedError
	 */
	protected function checkExecutePermissions( User $user ) {
		$this->checkPermissions();

		if ( $this->requiresUnblock() ) {
			$block = $user->getBlock();
			if ( $block && $block->isSitewide() ) {
				throw new UserBlockedError(
					$block,
					$user,
					$this->getLanguage(),
					$this->getRequest()->getIP()
				);
			}
		}

		if ( $this->requiresWrite() ) {
			$this->checkReadOnly();
		}
	}

	/**
	 * Whether this action should using POST method to submit, default to true
	 * @since 1.40
	 * @return bool
	 */
	public function requiresPost() {
		return true;
	}

	/**
	 * Whether this action requires the wiki not to be locked, default to requiresPost()
	 * @return bool
	 */
	public function requiresWrite() {
		return $this->requiresPost();
	}

	/**
	 * Whether this action cannot be executed by a blocked user, default to requiresPost()
	 * @return bool
	 */
	public function requiresUnblock() {
		return $this->requiresPost();
	}

	/**
	 * Preserve POST data across reauthentication
	 *
	 * @since 1.32
	 * @param array $data
	 */
	protected function setReauthPostData( array $data ) {
		$this->reauthPostData = $data;
	}
}

/** @deprecated class alias since 1.41 */
class_alias( FormSpecialPage::class, 'FormSpecialPage' );

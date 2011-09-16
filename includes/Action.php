<?php
/**
 * Actions are things which can be done to pages (edit, delete, rollback, etc).  They
 * are distinct from Special Pages because an action must apply to exactly one page.
 *
 * To add an action in an extension, create a subclass of Action, and add the key to
 * $wgActions.  There is also the deprecated UnknownAction hook
 *
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA
 *
 * @file
 */
abstract class Action {

	/**
	 * Page on which we're performing the action
	 * @var Article
	 */
	protected $page;

	/**
	 * IContextSource if specified; otherwise we'll use the Context from the Page
	 * @var IContextSource
	 */
	protected $context;

	/**
	 * The fields used to create the HTMLForm
	 * @var Array
	 */
	protected $fields;

	/**
	 * Get the Action subclass which should be used to handle this action, false if
	 * the action is disabled, or null if it's not recognised
	 * @param $action String
	 * @param $overrides Array
	 * @return bool|null|string
	 */
	private final static function getClass( $action, array $overrides ) {
		global $wgActions;
		$action = strtolower( $action );

		if ( !isset( $wgActions[$action] ) ) {
			return null;
		}

		if ( $wgActions[$action] === false ) {
			return false;
		} elseif ( $wgActions[$action] === true && isset( $overrides[$action] ) ) {
			return $overrides[$action];
		} elseif ( $wgActions[$action] === true ) {
			return ucfirst( $action ) . 'Action';
		} else {
			return $wgActions[$action];
		}
	}

	/**
	 * Get an appropriate Action subclass for the given action
	 * @param $action String
	 * @param $page Article
	 * @return Action|false|null false if the action is disabled, null
	 *     if it is not recognised
	 */
	public final static function factory( $action, Page $page ) {
		$class = self::getClass( $action, $page->getActionOverrides() );
		if ( $class ) {
			$obj = new $class( $page );
			return $obj;
		}
		return $class;
	}

	/**
	 * Check if a given action is recognised, even if it's disabled
	 *
	 * @param $name String: name of an action
	 * @return Bool
	 */
	public final static function exists( $name ) {
		return self::getClass( $name ) !== null;
	}

	/**
	 * Get the IContextSource in use here
	 * @return IContextSource
	 */
	protected final function getContext() {
		if ( $this->context instanceof IContextSource ) {
			return $this->context;
		}
		return $this->page->getContext();
	}

	/**
	 * Get the WebRequest being used for this instance
	 *
	 * @return WebRequest
	 */
	protected final function getRequest() {
		return $this->getContext()->getRequest();
	}

	/**
	 * Get the OutputPage being used for this instance
	 *
	 * @return OutputPage
	 */
	protected final function getOutput() {
		return $this->getContext()->getOutput();
	}

	/**
	 * Shortcut to get the User being used for this instance
	 *
	 * @return User
	 */
	protected final function getUser() {
		return $this->getContext()->getUser();
	}

	/**
	 * Shortcut to get the Skin being used for this instance
	 *
	 * @return Skin
	 */
	protected final function getSkin() {
		return $this->getContext()->getSkin();
	}

	/**
	 * Shortcut to get the user Language being used for this instance
	 *
	 * @return Skin
	 */
	protected final function getLang() {
		return $this->getContext()->getLang();
	}

	/**
	 * Shortcut to get the Title object from the page
	 * @return Title
	 */
	protected final function getTitle() {
		return $this->page->getTitle();
	}

	/**
	 * Protected constructor: use Action::factory( $action, $page ) to actually build
	 * these things in the real world
	 * @param Page $page
	 */
	protected function __construct( Page $page ) {
		$this->page = $page;
	}

	/**
	 * Return the name of the action this object responds to
	 * @return String lowercase
	 */
	public abstract function getName();

	/**
	 * Get the permission required to perform this action.  Often, but not always,
	 * the same as the action name
	 */
	public abstract function getRestriction();

	/**
	 * Checks if the given user (identified by an object) can perform this action.  Can be
	 * overridden by sub-classes with more complicated permissions schemes.  Failures here
	 * must throw subclasses of ErrorPageError
	 *
	 * @param $user User: the user to check, or null to use the context user
	 * @throws ErrorPageError
	 */
	protected function checkCanExecute( User $user ) {
		if ( $this->requiresWrite() && wfReadOnly() ) {
			throw new ReadOnlyError();
		}

		if ( $this->getRestriction() !== null && !$user->isAllowed( $this->getRestriction() ) ) {
			throw new PermissionsError( $this->getRestriction() );
		}

		if ( $this->requiresUnblock() && $user->isBlocked() ) {
			$block = $user->mBlock;
			throw new UserBlockedError( $block );
		}
	}

	/**
	 * Whether this action requires the wiki not to be locked
	 * @return Bool
	 */
	public function requiresWrite() {
		return true;
	}

	/**
	 * Whether this action can still be executed by a blocked user
	 * @return Bool
	 */
	public function requiresUnblock() {
		return true;
	}

	/**
	 * Set output headers for noindexing etc.  This function will not be called through
	 * the execute() entry point, so only put UI-related stuff in here.
	 */
	protected function setHeaders() {
		$out = $this->getOutput();
		$out->setRobotPolicy( "noindex,nofollow" );
		$out->setPageTitle( $this->getPageTitle() );
		$this->getOutput()->setSubtitle( $this->getDescription() );
		$out->setArticleRelated( true );
	}

	/**
	 * Returns the name that goes in the \<h1\> page title
	 *
	 * @return String
	 */
	protected function getPageTitle() {
		return $this->getTitle()->getPrefixedText();
	}

	/**
	 * Returns the description that goes below the \<h1\> tag
	 *
	 * @return String
	 */
	protected function getDescription() {
		return wfMsg( strtolower( $this->getName() ) );
	}

	/**
	 * The main action entry point.  Do all output for display and send it to the context
	 * output.  Do not use globals $wgOut, $wgRequest, etc, in implementations; use
	 * $this->getOutput(), etc.
	 * @throws ErrorPageError
	 */
	public abstract function show();

	/**
	 * Execute the action in a silent fashion: do not display anything or release any errors.
	 * @param $data Array values that would normally be in the POST request
	 * @param $captureErrors Bool whether to catch exceptions and just return false
	 * @return Bool whether execution was successful
	 */
	public abstract function execute();
}

abstract class FormAction extends Action {

	/**
	 * Get an HTMLForm descriptor array
	 * @return Array
	 */
	protected abstract function getFormFields();

	/**
	 * Add pre- or post-text to the form
	 * @return String HTML which will be sent to $form->addPreText()
	 */
	protected function preText() { return ''; }
	protected function postText() { return ''; }

	/**
	 * Play with the HTMLForm if you need to more substantially
	 * @param $form HTMLForm
	 */
	protected function alterForm( HTMLForm $form ) {}

	/**
	 * Get the HTMLForm to control behaviour
	 * @return HTMLForm|null
	 */
	protected function getForm() {
		$this->fields = $this->getFormFields();

		// Give hooks a chance to alter the form, adding extra fields or text etc
		wfRunHooks( 'ActionModifyFormFields', array( $this->getName(), &$this->fields, $this->page ) );

		$form = new HTMLForm( $this->fields, $this->getContext() );
		$form->setSubmitCallback( array( $this, 'onSubmit' ) );

		// Retain query parameters (uselang etc)
		$form->addHiddenField( 'action', $this->getName() ); // Might not be the same as the query string
		$params = array_diff_key(
			$this->getRequest()->getQueryValues(),
			array( 'action' => null, 'title' => null )
		);
		$form->addHiddenField( 'redirectparams', wfArrayToCGI( $params ) );

		$form->addPreText( $this->preText() );
		$form->addPostText( $this->postText() );
		$this->alterForm( $form );

		// Give hooks a chance to alter the form, adding extra fields or text etc
		wfRunHooks( 'ActionBeforeFormDisplay', array( $this->getName(), &$form, $this->page ) );

		return $form;
	}

	/**
	 * Process the form on POST submission.  If you return false from getFormFields(),
	 * this will obviously never be reached.  If you don't want to do anything with the
	 * form, just return false here
	 * @param  $data Array
	 * @return Bool|Array true for success, false for didn't-try, array of errors on failure
	 */
	public abstract function onSubmit( $data );

	/**
	 * Do something exciting on successful processing of the form.  This might be to show
	 * a confirmation message (watch, rollback, etc) or to redirect somewhere else (edit,
	 * protect, etc).
	 */
	public abstract function onSuccess();

	/**
	 * The basic pattern for actions is to display some sort of HTMLForm UI, maybe with
	 * some stuff underneath (history etc); to do some processing on submission of that
	 * form (delete, protect, etc) and to do something exciting on 'success', be that
	 * display something new or redirect to somewhere.  Some actions have more exotic
	 * behaviour, but that's what subclassing is for :D
	 */
	public function show() {
		$this->setHeaders();

		// This will throw exceptions if there's a problem
		$this->checkCanExecute( $this->getUser() );

		$form = $this->getForm();
		if ( $form->show() ) {
			$this->onSuccess();
		}
	}

	/**
	 * @see Action::execute()
	 * @throws ErrorPageError
	 * @param array|null $data
	 * @param bool $captureErrors
	 * @return bool
	 */
	public function execute( array $data = null, $captureErrors = true ) {
		try {
			// Set a new context so output doesn't leak.
			$this->context = clone $this->page->getContext();

			// This will throw exceptions if there's a problem
			$this->checkCanExecute( $this->getUser() );

			$fields = array();
			foreach ( $this->fields as $key => $params ) {
				if ( isset( $data[$key] ) ) {
					$fields[$key] = $data[$key];
				} elseif ( isset( $params['default'] ) ) {
					$fields[$key] = $params['default'];
				} else {
					$fields[$key] = null;
				}
			}
			$status = $this->onSubmit( $fields );
			if ( $status === true ) {
				// This might do permanent stuff
				$this->onSuccess();
				return true;
			} else {
				return false;
			}
		}
		catch ( ErrorPageError $e ) {
			if ( $captureErrors ) {
				return false;
			} else {
				throw $e;
			}
		}
	}
}

/**
 * Actions generally fall into two groups: the show-a-form-then-do-something-with-the-input
 * format (protect, delete, move, etc), and the just-do-something format (watch, rollback,
 * patrol, etc).
 */
abstract class FormlessAction extends Action {

	/**
	 * Show something on GET request.
	 * @return String|null will be added to the HTMLForm if present, or just added to the
	 *     output if not.  Return null to not add anything
	 */
	public abstract function onView();

	/**
	 * We don't want an HTMLForm
	 */
	protected function getFormFields() {
		return false;
	}

	public function onSubmit( $data ) {
		return false;
	}

	public function onSuccess() {
		return false;
	}

	public function show() {
		$this->setHeaders();

		// This will throw exceptions if there's a problem
		$this->checkCanExecute( $this->getUser() );

		$this->getOutput()->addHTML( $this->onView() );
	}

	/**
	 * Execute the action silently, not giving any output.  Since these actions don't have
	 * forms, they probably won't have any data, but some (eg rollback) may do
	 * @param $data Array values that would normally be in the GET request
	 * @param $captureErrors Bool whether to catch exceptions and just return false
	 * @return Bool whether execution was successful
	 */
	public function execute( array $data = null, $captureErrors = true ) {
		try {
			// Set a new context so output doesn't leak.
			$this->context = clone $this->page->getContext();
			if ( is_array( $data ) ) {
				$this->context->setRequest( new FauxRequest( $data, false ) );
			}

			// This will throw exceptions if there's a problem
			$this->checkCanExecute( $this->getUser() );

			$this->onView();
			return true;
		}
		catch ( ErrorPageError $e ) {
			if ( $captureErrors ) {
				return false;
			} else {
				throw $e;
			}
		}
	}
}

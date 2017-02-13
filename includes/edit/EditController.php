<?php
/**
 * Abstract controller for page editing.
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
 */

/**
 *
 */
abstract class EditController {

	/**
	 * Constructor-set
	 */

	protected $page;

	protected $title;

	protected $user;

	protected $data;

	protected $model;

	/**
	 * Derived from edit form data
	 */

	/** @var bool */
	protected $allowBlankSummary = false;

	/** @var bool */
	protected $allowBlankArticle = false;

	/** @var bool */
	protected $allowSelfRedirect = false;

	/** @var bool */
	protected $recreate = false;

	/**
	 * Needed to attempt save
	 */

	/** @var string */
	protected $autoSumm = '';

	/**
	 * @param WikiPage $page
	 * @param User $user
	 */
	final public function __construct( WikiPage $page, User $user ) {
		$this->page = $page;
		$this->title = $this->page->getTitle(); // shortcut
		$this->user = $user;

		// edit form data
		$this->data = new EditFormData();
		$this->data->contentModel = $this->title->getContentModel();
		$this->data->contentFormat = ContentHandler::getForModelID( $this->data->contentModel )
			->getDefaultFormat();

		// edit model
		$this->model = new EditModel( $page, $this->user, $this->data );
	}

	final public function getPage() {
		return $this->page;
	}

	final public function getUser() {
		return $this->user;
	}

	final public function getData() {
		return $this->data;
	}

	final public function getModel() {
		return $this->model;
	}

	/**
	 * Attempt submission
	 * @param array|bool $resultDetails See docs for $result in internalAttemptSave
	 * @throws UserBlockedError|ReadOnlyError|ThrottledError|PermissionsError
	 * @return Status The resulting status object.
	 */
	final public function attemptSave( &$resultDetails = false ) {
		$options = [
			'allowBlankArticle' => $this->allowBlankArticle,
			'allowBlankSummary' => $this->allowBlankSummary,
			'allowSelfRedirect' => $this->allowSelfRedirect,
			'recreate' => $this->recreate,
		];
		$info = [
			'autoSumm' => $this->autoSumm,
		];

		$status = $this->model->internalAttemptSave( $info, $options, $resultDetails );

		Hooks::run( 'AbstractEditController::attemptSave:after', [ $this, $status, $resultDetails ] );

		return $status;
	}

	final protected function validateContentModelAndFormat() {
		try {
			$handler = ContentHandler::getForModelID( $this->data->contentModel );
		} catch ( MWUnknownContentModelException $e ) {
			throw new ErrorPageError(
				'editpage-invalidcontentmodel-title',
				'editpage-invalidcontentmodel-text',
				[ $this->data->contentModel ]
			);
		}

		if ( !$handler->isSupportedFormat( $this->data->contentFormat ) ) {
			throw new ErrorPageError(
				'editpage-notsupportedcontentformat-title',
				'editpage-notsupportedcontentformat-text',
				[ $this->data->contentFormat,
					ContentHandler::getLocalizedName( $this->data->contentModel ) ]
			);
		}

		/**
		 * @todo Check if the desired model is allowed in this namespace, and if
		 *   a transition from the page's current model to the new model is
		 *   allowed.
		 */
	}

	/**
	 * Returns whether section editing is supported for the current page.
	 * Subclasses may override this to replace the default behavior, which is
	 * to check ContentHandler::supportsSections.
	 *
	 * @return bool True if this edit page supports sections, false otherwise.
	 */
	final public function isSectionEditSupported() {
		$contentHandler = ContentHandler::getForTitle( $this->title );
		return $contentHandler->supportsSections();
	}

	final public function getAutoSummary() {
		return $this->autoSumm;
	}

}

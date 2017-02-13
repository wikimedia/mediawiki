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
abstract class AbstractEditController {

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

	/**
	 * Needed to attempt save
	 */

	/** @var bool|null */
	private $deletedSinceEdit = null;

	/** @var bool|stdClass */
	private $lastDelete = null;

	/** @var string */
	protected $autoSumm = '';

	/**
	 * Magic accessor, prevents invalid field access (mostly for debugging)
	 * @param string $field
	 */
	public function __get( $field ) {
		throw new InvalidArgumentException( "$field is not a valid Editor property." );
	}

	/**
	 * Magic mutator, prevents invalid field mutation (mostly for debugging)
	 * @param string $field
	 * @param mixed $value
	 */
	public function __set( $field, $value ) {
		throw new InvalidArgumentException( "$field is not a valid Editor property." );
	}

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
		];
		$info = [
			'autoSumm' => $this->autoSumm,
			'wasDeleted' => $this->wasDeletedSinceLastEdit(),
		];

		$status = $this->model->internalAttemptSave( $info, $options, $resultDetails );

		Hooks::run( 'AbstractEditController::attemptSave:after', [ $this, $status, $resultDetails ] );

		return $status;
	}

	/**
	 * Check if a page was deleted while the user was editing it, before submit.
	 * Note that we rely on the logging table, which hasn't been always there,
	 * but that doesn't matter, because this only applies to brand new
	 * deletes.
	 * @param bool|stdClass &$lastDelete Gives the raw db result for the last deletion
	 * @return bool
	 */
	public function wasDeletedSinceLastEdit( &$lastDelete = null ) {
		if ( $this->deletedSinceEdit !== null ) {
			$lastDelete = $this->lastDelete;
			return $this->deletedSinceEdit;
		}

		$this->deletedSinceEdit = false;

		if ( !$this->title->exists() && $this->title->isDeletedQuick() ) {
			$this->lastDelete = $this->getLastDelete();
			if ( $this->lastDelete ) {
				$deleteTime = wfTimestamp( TS_MW, $this->lastDelete->log_timestamp );
				if ( $deleteTime > $this->data->startTime ) {
					$this->deletedSinceEdit = true;
				}
			}
		}

		$lastDelete = $this->lastDelete;
		return $this->deletedSinceEdit;
	}

	/**
	 * @return bool|stdClass
	 */
	private function getLastDelete() {
		$dbr = wfGetDB( DB_REPLICA );
		return $dbr->selectRow(
			[ 'logging', 'user' ],
			[
				'log_type',
				'log_action',
				'log_timestamp',
				'log_user',
				'log_namespace',
				'log_title',
				'log_comment',
				'log_params',
				'log_deleted',
				'user_name'
			],
			[
				'log_namespace' => $this->title->getNamespace(),
				'log_title' => $this->title->getDBkey(),
				'log_type' => 'delete',
				'log_action' => 'delete',
				'user_id=log_user'
			],
			__METHOD__,
			[ 'LIMIT' => 1, 'ORDER BY' => 'log_timestamp DESC' ]
		);
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

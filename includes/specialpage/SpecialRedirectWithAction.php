<?php

namespace MediaWiki\SpecialPage;

use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\MediaWikiServices;
use MediaWiki\Status\Status;
use MediaWiki\Title\MalformedTitleException;
use MediaWiki\Title\Title;
use SearchEngineFactory;

/**
 * Abstract to simplify creation of redirect special pages
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
 * @stable to extend
 *
 * @file
 * @ingroup SpecialPage
 * @author DannyS712
 */
abstract class SpecialRedirectWithAction extends RedirectSpecialPage {
	/** @var string */
	protected $action;

	/** @var string */
	protected $msgPrefix;

	/** @var SearchEngineFactory */
	private $searchEngineFactory;

	/**
	 * @stable to call
	 * @since 1.39 SearchEngineFactory added
	 *
	 * @param string $name
	 * @param string $action
	 * @param string $msgPrefix
	 * @param SearchEngineFactory|null $searchEngineFactory Not providing this param is deprecated since 1.39
	 */
	public function __construct(
		$name,
		$action,
		$msgPrefix,
		?SearchEngineFactory $searchEngineFactory = null
	) {
		parent::__construct( $name );
		$this->action = $action;
		$this->msgPrefix = $msgPrefix;
		if ( !$searchEngineFactory ) {
			// Fallback to global state if the new parameter was not provided
			wfDeprecated( __METHOD__ . ' without providing SearchEngineFactory', '1.39' );
			$searchEngineFactory = MediaWikiServices::getInstance()->getSearchEngineFactory();
		}
		$this->searchEngineFactory = $searchEngineFactory;
	}

	/**
	 * @inheritDoc
	 */
	public function getRedirect( $subpage ) {
		if ( $subpage === null || $subpage === '' ) {
			return false;
		}
		$this->mAddedRedirectParams['title'] = $subpage;
		$this->mAddedRedirectParams['action'] = $this->action;
		return true;
	}

	/**
	 * @stable to override
	 */
	protected function showNoRedirectPage() {
		$this->setHeaders();
		$this->outputHeader();
		$this->showForm();
	}

	private function showForm() {
		// Dynamic messages used:
		// 'special' . $this->msgPrefix . '-page'
		// 'special' . $this->msgPrefix . '-submit'
		// Each special page that extends this should include those as comments for grep
		$form = HTMLForm::factory( 'ooui', [
			'page' => [
				'type' => 'text',
				'name' => 'page',
				'label-message' => 'special' . $this->msgPrefix . '-page',
				'required' => true,
			],
		], $this->getContext(), $this->msgPrefix );
		$form->setSubmitTextMsg( 'special' . $this->msgPrefix . '-submit' );
		$form->setSubmitCallback( $this->onFormSubmit( ... ) );
		$form->show();
	}

	/**
	 * @stable to override
	 *
	 * @param array $formData
	 *
	 * @return Status|null
	 */
	public function onFormSubmit( $formData ) {
		$title = $formData['page'];
		try {
			$page = Title::newFromTextThrow( $title );
		} catch ( MalformedTitleException $e ) {
			return Status::newFatal( $e->getMessageObject() );
		}
		$query = [ 'action' => $this->action ];
		$url = $page->getFullUrlForRedirect( $query );
		$this->getOutput()->redirect( $url );
	}

	/**
	 * @stable to override
	 * @return bool
	 */
	public function isListed() {
		return true;
	}

	/**
	 * Return an array of subpages beginning with $search that this special page will accept.
	 *
	 * @param string $search Prefix to search for
	 * @param int $limit Maximum number of results to return (usually 10)
	 * @param int $offset Number of results to skip (usually 0)
	 * @return string[] Matching subpages
	 */
	public function prefixSearchSubpages( $search, $limit, $offset ) {
		return $this->prefixSearchString( $search, $limit, $offset, $this->searchEngineFactory );
	}

	/**
	 * @stable to override
	 * @return string
	 */
	protected function getGroupName() {
		return 'redirects';
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialRedirectWithAction::class, 'SpecialRedirectWithAction' );

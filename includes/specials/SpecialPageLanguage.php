<?php
/**
 * Implements Special:PageLanguage
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
 * @author Kunal Grover
 * @since 1.24
 */

/**
 * Special page for changing the content language of a page
 *
 * @ingroup SpecialPage
 */
class SpecialPageLanguage extends FormSpecialPage {
	/**
	 * @var string URL to go to if language change successful
	 */
	private $goToUrl;

	public function __construct() {
		parent::__construct( 'PageLanguage', 'pagelang' );
	}

	public function doesWrites() {
		return true;
	}

	protected function preText() {
		$this->getOutput()->addModules( 'mediawiki.special.pageLanguage' );
	}

	protected function getFormFields() {
		// Get default from the subpage of Special page
		$defaultName = $this->par;

		$page = [];
		$page['pagename'] = [
			'type' => 'title',
			'label-message' => 'pagelang-name',
			'default' => $defaultName,
			'autofocus' => $defaultName === null,
			'exists' => true,
		];

		// Options for whether to use the default language or select language
		$selectoptions = [
			(string)$this->msg( 'pagelang-use-default' )->escaped() => 1,
			(string)$this->msg( 'pagelang-select-lang' )->escaped() => 2,
		];
		$page['selectoptions'] = [
			'id' => 'mw-pl-options',
			'type' => 'radio',
			'options' => $selectoptions,
			'default' => 1
		];

		// Building a language selector
		$userLang = $this->getLanguage()->getCode();
		$languages = Language::fetchLanguageNames( $userLang, 'mwfile' );
		ksort( $languages );
		$options = [];
		foreach ( $languages as $code => $name ) {
			$options["$code - $name"] = $code;
		}

		$page['language'] = [
			'id' => 'mw-pl-languageselector',
			'cssclass' => 'mw-languageselector',
			'type' => 'select',
			'options' => $options,
			'label-message' => 'pagelang-language',
			'default' => $this->getConfig()->get( 'LanguageCode' ),
		];

		// Allow user to enter a comment explaining the change
		$page['reason'] = [
			'type' => 'text',
			'label-message' => 'pagelang-reason'
		];

		return $page;
	}

	protected function postText() {
		if ( $this->par ) {
			return $this->showLogFragment( $this->par );
		}
		return '';
	}

	protected function getDisplayFormat() {
		return 'ooui';
	}

	public function alterForm( HTMLForm $form ) {
		Hooks::run( 'LanguageSelector', [ $this->getOutput(), 'mw-languageselector' ] );
		$form->setSubmitTextMsg( 'pagelang-submit' );
	}

	/**
	 *
	 * @param array $data
	 * @return Status
	 */
	public function onSubmit( array $data ) {
		$pageName = $data['pagename'];

		// Check if user wants to use default language
		if ( $data['selectoptions'] == 1 ) {
			$newLanguage = 'default';
		} else {
			$newLanguage = $data['language'];
		}

		try {
			$title = Title::newFromTextThrow( $pageName );
		} catch ( MalformedTitleException $ex ) {
			return Status::newFatal( $ex->getMessageObject() );
		}

		// Url to redirect to after the operation
		$this->goToUrl = $title->getFullUrlForRedirect(
			$title->isRedirect() ? [ 'redirect' => 'no' ] : []
		);

		return self::changePageLanguage(
			$this->getContext(),
			$title,
			$newLanguage,
			$data['reason'] === null ? '' : $data['reason']
		);
	}

	/**
	 * @param IContextSource $context
	 * @param Title $title
	 * @param string $newLanguage Language code
	 * @param string $reason Reason for the change
	 * @param array $tags Change tags to apply to the log entry
	 * @return Status
	 */
	public static function changePageLanguage( IContextSource $context, Title $title,
		$newLanguage, $reason, array $tags = [] ) {
		// Get the default language for the wiki
		$defLang = $context->getConfig()->get( 'LanguageCode' );

		$pageId = $title->getArticleID();

		// Check if article exists
		if ( !$pageId ) {
			return Status::newFatal(
				'pagelang-nonexistent-page',
				wfEscapeWikiText( $title->getPrefixedText() )
			);
		}

		// Load the page language from DB
		$dbw = wfGetDB( DB_MASTER );
		$oldLanguage = $dbw->selectField(
			'page',
			'page_lang',
			[ 'page_id' => $pageId ],
			__METHOD__
		);

		// Check if user wants to use the default language
		if ( $newLanguage === 'default' ) {
			$newLanguage = null;
		}

		// No change in language
		if ( $newLanguage === $oldLanguage ) {
			// Check if old language does not exist
			if ( !$oldLanguage ) {
				return Status::newFatal( ApiMessage::create(
					[
						'pagelang-unchanged-language-default',
						wfEscapeWikiText( $title->getPrefixedText() )
					],
					'pagelang-unchanged-language'
				) );
			}
			return Status::newFatal(
				'pagelang-unchanged-language',
				wfEscapeWikiText( $title->getPrefixedText() ),
				$oldLanguage
			);
		}

		// Hardcoded [def] if the language is set to null
		$logOld = $oldLanguage ? $oldLanguage : $defLang . '[def]';
		$logNew = $newLanguage ? $newLanguage : $defLang . '[def]';

		// Writing new page language to database
		$dbw->update(
			'page',
			[ 'page_lang' => $newLanguage ],
			[
				'page_id' => $pageId,
				'page_lang' => $oldLanguage
			],
			__METHOD__
		);

		if ( !$dbw->affectedRows() ) {
			return Status::newFatal( 'pagelang-db-failed' );
		}

		// Logging change of language
		$logParams = [
			'4::oldlanguage' => $logOld,
			'5::newlanguage' => $logNew
		];
		$entry = new ManualLogEntry( 'pagelang', 'pagelang' );
		$entry->setPerformer( $context->getUser() );
		$entry->setTarget( $title );
		$entry->setParameters( $logParams );
		$entry->setComment( $reason );
		$entry->setTags( $tags );

		$logid = $entry->insert();
		$entry->publish( $logid );

		// Force re-render so that language-based content (parser functions etc.) gets updated
		$title->invalidateCache();

		return Status::newGood( (object)[
			'oldLanguage' => $logOld,
			'newLanguage' => $logNew,
			'logId' => $logid,
		] );
	}

	public function onSuccess() {
		// Success causes a redirect
		$this->getOutput()->redirect( $this->goToUrl );
	}

	function showLogFragment( $title ) {
		$moveLogPage = new LogPage( 'pagelang' );
		$out1 = Xml::element( 'h2', null, $moveLogPage->getName()->text() );
		$out2 = '';
		LogEventsList::showLogExtract( $out2, 'pagelang', $title );
		return $out1 . $out2;
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
		return $this->prefixSearchString( $search, $limit, $offset );
	}

	protected function getGroupName() {
		return 'pagetools';
	}
}

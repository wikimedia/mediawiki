<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\FileRepo\File\File;
use MediaWiki\FileRepo\RepoGroup;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Language\ILanguageConverter;
use MediaWiki\Languages\LanguageConverterFactory;
use MediaWiki\Linker\Linker;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\Title;
use SearchEngineFactory;

/**
 * Search the database for files of the requested hash, comparing this with the
 * 'img_sha1' field in the image table.
 *
 * @ingroup SpecialPage
 * @author Raimond Spekking, based on Special:MIMESearch by Ævar Arnfjörð Bjarmason
 */
class SpecialFileDuplicateSearch extends SpecialPage {
	/**
	 * @var string The form input hash
	 */
	private $hash = '';

	/**
	 * @var string The form input filename
	 */
	private $filename = '';

	/**
	 * @var File|null selected reference file, if present
	 */
	private $file = null;

	private LinkBatchFactory $linkBatchFactory;
	private RepoGroup $repoGroup;
	private SearchEngineFactory $searchEngineFactory;
	private ILanguageConverter $languageConverter;

	public function __construct(
		LinkBatchFactory $linkBatchFactory,
		RepoGroup $repoGroup,
		SearchEngineFactory $searchEngineFactory,
		LanguageConverterFactory $languageConverterFactory
	) {
		parent::__construct( 'FileDuplicateSearch' );
		$this->linkBatchFactory = $linkBatchFactory;
		$this->repoGroup = $repoGroup;
		$this->searchEngineFactory = $searchEngineFactory;
		$this->languageConverter = $languageConverterFactory->getLanguageConverter( $this->getContentLanguage() );
	}

	/**
	 * Fetch dupes from all connected file repositories.
	 *
	 * @return File[]
	 */
	private function getDupes() {
		return $this->repoGroup->findBySha1( $this->hash );
	}

	/**
	 * @param File[] $dupes
	 */
	private function showList( $dupes ) {
		$html = [];
		$html[] = "<ol class='special'>";

		foreach ( $dupes as $dupe ) {
			$line = $this->formatResult( $dupe );
			$html[] = "<li>" . $line . "</li>";
		}
		$html[] = '</ol>';

		$this->getOutput()->addHTML( implode( "\n", $html ) );
	}

	/** @inheritDoc */
	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();

		$this->filename = $par ?? $this->getRequest()->getText( 'filename' );
		$this->file = null;
		$this->hash = '';
		$title = Title::newFromText( $this->filename, NS_FILE );
		if ( $title && $title->getText() != '' ) {
			$this->file = $this->repoGroup->findFile( $title );
		}

		$out = $this->getOutput();

		# Create the input form
		$formFields = [
			'filename' => [
				'type' => 'text',
				'name' => 'filename',
				'label-message' => 'fileduplicatesearch-filename',
				'id' => 'filename',
				'size' => 50,
				'default' => $this->filename,
			],
		];
		$htmlForm = HTMLForm::factory( 'ooui', $formFields, $this->getContext() );
		$htmlForm->setTitle( $this->getPageTitle() );
		$htmlForm->setMethod( 'get' );
		$htmlForm->setSubmitTextMsg( $this->msg( 'fileduplicatesearch-submit' ) );

		// The form should be visible always, even if it was submitted (e.g. to perform another action).
		// To bypass the callback validation of HTMLForm, use prepareForm() and displayForm().
		$htmlForm->prepareForm()->displayForm( false );

		if ( $this->file ) {
			$this->hash = $this->file->getSha1();
		} elseif ( $this->filename !== '' ) {
			$out->wrapWikiMsg(
				"<p class='mw-fileduplicatesearch-noresults'>\n$1\n</p>",
				[ 'fileduplicatesearch-noresults', wfEscapeWikiText( $this->filename ) ]
			);
		}

		if ( $this->hash != '' ) {
			# Show a thumbnail of the file
			$img = $this->file;
			if ( $img ) {
				$thumb = $img->transform( [ 'width' => 120, 'height' => 120 ] );
				if ( $thumb ) {
					$out->addModuleStyles( 'mediawiki.special' );
					$out->addHTML( '<div id="mw-fileduplicatesearch-icon">' .
						$thumb->toHtml( [ 'desc-link' => false ] ) . '<br />' .
						$this->msg( 'fileduplicatesearch-info' )
							->numParams( $img->getWidth(), $img->getHeight() )
							->sizeParams( $img->getSize() )
							->params( $img->getMimeType() )->parseAsBlock() .
						'</div>' );
				}
			}

			$dupes = $this->getDupes();
			$numRows = count( $dupes );

			# Show a short summary
			if ( $numRows == 1 ) {
				$out->wrapWikiMsg(
					"<p class='mw-fileduplicatesearch-result-1'>\n$1\n</p>",
					[ 'fileduplicatesearch-result-1', wfEscapeWikiText( $this->filename ) ]
				);
			} elseif ( $numRows ) {
				$out->wrapWikiMsg(
					"<p class='mw-fileduplicatesearch-result-n'>\n$1\n</p>",
					[ 'fileduplicatesearch-result-n', wfEscapeWikiText( $this->filename ),
						$this->getLanguage()->formatNum( $numRows - 1 ) ]
				);
			}

			$this->doBatchLookups( $dupes );
			$this->showList( $dupes );
		}
	}

	/**
	 * @param File[] $list
	 */
	private function doBatchLookups( $list ) {
		$batch = $this->linkBatchFactory->newLinkBatch()->setCaller( __METHOD__ );
		foreach ( $list as $file ) {
			$batch->addObj( $file->getTitle() );
			if ( $file->isLocal() ) {
				$uploader = $file->getUploader( File::FOR_THIS_USER, $this->getAuthority() );
				if ( $uploader ) {
					$batch->addUser( $uploader );
				}
			}
		}

		$batch->execute();
	}

	/**
	 * @param File $result
	 * @return string HTML
	 */
	private function formatResult( $result ) {
		$linkRenderer = $this->getLinkRenderer();
		$nt = $result->getTitle();
		$text = $this->languageConverter->convert( $nt->getText() );
		$plink = $linkRenderer->makeLink(
			$nt,
			$text
		);

		$uploader = $result->getUploader( File::FOR_THIS_USER, $this->getAuthority() );
		if ( $result->isLocal() && $uploader ) {
			$user = Linker::userLink( $uploader->getId(), $uploader->getName() );
			$user .= '<span style="white-space: nowrap;">';
			$user .= Linker::userToolLinks( $uploader->getId(), $uploader->getName() );
			$user .= '</span>';
		} elseif ( $uploader ) {
			$user = htmlspecialchars( $uploader->getName() );
		} else {
			$user = '<span class="history-deleted">'
				. $this->msg( 'rev-deleted-user' )->escaped() . '</span>';
		}

		$time = htmlspecialchars( $this->getLanguage()->userTimeAndDate(
			$result->getTimestamp(), $this->getUser() ) );

		return "$plink . . $user . . $time";
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
		$title = Title::newFromText( $search, NS_FILE );
		if ( !$title || $title->getNamespace() !== NS_FILE ) {
			// No prefix suggestion outside of file namespace
			return [];
		}
		$searchEngine = $this->searchEngineFactory->create();
		$searchEngine->setLimitOffset( $limit, $offset );
		// Autocomplete subpage the same as a normal search, but just for files
		$searchEngine->setNamespaces( [ NS_FILE ] );
		$result = $searchEngine->defaultPrefixSearch( $search );

		return array_map( static function ( Title $t ) {
			// Remove namespace in search suggestion
			return $t->getText();
		}, $result );
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'media';
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialFileDuplicateSearch::class, 'SpecialFileDuplicateSearch' );

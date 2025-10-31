<?php

namespace MediaWiki\Content;

use MediaWiki\MediaWikiServices;
use MediaWiki\Page\WikiPage;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Revision\RevisionRecord;
use SearchEngine;
use SearchIndexField;

/**
 * Content handler for "File" page content
 *
 * TODO: this handler is not used directly now,
 * but instead manually called by WikitextHandler.
 * This should be fixed in the future.
 *
 * @ingroup Content
 */
class FileContentHandler extends WikitextContentHandler {

	/** @inheritDoc */
	public function getFieldsForSearchIndex( SearchEngine $engine ) {
		$fields = [];
		$fields['file_media_type'] =
			$engine->makeSearchFieldMapping( 'file_media_type', SearchIndexField::INDEX_TYPE_KEYWORD );
		$fields['file_media_type']->setFlag( SearchIndexField::FLAG_CASEFOLD );
		$fields['file_mime'] =
			$engine->makeSearchFieldMapping( 'file_mime', SearchIndexField::INDEX_TYPE_SHORT_TEXT );
		$fields['file_mime']->setFlag( SearchIndexField::FLAG_CASEFOLD );
		$fields['file_size'] =
			$engine->makeSearchFieldMapping( 'file_size', SearchIndexField::INDEX_TYPE_INTEGER );
		$fields['file_width'] =
			$engine->makeSearchFieldMapping( 'file_width', SearchIndexField::INDEX_TYPE_INTEGER );
		$fields['file_height'] =
			$engine->makeSearchFieldMapping( 'file_height', SearchIndexField::INDEX_TYPE_INTEGER );
		$fields['file_bits'] =
			$engine->makeSearchFieldMapping( 'file_bits', SearchIndexField::INDEX_TYPE_INTEGER );
		$fields['file_resolution'] =
			$engine->makeSearchFieldMapping( 'file_resolution', SearchIndexField::INDEX_TYPE_INTEGER );
		$fields['file_text'] =
			$engine->makeSearchFieldMapping( 'file_text', SearchIndexField::INDEX_TYPE_TEXT );
		return $fields;
	}

	/** @inheritDoc */
	public function getDataForSearchIndex(
		WikiPage $page,
		ParserOutput $parserOutput,
		SearchEngine $engine,
		?RevisionRecord $revision = null
	) {
		$fields = [];

		$title = $page->getTitle();
		if ( NS_FILE != $title->getNamespace() ) {
			return [];
		}
		$file = MediaWikiServices::getInstance()->getRepoGroup()->getLocalRepo()
			->newFile( $title );
		if ( !$file || !$file->exists() ) {
			return [];
		}

		$handler = $file->getHandler();
		if ( $handler ) {
			$fileText = $handler->getEntireText( $file );
			if ( $fileText !== false ) {
				$fields['file_text'] = $fileText;
			}
		}
		$fields['file_media_type'] = $file->getMediaType();
		$fields['file_mime'] = $file->getMimeType();
		$fields['file_size'] = $file->getSize();
		$fields['file_width'] = $file->getWidth();
		$fields['file_height'] = $file->getHeight();
		$fields['file_bits'] = $file->getBitDepth();
		$fields['file_resolution'] =
			(int)floor( sqrt( $fields['file_width'] * $fields['file_height'] ) );

		return $fields;
	}

}

/** @deprecated class alias since 1.43 */
class_alias( FileContentHandler::class, 'FileContentHandler' );

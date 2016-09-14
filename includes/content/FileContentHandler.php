<?php

/**
 * Content handler for File: files
 */
class FileContentHandler extends WikitextContentHandler  {

	public function getFieldsForSearchIndex( SearchEngine $engine ) {
		$fields['file_media_type'] =
			$engine->makeSearchFieldMapping( 'file_media_type', SearchIndexField::INDEX_TYPE_KEYWORD );
		$fields['file_mime'] =
			$engine->makeSearchFieldMapping( 'file_mime', SearchIndexField::INDEX_TYPE_KEYWORD );
		$fields['file_size'] =
			$engine->makeSearchFieldMapping( 'file_size', SearchIndexField::INDEX_TYPE_INTEGER );
		$fields['file_width'] =
			$engine->makeSearchFieldMapping( 'file_width', SearchIndexField::INDEX_TYPE_INTEGER );
		$fields['file_height'] =
			$engine->makeSearchFieldMapping( 'file_height', SearchIndexField::INDEX_TYPE_INTEGER );
		$fields['file_bits'] =
			$engine->makeSearchFieldMapping( 'file_bits', SearchIndexField::INDEX_TYPE_INTEGER );
		$fields['file_resolution'] =
			$engine->makeSearchFieldMapping( 'file_resolution', SearchIndexField::INDEX_TYPE_NUMBER );
		$fields['file_description'] =
			$engine->makeSearchFieldMapping( 'file_description', SearchIndexField::INDEX_TYPE_TEXT );
		$fields['file_text'] =
			$engine->makeSearchFieldMapping( 'file_text', SearchIndexField::INDEX_TYPE_TEXT );
	}

	public function getDataForSearchIndex( WikiPage $page, ParserOutput $parserOutput,
	                                       SearchEngine $engine ) {
		$fields = [];

		$title = $page->getTitle();
		if ( NS_FILE != $title->getNamespace() ) {
			return [];
		}
		$file = wfLocalFile( $title );
		if ( !$file || !$file->exists() ) {
			return [];
		}

		$handler = $file->getHandler();
		if ( $handler ) {
			$fields['file_text'] = $handler->getEntireText( $file );
		}
		$fields['file_media_type'] = $file->getMediaType();
		$fields['file_mime'] = $file->getMimeType();
		$fields['file_size'] = $file->getSize();
		$fields['file_width'] = $file->getWidth();
		$fields['file_height'] = $file->getHeight();
		$fields['file_bits'] = $file->getBitDepth();
		$fields['file_resolution'] = sqrt($fields['file_width']*$fields['file_height']);
		$fields['file_description'] = $file->getDescriptionText();

		return $fields;
	}
}
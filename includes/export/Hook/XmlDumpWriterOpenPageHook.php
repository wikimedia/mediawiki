<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface XmlDumpWriterOpenPageHook {
	/**
	 * Called at the end of XmlDumpWriter::openPage, to allow
	 * extra metadata to be added.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $obj The XmlDumpWriter object.
	 * @param ?mixed &$out The output string.
	 * @param ?mixed $row The database row for the page.
	 * @param ?mixed $title The title of the page.
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onXmlDumpWriterOpenPage( $obj, &$out, $row, $title );
}

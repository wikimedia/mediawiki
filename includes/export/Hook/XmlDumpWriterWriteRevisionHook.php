<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface XmlDumpWriterWriteRevisionHook {
	/**
	 * Called at the end of a revision in an XML dump, to
	 * add extra metadata.
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $obj The XmlDumpWriter object.
	 * @param ?mixed &$out The text being output.
	 * @param ?mixed $row The database row for the revision being dumped. DEPRECATED, use $rev instead.
	 * @param ?mixed $text The revision text to be dumped. DEPRECATED, use $rev instead.
	 * @param ?mixed $rev The RevisionRecord that is being dumped to XML
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onXmlDumpWriterWriteRevision( $obj, &$out, $row, $text, $rev );
}

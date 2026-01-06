<?php

namespace MediaWiki\Export\Hook;

use MediaWiki\Export\XmlDumpWriter;
use MediaWiki\Title\Title;
use stdClass;

/**
 * This is a hook handler interface, see docs/Hooks.md.
 * Use the hook name "XmlDumpWriterOpenPage" to register handlers implementing this interface.
 *
 * @stable to implement
 * @ingroup Hooks
 */
interface XmlDumpWriterOpenPageHook {
	/**
	 * This hook is called at the end of XmlDumpWriter::openPage, to allow
	 * extra metadata to be added.
	 *
	 * @since 1.35
	 *
	 * @param XmlDumpWriter $obj
	 * @param string &$out Output string
	 * @param stdClass $row Database row for the page
	 * @param Title $title Title of the page
	 * @return bool|void True or no return value to continue or false to abort
	 */
	public function onXmlDumpWriterOpenPage( $obj, &$out, $row, $title );
}

/** @deprecated class alias since 1.46 */
class_alias( XmlDumpWriterOpenPageHook::class, 'MediaWiki\\Hook\\XmlDumpWriterOpenPageHook' );

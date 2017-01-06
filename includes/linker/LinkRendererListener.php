<?php

namespace MediaWiki\Linker;

/**
 * LinkRendererListener
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
interface LinkRendererListener {

	/**
	 * @param LinkRenderer $renderer
	 * @param LinkTarget $target
	 * @param bool $isKnown
	 * @param string &$text
	 * @param array &$extraAttribs
	 * @param array &$query
	 *
	 * @return bool
	 */
	public function onHtmlPageLinkRendererBegin(
		LinkRenderer $renderer,
		LinkTarget $target,
		$isKnown,
		&$text,
		array &$extraAttribs,
		array &$query,
		&$ret
	);

	/**
	 * @param LinkRenderer $renderer
	 * @param LinkTarget $target
	 * @param bool $isKnown
	 * @param string &$text
	 * @param array &$attribs
	 * @param string &$ret
	 *
	 * @return bool
	 */
	public function onHtmlPageLinkRendererEnd(
		LinkRenderer $renderer,
		LinkTarget $target,
		$isKnown,
		&$text,
		array &$attribs,
		&$ret
	);

}

<?php

use MediaWiki\Parser\Parser;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\Parsoid\ParsoidParser;
use MediaWiki\Title\Title;

/**
 * Parse some wikitext.
 *
 * Wikitext can be given by stdin or using a file. The wikitext will be parsed
 * using 'CLIParser' as a title. This can be overridden with --title option.
 *
 * Example1:
 * @code
 * $ php parse.php --title foo
 * ''[[foo]]''^D
 * <p><i><strong class="selflink">foo</strong></i>
 * </p>
 * @endcode
 *
 * Example2:
 * @code
 * $ echo "'''bold'''" > /tmp/foo.txt
 * $ php parse.php /tmp/foo.txt
 * <p><b>bold</b>
 * </p>$
 * @endcode
 *
 * Example3:
 * @code
 * $ cat /tmp/foo | php parse.php
 * <p><b>bold</b>
 * </p>$
 * @endcode
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
 * @ingroup Maintenance
 * @author Antoine Musso <hashar at free dot fr>
 * @license GPL-2.0-or-later
 */

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

/**
 * Maintenance script to parse some wikitext.
 *
 * @ingroup Maintenance
 */
class CLIParser extends Maintenance {
	/** @var Parser|ParsoidParser */
	protected $parser;

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Parse a given wikitext' );
		$this->addOption(
			'title',
			'Title name for the given wikitext (Default: \'CLIParser\')',
			false,
			true
		);
		$this->addArg( 'file', 'File containing wikitext (Default: stdin)', false );
		$this->addOption( 'parsoid', 'Whether to use Parsoid', false, false, 'p' );
	}

	public function execute() {
		$this->initParser();
		print $this->render( $this->Wikitext() );
	}

	/**
	 * @param string $wikitext Wikitext to get rendered
	 * @return string HTML Rendering
	 */
	public function render( $wikitext ) {
		$options = ParserOptions::newFromAnon();
		$options->setOption( 'enableLimitReport', false );
		$po = $this->parser->parse(
			$wikitext,
			$this->getTitle(),
			$options
		);
		// TODO T371008 consider if using the Content framework makes sense instead of creating the pipeline
		$pipeline = $this->getServiceContainer()->getDefaultOutputPipeline();
		return $pipeline->run( $po, $options, [ 'wrapperDivClass' => '' ] )->getContentHolderText();
	}

	/**
	 * Get wikitext from a the file passed as argument or STDIN
	 * @return string Wikitext
	 */
	protected function Wikitext() {
		$php_stdin = 'php://stdin';
		$input_file = $this->getArg( 0, $php_stdin );

		if ( $input_file === $php_stdin && !$this->mQuiet ) {
			$ctrl = wfIsWindows() ? 'CTRL+Z' : 'CTRL+D';
			$this->error( basename( __FILE__ )
				. ": warning: reading wikitext from STDIN. Press $ctrl to parse.\n" );
		}

		return file_get_contents( $input_file );
	}

	protected function initParser() {
		$services = $this->getServiceContainer();
		if ( $this->hasOption( 'parsoid' ) ) {
			$this->parser = $services->getParsoidParserFactory()->create();
		} else {
			$this->parser = $services->getParserFactory()->create();
		}
	}

	/**
	 * Title object to use for CLI parsing.
	 * Default title is 'CLIParser', it can be overridden with the option
	 * --title <Your:Title>
	 *
	 * @return Title
	 */
	protected function getTitle() {
		$title = $this->getOption( 'title' ) ?: 'CLIParser';

		return Title::newFromText( $title );
	}
}

// @codeCoverageIgnoreStart
$maintClass = CLIParser::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd

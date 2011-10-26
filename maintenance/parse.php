<?php
/**
 * CLI script to easily parse some wikitext.
 * Wikitext can be given by stdin or using a file. The wikitext will be parsed
 * using 'CLIParser' as a title. This can be overriden with --title option.
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
 * $ echo "'''bold'''" > /tmp/foo
 * $ php parse.php --file /tmp/foo
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
 * @inGroup Maintenance
 * @author Antoine Musso <hashar at free dot fr>
 * @license GNU General Public License 2.0 or later
 */
require_once( dirname(__FILE__) . '/Maintenance.php' );

class CLIParser extends Maintenance {
	protected $parser;

	public function __construct() {
		$this->mDescription = "Parse a given wikitext";
		$this->addOption( 'title', 'Title name for the given wikitext (Default: \'CLIParser\')', false, true );
		$this->addOption( 'file', 'File containing wikitext (Default: stdin)', false, true );
		parent::__construct();
	}

	public function execute() {
		$this->initParser();
		print $this->render( $this->WikiText() );
	}

	/**
	 * @param string $wikitext Wikitext to get rendered
	 * @return string HTML Rendering
	 */
	public function render( $wikitext ) {
		return $this->parse( $wikitext )->getText();
	}

	/**
	 * Get wikitext from --file or from STDIN
	 * @return string Wikitext
	 */
	protected function Wikitext() {
		return file_get_contents(
			$this->getOption( 'file', 'php://stdin' )
		);
	}

	protected function initParser() {
		global $wgParserConf;
		$parserClass = $wgParserConf['class'];
		$this->parser = new $parserClass();
	}

	/**
	 * Title object to use for CLI parsing.
	 * Default title is 'CLIParser', it can be overriden with the option
	 * --title <Your:Title>
	 *
	 * @return Title object
	 */
	protected function getTitle( ) {
		$title =
			$this->getOption( 'title' )
			? $this->getOption( 'title' )
			: 'CLIParser' ;
		return Title::newFromText( $title );
	}

	/**
	 * @param string $text Wikitext to parse
	 * @return ParserOutput
	 */
	protected function parse( $wikitext ) {
		return $this->parser->parse(
			$wikitext
			, $this->getTitle()
			, new ParserOptions()
		);
	}
}

$maintClass = "CLIParser";
require_once( RUN_MAINTENANCE_IF_MAIN );

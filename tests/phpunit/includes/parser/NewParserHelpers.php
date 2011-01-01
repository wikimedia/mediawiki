<?php

class ParserTestFileIterator implements Iterator {

	protected $file;
	protected $fh;
	protected $parserTest; /* An instance of ParserTest (parserTests.php) or MediaWikiParserTest (phpunit) */
	protected $index = 0;
	protected $test;
	protected $lineNum;
	protected $eof;

	function __construct( $file, $parserTest ) {
		global $IP;

		$this->file = $file;
		$this->fh = fopen( $this->file, "rt" );

		if ( !$this->fh ) {
			wfDie( "Couldn't open file '$file'\n" );
		}

		$this->parserTest = $parserTest;
		//$this->parserTest->showRunFile( wfRelativePath( $this->file, $IP ) );

		$this->lineNum = $this->index = 0;
	}

	function rewind() {
		if ( fseek( $this->fh, 0 ) ) {
			wfDie( "Couldn't fseek to the start of '$this->file'\n" );
		}

		$this->index = -1;
		$this->lineNum = 0;
		$this->eof = false;
		$this->next();

		return true;
	}

	function current() {
		return $this->test;
	}

	function key() {
		return $this->index;
	}

	function next() {
		if ( $this->readNextTest() ) {
			$this->index++;
			return true;
		} else {
			$this->eof = true;
		}
	}

	function valid() {
		return $this->eof != true;
	}

	function readNextTest() {
		$data = array();
		$section = null;

		while ( false !== ( $line = fgets( $this->fh ) ) ) {
			$this->lineNum++;
			$matches = array();

			if ( preg_match( '/^!!\s*(\w+)/', $line, $matches ) ) {
				$section = strtolower( $matches[1] );

				if ( $section == 'endarticle' ) {
					if ( !isset( $data['text'] ) ) {
						wfDie( "'endarticle' without 'text' at line {$this->lineNum} of $this->file\n" );
					}

					if ( !isset( $data['article'] ) ) {
						wfDie( "'endarticle' without 'article' at line {$this->lineNum} of $this->file\n" );
					}

					$this->parserTest->addArticle( $this->parserTest->removeEndingNewline( $data['article'] ), $data['text'], $this->lineNum );
					
					
					$data = array();
					$section = null;

					continue;
				}

				if ( $section == 'endhooks' ) {
					if ( !isset( $data['hooks'] ) ) {
						wfDie( "'endhooks' without 'hooks' at line {$this->lineNum} of $this->file\n" );
					}

					foreach ( explode( "\n", $data['hooks'] ) as $line ) {
						$line = trim( $line );

						if ( $line ) {
							if ( !$this->parserTest->requireHook( $line ) ) {
								return false;
							}
						}
					}

					$data = array();
					$section = null;

					continue;
				}

				if ( $section == 'endfunctionhooks' ) {
					if ( !isset( $data['functionhooks'] ) ) {
						wfDie( "'endfunctionhooks' without 'functionhooks' at line {$this->lineNum} of $this->file\n" );
					}

					foreach ( explode( "\n", $data['functionhooks'] ) as $line ) {
						$line = trim( $line );

						if ( $line ) {
							if ( !$this->parserTest->requireFunctionHook( $line ) ) {
								return false;
							}
						}
					}

					$data = array();
					$section = null;

					continue;
				}

				if ( $section == 'end' ) {
					if ( !isset( $data['test'] ) ) {
						wfDie( "'end' without 'test' at line {$this->lineNum} of $this->file\n" );
					}

					if ( !isset( $data['input'] ) ) {
						wfDie( "'end' without 'input' at line {$this->lineNum} of $this->file\n" );
					}

					if ( !isset( $data['result'] ) ) {
						wfDie( "'end' without 'result' at line {$this->lineNum} of $this->file\n" );
					}

					if ( !isset( $data['options'] ) ) {
						$data['options'] = '';
					}

					if ( !isset( $data['config'] ) )
						$data['config'] = '';

					if ( ( preg_match( '/\\bdisabled\\b/i', $data['options'] ) && !$this->parserTest->runDisabled )
							 || !preg_match( "/" . $this->parserTest->regex . "/i", $data['test'] ) ) {
						# disabled test
						$data = array();
						$section = null;

						continue;
					}

					global $wgUseTeX;

					if ( preg_match( '/\\bmath\\b/i', $data['options'] ) && !$wgUseTeX ) {
						# don't run math tests if $wgUseTeX is set to false in LocalSettings
						$data = array();
						$section = null;

						continue;
					}

					$this->test = array(
						'test' => $this->parserTest->removeEndingNewline( $data['test'] ),
						'input' => $this->parserTest->removeEndingNewline( $data['input'] ),
						'result' => $this->parserTest->removeEndingNewline( $data['result'] ),
						'options' => $this->parserTest->removeEndingNewline( $data['options'] ),
						'config' => $this->parserTest->removeEndingNewline( $data['config'] ) );

					return true;
				}

				if ( isset ( $data[$section] ) ) {
					wfDie( "duplicate section '$section' at line {$this->lineNum} of $this->file\n" );
				}

				$data[$section] = '';

				continue;
			}

			if ( $section ) {
				$data[$section] .= $line;
			}
		}

		return false;
	}
}
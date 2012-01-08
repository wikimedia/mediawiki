<?php

class TestFileIterator implements Iterator {
	private $file;
	private $fh;
	private $parserTest; /* An instance of ParserTest (parserTests.php) or MediaWikiParserTest (phpunit) */
	private $index = 0;
	private $test;
	private $section = null; /** String|null: current test section being analyzed */
	private $sectionData = array();
	private $lineNum;
	private $eof;

	function __construct( $file, $parserTest ) {
		global $IP;

		$this->file = $file;
		$this->fh = fopen( $this->file, "rt" );

		if ( !$this->fh ) {
			throw new MWException( "Couldn't open file '$file'\n" );
		}

		$this->parserTest = $parserTest;
		$this->parserTest->showRunFile( wfRelativePath( $this->file, $IP ) );

		$this->lineNum = $this->index = 0;
	}

	function rewind() {
		if ( fseek( $this->fh, 0 ) ) {
			throw new MWException( "Couldn't fseek to the start of '$this->file'\n" );
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
		$this->clearSection();

		# Create a fake parser tests which never run anything unless
		# asked to do so. This will avoid running hooks for a disabled test
		$delayedParserTest = new DelayedParserTest();

		while ( false !== ( $line = fgets( $this->fh ) ) ) {
			$this->lineNum++;
			$matches = array();

			if ( preg_match( '/^!!\s*(\w+)/', $line, $matches ) ) {
				$this->section = strtolower( $matches[1] );

				if ( $this->section == 'endarticle' ) {
					$this->checkSection( 'text'    );
					$this->checkSection( 'article' );

					$this->parserTest->addArticle( self::chomp( $this->sectionData['article'] ), $this->sectionData['text'], $this->lineNum );

					$this->clearSection();

					continue;
				}

				if ( $this->section == 'endhooks' ) {
					$this->checkSection( 'hooks' );

					foreach ( explode( "\n", $this->sectionData['hooks'] ) as $line ) {
						$line = trim( $line );

						if ( $line ) {
							$delayedParserTest->requireHook( $line );
						}
					}

					$this->clearSection();

					continue;
				}

				if ( $this->section == 'endfunctionhooks' ) {
					$this->checkSection( 'functionhooks' );

					foreach ( explode( "\n", $this->sectionData['functionhooks'] ) as $line ) {
						$line = trim( $line );

						if ( $line ) {
							$delayedParserTest->requireFunctionHook( $line );
						}
					}

					$this->clearSection();

					continue;
				}

				if ( $this->section == 'end' ) {
					$this->checkSection( 'test'   );
					$this->checkSection( 'input'  );
					$this->checkSection( 'result' );

					if ( !isset( $this->sectionData['options'] ) ) {
						$this->sectionData['options'] = '';
					}

					if ( !isset( $this->sectionData['config'] ) ) {
						$this->sectionData['config'] = '';
					}

					if ( ( ( preg_match( '/\\bdisabled\\b/i', $this->sectionData['options'] ) && !$this->parserTest->runDisabled )
							 || !preg_match( "/" . $this->parserTest->regex . "/i", $this->sectionData['test'] ) )  ) {
						# disabled test
						$this->clearSection();

						# Forget any pending hooks call since test is disabled
						$delayedParserTest->reset();

						continue;
					}

					# We are really going to run the test, run pending hooks and hooks function
					wfDebug( __METHOD__ . " unleashing delayed test for: {$this->sectionData['test']}" );
					$hooksResult = $delayedParserTest->unleash( $this->parserTest );
					if( !$hooksResult ) {
						# Some hook reported an issue. Abort.
						return false;
					}

					$this->test = array(
						'test'    => self::chomp( $this->sectionData['test']    ),
						'input'   => self::chomp( $this->sectionData['input']   ),
						'result'  => self::chomp( $this->sectionData['result']  ),
						'options' => self::chomp( $this->sectionData['options'] ),
						'config'  => self::chomp( $this->sectionData['config']  ),
					);

					return true;
				}

				if ( isset ( $this->sectionData[$this->section] ) ) {
					throw new MWException( "duplicate section '$section' at line {$this->lineNum} of $this->file\n" );
				}

				$this->sectionData[$this->section] = '';

				continue;
			}

			if ( $this->section ) {
				$this->sectionData[$this->section] .= $line;
			}
		}

		return false;
	}


	/**
	 * Clear section name and its data
	 */
	private function clearSection() {
		$this->sectionData = array();
		$this->section = null;
		
	}

	/**
	 * Remove last character if it is a newline
	 * @group utility
	 */
	public static function chomp( $s ) {
		if ( substr( $s, -1 ) === "\n" ) {
			return substr( $s, 0, -1 );
		}
		else {
			return $s;
		}
	}

	/**
	 * Verify the current section data has some value for the given token
	 * name (first parameter).
	 * Throw an exception if it is not set, referencing current section
	 * and adding the current file name and line number
	 *
	 * @param $token String: expected token that should have been mentionned before closing this section 
	 */
	private function checkSection( $token ) {
		if( is_null( $this->section ) ) {
			throw new MWException( __METHOD__ . " can not verify a null section!\n" );
		}

		if( !isset($this->sectionData[$token]) ) {
			throw new MWException( sprintf(
				"'%s' without '%s' at line %s of %s\n",
				$this->section,
				$token,
				$this->lineNum,
				$this->file
			));
		}
		return true;
	}
}

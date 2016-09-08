<?php
/**
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
 * @ingroup Testing
 */

class TestFileReader implements Iterator {
	private $file;
	private $fh;
	/**
	 * @var ParserTestRunner|ParserTestTopLevelSuite An instance of ParserTestRunner
	 * (parserTests.php) or ParserTestTopLevelSuite (phpunit)
	 */
	private $parserTest;
	private $index = 0;
	private $test;
	private $section = null;
	/** String|null: current test section being analyzed */
	private $sectionData = [];
	private $lineNum;
	private $eof;
	# Create a fake parser tests which never run anything unless
	# asked to do so. This will avoid running hooks for a disabled test
	private $delayedParserTest;
	private $nextSubTest = 0;

	function __construct( $file, $parserTest ) {
		$this->file = $file;
		$this->fh = fopen( $this->file, "rt" );

		if ( !$this->fh ) {
			throw new MWException( "Couldn't open file '$file'\n" );
		}

		$this->parserTest = $parserTest;
		$this->delayedParserTest = new DelayedParserTest();

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

	function setupCurrentTest() {
		// "input" and "result" are old section names allowed
		// for backwards-compatibility.
		$input = $this->checkSection( [ 'wikitext', 'input' ], false );
		$result = $this->checkSection( [ 'html/php', 'html/*', 'html', 'result' ], false );
		// some tests have "with tidy" and "without tidy" variants
		$tidy = $this->checkSection( [ 'html/php+tidy', 'html+tidy' ], false );
		if ( $tidy != false ) {
			if ( $this->nextSubTest == 0 ) {
				if ( $result != false ) {
					$this->nextSubTest = 1; // rerun non-tidy variant later
				}
				$result = $tidy;
			} else {
				$this->nextSubTest = 0; // go on to next test after this
				$tidy = false;
			}
		}

		if ( !isset( $this->sectionData['options'] ) ) {
			$this->sectionData['options'] = '';
		}

		if ( !isset( $this->sectionData['config'] ) ) {
			$this->sectionData['config'] = '';
		}

		$isDisabled = preg_match( '/\\bdisabled\\b/i', $this->sectionData['options'] ) &&
			!$this->parserTest->runDisabled;
		$isParsoidOnly = preg_match( '/\\bparsoid\\b/i', $this->sectionData['options'] ) &&
			$result == 'html' &&
			!$this->parserTest->runParsoid;
		$isFiltered = !preg_match( "/" . $this->parserTest->regex . "/i", $this->sectionData['test'] );
		if ( $input == false || $result == false || $isDisabled || $isParsoidOnly || $isFiltered ) {
			# disabled test
			return false;
		}

		# We are really going to run the test, run pending hooks and hooks function
		wfDebug( __METHOD__ . " unleashing delayed test for: {$this->sectionData['test']}" );
		$hooksResult = $this->delayedParserTest->unleash( $this->parserTest );
		if ( !$hooksResult ) {
			# Some hook reported an issue. Abort.
			throw new MWException( "Problem running requested parser hook from the test file" );
		}

		$this->test = [
			'test' => ParserTestRunner::chomp( $this->sectionData['test'] ),
			'subtest' => $this->nextSubTest,
			'input' => ParserTestRunner::chomp( $this->sectionData[$input] ),
			'result' => ParserTestRunner::chomp( $this->sectionData[$result] ),
			'options' => ParserTestRunner::chomp( $this->sectionData['options'] ),
			'config' => ParserTestRunner::chomp( $this->sectionData['config'] ),
		];
		if ( $tidy != false ) {
			$this->test['options'] .= " tidy";
		}
		return true;
	}

	function readNextTest() {
		# Run additional subtests of previous test
		while ( $this->nextSubTest > 0 ) {
			if ( $this->setupCurrentTest() ) {
				return true;
			}
		}

		$this->clearSection();
		# Reset hooks for the delayed test object
		$this->delayedParserTest->reset();

		while ( false !== ( $line = fgets( $this->fh ) ) ) {
			$this->lineNum++;
			$matches = [];

			if ( preg_match( '/^!!\s*(\S+)/', $line, $matches ) ) {
				$this->section = strtolower( $matches[1] );

				if ( $this->section == 'endarticle' ) {
					$this->checkSection( 'text' );
					$this->checkSection( 'article' );

					$this->parserTest->addArticle(
						ParserTestRunner::chomp( $this->sectionData['article'] ),
						$this->sectionData['text'], $this->lineNum );

					$this->clearSection();

					continue;
				}

				if ( $this->section == 'endhooks' ) {
					$this->checkSection( 'hooks' );

					foreach ( explode( "\n", $this->sectionData['hooks'] ) as $line ) {
						$line = trim( $line );

						if ( $line ) {
							$this->delayedParserTest->requireHook( $line );
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
							$this->delayedParserTest->requireFunctionHook( $line );
						}
					}

					$this->clearSection();

					continue;
				}

				if ( $this->section == 'endtransparenthooks' ) {
					$this->checkSection( 'transparenthooks' );

					foreach ( explode( "\n", $this->sectionData['transparenthooks'] ) as $line ) {
						$line = trim( $line );

						if ( $line ) {
							$this->delayedParserTest->requireTransparentHook( $line );
						}
					}

					$this->clearSection();

					continue;
				}

				if ( $this->section == 'end' ) {
					$this->checkSection( 'test' );
					do {
						if ( $this->setupCurrentTest() ) {
							return true;
						}
					} while ( $this->nextSubTest > 0 );
					# go on to next test (since this was disabled)
					$this->clearSection();
					$this->delayedParserTest->reset();
					continue;
				}

				if ( isset( $this->sectionData[$this->section] ) ) {
					throw new MWException( "duplicate section '$this->section' "
						. "at line {$this->lineNum} of $this->file\n" );
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
		$this->sectionData = [];
		$this->section = null;

	}

	/**
	 * Verify the current section data has some value for the given token
	 * name(s) (first parameter).
	 * Throw an exception if it is not set, referencing current section
	 * and adding the current file name and line number
	 *
	 * @param string|array $tokens Expected token(s) that should have been
	 * mentioned before closing this section
	 * @param bool $fatal True iff an exception should be thrown if
	 * the section is not found.
	 * @return bool|string
	 * @throws MWException
	 */
	private function checkSection( $tokens, $fatal = true ) {
		if ( is_null( $this->section ) ) {
			throw new MWException( __METHOD__ . " can not verify a null section!\n" );
		}
		if ( !is_array( $tokens ) ) {
			$tokens = [ $tokens ];
		}
		if ( count( $tokens ) == 0 ) {
			throw new MWException( __METHOD__ . " can not verify zero sections!\n" );
		}

		$data = $this->sectionData;
		$tokens = array_filter( $tokens, function ( $token ) use ( $data ) {
			return isset( $data[$token] );
		} );

		if ( count( $tokens ) == 0 ) {
			if ( !$fatal ) {
				return false;
			}
			throw new MWException( sprintf(
				"'%s' without '%s' at line %s of %s\n",
				$this->section,
				implode( ',', $tokens ),
				$this->lineNum,
				$this->file
			) );
		}
		if ( count( $tokens ) > 1 ) {
			throw new MWException( sprintf(
				"'%s' with unexpected tokens '%s' at line %s of %s\n",
				$this->section,
				implode( ',', $tokens ),
				$this->lineNum,
				$this->file
			) );
		}

		return array_values( $tokens )[0];
	}
}


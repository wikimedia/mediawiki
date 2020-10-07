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

class TestFileReader {
	private $file;
	private $fh;
	private $section = null;
	/** String|null: current test section being analyzed */
	private $sectionData = [];
	private $sectionLineNum = [];
	private $lineNum = 0;
	private $runDisabled;
	private $regex;
	private $format = 1;

	private $articles = [];
	private $requirements = [];
	private $tests = [];

	public static function read( $file, array $options = [] ) {
		$reader = new self( $file, $options );
		$reader->execute();

		$requirements = [];
		foreach ( $reader->requirements as $type => $reqsOfType ) {
			foreach ( $reqsOfType as $name => $unused ) {
				$requirements[] = [
					'type' => $type,
					'name' => $name
				];
			}
		}

		return [
			'requirements' => $requirements,
			'tests' => $reader->tests,
			'articles' => $reader->articles
		];
	}

	private function __construct( $file, $options ) {
		$this->file = $file;
		$this->fh = fopen( $this->file, "rt" );

		if ( !$this->fh ) {
			throw new MWException( "Couldn't open file '$file'\n" );
		}

		$options = $options + [
			'runDisabled' => false,
			'regex' => '//',
		];
		$this->runDisabled = $options['runDisabled'];
		$this->regex = $options['regex'];
	}

	private function addCurrentTest() {
		// "input" and "result" are old section names allowed
		// for backwards-compatibility.
		$input = $this->checkSection(
			[ 'wikitext', 'input' ], false );
		$output = $this->checkSection(
			[ 'html/php', 'html/*', 'html', 'result' ], false );
		$tidySection = $this->checkSection(
			[ 'html/php+tidy', 'html+tidy' ], false );
		$nonTidySection = $this->checkSection(
			[ 'html/php+untidy', 'html+untidy' ], false );
		if ( $this->format < 2 ) {
			wfDeprecatedMsg(
				"The parserTest v1 file format was deprecated in MediaWiki 1.35 " .
				"(used in {$this->file})", '1.35', false, false );
			if ( $nonTidySection === false ) {
				// untidy by default
				$nonTidySection = $output;
			}
		} else {
			if ( $this->checkSection( [ 'input' ], false ) ) {
				wfDeprecatedMsg(
					"The input section in parserTest files was deprecated in MediaWiki 1.35 " .
					"(used in {$this->file})",
					'1.35', false, false );
			}
			if ( $this->checkSection( [ 'result' ], false ) ) {
				wfDeprecatedMsg(
					"The result section in parserTest files was deprecated in MediaWiki 1.35 " .
					"(used in {$this->file})",
					'1.35', false, false );
			}
			if ( $output && $tidySection ) {
				wfDeprecatedMsg(
					'The untidy section of parserTest files was deprecated in MediaWiki 1.35, ' .
					"it should be renamed at {$this->file}:{$this->sectionLineNum['test']}",
					'1.35', false, false
				);
			}
			if ( $tidySection === false ) {
				// tidy by default
				$tidySection = $output;
			}
			if ( $nonTidySection && !$tidySection ) {
				// Tests with a "without tidy" section but no "with tidy" section
				// are deprecated!
				wfDeprecatedMsg(
					'Parser tests with a "without tidy" section but no "with tidy" ' .
					 ' section were deprecated in MediaWiki 1.35. Found at ' .
					"{$this->file}:{$this->sectionLineNum['test']}",
					'1.35', false, false );
			}
		}

		// Remove trailing newline
		$data = array_map( 'ParserTestRunner::chomp', $this->sectionData );

		// Apply defaults
		$data += [
			'options' => '',
			'config' => ''
		];

		if ( $input === false ) {
			throw new MWException( "Test at {$this->file}:{$this->sectionLineNum['test']} " .
				"lacks input section" );
		}
		if ( preg_match( '/\\bdisabled\\b/i', $data['options'] ) &&	!$this->runDisabled ) {
			// Disabled
			return;
		}

		if ( $tidySection === false && $nonTidySection === false ) {
			if ( isset( $data['html/parsoid'] ) || isset( $data['wikitext/edited'] ) ) {
				// Parsoid only
				return;
			} else {
				throw new MWException( "Test at {$this->file}:{$this->sectionLineNum['test']} " .
					"lacks result section" );
			}
		}

		if ( !preg_match( $this->regex, $data['test'] ) ) {
			// Filtered test
			return;
		}

		$commonInfo = [
			'test' => $data['test'],
			'desc' => $data['test'],
			'input' => $data[$input],
			'options' => $data['options'],
			'config' => $data['config'],
			'line' => $this->sectionLineNum['test'],
			'file' => $this->file
		];

		if ( $nonTidySection !== false ) {
			if ( $tidySection !== false ) {
				// Add tidy subtest
				$this->tests[] = [
					'desc' => $data['test'] . ' (with tidy)',
					'result' => $data[$tidySection],
					'resultSection' => $tidySection,
					'options' => $data['options'] . ' tidy',
					'isSubtest' => true,
				] + $commonInfo;
			} else {
				// We can no longer run the non-tidy test, and we don't have
				// a tidy alternative.
				wfDeprecatedMsg( "Skipping non-tidy test {$data['test']} " .
					"since it was removed in MediaWiki 1.35, and there is no tidy subtest",
					'1.35', false, false );
			}
		} elseif ( $tidySection !== false ) {
			// No need to override desc when there is no subtest
			$this->tests[] = [
				'result' => $data[$tidySection],
				'resultSection' => $tidySection,
				'options' => $data['options'] . ' tidy'
			] + $commonInfo;
		} else {
			throw new MWException( "Test at {$this->file}:{$this->sectionLineNum['test']} " .
				"lacks result section" );
		}
	}

	private function execute() {
		while ( ( $line = fgets( $this->fh ) ) !== false ) {
			$this->lineNum++;
			$matches = [];

			// Support test file format versioning.
			if ( $this->lineNum === 1 &&
				 preg_match( '/^!!\s*VERSION\s+(\d+)/i', $line, $matches ) ) {
				$this->format = intval( $matches[1] );
				continue;
			}

			if ( preg_match( '/^!!\s*(\S+)/', $line, $matches ) ) {
				$this->section = strtolower( $matches[1] );

				if ( $this->section == 'endarticle' ) {
					$this->checkSection( 'text' );
					$this->checkSection( 'article' );

					$this->addArticle(
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
							$this->addRequirement( 'hook', $line );
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
							$this->addRequirement( 'functionHook', $line );
						}
					}

					$this->clearSection();

					continue;
				}

				if ( $this->section == 'end' ) {
					$this->checkSection( 'test' );
					$this->addCurrentTest();
					$this->clearSection();
					continue;
				}

				if ( isset( $this->sectionData[$this->section] ) ) {
					throw new MWException( "duplicate section '$this->section' "
						. "at line {$this->lineNum} of $this->file\n" );
				}

				$this->sectionLineNum[$this->section] = $this->lineNum;
				$this->sectionData[$this->section] = '';

				continue;
			}

			if ( $this->section ) {
				$this->sectionData[$this->section] .= $line;
			}
		}
	}

	/**
	 * Clear section name and its data
	 */
	private function clearSection() {
		$this->sectionLineNum = [];
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
		if ( $this->section === null ) {
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

	private function addArticle( $name, $text, $line ) {
		$this->articles[] = [
			'name' => $name,
			'text' => $text,
			'line' => $line,
			'file' => $this->file
		];
	}

	private function addRequirement( $type, $name ) {
		$this->requirements[$type][$name] = true;
	}
}

<?php

class TestFileEditor {
	/** @var string[] */
	private $lines;
	/** @var int */
	private $numLines;
	/** @var array<string,true> */
	private $deletions;
	/** @var array<string,array<string,array>> */
	private $changes;
	/** @var int */
	private $pos = 0;
	/** @var callable|false */
	private $warningCallback;
	/** @var string */
	private $result = '';

	public static function edit( $text, array $deletions, array $changes, $warningCallback = null ) {
		$editor = new self( $text, $deletions, $changes, $warningCallback );
		$editor->execute();
		return $editor->result;
	}

	private function __construct( string $text, array $deletions, array $changes, callable $warningCallback ) {
		$this->lines = explode( "\n", $text );
		$this->numLines = count( $this->lines );
		$this->deletions = array_fill_keys( $deletions, true );
		$this->changes = $changes;
		$this->warningCallback = $warningCallback;
	}

	private function execute() {
		while ( $this->pos < $this->numLines ) {
			$line = $this->lines[$this->pos];
			switch ( $this->getHeading( $line ) ) {
				case 'test':
					$this->parseTest();
					break;
				case 'hooks':
				case 'functionhooks':
					$this->parseHooks();
					break;
				default:
					if ( $this->pos < $this->numLines - 1 ) {
						$line .= "\n";
					}
					$this->emitComment( $line );
					$this->pos++;
			}
		}
		foreach ( $this->deletions as $deletion => $unused ) {
			$this->warning( "Could not find test \"$deletion\" to delete it" );
		}
		foreach ( $this->changes as $test => $sectionChanges ) {
			foreach ( $sectionChanges as $section => $change ) {
				$this->warning( "Could not find section \"$section\" in test \"$test\" " .
					"to {$change['op']} it" );
			}
		}
	}

	private function warning( string $text ) {
		$cb = $this->warningCallback;
		if ( $cb ) {
			$cb( $text );
		}
	}

	/** @return string|false */
	private function getHeading( string $line ) {
		if ( preg_match( '/^!!\s*(\S+)/', $line, $m ) ) {
			return $m[1];
		} else {
			return false;
		}
	}

	private function parseTest() {
		$test = [];
		$line = $this->lines[$this->pos++];
		$heading = $this->getHeading( $line );
		$section = [
			'name' => $heading,
			'headingLine' => $line,
			'contents' => ''
		];

		while ( $this->pos < $this->numLines ) {
			$line = $this->lines[$this->pos++];
			$nextHeading = $this->getHeading( $line );
			if ( $nextHeading === 'end' ) {
				$test[] = $section;

				// Add trailing line breaks to the "end" section, to allow for neat deletions
				$trail = '';
				while ( $this->lines[$this->pos] === '' && $this->pos < $this->numLines - 1 ) {
					$trail .= "\n";
					$this->pos++;
				}

				$test[] = [
					'name' => 'end',
					'headingLine' => $line,
					'contents' => $trail
				];
				$this->emitTest( $test );
				return;
			} elseif ( $nextHeading !== false ) {
				$test[] = $section;
				$heading = $nextHeading;
				$section = [
					'name' => $heading,
					'headingLine' => $line,
					'contents' => ''
				];
			} else {
				$section['contents'] .= "$line\n";
			}
		}

		throw new OutOfBoundsException( 'Unexpected end of file' );
	}

	private function parseHooks() {
		$line = $this->lines[$this->pos++];
		$heading = $this->getHeading( $line );
		$expectedEnd = 'end' . $heading;
		$contents = "$line\n";

		do {
			$line = $this->lines[$this->pos++];
			$nextHeading = $this->getHeading( $line );
			$contents .= "$line\n";
		} while ( $this->pos < $this->numLines && $nextHeading !== $expectedEnd );

		if ( $nextHeading !== $expectedEnd ) {
			throw new UnexpectedValueException( 'Unexpected end of file' );
		}
		$this->emitHooks( $heading, $contents );
	}

	protected function emitComment( $contents ) {
		$this->result .= $contents;
	}

	protected function emitTest( $test ) {
		$testName = false;
		foreach ( $test as $section ) {
			if ( $section['name'] === 'test' ) {
				$testName = rtrim( $section['contents'], "\n" );
			}
		}
		if ( isset( $this->deletions[$testName] ) ) {
			// Acknowledge deletion
			unset( $this->deletions[$testName] );
			return;
		}
		if ( isset( $this->changes[$testName] ) ) {
			$changes =& $this->changes[$testName];
			foreach ( $test as $i => $section ) {
				$sectionName = $section['name'];
				if ( isset( $changes[$sectionName] ) ) {
					$change = $changes[$sectionName];
					switch ( $change['op'] ) {
						case 'rename':
							$test[$i]['name'] = $change['value'];
							$test[$i]['headingLine'] = "!! {$change['value']}";
							break;
						case 'update':
							$test[$i]['contents'] = $change['value'];
							break;
						case 'delete':
							$test[$i]['deleted'] = true;
							break;
						default:
							throw new UnexpectedValueException( "Unknown op: {$change['op']}" );
					}
					// Acknowledge
					// Note that we use the old section name for the rename op
					unset( $changes[$sectionName] );
				}
			}
		}
		foreach ( $test as $section ) {
			if ( isset( $section['deleted'] ) ) {
				continue;
			}
			$this->result .= $section['headingLine'] . "\n";
			$this->result .= $section['contents'];
		}
	}

	protected function emitHooks( $heading, $contents ) {
		$this->result .= $contents;
	}
}

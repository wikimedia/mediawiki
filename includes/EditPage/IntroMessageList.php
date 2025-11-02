<?php

namespace MediaWiki\EditPage;

use InvalidArgumentException;
use MediaWiki\Message\Message;

/**
 * Encapsulates a list of edit form intro messages (as HTML) with their identifiers.
 *
 * @internal
 */
class IntroMessageList {
	/** @var array<string,string> */
	public array $list = [];
	/** @var int IntroMessageBuilder::MORE_FRAMES or IntroMessageBuilder::LESS_FRAMES */
	private int $frames;
	/** @var array<string,true> */
	private array $skip;

	/**
	 * @param int $frames Some intro messages come with optional wrapper frames.
	 *   Pass IntroMessageBuilder::MORE_FRAMES to include the frames whenever possible,
	 *   or IntroMessageBuilder::LESS_FRAMES to omit them whenever possible.
	 * @param string[] $skip Identifiers of messages not to generate
	 */
	public function __construct( int $frames, array $skip = [] ) {
		if ( !in_array( $frames, [ IntroMessageBuilder::MORE_FRAMES, IntroMessageBuilder::LESS_FRAMES ], true ) ) {
			throw new InvalidArgumentException( "Expected MORE_FRAMES or LESS_FRAMES" );
		}
		$this->frames = $frames;
		$this->skip = array_fill_keys( $skip, true );
	}

	private function wrap( string $html, string $wrap ): string {
		if ( $this->frames === IntroMessageBuilder::LESS_FRAMES ) {
			return $html;
		}
		return str_replace( '$1', $html, $wrap );
	}

	public function add( Message $msg, string $wrap = '$1' ): void {
		if ( !$msg->isDisabled() && !isset( $this->skip[ $msg->getKey() ] ) ) {
			$this->addWithKey( $msg->getKey(), $msg->parse(), $wrap );
		}
	}

	public function addWithKey( string $key, string $html, string $wrap = '$1' ): void {
		if ( $html === '' ) {
			// Remove empty notices (T265798)
			return;
		}
		if ( !isset( $this->skip[$key] ) ) {
			$this->list[$key] = $this->wrap( $html, $wrap );
		}
	}

	public function getList(): array {
		return $this->list;
	}
}

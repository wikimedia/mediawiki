<?php
// CardSet is a widget for displaying a stack of cards, e.g. for
// user preferences. It is skinnable by overloading.
// Default CardSet uses fieldsets for the cards,
// the derived class TabbedCardSet uses JavaScript-based tabs
//
// Usage:
// First, create a CardSet using the constructor
// Then, add cards to it
// Finally Render to get the HTML

class CardSet {

	/* private */ var $mLabels,	// Array of card labels
			  $mBodies,	// Array of card bodies
			  $mTitle;	// Title of this stack

	// Initialize an empty CardSet.
	function CardSet( $title )
	{
		$this->mLabels = array();
		$this->mBodies = array();
		$this->mTitle  = $title;
	}

	// Add a card to the set. The body of the card is expected to be
	// HTML, not wikitext.
	function addCard( $label, $body )
	{
		$this->mLabels[] = $label;
		$this->mBodies[] = $body;
	}

	// Return the HTML of this CardSet
	function renderToOutpage( &$out )
	{
		for ( $i=0; $i<count( $this->mLabels ); $i++ )
		{
			$s .= "<fieldset>\n<legend>" . $this->mLabels[$i] . "</legend>\n"
				. $this->mBodies[$i] . "</fieldset>\n";
		}

		$out->addHTML( $s );
	}

} // end of: class CardSet

?>

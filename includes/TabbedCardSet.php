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

class TabbedCardSet extends CardSet {

	// Return the HTML of this CardSet
	function renderToOutpage( &$out )
	{
		$s = "<div id=\"column-content\"><div id=\"content\">\n";

		for ( $i=0; $i<count( $this->mLabels ); $i++ )
		{
			$s .= "<div class=\"usage\" id=\"area{$i}\"><div id=\"subheading{$i}\"><h2><a name=\"card{$i}\"></a>".
				$this->mLabels[$i] . "</h2></div>" . $this->mBodies[$i] . "</div>\n\n";
		}
		$s .= "</div></div>\n\n<div id=\"bar\"><div id=\"p-cactions\" class=\"portlet\">\n<ul>\n";

		for ( $i=0; $i<count( $this->mLabels ); $i++ )
		{
			$selected = ($i==0 ? "class=\"selected\"" : "");
			$s .= "<li id=\"tab{$i}\" {$selected} onclick=\"chgArea({$i},this);\"
			    onmouseover=\"hilight({$i});\" onmouseout=\"if(prev!={$i}){hilight(this,-1);}\"><a href=\"#card{$i}\">".
			    $this->mLabels[$i] . "</a></li>";
		}

		$s .= "</ul></div></div>\n\n";

		$out->addScript("
			<script>
			var elements=". count( $this->mLabels ) .";
			var prev=-1;
			
			function hideall(){
  				for(i=0; i<elements; i++){document.getElementById('area'+i).style.display='none';}
  				document.getElementById('bar').style.display='inline';
			}
			
			function chgArea(n,obj){
   				if(prev>=0){
     					hilight( document.getElementById('tab'+prev) , -1);
     					document.getElementById('area'+prev).style.display='none';
   				}
   				hilight(obj,n);
			
   				document.getElementById('area'+n).style.display='inline';
   				prev=n ;
			}
			
			function hilight(obj,n){
   				if (n<0) { obj.className=\"\"; }
      				else  { obj.className=\"selected\"; }
			}
			function inittabs(){
   				hideall();
   				chgArea(0,document.getElementById('tab0'));
			}
			</script>
		" );
			
		$out->setOnloadHandler( $out->getOnloadHandler() . "inittabs();" );
		$out->addHTML( $s );
	}

} // end of: class CardSet

?>

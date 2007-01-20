<?php
/**
 *
 * @addtogroup SpecialPage
 */

/**
 *
 * @addtogroup SpecialPage
 */
class LongPagesPage extends ShortPagesPage {

	function getName() {
		return "Longpages";
	}

	function sortDescending() {
		return true;
	}
}

/**
 * constructor
 */
function wfSpecialLongpages()
{
    list( $limit, $offset ) = wfCheckLimits();

    $lpp = new LongPagesPage();

    $lpp->doQuery( $offset, $limit );
}

?>

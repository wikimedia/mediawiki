/**
 * jQuery checkboxShiftClick
 *
 * This will enable checkboxes to be checked or unchecked in a row by clicking one, holding shift and clicking another one
 *
 * @author Krinkle <krinklemail@gmail.com>
 * @license GPL v2
 */

jQuery.fn.checkboxShiftClick = function( text ) {
	var prevCheckbox = null;
	var $box = this;
	// When our boxes are clicked..
	$box.click(function (e) {
		// And one has been clicked before...
		if (prevCheckbox !== null && e.shiftKey) {
			// Check or uncheck this one and all in-between checkboxes
			$box.slice(
			  Math.min($box.index(prevCheckbox), $box.index(e.target)),
			  Math.max($box.index(prevCheckbox), $box.index(e.target)) + 1
			).attr({checked: e.target.checked ? 'checked' : ''});
		}
		// Either way, update the prevCheckbox variable to the one clicked now
		prevCheckbox = e.target;
	});
	return $box;
};
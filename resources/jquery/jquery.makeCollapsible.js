/**
 * make lists, divs, tables etc. toggleable
 *
 * This will enable collapsible-functionality on all passed elements.
 * Will prevent binding twice to the same element.
 * Initial state is expanded by default, this can be overriden by adding class
 * "kr-collapsed" to the "kr-collapsible" element.
 * Elements made collapsible have class "kr-made-collapsible".
 * Except for tables and lists, the inner content is wrapped in "kr-collapsible-content".
 *
 * Copyright 2008-2009 Krinkle <krinklemail@gmail.com>
 *
 * @license CC-BY 2.0 NL <http://creativecommons.org/licenses/by/2.0/nl/deed.en>
 */

$.fn.makeCollapsible = function() {

	return this.each(function() {
		var	$that = $(this).addClass('kr-collapsible'), // in case $('#myAJAXelement').makeCollapsible() was called
			that = this,
			collapsetext = $(this).attr('data-collapsetext'),
			expandtext = $(this).attr('data-expandtext');
		// Use custom text or default ?
		if( !collapsetext || collapsetext == ''){
			collapsetext = 'Collapse';
		}
		if ( !expandtext || expandtext == ''){
			expandtext = 'Expand';
		}
		// Create toggle link with a space around the brackets (&nbsp)
		$toggleLink = $('<span class="kr-collapsible-toggle kr-collapsible-toggle-hide">&nbsp;[<a href="#">' + collapsetext + '</a>]&nbsp;</span>').click(function(){
			var	$that = $(this),
				$collapsible = $that.closest('.kr-collapsible.kr-made-collapsible').toggleClass('kr-collapsed');
			if ($that.hasClass('kr-collapsible-toggle-hide')) {
				// Change link to "Show"
				$that
					.removeClass('kr-collapsible-toggle-hide').addClass('kr-collapsible-toggle-show')
					.find('> a').html(expandtext);
				// Hide the collapsible element
				if ($collapsible.is('table')) {
					// Hide all direct childing table rows of this table, except the row containing the link
					// Slide doens't work, but fade works fine as of jQuery 1.1.3
					// http://stackoverflow.com/questions/467336/jquery-how-to-use-slidedown-or-show-function-on-a-table-row#920480
					// Stop to prevent animaties from stacking up 
					$collapsible.find('> tbody > tr').not($that.parent().parent()).stop(true, true).fadeOut();
				} else if ($collapsible.is('ul') || $collapsible.is('ol')) {
					$collapsible.find('> li').not($that.parent()).stop(true, true).slideUp();
				} else { // <div>, <p> etc.
					$collapsible.find('> .kr-collapsible-content').slideUp();
				}
			} else {
				// Change link to "Hide"
				$that
					.removeClass('kr-collapsible-toggle-show').addClass('kr-collapsible-toggle-hide')
					.find('> a').html(collapsetext);
				// Show the collapsible element
				if ($collapsible.is('table')) {
					$collapsible.find('> tbody > tr').not($that.parent().parent()).stop(true, true).fadeIn();
				} else if ($collapsible.is('ul') || $collapsible.is('ol')) {
					$collapsible.find('> li').not($that.parent()).stop(true, true).slideDown();
				} else { // <div>, <p> etc.
					$collapsible.find('> .kr-collapsible-content').slideDown();
				}
			}
			return false;
		});
		// Skip if it has been enabled already.
		if ($that.hasClass('kr-made-collapsible')) {
			return;
		} else {
			$that.addClass('kr-made-collapsible');
		}
		// Elements are treated differently
		if ($that.is('table')) {
			// The toggle-link will be in the last cell (td or th) of the first row
			var $lastCell = $('tr:first th, tr:first td', that).eq(-1);
			if (!$lastCell.find('> .kr-collapsible-toggle').size()) {
				$lastCell.prepend($toggleLink);
			}
			
		} else if ($that.is('ul') || $that.is('ol')) {
			if (!$('li:first', $that).find('> .kr-collapsible-toggle').size()) {
				// Make sure the numeral count doesn't get messed up, reset to 1 unless value-attribute is already used
				if ( $('li:first', $that).attr('value') == '' ) {
					$('li:first', $that).attr('value', '1');
				}
				$that.prepend($toggleLink.wrap('<li class="kr-collapsibile-toggle-li">').parent());
			}		
		} else { // <div>, <p> etc.
			// Is there an content-wrapper already made ?
			// If a direct child with the class does not exists, create the wrap.
			if (!$that.find('g> .kr-collapsible-content').size()) {
				$that.wrapInner('<div class="kr-collapsible-content">');
			}

			// Add toggle-link if not present
			if (!$that.find('> .kr-collapsible-toggle').size()) {
				$that.prepend($toggleLink);
			}
		}
		// Initial state
		if ($that.hasClass('kr-collapsed')) {
			$toggleLink.click();
		}
	});
};
$(function(){
	$('.kr-collapsible').makeCollapsible();
});
/* JavaScript for Drafts extension */

function editSectionHiliteOn (sectionID) {
	document.getElementById(sectionID).className = 'edit_section_hilite';
}

function editSectionHiliteOff (sectionID) {
	document.getElementById(sectionID).className = '';
}
/* Austrian German initialisation for the jQuery UI date picker plugin. */
jQuery(function($){
	$.datepicker.regional['de-AT'] = {
		closeText: 'schließen',
		prevText: '&#x3C;zurück',
		nextText: 'Vor&#x3E;',
		currentText: 'heute',
		monthNames: ['Jänner','Februar','März','April','Mai','Juni',
		'Juli','August','September','Oktober','November','Dezember'],
		monthNamesShort: ['Jän','Feb','Mär','Apr','Mai','Jun',
		'Jul','Aug','Sep','Okt','Nov','Dez'],
		dayNames: ['Sonntag','Montag','Dienstag','Mittwoch','Donnerstag','Freitag','Samstag'],
		dayNamesShort: ['So','Mo','Di','Mi','Do','Fr','Sa'],
		dayNamesMin: ['So','Mo','Di','Mi','Do','Fr','Sa'],
		weekHeader: 'KW',
		dateFormat: 'dd.mm.yy',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ''};
	$.datepicker.setDefaults($.datepicker.regional['de-AT']);
});

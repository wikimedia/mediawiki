loadGM( {
		"ajax-add-category":"[Add Category]",
		"ajax-add-category-submit":"[Add]",
		"ajax-confirm-prompt":"[Confirmation Text]",
		"ajax-confirm-title":"[Confirmation Title]",
		"ajax-confirm-save":"[Save]",
		"ajax-add-category-summary":"[Add category $1]",
		"ajax-remove-category-summary":"[Remove category $2]",
		"ajax-confirm-actionsummary":"[Summary]",
		"ajax-error-title":"Error",
		"ajax-error-dismiss":"OK",
		"ajax-remove-category-error":"[RemoveErr]"
		} );

var ajaxCategories = {

	handleAddLink : function(e) {
		e.preventDefault();
		
		// Make sure the suggestion plugin is loaded. Load everything else while we're at it
		mvJsLoader.doLoad( ['$j.ui', '$j.ui.dialog', '$j.fn.suggestions'],
			function() {
				$j('#mw-addcategory-prompt').toggle();
				
				$j('#mw-addcategory-input').suggestions( {
						'fetch':ajaxCategories.fetchSuggestions,
						'cancel': function() {
								var req = ajaxCategories.request;
								if (req.abort)
									req.abort()
							},
					} );
					
				$j('#mw-addcategory-input').suggestions();
			} );
	},
	
	fetchSuggestions : function( query ) {
		var that = this;
		var request = $j.ajax( {
			url: wgScriptPath + '/api.php',
			data: {
				'action': 'query',
				'list': 'allpages',
				'apnamespace': 14,
				'apprefix': $j(this).val(),
				'format': 'json'
			},
			dataType: 'json',
			success: function( data ) {
				// Process data.query.allpages into an array of titles
				var pages = data.query.allpages;
				var titleArr = [];
				
				$j.each(pages, function(i, page) {
					var title = page.title.split( ':', 2 )[1];
					titleArr.push(title);
				} );
				
				$j(that).suggestions( 'suggestions', titleArr );
			}
		});
		
		ajaxCategories.request = request;
	},
	
	reloadCategoryList : function( response ) {
		var holder = $j('<div/>');
		
		holder.load( window.location.href+' .catlinks', function() {
			$j('.catlinks').replaceWith( holder.find('.catlinks') );
			ajaxCategories.setupAJAXCategories();
			ajaxCategories.removeProgressIndicator( $j('.catlinks') );
		});
	},
	
	confirmEdit : function( page, fn, actionSummary, doneFn ) {
		// Load jQuery UI
		mvJsLoader.doLoad( ['$j.ui', '$j.ui.dialog', '$j.fn.suggestions'], function() {
				// Produce a confirmation dialog
				
				var dialog = $j('<div/>');
				
				dialog.addClass('mw-ajax-confirm-dialog');
				dialog.attr( 'title', gM('ajax-confirm-title') );
				
				// Intro text.
				var confirmIntro = $j('<p/>');
				confirmIntro.text( gM('ajax-confirm-prompt') );
				dialog.append(confirmIntro);
				
				// Summary of the action to be taken
				var summaryHolder = $j('<p/>');
				var summaryLabel = $j('<strong/>');
				summaryLabel.text(gM('ajax-confirm-actionsummary')+" " );
				summaryHolder.text( actionSummary );
				summaryHolder.prepend( summaryLabel );
				dialog.append(summaryHolder);
				
				// Reason textbox.
				var reasonBox = $j('<input type="text" size="45" />');
				reasonBox.addClass('mw-ajax-confirm-reason');
				dialog.append(reasonBox);
				
				// Submit button
				var submitButton = $j('<input type="button"/>');
				submitButton.val( gM( 'ajax-confirm-save' ) );
				
				var submitFunction = function() {
						ajaxCategories.addProgressIndicator( dialog );
						ajaxCategories.doEdit( page, fn, reasonBox.val(),
								function() {
									doneFn();
									dialog.dialog('close');
									ajaxCategories.removeProgressIndicator( dialog );
								}
							);
					};
				
				var buttons = {};
				buttons[gM('ajax-confirm-save')] = submitFunction;
				var dialogOptions = { 
										'AutoOpen' : true,
										'buttons' : buttons,
										'width' : 450,
									};
				
				$j('#catlinks').prepend(dialog);
				dialog.dialog( dialogOptions );
			} );
	},
	
	doEdit : function( page, fn, summary, doneFn ) {
		// Get an edit token for the page.
		var getTokenVars = {
							'action':'query',
							'prop':'info|revisions',
							'intoken':'edit',
							'titles':page,
							'rvprop':'content|timestamp',
							'format':'json',
							};
		$j.get(wgScriptPath+'/api.php', getTokenVars, 
			function( reply ) {
				var infos = reply.query.pages;
				$j.each(infos, function(pageid, data) {
					var token = data.edittoken;
					var timestamp = data.revisions[0].timestamp;
					var oldText = data.revisions[0]['*'];
					
					var newText = fn(oldText);
					
					if (newText === false) return;
					
					var postEditVars = {
							'action':'edit',
							'title':page,
							'text':newText,
							'summary':summary,
							'token':token,
							'basetimestamp':timestamp,
							'format':'json',
							};
					
					$j.post( wgScriptPath+'/api.php', postEditVars, doneFn,	'json' );
				} );
			}
		, 'json' );
	},
	
	addProgressIndicator : function( elem ) {
		var indicator = $j('<div/>');
		
		indicator.addClass('mw-ajax-loader');
		
		elem.append( indicator );
	},
	
	removeProgressIndicator : function( elem ) {
		elem.find('.mw-ajax-loader').remove();
	},
	
	handleCategoryAdd : function(e) {
		// Grab category text
		var category = $j('#mw-addcategory-input').val();
		var appendText = "\n[["+wgFormattedNamespaces[14]+":"+category+"]]\n";
		var summary = gM('ajax-add-category-summary', category);
		
		ajaxCategories.confirmEdit( wgPageName, function(oldText) { return oldText+appendText },
				summary, ajaxCategories.reloadCategoryList );
	},
	
	handleDeleteLink : function(e) {
		e.preventDefault();
		
		var category = $j(this).parent().find('a').text();
		
		// Build a regex that matches legal invocations of that category.
		
		// In theory I should escape the aliases, but there's no JS function for it
		//  Shouldn't have any real impact, can't be exploited or anything, so we'll
		//  leave it for now.
		var categoryNSFragment = '';
		$j.each(wgNamespaceIds, function( name, id ) {
			if (id == 14) {
				// Allow the first character to be any case
				var firstChar = name.charAt(0);
				firstChar = '['+firstChar.toUpperCase()+firstChar.toLowerCase()+']'
				categoryNSFragment += '|'+firstChar+name.substr(1);
			}
		} );
		categoryNSFragment = categoryNSFragment.substr(1) // Remove leading |
		
		
		// Build the regex
		var titleFragment = category;
		
		firstChar = category.charAt(0);
		firstChar = '['+firstChar.toUpperCase()+firstChar.toLowerCase()+']';
		titleFragment = firstChar+category.substr(1);
		var categoryRegex = '\\[\\['+categoryNSFragment+':'+titleFragment+'(\\|[^\\]]*)?\\]\\]';
		categoryRegex = new RegExp( categoryRegex, 'g' );
		
		var summary = gM('ajax-remove-category-summary', category);
		
		ajaxCategories.confirmEdit( wgPageName,
				function(oldText) {
					var newText = oldText.replace(categoryRegex, '');
					
					if (newText == oldText) {
						var error = gM('ajax-remove-category-error');
						ajaxCategories.showError( error );
						ajaxCategories.removeProgressIndicator( $j('.mw-ajax-confirm-dialog') );
						$j('.mw-ajax-confirm-dialog').dialog('close');
						return false;
					}
					
					return newText;
				}, summary, ajaxCategories.reloadCategoryList );
	},
	
	showError : function( str ) {
		var dialog = $j('<div/>');
		dialog.text(str);
		
		$j('#bodyContent').append(dialog);
		
		var buttons = {};
		buttons[gM('ajax-error-dismiss')] = function(e) { dialog.dialog('close'); };
		var dialogOptions = {
			'buttons' : buttons,
			'AutoOpen' : true,
			'title' : gM('ajax-error-title'),
		};
		
		dialog.dialog(dialogOptions);
	},
	
	setupAJAXCategories : function() {
		// Only do it for articles.
		if ( !wgIsArticle ) return;
		
		var clElement = $j('.catlinks');
		
		// Unhide hidden category holders.
		clElement.removeClass( 'catlinks-allhidden' );
		
		var addLink = $j('<a/>');
		addLink.addClass( 'mw-ajax-addcategory' );
		
		// Create [Add Category] link
		addLink.text( gM( 'ajax-add-category' ) );
		addLink.attr('href', '#');
		addLink.click( ajaxCategories.handleAddLink );
		clElement.append(addLink);
		
		// Create add category prompt
		var promptContainer = $j('<div id="mw-addcategory-prompt"/>');
		var promptTextbox = $j('<input type="text" size="45" id="mw-addcategory-input"/>');
		var addButton = $j('<input type="button" id="mw-addcategory-button"/>' );
		addButton.val( gM('ajax-add-category-submit') );
		
		promptTextbox.keypress( ajaxCategories.handleCategoryInput );
		addButton.click( ajaxCategories.handleCategoryAdd );
		
		promptContainer.append(promptTextbox);
		promptContainer.append(addButton);
		promptContainer.hide();
		
		// Create delete link for each category.
		$j('.catlinks div span a').each( function(e) {
				// Create a remove link
				var deleteLink = $j('<a class="mw-remove-category" href="#"/>');	
				
				deleteLink.click(ajaxCategories.handleDeleteLink);
				
				$j(this).after(deleteLink);
			} );
		
		clElement.append(promptContainer);
	},

};

js2AddOnloadHook( ajaxCategories.setupAJAXCategories );

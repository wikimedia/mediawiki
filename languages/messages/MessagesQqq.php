<?php
/** Message documentation (Message documentation)
 *
 * @ingroup Language
 * @file
 *
 * @author Ahonc
 * @author Ans
 * @author Bangin
 * @author BrokenArrow
 * @author Codex Sinaiticus
 * @author Darth Kule
 * @author Dsvyas
 * @author Erwin85
 * @author EugeneZelenko
 * @author Garas
 * @author GerardM
 * @author Helix84
 * @author INkubusse
 * @author Jon Harald Søby
 * @author Kizito
 * @author Lejonel
 * @author Li-sung
 * @author Lloffiwr
 * @author Malafaya
 * @author McDutchie
 * @author Meno25
 * @author MichaelFrey
 * @author Mormegil
 * @author Mpradeep
 * @author Nemo bis
 * @author Nike
 * @author Node ue
 * @author Octahedron80
 * @author Purodha
 * @author Raymond
 * @author SPQRobin
 * @author Sanbec
 * @author Sborsody
 * @author Seb35
 * @author Shushruth
 * @author Siebrand
 * @author Singularity
 * @author Slomox
 * @author Sp5uhe
 * @author Urhixidur
 * @author Vinhtantran
 * @author Yyy
 * @author פוילישער
 */

$messages = array(
# User preference toggles
'tog-underline' => "[[Special:Preferences]], tab 'Misc'. Offers user a choice how to underline links.",
'tog-highlightbroken' => "[[Special:Preferences]], tab 'Misc'. Offers user a choice how format internal links to non-existing pages. As red links or with a trailing question mark.",
'tog-justify' => "[[Special:Preferences]], tab 'Misc'. Offers user a choice to justify paragraphs or not.",
'tog-hideminor' => "[[Special:Preferences]], tab 'Recent changes'. Offers user to hide minor edits in recent changes or not.",
'tog-extendwatchlist' => "[[Special:Preferences]], tab 'Watchlist'. Offers user to show all applicable changes in watchlist (by default only the last change to a page on the watchlist is shown).",
'tog-usenewrc' => "[[Special:Preferences]], tab 'Recent changes'. Offers user to use alternative reprsentation of [[Special:RecentChanges]].",
'tog-numberheadings' => "[[Special:Preferences]], tab 'Misc'. Offers user to numbered headings on content pages.",
'tog-showtoolbar' => "[[Special:Preferences]], tab 'Edit'. Offers user to show edit toolbar in page edit screen.

This is the toolbar: [[Image:Toolbar.png]]",
'tog-editondblclick' => "[[Special:Preferences]], tab 'Edit'. Offers user to open edit page on double click.",
'tog-editsection' => "[[Special:Preferences]], tab 'Edit'. Offers user to add links in sub headings for editing sections.",
'tog-editsectiononrightclick' => "[[Special:Preferences]], tab 'Edit'. Offers user to edit a section by clicking on a section title.",
'tog-showtoc' => "[[Special:Preferences]], tab 'Misc'. Offers user to show a table of contents automatically if a page has more than three headings.",
'tog-rememberpassword' => "[[Special:Preferences]], tab 'User profile', section 'Change password'. Offers user remember login details.

{{Identical|Remember my login on this computer}}",
'tog-editwidth' => "[[Special:Preferences]], tab 'Edit'. Offers user make give edit box full width in browser.",
'tog-watchcreations' => "[[Special:Preferences]], tab 'Watchlist'. Offers user to add created pages to watchlist.",
'tog-watchdefault' => "[[Special:Preferences]], tab 'Watchlist'. Offers user to add edited pages to watchlist.",
'tog-watchmoves' => "[[Special:Preferences]], tab 'Watchlist'. Offers user to add moved pages to watchlist.",
'tog-watchdeletion' => "[[Special:Preferences]], tab 'Watchlist'. Offers user to add deleted pages to watchlist.",
'tog-minordefault' => "[[Special:Preferences]], tab 'Edit'. Offers user to mark all edits minor by default.",
'tog-previewontop' => 'Toggle option used in [[Special:Preferences]].',
'tog-previewonfirst' => 'Toggle option used in [[Special:Preferences]].',
'tog-nocache' => "[[Special:Preferences]], tab 'Misc.'. Offers the user the option of disabling caching of pages in the browser",
'tog-enotifwatchlistpages' => 'In user preferences',
'tog-enotifusertalkpages' => 'In user preferences',
'tog-enotifminoredits' => 'In user preferences',
'tog-enotifrevealaddr' => 'Toggle option used in [[Special:Preferences]].',
'tog-shownumberswatching' => 'Toggle option used in [[Special:Preferences]], in the section for recent changes. When this option is activated, the entries in recent changes includes the number of users who watch pages.',
'tog-fancysig' => 'In user preferences under the signature box',
'tog-externaleditor' => "[[Special:Preferences]], tab 'Edit'. Offers user to use an external editor by default.",
'tog-externaldiff' => "[[Special:Preferences]], tab 'Edit'. Offers user to use an external diff program by default.",
'tog-showjumplinks' => 'Toggle option used in [[Special:Preferences]]. The "jump to" links are shown as "jump to: navigation, search" but they are hidden by default (you can enable them with this option).',
'tog-uselivepreview' => 'Toggle option used in [[Special:Preferences]]. Live preview is an experimental feature (unavailable by default) to use edit preview without loading the page again.',
'tog-forceeditsummary' => 'Toggle option used in [[Special:Preferences]].',
'tog-watchlisthideown' => "[[Special:Preferences]], tab 'Watchlist'. Offers user to hide own edits from watchlist.",
'tog-watchlisthidebots' => "[[Special:Preferences]], tab 'Watchlist'. Offers user to hide bot edits from watchlist.",
'tog-watchlisthideminor' => "[[Special:Preferences]], tab 'Watchlist'. Offers user to hide minor edits from watchlist.",
'tog-watchlisthideliu' => "Option in tab 'Watchlist' of [[Special:Preferences]]",
'tog-watchlisthideanons' => "Option in tab 'Watchlist' of [[Special:Preferences]]",
'tog-nolangconversion' => 'In user preferences.',
'tog-ccmeonemails' => 'In user preferences',
'tog-diffonly' => 'Toggle option used in [[Special:Preferences]].',
'tog-showhiddencats' => 'Toggle option used in [[Special:Preferences]].',

'underline-always' => 'Used in [[Special:Preferences]] (under "Misc"). This option means "always underline links", there are also options "never" and "browser default".',
'underline-never' => 'Used in [[Special:Preferences]] (under "Misc"). This option means "never underline links", there are also options "always" and "browser default".

{{Identical|Never}}',
'underline-default' => 'Used in [[Special:Preferences]] (under "Misc"). This option means "underline links as in your browser", there are also options "never" and "always".',

# Dates
'sunday' => 'Name of the day of the week.',
'monday' => 'Name of the day of the week.',
'tuesday' => 'Name of the day of the week.',
'wednesday' => 'Name of the day of the week.',
'thursday' => 'Name of the day of the week.',
'friday' => 'Name of the day of the week.',
'saturday' => 'Name of the day of the week.',
'sun' => 'Abbreviation for Sunday, a day of the week.',
'mon' => 'Abbreviation for Monday, a day of the week.',
'tue' => 'Abbreviation for Tuesday, a day of the week.',
'wed' => 'Abbreviation for Wednesday, a day of the week.',
'thu' => 'Abbreviation for Thursday, a day of the week.',
'fri' => 'Abbreviation for Friday, a day of the week.',
'sat' => 'Abbreviation for Saturday, a day of the week.',
'january' => 'The first month of the Gregorian calendar',
'february' => 'The second month of the Gregorian calendar',
'march' => 'The third month of the Gregorian calendar',
'april' => 'The fourth month of the Gregorian calendar',
'may_long' => 'The fifth month of the Gregorian calendar',
'june' => 'The sixth month of the Gregorian calendar',
'july' => 'The seventh month of the Gregorian calendar',
'august' => 'The eighth month of the Gregorian calendar',
'september' => 'The ninth month of the Gregorian calendar',
'october' => 'The tenth month of the Gregorian calendar',
'november' => 'The eleventh month of the Gregorian calendar',
'december' => 'The twelfth month of the Gregorian calendar',
'january-gen' => 'The first month of the Gregorian calendar. Must be in genitive, if the language has a genitive case.',
'february-gen' => 'The second month of the Gregorian calendar. Must be in genitive, if the language has a genitive case.',
'march-gen' => 'The third month of the Gregorian calendar. Must be in genitive, if the language has a genitive case.',
'april-gen' => 'The fourth month of the Gregorian calendar. Must be in genitive, if the language has a genitive case.',
'may-gen' => 'The fifth month of the Gregorian calendar. Must be in genitive, if the language has a genitive case.',
'june-gen' => 'The sixth month of the Gregorian calendar. Must be in genitive, if the language has a genitive case.',
'july-gen' => 'The seventh month of the Gregorian calendar. Must be in genitive, if the language has a genitive case.',
'august-gen' => 'The eighth month of the Gregorian calendar. Must be in genitive, if the language has a genitive case.',
'september-gen' => 'The nineth month of the Gregorian calendar. Must be in genitive, if the language has a genitive case.',
'october-gen' => 'The tenth month of the Gregorian calendar. Must be in genitive, if the language has a genitive case.',
'november-gen' => 'The eleventh month of the Gregorian calendar. Must be in genitive, if the language has a genitive case.',
'december-gen' => 'The twelfth month of the Gregorian calendar. Must be in genitive, if the language has a genitive case.',
'jan' => 'Abbreviation of January, the first month of the Gregorian calendar',
'feb' => 'Abbreviation of February, the second month of the Gregorian calendar',
'mar' => 'Abbreviation of March, the thrird month of the Gregorian calendar',
'apr' => 'Abbreviation of April, the fourth month of the Gregorian calendar',
'may' => 'Abbreviation of May, the fifth month of the Gregorian calendar',
'jun' => 'Abbreviation of June, the sixth month of the Gregorian calendar',
'jul' => 'Abbreviation of July, the seventh month of the Gregorian calendar',
'aug' => 'Abbreviation of August, the eighth month of the Gregorian calendar',
'sep' => 'Abbreviation of September, the nineth month of the Gregorian calendar',
'oct' => 'Abbreviation of October, the tenth month of the Gregorian calendar',
'nov' => 'Abbreviation of November, the eleventh month of the Gregorian calendar',
'dec' => 'Abbreviation of December, the twelfth month of the Gregorian calendar',

# Categories related messages
'category_header' => 'In category description page',
'category-media-header' => 'In category description page',
'category-empty' => 'The text displayed in category page when that category is empty',
'hidden-category-category' => 'Name of the category where hidden categories will be listed.',
'category-subcat-count' => 'This message is displayed at the top of a category page showing the number of pages in the category.

* $1: number of subcategories shown
* $2: total number of subcategories in category',
'category-subcat-count-limited' => 'This message is displayed at the top of a category page showing the number of pages in the category when not all pages in a category are counted.

* $1: number of subcategories shown',
'category-article-count' => 'This message is used on category pages.

* $1: number of pages shown
* $2: total number of pages in category',
'category-article-count-limited' => 'This message is displayed at the top of a category page showing the number of pages in the category when not all pages in a category are counted.

* $1: number of pages shown',
'category-file-count' => 'This message is displayed at the top of a category page showing the number of pages in the category.

* $1: number of files shown
* $2: total number of files in category',
'category-file-count-limited' => 'This message is displayed at the top of a category page showing the number of pages in the category when not all pages in a category are counted.

* $1: number of files shown',
'listingcontinuesabbrev' => 'Shown in contiuation of each first letter group.
See http://test.wikipedia.org/wiki/Category:Test_ko?uselang={{SUBPAGENAME}}, for example.',

'mainpagetext' => 'Along with {{msg|mainpagedocfooter}}, the text you will see on the Main Page when your wiki is installed.',
'mainpagedocfooter' => 'Along with {{msg|mainpagetext}}, the text you will see on the Main Page when your wiki is installed.',

'about' => '{{Identical|About}}',
'newwindow' => 'Below the edit form, next to "[[MediaWiki:Edithelp/{{SUBPAGENAME}}|Editing help]]".',
'cancel' => 'Message shown below the edit form, and if you click on it, you stop with editing the page and go back to the normal page view.

{{Identical|Cancel}}',
'qbfind' => 'Alternative for "search" as used in Cologne Blue skin.',
'qbedit' => '{{Identical|Edit}}',
'qbmyoptions' => '{{Identical|My pages}}',
'qbspecialpages' => '{{Identical|Special pages}}',
'moredotdotdot' => '{{Identical|More...}}',
'mytalk' => 'In the personal urls page section - right upper corner.',
'navigation' => '{{Identical|Navigation}}',
'and' => 'The translation for "and" appears in the [[Special:Version]] page, between the last two items of a list.

{{Identical|And}}',

# Metadata in edit box
'metadata_help' => '{{Identical|Metadata}}',

'errorpagetitle' => 'Message shown in browser title bar when encountering error operation.

{{Identical|Error}}',
'returnto' => '{{Identical|Return to $1}}',
'tagline' => 'Used to idenify the source of copied information. Do not change <nowiki>{{SITENAME}}</nowiki>.',
'help' => 'General text (noun) used in the sidebar (by default). 

See also [[MediaWiki:Helppage/{{SUBPAGENAME}}|{{int:helppage}}]] and [[MediaWiki:Edithelp/{{SUBPAGENAME}}|{{int:edithelp}}]].

{{Identical|Help}}',
'search' => 'Noun. Text of menu section shown on every page of the wiki above the search form.

{{Identical|Search}}',
'searchbutton' => 'The button you can see in the sidebar, below the search input box. The "Go" button is [[MediaWiki:Searcharticle/{{SUBPAGENAME}}]].

{{Identical|Search}}',
'go' => '{{Identical|Go}}',
'searcharticle' => 'Button description in the search menu displayed on every page. The "Search" button is [[MediaWiki:Searchbutton/{{SUBPAGENAME}}]].

{{Identical|Go}}',
'history_short' => 'Text used on the history tab.

{{Identical|History}}',
'updatedmarker' => 'Displayed in the page history (of a page you are [[Special:Watchlist|watching]]), when the page has been edited since the last time you visited it.',
'printableversion' => 'Display name for link in wiki menu that leads to a printable version of a content page. Example: see one but last menu item on [[Main Page]].',
'permalink' => 'Display name for a permanent link to the current revision of a page. When the page is edited, permalink will still link to this revision. Example: Last menu link on [[{{MediaWiki:Mainpage}}]]',
'edit' => 'The text of the tab going to the edit form. When the page is protected, you will see "[[MediaWiki:Viewsource/{{SUBPAGENAME}}|{{int:viewsource}}]]". Should be in the infinitive mood.

{{Identical|Edit}}',
'create' => 'The text on the tab for to the edit form on unexisting pages.

{{Identical|Create}}',
'editthispage' => 'This is the "edit" link as used in the skins Classic/Standard, Cologne Blue and Nostalgia. See {{msg|create-this-page}} for when the page does not exist.',
'create-this-page' => 'In the skins Classic/Standard, Cologne Blue and Nostalgia this is the text for the link leading to the edit form on pages that have not yet been created. See {{msg|editthispage}} for when the page already exists.',
'delete' => 'Name of the Delete tab shown for admins. Should be in the imperative mood.

{{Identical|Delete}}',
'deletethispage' => '{{Identical|Delete this page}}',
'undelete_short' => "It is tab label. It's really can be named ''nstab-undelete''.",
'protect' => 'Name of protect tab displayed for admins',
'protect_change' => 'Text on links for each entry in [[Special:ProtectedPages]] to change the protection of pages (only displayed to admins).',
'protectthispage' => '{{Identical|Protect this page}}',
'unprotect' => 'Name of unprotect tab displayed for admins',
'talkpagelinktext' => 'Used as name of links going to talk page in some places, like in the subheading of [[Special:Mycontributions|Special:Contributions]], in [[Special:RecentChanges]], and in [[Special:Watchlist]].

{{Identical|Talk}}',
'personaltools' => 'Heading for a group of links to your user page, talk page, preferences, watchlist, and contributions. This heading is visible in the sidebar in some skins. For an example, see [http://translatewiki.net/wiki/Main_Page?useskin=simple Main Page using simple skin].',
'talk' => 'Used as display name for the tab to all talk pages. These pages accompany all content pages and can be used for discussing the content page. Example: [[Talk:Example]].

{{Identical|Discussion}}',
'views' => 'Subtitle for the list of available views, for the current page. In "monobook" skin the list of views are shown as tabs, so this sub-title is not shown.  To check when and where this message is displayed switch to "simple" skin.',
'toolbox' => 'The title of the toolbox below the search menu.',
'otherlanguages' => 'This message is shown under the toolbox. It is used if there are interwiki links added to the page, like <tt><nowiki>[[</nowiki>en:Interwiki article]]</tt>.',
'redirectedfrom' => 'The text displayed when a certain page is redirected to another page. Variable <tt>$1</tt> contains the name of the page user came from.',
'redirectpagesub' => 'Displayed under the page title of a page which is a redirect to another page, see [{{fullurl:Betawiki:Translators|redirect=no}} Betawiki:Translators] for example.

{{Identical|Redirect page}}',
'lastmodifiedat' => 'This message is shown below each page, in the footer with the logos and links.
* $1: date
* $2: time

See also [[MediaWiki:Lastmodifiedatby/{{SUBPAGENAME}}]].',
'jumpto' => '"Jump to" navigation links. Hidden by default in monobook skin. The format is: {{int:jumpto}} [[MediaWiki:Jumptonavigation/{{SUBPAGENAME}}|{{int:jumptonavigation}}]], [[MediaWiki:Jumptosearch/{{SUBPAGENAME}}|{{int:jumptosearch}}]].',
'jumptonavigation' => 'Part of the "jump to" navigation links. Hidden by default in monobook skin. The format is: [[MediaWiki:Jumpto/{{SUBPAGENAME}}|{{int:jumpto}}]] {{int:jumptonavigation}}, [[MediaWiki:Jumptosearch/{{SUBPAGENAME}}|{{int:jumptosearch}}]].

{{Identical|Navigation}}',
'jumptosearch' => 'Part of the "jump to" navigation links. Hidden by default in monobook skin. The format is: [[MediaWiki:Jumpto/{{SUBPAGENAME}}|{{int:jumpto}}]] [[MediaWiki:Jumptonavigation/{{SUBPAGENAME}}|{{int:jumptonavigation}}]], {{int:jumptosearch}}.

{{Identical|Search}}',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite' => 'Used as page name and link at the bottom of each wiki page. The contents of the page explain the purpose of the site.

{{doc-important|Do not change <nowiki>{{SITENAME}}</nowiki>}}

{{Identical|About}}',
'aboutpage' => 'Used as page for that contains the site description. Used at the bottom of every page on the wiki. Example: [[{{MediaWiki:Aboutpage}}|{{MediaWiki:About}} {{SITENAME}}]].

{{doc-important|Do not translate "Project:" part}}',
'bugreportspage' => 'Not used in Monobook skin. 
{{doc-important|Do not change <tt>Project:</tt> part.}}',
'copyrightpagename' => '{{doc-important|Do not change <nowiki>{{SITENAME}}</nowiki>}}',
'copyrightpage' => '{{doc-important|Do not change <nowiki>{{ns:project}}</nowiki>}}',
'currentevents' => 'Standard link in the sidebar, for news. See also {{msg|currentevents-url}} for the link url.',
'currentevents-url' => "Target page of ''{{Mediawiki:currentevents}}'' in the sidebar. See also {{msg|currentevents}}.
{{doc-important|Do not translate <tt>Project:</tt> part.}}",
'disclaimers' => 'Used as display name for the link to [[{{MediaWiki:Disclaimerpage}}]] shown at the bottom of every page on the wiki. Example [[{{MediaWiki:Disclaimerpage}}|{{MediaWiki:Disclaimers}}]].',
'disclaimerpage' => 'Used as page for that contains the site disclaimer. Used at the bottom of every page on the wiki. Example: [[{{MediaWiki:Disclaimerpage}}|{{MediaWiki:Disclaimers}}]]. 
{{doc-important|Do not change <tt>Project:</tt> part.}}',
'edithelp' => 'This is the text that appears on the editing help link that is near the bottom of the editing page',
'edithelppage' => 'The help page displayed when a user clicks on editing help link which is present on the right of Show changes button. 
{{doc-important|Do not change <tt>Help:</tt> part.}}',
'faqpage' => "FAQ is short for ''frequently asked questions''. This page is only linked on some of the old skins, not in Monobook or Modern.

{{doc-important|Do not translate <tt>Project:</tt> part.}}",
'helppage' => 'The link destination used by default in the sidebar, and in {{msg|noarticletext}}. 
{{doc-important|Do not change <tt>Help:</tt> part.}}',
'mainpage' => 'Defines the link and display name of the main page of the wiki. Shown as the top link in the navigation part of the interface. Please do not change it too often, that could break things!

{{Identical|Main page}}',
'mainpage-description' => 'The same as {{msg|mainpage|pl=yes}}, used as link text on [[MediaWiki:Sidebar]]. This makes it possible to the change the link destination (the message "mainpage") without changing the link text or without disabling translations.',
'policy-url' => 'Description: The URL of the project page describing the policies of the wiki. This is shown below every page (the left link).
{{doc-important|Do not change "Project:" part.}}',
'portal' => "Display name for the 'Community portal', shown in the sidebar menu of all pages. The target page is meant to be a portal for users where useful links are to be found about the wiki's operation.",
'portal-url' => 'Description: The URL of the community portal. This is shown in the sidebar by default (removed on Betawiki).
{{doc-important|Do not change "Project:" part.}}',
'privacy' => 'Used as page name and link at the bottom of each wiki page. The page contains a legal notice providing information about the use of personal information by the website owner.of the site. Example: [[Privacy policy]].',
'privacypage' => 'Used as page for that contains the privacy policy. Used at the bottom of every page on the wiki. Example: [[{{MediaWiki:Privacypage}}|{{MediaWiki:Privacy}}]].

{{doc-important|Do not change <tt>Project:</tt> part.}}',

'badaccess' => 'Title shown within page indicating unauthorized access.',
'badaccess-group0' => 'Shown when you are not allowed to do something.',
'badaccess-groups' => "Error message when you aren't allowed to do something.

* $1 is a list of groups.
* $2 is the number of groups.",

'versionrequired' => 'This message is not used in the MediaWiki core, but was introduced with the reason that it could be useful for extensions. See also {{msg|versionrequiredtext}}.',
'versionrequiredtext' => 'This message is not used in the MediaWiki core, but was introduced with the reason that it could be useful for extensions. See also {{msg|versionrequired}}.',

'ok' => '{{Identical|OK}}',
'pagetitle' => '{{doc-important|You most probably do not need to translate this message.}}',
'retrievedfrom' => 'Message which appears in the source of every page, but it is hidden. It is shown when printing. $1 is a link back to the current page: {{FULLURL:{{FULLPAGENAME}}}}.',
'youhavenewmessages' => 'The orange message appearing when someone edited your user talk page.
The format is: "{{int:youhavenewmessages| [[MediaWiki:Newmessageslink/{{SUBPAGENAME}}|{{int:newmessageslink}}]] |[[MediaWiki:Newmessagesdifflink/{{SUBPAGENAME}}|{{int:newmessagesdifflink}}]]}}"',
'newmessageslink' => 'This is the first link displayed in an orange rectangle when a user gets a message on his talk page. Used in message {{msg|youhavenewmessages|pl=yes}} (as parameter $1).

{{Identical|New messages}}',
'newmessagesdifflink' => 'This is the second link displayed in an orange rectangle when a user gets a message on his talk page',
'youhavenewmessagesmulti' => 'The alternative of {{msg|youhavenewmessages}} as used on wikis with a special setup so they can receive the "new message" notice on other wikis as well. Used on [http://www.wikia.com/ Wikia].',
'editsection' => 'Display name of link to edit a section on a content page. Example: [{{MediaWiki:Editsection}}].

{{Identical|Edit}}',
'editsection-brackets' => '{{doc-important|This message should most probably not be translated.}}',
'editold' => '{{Identical|Edit}}',
'editsectionhint' => "Tool tip shown when hovering the mouse over the link to '[{{MediaWiki:Editsection}}]' a section. Example: Edit section: Heading name",
'toc' => 'This is the title of the table of contents displayed in pages with more than 3 sections

{{Identical|Contents}}',
'showtoc' => 'This is the link used to show the table of contents

{{Identical|Show}}',
'hidetoc' => 'This is the link used to hide the table of contents

{{Identical|Hide}}',
'restorelink' => "This text is always displayed in conjunction with the \"thisisdeleted\" message (View or restore \$1?).  The user will see
View or restore <nowiki>{{PLURAL:\$1|one deleted edit|\$1 deleted edits}}</nowiki>?    i.e ''View or restore one deleted edit?''     or 
''View or restore n deleted edits?''",
'feed-unavailable' => 'This message is displayed when a user tries to use an RSS or Atom feed on a wiki where such feeds have been disabled.',
'site-rss-feed' => "Used in the HTML header of a wiki's RSS feed.
$1 is <nowiki>{{SITENAME}}</nowiki>.
HTML markup cannot be used.",
'site-atom-feed' => "Used in the HTML header of a wiki's Atom feed.
$1 is <nowiki>{{SITENAME}}</nowiki>.
HTML markup cannot be used.",
'red-link-title' => 'Title for red hyperlinks. Indicates, that the page is empty, not written yet.',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'The name for the tab of the main namespace. Example: [[Example]]

{{Identical|Page}}',
'nstab-user' => 'The name for the tab of the user namespace. Example: [[User:Example]]

{{Identical|User page}}',
'nstab-special' => 'The name for the tab of the special namespace. Example: [[Special:Example]]',
'nstab-project' => 'The name for the tab of the project namespace. Example: [[Project:Example]]',
'nstab-image' => 'The name for the tab of the image namespace. Example: [[Image:Example]]

{{Identical|File}}',
'nstab-mediawiki' => 'The name for the tab of the MediaWiki namespace. Example: [[MediaWiki:Example]]

{{Identical|Message}}',
'nstab-template' => 'The name for the tab of the template namespace. Example: [[Template:Example]]

{{Identical|Template}}',
'nstab-help' => 'The name for the tab of the help namespace. Example: [[Help:Rollback]]',
'nstab-category' => 'The name for the tab of the category namespace. Example: [[:Category:Example]]

{{Identical|Category}}',

# Main script and global functions
'nosuchspecialpage' => 'The title of the error you get when trying to open a special page which does not exist.
The text of the warning is the message [[MediaWiki:Nospecialpagetext/{{SUBPAGENAME}}]]. Example: [[Special:Nosuchpage]]',
'nospecialpagetext' => "The text of the error you get when trying to open a special page which does not exist.
The title of the warning is the message [[MediaWiki:Nosuchspecialpage/{{SUBPAGENAME}}]]. \"''<nowiki>[[Special:SpecialPages|{{int:specialpages}}]]</nowiki>''\" should remain untranslated. Example: [[Special:Nosuchpage]]",

# General errors
'error' => '{{Identical|Error}}',
'enterlockreason' => 'For developers when locking the database',
'missing-article' => "This message is shown when a revision does not exist, either as permalink or as diff. Examples:

# [http://translatewiki.net/w/i.php?title=Betawiki:News&oldid=9999999 Permalink with invalid revision#]
# [http://translatewiki.net/w/i.php?title=Betawiki:News&diff=426850&oldid=99999999 Diff with invalid revision#]

'''Parameters'''
* $1: Pagename
* $2: Content of 
*# {{msg|Missingarticle-rev}} - Permalink with invalid revision#
*# {{msg|Missingarticle-diff}} - Diff with invalid revision#",
'missingarticle-rev' => 'Parameter $2 of {{msg|Missing-article}}: It is shown after the articlename.

* $1: revision# of the requested id

[http://translatewiki.net/w/i.php?title=Translating:Tasks&oldid=371789000 Click here] to see an example of such an error message.',
'missingarticle-diff' => 'Parameter $2 of {{msg|Missing-article}}: It is shown after the articlename.

* $1: revision# of the old id
* $2: revision# of the id build the diff with.

[http://translatewiki.net/w/i.php?title=Translating:Tasks&diff=372398&oldid=371789000 Click here] to see an example of such an error message.',
'readonly_lag' => 'Error message displayed when the database is locked.',
'internalerror' => '{{Identical|Internal error}}',
'badtitle' => '{{Identical|Bad title}}',
'querypage-no-updates' => 'Text on some special pages, e.g. [[Special:FewestRevisions]].',
'viewsource' => 'the text displayed in place of the "edit" tab when the user has no permission to edit the page',
'viewsourcefor' => 'Subtitle shown when trying to edit a protected page.

{{Identical|For $1}}',
'protectedpagetext' => 'This message is displayed when trying to edit a page you can\'t edit because it has been protected.

* $1: the protection type, e.g. "protect" for fully protected pages',
'viewsourcetext' => 'The text shown when displaying the source of a page that the user has no permission to edit',
'protectedinterface' => 'Message shown if a user without the "editinterface" right tries to edit a page in the MediaWiki namespace.',
'editinginterface' => "A message shown when editing pages in the namespace MediaWiki:. In the URL, '''change \"setlang=en\" to your own language code.'''",
'ns-specialprotected' => 'Error message displayed when trying to edit a page in the Special namespace',

# Login and logout pages
'logouttext' => 'Log out message',
'welcomecreation' => 'The welcome message users see after registering a user account. $1 is the username of the new user.',
'yourname' => 'In user preferences

{{Identical|Username}}',
'yourpassword' => 'In user preferences

{{Identical|Password}}',
'yourpasswordagain' => 'In user preferences',
'remembermypassword' => '{{Identical|Remember my login on this computer}}',
'login' => "Shown to anonymous users in the upper right corner of the page. It is shown when you can't create an account, otherwise the message {{msg|nav-login-createaccount}} is shown.

{{Identical|Log in}}",
'nav-login-createaccount' => "Shown to anonymous users in the upper right corner of the page. When you can't create an account, the message {{msg|login}} is shown.",
'loginprompt' => 'A small notice in the log in form.',
'userlogin' => 'Name of special page [[Special:UserLogin]] where a user can log in or click to create a user account.',
'logout' => '{{Identical|Log out}}',
'userlogout' => '{{Identical|Log out}}',
'notloggedin' => 'This message is displayed in the standard skin when not logged in. The message is placed above the login link in the top right corner of pages.

{{Identical|Not logged in}}',
'nologin' => 'A message shown in the log in form. $1 is a link to the account creation form, and the text of it is "[[MediaWiki:Nologinlink/{{SUBPAGENAME}}|{{int:nologinlink}}]]".',
'nologinlink' => 'Text of the link to the account creation form. Before that link, the message [[MediaWiki:Nologin/{{SUBPAGENAME}}]] appears.',
'createaccount' => 'Used on the submit button in the form where you register a new account.',
'gotaccount' => 'A message shown in the account creation form. $1 is a link to the log in form, and the text of it is "[[MediaWiki:Gotaccountlink/{{SUBPAGENAME}}|{{int:gotaccountlink}}]]".',
'gotaccountlink' => 'Text of the link to the log in form. Before that link, the message [[MediaWiki:Gotaccount/{{SUBPAGENAME}}]] appears.

{{Identical|Log in}}',
'youremail' => '{{Identical|E-mail}}',
'username' => '{{Identical|Username}}',
'uid' => '{{Identical|User ID}}',
'prefs-memberingroups' => 'This message is shown on [[Special:Preferences]], first tab, where it is follwed by a colon.',
'yourrealname' => '{{Identical|Real name}}',
'yourlanguage' => '{{Identical|Language}}',
'yournick' => 'Used in [[Special:Preferences]].',
'badsig' => 'Error message displayed when entering invalid signature in user preferences',
'badsiglength' => 'Warning message that is displayed on [[Special:Preferences]] when trying to save a signature that is too long. Parameter $1 is the maximum number of characters that is allowed in a signature (multi-byte characters are counted as one character).',
'email' => '{{Identical|E-mail}}',
'prefs-help-realname' => 'In user preferences.',
'prefs-help-email' => 'Shown as explanation text on [[Special:Preferences]].',
'nocookiesnew' => "This message is displayed when a new account was successfully created, but the browser doesn't accept cookies.",
'nocookieslogin' => "This message is displayed when someone tried to login, but the browser doesn't accept cookies.",
'loginsuccesstitle' => 'The title of the page saying that you are logged in. The content of the page is the message "[[MediaWiki:Loginsuccess/{{SUBPAGENAME}}]]".',
'loginsuccess' => 'The content of the page saying that you are logged in. The title of the page is "[[MediaWiki:Loginsuccesstitle/{{SUBPAGENAME}}|{{int:loginsuccesstitle}}]]". $1 is the name of the logged in user.',
'nosuchuser' => 'Displayed when trying to log in with an unexisting username. When you are not allowed to create an account, the message {{msg|nosuchusershort}} is displayed.',
'nosuchusershort' => "Displayed when trying to log in with an unexisting username. This message is only shown when you can't create an account, otherwise the message {{msg|nosuchusershort}} is displayed.",
'wrongpasswordempty' => 'Error message displayed when entering a blank password',
'passwordtooshort' => 'This message is shown at

* [[Special:Preferences]]
* [[Special:CreateAccount]]

$1 is the minimum number of characters in the password.',
'mailmypassword' => 'Shown at [[Special:UserLogin]]
{{Identical|E-mail password}}',
'passwordremindertitle' => 'Title of e-mail which contains temporary password',
'passwordremindertext' => 'This text is used in an e-mail sent when a user requests a new temporary password (he has forgotten his password) or when an sysop creates a new user account choosing to have password and username sent to the new user by e-mail.
* $1 is an IP addres. Example: 123.123.123.123
* $2 is a username. Example: Joe
* $3 is a password. Example: er##@fdas!
* $4 is a URL. Example: http://wiki.example.com',
'noemail' => 'Shown as error message when trying to register a user sending password to e-mail adress and no e-mail address has been given. Registering users and sending a password to an e-mail address may require non-standard user rights. ([http://translatewiki.net/w/i.php?title=Special:UserLogin&action=submitlogin&type=signup Register user link])',
'acct_creation_throttle_hit' => 'Errormessage at [[Special:CreateAccount]].',
'emailauthenticated' => 'In user preferences. ([[Special:Preferences]])

* $1: obsolet, date and time
* $2: date
* $3: time',
'createaccount-title' => 'This is the subject of an e-mail sent to the e-mail address entered at [[Special:CreateAccount]] if the button "by e-mail" is clicked.',
'createaccount-text' => 'This text is sent as an e-mail to the e-mail address entered at [[Special:CreateAccount]] if the button "by e-mail" is clicked.

*Parameter $2 is the name entered as username.
*Parameter $3 is a password (randomly generated).
*Parameter $4 is a URL to the wiki',
'login-throttled' => 'Error message shown at [[Special:UserLogin]] after 5 wrong passwords. The hardcoded waiting time is 300 seconds.',

# Password reset dialog
'resetpass_header' => '{{Identical|Reset password}}',

# Edit page toolbar
'bold_sample' => 'This is the sample text that you get when you press the first button on the left on the edit toolbar.

{{Identical|Bold text}}',
'bold_tip' => 'This is the text that appears when you hover the mouse over the first button on the left of the edit toolbar.

{{Identical|Bold text}}',
'italic_sample' => 'The sample text that you get when you press the second button from the left on the edit toolbar.

{{Identical|Italic text}}',
'italic_tip' => 'This is the text that appears when you hover the mouse over the second button from the left on the edit toolbar.

{{Identical|Italic text}}',
'link_sample' => 'This is the default text in the internal link that is created when you press the third button from the left on the edit toolbar (the "Ab" icon).',
'link_tip' => 'Tip for internal links',
'extlink_sample' => 'This message appears when clicking on the fourth button of the edit toolbar. You can translate "link title". Because many of the localisations had urls that went to domains reserved for advertising, it is recommended that the link is left as-is. All customised links were replaced with the standard one, that is reserved in the standard and will never have adds or something.',
'extlink_tip' => 'This is the tip that appears when you hover the mouse over the fourth button from the left on the edit toolbar.',
'headline_sample' => 'Sample of headline text.',
'headline_tip' => 'This is the text that appears when you hover the mouse over the fifth button from the left on the edit toolbar.',
'math_sample' => 'The sample formula text that you get when you press the fourth button from the right on the edit toolbar.',
'math_tip' => 'This is the text that appears when you hover the mouse over the fourth button from the right on the edit toolbar.',
'nowiki_sample' => 'Text inserted between nowiki tags',
'nowiki_tip' => 'This is the text that appears when you hover the mouse over the third button from the right on the edit toolbar.',
'image_tip' => 'This is the text that appears when you hover the mouse over the sixth (middle) button on the edit toolbar',
'media_tip' => 'This is the text that appears when you hover the mouse over the fifth button from the right in the edit toolbar.',
'sig_tip' => 'This is the text that appears when you hover the mouse over the second key from the right on the edit toolbar.',
'hr_tip' => 'This is the text that appears when you hover the mouse over the first button on the right on the edit toolbar.',

# Edit pages
'summary' => 'The Summary text beside the edit summary field

{{Identical|Summary}}',
'minoredit' => 'Text above Save page button in editor',
'watchthis' => 'Text above Show preview button in editor

{{Identical|Watch this page}}',
'savearticle' => 'Text on the Save page button. See also {{msg|showpreview}} and {{msg|showdiff}} for the other buttons.',
'preview' => '{{Identical|Preview}}',
'showpreview' => 'The text of the button to preview the page you are editing. See also {{msg|showdiff}} and {{msg|savearticle}} for the other buttons.',
'showdiff' => 'Button below the edit page. See also {{msg|showpreview}} and {{msg|savearticle}} for the other buttons.',
'anoneditwarning' => 'Shown when editing a page anonymously.',
'summary-preview' => 'Preview of the edit summary, shown under the edit summary itself.',
'blockedtext' => 'Text displayed to blocked users',
'autoblockedtext' => 'Text displayed to automatically blocked users',
'blockednoreason' => '{{Identical|No reason given}}',
'nosuchsectiontext' => 'This message is displayed when a user tries to edit a section that does not exist. 

Parameter $1 is the content of section parameter in the URL (for example 1234 in the URL http://translatewiki.net/w/i.php?title=Sandbox&action=edit&section=1234)',
'loginreqlink' => 'Take a look on inflection. Used as parameter in messages loginreqpagetext and whitelistedittext.

{{Identical|Log in}}',
'newarticle' => '{{Identical|New}}',
'newarticletext' => "Text displayed above the edit box in editor when trying to create a new page.<br />'''Very important:''' leave <tt><nowiki>{{MediaWiki:Helppage}}</nowiki></tt> exactly as it is!",
'noarticletext' => 'This is the message that you get if you search for a term that has not yet got any entries on the wiki.',
'userpage-userdoesnotexist' => 'Error message displayed when trying to edit or create a page or a subpage that belongs to a user who is not registered on the wiki',
'clearyourcache' => 'Text displayed at the bottom in user preferences',
'usercssjsyoucanpreview' => 'Text displayed on every css/js page',
'updated' => '{{Identical|Updated}}',
'previewnote' => 'Note displayed when clicking on Show preview',
'editing' => "Shown as page title when editing a page. \$1 is the name of the page that is being edited. Example: \"''Editing Main Page''\".",
'editingsection' => 'The variable $1 is the page name.  This message displays at the top of the page when a user is editing a page section.',
'yourdiff' => '{{Identical|Differences}}',
'copyrightwarning' => 'Copyright warning displayed under the edit box in editor',
'longpagewarning' => 'Warning displayed when trying to edit a long page',
'longpageerror' => 'Warning displayed when trying to save a text larger than the maximum size allowed',
'titleprotectedwarning' => 'Warning message above the edit form when editing a page that has been protected aginst creation.',
'templatesused' => 'Displayed below the page when editing it. It indicates a list of templates which are used on that page.',
'templatesusedpreview' => 'Used in editor when displaying a preview.',
'templatesusedsection' => 'Used in editor when displaying a preview.',
'template-protected' => '{{Identical|Protected}}',
'template-semiprotected' => 'Used on [[Special:ProtectedPages]]. Appears in brackets after listed page titles which are semi-protected.',
'hiddencategories' => "This message is shown below the edit form, like you have a section ''\"Templates used on this page\"''.",
'edittools' => 'This text will be shown below edit and upload forms. It can be used to offer special characters not present on most keyboards for copying/pasting, and also often makes them clickable for insertion via a javascript. Since these are seen as specific to a wiki, however, this message should not contain anything but an html comment explaining how it should be used once the wiki has been installed.',
'permissionserrorstext-withaction' => '* $1 is the number of reasons that were found why the action cannot be performed.
* $2 is one of the right-* messages.

Please report at [[Support]] if you are unable to properly translate this message. Also see [[bugzilla:14246]]',
'recreate-deleted-warn' => 'Warning shown when creating a page which has already been deleted. See for example [[Test]].',

# Parser/template warnings
'expensive-parserfunction-warning' => 'On some (expensive) [[MetaWikipedia:Help:ParserFunctions|parser functions]] (e.g. <code><nowiki>{{#ifexist:}}</nowiki></code>) there is a limit of how many times it may be used. This is an error message shown when the limit is exceeded.

* $1 is the current number of parser function calls.
* $2 is the allowed number of parser function calls.',
'expensive-parserfunction-category' => 'This message is used as a category name for a category where pages are placed automatically if they contain too many calls to expensive parser functions.',
'post-expand-template-inclusion-category' => 'When templates are expanded, there is a size limit for the number of bytes yielded. Usually that occurs from excessively nested templates, recursive templates, or ones having x-zillion of #if #case or similar contructs in them. When the wikicode parser detects this, it outputs a red warning message to the page.',

# History pages
'viewpagelogs' => 'Link displayed in history of pages',
'currentrev' => '{{Identical|Current revision}}',
'revisionasof' => "Used on a difference page when comparing different versions of a page or when viewing an non-current version of a page. \$1 is the date/time at which the revision was created. Example: \"''Revision as of 14:44, 24 January 2008''\".",
'revision-info' => 'Appears just below the page title when an old version of the page is being viewed.

$1 indicates the time of that revision and 
$2 the author of the revision',
'currentrevisionlink' => '{{Identical|Current revision}}',
'cur' => 'Link in page history',
'next' => 'Link in page history

{{Identical|Next}}',
'last' => 'Link in page history

{{Identical|Last}}',
'page_first' => "This is part of the navigation message on the top and bottom of Special pages which are lists of things in alphabetical order, e.g. the 'Categories' special page. It is followed by the message [[Mediawiki:viewprevnext]].

first",
'page_last' => "This is part of the navigation message on the top and bottom of Special pages which are lists of things in alphabetical order, e.g. the 'Categories' special page. It is followed by the message [[Mediawiki:viewprevnext]].

{{Identical|Last}}",
'histlegend' => 'Text in history page',
'history-search' => 'Fieldset label in the edit history pages.',
'deletedrev' => 'When comparing deleted revisions for sysops

{{Identical|Deleted}}',
'histfirst' => 'Used in page history.',
'histlast' => 'Used in page history.',
'historyempty' => 'Text in page history for empty page revisions

{{Identical|Empty}}',

# Revision feed
'history-feed-item-nocomment' => "Title for each revision when viewing the RSS/Atom feed for a page history:

'''$1''' - user name

'''$2''' - date/time",

# Revision deletion
'rev-delundel' => 'Link in page history for oversight',
'revisiondelete' => '{{RevisionDelete}}
It is the page title of [[Special:RevisionDelete]].',
'revdelete-nooldid-title' => '{{RevisionDelete}}',
'revdelete-nooldid-text' => '{{RevisionDelete}}',
'revdelete-selected' => '{{RevisionDelete}}',
'logdelete-selected' => '{{RevisionDelete}}',
'revdelete-text' => '{{RevisionDelete}}
This is the introduction explaining the feature.',
'revdelete-legend' => '{{RevisionDelete}}',
'revdelete-hide-text' => 'Option for oversight',
'revdelete-hide-name' => 'Option for oversight',
'revdelete-hide-comment' => 'Option for oversight',
'revdelete-hide-user' => 'Option for oversight',
'revdelete-hide-restricted' => 'Option for oversight.',
'revdelete-suppress' => 'Option for oversight',
'revdelete-hide-image' => 'Option for oversight',
'revdelete-unsuppress' => '{{RevisionDelete}}',
'revdelete-log' => 'Log comment text for oversight

{{Identical|Log comment}}',
'revdelete-submit' => '{{RevisionDelete}}
This is the submit button on [[Special:RevisionDelete]].',
'revdelete-logentry' => '{{RevisionDelete}}
This is the message for the log entry in [[Special:Log/delete]].',
'logdelete-logentry' => '{{RevisionDelete}}
This is the message for the log entry in [[Special:Log/delete]].',
'revdelete-success' => '{{RevisionDelete}}',
'logdelete-success' => '{{RevisionDelete}}',
'revdel-restore' => '{{RevisionDelete}}',
'revdelete-content' => 'This message is used as parameter $1 in {{msg|revdelete-hid}} and {{msg|revdelete-unhid}} when hiding or unhiding the content of a revision.',
'revdelete-summary' => 'This message is used as parameter $1 in {{msg|revdelete-hid}} and {{msg|revdelete-unhid}} when hiding or unhiding the edit summary of a revision.',
'revdelete-uname' => 'This message is used as parameter $1 in {{msg|revdelete-hid}} and {{msg|revdelete-unhid}} when hiding or unhiding the username for a revision.

{{Identical|Username}}',
'revdelete-restricted' => 'This message is used as parameter $1 in {{msg|revdelete-log-message}} when setting visibility restrictions for administrators.',
'revdelete-unrestricted' => 'This message is used as parameter $1 in {{msg|revdelete-log-message}} when removing visibility restrictions for administrators.',
'revdelete-hid' => 'This message is used as parameter $1 in {{msg|revdelete-log-message}} when hiding revisions.

Parameter $1 is either {{msg|revdelete-content}} (when hiding the page content), {{msg|revdelete-summary}} (when hiding the edit summary), {{msg|revdelete-uname}} (when hiding the user name), or a combination of these three messages.',
'revdelete-unhid' => 'This message is used as parameter $1 in {{msg|revdelete-log-message}} when unhiding revisions.

Parameter $1 is either {{msg|revdelete-content}} (when unhiding the page content), {{msg|revdelete-summary}} (when unhiding the edit summary), {{msg|revdelete-uname}} (when unhiding the user name), or a combination of these three messages.',
'revdelete-log-message' => 'This log message is used together with {{msg|revdelete-logentry}} in the deletion or suppression logs when changing visibility restrictions for page revisions.

*Parameter $1 is either {{msg|revdelete-hid}} (when hiding data), {{msg|revdelete-unhid}} (when unhiding data), {{msg|revdelete-restricted}} (when applying restrictions for sysops),  {{msg|revdelete-unrestricted}} (when removing restrictions for sysops), or a combination of those messages.
*Parameter $2 is the number of revisions for which the restrictions are changed.',

# Suppression log
'suppressionlog' => 'Title of the suppression log. Shown in the drop down menu at [[Special:log]] and as header of [[Special:log/suppress]].',
'suppressionlogtext' => 'Description text of the suppression log. Shown at top of of [[Special:log/suppress]].',

# History merging
'mergehistory-autocomment' => 'This message is used as an edit summary when a redirect is automatically created after an entire page history is merged into another page history, and the user who did the merge wrote no comment.

*Parameter $1 is the name of the redirect page which is created
*Parameter $2 is the target of the redirect',
'mergehistory-comment' => 'This message is used as an edit summary when a redirect is automatically created after an entire page history is merged into another page history, and the user who did the merge wrote a comment.

*Parameter $1 is the name of the redirect page which is created
*Parameter $2 is the target of the redirect
*Parameter $3 is a log comment for the merge',

# Merge log
'mergelog' => 'This is the name of a log of merge actions done on [[Special:MergeHistory]]. This special page and this log is not enabled by default.',

# Diffs
'history-title' => 'Displayed as page title when you click on the "history" tab. The parameter $1 is the normal page title.',
'difference' => 'Displayed under the title when viewing the difference between two or more edits.',
'lineno' => 'Message used when comparing different versions of a page (diff). $1 is a line number.',
'compareselectedversions' => 'Used as button in history pages.',
'visualcomparison' => '{{Identical|Visual comparison}}',
'editundo' => 'Undo link when viewing diffs',
'diff-multi' => "This message appears in the revision history of a page when comparing two versions which aren't consecutive.",
'diff-src' => '{{Identical|Source}}',
'diff-with-final' => '* "<code><nowiki>&amp;#32;</nowiki></code>" is a forced space; leave it in if your language uses spaces
* $1 is <need more info>
* $2 is <need more info>',
'diff-width' => '{{Identical|Width}}',
'diff-height' => '{{Identical|Height}}',

# Search results
'searchresults-title' => 'Appears as page title in the html header of the search result special page.',
'noexactmatch' => 'This is the message that you get if you follow a link to a page or article that does not exist.',
'notitlematches' => 'Header of results page after a search for a title for which no page exists',
'textmatches' => 'When displaying search results',
'notextmatches' => 'Error message when there are no results',
'prevn' => "This is part of the navigation message on the top and bottom of Special pages (lists of things in alphabetical order, i.e. the 'Special:Categories' page), where it is used as the first argument of [[MediaWiki:Viewprevnext]].
It is also used by Category pages (which do ''not'' use [[MediaWiki:Viewprevnext]]).
$1 is the number of items shown per page. It is not used when $1 is zero; not sure what happens when $1 is one.
Whatlinkshere pages use [[MediaWiki:Whatlinkshere-prev]] instead (still as an argument to [[MediaWiki:Viewprevnext]]).

{{Identical|Previous}}",
'nextn' => "This is part of the navigation message on the top and bottom of Special pages (lists of things in alphabetical order, i.e. the 'Special:Categories' page), where it is used as the second argument of [[MediaWiki:Viewprevnext]].
It is also used by Category pages (which do ''not'' use [[MediaWiki:Viewprevnext]]).
$1 is the number of items shown per page. It is not used when $1 is zero; not sure what happens when $1 is one.
Whatlinkshere pages use [[MediaWiki:Whatlinkshere-next]] instead (still as an argument to [[MediaWiki:Viewprevnext]]).

{{Identical|Next $1}}",
'viewprevnext' => 'This is part of the navigation message on the top and bottom of Special pages which are lists of things, e.g. the User\'s contributions page (in date order) or the list of all categories (in alphabetical order). ($1) and ($2) are either [[MediaWiki:Pager-older-n]] and [[MediaWiki:Pager-newer-n]] (for date order) or [[MediaWiki:Prevn]] and [[MediaWiki:Nextn]] (for alphabetical order).
It is also used by Whatlinkshere pages, where ($1) and ($2) are [[MediaWiki:Whatlinkshere-prev]] and [[MediaWiki:Whatlinkshere-next]].
($3) is made up in all cases of the various proposed numbers of results per page, e.g. "(20 | 50 | 100 | 250 | 500)".
For Special pages, the navigation bar is prefixed by "([[MediaWiki:Page_first]] | [[MediaWiki:Page_last]])" (alphabetical order) or "([[MediaWiki:Histfirst]] | [[MediaWiki:Histlast]])" (date order).
Viewprevnext is sometimes preceded by the [[MediaWiki:Showingresults]] or [[MediaWiki:Showingresultsnum]] message (for Special pages) or by the [[MediaWiki:Linkshere]] message (for Whatlinkshere pages).',
'search-result-size' => 'Shown per line of a [[Special:Search|search result]]
* $1 is the size of the page in bytes, but no need to add "byte" or similar as the unit is added by special function.
* $2 is the sum of all words in this page.',
'search-result-score' => 'Shown per line of a [[Special:Search|search result]].

$1 is the relevance of this result in per cent.

{{Identical|Relevance: $1%}}',
'search-interwiki-default' => '* $1 is the hostname of the remote wiki from where the additional results listed below are returned',
'search-relatedarticle' => '{{Identical|Related}}',
'searchrelated' => '{{Identical|Related}}',
'searchall' => '{{Identical|All}}',
'showingresults' => "This message is used on some special pages such as 'Wanted categories'. $1 is the total number of results in the batch shown and $2 is the number of the first item listed.",
'showingresultsnum' => '$3 is the number of results on the page and $2 is the first number in the batch of results.',
'showingresultstotal' => 'Text above list of search results on special page of search results. 
* $1–$2 is the range of results shown on the page
* $3 is the total number of results from the search
* $4 is the number of results shown on the page (equal to the size of the $1–$2 interval)',
'nonefound' => 'This message appears on the search results page if no results are found.',
'powersearch' => 'Verb. Text of search button at the bottom of [[Special:Search]], for searching in selected namespaces.

{{Identical|Advanced search}}',
'powersearch-legend' => 'Advanced search

{{Identical|Advanced search}}',
'powersearch-ns' => 'Used in the extended search form at [[Special:Search]]',
'powersearch-redir' => 'Used in the extended search form at [[Special:Search]]',
'powersearch-field' => 'Used in the extended search form at [[Special:Search]]',
'search-external' => 'Legend of the fieldset for the input form when the internal search is disabled. Inside the fieldset [[MediaWiki:Searchdisabled]] and [[MediaWiki:Googlesearch]] is shown.',
'searchdisabled' => 'Shown on [[Special:Search]] when the internal search is disabled.',

# Preferences page
'preferences' => '{{Identical|Preferences}}',
'mypreferences' => '{{Identical|My preferences}}',
'prefs-edits' => 'In user preferences.',
'prefsnologin' => '{{Identical|Not logged in}}',
'qbsettings' => 'The title of the section in [[Special:Preferences]], only shown when using the skins "Standard/Classic" or "Cologne Blue". The quicbar is the same as the sidebar.',
'qbsettings-none' => '{{Identical|None}}',
'changepassword' => "Section heading on [[Special:Preferences]], tab 'User profile'.",
'skin' => 'Used in user preferences.',
'skin-preview' => 'The link beside each skin name in [[Special:Preferences|your user preferences]], tab "skin".

{{Identical|Preview}}',
'math' => 'Used in user preferences.',
'math_syntax_error' => '{{Identical|Syntax error}}',
'prefs-personal' => 'Title of a tab in [[Special:Preferences]].',
'prefs-rc' => 'Used in user preferences.

{{Identical|Recent changes}}',
'prefs-watchlist' => 'Used in user preferences.',
'prefs-watchlist-days' => 'Used in [[Special:Preferences]], tab "Watchlist".',
'prefs-watchlist-edits' => 'Used in [[Special:Preferences]], tab "Watchlist".',
'prefs-misc' => 'Used in user preferences.',
'saveprefs' => 'Button for saving changes in the preferences page.

{{Identical|Save}}',
'resetprefs' => 'Button for resetting changes in the preferences page.',
'oldpassword' => "Used on the 'User profile' tab of 'my preferences'. This is the text next to an entry box for the old password in the 'change password' section.",
'newpassword' => '{{Identical|New password}}',
'retypenew' => "Appears on the 'User profile' tab of the 'Preferences' special page in the 'Change password' section. It appears next to the text box for entering the new password a second time.",
'textboxsize' => 'Title of a tab in [[Special:Preferences]].',
'searchresultshead' => 'This is the label of the tab in [[Special:Preferences|my preferences]] which contains options for searching the wiki.

{{Identical|Search}}',
'stub-threshold' => 'Used in [[Special:Preferences]], tab "Misc".',
'recentchangesdays' => 'Used in [[Special:Preferences]], tab "Recent changes".',
'recentchangescount' => 'Used in [[Special:Preferences]], tab "Recent changes".',
'savedprefs' => 'This message appears after saving changes to your user preferences.',
'timezonetext' => "Additional explanation given in [[Special:Preferences]], tab 'date and time' with the preference in message ''timezoneoffset''",
'timezoneoffset' => "Text next to input box in [[Special:Preferences]], tab 'date and time', section 'timezone'.",
'allowemail' => 'Used in [[Special:Preferences]], tab "User profile".',
'prefs-searchoptions' => "Shown at the top of the tab 'Search' in [[Special:Preferences]]",
'prefs-namespaces' => "{{Identical|Namespaces}}
Shown as legend of the second fieldset of the tab 'Search' in [[Special:Preferences]]",
'defaultns' => 'Used in [[Special:Preferences]], tab "Search".',
'default' => '{{Identical|Default}}',
'files' => 'Title of a tab in [[Special:Preferences]].',

# User rights
'userrights' => 'Page title of [[Special:UserRights]].',
'userrights-lookup-user' => 'Button text when managing user rights',
'userrights-user-editname' => 'Displayed on [[Special:UserRights]].',
'editusergroup' => '{{Identical|Edit user groups}}',
'editinguser' => "Appears on [[Special:UserRights]]. The '''last part''' of the message '''should remain completely untranslated''', but if your language has S-O-V word order, the verb can follow it.",
'userrights-editusergroup' => '{{Identical|Edit user groups}}',
'saveusergroups' => 'Button text when editing user groups',
'userrights-groupsmember' => 'When editing user groups',
'userrights-groups-help' => 'Instructions displayed on [[Special:UserRights]].',
'userrights-reason' => 'Text beside log field when editing user groups',
'userrights-no-interwiki' => 'Error message when editing user groups',
'userrights-nodatabase' => 'Error message when editing user groups',
'userrights-nologin' => "Error displayed on [[Special:UserRights]] when you aren't logged in. If you are logged in, but don't have the correct permission, you see {{msg|userrights-notallowed|pl=yes}}.",
'userrights-notallowed' => "Error displayed on [[Special:UserRights]] when you don't have the permission.",
'userrights-changeable-col' => 'Used in [[Special:UserRights]].',
'userrights-unchangeable-col' => 'Used in [[Special:UserRights]].',

# Groups
'group' => '{{Identical|Group}}',
'group-user' => 'Name of group',
'group-autoconfirmed' => 'Name of group. On Wikimedia sites autoconfirmed users are users which are older than 4 days. After those 4 days, they have more rights.',
'group-bot' => 'Name of group',
'group-sysop' => 'Name of group',
'group-bureaucrat' => 'Name of group',
'group-suppress' => 'This is an optional (disabled by default) user group, meant for the [[mw:RevisionDelete|RevisionDelete]] feature, to change the visibility of revisions through [[Special:RevisionDelete]].

* See also: {{msg|Group-suppress-member|pl=yes}} for a member of this group.',
'group-all' => 'The name of the user group that contains all users, including anonymous users

{{Identical|All}}',

'group-user-member' => 'Name of member of group',
'group-autoconfirmed-member' => 'Name of a member of group',
'group-bot-member' => 'Name of a member of group',
'group-sysop-member' => 'Name of member of group',
'group-bureaucrat-member' => 'Name of member of group',
'group-suppress-member' => 'This is a member of the optional (disabled by default) user group, meant for the [[mw:RevisionDelete|RevisionDelete]] feature, to change the visibility of revisions through [[Special:RevisionDelete]].

* See also: {{msg|Group-suppress|pl=yes}} for the name of the group.',

'grouppage-user' => 'Link to group page on wiki',
'grouppage-autoconfirmed' => 'Link to group page on wiki.',
'grouppage-bot' => 'Link to project page of this group, displayed on [[Special:ListUsers/bot]].',
'grouppage-sysop' => 'Link to project page of this group, displayed on [[Special:ListUsers/sysop]].',
'grouppage-bureaucrat' => 'Name of project page of this group, linked to from [[Special:ListUsers/bureaucrat]], [[Special:ListGroupRights]], and some other special pages.',
'grouppage-suppress' => 'Link to project page of this group, displayed on [[Special:ListUsers/suppress]].',

# Rights
'right-read' => '{{doc-right}}
Basic right to read any page.',
'right-edit' => '{{doc-right}}
Basic right to edit pages that are not protected.',
'right-createpage' => '{{doc-right}}
Basic right to create pages. The right to edit discussion/talk pages is {{msg|right-createtalk|pl=yes}}.',
'right-createtalk' => '{{doc-right}}
Basic right to create discussion/talk pages. The right to edit other pages is {{msg|right-createpage|pl=yes}}.',
'right-createaccount' => '{{doc-right}}
The right to [[Special:CreateAccount|create a user account]].',
'right-minoredit' => '{{doc-right}}
The right to use the "This is a minor edit" checkbox. See {{msg|minoredit|pl=yes}} for the message used for that checkbox.',
'right-move' => '{{doc-right}}
The right to move any page that is not protected from moving.',
'right-upload' => '{{doc-right}}
The right to [[Special:Upload|upload]] a file (this includes images, media, audio, ...).',
'right-reupload' => '{{doc-right}}
The right to upload a file under a file name that already exists. Related messages: {{msg|right-upload|pl=yes}}, {{msg|right-reupload-own|pl=yes}} and {{msg|right-reupload-shared|pl=yes}}.',
'right-reupload-own' => '{{doc-right}}
Right to upload a file under a file name that already exists, and that the same user has uploaded. Related messages: {{msg|right-upload|pl=yes}} and {{msg|right-reupload|pl=yes}}.',
'right-reupload-shared' => '{{doc-right}}
The right to upload a file locally under a file name that already exists in a shared database (for example Commons). Related messages: {{msg|right-upload|pl=yes}} and {{msg|right-reupload|pl=yes}}.',
'right-purge' => '{{doc-right}}
The right to use <tt>&action=purge</tt> in the URL, without needing to confirm it (by default, anonymous users need to confirm it).',
'right-autoconfirmed' => "{{doc-right}}
If your account is older than [[mw:Manual:\$wgAutoConfirmAge|wgAutoConfirmAge]] and if you have at least [[mw:Manual:\$wgAutoConfirmCount|\$wgAutoConfirmCount]] edits, you are in the '''group \"autoconfirmed\"''' (note that you can't see this group at [[Special:ListUsers]]).
If you are in that group, you have (by default) the '''right \"autoconfirmed\"'''. With this right, you can for example <!-- I think this right includes more things --> edit semi-protected pages.",
'right-nominornewtalk' => '{{doc-right}}
If someone with this right (bots by default) edits a user talk page and marks it as minor (requires {{msg|right-minoredit|pl=yes}}), the user will not get a notification "You have new messages".',
'right-deleterevision' => 'This is a user right that is part of the [[mw:RevisionDelete|RevisionDelete]] feature.
It can be given to the group {{msg|group-sysop|pl=yes}}, although this right is disabled by default.

See also
* {{msg|right-suppressionlog|pl=yes}}
* {{msg|right-hideuser|pl=yes}}
* {{msg|right-suppressrevision|pl=yes}}',
'right-suppressrevision' => 'This is a user right that is part of the [[mw:RevisionDelete|RevisionDelete]] feature.
It can be given to the group {{msg|group-suppress|pl=yes}}, although that group is disabled by default.

See also
* {{msg|right-suppressionlog|pl=yes}}
* {{msg|right-hideuser|pl=yes}}
* {{msg|right-deleterevision|pl=yes}}',
'right-suppressionlog' => 'This is a user right that is part of the [[mw:RevisionDelete|RevisionDelete]] feature.
It can be given to the group {{msg|group-suppress|pl=yes}}, although that group is disabled by default.

See also
* {{msg|right-suppressrevision|pl=yes}}
* {{msg|right-hideuser|pl=yes}}
* {{msg|right-deleterevision|pl=yes}}',
'right-hideuser' => 'This is a user right that is part of the [[mw:RevisionDelete|RevisionDelete]] feature.
It can be given to the group {{msg|group-suppress|pl=yes}}, although that group is disabled by default.

See also
* {{msg|right-suppressionlog|pl=yes}}
* {{msg|right-suppressrevision|pl=yes}}
* {{msg|right-deleterevision|pl=yes}}',
'right-ipblock-exempt' => 'This user automatically 
bypasses IP blocks, auto-blocks and range blocks - so I presume - but I am uncertain',
'right-markbotedits' => '{{doc-right}}
A user with this right can mark a roll-back edit as a bot edit by adding <tt>&bot=1</tt> to the URL (not by default).',
'right-noratelimit' => '{{doc-right}}
The rate limits have no effect on the groups that have this right. Rate limits is a restriction that you can only do X actions (edits, moves, etc.) in Y number of seconds (set by [[mw:Manual:$wgRateLimits|$wgRateLimits]]).',

# User rights log
'rightslog' => 'In [[Special:Log]]',
'rightslogtext' => 'Text in [[Special:Log/rights]].',
'rightslogentry' => 'This message is displayed in the [[Special:Log/rights|User Rights Log]] when a bureaucrat changes the user groups for a user.

*Parameter $1 is the username
*Parameters $2 and $3 are lists of user groups or [[MediaWiki:Rightsnone]]',
'rightsnone' => 'Default rights for registered users.

{{Identical|None}}',

# Recent changes
'nchanges' => 'Appears on the [[Special:RecentChanges]] special page in brackets after pages having more than one change on that date. $1 is the number of changes on that day.',
'recentchanges' => 'The text of the link in sidebar going to the special page [[Special:RecentChanges]]. Also the page title of that special page.

{{Identical|Recent changes}}',
'recentchanges-legend' => 'Legend of the fieldset of [[Special:RecentChanges]]',
'recentchangestext' => 'Text in recent changes',
'rcnote' => 'Used on [[Special:RecentChanges]].
* $1 is the number of changes shown,
* $2 is the number of days for which the changes are shown,
* $3 is a datetime (deprecated),
* $4 is a date alone,
* $5 is a time alone.

 Example: "\'\'Below are the last 50 changes in the last 7 days, as of 14:48, 24 January 2008.\'\'"',
'rcnotefrom' => 'This message is displayed at [[Special:RecentChanges]] when viewing recentchanges from some specific time.

Parameter $1 is the maximum number of changes that are displayed.
Parameter $2 is a date and time.',
'rclistfrom' => 'Used on [[Special:RecentChanges]]. Parameter $1 is a date.',
'rcshowhideminor' => 'Option text in [[Special:RecentChanges]]',
'rcshowhidebots' => "Option text in [[Special:RecentChanges]]. $1 is the 'show/hide' command, with the text taken from either [[Mediawiki:Show]] or [[Mediawiki:Hide]].

{{Identical|$1 bots}}",
'rcshowhideliu' => 'Option text in [[Special:RecentChanges]]',
'rcshowhideanons' => 'Option text in [[Special:RecentChanges]]',
'rcshowhidepatr' => "Option text in [[Special:RecentChanges]]. $1 is the 'show/hide' command, with the text taken from either [[Mediawiki:Show]] or [[Mediawiki:Hide]].",
'rclinks' => "Used on [[Special:RecentChanges]]. \$1 is a list of different choices with number of pages to be shown, \$2 is a list of clickable links with a number of days for which recent changes are to be displayed. Example: \"''Show last 50 | 100 | 250 | 500 changes in last 1 | 3 | 7 | 14 | 30 days''\".

\$3 is a block of text that consists of other messages: \"''Hide minor edits | Show bots | Hide anonymous users | Hide logged-in users | Hide patrolled edits | Hide my edits''\"",
'diff' => 'Short form of "differences". Used on [[Special:RecentChanges]], [[Special:Watchlist]], ...',
'hist' => 'Short form of "history". Used on [[Special:RecentChanges]], [[Special:Watchlist]], ...',
'hide' => 'Option text in [[Special:RecentChanges]]

{{Identical|Hide}}',
'show' => '{{Identical|Show}}',
'minoreditletter' => "Very short form of \"'''minor edit'''\". Used in [[Special:RecentChanges]], [[Special:Watchlist]], [[Special:Contributions]] and history pages.",
'newpageletter' => "Very short form of \"'''new page'''\". Used in [[Special:RecentChanges]], [[Special:Watchlist]] and [[Special:Contributions]].",
'boteditletter' => 'Abbreviation of "bot". Appears in [[Special:RecentChanges]] and [[Special:Watchlist]].',
'newsectionsummary' => 'Default summary when adding a new section to a page.',

# Recent changes linked
'recentchangeslinked' => 'Title of [[Special:RecentChangesLinked]].',
'recentchangeslinked-title' => 'Message used as title and page header on [[Special:RecentChangesLinked]] (needs an argument like "/Main Page"). Related changes are all recent change to pages that are linked from \'\'this page\'\'. "$1" is the name of the page for which related changes as show.',
'recentchangeslinked-summary' => 'Summary of [[Special:RecentChangesLinked]].',
'recentchangeslinked-page' => '{{Identical|Page name}}',

# Upload
'upload' => 'Display name for link to [[Special:Upload]] for uploading files to the wiki.

{{Identical|Upload file}}',
'uploadbtn' => 'Button name in [[Special:Upload]].

{{Identical|Upload file}}',
'uploadnologin' => '{{Identical|Not logged in}}',
'uploadtext' => 'Text displayed when uploading a file using [[Special:Upload]].',
'upload-permitted' => 'Used in [[Special:Upload]].',
'upload-preferred' => 'Used in [[Special:Upload]].',
'upload-prohibited' => 'Used in [[Special:Upload]].',
'uploadlogpage' => 'Page title of [[Special:Log/upload]].',
'filename' => '{{Identical|Filename}}',
'filedesc' => '{{Identical|Summary}}',
'fileuploadsummary' => '{{Identical|Summary}}',
'filesource' => 'On page [[Special:Upload]] if defined $wgUseCopyrightUpload for detailed copyright information forms. This is source of file.

{{Identical|Source}}',
'ignorewarnings' => 'In [[Special:Upload]]',
'filetype-unwanted-type' => "* $1 is the extension of the file which cannot be uploaded
* $2 is the list of file extensions that can be uploaded (Example: ''png, gif, jpg, jpeg, ogg, pdf, svg.'')
* $3 is the number of allowed file formats (to be used for the PLURAL function)",
'filetype-banned-type' => "* $1 is the extension of the file which cannot be uploaded
* $2 is the list of file extensions that can be uploaded (Example: ''png, gif, jpg, jpeg, ogg, pdf, svg.'')
* $3 is the number of allowed file formats (to be used for the PLURAL function)",
'filetype-missing' => 'Error when uploading a file with no extension',
'large-file' => 'Variables $1 and $2 have appropriate unit symbols already. See for example [[Mediawiki:size-kilobytes]].',
'largefileserver' => 'Error message when uploading a file whose size is larger than the maximum allowed',
'emptyfile' => 'Error message when trying to upload an empty file',
'filepageexists' => 'Shown on [[Special:Upload]], $1 is link to the page. This message is displayed if a description page exists, but a file with the same name does not yet exists, and a user tries to upload a file with that name. In that case the description page is not changed, even if the uploading user specifies a description with the upload.',
'file-thumbnail-no' => 'Error message at [[Special:Upload]]',
'fileexists-shared-forbidden' => 'Error message at [[Special:Upload]]',
'savefile' => 'When uploading a file',
'overwroteimage' => 'Log text when uploading a new version of a file',
'uploaddisabledtext' => 'This message can have parameter $1, which contains the name of the target file. See r22243 and [https://bugzilla.wikimedia.org/show_bug.cgi?id=8818 bug 8818].',
'uploadvirus' => 'Note displayed when uploaded file contains a virus',
'sourcefilename' => 'In [[Special:Upload]]',
'destfilename' => 'In [[Special:Upload]]',
'upload-maxfilesize' => 'Shows at [[Special:Upload]] the maximum file size that can be uploaded.

$1 is the value in KB/MB/GB',
'watchthisupload' => 'In [[Special:Upload]]

{{Identical|Watch this page}}',
'filewasdeleted' => 'This warning is shown when trying to upload a file that does not exist, but has previously been deleted.
Parameter $1 is a link to the deletion log, with the text in {{msg|deletionlog}}.',

'upload-file-error' => '{{Identical|Internal error}}',

'nolicense' => '{{Identical|None selected}}',
'license-nopreview' => 'Error message when a certain license does not exist',

# Special:ImageList
'imagelist-summary' => 'This message is displayed at the top of [[Special:ImageList]] to explain how to use that special page.',
'imgfile' => '{{Identical|File}}',
'imagelist' => '{{Identical|File list}}',
'imagelist_name' => '{{Identical|Name}}',
'imagelist_user' => '{{Identical|User}}',
'imagelist_description' => '{{Identical|Description}}',

# Image description page
'filehist' => 'Text shown on a media description page. Heads the section where the different versions of the file are displayed.',
'filehist-help' => 'In file description page',
'filehist-deleteall' => 'Link in image description page for admins.',
'filehist-deleteone' => 'Link description on file description page to delete an earlier version of a file.

{{Identical|Delete}}',
'filehist-revert' => 'Link in image description page.

{{Identical|Revert}}',
'filehist-current' => 'Link in file description page.

{{Identical|Current}}',
'filehist-datetime' => 'Used on image descriptions, see for example [[:Image:Yes.png#filehistory]].',
'filehist-thumb' => 'Shown in the file history list of a file desription page.

Example: [[:Image:Addon-icn.png]]',
'filehist-thumbtext' => 'Shown in the file history list of a file description page.

Example: [[wikipedia:Image:Madeleine close2.jpg]]',
'filehist-nothumb' => 'Shown if no thumbnail is available in the file history list of a file desription page.

Example: [[:Image:Addon-icn.png]]',
'filehist-user' => 'In image description page.

{{Identical|User}}',
'filehist-dimensions' => 'In file description page',
'filehist-filesize' => 'In image description page',
'filehist-comment' => 'In file description page

{{Identical|Comment}}',
'imagelinks' => 'In image description page

{{Identical|Links}}',
'linkstoimage' => 'Used on image description, see for example [[:Image:Yes.png#filelinks]].
* Parameter $1 is the number of pages that link to the file/image.',
'linkstoimage-more' => 'Shown on an image description page when a file is used/linked more than 100 times on other pages.

* $1: limit. At the moment hardcoded at 100
* $2: filename',
'nolinkstoimage' => 'Displayed on image description pages, see for exampe [[:Image:Tournesol.png#filelinks]].',
'redirectstofile' => 'Used on file description pages after the list of pages which used this file',
'sharedupload' => 'Shown on an image description page when it is used in a central repository (i.e. [http://commons.wikimedia.org/ Commons] for Wikimedia wikis).
{{doc-important|Do not customise this message. Just translate it.|Customisation should be done by local wikis.}}',
'shareduploadwiki' => 'The variable $1 is {{msg|shareduploadwiki-linktext}}',
'shareduploadwiki-desc' => "This message appears after [[MediaWiki:Sharedupload]]. \$1 is a link to the file description page on the shared repository with [[MediaWiki:Shareduploadwiki-linktext]] as display text. Example: \"''The description on its file description page there is shown below.''\"",
'shareduploadwiki-linktext' => 'This message is used as variable $1 in [[MediaWiki:Shareduploadwiki]] and in [[MediaWiki:Shareduploadwiki-desc]]',
'shareduploadduplicate' => '$1 is contents of message shareduploadduplicate-linktext (i.e. "another file")',
'shareduploadduplicate-linktext' => '{{Identical|Another file}}',
'shareduploadconflict' => '$1 is contents of message shareduploadconflict-linktext (i.e. "another file")',
'shareduploadconflict-linktext' => '{{Identical|Another file}}',
'noimage' => 'In image description page when there is no file by that name.  The variable $1 comes from {{msg|noimage-linktext}}.',
'noimage-linktext' => 'This message is used as a variable in {{msg|noimage}}.',
'imagepage-searchdupe' => 'This message is used as text on a link from image pages to [[Special:FileDuplicateSearch]].',

# File reversion
'filerevert-intro' => 'Message displayed when you try to revert a version of a file.
* $1 is the name of the media
* $2 is a date
* $3 is a hour
* $4 is an URL and must follow square bracket: [$4',
'filerevert-comment' => '{{Identical|Comment}}',
'filerevert-defaultcomment' => '* $1 is a date
* $2 is an hour',
'filerevert-submit' => '{{Identical|Revert}}',
'filerevert-success' => 'Message displayed when you succeed in reverting a version of a file.
* $1 is the name of the media
* $2 is a date
* $3 is a hour
* $4 is an URL and must follow square bracket: [$4',

# File deletion
'filedelete-intro-old' => 'Message displayed when you try to delete a version of a file.
* $1 is the name of the media
* $2 is a date
* $3 is a hour
* $4 is an URL and must follow square bracket: [$4',
'filedelete-comment' => '{{Identical|Reason for deletion}}',
'filedelete-submit' => 'Delete button when deleting a file for admins

{{Identical|Delete}}',
'filedelete-success-old' => 'Message displayed when you succeed in deleting a version of a file.
* $1 is the name of the media
* $2 is a date
* $3 is a hour',
'filedelete-otherreason' => 'Message used when deleting a file. This is the description field for "Other/additional reason" for deletion.

{{Identical|Other/additional reason}}',
'filedelete-reason-otherlist' => 'Message used as default in the dropdown menu in the form for deleting a file. Keeping this message selected assumes that a reason for deletion is specified in the field below.

{{Identical|Other reason}}',
'filedelete-reason-dropdown' => 'Predefined reasons for deleting a file that can be selected in a drop down list. Entries prefixed with one asterisk ("*") are group headers and cannot be selected. Entries prefixed with two asterisks can be selected as reason for deletion.',
'filedelete-edit-reasonlist' => 'Shown beneath the file deletion form on the right side. It is a link to [[MediaWiki:Filedelete-reason-dropdown]].

{{Identical|Edit delete reasons}}',

# MIME search
'mimesearch' => 'Title of [[Special:MIMESearch]].',
'mimesearch-summary' => 'Text for [[Special:MIMESearch]]',
'download' => 'Direct download link in each line returned by [[Special:MIMESearch]]. Points to the actual file, rather than the image description page.',

# Unwatched pages
'unwatchedpages' => 'Name of special page displayed in [[Special:SpecialPages]] for admins',

# List redirects
'listredirects' => 'Name of special page displayed in [[Special:SpecialPages]].',

# Unused templates
'unusedtemplates' => 'Name of special page displayed in [[Special:SpecialPages]].',
'unusedtemplatestext' => 'Shown on top of [[Special:Unusedtemplates]]',

# Random page
'randompage' => 'Name of special page displayed in [[Special:SpecialPages]].

{{Identical|Random page}}',

# Random redirect
'randomredirect' => 'Name of special page displayed in [[Special:SpecialPages]].',

# Statistics
'statistics' => 'Name of special page displayed in [[Special:SpecialPages]].

{{Identical|Statistics}}',
'statistics-header-pages' => 'Used in [[Special:Statistics]]',
'statistics-header-edits' => 'Used in [[Special:Statistics]]',
'statistics-header-views' => 'Used in [[Special:Statistics]]',
'statistics-header-users' => 'Used in [[Special:Statistics]]',
'statistics-articles' => 'Used in [[Special:Statistics]]',
'statistics-pages' => 'Used in [[Special:Statistics]]',
'statistics-files' => 'Used in [[Special:Statistics]]',
'statistics-edits' => 'Used in [[Special:Statistics]]',
'statistics-edits-average' => 'Used in [[Special:Statistics]]',
'statistics-views-total' => 'Used in [[Special:Statistics]]',
'statistics-views-peredit' => 'Used in [[Special:Statistics]]',
'statistics-jobqueue' => 'Used in [[Special:Statistics]]',
'statistics-users' => 'Used in [[Special:Statistics]]',
'statistics-users-active' => 'Used in [[Special:Statistics]]',
'statistics-mostpopular' => 'Used in [[Special:Statistics]]',

'disambiguations' => 'Name of a special page displayed in [[Special:SpecialPages]].',
'disambiguationspage' => 'This message is the name of the template used for marking disambiguation pages. It is used by [[Special:Disambiguations]] to find all pages that links to disambiguation pages.

{{doc-important|Don\'t translate the "Template:" part!}}',
'disambiguations-text' => "This block of text is shown on [[:Special:Disambiguations]].

* '''Note:''' Do not change the link [[MediaWiki:Disambiguationspage]], even because it is listed as problematic. Be sure the \"D\" is in uppercase, so not \"d\".

* '''Background information:''' Beyond telling about links going to disambiguation pages, that they are generally bad, it should explain which pages in the article namespace are seen as diambiguations: [[MediaWiki:Disambiguationspage]] usually holds a list of diambiguation templates of the local wiki. Pages linking to one of them (by transclusion) will count as disambiguation pages. Pages linking to these disambiguation pages, instead to the disambiguated article itself, are listed on [[:Special:Disambiguations]].",

'doubleredirects' => 'Name of [[Special:DoubleRedirects]] displayed in [[Special:SpecialPages]]',
'doubleredirectstext' => 'Shown on top of [[Special:Doubleredirects]]',
'double-redirect-fixed-move' => 'This is the message in the log when the software (under the username {{msg|double-redirect-fixer}}) updates the redirects after a page move. See also {{msg|fix-double-redirects}}.',
'double-redirect-fixer' => "This is the '''username''' of the user who updates the double redirects after a page move. A user is created with this username, so it is perhaps better to not change this message too often. See also {{msg|double-redirect-fixed-move}} and {{msg|fix-double-redirects}}.",

'brokenredirects' => 'Name of [[Special:BrokenRedirects]] displayed in [[Special:SpecialPages]]',
'brokenredirectstext' => 'Shown on top of [[Special:Brokenredirects]].',
'brokenredirects-edit' => 'Link in [[Special:BrokenRedirects]]

{{Identical|Edit}}',
'brokenredirects-delete' => 'Link in [[Special:BrokenRedirects]] for admins

{{Identical|Delete}}',

'withoutinterwiki' => 'The title of the special page [[Special:WithoutInterwiki]].',
'withoutinterwiki-summary' => 'Summary of [[Special:WithoutInterwiki]].',
'withoutinterwiki-legend' => 'Used on [[Special:WithoutInterwiki]] as title of fieldset.',
'withoutinterwiki-submit' => '{{Identical|Show}}',

'fewestrevisions' => 'Name of a special page displayed in [[Special:SpecialPages]].',

# Miscellaneous special pages
'nbytes' => 'Message used on the history page of a wiki page. Each version of a page consist of a number of bytes. $1 is the number of bytes that the page uses. Uses plural as configured for a language based on $1.',
'ncategories' => "Used in the special page '[[Special:MostCategories]]' in brackets after each entry on the list signifying how many categories a page is part of. $1 is the number of categories.",
'nlinks' => 'This appears in brackets after each entry on the special page [[Special:MostLinked]]. $1 is the number of wiki links.',
'nmembers' => 'Appears in brackets after each category listed on the special page [[Special:WantedCategories]]. $1 is the number of members of the category.',
'nrevisions' => 'Number of revisions.',
'nviews' => 'This message is used on [[Special:PopularPages]] to say how many times each page has been viewed. Parameter $1 is the number of views.',
'specialpage-empty' => 'Used on a special page when there is no data. For example on [[Special:Unusedimages]] when all images are used.',
'lonelypages' => 'Name of [[Special:LonelyPages]] displayed in [[Special:SpecialPages]]',
'lonelypagestext' => 'Text displayed in [[Special:LonelyPages]]',
'uncategorizedpages' => 'Name of a special page displayed in [[Special:SpecialPages]].',
'uncategorizedcategories' => 'Name of special page displayed in [[Special:SpecialPages]]',
'uncategorizedimages' => 'The title of the special page [[Special:UncategorizedImages]].',
'uncategorizedtemplates' => 'The title of the special page [[Special:UncategorizedTemplates]].',
'unusedcategories' => 'Name of special page displayed in [[Special:SpecialPages]]',
'unusedimages' => 'Name of special page displayed in [[Special:SpecialPages]]',
'popularpages' => 'Name of special page displayed in [[Special:SpecialPages]]',
'wantedcategories' => 'Name of special page displayed in [[Special:SpecialPages]]',
'wantedpages' => 'Name of special page displayed in [[Special:SpecialPages]]',
'wantedfiles' => 'Name of special page displayed in [[Special:SpecialPages]] and title of [[Special:WantedFiles]].',
'mostlinked' => 'Name of special page displayed in [[Special:SpecialPages]]',
'mostlinkedcategories' => 'Name of special page displayed in [[Special:SpecialPages]]',
'mostlinkedtemplates' => 'Name of special page displayed in [[Special:SpecialPages]]',
'mostcategories' => 'Name of special page displayed in [[Special:SpecialPages]]',
'mostimages' => 'Name of special page displayed in [[Special:SpecialPages]]',
'mostrevisions' => 'Name of special page displayed in [[Special:SpecialPages]]',
'prefixindex' => 'Name of special page displayed in [[Special:SpecialPages]]',
'shortpages' => 'Name of special page displayed in [[Special:SpecialPages]]',
'longpages' => 'Name of special page displayed in [[Special:SpecialPages]]',
'deadendpages' => 'Name of special page displayed in [[Special:SpecialPages]]',
'deadendpagestext' => 'Introductory text for [[Special:DeadendPages]]',
'protectedpages' => 'Name of special page displayed in [[Special:SpecialPages]]',
'protectedpages-indef' => 'Option in [[Special:ProtectedPages]]',
'protectedpages-cascade' => 'Option in [[Special:ProtectedPages]]',
'protectedpagestext' => 'Shown on top of [[Special:ProtectedPages]]',
'protectedtitles' => 'Name of special page displayed in [[Special:SpecialPages]]',
'protectedtitlestext' => 'Shown on top of [[Special:Protectedtitles]]',
'listusers' => 'Name of special page displayed in [[Special:SpecialPages]]',
'listusers-editsonly' => 'Option in [[Special:ListUsers]].',
'newpages' => 'Name of special page displayed in [[Special:SpecialPages]]',
'newpages-username' => '{{Identical|Username}}',
'ancientpages' => 'Name of special page displayed in [[Special:SpecialPages]]',
'move' => 'Name of Move tab. Should be in the imperative mood.

{{Identical|Move}}',
'movethispage' => '{{Identical|Move this page}}',
'pager-newer-n' => "This is part of the navigation message on the top and bottom of Special pages which are lists of things in date order, e.g. the User's contributions page. It is passed as the second argument of [[MediaWiki:Viewprevnext]]. $1 is the number of items shown per page.",
'pager-older-n' => "This is part of the navigation message on the top and bottom of Special pages which are lists of things in date order, e.g. the User's contributions page. It is passed as the first argument of [[MediaWiki:Viewprevnext]]. $1 is the number of items shown per page.",

# Book sources
'booksources' => 'Name of special page displayed in [[Special:SpecialPages]]',
'booksources-go' => 'Name of button in [[Special:BookSources]]

{{Identical|Go}}',

# Special:Log
'specialloguserlabel' => 'Used in [[Special:Log]].

{{Identical|User}}',
'speciallogtitlelabel' => 'Used in [[Special:Log]].

{{Identical|Title}}',
'log' => 'Name of special page displayed in [[Special:SpecialPages]]',
'all-logs-page' => 'Title of [[Special:Log]].',
'alllogstext' => 'Header of [[Special:Log]]',

# Special:AllPages
'allpages' => 'Name of special page displayed in [[Special:AllPages]]

{{Identical|All pages}}',
'alphaindexline' => 'Used on [[Special:AllPages]] if the main namespace contains more than 960 pages. Indicates the page range displayed behind the link. "from page $1 to page $2". $1 is the source page name. $1 is the target page name.',
'nextpage' => 'Third part of the navigation bar for the special page [[Special:AllPages]]. $1 is a page title. The other parts are [[MediaWiki:Allarticles]] and [[MediaWiki:Prevpage]].

{{Identical|Next page}}',
'prevpage' => 'Second part of the navigation bar for the special page [[Special:AllPages]]. $1 is a page title. The other parts are [[MediaWiki:Allarticles]] and [[MediaWiki:Nextpage]].

{{Identical|Previous page}}',
'allpagesfrom' => 'Option in [[Special:AllPages]]. See also {{msg|allpagesto}}.',
'allpagesto' => 'Option in [[Special:AllPages]]. See also {{msg|allpagesfrom}}.',
'allarticles' => 'First part of the navigation bar for the special page [[Special:AllPages]]. The other parts are [[MediaWiki:Prevpage]] and [[MediaWiki:Nextpage]].
{{Identical|All pages}}',
'allpagesprev' => "Allegedly used in [[Special:AllPages]], although I haven't seen it.

{{Identical|Previous}}",
'allpagesnext' => "Allegedly used in [[Special:AllPages]], although I haven't seen it.

{{Identical|Next}}",
'allpagessubmit' => 'Text on submit button in [[Special:AllPages]], [[Special:RecentChanges]], [[Special:RecentChangesLinked]], [[Special:NewPages]], [[Special:Log]], [[Special:ListUsers]], [[Special:ProtectedPages]], [[Special:ProtectedTitles]], [[Special:WhatLinksHere]] and [[Special:Watchlist]].

{{Identical|Go}}',

# Special:Categories
'categories' => '{{Identical|Categories}}',
'categoriespagetext' => "Text displayed in [[Special:Categories]]. Do not translate or change links. In order to translate ''Unused categories'' and ''wanted categories'' see {{msg|unusedcategories}} and {{msg|wantedcategories}}.",
'special-categories-sort-count' => 'This message is used on [[Special:Categories]] to sort the list by the number of members in the categories.',

# Special:LinkSearch
'linksearch-ns' => '{{Identical|Namespace}}',
'linksearch-ok' => '{{Identical|Search}}',

# Special:ListUsers
'listusers-submit' => '{{Identical|Show}}',

# Special:Log/newusers
'newuserlogpage' => 'Part of the "Newuserlog" extension. It is both the title of [[Special:Log/newusers]] and the link you can see in the recent changes.',
'newuserlogpagetext' => 'Part of the "Newuserlog" extension. It is the description you can see on [[Special:Log/newusers]].',
'newuserlog-create-entry' => 'Part of the "Newuserlog" extension. It is the summary in the [[Special:RecentChanges|recent changes]] and on [[Special:Log/newusers]].',
'newuserlog-create2-entry' => 'Part of the "Newuserlog" extension. It is the summary in the [[Special:RecentChanges|recent changes]] and on [[Special:Log/newusers]] when creating an account for someone else ("$1").',
'newuserlog-autocreate-entry' => 'This message is used in the [[:mw:Extension:Newuserlog|new user log]] to mark an account that was created by MediaWiki as part of a [[:mw:Extension:CentralAuth|CentralAuth]] global account.',

# Special:ListGroupRights
'listgrouprights' => 'The name of the special page [[Special:ListGroupRights]].',
'listgrouprights-summary' => 'The description used on [[Special:ListGroupRights]].',
'listgrouprights-group' => "The title of the column in the table, about user groups (like you are in the ''translator'' group).

{{Identical|Group}}",
'listgrouprights-rights' => "The title of the column in the table, about user rights (like you can ''edit'' this page).",
'listgrouprights-helppage' => "The link used on [[Special:ListGroupRights]]. Just translate \"Group rights\", and '''leave the \"Help:\" namespace exactly as it is'''.",
'listgrouprights-members' => 'Used on [[Special:ListGroupRights]] and [[Special:Statistics]] as a link to [[Special:ListUsers|Special:ListUsers/"group"]], a list of members in that group.',
'listgrouprights-addgroup' => 'Used on [[Special:ListGroupRights]]. See also {{msg|listgrouprights-removegroup}}.',
'listgrouprights-removegroup' => 'Used on [[Special:ListGroupRights]]. See also {{msg|listgrouprights-addgroup}}.',
'listgrouprights-addgroup-all' => '{{doc-right}}',
'listgrouprights-removegroup-all' => '{{doc-right}}',

# E-mail user
'emailuser' => 'Link in the sidebar',
'emailpagetext' => 'This is the text that is displayed above the e-mail form on Special:EmailUser.',
'email-legend' => 'Title of the box in [[Special:EmailUser]]',
'emailfrom' => 'Field in [[Special:EmailUser]].',
'emailto' => 'Field in [[Special:EmailUser]].',
'emailsubject' => 'Field in [[Special:EmailUser]].

{{Identical|Subject}}',
'emailmessage' => 'Field in [[Special:EmailUser]].

{{Identical|Message}}',
'emailsend' => 'Button name in [[Special:EmailUser]].

{{Identical|Send}}',
'emailuserfooter' => 'This message is appended to every email sent through the "Email user" function.

* $1: username of the sender
* $2: username of the recipient',

# Watchlist
'watchlist' => '{{Identical|My watchlist}}',
'mywatchlist' => 'Link at the upper right corner of the screen.

{{Identical|My watchlist}}',
'watchlistfor' => 'Subtitle on [[Special:Watchlist]].

*$1: Username of current user
{{Identical|For $1}}',
'nowatchlist' => 'Displayed when there is no pages in the watchlist.',
'watchnologin' => '{{Identical|Not logged in}}',
'addedwatch' => 'Page title displayed when clicking on {{msg|watch}} tab (only when not using the AJAX feauture which allows watching a page without reloading the page or such). See also {{msg|addedwatchtext}}.',
'addedwatchtext' => 'Explanation shown when clicking on the {{msg|watch}} tab. See also {{msg|addedwatch}}.',
'removedwatch' => 'Page title displayed when clicking on {{msg|unwatch}} tab (only when not using the AJAX feauture which allows watching a page without reloading the page or such). See also {{msg|removedwatchtext}}.',
'removedwatchtext' => "After a page has been removed from a user's watchlist by clicking the {{msg|unwatch}} tab at the top of an article, this message appears just below the title of the article. $1 is the title of the article. See also {{msg|removedwatch}} and {{msg|addedwatchtext}}.",
'watch' => 'Name of the Watch tab. Should be in the imperative mood.',
'watchthispage' => '{{Identical|Watch this page}}',
'unwatch' => 'Name of "Unwatch" tab.',
'watchlist-details' => 'Message on Special page: My watchlist. This is paired with the message [[Mediawiki:Nowatchlist]] which appears instead of Watchlist-details when $1 is 0.',
'wlheader-showupdated' => 'This message shows up near top of users watchlist page.',
'wlshowlast' => "Appears on [[Special:Watchlist]]. Variable $1 gives a choice of different numbers of hours, $2 gives a choice of different numbers of days and $3 is 'all' ([[Mediawiki:watchlistall2]]). Clicking on your choice changes the list of changes you see (without changing the default in my preferences).",
'watchlist-show-bots' => 'Option in [[Special:Watchlist]].',
'watchlist-hide-bots' => 'Option in [[Special:Watchlist]].',
'watchlist-show-own' => 'Option in [[Special:Watchlist]].',
'watchlist-hide-own' => 'Option in [[Special:Watchlist]].',
'watchlist-show-minor' => 'Option in [[Special:Watchlist]].',
'watchlist-hide-minor' => 'Option in [[Special:Watchlist]].',
'watchlist-show-anons' => 'Option in [[Special:Watchlist]]',
'watchlist-hide-anons' => 'Option in [[Special:Watchlist]]',
'watchlist-show-liu' => 'Option in [[Special:Watchlist]]',
'watchlist-hide-liu' => 'Option in [[Special:Watchlist]]',
'watchlist-options' => 'Legend of the fieldset of [[Special:Watchlist]]',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'Text displayed when clicked on the watch tab: [[MediaWiki:Watch/{{SUBPAGENAME}}|{{int:watch}}]]. It means the wiki is adding that page to your watchlist.',
'unwatching' => 'Text displayed when clicked on the unwatch tab: [[MediaWiki:Unwatch/{{SUBPAGENAME}}|{{int:unwatch}}]]. It means the wiki is removing that page from your watchlist.',

# Delete/protect/revert
'confirm' => 'Submit button text for protection confirmation

{{Identical|Confirm}}',
'excontent' => 'Automated deletion reason when deleting a page for admins',
'excontentauthor' => 'Automated deletion reason when deleting a page for admins providing that the page has one author only.',
'exbeforeblank' => 'Automated deletion reason when deleting a page for admins providing that the page was blanked before deletion.',
'delete-confirm' => 'The title of the form to delete a page.

$1 = the name of the page',
'delete-legend' => '{{Identical|Delete}}',
'historywarning' => 'Warning when about to delete a page that has history.',
'confirmdeletetext' => 'Introduction shown when deleting a page.',
'actioncomplete' => 'Used in several situations, for example when a page has been deleted.',
'deletedarticle' => "This is a ''logentry'' message. $1 is deleted page name.",
'dellogpage' => 'The name of the deletion log. Used as heading on [[Special:Log/delete]] and in the drop down menu for selecting logs on [[Special:Log]].

{{Identical|Deletion log}}',
'dellogpagetext' => 'Text in [[Special:Log/delete]].',
'deletionlog' => 'This message is used to link to the deletion log as parameter $1 of {{msg|Filewasdeleted}} and as parameter $2 of {{msg|deletedtext}}.

{{Identical|Deletion log}}',
'deletecomment' => '{{Identical|Reason for deletion}}',
'deleteotherreason' => '{{Identical|Other/additional reason}}',
'deletereasonotherlist' => '{{Identical|Other reason}}',
'deletereason-dropdown' => 'Default reasons for deletion.',
'delete-edit-reasonlist' => 'Shown beneath the page deletion form on the right side. It is a link to [[MediaWiki:Deletereason-dropdown]].

{{Identical|Edit delete reasons}}',
'rollback_short' => '{{Identical|Rollback}}',
'rollbacklink' => '{{Identical|Rollback}}',
'rollback-success' => 'This message shows up on screen after successful revert (generally visible only to admins). $1 describes user whose changes have been reverted, $2 describes user which produced version, which replaces reverted version.',
'protectlogpage' => 'Title of [[Special:Log/protect]].',
'protectlogtext' => 'Text in [[Special:Log/protect]].',
'protect-title' => 'Title for the protection form. $1 is the title of the page to be (un)protected.',
'protect-backlink' => 'Optional message. Translate it only if you have to change it, i.e. for RTL wikis

Shown as subtitle of the protection form. $1 is the title of the page to be (un)protected.',
'protect-legend' => 'Legend of the fieldset around the input form of the protection form.',
'protectcomment' => '{{Identical|Comment}}',
'protectexpiry' => '{{Identical|Expires}}',
'protect-unchain' => 'Used for a checkbox to be able to change move permissions. See [[meta:Protect]] for more information.',
'protect-text' => 'Intro of the protection interface. See [[meta:Protect]] for more information.',
'protect-default' => '{{Identical|Default}}',
'protect-fallback' => 'This message is used as an option in the protection form on wikis were extra protection levels have been configured.',
'protect-summary-cascade' => 'Used in edit summary when cascade protecting a page.',
'protect-expiring' => 'Used in page history.

{{Identical|Expires $1 (UTC)}}',
'protect-cascade' => 'See [[meta:Protect]] for more information.',
'protect-othertime' => 'Used on the page protection form as label for the following input field (text)
{{Identical|Other time}}',
'protect-othertime-op' => 'Used on the page protection form in the drop down menu
{{Identical|Other time}}',
'protect-otherreason' => 'Shown on the page protection form as label for the following input field (text)
{{Identical|Other/additional reason}}',
'protect-otherreason-op' => 'Shown on the page protection form in the drop down menu
{{Identical|Other/additional reason}}',
'protect-dropdown' => 'Shown on the page protection form as drop down menu for protection reasons.

<tt><nowiki>* Groupname</nowiki></tt> - defines a new group<br />
<tt><nowiki>** Reason</nowiki></tt> - defines a reason in this group',
'restriction-type' => 'Used on [[Special:ProtectedPages]].',
'restriction-level' => 'Used on [[Special:ProtectedPages]].',

# Restrictions (nouns)
'restriction-edit' => "Used on [[Special:ProtectedPages]]. Option in the 'permission' drop-down box.

{{Identical|Edit}}",
'restriction-move' => "Used on [[Special:ProtectedPages]]. Option in the 'permission' drop-down box.

{{Identical|Move}}",
'restriction-create' => '{{Identical|Create}}',

# Restriction levels
'restriction-level-sysop' => "Used on [[Special:ProtectedPages]]. An option in the drop-down box 'Restriction level'.",
'restriction-level-all' => "Used on [[Special:ProtectedPages]]. An option in the drop-down box 'Restriction level'.",

# Undelete
'undelete' => 'Name of special page for admins as displayed in [[Special:SpecialPages]].

{{Identical|View deleted pages}}',
'undeletepage' => 'Title of special page [[Special:Undelete]]. This special page is only visible to administrators.',
'viewdeletedpage' => '{{Identical|View deleted pages}}',
'undeleteextrahelp' => "Help message displayed when restoring history of a page. In your language, ''Restore'' is called ''[[MediaWiki:Undeletebtn/{{SUBPAGENAME}}|{{int:Undeletebtn}}]]'' ({{msg|Undeletebtn}}), ''Reset'' button is called ''[[MediaWiki:Undeletereset/{{SUBPAGENAME}}|{{int:Undeletereset}}]]'' ({{msg|Undeletereset}}).",
'undelete-revision' => 'Shown in "View and restore deleted pages" ([[Special:Undelete/$1]]).

* $1: deleted page name
* $3: user name (author of revision, not who deleted it)
* $4: date of the revision
* $5: time of the revision

\'\'Example:\'\' Deleted revision of [[Main Page]] (as of {{CURRENTDAY}} {{CURRENTMONTHNAME}} {{CURRENTYEAR}} on {{CURRENTTIME}}) by [[User:Username|Username]]:',
'undeletebtn' => 'Shown on [[Special:Undelete]] as button caption and on [[Special:Log/delete|deletion log]] after each entry (for sysops).

{{Identical|Restore}}',
'undeletelink' => 'Display name of link to undelete a page used on [[Special:Log/delete]]

{{Identical|Restore}}',
'undeletereset' => 'Shown on [[Special:Undelete]] as button caption.
{{Identical|Reset}}',
'undeletecomment' => '{{Identical|Comment}}',
'undelete-search-submit' => '{{Identical|Search}}',

# Namespace form on various pages
'namespace' => '{{Identical|Namespace}}',
'invert' => 'Displayed in [[Special:RecentChanges]].',
'blanknamespace' => 'Name for main namespace (blank namespace) in drop-down menus at [[Special:RecentChanges]] and other special pages.',

# Contributions
'contributions' => "Display name for the 'User contributions', shown in the sidebar menu of all user pages and user talk pages. Also the page name of the target page. The target page shows an overview of the most recent contributions by a user.",
'contributions-title' => 'The page title in your browser bar, but not the page title. See also {{msg|contributions}}. Parameter $1 is the username.',
'mycontris' => 'In the personal urls page section - right upper corner.',
'uctop' => 'This message is used in [[Special:Contributions]]. It is used to show that a particular edit was the last made to a page. Example: 09:57, 11 February 2008 (hist) (diff) Pagename‎ (edit summary) (top)',
'month' => 'Used in [[Special:Contributions]] and history pages ([{{fullurl:Sandbox|action=history}} example]), as label for a dropdown box to select a specific month to view the edits made in that month, and the earlier months. See also {{msg|year}}.',
'year' => 'Used in [[Special:Contributions]] and history pages ([{{fullurl:Sandbox|action=history}} example]), as label for a inputbox to select a specific year to view the edits made in that year, and the earlier years. See also {{msg|month}}.',

'sp-contributions-newbies' => 'Text of radio button on special page [[Special:Contributions]].',
'sp-contributions-newbies-sub' => "Note at the top of the page of results for a search on [[Special:Contributions]] where 'Show contributions for new accounts only' has been selected.",
'sp-contributions-newbies-title' => 'The page title in your browser bar, but not the page title. See also {{msg|sp-contributions-newbies-sub}}.',
'sp-contributions-blocklog' => 'Used as a display name for a link to the block log on for example [[Special:Contributions/Mediawiki default]]

{{Identical|Block log}}',
'sp-contributions-submit' => '{{Identical|Search}}',

# What links here
'whatlinkshere' => 'The text of the link in the toolbox (on the left, below the search menu) going to [[Special:WhatLinksHere]].',
'whatlinkshere-title' => "Title of the special page [[Special:WhatLinksHere]]. This page appears when you click on the 'What links here' button in the toolbox. $1 is the name of the page concerned.",
'whatlinkshere-page' => '{{Identical|Page}}',
'linkshere' => "This message is the header line of the [[Special:WhatLinksHere/$1]] page generated by clicking 'What links here' in the sidebar toolbox. It is followed by a navigation bar built using [[MediaWiki:Viewprevnext]].",
'nolinkshere' => 'This appears on Whatlinkshere pages which are empty.

Parameter $1 is a page title.',
'isredirect' => 'Displayed in Special:WhatLinksHere (see [{{fullurl:Special:WhatLinksHere/Betawiki:Translator|hidelinks=1}} Special:WhatLinksHere/Betawiki:Translator] for example).

{{Identical|Redirect page}}',
'istemplate' => 'Means that a page (a template, specifically) is used as <code><nowiki>{{Page name}}</nowiki></code>.
Displayed in Special:WhatLinksHere (see [[Special:WhatLinksHere/Template:New portal]] for example).',
'isimage' => 'This message is displayed on [[Special:WhatLinksHere]] for images. It means that the image is used on the page (as opposed to just being linked to like an non-image page).',
'whatlinkshere-prev' => 'This is part of the navigation message on the top and bottom of Whatlinkshere pages, where it is used as the first argument of [[MediaWiki:Viewprevnext]].
$1 is the number of items shown per page. It is not used when $1 is zero; not sure what happens when $1 is one.
Special pages use [[MediaWiki:Prevn]] instead (still as an argument to [[MediaWiki:Viewprevnext]]).

{{PLURAL:$1|previous|previous $1}}',
'whatlinkshere-next' => 'This is part of the navigation message on the top and bottom of Whatlinkshere pages, where it is used as the second argument of [[MediaWiki:Viewprevnext]].
$1 is the number of items shown per page. It is not used when $1 is zero; not sure what happens when $1 is one.
Special pages use [[MediaWiki:Nextn]] instead (still as an argument to [[MediaWiki:Viewprevnext]]).

{{PLURAL:$1|next|next $1}}',
'whatlinkshere-links' => 'Used on [[Special:WhatLinksHere]]. It is a link to the WhatLinksHere page of that page.

Example line:
* [[Main Page]] ([[Special:WhatLinksHere/Main Page|{{int:whatlinkshere-links}}]])

{{Identical|Links}}',
'whatlinkshere-hideredirs' => 'Parameter $1 is the message "[[MediaWiki:Hide/{{SUBPAGENAME}}|hide]]" or "[[MediaWiki:Show/{{SUBPAGENAME}}|show]]".',
'whatlinkshere-hidetrans' => 'Parameter $1 is the message "[[MediaWiki:Hide/{{SUBPAGENAME}}|hide]]" or "[[MediaWiki:Show/{{SUBPAGENAME}}|show]]".',
'whatlinkshere-hidelinks' => 'Parameter $1 is the message "[[MediaWiki:Hide/{{SUBPAGENAME}}|hide]]" or "[[MediaWiki:Show/{{SUBPAGENAME}}|show]]".',
'whatlinkshere-hideimages' => 'This is the text of the option on [[Special:WhatLinksHere]] for image pages, allowing to hide/show pages which display the file inline ($1 is either [[MediaWiki:Show|{{MediaWiki:Show}}]], or [[MediaWiki:Hide|{{MediaWiki:Hide}}]])',
'whatlinkshere-filters' => '{{Identical|Filter}}',

# Block/unblock
'blockip' => 'The title of the special page [[Special:BlockIP]].

{{Identical|Block user}}',
'blockip-legend' => 'Legend/Header for the fieldset around the input form of [[Special:BlockIP]].

{{Identical|Block user}}',
'ipaddress' => '{{Identical|IP Address}}',
'ipbexpiry' => '{{Identical|Expiry}}',
'ipbreason' => 'Label of the block reason dropdown in [[Special:BlockIP]] and the unblock reason textfield in [{{fullurl:Special:IPBlockList|action=unblock}} Special:IPBlockList?action=unblock].

{{Identical|Reason}}',
'ipbreasonotherlist' => '{{Identical|Other reason}}',
'ipbanononly' => '{{Identical|Block anonymous users only}}',
'ipbcreateaccount' => '{{Identical|Prevent account creation}}',
'ipbemailban' => '{{Identical|Prevent user from sending e-mail}}',
'ipbenableautoblock' => '{{Identical|Automatically block ...}}',
'ipbsubmit' => '{{Identical|Block this user}}',
'ipbother' => '{{Identical|Other time}}',
'ipboptions' => "* Description: Options for the duration of the block. 
* <font color=\"red\">Be careful:</font> '''1 translation:1 english''', so the first part is the translation and the second part should stay in English. 
* Example: See e.g. [[MediaWiki:Ipboptions/nl]] if you still don't know how to do it.",
'ipbotheroption' => '{{Identical|Other}}',
'ipbotherreason' => '{{Identical|Other/additional reason}}',
'ipbhidename' => 'This is the label for a checkbox in the user block form on [[Special:BlockIP]].',
'ipbwatchuser' => 'This is an option on [[Special:BlockIP]] to watch the user page and talk page of the blocked user',
'ipblocklist-submit' => '{{Identical|Search}}',
'blocklistline' => 'This is the text of an entry in the Special:IPBlockList.
*$1 is the hour and date of the block. 
*$2 is the sysop. 
*$3 is the blocked user or IP (with link to contributions and talk)
*$4 contains "hour and date of expiry, details (\'\'reason\'\')"

See also [[MediaWiki:Blocklogentry]].',
'anononlyblock' => '{{Identical|Anon only}}',
'noautoblockblock' => '{{Identical|Autoblock disabled}}',
'emailblock' => '{{Identical|E-mail blocked}}',
'blocklink' => "Display name for a link that, when selected, leads to a form where a user can be blocked. Used in page history and recent changes pages. Example: \"''UserName (Talk | contribs | '''block''')''\".",
'contribslink' => 'Short for "contributions". Used as display name for a link to user contributions on history pages, [[Special:RecentChanges]], [[Special:Watchlist]], etc.',
'blocklogpage' => '{{Identical|Block log}}',
'blocklogentry' => 'This is the text of an entry in the Block log (and RC), after hour (and date, only in the Block log) and sysop name: 
*$1 is the blocked user or IP (with link to contributions and talk)
*$2 is the duration of the block (hours, days etc.) or the specified expiry date
*$3 contains "(details) (\'\'reason\'\')"
See also [[MediaWiki:Blocklistline]].',
'block-log-flags-noautoblock' => '{{Identical|Autoblock disabled}}',
'block-log-flags-noemail' => "Log message for [[Special:Log/block]] to note that a user cannot use the 'email another user' option.

{{Identical|E-mail blocked}}",
'ipb_expiry_temp' => 'Warning message displayed on [[Special:BlockIP]] if the option "hide username" is selected but the expiry time is not infinite.',
'ipb_already_blocked' => '{{Identical|$1 is already blocked}}',
'blockme' => 'The page title of [[Special:Blockme]], a feature which is disabled by default.',

# Developer tools
'lockdb' => 'The title of the special page [[Special:LockDB]].

{{Identical|Lock database}}',
'unlockdb' => 'The title of the special page [[Special:UnlockDB]].

{{Identical|Unlock database}}',
'lockbtn' => 'The submit button on the special page [[Special:LockDB]].

{{Identical|Lock database}}',
'unlockbtn' => 'The submit button on the special page [[Special:UnlockDB]].

{{Identical|Unlock database}}',
'lockfilenotwritable' => "'No longer needed' on wikipedia.",

# Move page
'move-page' => 'Header of the special page to move pages. $1 is the name of the page to be moved.',
'move-page-backlink' => 'Optional message. Translate it only if you have to change it, i.e. for RTL wikis

Shown as subtitle of [[Special:MovePage/testpage]]. $1 is the title of the page to be moved.',
'move-page-legend' => 'Legend of the fieldset around the input form of [[Special:MovePage/testpage]].

{{Identical|Move page}}',
'movepagetext' => 'Introduction shown when moving a page ([[Special:MovePage]]).',
'movepagetalktext' => "Text on the special 'Move page'. This text only appears if the talk page is not empty.",
'movearticle' => 'The text before the name of the page that you are moving.

{{Identical|Move page}}',
'newtitle' => 'Used in the special page "[[Special:MovePage]]". The text for the inputbox to give the new page title.',
'move-watch' => 'The text of the checkbox to watch the page you are moving.

{{Identical|Watch this page}}',
'movepagebtn' => "Button label on the special 'Move page'.

{{Identical|Move page}}",
'pagemovedsub' => 'Message displayed as aheader of the body, after succesfully moving a page from source to target name.',
'movepage-moved' => 'Message displayed after succesfully moving a page from source to target name.
* $1 is the source page as a link with display name
* $2 is the target page as a link with display name',
'movetalk' => 'The text of the checkbox to watch the associated talk page to the page you are moving. This only appears when the talk page is not empty.',
'1movedto2' => "This is ''logentry'' message. $1 is the original page name, $2 is the destination page name.",
'1movedto2_redir' => "This is ''logentry'' message. $1 is the original page name, $2 is the destination page name.",
'movelogpage' => 'Title of special page',
'movelogpagetext' => "Text on the special page 'Move log'.",
'movereason' => 'Used in [[Special:MovePage]]. The text for the inputbox to give a reason for the page move.

{{Identical|Reason}}',
'revertmove' => '{{Identical|Revert}}',
'delete_and_move_text' => 'Used when moving a page, but the destination page already exists and needs deletion. This message is to confirm that you really want to delete the page. See also {{msg|delete and move confirm}}.',
'delete_and_move_confirm' => 'Used when moving a page, but the destination page already exists and needs deletion. This message is for a checkbox to confirm that you really want to delete the page. See also {{msg|delete and move text}}.',
'fix-double-redirects' => 'This is a checkbox in [[Special:MovePage]] which allows to move all redirects from the old title to the new title.',

# Export
'export' => 'Page title of [[Special:Export]], a page where a user can export pages from a wiki to a file.',
'exporttext' => 'Main text on [[Special:Export]]. Leave the line <tt><nowiki>[[{{ns:special}}:Export/{{MediaWiki:Mainpage}}]]</nowiki></tt> exactly as it is!',
'exportcuronly' => 'A label of checkbox option in [[Special:Export]]',
'export-submit' => 'Button name in [[Special:Export]].

{{Identical|Export}}',
'export-addcat' => '{{Identical|Add}}',
'export-download' => 'A label of checkbox option in [[Special:Export]]',
'export-templates' => 'A label of checkbox option in [[Special:Export]]',

# Namespace 8 related
'allmessages' => 'The title of the special page [[Special:AllMessages]].',
'allmessagesname' => 'Used on [[Special:Allmessages]] meaning "the name of the message".
{{Identical|Name}}',
'allmessagesdefault' => 'Used in [[Special:AllMessages]].',
'allmessagescurrent' => 'Used in [[Special:AllMessages]].',
'allmessagestext' => 'Used in [[Special:AllMessages]].',
'allmessagesnotsupportedDB' => 'This message is displayed on [[Special:AllMessages]] on wikis were the configuration variable $wgUseDatabaseMessages is disabled. It means that the MediaWiki namespace is not used.',

# Thumbnails
'thumbnail-more' => '[[Image:Yes.png|thumb|This:]]
Tooltip shown when hovering over a little sign of a thumb image, to go to the image page (where it is bigger). For example, see the image at the right:',

# Special:Import
'import' => 'The title of the special page [[Special:Import]];',
'import-interwiki-submit' => '{{Identical|Import}}',
'xml-error-string' => ':$1: Some kind of message, perhaps name of the error?
:$2: line number
:$3: columm number
:$4: ?? $this->mByte . $this->mContext
:$5: error description',
'import-upload' => 'Used on [[Special:Import]].

Related messages: {{msg|right-importupload|pl=yes}} (the user right for this).',

# Import log
'importlogpage' => '{{Identical|Import log}}',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'This text appears in the tool-tip when you hover the mouse over your the tab with you User name on it',
'tooltip-pt-mytalk' => 'Tooltip shown when hovering over the "my talk" link in your personal toolbox (upper right side).',
'tooltip-pt-preferences' => 'Tooltip shown when hovering over the "my preferences" link in your personal toolbox (upper right side).

{{Identical|My preferences}}',
'tooltip-pt-watchlist' => 'Tooltip shown when hovering over the "my watchlist" link in your personal toolbox (upper right side).',
'tooltip-pt-mycontris' => 'Tooltip shown when hovering over the "my contributions" link in your personal toolbox (upper right side).',
'tooltip-pt-login' => "Tooltip shown when hovering over the link 'Log in / create account' in the upper right corner show on all pages while not logged in.",
'tooltip-pt-logout' => 'Tooltip shown when hovering over the "Log out" link in your personal toolbox (upper right side).

{{Identical|Log out}}',
'tooltip-ca-talk' => 'Tooltip shown when hovering over the "[[MediaWiki:Talk/{{SUBPAGENAME}}|{{int:talk}}]]" tab.',
'tooltip-ca-edit' => 'The tooltip when hovering over the "[[MediaWiki:Edit/{{SUBPAGENAME}}|{{int:edit}}]]" tab.',
'tooltip-ca-addsection' => 'Tooltip shown when hovering over the "addsection" tab (shown on talk pages).',
'tooltip-ca-viewsource' => 'Tooltip displayed when hovering over the {{msg|viewsource}} tab.',
'tooltip-ca-protect' => '{{Identical|Protect this page}}',
'tooltip-ca-delete' => 'Tooltip shown when hovering over the "[[MediaWiki:Delete/{{SUBPAGENAME}}|{{int:delete}}]]" tab.

{{Identical|Delete this page}}',
'tooltip-ca-move' => '{{Identical|Move this page}}',
'tooltip-ca-watch' => '{{Identical|Add this page to your watchlist}}',
'tooltip-ca-unwatch' => 'Tooltip shown when hovering over the {{msg|unwatch}} tab.',
'tooltip-search' => 'The tooltip when hovering over the search menu.',
'tooltip-search-go' => 'This is the text of the tooltip displayed when hovering the mouse over the “[[MediaWiki:Go|Go]]” button next to the search box.',
'tooltip-search-fulltext' => 'This is the text of the tooltip displayed when hovering the mouse over the “[[MediaWiki:Search|Search]]” button under the search box.',
'tooltip-p-logo' => '{{Identical|Main page}}',
'tooltip-n-mainpage' => 'Tool tip shown when hovering the mouse over the link to [[{{MediaWiki:Mainpage}}]].',
'tooltip-n-portal' => "Tooltip shown when hovering over the link to 'Community portal' shown in the side bar menu on all pages.",
'tooltip-n-currentevents' => 'Tooltip shown when hovering over {{msg|currentevents}} in the sidebar.',
'tooltip-n-recentchanges' => 'The tooltip when hovering over the "[[MediaWiki:Recentchanges/{{SUBPAGENAME}}|{{int:recentchanges}}]]" link in the sidebar going to the special page [[Special:RecentChanges]].',
'tooltip-n-randompage' => "Tooltip shown when hovering over the link to 'Random page' shown in the side bar menu on all pages. Clicking the link will show a random page in from the wiki's main namespace.",
'tooltip-n-help' => "Tooltip shown when hovering over the link 'help' shown in the side bar menu on all pages.",
'tooltip-t-whatlinkshere' => 'Tooltip shown when hovering over the {{msg|whatlinkshere}} message in the toolbox.',
'tooltip-t-contributions' => 'Tooltip shown when hovering over {{msg|contributions}} in the toolbox.',
'tooltip-t-emailuser' => 'Tooltip shown when hovering over the {{msg|emailuser}} link in the toolbox (sidebar, below).',
'tooltip-t-upload' => 'Tooltip shown when hovering over the link to upload files shown in the side bar menu on all pages.

{{Identical|Upload files}}',
'tooltip-t-specialpages' => 'The tooltip when hovering over the link "[[MediaWiki:Specialpages/{{SUBPAGENAME}}|{{int:specialpages}}]]" going to a list of all special pages available in the wiki.',
'tooltip-ca-nstab-user' => 'Tooltip shown when hovering over {{msg|nstab-user}} (User namespace tab).',
'tooltip-ca-nstab-image' => 'Tooltip shown when hovering over {{msg|nstab-image}} (Image namespace tab).',
'tooltip-ca-nstab-template' => 'Tooltip shown when hovering over the {{msg|nstab-template}} tab.',
'tooltip-ca-nstab-help' => 'Tootip shown when hovering over the {{msg|nstab-help}} tab in the Help namespace.',
'tooltip-ca-nstab-category' => 'Tooltip shown when hovering over the {{msg|nstab-category}} tab.',
'tooltip-minoredit' => 'Tooltip shown when hovering over the "[[MediaWiki:Minoredit/{{SUBPAGENAME}}|{{int:minoredit}}]]" link below the edit form.',
'tooltip-save' => "This is the text that appears when you hover the mouse over the 'Save page' button on the edit page",
'tooltip-preview' => 'Tooltip shown when hovering over the "Show preview" button.',
'tooltip-diff' => 'This is the text (tooltip) that appears when you hover the mouse over the "Show changes" button ({{msg|showdiff}}) on the edit page.',
'tooltip-compareselectedversions' => 'Tooltip of {{msg|compareselectedversions}} (which is used as button in history pages).',
'tooltip-watch' => '{{Identical|Add this page to your watchlist}}',
'tooltip-rollback' => 'Tooltip of the rollback link on the history page and the diff view',
'tooltip-undo' => 'Tooltip of the undo link on the history page and the diff view',

# Stylesheets
'common.css' => 'CSS applied to all users.',
'monobook.css' => 'CSS applied to users using Monobook skin.',

# Scripts
'common.js' => 'JS for all users.',
'monobook.js' => 'JS for users using Monobook skin.',

# Attribution
'lastmodifiedatby' => 'This message is shown when viewing the credits of a page (example: {{fullurl:Main Page|action=credits}}). Note that this action is disabled by default (currently enabled on Betawiki).
* $1: date
* $2: time
* $3: user

See also [[MediaWiki:Lastmodifiedat/{{SUBPAGENAME}}]].',
'siteusers' => '* $1 is a list of user names (example: "\'\'Jim, Janet, Jane, Joe\'\'")
* $2 is the number of user names in $1',
'creditspage' => "This message is the ''contentSub'' (the grey subtitle) shown when viewing credits of a page (example: {{fullurl:Betawiki:News|action=credits}}). Note that the credits action is disabled by default (currently enabled on Betawiki).",
'nocredits' => 'This message is shown when viewing the credits of a page (example: {{fullurl:Main Page|action=credits}}) but when there are no credits available. Note that the credits action is disabled by default (currently enabled on Betawiki).',

# Math options
'mw_math_png' => 'In user preferences.',
'mw_math_simple' => 'In user preferences.',
'mw_math_html' => 'In user preferences.',
'mw_math_source' => 'In user preferences (math)',
'mw_math_modern' => 'In user preferences (math)',
'mw_math_mathml' => 'In user preferences.',

# Patrol log
'patrol-log-page' => 'Name of log.',
'patrol-log-line' => 'Text of notes on entries in the [[Special:Log|patrol log]]. $1 is the reference number of the revision in [[Mediawiki:patrol-log-diff]]. $2 is the page title. $3 appears to be [[Mediawiki:Patrol-log-auto]] (at least sometimes).',
'patrol-log-auto' => 'Automated edit summary when patrolling.

{{Identical|Automatic}}',

# Browsing diffs
'previousdiff' => 'Used when viewing the difference between edits. See also {{msg|nextdiff}}.',
'nextdiff' => 'Used when viewing the difference between edits. See also {{msg|previousdiff}}.',

# Visual comparison
'visual-comparison' => '{{Identical|Visual comparison}}',

# Media information
'widthheightpage' => 'This message is used on image pages in the dimensions column in the file history section for images  with more than one page. Parameter $1 is the image width (in pixels), parameter $2 is the image height, and parameter $3 is the number of pages.',
'file-info' => 'File info displayed on file description page.',
'file-info-size' => 'File info displayed on file description page.',
'file-nohires' => 'File info displayed on file description page.',
'svg-long-desc' => 'Displayed under an SVG image at the image description page. See for example [[:Image:Wiki.svg]].',
'show-big-image' => 'Displayed under an image at the image description page, when it is displayed smaller there than it was uploaded.',
'show-big-image-thumb' => 'File info displayed on file description page.',

# Special:NewImages
'newimages' => 'Page title of [[Special:NewImages]].',
'imagelisttext' => 'This is text on [[Special:NewImages]]. $1 is the number of files. $2 is the message [[Mediawiki:Bydate]].',
'newimages-summary' => 'This message is displayed at the top of [[Special:NewImages]] to explain what is shown on that special page.',
'newimages-legend' => 'Caption of the fieldset for the filter on [[Special:NewImages]]

{{Identical|Filter}}',
'newimages-label' => 'Caption of the filter editbox on [[Special:NewImages]]',
'showhidebots' => 'This is shown on the special page [[Special:NewImages]]. The format is "{{int:showhidebots|[[MediaWiki:Hide/{{SUBPAGENAME}}|{{int:hide}}]]}}" or "{{int:showhidebots|[[MediaWiki:Show/{{SUBPAGENAME}}|{{int:show}}]]}}"

{{Identical|$1 bots}}',
'noimages' => "This is shown on the special page [[Special:NewImages]], when there aren't any recently uploaded files.",
'ilsubmit' => '{{Identical|Search}}',
'sp-newimages-showfrom' => "This is a link on [[Special:NewImages]] which takes you to a gallery of the newest files.
* $1 is a date (example: ''19 March 2008'')
* $2 is a time (example: ''12:15'')",

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'hours-abbrev' => 'Abbreviation for "hours"',

# Bad image list
'bad_image_list' => 'This is only message appears to guide administrators to add links with right format. This will not appear anywhere else in Mediawiki.',

/*
Short names for language variants used for language conversion links.
To disable showing a particular link, set it to 'disable', e.g.
'variantname-zh-sg' => 'disable',
Variants for Chinese language
*/
'variantname-zh-hans' => 'Varient Option for wikis with variants conversion enabled.',
'variantname-zh-hant' => 'Varient Option for wikis with variants conversion enabled.',
'variantname-zh-cn' => 'Varient Option for wikis with variants conversion enabled.',
'variantname-zh-tw' => 'Varient Option for wikis with variants conversion enabled.',
'variantname-zh-hk' => 'Varient Option for wikis with variants conversion enabled.',
'variantname-zh-sg' => 'Varient Option for wikis with variants conversion enabled.',
'variantname-zh' => 'Varient Option for wikis with variants conversion enabled.',

# Variants for Serbian language
'variantname-sr-ec' => 'Varient Option for wikis with variants conversion enabled.',
'variantname-sr-el' => 'Varient Option for wikis with variants conversion enabled.',
'variantname-sr' => 'Varient Option for wikis with variants conversion enabled.',

# Variants for Kazakh language
'variantname-kk-kz' => 'Varient Option for wikis with variants conversion enabled.',
'variantname-kk-tr' => 'Varient Option for wikis with variants conversion enabled.',
'variantname-kk-cn' => 'Varient Option for wikis with variants conversion enabled.',
'variantname-kk-cyrl' => 'Varient Option for wikis with variants conversion enabled.',
'variantname-kk-latn' => 'Varient Option for wikis with variants conversion enabled.',
'variantname-kk-arab' => 'Varient Option for wikis with variants conversion enabled.',
'variantname-kk' => 'Varient Option for wikis with variants conversion enabled.',

# Variants for Kurdish language
'variantname-ku-arab' => 'Varient Option for wikis with variants conversion enabled.',
'variantname-ku-latn' => 'Varient Option for wikis with variants conversion enabled.',
'variantname-ku' => 'Varient Option for wikis with variants conversion enabled.',

# Metadata
'metadata' => 'The title of a section on an image description page, with information and data about the image.

{{Identical|Metadata}}',
'metadata-expand' => 'On an image description page, there is mostly a table containing data (metadata) about the image. The most important data are shown, but if you click on this link, you can see more data and information. For the link to hide back the less important data, see "[[MediaWiki:Metadata-collapse/{{SUBPAGENAME}}|{{int:metadata-collapse}}]]".',
'metadata-collapse' => 'On an image description page, there is mostly a table containing data (metadata) about the image. The most important data are shown, but if you click on the link "[[MediaWiki:Metadata-expand/{{SUBPAGENAME}}|{{int:metadata-expand}}]]", you can see more data and information. This message is for the link to hide back the less important data.',
'metadata-fields' => "'''Warning:''' Do not translate list items, only translate the text! So leave \"<tt>* make</tt>\" and the other items exactly as it is.",

# EXIF tags
'exif-imagewidth' => '{{Identical|Width}}',
'exif-imagelength' => '{{Identical|Height}}',
'exif-primarychromaticities' => 'The chromaticity of the three primary colours of the image. Normally this tag is not necessary, since colour space is specified in the colour space information tag. This should probably be translated it as "Chromaticity of primary colours".',
'exif-software' => 'Short for "The software which was used to create this image".',
'exif-artist' => '{{Identical|Author}}',
'exif-flash' => '{{Identical|Flash}}',
'exif-subjectarea' => 'This exif property contains the position of the main subject of the picture in pixels from the upper left corner and additionally its width and heigth in pixels.',
'exif-spatialfrequencyresponse' => '[http://en.wikipedia.org/wiki/Spatial_frequency Spatial frequency] is the number of edges per degree of the visual angle. The human eye scans the viewed scenary for edges and uses these edges to detect what it sees. Few edges make it hard to recognize the seen objects, but many edges do so too. A rate of about 4 to 6 edges per degree of the viewing range is seen as optimal for the recognition of objects.

Spatial frequency response is a measure for the capability of camera lenses to depict spatial frequencies.',
'exif-gpslatitude' => '{{Identical|Latitude}}',
'exif-gpslongitude' => '{{Identical|Longitude}}',

'exif-orientation-1' => '{{Identical|Normal}}',

'exif-subjectdistance-value' => '$1 is a distance measured in metres. The value can, and usually does, include decimal places.',

'exif-meteringmode-0' => '{{Identical|Unknown}}',
'exif-meteringmode-255' => '{{Identical|Other}}',

'exif-lightsource-0' => '{{Identical|Unknown}}',
'exif-lightsource-4' => '{{Identical|Flash}}',

# Flash modes
'exif-flash-mode-1' => 'This is when you have chosen that your camera must use a flash for this picture.',
'exif-flash-mode-2' => "This is when you have chosen that your camera must ''not'' use a flash for this picture.",

'exif-sensingmethod-5' => "''Color sequential'' means, that the three base colors are measured one after another (i.e. the sensor is first measuring red, than green, than blue).",
'exif-sensingmethod-8' => "''Color sequential'' means, that the three base colors are measured one after another (i.e. the sensor is first measuring red, than green, than blue).",

'exif-scenecapturetype-0' => '{{Identical|Standard}}',

'exif-gaincontrol-0' => 'Gain amplifies the signal off of the image sensor. Gain turns the brightness level up or down.

:0: None: no gain at all
:1: Low gain up: some more brightness
:2: High gain up: much more brightness
:3: Low gain down: some less brightness (seems to be uncommon in photography)
:4: High gain down: much less brightness (seems to be uncommon in photography)

{{Identical|None}}',
'exif-gaincontrol-1' => '{{:MediaWiki:Exif-gaincontrol-0/qqq}}',
'exif-gaincontrol-2' => '{{:MediaWiki:Exif-gaincontrol-0/qqq}}',
'exif-gaincontrol-3' => '{{:MediaWiki:Exif-gaincontrol-0/qqq}}',
'exif-gaincontrol-4' => '{{:MediaWiki:Exif-gaincontrol-0/qqq}}',

'exif-contrast-0' => '{{Identical|Normal}}',
'exif-contrast-1' => '{{Identical|Soft}}',
'exif-contrast-2' => '{{Identical|Hard}}',

'exif-saturation-0' => '{{Identical|Normal}}',

'exif-sharpness-0' => '{{Identical|Normal}}',
'exif-sharpness-1' => '{{Identical|Soft}}',
'exif-sharpness-2' => '{{Identical|Hard}}',

'exif-subjectdistancerange-0' => '{{Identical|Unknown}}',
'exif-subjectdistancerange-1' => 'See also:
* {{msg|Exif-subjectdistancerange-0}}
* {{msg|Exif-subjectdistancerange-1}}
* {{msg|Exif-subjectdistancerange-2}}
* {{msg|Exif-subjectdistancerange-3}}',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-n' => "Knots: ''Knot'' is a unit of speed on water used for ships, etc., equal to one nautical mile per hour.",

# External editor support
'edit-externally' => 'Displayed on image description pages. See for example [[:Image:Yes.png#filehistory]].',
'edit-externally-help' => 'Displayed on image description pages. See for example [[:Image:Yes.png#filehistory]].

Please leave the link http://www.mediawiki.org/wiki/Manual:External_editors exactly as it is.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => '{{Identical|All}}',
'imagelistall' => '{{Identical|All}}',
'watchlistall2' => 'Appears on [[Special:Watchlist]]. It is variable $3 in the text message [[Mediawiki:Wlshowlast]].

{{Identical|All}}',
'namespacesall' => 'In special page [[Special:WhatLinksHere]]. Drop-down box option for namespace.

{{Identical|All}}',
'monthsall' => 'Used in a drop-down box on [[Special:Contributions]] as an option for "all months". See also [[MediaWiki:Month/{{SUBPAGENAME}}]].

{{Identical|All}}',

# E-mail address confirmation
'confirmemail_needlogin' => 'Used on [[Special:ConfirmEmail]] when you are logged out. Parameter $1 is a link to the log in form.',
'confirmemail_body' => 'This message is sent as an e-mail to users when they add or change their e-mail adress in [[Special:Preferences]].

*$1 is the IP adress of the user that changed the e-mail adress
*$2 is the name of the user
*$3 is a URL to [[Special:ConfirmEmail]]
*$4 is a time and date 
*$5 is a URL to [[Special:InvalidateEmail]]',
'invalidateemail' => "This is the '''name of the special page''' where user goes if he chooses the cancel e-mail confirmation link from the confirmation e-mail.",

# action=purge
'confirm_purge_button' => '{{Identical|OK}}',

# Separators for various lists, etc.
'colon-separator' => "Optional message. Change it only if your language uses another character for ':' or it needs an extra space before the colon.",

# Multipage image navigation
'imgmultipageprev' => '{{Identical|Previous page}}',
'imgmultipagenext' => '{{Identical|Next page}}',
'imgmultigo' => '{{Identical|Go}}',

# Table pager
'table_pager_next' => '{{Identical|Next page}}',
'table_pager_prev' => '{{Identical|Previous page}}',
'table_pager_limit' => "Do not use PLURAL in this message, because ''$1'' is not the actual number. ''$1'' is a limit selector drop-down list.",
'table_pager_limit_submit' => '{{Identical|Go}}',
'table_pager_empty' => 'Used in a table pager when there are no results (e.g. when there are no images in the table on [[Special:ImageList]]).',

# Auto-summaries
'autosumm-blank' => 'The auto summary when blanking the whole page. This is not the same as deleting the page.',
'autosumm-replace' => 'The auto summary when a user removes a lot of characters in the page.',
'autoredircomment' => 'The auto summary when making a redirect. $1 is the page where it redirects to.',
'autosumm-new' => 'The auto summary when creating a new page. $1 are the first X number of characters of the new page.',

# Size units
'size-kilobytes' => 'Size (of a page, typically) in kibibytes (1 kibibyte = 1024 bytes).',
'size-megabytes' => 'Size (of a file, typically) in mebibytes (1 mebibyte = 1024×1024 bytes).',
'size-gigabytes' => 'Size (of a file, typically) in gibibytes (1 gibibyte = 1024×1024×1024 bytes).',

# Live preview
'livepreview-loading' => '{{Identical|Loading}}',

# Watchlist editor
'watchlistedit-numitems' => 'Message on Special page: Edit watchlist. This is paired with the message [[Mediawiki:Watchlistedit-noitems]] which appears instead of Watchlistedit-numitems when $1 is 0.',
'watchlistedit-noitems' => "Message on Special page: Edit watchlist, which only appears when a user's watchlist is empty.",
'watchlistedit-normal-explain' => 'An introduction/explanation about the [[Special:Watchlist/edit|normal edit watchlist function]].',
'watchlistedit-normal-done' => 'Message on Special page: Edit watchlist after pages are removed from the watchlist.',
'watchlistedit-raw-title' => '{{Identical|Edit raw watchlist}}',
'watchlistedit-raw-legend' => '{{Identical|Edit raw watchlist}}',
'watchlistedit-raw-explain' => 'An introduction/explanation about the [[Special:Watchlist/raw|raw edit watchlist function]].',
'watchlistedit-raw-added' => 'Message on special page: Edit raw watchlist. The message appears after at least 1 message is added to the raw watchlist.',
'watchlistedit-raw-removed' => 'Message on special page: Edit raw watchlist. The message appears after at least 1 message is deleted from the raw watchlist.',

# Watchlist editing tools
'watchlisttools-view' => '[[Special:Watchlist]]: Navigation link under the title. See also {{msg|watchlisttools-edit}} and {{msg|watchlisttools-raw}}.',
'watchlisttools-edit' => '[[Special:Watchlist]]: Navigation link under the title. See also {{msg|watchlisttools-view}} and {{msg|watchlisttools-raw}}.',
'watchlisttools-raw' => '[[Special:Watchlist]]: Navigation link under the title. See also {{msg|watchlisttools-view}} and {{msg|watchlisttools-edit}}.

{{Identical|Edit raw watchlist}}',

# Iranian month names
'iranian-calendar-m1' => 'Name of month in Iranian calender.',
'iranian-calendar-m2' => 'Name of month in Iranian calender.',
'iranian-calendar-m3' => 'Name of month in Iranian calender.',
'iranian-calendar-m4' => 'Name of month in Iranian calender.',
'iranian-calendar-m5' => 'Name of month in Iranian calender.',
'iranian-calendar-m6' => 'Name of month in Iranian calender.',
'iranian-calendar-m7' => 'Name of month in Iranian calender.',
'iranian-calendar-m8' => 'Name of month in Iranian calender.',
'iranian-calendar-m9' => 'Name of month in Iranian calender.',
'iranian-calendar-m10' => 'Name of month in Iranian calender.',
'iranian-calendar-m11' => 'Name of month in Iranian calender.',
'iranian-calendar-m12' => 'Name of month in Iranian calender.',

# Hijri month names
'hijri-calendar-m1' => 'Name of month in Islamic calender.',
'hijri-calendar-m2' => 'Name of month in Islamic calender.',
'hijri-calendar-m3' => 'Name of month in Islamic calender.',
'hijri-calendar-m4' => 'Name of month in Islamic calender.',
'hijri-calendar-m5' => 'Name of month in Islamic calender.',
'hijri-calendar-m6' => 'Name of month in Islamic calender.',
'hijri-calendar-m7' => 'Name of month in Islamic calender.',
'hijri-calendar-m8' => 'Name of month in Islamic calender.',
'hijri-calendar-m9' => 'Name of month in Islamic calender.',
'hijri-calendar-m10' => 'Name of month in Islamic calender.',
'hijri-calendar-m11' => 'Name of month in Islamic calender.',
'hijri-calendar-m12' => 'Name of month in Islamic calender.',

# Hebrew month names
'hebrew-calendar-m1' => 'Name of month in Hebrew calender.',
'hebrew-calendar-m2' => 'Name of month in Hebrew calender.',
'hebrew-calendar-m3' => 'Name of month in Hebrew calender.',
'hebrew-calendar-m4' => 'Name of month in Hebrew calender.',
'hebrew-calendar-m5' => 'Name of month in Hebrew calender.',
'hebrew-calendar-m6' => 'Name of month in Hebrew calender.',
'hebrew-calendar-m6a' => 'Name of month in Hebrew calender.',
'hebrew-calendar-m6b' => 'Name of month in Hebrew calender.',
'hebrew-calendar-m7' => 'Name of month in Hebrew calender.',
'hebrew-calendar-m8' => 'Name of month in Hebrew calender.',
'hebrew-calendar-m9' => 'Name of month in Hebrew calender.',
'hebrew-calendar-m10' => 'Name of month in Hebrew calender.',
'hebrew-calendar-m11' => 'Name of month in Hebrew calender.',
'hebrew-calendar-m12' => 'Name of month in Hebrew calender.',
'hebrew-calendar-m1-gen' => 'Name of month in Hebrew calender.',
'hebrew-calendar-m2-gen' => 'Name of month in Hebrew calender.',
'hebrew-calendar-m3-gen' => 'Name of month in Hebrew calender.',
'hebrew-calendar-m4-gen' => 'Name of month in Hebrew calender.',
'hebrew-calendar-m5-gen' => 'Name of month in Hebrew calender.',
'hebrew-calendar-m6-gen' => 'Name of month in Hebrew calender.',
'hebrew-calendar-m6a-gen' => 'Name of month in Hebrew calender.',
'hebrew-calendar-m6b-gen' => 'Name of month in Hebrew calender.',
'hebrew-calendar-m7-gen' => 'Name of month in Hebrew calender.',
'hebrew-calendar-m8-gen' => 'Name of month in Hebrew calender.',
'hebrew-calendar-m9-gen' => 'Name of month in Hebrew calender.',
'hebrew-calendar-m10-gen' => 'Name of month in Hebrew calender.',
'hebrew-calendar-m11-gen' => 'Name of month in Hebrew calender.',
'hebrew-calendar-m12-gen' => 'Name of month in Hebrew calender.',

# Core parser functions
'unknown_extension_tag' => '* Description: This is an error shown when you use an unknown extension tag name. This feature allows tags like <tt><nowiki><pre></nowiki></tt> to be called with a parser like <tt><nowiki>{{#tag:pre}}</nowiki></tt>.
* Parameter $1: This is the unknown extension tag name.',

# Special:Version
'version' => 'Name of special page displayed in [[Special:SpecialPages]]

{{Identical|Version}}',
'version-extensions' => 'Header on [[Special:Version]].',
'version-specialpages' => 'Part of [[Special:Version]].

{{Identical|Special pages}}',
'version-parserhooks' => 'This message is a heading at [[Special:Version]] for extensions that modifies the parser of wikitext.',
'version-other' => '{{Identical|Other}}',
'version-mediahandlers' => 'Used in [[Special:Version]]. It is the title of a section for media handler extensions (e.g. [[mw:Extension:OggHandler]]).
There are no such extensions here, so look at [[wikipedia:Special:Version]] for an example.',
'version-hooks' => 'Shown in [[Special:Version]]',
'version-extension-functions' => 'Shown in [[Special:Version]]',
'version-parser-function-hooks' => 'Shown in [[Special:Version]]',
'version-skin-extension-functions' => 'Shown in [[Special:Version]]',
'version-hook-name' => 'Shown in [[Special:Version]]',
'version-hook-subscribedby' => 'Shown in [[Special:Version]]',
'version-version' => '{{Identical|Version}}',
'version-software-product' => 'Shown in [[Special:Version]]',
'version-software-version' => '{{Identical|Version}}',

# Special:FilePath
'filepath' => 'Shown in [[Special:FilePath]]',
'filepath-page' => 'Shown in [[Special:FilePath]]

{{Identical|File}}',
'filepath-submit' => 'Shown in [[Special:FilePath]]',
'filepath-summary' => 'Shown in [[Special:FilePath]]',

# Special:FileDuplicateSearch
'fileduplicatesearch-summary' => 'Summary of [[Special:FileDuplicateSearch]]',
'fileduplicatesearch-legend' => 'Legend of the fieldset around the input form of [[Special:FileDuplicateSearch]]',
'fileduplicatesearch-filename' => 'Input form of [[Special:FileDuplicateSearch]]:

{{Identical|Filename}}',
'fileduplicatesearch-submit' => '{{Identical|Search}}',
'fileduplicatesearch-info' => 'Information beneath the thumbnail on the right side shown after a successful search via [[Special:FileDuplicateSearch]]

* $1: width of the file
* $2: height of the file
* $3: File size 
* $4: MIME type',
'fileduplicatesearch-result-1' => 'Result line after the list of files of [[Special:FileDuplicateSearch]]

$1 is the name of the requested file.',
'fileduplicatesearch-result-n' => 'Result line after the list of files of [[Special:FileDuplicateSearch]]

* $1 is the name of the requested file. 
* $2 is the number of identical duplicates of the requested file',

# Special:SpecialPages
'specialpages' => 'Display name of link to [[Special:SpecialPages]] shown on all pages in the toolbox, as well as the page title and header of [[Special:SpecialPages]].

{{Identical|Special pages}}',
'specialpages-note' => 'Footer note for the [[Special:SpecialPages]] page',
'specialpages-group-maintenance' => 'Section heading in the list of [[Special:SpecialPages|Special pages]].',
'specialpages-group-other' => 'Section heading in the list of [[Special:SpecialPages|Special pages]].',
'specialpages-group-login' => 'Section heading in the list of [[Special:SpecialPages|Special pages]].',
'specialpages-group-changes' => 'Section heading in the list of [[Special:SpecialPages|Special pages]].',
'specialpages-group-media' => 'Section heading in the list of [[Special:SpecialPages|Special pages]].',
'specialpages-group-users' => 'Section heading in the list of [[Special:SpecialPages|Special pages]].',
'specialpages-group-highuse' => 'Section heading in the list of [[Special:SpecialPages|Special pages]].',
'specialpages-group-pages' => 'Title of the special pages group, containing pages like [[Special:AllPages]], [[Special:PrefixIndex]], [[Special:Categories]], [[Special:Disambiguations]], etc.',
'specialpages-group-pagetools' => 'Title of the special pages group containing special pages like [[Special:MovePage]], [[Special:Undelete]], [[Special:WhatLinksHere]], [[Special:Export]] etc.',
'specialpages-group-wiki' => 'Title of the special pages group, containing special pages like [[Special:Version]], [[Special:Statistics]], [[Special:LockDB]], etc.',
'specialpages-group-redirects' => 'Title of the special pages group, containing special pages that redirect to another location, like [[Special:Randompage]], [[Special:Mypage]], [[Special:Mytalk]], etc.',

# Special:BlankPage
'intentionallyblankpage' => 'Text displayed in [[Special:BlankPage]].',

);

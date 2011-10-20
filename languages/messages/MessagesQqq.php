<?php
/** Message documentation (Message documentation)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author *Surak*
 * @author Ahonc
 * @author Aleator
 * @author AlexSm
 * @author AnakngAraw
 * @author Ans
 * @author Antime
 * @author Aotake
 * @author Bangin
 * @author Bennylin
 * @author Beta16
 * @author Boivie
 * @author Brest
 * @author BrokenArrow
 * @author Byrial
 * @author Claudia Hattitten
 * @author Codex Sinaiticus
 * @author Crt
 * @author Dalibor Bosits
 * @author Darth Kule
 * @author Deadelf
 * @author Dsvyas
 * @author Emilkris33
 * @author Erwin
 * @author Erwin85
 * @author EugeneZelenko
 * @author Fitoschido
 * @author Fryed-peach
 * @author Garas
 * @author GerardM
 * @author Hamilton Abreu
 * @author Helix84
 * @author Holek
 * @author Huji
 * @author IAlex
 * @author INkubusse
 * @author Iniquity
 * @author Iwan Novirion
 * @author Jon Harald Søby
 * @author Karduelis
 * @author Kizito
 * @author Klenje
 * @author Klutzy
 * @author Krinkle
 * @author Kwj2772
 * @author Leinad
 * @author Lejonel
 * @author Li-sung
 * @author Lloffiwr
 * @author MF-Warburg
 * @author Malafaya
 * @author MarkvA
 * @author MaxSem
 * @author McDutchie
 * @author Meno25
 * @author Metalhead64
 * @author MichaelFrey
 * @author Mihai
 * @author Mormegil
 * @author Mpradeep
 * @author Najami
 * @author Nemo bis
 * @author Niels
 * @author Nike
 * @author Node ue
 * @author Octahedron80
 * @author Od1n
 * @author Onecountry
 * @author OsamaK
 * @author PhiLiP
 * @author Platonides
 * @author Prima klasy4na
 * @author Prometheus.pyrphoros
 * @author Psubhashish
 * @author Purodha
 * @author Rancher
 * @author Raymond
 * @author Robby
 * @author Ryan Schmidt
 * @author SPQRobin
 * @author Sanbec
 * @author Sborsody
 * @author Seb35
 * @author Sherbrooke
 * @author Shushruth
 * @author Siebrand
 * @author Singularity
 * @author Sionnach
 * @author Slomox
 * @author Sp5uhe
 * @author Srhat
 * @author Tedjuh10
 * @author Tgr
 * @author The Evil IP address
 * @author Toliño
 * @author UV
 * @author Umherirrender
 * @author Urhixidur
 * @author Usarker
 * @author Verdy p
 * @author Vinhtantran
 * @author Waldir
 * @author Whym
 * @author Yekrats
 * @author Yuriy Apostol
 * @author Yyy
 * @author Žekřil71pl
 * @author פוילישער
 */

$messages = array(
# User preference toggles
'tog-underline'               => "[[Special:Preferences]], tab 'Misc'. Offers user a choice how to underline links. {{Gender}}",
'tog-highlightbroken'         => "[[Special:Preferences]], tab 'Appearance'. Offers user a choice how format internal links to non-existing pages. As red links or with a trailing question mark. {{Gender}}",
'tog-justify'                 => "[[Special:Preferences]], tab 'Appearance'. Offers user a choice to justify paragraphs or not. {{Gender}}",
'tog-hideminor'               => "[[Special:Preferences]], tab 'Recent changes'. Offers user to hide minor edits in recent changes or not. {{Gender}}",
'tog-hidepatrolled'           => 'Option in Recent changes tab of [[Special:Preferences]] (if [[mw:Manual:$wgUseRCPatrol|$wgUseRCPatrol]] is enabled). {{Gender}}',
'tog-newpageshidepatrolled'   => 'Toggle in [[Special:Preferences]], section "Recent changes" (if [[mw:Manual:$wgUseRCPatrol|$wgUseRCPatrol]] is enabled). {{Gender}}',
'tog-extendwatchlist'         => "[[Special:Preferences]], tab 'Watchlist'. Offers user to show all applicable changes in watchlist (by default only the last change to a page on the watchlist is shown). {{Gender}}",
'tog-usenewrc'                => "[[Special:Preferences]], tab 'Recent changes'. Offers user to use alternative reprsentation of [[Special:RecentChanges]]. {{Gender}}",
'tog-numberheadings'          => "[[Special:Preferences]], tab 'Misc'. Offers numbered headings on content pages to user. {{Gender}}",
'tog-showtoolbar'             => "[[Special:Preferences]], tab 'Edit'. Offers user to show edit toolbar in page edit screen. {{Gender}}

This is the toolbar: [[Image:Toolbar.png]]",
'tog-editondblclick'          => "[[Special:Preferences]], tab 'Edit'. Offers user to open edit page on double click. {{Gender}}",
'tog-editsection'             => "[[Special:Preferences]], tab 'Edit'. Offers user to add links in sub headings for editing sections. {{Gender}}",
'tog-editsectiononrightclick' => "[[Special:Preferences]], tab 'Edit'. Offers user to edit a section by clicking on a section title. {{Gender}}",
'tog-showtoc'                 => "[[Special:Preferences]], tab 'Misc'. Offers user to show a table of contents automatically if a page has more than three headings. {{Gender}}",
'tog-rememberpassword'        => "{{Identical|Remember my login on this computer}}[[Special:Preferences]], tab 'User profile', section 'Change password'. Offers user remember login details.  {{Gender}} Parameters:
* $1 is the number of days the login details are remembered.",
'tog-watchcreations'          => "[[Special:Preferences]], tab 'Watchlist'. Offers user to add created pages to watchlist. {{Gender}}",
'tog-watchdefault'            => "[[Special:Preferences]], tab 'Watchlist'. Offers user to add edited pages to watchlist. {{Gender}}",
'tog-watchmoves'              => "[[Special:Preferences]], tab 'Watchlist'. Offers user to add moved pages to watchlist. {{Gender}}",
'tog-watchdeletion'           => "[[Special:Preferences]], tab 'Watchlist'. Offers user to add deleted pages to watchlist. {{Gender}}",
'tog-minordefault'            => "[[Special:Preferences]], tab 'Edit'. Offers user to mark all edits minor by default.  {{Gender}}",
'tog-previewontop'            => 'Toggle option used in [[Special:Preferences]]. {{Gender}}',
'tog-previewonfirst'          => 'Toggle option used in [[Special:Preferences]]. {{Gender}}',
'tog-nocache'                 => "[[Special:Preferences]], tab 'Misc.'. Offers the user the option of disabling caching of pages in the browser. {{Gender}}",
'tog-enotifwatchlistpages'    => 'Option in [[Special:Preferences]] > {{int:prefs-personal}} > {{int:email}}. {{Gender}}',
'tog-enotifusertalkpages'     => 'Option in [[Special:Preferences]] > {{int:prefs-personal}} > {{int:email}}. {{Gender}}',
'tog-enotifminoredits'        => 'Option in [[Special:Preferences]] > {{int:prefs-personal}} > {{int:email}}. {{Gender}}

Is only shown if {{msg-mw|tog-enotifusertalkpages}} or/and {{msg-mw|tog-enotifwatchlistpages}} are shown.',
'tog-enotifrevealaddr'        => 'Toggle option used in [[Special:Preferences]] > {{int:prefs-personal}} > {{int:email}}. {{Gender}}',
'tog-shownumberswatching'     => 'Toggle option used in [[Special:Preferences]], in the section for recent changes. When this option is activated, the entries in recent changes includes the number of users who watch pages. {{Gender}}',
'tog-oldsig'                  => 'Used in [[Special:Preferences]], tab User profile. {{Gender}}',
'tog-fancysig'                => 'In user preferences under the signature box.  {{Gender}}',
'tog-externaleditor'          => "[[Special:Preferences]], tab 'Edit'. Offers user to use an external editor by default. {{Gender}}",
'tog-externaldiff'            => "[[Special:Preferences]], tab 'Edit'. Offers user to use an external diff program by default. {{Gender}}",
'tog-showjumplinks'           => 'Toggle option used in [[Special:Preferences]]. The "jump to" part should be the same with {{msg-mw|jumpto}} (or you can use <nowiki>{{int:jumpto}}</nowiki>). Thess links are shown in some of the older skins as "jump to: navigation, search" but they are hidden by default (you can enable them with this option). {{Gender}}',
'tog-uselivepreview'          => 'Toggle option used in [[Special:Preferences]]. Live preview is an experimental feature (unavailable by default) to use edit preview without loading the page again. {{Gender}}',
'tog-forceeditsummary'        => "Toggle option used in [[Special:Preferences]] to force an edit ''{{msg-mw|summary}}''. {{Gender}}",
'tog-watchlisthideown'        => "[[Special:Preferences]], tab 'Watchlist'. Offers user to hide own edits from watchlist. {{Gender}}",
'tog-watchlisthidebots'       => "[[Special:Preferences]], tab 'Watchlist'. Offers user to hide bot edits from watchlist. {{Gender}}",
'tog-watchlisthideminor'      => "[[Special:Preferences]], tab 'Watchlist'. Offers user to hide minor edits from watchlist. {{Gender}}",
'tog-watchlisthideliu'        => "Option in tab 'Watchlist' of [[Special:Preferences]]. {{Gender}}",
'tog-watchlisthideanons'      => "Option in tab 'Watchlist' of [[Special:Preferences]]. {{Gender}}",
'tog-watchlisthidepatrolled'  => 'Option in Watchlist tab of [[Special:Preferences]]. {{Gender}}',
'tog-nolangconversion'        => 'In user preferences.',
'tog-ccmeonemails'            => 'Option in [[Special:Preferences]] > {{int:prefs-personal}} > {{int:email}}. {{Gender}}',
'tog-diffonly'                => 'Toggle option used in [[Special:Preferences]]. {{Gender}}',
'tog-showhiddencats'          => 'Toggle option used in [[Special:Preferences]]. {{Gender}}',
'tog-noconvertlink'           => "{{optional}}

''(the message is considered optional because it is only used in wikis with language variants)''",
'tog-norollbackdiff'          => "Option in [[Special:Preferences]], 'Misc' tab. Only shown for users with the rollback right. By default a diff is shown below the return screen of a rollback. Checking this preference toggle will suppress that. {{Gender}}
{{Identical|Rollback}}",

'underline-always'  => 'Used in [[Special:Preferences]] (under "Misc"). This option means "always underline links", there are also options "never" and "browser default". {{Gender}}
{{Identical|Always}}',
'underline-never'   => 'Used in [[Special:Preferences]] (under "Misc"). This option means "never underline links", there are also options "always" and "browser default". {{Gender}}

{{Identical|Never}}',
'underline-default' => 'Used in [[Special:Preferences]] (under "Misc"). This option means "underline links as in your browser", there are also options "never" and "always". {{Gender}}

{{Identical|Browser default}}',

# Font style option in Special:Preferences
'editfont-style'     => 'Used in [[Special:Preferences]], tab Editing. {{Gender}}',
'editfont-default'   => 'Option used in [[Special:Preferences]], tab Editing. {{Gender}}

{{identical|Browser default}}',
'editfont-monospace' => 'Option used in [[Special:Preferences]], tab Editing. {{Gender}}',
'editfont-sansserif' => 'Option used in [[Special:Preferences]], tab Editing. {{Gender}}',
'editfont-serif'     => 'Option used in [[Special:Preferences]], tab Editing. {{Gender}}',

# Dates
'sunday'        => 'Name of the day of the week.',
'monday'        => 'Name of the day of the week.',
'tuesday'       => 'Name of the day of the week.',
'wednesday'     => 'Name of the day of the week.',
'thursday'      => 'Name of the day of the week.',
'friday'        => 'Name of the day of the week.',
'saturday'      => 'Name of the day of the week.',
'sun'           => 'Abbreviation for Sunday, a day of the week.',
'mon'           => 'Abbreviation for Monday, a day of the week.',
'tue'           => 'Abbreviation for Tuesday, a day of the week.',
'wed'           => 'Abbreviation for Wednesday, a day of the week.',
'thu'           => 'Abbreviation for Thursday, a day of the week.',
'fri'           => 'Abbreviation for Friday, a day of the week.',
'sat'           => 'Abbreviation for Saturday, a day of the week.',
'january'       => 'The first month of the Gregorian calendar
{{Identical|January}}',
'february'      => 'The second month of the Gregorian calendar
{{Identical|February}}',
'march'         => 'The third month of the Gregorian calendar
{{Identical|March}}',
'april'         => 'The fourth month of the Gregorian calendar
{{Identical|April}}',
'may_long'      => 'The fifth month of the Gregorian calendar
{{Identical|May}}',
'june'          => 'The sixth month of the Gregorian calendar
{{Identical|June}}',
'july'          => 'The seventh month of the Gregorian calendar
{{Identical|July}}',
'august'        => 'The eighth month of the Gregorian calendar
{{Identical|August}}',
'september'     => 'The ninth month of the Gregorian calendar
{{Identical|September}}',
'october'       => 'The tenth month of the Gregorian calendar
{{Identical|October}}',
'november'      => 'The eleventh month of the Gregorian calendar
{{Identical|November}}',
'december'      => 'The twelfth month of the Gregorian calendar
{{Identical|December}}',
'january-gen'   => 'The first month of the Gregorian calendar. Must be in genitive, if the language has a genitive case.
{{Identical|January}}',
'february-gen'  => 'The second month of the Gregorian calendar. Must be in genitive, if the language has a genitive case.
{{Identical|February}}',
'march-gen'     => 'The third month of the Gregorian calendar. Must be in genitive, if the language has a genitive case.
{{Identical|March}}',
'april-gen'     => 'The fourth month of the Gregorian calendar. Must be in genitive, if the language has a genitive case.
{{Identical|April}}',
'may-gen'       => 'The fifth month of the Gregorian calendar. Must be in genitive, if the language has a genitive case.
{{Identical|May}}',
'june-gen'      => 'The sixth month of the Gregorian calendar. Must be in genitive, if the language has a genitive case.
{{Identical|June}}',
'july-gen'      => 'The seventh month of the Gregorian calendar. Must be in genitive, if the language has a genitive case.
{{Identical|July}}',
'august-gen'    => 'The eighth month of the Gregorian calendar. Must be in genitive, if the language has a genitive case.
{{Identical|August}}',
'september-gen' => 'The nineth month of the Gregorian calendar. Must be in genitive, if the language has a genitive case.
{{Identical|September}}',
'october-gen'   => 'The tenth month of the Gregorian calendar. Must be in genitive, if the language has a genitive case.
{{Identical|October}}',
'november-gen'  => 'The eleventh month of the Gregorian calendar. Must be in genitive, if the language has a genitive case.
{{Identical|November}}',
'december-gen'  => 'The twelfth month of the Gregorian calendar. Must be in genitive, if the language has a genitive case.
{{Identical|December}}',
'jan'           => 'Abbreviation of January, the first month of the Gregorian calendar',
'feb'           => 'Abbreviation of February, the second month of the Gregorian calendar',
'mar'           => 'Abbreviation of March, the thrird month of the Gregorian calendar',
'apr'           => 'Abbreviation of April, the fourth month of the Gregorian calendar',
'may'           => 'Abbreviation of May, the fifth month of the Gregorian calendar',
'jun'           => 'Abbreviation of June, the sixth month of the Gregorian calendar',
'jul'           => 'Abbreviation of July, the seventh month of the Gregorian calendar',
'aug'           => 'Abbreviation of August, the eighth month of the Gregorian calendar',
'sep'           => 'Abbreviation of September, the ninth month of the Gregorian calendar',
'oct'           => 'Abbreviation of October, the tenth month of the Gregorian calendar',
'nov'           => 'Abbreviation of November, the eleventh month of the Gregorian calendar',
'dec'           => 'Abbreviation of December, the twelfth month of the Gregorian calendar',

# Categories related messages
'pagecategories'                 => 'Used in the categories section of pages. Is followed by a colon and a list of categories.',
'category_header'                => 'In category description page',
'subcategories'                  => 'Used as a header on category pages that have subcategories.',
'category-media-header'          => 'In category description page',
'category-empty'                 => 'The text displayed in category page when that category is empty',
'hidden-categories'              => 'Used in the categories section of pages. Is followed by a colon and a list of categories.',
'hidden-category-category'       => 'Name of the category where hidden categories will be listed.',
'category-subcat-count'          => 'This message is displayed at the top of a category page showing the number of pages in the category. Parameters:
* $1: number of subcategories shown
* $2: total number of subcategories in category',
'category-subcat-count-limited'  => 'This message is displayed at the top of a category page showing the number of pages in the category when not all pages in a category are counted. Parameters:
* $1: number of subcategories shown',
'category-article-count'         => 'This message is used on category pages. Parameters:
* $1: number of pages shown
* $2: total number of pages in category',
'category-article-count-limited' => 'This message is displayed at the top of a category page showing the number of pages in the category when not all pages in a category are counted. Parameters:
* $1: number of pages shown',
'category-file-count'            => 'This message is displayed at the top of a category page showing the number of pages in the category. Parameters:
* $1: number of files shown
* $2: total number of files in category',
'category-file-count-limited'    => 'This message is displayed at the top of a category page showing the number of pages in the category when not all pages in a category are counted. Parameters:
* $1: number of files shown',
'listingcontinuesabbrev'         => 'Shown in contiuation of each first letter group.
See http://test.wikipedia.org/wiki/Category:Test_ko?uselang={{SUBPAGENAME}}, for example.',
'index-category'                 => 'Name of the category where pages with the <nowiki>__INDEX__</nowiki> behaviour switch are listed. For description of this behaviour switch see [http://www.mediawiki.org/wiki/Help:Magic_words#Behavior_switches mediawiki].',
'noindex-category'               => 'Name of the category where pages with the <nowiki>__NOINDEX__</nowiki> behaviour switch are listed. For description of this behaviour switch see [http://www.mediawiki.org/wiki/Help:Magic_words#Behavior_switches mediawiki].',
'broken-file-category'           => 'Name of category where pages that embed files that do not exist ("broken images") are listed.',

'linkprefix' => '{{optional}}',

'about'         => '{{Identical|About}}',
'article'       => "A 'content page' is a page that forms part of the purpose of the wiki. It includes the main page and pages in the main namespace and any other namespaces that are included when the wiki is customised. For example on Wikimedia Commons 'content pages' include pages in the file and category namespaces. On Wikinews 'content pages' include pages in the Portal namespace. For technical definition of 'content namespaces' see [http://www.mediawiki.org/wiki/Manual:Using_custom_namespaces#Content_namespaces Mediawiki].

Possible alternatives to the word 'content' are 'subject matter' or 'wiki subject' or 'wiki purpose'.

{{Identical|Content page}}",
'newwindow'     => 'Below the edit form, next to "[[MediaWiki:Edithelp/{{SUBPAGENAME}}|Editing help]]".',
'cancel'        => 'Message shown below the edit form, and if you click on it, you stop with editing the page and go back to the normal page view.

{{Identical|Cancel}}',
'moredotdotdot' => '{{Identical|More...}}',
'mytalk'        => 'In the personal urls page section - right upper corner.',
'anontalk'      => 'Link to the talk page appearing in [[mw:Help:Navigation#User_Links|user links]] for each anonymous users when [[mw:Manual:$wgShowIPinHeader|$wgShowIPinHeader]] is true.',
'navigation'    => 'This is shown as a section header in the sidebar of most skins.

{{Identical|Navigation}}',
'and'           => 'The translation for "and" appears in the [[Special:Version]] page, between the last two items of a list. If a comma is needed, add it at the beginning without a gap between it and the "&". <nowiki>&#32;</nowiki> is a blank space, one character long. Please leave it as it is.

This can also appear in the credits page if the credits feature is enabled,for example [http://translatewiki.net/wiki/Support&action=credits the credits of the support page]. (To view any credits page type <nowiki>&action=credits</nowiki> at the end of any URL in the address bar.)

{{Identical|And}}',

# Cologne Blue skin
'qbfind'         => 'Alternative for "search" as used in Cologne Blue skin.
{{Identical|Find}}',
'qbedit'         => '{{Identical|Edit}}',
'qbmyoptions'    => '{{Identical|My pages}}',
'qbspecialpages' => '{{Identical|Special pages}}',
'faqpage'        => "FAQ is short for ''frequently asked questions''. This page is only linked on some of the old skins, not in Monobook or Modern.

{{doc-important|Do not translate <tt>Project:</tt> part.}}",

# Vector skin
'vector-action-addsection' => 'Used in the Vector skin. See for example http://translatewiki.net/wiki/Talk:Main_Page?useskin=vector',
'vector-action-delete'     => 'Used in the Vector skin, as the name of a tab at the top of the page. See for example http://translatewiki.net/wiki/Main_Page?useskin=vector

{{Identical|Delete}}',
'vector-action-move'       => 'Used in the Vector skin, on the tabs at the top of the page. See for example http://translatewiki.net/wiki/Talk:Main_Page?useskin=vector

{{Identical|Move}}',
'vector-action-protect'    => 'Tab at top of page, in vector skin

{{Identical|Protect}}',
'vector-action-undelete'   => 'Tab at top of page, in vector skin.
{{Identical|Undelete}}',
'vector-action-unprotect'  => 'Tab at top of page, in vector skin.

{{Identical|Unprotect}}',
'vector-view-create'       => 'Tab label in the Vector skin. See for example http://translatewiki.net/wiki/Foo?useskin=vector
{{Identical|Create}}',
'vector-view-edit'         => 'Tab label in the Vector skin. See for example http://translatewiki.net/wiki/Main_Page?useskin=vector
{{Identical|Edit}}',
'vector-view-history'      => 'Tab label in the Vector skin. See for example http://translatewiki.net/wiki/Main_Page?useskin=vector
{{Identical|View history}}',
'vector-view-view'         => 'Tab label in the Vector skin (verb). See for example http://translatewiki.net/w/i.php?title=Main_Page&useskin=vector',
'vector-view-viewsource'   => 'Tab label in the Vector skin.
{{Identical|View source}}',
'actions'                  => '{{Identical|Action}}',
'namespaces'               => '{{Identical|Namespace}}',
'variants'                 => 'Used by the Vector skin.',

'errorpagetitle'    => 'Message shown in browser title bar when encountering error operation.

{{Identical|Error}}',
'returnto'          => '{{Identical|Return to $1}}',
'tagline'           => 'Used to identify the source of copied information. Do not change <nowiki>{{SITENAME}}</nowiki>.',
'help'              => 'General text (noun) used in the sidebar (by default).

See also [[MediaWiki:Helppage/{{SUBPAGENAME}}|{{int:helppage}}]] and [[MediaWiki:Edithelp/{{SUBPAGENAME}}|{{int:edithelp}}]].

{{Identical|Help}}',
'search'            => 'Noun. Text of menu section shown on every page of the wiki above the search form.

Also used as title of [[Special:Search]] page in [[Special:SpecialPages]].

{{Identical|Search}}',
'searchbutton'      => 'The button you can see in the sidebar, below the search input box. The "Go" button is [[MediaWiki:Searcharticle/{{SUBPAGENAME}}]].

{{Identical|Search}}',
'go'                => '{{Identical|Go}}',
'searcharticle'     => 'Button description in the search menu displayed on every page. The "Search" button is [[MediaWiki:Searchbutton/{{SUBPAGENAME}}]].

{{Identical|Go}}',
'history_short'     => 'Text used on the history tab.

{{Identical|History}}',
'updatedmarker'     => 'Displayed in the page history (of a page you are [[Special:Watchlist|watching]]), when the page has been edited since the last time you visited it. This feature is used if [[mw:Manual:$wgShowUpdatedMarker|$wgShowUpdatedMarker]] is enabled.',
'printableversion'  => 'Display name for link in wiki menu that leads to a printable version of a content page. Example: see one but last menu item on [[Main Page]].',
'permalink'         => 'Display name for a permanent link to the current revision of a page. When the page is edited, permalink will still link to this revision. Example: Last menu link on [[{{MediaWiki:Mainpage}}]]',
'print'             => '{{Identical|Print}}',
'view'              => 'The default text of the "View" or "Read" (Vector) views tab which represents the basic view for the page. Should be in the infinitive mood.

{{Identical|View}}',
'edit'              => 'The text of the tab going to the edit form. When the page is protected, you will see "[[MediaWiki:Viewsource/{{SUBPAGENAME}}|{{int:viewsource}}]]". Should be in the infinitive mood.

{{Identical|Edit}}',
'create'            => 'The text on the tab for to the edit form on unexisting pages.

{{Identical|Create}}',
'editthispage'      => 'This is the "edit" link as used in the skins Classic/Standard, Cologne Blue and Nostalgia. See {{msg|create-this-page}} for when the page does not exist.',
'create-this-page'  => 'In the skins Classic/Standard, Cologne Blue and Nostalgia this is the text for the link leading to the edit form on pages that have not yet been created. See {{msg|editthispage}} for when the page already exists.
{{Identical|Createpage}}',
'delete'            => 'Name of the Delete tab shown for admins. Should be in the infinitive mood.

{{Identical|Delete}}',
'deletethispage'    => 'In the skins Classic/Standard, Cologne Blue and Nostalgia this is the text for link to delete the page in admin view.
{{Identical|Delete this page}}',
'undelete_short'    => "It is tab label. It's really can be named ''nstab-undelete''.",
'viewdeleted_short' => 'Tab label for the undelete button when the user has permission to view the deleted history but not undelete.',
'protect'           => 'Name of protect tab displayed for admins. Should be in the infinitive mood.

{{Identical|Protect}}',
'protect_change'    => 'Text on links for each entry in [[Special:ProtectedPages]] to change the protection of pages (only displayed to admins).
{{Identical|Change}}',
'protectthispage'   => 'In the skins Classic/Standard, Cologne Blue and Nostalgia this is the text for link to protect the page in admin view.
{{Identical|Protect this page}}',
'unprotect'         => 'Name of unprotect tab displayed for admins

{{Identical|Unprotect}}',
'unprotectthispage' => 'In the skins Classic/Standard, Cologne Blue and Nostalgia this is the anchor text for the link to unprotect the page in admin view.
{{Identical|Unprotect this page}}',
'newpage'           => '{{Identical|New page}}',
'talkpage'          => 'In the skins Classic/Standard, Cologne Blue and Nostalgia this is the text for link to discuss the page.',
'talkpagelinktext'  => 'Used as name of links going to talk page in some places, like in [[Special:RecentChanges]], [[Special:Allmessages]], [[Special:Logs]], and [[Special:Watchlist/edit]].

{{Identical|Talk}}',
'personaltools'     => 'Heading for a group of links to your user page, talk page, preferences, watchlist, and contributions. This heading is visible in the sidebar in some skins. For an example, see [http://translatewiki.net/wiki/Main_Page?useskin=simple Main Page using simple skin].',
'articlepage'       => "'Content page' is used for NS_MAIN and any other non-standard namespace and this message is only used in skins Nostalgia, Cologneblue and Standard in the bottomLinks part.

{{Identical|Content page}}",
'talk'              => 'Used as display name for the tab to all talk pages. These pages accompany all content pages and can be used for discussing the content page. Example: [[Talk:Example]].

{{Identical|Discussion}}',
'views'             => 'Subtitle for the list of available views, for the current page. In "monobook" skin the list of views are shown as tabs, so this sub-title is not shown. For an example, see [http://translatewiki.net/wiki/Main_Page?useskin=simple Main Page using simple skin].

\'\'\'Note:\'\'\' This is "views" as in "appearances"/"representations", \'\'\'not\'\'\' as in "visits"/"accesses".
{{Identical|View}}',
'toolbox'           => 'The title of the toolbox below the search menu.',
'otherlanguages'    => 'This message is shown under the toolbox. It is used if there are interwiki links added to the page, like <tt><nowiki>[[</nowiki>en:Interwiki article]]</tt>.
{{Identical|Otherlanguages}}',
'redirectedfrom'    => 'The text displayed when a certain page is redirected to another page. Variable <tt>$1</tt> contains the name of the page user came from.',
'redirectpagesub'   => 'Displayed under the page title of a page which is a redirect to another page, see [{{fullurl:Project:Translators|redirect=no}} Project:Translators] for example.

{{Identical|Redirect page}}',
'lastmodifiedat'    => 'This message is shown below each page, in the footer with the logos and links.
* $1: date
* $2: time

See also [[MediaWiki:Lastmodifiedatby/{{SUBPAGENAME}}]].',
'jumpto'            => '"Jump to" navigation links. Hidden by default in monobook skin. The format is: {{int:jumpto}} [[MediaWiki:Jumptonavigation/{{SUBPAGENAME}}|{{int:jumptonavigation}}]], [[MediaWiki:Jumptosearch/{{SUBPAGENAME}}|{{int:jumptosearch}}]].',
'jumptonavigation'  => 'Part of the "jump to" navigation links. Hidden by default in monobook skin. The format is: [[MediaWiki:Jumpto/{{SUBPAGENAME}}|{{int:jumpto}}]] {{int:jumptonavigation}}, [[MediaWiki:Jumptosearch/{{SUBPAGENAME}}|{{int:jumptosearch}}]].

{{Identical|Navigation}}',
'jumptosearch'      => 'Part of the "jump to" navigation links. Hidden by default in monobook skin. The format is: [[MediaWiki:Jumpto/{{SUBPAGENAME}}|{{int:jumpto}}]] [[MediaWiki:Jumptonavigation/{{SUBPAGENAME}}|{{int:jumptonavigation}}]], {{int:jumptosearch}}.

{{Identical|Search}}',
'pool-timeout'      => 'Part of {{msg-mw|view-pool-error}}.',
'pool-queuefull'    => 'Part of {{msg-mw|view-pool-error}}

"Pool" refers to a pool of processes.',
'pool-errorunknown' => 'Part of {{msg-mw|view-pool-error}}.',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Used as the label of the link that appears at the footer of every page on the wiki (in most of  the skins) and leads to the page that contains the site description. The link target is {{msg-mw|aboutpage}}.

[[mw:Manual:Interface/Aboutsite|MediaWiki manual]].

{{doc-important|Do not change <nowiki>{{SITENAME}}</nowiki>.}}

{{Identical|About}}',
'aboutpage'            => 'Used as the target of the link that appears at the footer of every page on the wiki (in most of  the skins) and leads to the page that contains the site description. Therefore the content should be the same with the page name of the site description page. Only the message in the [[mw:Manual:$wgLanguageCode|site language]]  ([[MediaWiki:Aboutpage]]) is used. The link label is {{msg-mw|aboutsite}}.

{{doc-important|Do not translate "Project:" part, for this is the namespace prefix.}}',
'copyrightpage'        => '{{doc-important|Do not change <nowiki>{{ns:project}}</nowiki>}}

{{Identical|Copyright}}',
'currentevents'        => 'Standard link in the sidebar, for news. See also {{msg|currentevents-url}} for the link url.',
'currentevents-url'    => "Target page of ''{{Mediawiki:currentevents}}'' in the sidebar. See also {{msg|currentevents}}.
{{doc-important|Do not translate <tt>Project:</tt> part.}}",
'disclaimers'          => 'Used as display name for the link to [[{{MediaWiki:Disclaimerpage}}]] shown at the bottom of every page on the wiki. Example [[{{MediaWiki:Disclaimerpage}}|{{MediaWiki:Disclaimers}}]].',
'disclaimerpage'       => 'Used as page for that contains the site disclaimer. Used at the bottom of every page on the wiki. Example: [[{{MediaWiki:Disclaimerpage}}|{{MediaWiki:Disclaimers}}]].
{{doc-important|Do not change the "<tt>Project:</tt>" part.}}',
'edithelp'             => 'This is the text that appears on the editing help link that is near the bottom of the editing page',
'edithelppage'         => 'The help page displayed when a user clicks on editing help link which is present on the right of Show changes button.
{{doc-important|Do not change the "<tt>Help:</tt>" part.}}',
'helppage'             => 'The link destination used by default in the sidebar, and in {{msg|noarticletext}}.
{{doc-important|Do not change the "<tt>Help:</tt>" part.}}
{{Identical|HelpContent}}',
'mainpage'             => 'Defines the link and display name of the main page of the wiki. Shown as the top link in the navigation part of the interface. Please do not change it too often, that could break things!

{{Identical|Main page}}',
'mainpage-description' => 'The same as {{msg|mainpage|pl=yes}}, used as link text on [[MediaWiki:Sidebar]]. This makes it possible to the change the link destination (the message "mainpage") without changing the link text or without disabling translations.',
'policy-url'           => 'Description: The URL of the project page describing the policies of the wiki. This is shown below every page (the left link).
{{doc-important|Do not change the "<tt>Project:</tt>" part.}}',
'portal'               => "Display name for the 'Community portal', shown in the sidebar menu of all pages. The target page is meant to be a portal for users where useful links are to be found about the wiki's operation.",
'portal-url'           => 'Description: The URL of the community portal. This is shown in the sidebar by default (removed on translatewiki.net).
{{doc-important|Do not change the "<tt>Project:</tt>" part.}}',
'privacy'              => 'Used as page name and link at the bottom of each wiki page. The page contains a legal notice providing information about the use of personal information by the website owner.of the site. Example: [[Privacy policy]].',
'privacypage'          => 'Used as page for that contains the privacy policy. Used at the bottom of every page on the wiki. Example: [[{{MediaWiki:Privacypage}}|{{MediaWiki:Privacy}}]].
{{doc-important|Do not change the "<tt>Project:</tt>" part.}}',

'badaccess'        => 'Title shown within page indicating unauthorized access.',
'badaccess-group0' => 'Shown when you are not allowed to do something.',
'badaccess-groups' => "Error message when you aren't allowed to do something.

* $1 is a list of groups.
* $2 is the number of groups.",

'versionrequired'     => 'This message is not used in the MediaWiki core, but was introduced with the reason that it could be useful for extensions. See also {{msg|versionrequiredtext}}.',
'versionrequiredtext' => 'This message is not used in the MediaWiki core, but was introduced with the reason that it could be useful for extensions. See also {{msg|versionrequired}}.',

'ok'                      => '{{Identical|OK}}',
'pagetitle'               => "{{doc-important|You most probably do not need to translate this message.}}

Do '''not''' replace SITENAME with a translation of Wikipedia or some encycopedic additions. The message has to be neutral for all projects.",
'pagetitle-view-mainpage' => '{{optional}}',
'retrievedfrom'           => 'Message which appears in the source of every page, but it is hidden. It is shown when printing. $1 is a link back to the current page: {{FULLURL:{{FULLPAGENAME}}}}.',
'youhavenewmessages'      => 'The blue message appearing when someone edited your user talk page.
The format is: "{{int:youhavenewmessages| [[MediaWiki:Newmessageslink/{{SUBPAGENAME}}|{{int:newmessageslink}}]] |[[MediaWiki:Newmessagesdifflink/{{SUBPAGENAME}}|{{int:newmessagesdifflink}}]]}}"',
'newmessageslink'         => 'This is the first link displayed in an orange rectangle when a user gets a message on his talk page. Used in message {{msg-mw|youhavenewmessages}} (as parameter $1).

{{Identical|New messages}}',
'newmessagesdifflink'     => 'This is the second link displayed in an orange rectangle when a user gets a message on his talk page. Used in message {{msg-mw|youhavenewmessages}} (as parameter $2).',
'youhavenewmessagesmulti' => 'The alternative of {{msg|youhavenewmessages}} as used on wikis with a special setup so they can receive the "new message" notice on other wikis as well. Used on [http://www.wikia.com/ Wikia].
The format is: "{{int:youhavenewmessagesmulti| [[MediaWiki:Newmessageslink/{{SUBPAGENAME}}|{{int:newmessageslink}}]]}}"',
'editsection'             => 'Display name of link to edit a section on a content page. Example: [{{MediaWiki:Editsection}}].

{{Identical|Edit}}',
'editsection-brackets'    => '{{optional}}',
'editold'                 => '{{Identical|Edit}}',
'viewsourceold'           => '{{Identical|View source}}',
'editlink'                => 'Teks dari pranala yang terlihat di sebelah setiap templat yang dapat disunting (tidak diproteksi) pada daftar templat di bawah jendela penyuntingan. Lihat juga {{msg-mw|Viewsourcelink}}.

{{Identical|Edit}}',
'viewsourcelink'          => 'Text of the link shown next to every uneditable (protected) template in the list of used templates below the edit window. See also {{msg-mw|Editlink}}.

{{Identical|View source}}',
'editsectionhint'         => "Tool tip shown when hovering the mouse over the link to '[{{MediaWiki:Editsection}}]' a section. Example: Edit section: Heading name",
'toc'                     => 'Ini adalah judul "Daftar isi" yang terlihat pada halaman yang memiliki lebih dari 3 bagian

{{Identical|Contents}}',
'showtoc'                 => 'This is the link used to show the table of contents

{{Identical|Show}}',
'hidetoc'                 => 'This is the link used to hide the table of contents

{{Identical|Hide}}',
'collapsible-collapse'    => 'This is the link used to collapse a collapsible element. (used as plaintext. No wikitext or html is parsed.)',
'collapsible-expand'      => 'This is the link used to expand a collapsible element (used as plaintext. No wikitext or html is parsed.)',
'thisisdeleted'           => 'Message shown on a deleted page when the user has the undelete right. $1 is a link to [[Special:Undelete]], with {{msg-mw|restorelink}} as the text. See also {{msg-mw|viewdeleted}}.',
'viewdeleted'             => 'Message shown on a deleted page when the user does not have the undelete right (but has the deletedhistory right). $1 is a link to [[Special:Undelete]], with {{msg-mw|restorelink}} as the text. See also {{msg-mw|thisisdeleted}}.',
'restorelink'             => "This text is always displayed in conjunction with the {{msg-mw|thisisdeleted}} message (View or restore $1?). The user will see
View or restore <nowiki>{{PLURAL:$1|one deleted edit|$1 deleted edits}}</nowiki>?    i.e ''View or restore one deleted edit?''     or
''View or restore n deleted edits?''",
'feed-unavailable'        => 'This message is displayed when a user tries to use an RSS or Atom feed on a wiki where such feeds have been disabled.',
'site-rss-feed'           => "Used in the HTML header of a wiki's RSS feed.
$1 is <nowiki>{{SITENAME}}</nowiki>.
HTML markup cannot be used.
{{Identical|S1 RSS/Atom feed}}",
'site-atom-feed'          => "Used in the HTML header of a wiki's Atom feed.
$1 is <nowiki>{{SITENAME}}</nowiki>.
HTML markup cannot be used.
{{Identical|S1 RSS/Atom feed}}",
'page-rss-feed'           => '{{Identical|S1 RSS/Atom feed}}',
'page-atom-feed'          => '{{Identical|S1 RSS/Atom feed}}',
'feed-atom'               => '{{optional}}',
'feed-rss'                => '{{optional}}',
'red-link-title'          => 'Title for red hyperlinks. Indicates, that the page is empty, not written yet.',
'sort-descending'         => 'JavaScript message. Used by sortable table script.',
'sort-ascending'          => 'JavaScript message. Used by sortable table script.',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'The name for the tab of the main namespace. Example: [[Example]]

{{Identical|Page}}',
'nstab-user'      => 'The name for the tab of the user namespace. Example: [[User:Example]]. It is possible to use <nowiki>{{GENDER:{{BASEPAGENAME}}|male form|female form}}</nowiki> if needed.

{{Identical|User page}}',
'nstab-special'   => 'The name for the tab of the special namespace. Example: [[Special:Version]]',
'nstab-project'   => 'The name for the tab of the project namespace. Example: [[Project:Example]]',
'nstab-image'     => 'The name for the tab of the image namespace. Example: [[Image:Example]]

{{Identical|File}}',
'nstab-mediawiki' => 'The name for the tab of the MediaWiki namespace. Example: [[MediaWiki:Example]]

{{Identical|Message}}',
'nstab-template'  => 'The name for the tab of the template namespace. Example: [[Template:Example]]

{{Identical|Template}}',
'nstab-help'      => 'The name for the tab of the help namespace. Example: [[Help:Rollback]]',
'nstab-category'  => 'The name for the tab of the category namespace. Example: [[:Category:Example]]

{{Identical|Category}}',

# Main script and global functions
'nosuchspecialpage' => 'The title of the error you get when trying to open a special page which does not exist. The text of the warning is the message {{msg-mw|nospecialpagetext}}. Example: [[Special:Nosuchpage]]',
'nospecialpagetext' => 'This error is shown when trying to open a special page which does not exist, e.g. [[Special:Nosuchpage]].
* The title of this error is the message {{msg-mw|nosuchspecialpage}}.
* Link <code><nowiki>[[Special:SpecialPages|{{int:specialpages}}]]</nowiki></code> should remain untranslated.',

# General errors
'error'                => '{{Identical|Error}}',
'dberrortext'          => 'Parameters:
* $1 - The last SQL command/query
* $2 - SQL function name
* $3 - Error number
* $4 - Error description',
'dberrortextcl'        => 'Parameters:
* $1 - The last SQL command/query
* $2 - SQL function name
* $3 - Error number
* $4 - Error description',
'enterlockreason'      => 'For developers when locking the database',
'missing-article'      => "This message is shown when a revision does not exist, either as permalink or as diff. Examples:

# [http://translatewiki.net/w/i.php?title=Project:News&oldid=9999999 Permalink with invalid revision#]
# [http://translatewiki.net/w/i.php?title=Project:News&diff=426850&oldid=99999999 Diff with invalid revision#]

'''Parameters'''
* $1: Pagename
* $2: Content of
*# {{msg-mw|Missingarticle-rev}} - Permalink with invalid revision#
*# {{msg-mw|Missingarticle-diff}} - Diff with invalid revision#",
'missingarticle-rev'   => 'Parameter $2 of {{msg|Missing-article}}: It is shown after the articlename.

* $1: revision# of the requested id

[http://translatewiki.net/w/i.php?title=Translating:Tasks&oldid=371789000 Click here] to see an example of such an error message.',
'missingarticle-diff'  => 'Parameter $2 of {{msg|Missing-article}}: It is shown after the articlename.

* $1: revision# of the old id
* $2: revision# of the id build the diff with.

[http://translatewiki.net/w/i.php?title=Translating:Tasks&diff=372398&oldid=371789000 Click here] to see an example of such an error message.',
'readonly_lag'         => 'Error message displayed when the database is locked.',
'internalerror'        => '{{Identical|Internal error}}',
'fileappenderrorread'  => '"Append" is a computer procedure, explained on [http://en.wikipedia.org/wiki/Append Wikipedia].

$1 is a filename, I think.',
'badtitle'             => 'The page title when a user requested a page with invalid page name. The content will be {{msg-mw|badtitletext}}.',
'badtitletext'         => 'The message shown when a user requested a page with invalid page name. The page title will be {{msg-mw|badtitle}}.',
'perfcachedts'         => 'Used on pages that list page lists for which the displayed data is cached. Parameters:
* $1 is a time stamp (date and time combined)
* $2 is a date (optional)
* $3 is a time (optional)',
'querypage-no-updates' => 'Text on some special pages, e.g. [[Special:FewestRevisions]].',
'viewsource'           => 'The text displayed in place of the "edit" tab when the user has no permission to edit the page.

{{Identical|View source}}',
'viewsourcefor'        => 'Subtitle shown when trying to edit a protected page.

{{Identical|For $1}}',
'actionthrottled'      => 'This is the title of an error page. Read it in combination with {{msg-mw|actionthrottledtext}}.',
'protectedpagetext'    => 'This message is displayed when trying to edit a page you can\'t edit because it has been protected.

* $1: the protection type, e.g. "protect" for fully protected pages',
'viewsourcetext'       => 'The text shown when displaying the source of a page that the user has no permission to edit',
'protectedinterface'   => 'Message shown if a user without the "editinterface" right tries to edit a page in the MediaWiki namespace.',
'editinginterface'     => "A message shown when editing pages in the namespace MediaWiki:. In the [http://translatewiki.net/wiki/Main_Page?setlang=en URL], '''change \"setlang=en\" to your own language code.'''",
'ns-specialprotected'  => 'Error message displayed when trying to edit a page in the Special namespace',
'titleprotected'       => 'Use $1 for GENDER.',

# Login and logout pages
'logouttext'                 => 'Log out message',
'welcomecreation'            => 'The welcome message users see after registering a user account. $1 is the username of the new user.',
'yourname'                   => "In user preferences

<nowiki>{{</nowiki>[[Gender|GENDER]]<nowiki>}}</nowiki> is '''NOT''' supported.

{{Identical|Username}}",
'yourpassword'               => 'In user preferences

{{Identical|Password}}',
'yourpasswordagain'          => 'In user preferences',
'remembermypassword'         => 'A check box in [[Special:UserLogin]]

{{Identical|Remember my login on this computer}}',
'externaldberror'            => 'This message is thrown when a valid attempt to change the wiki password for a user fails because of a database error or an error from an external system.',
'login'                      => "Shown as the caption of the button at [[Special:UserLogin]], and also to anonymous users in the upper right corner of the page when they can't create an account (otherwise the message {{msg|nav-login-createaccount}} is shown there).

{{Identical|Log in}}",
'nav-login-createaccount'    => "Shown to anonymous users in the upper right corner of the page. When you can't create an account, the message {{msg|login}} is shown.
{{identical|Log in / create account}}",
'loginprompt'                => 'A small notice in the log in form.',
'userlogin'                  => 'Name of special page [[Special:UserLogin]] where a user can log in or click to create a user account.
{{Identical|Log in / create account}}',
'userloginnocreate'          => 'A variant of {{msg-mw|Userlogin}} when the user is not allowed to create a new account.

{{Identical|Log in}}',
'logout'                     => '{{Identical|Log out}}',
'userlogout'                 => '{{Identical|Log out}}',
'notloggedin'                => 'This message is displayed in the standard skin when not logged in. The message is placed above the login link in the top right corner of pages.

{{Identical|Not logged in}}',
'nologin'                    => 'A message shown in the log in form. $1 is a link to the account creation form, and the text of it is "[[MediaWiki:Nologinlink/{{SUBPAGENAME}}|{{int:nologinlink}}]]".',
'nologinlink'                => 'Text of the link to the account creation form. Before that link, the message [[MediaWiki:Nologin/{{SUBPAGENAME}}]] appears.
{{Identical|Create an account}}',
'createaccount'              => 'The title of Special:CreateAccount, where users can register a new account. Used on Special:SpecialPages, and also on the submit button in the form where you register a new account.
{{Identical|Create account}}',
'gotaccount'                 => 'A message shown in the account creation form. $1 is a link to the log in form, and the text of it is "[[MediaWiki:Gotaccountlink/{{SUBPAGENAME}}|{{int:gotaccountlink}}]]".',
'gotaccountlink'             => 'Text of the link to the log in form. Before that link, the message [[MediaWiki:Gotaccount/{{SUBPAGENAME}}]] appears.

{{Identical|Log in}}',
'createaccountmail'          => 'Button text for creating a new account and sending the new password to the specified e-mail address directly, as used on [[Special:UserLogin/signup]] if creating accounts by e-mail is allowed.',
'createaccountreason'        => '{{Identical|Reason}}',
'createaccounterror'         => 'Parameters:
* $1 is an error message',
'nocookiesnew'               => "This message is displayed when a new account was successfully created, but the browser doesn't accept cookies.",
'nocookieslogin'             => "This message is displayed when someone tried to login, but the browser doesn't accept cookies.",
'nocookiesfornew'            => "This message is displayed when the user tried to create a new account, but it failed the cross-site request forgery (CSRF) check. It could be blocking an attack, but most likely, the browser isn't  accepting cookies.",
'nocookiesforlogin'          => "{{optional}}
This message is displayed when someone tried to login and the CSRF failed (most likely, the browser doesn't accept cookies). Defaults to nocookieslogin",
'loginsuccesstitle'          => 'The title of the page saying that you are logged in. The content of the page is the message "[[MediaWiki:Loginsuccess/{{SUBPAGENAME}}]]".',
'loginsuccess'               => 'The content of the page saying that you are logged in. The title of the page is "[[MediaWiki:Loginsuccesstitle/{{SUBPAGENAME}}|{{int:loginsuccesstitle}}]]". $1 is the name of the logged in user.

<nowiki>{{</nowiki>[[Gender|GENDER]]<nowiki>}}</nowiki> is supported.',
'nosuchuser'                 => 'Displayed when trying to log in with an unexisting username. When you are not allowed to create an account, the message {{msg|nosuchusershort}} is displayed.',
'nosuchusershort'            => "Displayed when trying to log in with a non-existant username. This message is only shown when you can't create an account, otherwise the message {{msg|nosuchusershort}} is displayed.",
'login-userblocked'          => 'This message supports GENDER, username is available in $1.',
'wrongpasswordempty'         => 'Error message displayed when entering a blank password',
'passwordtooshort'           => 'This message is shown at

* [[Special:Preferences]]
* [[Special:CreateAccount]]

$1 is the minimum number of characters in the password.',
'password-login-forbidden'   => 'Error message shown when the user has tried to log in using one of the special username/password combinations used for MediaWiki testing. (See [[mwr:75589]], [[mwr:75605]].)',
'mailmypassword'             => 'Shown at [[Special:UserLogin]]',
'passwordremindertitle'      => 'Title of e-mail which contains temporary password',
'passwordremindertext'       => 'This text is used in an e-mail sent when a user requests a new temporary password (he has forgotten his password) or when an sysop creates a new user account choosing to have password and username sent to the new user by e-mail.
* $1 is an IP addres. Example: 123.123.123.123
* $2 is a username. Example: Joe
* $3 is a password. Example: er##@fdas!
* $4 is a URL. Example: http://wiki.example.com
* $5 is a number of days in which the temporary password will expire',
'noemail'                    => 'Shown as error message when trying to register a user sending password to e-mail adress and no e-mail address has been given. Registering users and sending a password to an e-mail address may require non-standard user rights ([http://translatewiki.net/w/i.php?title=Special:UserLogin&action=submitlogin&type=signup register user link]).

Parameters:
* $1 is a user name. This parameter can be used with GENDER.',
'eauthentsent'               => "This message appears after entering an e-mail address in [[Special:Preferences]] > {{int:prefs-personal}} > {{int:email}}, then clicking on '{{int:saveprefs}}'.",
'acct_creation_throttle_hit' => 'Errormessage at [[Special:CreateAccount]].
"in the last day" precisely means: during the lasts 86400 seconds (24 hours) ending right now.',
'emailauthenticated'         => 'In user preferences ([[Special:Preferences]] > {{int:prefs-personal}} > {{int:email}}) and on [[Special:ConfirmEmail]].

* $1: obsolete, date and time
* $2: date
* $3: time',
'emailnotauthenticated'      => 'Message in [[Special:Preferences]] > {{int:prefs-personal}} > {{int:email}}. It appears after saving your e-mail address but before it has been authenticated.',
'noemailprefs'               => "Message appearing in the 'E-mail options' section of the 'User profile' page in [[Special:Preferences|Preferences]], when no user e-mail address has been entered.",
'emailconfirmlink'           => 'Link to [[Special:ConfirmEmail]]. Appears in [[Special:Preferences]] > {{int:prefs-personal}} > {{int:email}} after saving your e-mail address but before it has been authenticated.',
'invalidemailaddress'        => 'Shown as a warning when written an invalid e-mail adress in [[Special:Preferences]] and {{fullurl:Special:UserLogin|type=signup}} page',
'cannotchangeemail'          => 'Error message shown when user goes to [[Special:ChangeEmail]] but email addresses cannot be changed on the site.',
'createaccount-title'        => 'This is the subject of an e-mail sent to the e-mail address entered at [[Special:CreateAccount]] if the button "by e-mail" is clicked.',
'createaccount-text'         => 'This text is sent as an e-mail to the e-mail address entered at [[Special:CreateAccount]] if the button "by e-mail" is clicked.

*Parameter $2 is the name entered as username.
*Parameter $3 is a password (randomly generated).
*Parameter $4 is a URL to the wiki',
'login-throttled'            => 'Error message shown at [[Special:UserLogin]] after 5 wrong passwords. The hardcoded waiting time is 300 seconds.',
'login-abort-generic'        => 'The generic unsuccessful login message is used unless otherwise specified by hook writers',
'loginlanguagelabel'         => 'Used on [[Special:UserLogin]] if $wgLoginLanguageSelector is true.
{{Identical|Language}}',

# E-mail sending
'user-mail-no-addy' => 'This is the error message in case an e-mail could not be sent because there was no e-mail address to send it to.',

# Change password dialog
'resetpass'                 => 'The caption of [[Special:ChangePassword]]

{{Identical|Change password}}',
'resetpass_header'          => 'Header on box on special page [[Special:ChangePassword]].

{{Identical|Reset password}}',
'oldpassword'               => "Used on the 'User profile' tab of 'my preferences'. This is the text next to an entry box for the old password in the 'change password' section.",
'newpassword'               => '{{Identical|New password}}',
'retypenew'                 => "Appears on the 'User profile' tab of the 'Preferences' special page in the 'Change password' section. It appears next to the text box for entering the new password a second time.",
'resetpass_submit'          => 'Submit button on [[Special:ChangePassword]]',
'resetpass-submit-loggedin' => 'Button on [[Special:ResetPass]] to submit new password.

{{Identical|Change password}}',
'resetpass-submit-cancel'   => '{{Identical|Cancel}}

Used on [[Special:ResetPass]]',
'resetpass-wrong-oldpass'   => 'Error message shown on [[Special:ChangePassword]] when the old password is not valid.',
'resetpass-temp-password'   => 'The label of the input box for the temporary password (received by e-mail) on the form displayed after logging in with a temporary password.',

# Special:PasswordReset
'passwordreset'                    => 'Title of [[Special:PasswordReset]].
{{Identical|Reset password}}',
'passwordreset-text'               => 'Text on [[Special:PasswordReset]]',
'passwordreset-legend'             => '{{Identical|Reset password}}',
'passwordreset-pretext'            => '$1 is the number of password reset routes. Apparently, this is never 1, but always two or more. Thus, the first plural option is empty in English.',
'passwordreset-username'           => '{{Identical|Username}}',
'passwordreset-domain'             => 'A domain like used in Domain Name System (DNS) or more specifically like a domain component in the Lightweight Directory Access Protocol (LDAP)',
'passwordreset-capture'            => 'Label for checkbox asking the user whether they want to see the contents of the password reset email (only shown if they have the <tt>passwordreset</tt> permission',
'passwordreset-capture-help'       => 'Longer explanatory message for the capture checkbox label.',
'passwordreset-email'              => '{{Identical|E-mail address}}',
'passwordreset-emailtext-ip'       => 'Be consistent with {{msg-mw|Passwordreset-emailtext-user|notext=y}}.

Parameters:
* $1 - an IP address
* $2 - message {{msg-mw|passwordreset-emailelement|notext=1}} repeated $3 times
* $3 - the number of repetitions in $2
* $4 - base URL of the wiki',
'passwordreset-emailtext-user'     => 'Be consistent with {{msg-mw|Passwordreset-emailtext-ip|notext=y}}.

Parameters:
* $1 - a user name
* $2 - message {{msg-mw|passwordreset-emailelement|notext=1}} repeated $3 times
* $3 - the number of repetitions in $2
* $4 - base URL of the wiki',
'passwordreset-emailelement'       => "This is a body of a reminder email to allow them into the system with a new password.
$1 will be the user's login name.
$2 will be the temporary password given by the system.",
'passwordreset-emailerror-capture' => 'What is $1?',

# Special:ChangeEmail
'changeemail'          => 'Title of [[Special:ChangeEmail|special page]].',
'changeemail-header'   => 'Header on [[Special:ChangeEmail]].',
'changeemail-text'     => 'Text of [[Special:ChangeEmail]].',
'changeemail-no-info'  => 'Error message for [[Special:ChangeEmail]].',
'changeemail-oldemail' => 'Label for e-mail address field in [[Special:ChangeEmail]].',
'changeemail-newemail' => 'Label for e-mail address field in [[Special:ChangeEmail]].',
'changeemail-none'     => "Probably appears in 'Current E-mail address' field when no address held, in [[Special:ChangeEmail]].

{{Identical|None}}",
'changeemail-submit'   => 'Submit button on [[Special:ChangeEmail]]',
'changeemail-cancel'   => 'Cancel button on [[Special:ChangeEmail]]

{{Identical|Cancel}}',

# Edit page toolbar
'bold_sample'     => 'This is the sample text that you get when you press the first button on the left on the edit toolbar.

{{Identical|Bold text}}',
'bold_tip'        => 'This is the text that appears when you hover the mouse over the first button on the left of the edit toolbar.

{{Identical|Bold text}}',
'italic_sample'   => 'The sample text that you get when you press the second button from the left on the edit toolbar.

{{Identical|Italic text}}',
'italic_tip'      => 'This is the text that appears when you hover the mouse over the second button from the left on the edit toolbar.

{{Identical|Italic text}}',
'link_sample'     => 'This is the default text in the internal link that is created when you press the third button from the left on the edit toolbar (the "Ab" icon).',
'link_tip'        => '{{Identical|Internal link}}

Tip for internal links',
'extlink_sample'  => 'This message appears when clicking on the fourth button of the edit toolbar. You can translate "link title". Because many of the localisations had urls that went to domains reserved for advertising, it is recommended that the link is left as-is. All customised links were replaced with the standard one, that is reserved in the standard and will never have ads or something.',
'extlink_tip'     => 'This is the tip that appears when you hover the mouse over the fourth button from the left on the edit toolbar.

{{Identical|External link (remember http:// prefix)}}',
'headline_sample' => 'Sample of headline text.',
'headline_tip'    => 'This is the text that appears when you hover the mouse over the fifth button from the left on the edit toolbar.',
'nowiki_sample'   => 'Text inserted between nowiki tags',
'nowiki_tip'      => 'This is the text that appears when you hover the mouse over the third button from the right on the edit toolbar.',
'image_sample'    => 'Used in text generated by Picture button in toolbar.

{{optional}}',
'image_tip'       => 'This is the text that appears when you hover the mouse over the sixth (middle) button on the edit toolbar.

{{Identical|Embedded file}}',
'media_sample'    => '{{optional}}',
'media_tip'       => 'This is the text that appears when you hover the mouse over the fifth button from the right in the edit toolbar.',
'sig_tip'         => 'This is the text that appears when you hover the mouse over the second key from the right on the edit toolbar.
{{Identical|Signature with timestamp}}',
'hr_tip'          => 'This is the text that appears when you hover the mouse over the first button on the right on the edit toolbar.',

# Edit pages
'summary'                          => 'The Summary text beside the edit summary field

{{Identical|Summary}}',
'minoredit'                        => 'Text above Save page button in editor',
'watchthis'                        => 'Text of checkbox above "Show preview" button in editor.

{{Identical|Watch this page}}',
'savearticle'                      => 'Text on the Save page button. See also {{msg|showpreview}} and {{msg|showdiff}} for the other buttons.',
'preview'                          => 'The title of the Preview page shown after clicking the "Show preview" button in the edit page. Since this is a heading, it should probably be translated as a noun and not as a verb.

{{Identical|Preview}}',
'showpreview'                      => 'The text of the button to preview the page you are editing. See also {{msg|showdiff}} and {{msg|savearticle}} for the other buttons.',
'showdiff'                         => 'Button below the edit page. See also {{msg|showpreview}} and {{msg|savearticle}} for the other buttons.',
'anoneditwarning'                  => 'Shown when editing a page anonymously.',
'anonpreviewwarning'               => 'See also {{msg-mw|Anoneditwarning}}',
'missingsummary'                   => 'The text "edit summary" is in {{msg-mw|summary}}.
The text "Save" is in {{msg-mw|savearticle}}.',
'missingcommenttext'               => 'This message is shown, when the textbox by a new-section is empty.',
'missingcommentheader'             => 'Edit summary that is shown if you enable "Prompt me when entering a blank summary" and add a new section without headline to a talk page.',
'summary-preview'                  => 'Preview of the edit summary, shown under the edit summary itself.
Should match: {{msg-mw|summary}}.',
'subject-preview'                  => 'Should match {{msg-mw|subject}}',
'blockedtext'                      => 'Text displayed to blocked users.

Parameters:
* <tt>$1</tt> is the blocking sysop (with a link to his/her userpage)
* <tt>$2</tt> is the reason for the block
* <tt>$3</tt> is the current IP address of the blocked user
* <tt>$4</tt> is the blocking sysop’s username (plain text, without the link)
* <tt>$5</tt> is the unique numeric identifier of the applied autoblock
* <tt>$6</tt> is the expiry of the block
* <tt>$7</tt> is the intended target of the block (what the blocking user specified in the blocking form)
* <tt>$8</tt> is the timestamp when the block started',
'autoblockedtext'                  => 'Text displayed to automatically blocked users.

Parameters:
* <tt>$1</tt> is the blocking sysop (with a link to his/her userpage)
* <tt>$2</tt> is the reason for the block
* <tt>$3</tt> is the current IP address of the blocked user
* <tt>$4</tt> is the blocking sysop’s username (plain text, without the link). Use it for GENDER.
* <tt>$5</tt> is the unique numeric identifier of the applied autoblock
* <tt>$6</tt> is the expiry of the block
* <tt>$7</tt> is the intended target of the block (what the blocking user specified in the blocking form)
* <tt>$8</tt> is the timestamp when the block started',
'blockednoreason'                  => '{{Identical|No reason given}}',
'whitelistedittext'                => '* $1 is a link to [[Special:UserLogin]] with {{msg-mw|loginreqlink}} as link description',
'nosuchsectiontext'                => 'This message is displayed when a user tries to edit a section that does not exist.',
'loginreqlink'                     => 'Take a look on inflection. Used as parameter in {{msg-mw|loginreqpagetext}}, {{msg-mw|whitelistedittext}}, {{msg-mw|watchlistanontext‎}} and {{msg-mw|Confirmemail needlogin}}.

{{Identical|Log in}}',
'loginreqpagetext'                 => 'This message is displayed if an anonymous reader attempts to access a page which is only available to logged-in users.
* $1 is a link to [[Special:UserLogin]] with {{msg-mw|loginreqlink}} as link description',
'accmailtitle'                     => 'Page title when temporary password was sent to a user via email.',
'accmailtext'                      => "The message shown when a temporary password has been sent to the user's email address.

{{doc-important|Do not translate \"<nowiki>[[User talk:\$1|\$1]]</nowiki>\" and ''Special:ChangePassword''.}}",
'newarticle'                       => '{{Identical|New}}',
'newarticletext'                   => "Text displayed above the edit box in editor when trying to create a new page.<br />'''Very important:''' leave <tt><nowiki>{{MediaWiki:Helppage}}</nowiki></tt> exactly as it is!",
'noarticletext'                    => 'This is the message that you get if you search for a term that has not yet got any entries on the wiki.

See also {{msg-mw|Noarticletext-nopermission}}.',
'noarticletext-nopermission'       => 'See also {{msg-mw|Noarticletext}}.',
'userpage-userdoesnotexist'        => 'Error message displayed when trying to edit or create a page or a subpage that belongs to a user who is not registered on the wiki. Parameters:
* $1 is a possible username that has not been registered.',
'userpage-userdoesnotexist-view'   => 'Shown in user pages of non existing users. See for example [http://translatewiki.net/wiki/User:Foo User:Foo]. Parameters:
* $1 is a username.',
'blocked-notice-logextract'        => 'Parameters:
* $1 is the name of the blocked user (optional). Can be used for GENDER.',
'clearyourcache'                   => 'Text at the top of .js/.css pages',
'usercssyoucanpreview'             => "Text displayed on every css page. The 'Show preview' part should be the same as {{msg-mw|showpreview}} (or you can use <nowiki>{{int:showpreview}}</nowiki>).",
'userjsyoucanpreview'              => 'Text displayed on every js page.',
'usercsspreview'                   => 'Text displayed on preview of every user .css subpage',
'userjspreview'                    => 'Text displayed on preview of every user .js subpage',
'sitecsspreview'                   => 'Text displayed on preview of .css pages in MediaWiki namespace',
'sitejspreview'                    => 'Text displayed on preview of .js pages in MediaWiki namespace',
'updated'                          => '{{Identical|Updated}}',
'previewnote'                      => 'Note displayed when clicking on Show preview',
'editing'                          => "Shown as page title when editing a page. \$1 is the name of the page that is being edited. Example: \"''Editing Main Page''\".",
'editingsection'                   => 'The variable $1 is the page name.  This message displays at the top of the page when a user is editing a page section.',
'explainconflict'                  => 'Appears at the top of a page when there is an edit conflict.',
'storedversion'                    => 'This is used in an edit conflict as the label for the top revision that has been stored, as opposed to your version that has not been stored which is shown at the bottom of the page.',
'yourdiff'                         => '',
'copyrightwarning'                 => 'Copyright warning displayed under the edit box in editor',
'longpageerror'                    => 'Warning displayed when trying to save a text larger than the maximum size allowed',
'protectedpagewarning'             => '{{Related|Semiprotectedpagewarning}}',
'semiprotectedpagewarning'         => '{{Related|Semiprotectedpagewarning}}',
'titleprotectedwarning'            => 'Warning message above the edit form when editing a page that has been protected aginst creation.',
'templatesused'                    => 'Displayed below the page when editing it. It indicates a list of templates which are used on that page.',
'templatesusedpreview'             => 'Used in editor when displaying a preview.',
'templatesusedsection'             => 'Used in editor when displaying a preview.',
'template-protected'               => '{{Identical|Protected}}',
'template-semiprotected'           => 'Used on [[Special:ProtectedPages]]. Appears in brackets after listed page titles which are semi-protected.',
'hiddencategories'                 => "This message is shown below the edit form, like you have a section ''\"Templates used on this page\"''.",
'edittools'                        => 'This text will be shown below edit and upload forms. It can be used to offer special characters not present on most keyboards for copying/pasting, and also often makes them clickable for insertion via a javascript. Since these are seen as specific to a wiki, however, this message should not contain anything but an html comment explaining how it should be used once the wiki has been installed.',
'edittools-upload'                 => '{{optional}}
This text will be shown below upload forms. It will default to the contents of edittools.',
'sectioneditnotsupported-title'    => 'Page title of special page, which presumably appears when someone tries to edit a section, and section editing is disabled. Explanation of section editing on [http://meta.wikimedia.org/wiki/Help:Section_editing#Section_editing meta].',
'sectioneditnotsupported-text'     => 'I think this is the text of an error message, which presumably appears when someone tries to edit a section, and section editing is disabled. Explanation of section editing on [http://meta.wikimedia.org/wiki/Help:Section_editing#Section_editing meta].',
'permissionserrorstext-withaction' => '* $1 is the number of reasons that were found why the action cannot be performed.
* $2 is one of the action-* messages (for example {{msg|action-edit}}).

Please report at [[Support]] if you are unable to properly translate this message. Also see [[bugzilla:14246]]',
'recreate-moveddeleted-warn'       => 'Warning shown when creating a page which has already been deleted. See for example [[Test]].',
'moveddeleted-notice'              => 'Shown on top of a deleted page in normal view modus ([http://translatewiki.net/wiki/Test example]).',
'edit-conflict'                    => "An 'Edit conflict' happens when more than one edit is being made to a page at the same time. This would usually be caused by separate individuals working on the same page. However, if the system is slow, several edits from one individual could back up and attempt to apply simultaneously - causing the conflict.",

# Parser/template warnings
'expensive-parserfunction-warning'        => 'On some (expensive) [[MetaWikipedia:Help:ParserFunctions|parser functions]] (e.g. <code><nowiki>{{#ifexist:}}</nowiki></code>) there is a limit of how many times it may be used. This is an error message shown when the limit is exceeded.

* $1 is the current number of parser function calls.
* $2 is the allowed number of parser function calls.',
'expensive-parserfunction-category'       => 'This message is used as a category name for a category where pages are placed automatically if they contain too many calls to expensive parser functions.',
'post-expand-template-inclusion-warning'  => 'When templates are expanded, there is a size limit for the number of bytes yielded. Usually that occurs from excessively nested templates, recursive templates, or ones having x-zillion of #if #case or similar contructs in them. When the wikicode parser detects this, it outputs a red warning message to the page.',
'post-expand-template-inclusion-category' => 'When templates are expanded, there is a size limit for the number of bytes yielded. Usually that occurs from excessively nested templates, recursive templates, or ones having x-zillion of #if #case or similar contructs in them. When the wikicode parser detects this, it outputs a red warning message to the page.',
'language-converter-depth-warning'        => 'Error message shown when a page uses too deeply nested language conversion syntax

* <tt>$1</tt> is the value of the depth limit',

# "Undo" feature
'undo-success' => 'Text on special page to confirm edit revert. You arrive on this page by clicking on the "undo" link on a revision history special page.

{{Identical|Undo}}',
'undo-failure' => 'Message appears if an attempt to revert an edit by clicking the "undo" link on the page history fails.

{{Identical|Undo}}',
'undo-norev'   => 'Message appears if an attempt to revert an edit by clicking the "undo" link on the page history fails.

{{Identical|Undo}}',
'undo-summary' => '{{Identical|Undo}}',

# History pages
'viewpagelogs'           => 'Link displayed in history of pages',
'currentrev'             => '{{Identical|Current revision}}',
'currentrev-asof'        => 'Used on a difference page when comparing the current versions of a page with each other. See {{msg-mw|Revisionasof}} for the message for non-current version.
* $1 is a date and time
* $2 is a date (optional)
* $3 is a time (optional)',
'revisionasof'           => 'Used on a difference page when comparing different versions of a page or when viewing an non-current version of a page. See {{msg-mw|Currentrev-asof}} for the message for the current version.
* $1 is the date/time at which the revision was created. Example: "\'\'Revision as of 14:44, 24 January 2008\'\'".
* $2 is the date at which the revision was created.
* $3 is the time at which the revision was created.',
'revision-info'          => 'Appears just below the page title when an old version of the page is being viewed.

* $1: date and time of revision
* $2: a series of links: to author of the revision, his talk page, etc.
* (optional) $3: revision ID
* (optional) $4: date of revision
* (optional) $5: time of revision
* (optional) $6: author of revision, for GENDER use',
'currentrevisionlink'    => '{{Identical|Current revision}}',
'cur'                    => 'Link in page history',
'next'                   => 'Link in page history

{{Identical|Next}}',
'last'                   => 'Link in page history

{{Identical|Last}}',
'page_first'             => "This is part of the navigation message on the top and bottom of Special pages which are lists of things in alphabetical order, e.g. the '[[Special:Categories|Categories]]' special page. It is followed by the message {{msg-mw|Viewprevnext}}.
{{Identical|First}}",
'page_last'              => "This is part of the navigation message on the top and bottom of Special pages which are lists of things in alphabetical order, e.g. the '[[Special:Categories|Categories]]' special page. It is followed by the message {{msg-mw|Viewprevnext}}.

{{Identical|Last}}",
'histlegend'             => 'Text in history page. Refers to {{msg-mw|cur}}, {{msg-mw|last}}, and {{msg-mw|minoreditletter}}.',
'history-fieldset-title' => 'Fieldset label in the edit history pages.',
'history-show-deleted'   => 'CheckBox to show only per [[mw:Manual:RevisionDelete|RevisonDelete]] deleted versions.

Used in History and [[Special:Contributions]].',
'histfirst'              => 'Used in page history.',
'histlast'               => 'Used in page history.',
'historyempty'           => 'Text in page history for empty page revisions

{{Identical|Empty}}',

# Revision feed
'history-feed-item-nocomment' => "Title for each revision when viewing the RSS/Atom feed for a page history:
* '''$1''' - user name,
* '''$2''' - date/time,
* '''$3''' - date,
* '''$4''' - time.",

# Revision deletion
'rev-deleted-user-contribs'   => 'Part of revision deletion.',
'rev-deleted-text-unhide'     => 'This message is very similar to {{msg-mw|rev-suppressed-unhide-diff}}. Parameters:
* $1 is a HTML link to the diff',
'rev-suppressed-unhide-diff'  => 'This message is very similar to {{msg-mw|rev-deleted-unhide-diff}} and to {{msg-mw|rev-suppressed-text-unhide}}. Parameters:
* $1 is a HTML link to the diff',
'rev-delundel'                => 'Link in page history for oversight (see also {{msg-mw|rev-showdeleted}})',
'rev-showdeleted'             => 'Link in page history for oversight (see also {{msg-mw|rev-delundel}})
{{Identical|Show}}',
'revisiondelete'              => '{{RevisionDelete}}
It is the page title of [[Special:RevisionDelete]].',
'revdelete-nooldid-title'     => '{{RevisionDelete}}',
'revdelete-nooldid-text'      => '{{RevisionDelete}}',
'revdelete-show-file-confirm' => 'A confirmation message shown on Special:Revisiondelete when the request does not contain a valid token (e.g. when a user clicks a link received in mail).

* <code>$1</code> is a file name
* <code>$2</code> is a date
* <code>$3</code> is a time

{{identical|Are you sure you want to view the deleted revision of the file...}}',
'revdelete-show-file-submit'  => 'Reply to {{msg-mw|Revdelete-show-file-confirm}}.

{{Identical|Yes}}',
'revdelete-selected'          => '{{RevisionDelete}}
[[File:RevDelete Special-RevisionDelete (r60428).png|frame|center|Screenshot of the interface]]',
'logdelete-selected'          => '{{RevisionDelete}}',
'revdelete-text'              => '{{RevisionDelete}}
This is the introduction explaining the feature.',
'revdelete-confirm'           => 'This message is a part of the [[mw:RevisionDelete|RevisionDelete]] feature.
[[File:RevDelete Special-RevisionDelete (r60428).png|frame|center|Screenshot of the interface]]',
'revdelete-legend'            => '{{RevisionDelete}}
[[File:RevDelete Special-RevisionDelete (r60428).png|frame|center|Screenshot of the interface]]',
'revdelete-hide-text'         => 'Option for oversight. This message is a part of the [[mw:RevisionDelete|RevisionDelete]] feature.
[[File:RevDelete Special-RevisionDelete (r60428).png|frame|center|Screenshot of the interface]]',
'revdelete-hide-image'        => 'Option for <del>oversight</del> [[:mw:RevisionDelete|RevisionDelete]] feature.',
'revdelete-hide-name'         => 'Option for oversight',
'revdelete-hide-comment'      => 'Option for oversight. {{RevisionDelete}}
[[File:RevDelete Special-RevisionDelete (r60428).png|frame|center|Screenshot of the interface]]',
'revdelete-hide-user'         => 'Option for oversight. {{RevisionDelete}}
[[File:RevDelete Special-RevisionDelete (r60428).png|frame|center|Screenshot of the interface]]',
'revdelete-hide-restricted'   => 'Option for oversight.',
'revdelete-radio-same'        => 'This message is a part of the [[mw:RevisionDelete|RevisionDelete]] feature. The message is a caption for a column of radioboxes inside a box with {{msg-mw|Revdelete-legend}} as a title.
[[File:RevDelete Special-RevisionDelete (r60428).png|frame|center|Screenshot of the interface]]
There are three radio buttons in each row, and the captions above each column read {{msg-mw|Revdelete-radio-same}}, {{msg-mw|Revdelete-radio-set}}, and {{msg-mw|Revdelete-radio-unset}}.',
'revdelete-radio-set'         => 'This message is a part of the [[mw:RevisionDelete|RevisionDelete]] feature. The message is a caption for a column of radioboxes inside a box with {{msg-mw|Revdelete-legend}} as a title.
[[File:RevDelete Special-RevisionDelete (r60428).png|frame|center|Screenshot of the interface]]
There are three radio buttons in each row, and the captions above each column read {{msg-mw|Revdelete-radio-same}}, {{msg-mw|Revdelete-radio-set}}, and {{msg-mw|Revdelete-radio-unset}}.

{{Identical|Yes}}',
'revdelete-radio-unset'       => 'This message is a part of the [[mw:RevisionDelete|RevisionDelete]] feature. The message is a caption for a column of radioboxes inside a box with {{msg-mw|Revdelete-legend}} as a title.
[[File:RevDelete Special-RevisionDelete (r60428).png|frame|center|Screenshot of the interface]]
There are three radio buttons in each row, and the captions above each column read {{msg-mw|Revdelete-radio-same}}, {{msg-mw|Revdelete-radio-set}}, and {{msg-mw|Revdelete-radio-unset}}.

{{Identical|No}}',
'revdelete-suppress'          => 'Option for oversight',
'revdelete-unsuppress'        => '{{RevisionDelete}}',
'revdelete-log'               => '{{Identical|Reason}}
Log comment text for oversight. {{RevisionDelete}}
[[File:RevDelete Special-RevisionDelete (r60428).png|thumb|331px||Screenshot of the interface]]',
'revdelete-submit'            => '{{RevisionDelete}}
This is the submit button on [[Special:RevisionDelete]].
[[File:RevDelete Special-RevisionDelete (r60428).png|frame|center|Screenshot of the interface]]',
'revdelete-success'           => "{{RevisionDelete}}

Possible alternative text - 'Restrictions on the revision visibility were successfully changed.'",
'revdelete-failure'           => "{{RevisionDelete}}

Possible alternative text - 'Restrictions on the revision visibility could not be changed'",
'logdelete-success'           => "{{RevisionDelete}}

Possible alternative message - 'Restrictions on log entry visibility successfully set.'",
'logdelete-failure'           => "{{RevisionDelete}}

Possible alternative message - 'Restrictions could not be set on the visibility of the log entry.'",
'revdel-restore'              => '{{RevisionDelete}}',
'pagehist'                    => 'Links to page history at Special:RevisionDelete header together with links to the logs and Special:Undelete.',
'deletedhist'                 => 'Links to Special:Undelete at Special:RevisionDelete header together with links to the logs and page history.',
'revdelete-hide-current'      => 'Parameters:
* $1 is a date
* $2 is a time',
'revdelete-show-no-access'    => '{{RevisionDelete}}
Parameters:
* $1 is a date
* $2 is a time',
'revdelete-modify-no-access'  => '{{RevisionDelete}}
Parameters:
* $1 is a date
* $2 is a time',
'revdelete-modify-missing'    => '{{RevisionDelete}}
* $1 is a revision ID',
'revdelete-no-change'         => '{{RevisionDelete}}
Parameters:
* $1 is a date
* $2 is a time',
'revdelete-concurrent-change' => '{{RevisionDelete}}
Parameters:
* $1 is a date
* $2 is a time',
'revdelete-only-restricted'   => '{{RevisionDelete}}
Parameters:
*$1 - date
*$2 - time',
'revdelete-otherreason'       => '{{Identical|Other/additional reason}}
{{RevisionDelete}}
[[File:RevDelete Special-RevisionDelete (r60428).png|frame|center|Screenshot of the interface]]',
'revdelete-reasonotherlist'   => '{{Identical|Other reason}}
{{RevisionDelete}}
[[File:RevDelete Special-RevisionDelete (r60428).png|frame|center|Screenshot of the interface]]',
'revdelete-edit-reasonlist'   => '{{Identical|Edit delete reasons}}
{{RevisionDelete}}
[[File:RevDelete Special-RevisionDelete (r60428).png|frame|center|Screenshot of the interface]]',

# Suppression log
'suppressionlog'     => 'Title of the suppression log. Shown in the drop down menu at [[Special:log]] and as header of [[Special:log/suppress]].',
'suppressionlogtext' => 'Description text of the suppression log. Shown at top of [[Special:log/suppress]].',

# History merging
'mergehistory-autocomment'      => 'This message is used as an edit summary when a redirect is automatically created after an entire page history is merged into another page history, and the user who did the merge wrote no comment.

*Parameter $1 is the name of the redirect page which is created
*Parameter $2 is the target of the redirect',
'mergehistory-comment'          => 'This message is used as an edit summary when a redirect is automatically created after an entire page history is merged into another page history, and the user who did the merge wrote a comment.

*Parameter $1 is the name of the redirect page which is created
*Parameter $2 is the target of the redirect
*Parameter $3 is a log comment for the merge',
'mergehistory-same-destination' => 'Error message shown on [[Special:MergeHistory]] when the user entered the same page title to both source and destination',
'mergehistory-reason'           => '{{Identical|Reason}}',

# Merge log
'mergelog'           => 'This is the name of a log of merge actions done on [[Special:MergeHistory]]. This special page and this log is not enabled by default.',
'pagemerge-logentry' => "This log message is used in a merge log entry.

*Parameter $1 is the page name of the source of the content to be merged.
*Parameter $2 is the page into which the content is merged.
*Parameter $3 is a date?

The log and its associated special page 'MergeHistory' is not enabled by default.

Please note that the parameters in a log entry will appear in the log only in the default language of the wiki. View [[Special:Log]] for examples on translatewiki.net with English default language.",
'mergelogpagetext'   => 'Description of the [http://translatewiki.net/w/i.php?title=Special%3ALog&type=merge&user=&page=&year=&month=-1 merge log], on the log. The associated [[Special:MergeHistory|Merge]] special page is not enabled by default.',

# Diffs
'history-title'            => 'Displayed as page title when you click on the "history" tab. The parameter $1 is the normal page title.',
'difference'               => 'Displayed under the title when viewing the difference between two or more edits.',
'difference-multipage'     => 'Displayed under the title when viewing the difference between two or more pages.
See also {{msg-mw|difference}}.',
'lineno'                   => 'Message used when comparing different versions of a page (diff). $1 is a line number.',
'compareselectedversions'  => 'Used as button in history pages.',
'showhideselectedversions' => 'Text of the button which brings up the [[mw:RevisionDelete|RevisionDelete]] menu.',
'editundo'                 => 'Undo link when viewing diffs
{{Identical|Undo}}',
'diff-multi'               => "This message appears in the revision history of a page when comparing two versions which aren't consecutive.

*Parameter $1 is the number of revisions
*Parameter $2 is the number of distinct users who made those revisions",
'diff-multi-manyusers'     => "This message appears in the revision history of a page when comparing two versions which aren't consecutive, and the intermediate revisions have been edited by more than 100 users. Parameters:
* $1 is the number of revisions, will always be 101 or more.
* $2 is the number of users that were found, which was limited at 100.",

# Search results
'searchresults-title'            => 'Appears as page title in the html header of the search result special page.',
'notitlematches'                 => 'Header of results page after a search for a title for which no page exists',
'textmatches'                    => 'When displaying search results',
'notextmatches'                  => 'Error message when there are no results',
'prevn'                          => "This is part of the navigation message on the top and bottom of Special pages (lists of things in alphabetical order, i.e. the '[[Special:Categories]]' page), where it is used as the first argument of {{msg-mw|Viewprevnext}}.
It is also used by Category pages (which do ''not'' use {{msg-mw|Viewprevnext}}).
{{PLURAL:$1|$1}} is the number of items shown per page. It is not used when {{PLURAL:$1|$1}} is zero; not sure what happens when {{PLURAL:$1|$1}} is one.
[[Special:WhatLinksHere|Whatlinkshere]] pages use {{msg-mw|Whatlinkshere-prev}} instead (still as an argument to {{msg-mw|Viewprevnext}}).

{{Identical|Previous}}",
'nextn'                          => "This is part of the navigation message on the top and bottom of Special pages (lists of things in alphabetical order, i.e. the '[[Special:Categories]]' page), where it is used as the second argument of {{msg-mw|Viewprevnext}}.
It is also used by Category pages (which do ''not'' use {{msg-mw|Viewprevnext}}).
$1 is the number of items shown per page. It is not used when $1 is zero; not sure what happens when $1 is one.
[[Special:WhatLinksHere|Whatlinkshere]] pages use {{msg-mw|Whatlinkshere-next}} instead (still as an argument to {{msg-mw|Viewprevnext}}).

{{Identical|Next $1}}",
'viewprevnext'                   => 'This is part of the navigation message on the top and bottom of Special pages which are lists of things, e.g. the User\'s contributions page (in date order) or the list of all categories (in alphabetical order). ($1) and ($2) are either {{msg-mw|Pager-older-n}} and {{msg-mw|Pager-newer-n}} (for date order) or {{msg-mw|Prevn}} and {{msg-mw|Nextn}} (for alphabetical order).

It is also used by [[Special:WhatLinksHere|Whatlinkshere]] pages, where ($1) and ($2) are {{msg-mw|Whatlinkshere-prev}} and {{msg-mw|Whatlinkshere-next}}.
($3) is made up in all cases of the various proposed numbers of results per page, e.g. "(20 | 50 | 100 | 250 | 500)".
For Special pages, the navigation bar is prefixed by "({{msg-mw|Page_first}} | {{msg-mw|Page_last}})" (alphabetical order) or "({{msg-mw|Histfirst}} | {{msg-mw|Histlast}})" (date order).

Viewprevnext is sometimes preceded by the {{msg-mw|Showingresults}} or {{msg-mw|Showingresultsnum}} message (for Special pages) or by the {{msg-mw|Linkshere}} message (for Whatlinkshere pages).',
'searchmenu-legend'              => '{{Identical|Search options}}',
'searchmenu-exists'              => 'An option shown in a menu beside search form offering a link to the existing page having the specified title (when using the default MediaWiki search engine).',
'searchmenu-new'                 => 'An option shown in a menu beside search form offering a red link to the not yet existing page having the specified title (when using the default MediaWiki search engine).',
'searchhelp-url'                 => '{{Identical|HelpContent}}
Description: The URL of the search help page.
{{doc-important|Do not change the "<tt>Help:</tt>" part.}}',
'searchprofile-articles'         => "A quick link in the advanced search box on [[Special:Search]]. Clicking on this link starts a search in the content pages of the wiki.

A 'content page' is a page that forms part of the purpose of the wiki. It includes the main page and pages in the main namespace and any other namespaces that are included when the wiki is customised. For example on Wikimedia Commons 'content pages' include pages in the file and category namespaces. On Wikinews 'content pages' include pages in the Portal namespace. For technical definition of 'content namespaces' see [http://www.mediawiki.org/wiki/Manual:Using_custom_namespaces#Content_namespaces Mediawiki].

Possible alternatives to the word 'content' are 'subject matter' or 'wiki subject' or 'wiki purpose'.

{{Identical|Content page}}",
'searchprofile-project'          => 'An option in the [[Special:Search]] page.',
'searchprofile-images'           => 'An option in the [http://translatewiki.net/wiki/Special:Search Special:search] page.',
'searchprofile-everything'       => 'An option in the [http://translatewiki.net/wiki/Special:Search Special:search] page.',
'searchprofile-advanced'         => 'An option in the [http://translatewiki.net/wiki/Special:Search Special:Search] page.

{{Identical|Advanced}}',
'searchprofile-articles-tooltip' => '$1 is list of namespaces. {{Identical|Search in $1}}',
'searchprofile-project-tooltip'  => '$1 is list of namespaces. {{Identical|Search in $1}}',
'search-result-size'             => 'Shown per line of a [[Special:Search|search result]]
* $1 is the size of the page in bytes, but no need to add "byte" or similar as the unit is added by special function.
* $2 is the sum of all words in this page.',
'search-result-score'            => 'Shown per line of a [[Special:Search|search result]].

$1 is the relevance of this result in per cent.

{{Identical|Relevance: $1%}}',
'search-redirect'                => "\$1 is a link to the redirect to the page (so, \$1 is the page that the search result is redirected '''from'''). \"Redirect\" is a noun here, not a verb.",
'search-section'                 => 'This text will be shown on the search result listing after the page title of a result if the search algorithm thinks that section is more relevant than the rest of the page. $1 is a section title.',
'search-interwiki-default'       => '* $1 is the hostname of the remote wiki from where the additional results listed below are returned',
'search-interwiki-more'          => '{{Identical|More}}',
'search-relatedarticle'          => '{{Identical|Related}}

This is a search result (and I guess search engine) dependent messages. I do not know how to trigger the feature. The message is displayed if the search result contains information that related pages can also be provided from the search engine. I assume this is "More Like This" functionality. Microsoft glossary defines MLT as "A way to refine search by identifying the right set of documents and then locating similar documents. This allows the searcher to control the direction of the search and focus on the most fruitful lines of inquiry."[http://www.microsoft.com/enterprisesearch/en/us/search-glossary.aspx]',
'mwsuggest-disable'              => "The text of an option on the 'search options' tab of a user's Preferences.",
'searcheverything-enable'        => 'Used in [[Special:Preferences]], tab “Search”.',
'searchrelated'                  => '{{Identical|Related}}

This is a search result (and I guess search engine) dependent messages. I do not know how to trigger the feature. The message is displayed if the search result contains information that related pages can also be provided from the search engine. I assume this is "More Like This" functionality. Microsoft glossary defines MLT as "A way to refine search by identifying the right set of documents and then locating similar documents. This allows the searcher to control the direction of the search and focus on the most fruitful lines of inquiry."[http://www.microsoft.com/enterprisesearch/en/us/search-glossary.aspx]',
'searchall'                      => '{{Identical|All}}',
'showingresults'                 => "This message is used on some special pages such as 'Wanted categories'. $1 is the total number of results in the batch shown and $2 is the number of the first item listed.",
'showingresultsnum'              => '$3 is the number of results on the page and $2 is the first number in the batch of results.',
'showingresultsheader'           => 'Used in search results of [[Special:Search]].',
'nonefound'                      => 'This message appears on the search results page if no results are found.
{{doc-important|Do not translate "all:".}}',
'search-nonefound'               => 'Message shown when a search returned no results (when using the default MediaWiki search engine).',
'powersearch'                    => 'Verb. Text of search button at the bottom of [[Special:Search]], for searching in selected namespaces.

{{Identical|Advanced search}}',
'powersearch-legend'             => 'Advanced search

{{Identical|Advanced search}}',
'powersearch-ns'                 => 'Used in the extended search form at [[Special:Search]]',
'powersearch-redir'              => 'Used in the extended search form at [[Special:Search]]',
'powersearch-field'              => 'Used in the extended search form at [[Special:Search]]',
'powersearch-togglelabel'        => 'Used in [http://translatewiki.net/w/i.php?title=Special:Search&advanced=1 Advanced search]. Synonym: "Select" as verb.',
'powersearch-toggleall'          => '"All" refers to namespaces. It is used in Advanced search: http://translatewiki.net/w/i.php?title=Special:Search&advanced=1
{{Identical|All}}',
'powersearch-togglenone'         => '"None" refers to namespaces. It is used in Advanced search: http://translatewiki.net/w/i.php?title=Special:Search&advanced=1
{{Identical|None}}',
'search-external'                => 'Legend of the fieldset for the input form when the internal search is disabled. Inside the fieldset [[MediaWiki:Searchdisabled]] and [[MediaWiki:Googlesearch]] is shown.',
'searchdisabled'                 => 'Shown on [[Special:Search]] when the internal search is disabled.',

# Quickbar
'qbsettings'                => 'The title of the section in [[Special:Preferences]], only shown when using the skins "Standard/Classic" or "Cologne Blue". The quicbar is the same as the sidebar.',
'qbsettings-none'           => '{{Identical|None}}',
'qbsettings-directionality' => '"Fixed", as in the position "fixed left or right". For left-to-right languages, the quickbar will be positioned at the left, for right-to-left languages at the right.',

# Preferences page
'preferences'                   => 'Title of the Special:Preferences page.

{{Identical|Preferences}}',
'mypreferences'                 => 'Action link label that leads to Special:Preferences; appears in the top menu (e.g. "Username My talk My preferences My watchlist My contributions Log out").

{{Identical|My preferences}}',
'prefs-edits'                   => 'In user preferences.',
'prefsnologin'                  => '{{Identical|Not logged in}}',
'changepassword'                => "Section heading on [[Special:Preferences]], tab 'User profile'.
{{Identical|Change password}}",
'prefs-skin'                    => 'Used in user preferences.
{{Identical|Skin}}',
'skin-preview'                  => 'The link beside each skin name in [[Special:Preferences|your user preferences]], tab "skin".

{{Identical|Preview}}',
'prefs-beta'                    => "Header of a subsection at [[Special:Preferences]], tab ''{{int:prefs-editing}}'', listing features that are in beta but mostly suitable for general use",
'prefs-datetime'                => '{{Identical|Date}}',
'prefs-labs'                    => "Header of a subsection at [[Special:Preferences]], tab ''{{int:prefs-editing}}'', listing features that are experimental",
'prefs-personal'                => 'Title of a tab in [[Special:Preferences]].',
'prefs-rc'                      => 'Used in user preferences.

{{Identical|Recent changes}}',
'prefs-watchlist'               => 'Used in user preferences.',
'prefs-watchlist-days'          => 'Used in [[Special:Preferences]], tab "Watchlist".',
'prefs-watchlist-days-max'      => 'Shown as hint in [[Special:Preferences]], tab "Watchlist"',
'prefs-watchlist-edits'         => 'Used in [[Special:Preferences]], tab "Watchlist".',
'prefs-watchlist-edits-max'     => 'Shown as hint in [[Special:Preferences]], tab "Watchlist"',
'prefs-watchlist-token'         => 'Used in [[Special:Preferences]], tab Watchlist.',
'prefs-misc'                    => 'Tab used on the [[Special:Preferences|user preferences]] special page.',
'prefs-resetpass'               => 'Button on user data tab in user preferences. When you click the button you go to the special page [[Special:ResetPass]].

{{Identical|Change password}}',
'prefs-changeemail'             => 'Link on [[Special:Preferences]] to [[Special:ChangeEmail]].',
'prefs-rendering'               => 'Title of tab in [[Special:Preferences]].',
'saveprefs'                     => 'Button for saving changes in the preferences page.

{{Identical|Save}}',
'resetprefs'                    => 'Button for resetting changes in the preferences page.',
'restoreprefs'                  => 'Used in [[Special:Preferences]]',
'prefs-editing'                 => 'Title of a tab in [[Special:Preferences]].
When changing this message, please also update {{msg-mw|vector-editwarning-warning}} which references to this message.',
'rows'                          => 'Used on [[Special:Preferences]], "Editing" section in the "Size of editing window" fieldset',
'columns'                       => 'Used on [[Special:Preferences]], "Editing" section in the "Size of editing window" fieldset',
'searchresultshead'             => 'This is the label of the tab in [[Special:Preferences|my preferences]] which contains options for searching the wiki.

{{Identical|Search}}',
'resultsperpage'                => "Option on the 'Search options' tab of [[Special:Preferences]]",
'stub-threshold'                => 'Used in [[Special:Preferences]], tab "Misc".',
'stub-threshold-disabled'       => 'Used in [[Special:Preferences]].',
'recentchangesdays'             => 'Used in [[Special:Preferences]], tab "Recent changes".',
'recentchangesdays-max'         => 'Shown as hint in [[Special:Preferences]], tab "Recent changes"',
'recentchangescount'            => 'Used in [[Special:Preferences]], tab "Recent changes".',
'prefs-help-recentchangescount' => 'Used in [[Special:Preferences]], tab "Recent changes".',
'prefs-help-watchlist-token'    => 'Used in [[Special:Preferences]], tab Watchlist.',
'savedprefs'                    => 'This message appears after saving changes to your user preferences.',
'timezonelegend'                => '{{Identical|Time zone}}',
'timezoneuseserverdefault'      => '[[Special:Preferences]] > Date and time > Time zone

This option lets your time zone setting use the one that is used on the wiki (often UTC).',
'timezoneoffset'                => "Text next to input box in [[Special:Preferences]], tab 'date and time', section 'timezone'.",
'allowemail'                    => 'Used in [[Special:Preferences]] > {{int:prefs-personal}} > {{int:email}}.',
'prefs-searchoptions'           => '{{Identical|Search options}}',
'prefs-namespaces'              => "{{Identical|Namespaces}}
Shown as legend of the second fieldset of the tab 'Search' in [[Special:Preferences]]",
'defaultns'                     => 'Used in [[Special:Preferences]], tab "Search".',
'default'                       => '{{Identical|Default}}',
'prefs-files'                   => 'Title of a tab in [[Special:Preferences]].',
'prefs-custom-css'              => 'visible on [[Special:Preferences]] -[Skins].',
'prefs-custom-js'               => 'visible on [[Special:Preferences]] -[Skins].',
'prefs-reset-intro'             => 'Used in [[Special:Preferences/reset]].',
'prefs-emailconfirm-label'      => 'Sub-heading in [[Special:Preferences]] > {{int:prefs-personal}} > {{int:email}}.',
'prefs-textboxsize'             => "Header for the box specifying the size of the editing window, displayed on the 'editing' tab of the [[Special:Preferences|user preferences]] special page.",
'youremail'                     => 'Label of the e-mail text box of the "E-mail options" section of "Special:Preferences".

{{Identical|E-mail}}',
'username'                      => '{{Identical|Username}}',
'uid'                           => '{{Identical|User ID}}',
'prefs-memberingroups'          => 'This message is shown on [[Special:Preferences]], first tab. See also {{msg-mw|prefs-memberingroups-type}}.',
'prefs-memberingroups-type'     => '{{optional}}
Parameters:
* $1 is list of group names
* $2 is list of group member names. Label for these is {{msg-mw|prefs-memberingroups}}.',
'prefs-registration'            => 'Used in [[Special:Preferences]].',
'prefs-registration-date-time'  => '{{optional}}
Used in [[Special:Preferences]]. Parameters are:
* $1 date and time of registration
* $2 date of registration
* $3 time of registration',
'yourrealname'                  => 'Used in [[Special:Preferences]], first tab.
{{Identical|Real name}}',
'yourlanguage'                  => 'Used in [[Special:Preferences]], first tab.
{{Identical|Language}}',
'yourvariant'                   => 'Used in [[Special:Preferences]], first tab, when the wiki content language has variants only.',
'prefs-help-variant'            => 'Explanation of the LanguageConverter feature. It is shown next to a dropdown box where you can choose a variant to convert content to (e.g. Serbian Latin vs. Serbian Cyrillic, or Chinese Traditional vs. Chinese Simplified).',
'yournick'                      => 'Used in [[Special:Preferences]], first tab.

{{Identical|Signature}}',
'prefs-help-signature'          => 'Used in [[Special:Preferences]], tab User profile.',
'badsig'                        => 'Error message displayed when entering invalid signature in user preferences',
'badsiglength'                  => 'Warning message that is displayed on [[Special:Preferences]] when trying to save a signature that is too long. Parameter $1 is the maximum number of characters that is allowed in a signature (multi-byte characters are counted as one character).',
'yourgender'                    => 'Used in [[Special:Preferences]], first tab.
{{Identical|Gender}}',
'gender-unknown'                => 'Used in [[Special:Preferences]], first tab, as one of the selectable options of the {{msg-mw|gender}} prompt. Choosing it indicates that the grammatical gender of the user name is not to be made public, cannot be determined, or matches none of the other choices preset in the select.',
'gender-male'                   => 'Used in [[Special:Preferences]], first tab, as one of the selectable options of the {{msg-mw|gender}} prompt. Choosing it indicates that the grammatical gender of the user name should be "male" for those languages having a "normal" male grammatical gender.
{{Identical|Male}}',
'gender-female'                 => 'Used in [[Special:Preferences]], first tab, as one of the selectable options of the {{msg-mw|gender}} prompt. Choosing it indicates that the grammatical gender of the user name should be "female" for those languages having a "normal" female grammatical gender.
{{Identical|Female}}',
'email'                         => '{{Identical|E-mail}}',
'prefs-help-realname'           => 'In user preferences.
{{Identical|Real name attribution}}',
'prefs-help-email'              => 'Shown as explanation text on [[Special:Preferences]] > {{int:prefs-personal}} > {{int:email}}.',
'prefs-help-email-others'       => 'Виводиться як підказка до поля «Адреса електронної пошти» (друге речення) в налаштуваннях, на вкладці «Особисті дані». Someone translate this to English!!!',
'prefs-info'                    => "Header for the box giving basic information on the user account, displayed on the 'user profile' tab of the [[Special:Preferences|user preferences]] special page.",
'prefs-i18n'                    => 'Field set legend for user preferences regarding the interface language',
'prefs-signature'               => '{{Identical|Signature}}',
'prefs-dateformat'              => 'Used in [[Special:Preferences]], tab "Date and time".',
'prefs-timeoffset'              => 'Used in [[Special:Preferences]], tab "Date and time".',
'prefs-advancedediting'         => 'Used in [[Special:Preferences]], tab "Editing".
{{Identical|Advanced options}}',
'prefs-advancedrc'              => 'Used in [[Special:Preferences]], tab "Recent changes".
{{Identical|Advanced options}}',
'prefs-advancedrendering'       => 'Used in [[Special:Preferences]], tab "Appearence".
{{Identical|Advanced options}}',
'prefs-advancedsearchoptions'   => 'Used in [[Special:Preferences]], tab "Search options".
{{Identical|Advanced options}}',
'prefs-advancedwatchlist'       => 'Used in [[Special:Preferences]], tab "Watchlist".
{{Identical|Advanced options}}',
'prefs-displayrc'               => '"Display" is a noun that specifies the kind of "options". So translate as "options about display", not as "display the options".

Used in [[Special:Preferences]], tab "Recent changes". The display options refer to:
* {{msg-mw|Recentchangesdays}}
* {{msg-mw|Recentchangescount}}',
'prefs-displaysearchoptions'    => '"Display" is a noun that specifies the kind of "options". So translate as "options about display", not as "display the options".

Used in [[Special:Preferences]], tab "Search options". The display options refer to:
* {{msg-mw|Vector-simplesearch-preference}}',
'prefs-displaywatchlist'        => '"Display" is a noun that specifies the kind of "options". So translate as "options about display", not as "display the options".

Used in [[Special:Preferences]], tab "Watchlist". The display options refer to:
* {{msg-mw|Prefs-watchlist-days}}
* {{msg-mw|Prefs-watchlist-edits}}',
'prefs-diffs'                   => 'Used in [[Special:Preferences]], tab "Misc".',

# User rights
'userrights'                     => 'Page title of [[Special:UserRights]].',
'userrights-lookup-user'         => 'Label text when managing user rights ([[Special:UserRights]])',
'userrights-user-editname'       => 'Displayed on [[Special:UserRights]].',
'editusergroup'                  => 'Button name, in page [[Special:Userrights]] (only available to administrators), in the section named {{MediaWiki:userrights-lookup-user}}.

{{Identical|Edit user groups}}',
'editinguser'                    => 'Appears on [[Special:UserRights]]. Parameters:
* $1 is a username
* $2 are user tool links. Example: "(Talk | contribs | block | send e-mail)".',
'userrights-editusergroup'       => '{{Identical|Edit user groups}}',
'saveusergroups'                 => 'Button text when editing user groups',
'userrights-groupsmember'        => 'Used when editing user groups in [[Special:Userrights]]. The messsage is followed by a list of group names.

Parameters:
* $1 - optional, for PLURAL use, the number of items in the list following the message. Avoid PLURAL, if your language can do without.',
'userrights-groupsmember-auto'   => 'Used when editing user groups in [[Special:Userrights]]. The messsage is followed by a list of group names.
"Implicit" is for groups that the user was automatically added to (such as "autoconfirmed"); cf. {{msg-mw|userrights-groupsmember}}

Parameters:
* $1 - optional, for PLURAL use, the number of items in the list following the message. Please avoid PLURAL, if your language can do without.',
'userrights-groups-help'         => 'Instructions displayed on [[Special:UserRights]].',
'userrights-reason'              => 'Text beside log field when editing user groups

{{Identical|Reason}}',
'userrights-no-interwiki'        => 'Error message when editing user groups',
'userrights-nodatabase'          => 'Error message when editing user groups. "Local" means databases/wikis of the same farm/cluster; that is, meta, enwiki, dewiki, commons, etc are all local databases of the Wikimedia Foundation.
See http://meta.wikimedia.org/w/index.php?title=Special%3ALog&type=rights for a usage of local databases: username@barwiki',
'userrights-nologin'             => "Error displayed on [[Special:UserRights]] when you aren't logged in. If you are logged in, but don't have the correct permission, you see {{msg|userrights-notallowed|pl=yes}}.",
'userrights-notallowed'          => "Error displayed on [[Special:UserRights]] when you don't have the permission.",
'userrights-changeable-col'      => 'Used when editing user groups in [[Special:Userrights]]. The messsage is the head of a column of group assignements.

Parameters:
* $1 - optional, for PLURAL use, the number of items in the column following the message. Avoid PLURAL, if your language can do without.',
'userrights-unchangeable-col'    => 'Used when editing user groups in [[Special:Userrights]]. The messsage is the head of a column of group assignements.

Parameters:
* $1 - optional, for PLURAL use, the number of items in the column following the message. Avoid PLURAL, if your language allows that.',
'userrights-irreversible-marker' => '{{optional}}',

# Groups
'group'               => '{{Identical|Group}}',
'group-user'          => '{{doc-group|user}}
{{Identical|User}}',
'group-autoconfirmed' => '{{doc-group|autoconfirmed}}
On Wikimedia sites autoconfirmed users are users which are older than 4 days. After those 4 days, they have more rights.',
'group-bot'           => '{{doc-group|bot}}',
'group-sysop'         => '{{doc-group|sysop}}',
'group-bureaucrat'    => '{{doc-group|bureaucrat}}',
'group-suppress'      => '{{doc-group|suppress}}
This is an optional (disabled by default) user group, meant for the [[mw:RevisionDelete|RevisionDelete]] feature, to change the visibility of revisions through [[Special:RevisionDelete]].

{{Identical|Oversight}}',
'group-all'           => 'The name of the user group that contains all users, including anonymous users

{{Identical|All}}',

'group-user-member'          => '{{doc-group|user|member}}',
'group-autoconfirmed-member' => '{{doc-group|autoconfirmed|member}}',
'group-bot-member'           => '{{doc-group|bot|member}}',
'group-sysop-member'         => '{{doc-group|sysop|member}}',
'group-bureaucrat-member'    => '{{doc-group|bureaucrat|member}}',
'group-suppress-member'      => '{{doc-group|suppress|member}}
This is a member of the optional (disabled by default) user group, meant for the [[mw:RevisionDelete|RevisionDelete]] feature, to change the visibility of revisions through [[Special:RevisionDelete]].

{{Identical|Oversight}}',

'grouppage-user'          => '{{doc-group|user|page}}',
'grouppage-autoconfirmed' => '{{doc-group|autoconfirmed|page}}',
'grouppage-bot'           => '{{doc-group|bot|page}}',
'grouppage-sysop'         => '{{doc-group|sysop|page}}',
'grouppage-bureaucrat'    => '{{doc-group|bureaucrat|page}}',
'grouppage-suppress'      => '{{doc-group|suppress|page}}
{{Identical|Oversight}}',

# Rights
'right-read'                  => '{{doc-right|read}}
Basic right to read any page.',
'right-edit'                  => '{{doc-right|edit}}
Basic right to edit pages that are not protected.',
'right-createpage'            => '{{doc-right|createpage}}
Basic right to create pages. The right to edit discussion/talk pages is {{msg|right-createtalk|pl=yes}}.',
'right-createtalk'            => '{{doc-right|createtalk}}
Basic right to create discussion/talk pages. The right to edit other pages is {{msg|right-createpage|pl=yes}}.',
'right-createaccount'         => '{{doc-right|createaccount}}
The right to [[Special:CreateAccount|create a user account]].',
'right-minoredit'             => '{{doc-right|minoredit}}
The right to use the "This is a minor edit" checkbox. See {{msg|minoredit|pl=yes}} for the message used for that checkbox.',
'right-move'                  => '{{doc-right|move}}
The right to move any page that is not protected from moving.',
'right-move-subpages'         => '{{doc-right|move-subpages}}',
'right-move-rootuserpages'    => '{{doc-right|move-rootuserpages}}',
'right-movefile'              => '{{doc-right|movefile}}',
'right-suppressredirect'      => '{{doc-right|suppressredirect}}',
'right-upload'                => '{{doc-right|upload}}
The right to [[Special:Upload|upload]] a file (this includes images, media, audio, ...).',
'right-reupload'              => '{{doc-right|reupload}}
The right to upload a file under a file name that already exists. Related messages: {{msg|right-upload|pl=yes}}, {{msg|right-reupload-own|pl=yes}} and {{msg|right-reupload-shared|pl=yes}}.',
'right-reupload-own'          => '{{doc-right|reupload-own}}
Right to upload a file under a file name that already exists, and that the same user has uploaded. Related messages: {{msg|right-upload|pl=yes}} and {{msg|right-reupload|pl=yes}}.',
'right-reupload-shared'       => '{{doc-right|reupload-shared}}
The right to upload a file locally under a file name that already exists in a shared database (for example Commons). Related messages: {{msg|right-upload|pl=yes}} and {{msg|right-reupload|pl=yes}}.',
'right-upload_by_url'         => '{{doc-right|upload by url}}',
'right-purge'                 => '{{doc-right|purge}}
The right to use <tt>&action=purge</tt> in the URL, without needing to confirm it (by default, anonymous users need to confirm it).',
'right-autoconfirmed'         => "{{doc-right|autoconfirmed}}
If your account is older than [[mw:Manual:\$wgAutoConfirmAge|wgAutoConfirmAge]] and if you have at least [[mw:Manual:\$wgAutoConfirmCount|\$wgAutoConfirmCount]] edits, you are in the '''group \"autoconfirmed\"''' (note that you can't see this group at [[Special:ListUsers]]).
If you are in that group, you have (by default) the '''right \"autoconfirmed\"'''. With this right, you can for example <!-- I think this right includes more things --> edit semi-protected pages.",
'right-bot'                   => '{{doc-right|bot}}',
'right-nominornewtalk'        => '{{doc-right|nominornewtalk}}
If someone with this right (bots by default) edits a user talk page and marks it as minor (requires {{msg|right-minoredit|pl=yes}}), the user will not get a notification "You have new messages".',
'right-apihighlimits'         => '{{doc-right|apihighlimits}}',
'right-writeapi'              => '{{doc-right|writeapi}}',
'right-delete'                => '{{doc-right|delete}}',
'right-bigdelete'             => '{{doc-right|bigdelete}}',
'right-deleterevision'        => '{{doc-right|deleterevision}}
This user right is part of the [[mw:RevisionDelete|RevisionDelete]] feature.
It can be given to the group {{msg|group-sysop|pl=yes}}, although this right is disabled by default.

See also
* {{msg|right-suppressionlog|pl=yes}}
* {{msg|right-hideuser|pl=yes}}
* {{msg|right-suppressrevision|pl=yes}}',
'right-deletedhistory'        => '{{doc-right|deletedhistory}}',
'right-deletedtext'           => '{{doc-right|deletedtext}}',
'right-browsearchive'         => '{{doc-right|browsearchive}}',
'right-undelete'              => '{{doc-right|undelete}}',
'right-suppressrevision'      => '{{doc-right|suppressrevision}}
This user right is part of the [[mw:RevisionDelete|RevisionDelete]] feature.
It can be given to the group {{msg|group-suppress|pl=yes}}, although that group is disabled by default.

See also
* {{msg|right-suppressionlog|pl=yes}}
* {{msg|right-hideuser|pl=yes}}
* {{msg|right-deleterevision|pl=yes}}',
'right-suppressionlog'        => '{{doc-right|suppressionlog}}
This user right is part of the [[mw:RevisionDelete|RevisionDelete]] feature.
It can be given to the group {{msg|group-suppress|pl=yes}}, although that group is disabled by default.

See also
* {{msg|right-suppressrevision|pl=yes}}
* {{msg|right-hideuser|pl=yes}}
* {{msg|right-deleterevision|pl=yes}}',
'right-block'                 => '{{doc-right|block}}',
'right-blockemail'            => '{{doc-right|blockemail}}',
'right-hideuser'              => '{{doc-right|hideuser}}
This user right is part of the [[mw:RevisionDelete|RevisionDelete]] feature.
It can be given to the group {{msg|group-suppress|pl=yes}}, although that group is disabled by default.

See also
* {{msg|right-suppressionlog|pl=yes}}
* {{msg|right-suppressrevision|pl=yes}}
* {{msg|right-deleterevision|pl=yes}}',
'right-ipblock-exempt'        => '{{doc-right|ipblock-exempt}}
This user automatically bypasses IP blocks, auto-blocks and range blocks - so I presume - but I am uncertain',
'right-proxyunbannable'       => '{{doc-right|proxyunbannable}}',
'right-unblockself'           => '{{doc-right|unblockself}}',
'right-protect'               => '{{doc-right|protect}}',
'right-editprotected'         => '{{doc-right|editprotected}}',
'right-editinterface'         => '{{doc-right|editinterface}}',
'right-editusercssjs'         => '{{doc-right|editusercssjs}}',
'right-editusercss'           => '{{doc-right|editusercss}}',
'right-edituserjs'            => '{{doc-right|edituserjs}}',
'right-rollback'              => '{{doc-right|rollback}}
{{Identical|Rollback}}',
'right-markbotedits'          => '{{doc-right|markbotedits}}
A user with this right can mark a roll-back edit as a bot edit by adding <tt>&bot=1</tt> to the URL (not by default).',
'right-noratelimit'           => '{{doc-right|noratelimit}}
The rate limits have no effect on the groups that have this right. Rate limits is a restriction that you can only do X actions (edits, moves, etc.) in Y number of seconds (set by [[mw:Manual:$wgRateLimits|$wgRateLimits]]).',
'right-import'                => '{{doc-right|import}}',
'right-importupload'          => '{{doc-right|importupload}}',
'right-patrol'                => '{{doc-right|patrol}}',
'right-autopatrol'            => '{{doc-right|autopatrol}}',
'right-patrolmarks'           => '{{doc-right|patrolmarks}}',
'right-unwatchedpages'        => '{{doc-right|unwatchedpages}}',
'right-trackback'             => '{{doc-right|trackback}}

"Submit" in this instance means that something called [[:wikipedia:trackback|trackback]] is being sent to the wiki, and the wiki accepts it. When the right is not given to the user, the wiki rejects, or ignores it. There is a nice description at [http://cruftbox.com/cruft/docs/trackback.html How TrackBack Works]. In MediaWiki it is one of those obscure features that probably nobody uses (it is a huge spam trap).

An alternative wording for translators could be \'Get the wiki to accept a trackback\'.',
'right-mergehistory'          => '{{doc-right|mergehistory}}',
'right-userrights'            => '{{doc-right|userrights}}',
'right-userrights-interwiki'  => '{{doc-right|userrights-interwiki}}',
'right-siteadmin'             => '{{doc-right|siteadmin}}',
'right-override-export-depth' => '{{doc-right|override-export-depth}}',
'right-sendemail'             => '{{doc-right|sendemail}}',
'right-passwordreset'         => '{{doc-right|passwordreset}}',

# User rights log
'rightslog'                  => 'In [[Special:Log]]',
'rightslogtext'              => 'Text in [[Special:Log/rights]].',
'rightslogentry'             => 'This message is displayed in the [[Special:Log/rights|User Rights Log]] when a bureaucrat changes the user groups for a user.

* Parameter $1 is the username
* Parameters $2 and $3 are lists of user groups or {{msg-mw|Rightsnone}}

The name of the bureaucrat who did this task appears before this message.

Similar to {{msg-mw|Gur-rightslog-entry}}',
'rightslogentry-autopromote' => 'This message is displayed in the [[Special:Log/rights|User Rights Log]] when a user is automatically promoted to a user group.

Parameters:
* $2 is a comma separated list of old user groups or {{msg-mw|Rightsnone}}
* $3 is a comma separated list of new user groups',
'rightsnone'                 => 'Default rights for registered users.

{{Identical|None}}',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => '{{Doc-action|read}}',
'action-edit'                 => '{{Doc-action|edit}}',
'action-createpage'           => '{{Doc-action|createpage}}',
'action-createtalk'           => '{{Doc-action|createtalk}}',
'action-createaccount'        => '{{Doc-action|createaccount}}',
'action-minoredit'            => '{{Doc-action|minoredit}}',
'action-move'                 => '{{Doc-action|move}}',
'action-move-subpages'        => '{{Doc-action|move-subpages}}',
'action-move-rootuserpages'   => '{{Doc-action|move-rootuserpages}}',
'action-movefile'             => '{{doc-action|movefile}}',
'action-upload'               => '{{Doc-action|upload}}',
'action-reupload'             => '{{Doc-action|reupload}}',
'action-reupload-shared'      => '{{Doc-action|reupload-shared}}',
'action-upload_by_url'        => '{{Doc-action|upload by url}}',
'action-writeapi'             => '{{Doc-action|writeapi}}

API is an abbreviation for [http://en.wikipedia.org/wiki/API application programming interface].',
'action-delete'               => '{{Doc-action|delete}}',
'action-deleterevision'       => '{{Doc-action|deleterevision}}',
'action-deletedhistory'       => '{{Doc-action|deletedhistory}}',
'action-browsearchive'        => '{{Doc-action|browsearchive}}',
'action-undelete'             => '{{Doc-action|undelete}}',
'action-suppressrevision'     => '{{Doc-action|suppressrevision}}',
'action-suppressionlog'       => '{{Doc-action|suppressionlog}}',
'action-block'                => '{{Doc-action|block}}',
'action-protect'              => '{{Doc-action|protect}}',
'action-import'               => '{{Doc-action|import}}',
'action-importupload'         => '{{Doc-action|importupload}}',
'action-patrol'               => '{{Doc-action|patrol}}',
'action-autopatrol'           => '{{Doc-action|autopatrol}}',
'action-unwatchedpages'       => '{{Doc-action|unwatchedpages}}',
'action-trackback'            => '{{Doc-action|trackback}}',
'action-mergehistory'         => '{{Doc-action|mergehistory}}',
'action-userrights'           => '{{Doc-action|userrights}}

This action allows editing of all of the "user rights", not just the rights of the group "all users".',
'action-userrights-interwiki' => '{{Doc-action|userrights-interwiki}}',
'action-siteadmin'            => '{{Doc-action|siteadmin}}',

# Recent changes
'nchanges'                        => 'Appears on the [[Special:RecentChanges]] special page in brackets after pages having more than one change on that date. $1 is the number of changes on that day.',
'recentchanges'                   => 'The text of the link in sidebar going to the special page [[Special:RecentChanges]]. Also the page title of that special page.

{{Identical|Recent changes}}',
'recentchanges-legend'            => 'Legend of the fieldset of [[Special:RecentChanges]]',
'recentchangestext'               => 'Text in recent changes',
'recentchanges-label-newpage'     => 'Tooltip for {{msg-mw|newpageletter}}',
'recentchanges-label-minor'       => 'Tooltip for {{msg-mw|newpageletter}}',
'recentchanges-label-bot'         => 'Tooltip for {{msg-mw|boteditletter}}',
'recentchanges-label-unpatrolled' => 'Tooltip for {{msg-mw|unpatrolledletter}}',
'rcnote'                          => 'Used on [[Special:RecentChanges]].
* $1 is the number of changes shown,
* $2 is the number of days for which the changes are shown,
* $3 is a date and time (deprecated),
* $4 is a date alone,
* $5 is a time alone.

 <!--Example: "\'\'Below are the last 50 changes in the last 7 days, as of 14:48, 24 January 2008.\'\'"-->
 Example: "\'\'{{int:rcnote/en|50|7||24 January 2008|14:48}}\'\'"',
'rcnotefrom'                      => 'This message is displayed at [[Special:RecentChanges]] when viewing recentchanges from some specific time. The corrosponding message is {{msg-mw|Rclistfrom}} (without split of date and time, [[bugzilla:19104|Bug 19104]]).

Parameter $1 is the maximum number of changes that are displayed.
Parameter $2 is a date and time.
Parameter $3 is a date.
Parameter $4 is a time.',
'rclistfrom'                      => 'Used on [[Special:RecentChanges]]. Parameter $1 is a link to the revision of a specific date and time. The date and the time are the link description (without split of date and time, [[bugzilla:19104|Bug 19104]]). The corrosponding message is {{msg-mw|Rcnotefrom}}.',
'rcshowhideminor'                 => "Option text in [[Special:RecentChanges]]. Parameters:
* $1 is the 'show/hide' command, with the text taken from either {{msg-mw|show}} or {{msg-mw|hide}}.",
'rcshowhidebots'                  => "Option text in [[Special:RecentChanges]]. Parameters:
* $1 is the 'show/hide' command, with the text taken from either {{msg-mw|show}} or {{msg-mw|hide}}.
{{Identical|$1 bots}}",
'rcshowhideliu'                   => 'Option text in [[Special:RecentChanges]]',
'rcshowhideanons'                 => "Option text in [[Special:RecentChanges]]. Parameters:
* $1 is the 'show/hide' command, with the text taken from either {{msg-mw|show}} or {{msg-mw|hide}}.",
'rcshowhidepatr'                  => "Option text in [[Special:RecentChanges]]. Parameters:
* $1 is the 'show/hide' command, with the text taken from either {{msg-mw|show}} or {{msg-mw|hide}}.",
'rcshowhidemine'                  => "Option text in [[Special:RecentChanges]]. Parameters:
* $1 is the 'show/hide' command, with the text taken from either {{msg-mw|show}} or {{msg-mw|hide}}.",
'rclinks'                         => "Used on [[Special:RecentChanges]].
* '''\$1''' is a list of different choices with number of pages to be shown.<br />&nbsp;Example: \"''50{{int:pipe-separator}}100{{int:pipe-separator}}250{{int:pipe-separator}}500\".
* '''\$2''' is a list of clickable links with a number of days for which recent changes are to be displayed.<br />&nbsp;Example: \"''1{{int:pipe-separator}}3{{int:pipe-separator}}7{{int:pipe-separator}}14{{int:pipe-separator}}30''\".
* '''\$3''' is a block of text that consists of other messages.<br />&nbsp;Example: \"''Hide minor edits{{int:pipe-separator}}Show bots{{int:pipe-separator}}Hide anonymous users{{int:pipe-separator}}Hide logged-in users{{int:pipe-separator}}Hide patrolled edits{{int:pipe-separator}}Hide my edits''\"
List elements are separated by {{msg-mw|pipe-separator}} each. Each list element is, or contains, a link.",
'diff'                            => 'Short form of "differences". Used on [[Special:RecentChanges]], [[Special:Watchlist]], ...',
'hist'                            => 'Short form of "history". Used on [[Special:RecentChanges]], [[Special:Watchlist]], ...',
'hide'                            => 'Option text in [[Special:RecentChanges]], and in [[Special:WhatLinksHere]]

{{Identical|Hide}}',
'show'                            => '{{Identical|Show}}',
'minoreditletter'                 => "Very short form of \"'''minor edit'''\". Used in [[Special:RecentChanges]], [[Special:Watchlist]], [[Special:Contributions]] and history pages.",
'newpageletter'                   => "Very short form of \"'''new page'''\". Used in [[Special:RecentChanges]], [[Special:Watchlist]] and [[Special:Contributions]].",
'boteditletter'                   => 'Abbreviation of "bot". Appears in [[Special:RecentChanges]] and [[Special:Watchlist]].',
'unpatrolledletter'               => '{{optional}}

Used in {{msg-mw|Recentchanges-label-legend}}, meaning "unpatrolled".',
'sectionlink'                     => '{{optional}}',
'rc_categories'                   => "Probably to do with 'recent changes' special page, either in a particular skin, or for a particular user group.

I guess that this should appear before an input box where you can specify that recent changes should be shown for pages belonging to certain categories only. You name the categories in the input box, and separate them by a pipe character. If this is right, then you should be able to use 'restrict' instead of 'limit', or even 'show pages in the following categories only'.",
'rc-change-size'                  => '{{optional}}

Does not work under $wgMiserMode ([[mwr:48986|r48986]]).',
'newsectionsummary'               => 'Default summary when adding a new section to a page.',

# Recent changes linked
'recentchangeslinked'          => 'Title of [[Special:RecentChangesLinked]] and display name of page on [[Special:SpecialPages]].',
'recentchangeslinked-feed'     => 'Title of [[Special:RecentChangesLinked]] and display name of page on [[Special:SpecialPages]].',
'recentchangeslinked-toolbox'  => 'Title of [[Special:RecentChangesLinked]] and display name of page on [[Special:SpecialPages]].',
'recentchangeslinked-title'    => 'Message used as title and page header on [[Special:RecentChangesLinked]] (needs an argument like "/Main Page"). Related changes are all recent change to pages that are linked from \'\'this page\'\'. "$1" is the name of the page for which related changes as show.',
'recentchangeslinked-backlink' => '{{optional}}',
'recentchangeslinked-summary'  => 'Summary of [[Special:RecentChangesLinked]].',
'recentchangeslinked-page'     => '{{Identical|Page name}}',
'recentchangeslinked-to'       => 'Checkbox in [[Special:RecentChangesLinked]].',

# Upload
'upload'                      => 'Display name for link to [[Special:Upload]] for uploading files to the wiki.

{{Identical|Upload file}}',
'uploadbtn'                   => 'Button name in [[Special:Upload]].

{{Identical|Upload file}}',
'uploadnologin'               => '{{Identical|Not logged in}}',
'uploadtext'                  => "{{doc-important|''thumb'' and ''left'' are magic words. Leave it untranslated!}}
Text displayed when uploading a file using [[Special:Upload]].",
'upload-permitted'            => 'Used in [[Special:Upload]].',
'upload-preferred'            => 'Used in [[Special:Upload]].',
'upload-prohibited'           => 'Used in [[Special:Upload]].',
'uploadlogpage'               => 'Page title of [[Special:Log/upload]].',
'filename'                    => '{{Identical|Filename}}',
'filedesc'                    => '{{Identical|Summary}}',
'fileuploadsummary'           => '{{Identical|Summary}}',
'filereuploadsummary'         => 'Label of textearea in Special:Upload when uploading a new version of existing file.',
'filesource'                  => 'On page [[Special:Upload]] if defined $wgUseCopyrightUpload for detailed copyright information forms. This is source of file.

{{Identical|Source}}',
'ignorewarnings'              => 'In [[Special:Upload]]',
'filetype-mime-mismatch'      => 'Upload error. Parameters:
* $1 is the extension of the uploaded file
* $2 is the MIME type of the uploaded file',
'filetype-bad-ie-mime'        => '$1 will contain a mime type like <tt>image/jpeg</tt> or <tt>application/zip</tt>',
'filetype-unwanted-type'      => "* $1 is the extension of the file which cannot be uploaded
* $2 is the list of file extensions that can be uploaded (Example: ''png, gif, jpg, jpeg, ogg, pdf, svg.'')
* $3 is the number of allowed file formats (to be used for the PLURAL function)",
'filetype-banned-type'        => "* $1 is the extension(s) of the file which cannot be uploaded
* $2 is the list of file extensions that can be uploaded (Example: ''png, gif, jpg, jpeg, ogg, pdf, svg.'')
* $3 is the number of allowed file formats (to be used for the PLURAL function)
* $4 is the number of extensions that could not be uploaded (to be used for the PLURAL function)",
'filetype-missing'            => 'Error when uploading a file with no extension',
'verification-error'          => 'Error message shown when an uploaded file contents does not pass verification, i.e. the file is corrupted, it is not the type it claims to be etc.',
'large-file'                  => 'Variables $1 and $2 have appropriate unit symbols already. See for example [[Mediawiki:size-kilobytes]].',
'largefileserver'             => 'Error message when uploading a file whose size is larger than the maximum allowed',
'emptyfile'                   => 'Error message when trying to upload an empty file',
'fileexists'                  => "{{doc-important|''thumb'' is a magic word. Leave it untranslated!}}",
'filepageexists'              => "{{doc-important|''thumb'' is a magic word. Leave it untranslated!}}
Shown on [[Special:Upload]], $1 is link to the page. This message is displayed if a description page exists, but a file with the same name does not yet exists, and a user tries to upload a file with that name. In that case the description page is not changed, even if the uploading user specifies a description with the upload.",
'fileexists-extension'        => "{{doc-important|''thumb'' is a magic word. Leave it untranslated!}}",
'fileexists-thumbnail-yes'    => "{{doc-important|''thumb'' is a magic word. Leave it untranslated!}}",
'file-thumbnail-no'           => 'Error message at [[Special:Upload]]',
'fileexists-forbidden'        => "{{doc-important|''thumb'' and ''center'' are magic words. Leave it untranslated!}}",
'fileexists-shared-forbidden' => "{{doc-important|''thumb'' and ''center'' are magic words. Leave it untranslated!}}
Error message at [[Special:Upload]]",
'savefile'                    => 'When uploading a file',
'uploadedimage'               => 'This is the text of an entry in the [[Special:Log|upload log]] (and Recent Changes), after hour (and date, only in the Upload log) and user name. $1 is the name of the file uploaded.',
'overwroteimage'              => 'This is the text of an entry in the [[Special:Log|upload log]] (and Recent Changes), after hour (and date, only in the Upload log) and user name. $1 is the name of the file uploaded.',
'uploaddisabled'              => 'Title of the Special:Upload page when upload is disabled.',
'uploaddisabledtext'          => 'This message can have parameter $1, which contains the name of the target file. See r22243 and [https://bugzilla.wikimedia.org/show_bug.cgi?id=8818 bug 8818].',
'php-uploaddisabledtext'      => 'This means that file uploading is disabled in PHP, not upload of PHP-files.',
'uploadvirus'                 => 'Note displayed when uploaded file contains a virus',
'upload-source'               => 'Caption above a section of the [[Special:Upload]] page',
'sourcefilename'              => 'In [[Special:Upload]]',
'sourceurl'                   => 'Caption for the upload-by-url feature at [[Special:Upload]]',
'destfilename'                => 'In [[Special:Upload]]',
'upload-maxfilesize'          => 'Shows at [[Special:Upload]] the maximum file size that can be uploaded.

$1 is the value in KB/MB/GB',
'upload-description'          => 'Caption above a section of the [[Special:Upload]] page',
'upload-options'              => 'Caption above a section of the [[Special:Upload]] page',
'watchthisupload'             => 'In [[Special:Upload]]',
'filewasdeleted'              => 'This warning is shown when trying to upload a file that does not exist, but has previously been deleted.
Parameters:
* $1 is a link to the deletion log, with the text from {{msg|deletionlog}}.',
'filename-prefix-blacklist'   => "Do not translate the file name prefixes before the hash mark (#). Leave all the wiki markup, including the spaces, as is. You can translate the text, including 'Leave this line exactly as it is'. The first line of this messages has one (1) leading space.",
'upload-success-msg'          => '$1 is the local file name after uploading
$2 is the url the file was uploaded from, when using upload-by-url.',
'upload-failure-msg'          => '$1 is the specific error message
$2 is the url the file tried to upload from, when using upload-by-url.',
'upload-warning-msg'          => '$1 is the url the file was uploaded from, when using upload-by-url
$2 is the session key for the upload',

'upload-proto-error-text' => '"Remote upload" is explained on [http://en.wikipedia.org/wiki/Uploading_and_downloading#Remote_upload Wikipedia].',
'upload-file-error'       => 'Unused on core [http://svn.wikimedia.org/viewvc/mediawiki/trunk/phase3/includes/specials/SpecialUpload.php?r1=53161&r2=53282].

Extensions making use of it:
*ImportFreeImages
*SemanticForms


{{Identical|Internal error}}',

# ZipDirectoryReader
'zip-unsupported' => "Perhaps translations of 'software' can be used instead of 'features' and 'understood' or 'handled' instead of 'supported'.",

# img_auth script messages
'img-auth-accessdenied' => '[[mw:Manual:Image Authorization|Manual:Image Authorization]]: Access Denied
{{Identical|Access denied}}',
'img-auth-nopathinfo'   => '[[mw:Manual:Image Authorization|Manual:Image Authorization]]: Missing PATH_INFO - see english description',
'img-auth-notindir'     => '[[mw:Manual:Image Authorization|Manual:Image Authorization]]: When the specified path is not in upload directory.',
'img-auth-badtitle'     => '[[mw:Manual:Image Authorization|Manual:Image Authorization]]: Bad title, $1 is the invalid title',
'img-auth-nologinnWL'   => '[[mw:Manual:Image Authorization|Manual:Image Authorization]]: Logged in and file not whitelisted. $1 is the file not in whitelist.',
'img-auth-nofile'       => '[[mw:Manual:Image Authorization|Manual:Image Authorization]]: Non existent file, $1 is the file that does not exist.',
'img-auth-isdir'        => '[[mw:Manual:Image Authorization|Manual:Image Authorization]]: Trying to access a directory instead of a file, $1 is the directory.',
'img-auth-streaming'    => '[[mw:Manual:Image Authorization|Manual:Image Authorization]]: Is now streaming file specified by $1.',
'img-auth-public'       => '[[mw:Manual:Image Authorization|Manual:Image Authorization]]: An error message when the admin has configured the wiki to be a public wiki, but is using img_auth script - normally this is a configuration error, except when special restriction extensions are used',
'img-auth-noread'       => '[[mw:Manual:Image Authorization|Manual:Image Authorization]]: User does not have access to read file, $1 is the file',

# HTTP errors
'http-invalid-scheme' => 'The message appears in the Mediawiki code as follows:

 if ( $this->parsedUrl[\'scheme\'] != \'http\' ) {
    $this->status->fatal( \'http-invalid-scheme\', $this->parsedUrl[\'scheme\'] );
 }

Siebrand think this has to do with allowing MediaWiki to fetch remote URLs, and in that not allowing anything but "http://" request. So if this for example is "irc://" or "https://", $1 would be "irc" or "https" respectively.

If \'scheme\' is difficult to translate, then you could use \'prefix\' instead.',
'http-bad-status'     => '$1 is an HTTP error code (e.g. 404), $2 is the HTTP error message (e.g. File Not Found)',

'license'           => 'This appears in the upload form for the license drop-down. The header in the file description page is now at {{msg-mw|License-header}}.',
'nolicense'         => '{{Identical|None selected}}',
'license-nopreview' => 'Error message when a certain license does not exist',

# Special:ListFiles
'listfiles-summary'     => 'This message is displayed at the top of [[Special:ImageList]] to explain how to use that special page.',
'listfiles_search_for'  => 'Input label for the form displayed on [[Special:ListFiles]].',
'imgfile'               => '{{Identical|File}}',
'listfiles'             => 'Page title and grouping label for the form displayed on [[Special:ListFiles]].
{{Identical|File list}}',
'listfiles_thumb'       => '{{Identical|Thumbnail}}',
'listfiles_date'        => 'Column header for the result table displayed on [[Special:ListFiles]].
{{Identical|Date}}',
'listfiles_name'        => 'Column header for the result table displayed on [[Special:ListFiles]].
{{Identical|Name}}',
'listfiles_user'        => 'Column header for the result table displayed on [[Special:ListFiles]].
{{Identical|User}}',
'listfiles_size'        => 'Column header for the result table displayed on [[Special:ListFiles]].
{{Identical|Size}}',
'listfiles_description' => 'Column header for the result table displayed on [[Special:ListFiles]].
{{Identical|Description}}',
'listfiles_count'       => 'One of the table column headers in [[Special:Listfiles]] denoting the amount of saved versions of that file.',

# File description page
'file-anchor-link'                  => '{{Identical|File}}',
'filehist'                          => 'Text shown on a media description page. Heads the section where the different versions of the file are displayed.',
'filehist-help'                     => 'In file description page',
'filehist-deleteall'                => 'Link in image description page for admins.',
'filehist-deleteone'                => 'Link description on file description page to delete an earlier version of a file.

{{Identical|Delete}}',
'filehist-revert'                   => 'Link in image description page.

{{Identical|Revert}}',
'filehist-current'                  => 'Link in file description page.

{{Identical|Current}}',
'filehist-datetime'                 => 'Used on image descriptions, see for example [[:File:Yes.png#filehistory]].
{{Identical|Date}}',
'filehist-thumb'                    => 'Shown in the file history list of a file description page.

Example: [[:Image:Addon-icn.png]]
{{Identical|Thumbnail}}',
'filehist-thumbtext'                => "Shown in the file history list of a file description page.
* '''$1''' is a time followed by a date, e.g. ''10:23, 18 april 2007''.
* '''$2''' is the date,
* '''$3''' is the time.

Example: [[wikipedia:Image:Madeleine close2.jpg]]",
'filehist-nothumb'                  => 'Shown if no thumbnail is available in the file history list of a file desription page.

Example: [[:Image:Addon-icn.png]]',
'filehist-user'                     => 'In image description page.

{{Identical|User}}',
'filehist-dimensions'               => 'In file description page',
'filehist-filesize'                 => 'In image description page',
'filehist-comment'                  => 'In file description page

{{Identical|Comment}}',
'imagelinks'                        => 'In top header of the image description page, see for example [[:Image:Yes.png]]. Shows a list of pages where this file is used.',
'linkstoimage'                      => 'Used on image description, see for example [[:Image:Yes.png#filelinks]].
* Parameter $1 is the number of pages that link to the file/image.',
'linkstoimage-more'                 => 'Shown on an image description page when a file is used/linked more than 100 times on other pages.

* $1: limit. At the moment hardcoded at 100
* $2: filename',
'nolinkstoimage'                    => 'Displayed on image description pages, see for exampe [[:Image:Tournesol.png#filelinks]].',
'linkstoimage-redirect'             => 'Item in the "the following pages link to this file" section on a file page if the item is a redirect.
* $1: an HTML link to the file
* $2: the list of files that link to the redirect (may be empty)',
'duplicatesoffile'                  => 'Shown on file description pages when a file is duplicated

* $1: Number of identical files
* $2: Name of the shown file to link to the special page "FileDuplicateSearch"',
'sharedupload'                      => 'Shown on an image description page when it is used in a central repository (i.e. [http://commons.wikimedia.org/ Commons] for Wikimedia wikis).

* $1 is the name of the shared repository. On Wikimedia sites, $1 is {{msg-mw|shared-repo-name-shared}}. The default value for $1 is {{msg-mw|shared-repo}}.

{{doc-important|Do not customise this message. Just translate it.|Customisation should be done by local wikis.}}',
'sharedupload-desc-there'           => ':See also: {{msg-mw|Sharedupload}}',
'sharedupload-desc-here'            => ':See also: {{msg-mw|Sharedupload}}',
'filepage-nofile'                   => "This message appears when visiting a File page for which there's no file, if the user cannot upload files, or file uploads are disabled. (Otherwise, see {{msg-mw|Filepage-nofile-link}})

Filepage-nofile and Filepage-nofile-link message deprecate {{msg-mw|Noimage}}",
'filepage-nofile-link'              => "This message appears when visiting a File page for which there's no file, if the user can upload files, and file uploads are enabled. (Otherwise, see {{msg-mw|Filepage-nofile}})

$1 - URL of upload page for this file.

Filepage-nofile and Filepage-nofile-link message deprecate {{msg-mw|Noimage}}",
'uploadnewversion-linktext'         => 'Used on pages in the "File" namespace to update the file version.',
'shared-repo-from'                  => 'This message is shown on an image description page when a duplicate of the image exists on a shared repository such as Wikimedia Commons.

Example: http://test.wikipedia.org/wiki/File:Wiki.png#filelinks

$1 is the name of the shared repository. On wikimedia sites, $1 is {{msg-mw|shared-repo-name-shared}}. The default value for $1 is {{msg-mw|shared-repo}}.',
'shared-repo'                       => 'This message can be used as parameter $1 in the following messages:
* {{msg-mw|shared-repo-from}}
* {{msg-mw|sharedupload}}, {{msg-mw|sharedupload-desc-here}}, {{msg-mw|sharedupload-desc-there}}',
'shared-repo-name-wikimediacommons' => '{{optional}}',
'filepage.css'                      => '{{Optional}}',

# File reversion
'filerevert'                => '{{Identical|Revert}}',
'filerevert-backlink'       => '{{optional}}',
'filerevert-legend'         => '{{Identical|Revert}}',
'filerevert-intro'          => 'Message displayed when you try to revert a version of a file.
* $1 is the name of the media
* $2 is a date
* $3 is a hour
* $4 is a URL and must follow square bracket: [$4
{{Identical|Revert}}',
'filerevert-comment'        => '{{Identical|Reason}}',
'filerevert-defaultcomment' => '* $1 is a date
* $2 is an hour
{{Identical|Revert}}',
'filerevert-submit'         => '{{Identical|Revert}}',
'filerevert-success'        => 'Message displayed when you succeed in reverting a version of a file.
* $1 is the name of the media
* $2 is a date
* $3 is a hour
* $4 is an URL and must follow square bracket: [$4
{{Identical|Revert}}',

# File deletion
'filedelete-backlink'         => '{{optional}}',
'filedelete-legend'           => '{{Identical|Delete}}',
'filedelete-intro-old'        => 'Message displayed when you try to delete a version of a file.
* $1 is the name of the media
* $2 is a date
* $3 is a hour
* $4 is a URL and must follow square bracket: [$4',
'filedelete-comment'          => '{{Identical|Reason}}',
'filedelete-submit'           => 'Delete button when deleting a file for admins

{{Identical|Delete}}',
'filedelete-success-old'      => 'Message displayed when you succeed in deleting a version of a file.
* $1 is the name of the media
* $2 is a date
* $3 is a hour',
'filedelete-otherreason'      => 'Message used when deleting a file. This is the description field for "Other/additional reason" for deletion.

{{Identical|Other/additional reason}}',
'filedelete-reason-otherlist' => 'Message used as default in the dropdown menu in the form for deleting a file. Keeping this message selected assumes that a reason for deletion is specified in the field below.

{{Identical|Other reason}}',
'filedelete-reason-dropdown'  => 'Predefined reasons for deleting a file that can be selected in a drop down list. Entries prefixed with one asterisk ("*") are group headers and cannot be selected. Entries prefixed with two asterisks can be selected as reason for deletion.',
'filedelete-edit-reasonlist'  => 'Shown beneath the file deletion form on the right side. It is a link to [[MediaWiki:Filedelete-reason-dropdown]].

{{Identical|Edit delete reasons}}',

# MIME search
'mimesearch'         => 'Title of [[Special:MIMESearch]].',
'mimesearch-summary' => 'Text for [[Special:MIMESearch]]',
'download'           => 'Direct download link in each line returned by [[Special:MIMESearch]]. Points to the actual file, rather than the image description page.
{{Identical|Download}}',

# Unwatched pages
'unwatchedpages' => 'Name of special page displayed in [[Special:SpecialPages]] for admins',

# List redirects
'listredirects' => 'Name of special page displayed in [[Special:SpecialPages]].',

# Unused templates
'unusedtemplates'     => 'Name of special page displayed in [[Special:SpecialPages]].',
'unusedtemplatestext' => 'Shown on top of [[Special:Unusedtemplates]]',

# Random page
'randompage' => 'Name of special page displayed in [[Special:SpecialPages]].

{{Identical|Random page}}',

# Random redirect
'randomredirect' => 'Name of special page displayed in [[Special:SpecialPages]].',

# Statistics
'statistics'                   => 'Name of special page displayed in [[Special:SpecialPages]].

{{Identical|Statistics}}',
'statistics-header-pages'      => 'Used in [[Special:Statistics]]',
'statistics-header-edits'      => 'Used in [[Special:Statistics]]',
'statistics-header-views'      => 'Used in [[Special:Statistics]]',
'statistics-header-users'      => 'Used in [[Special:Statistics]]',
'statistics-header-hooks'      => 'Header of a section on [[Special:Statistics]] containing data provided by MediaWiki extensions',
'statistics-articles'          => "Used in [[Special:Statistics]].

A 'content page' is a page that forms part of the purpose of the wiki. It includes the main page and pages in the main namespace and any other namespaces that are included when the wiki is customised. For example on Wikimedia Commons 'content pages' include pages in the file and category namespaces. On Wikinews 'content pages' include pages in the Portal namespace. For technical definition of 'content namespaces' see [http://www.mediawiki.org/wiki/Manual:Using_custom_namespaces#Content_namespaces Mediawiki].

Possible alternatives to the word 'content' are 'subject matter' or 'wiki subject' or 'wiki purpose'.

{{Identical|Content page}}",
'statistics-pages'             => 'Used in [[Special:Statistics]]
{{Identical|Pages}}',
'statistics-pages-desc'        => "Tooltip shown over ''Pages'' (or as a note below it) in [[Special:Statistics]]",
'statistics-files'             => 'Used in [[Special:Statistics]]',
'statistics-edits'             => 'Used in [[Special:Statistics]]',
'statistics-edits-average'     => 'Used in [[Special:Statistics]]',
'statistics-views-total'       => 'Used in [[Special:Statistics]]',
'statistics-views-peredit'     => 'Used in [[Special:Statistics]]',
'statistics-users'             => 'Used in [[Special:Statistics]]. Do not change "Special:ListUsers"!',
'statistics-users-active'      => 'Used in [[Special:Statistics]]',
'statistics-users-active-desc' => "Description shown beneath ''Active users'' in [[Special:Statistics]]

* \$1: Value of \$wgRCMaxAge in days",
'statistics-mostpopular'       => 'Used in [[Special:Statistics]]',

'disambiguations'      => 'Name of a special page displayed in [[Special:SpecialPages]].',
'disambiguationspage'  => 'This message is the name of the template used for marking disambiguation pages. It is used by [[Special:Disambiguations]] to find all pages which link to disambiguation pages.

{{doc-important|Don\'t translate the "Template:" part!}}',
'disambiguations-text' => "This block of text is shown on [[:Special:Disambiguations]].

* '''Note:''' Do not change the link [[MediaWiki:Disambiguationspage]], even because it is listed as problematic. Be sure the \"D\" is in uppercase, so not \"d\".

* '''Background information:''' Beyond telling about links going to disambiguation pages, that they are generally bad, it should explain which pages in the article namespace are seen as diambiguations: [[MediaWiki:Disambiguationspage]] usually holds a list of diambiguation templates of the local wiki. Pages linking to one of them (by transclusion) will count as disambiguation pages. Pages linking to these disambiguation pages, instead to the disambiguated article itself, are listed on [[:Special:Disambiguations]].",

'doubleredirects'                   => 'Name of [[Special:DoubleRedirects]] displayed in [[Special:SpecialPages]]',
'doubleredirectstext'               => 'Shown on top of [[Special:Doubleredirects]]',
'double-redirect-fixed-move'        => 'This is the message in the log when the software (under the username {{msg|double-redirect-fixer}}) updates the redirects after a page move. See also {{msg|fix-double-redirects}}.',
'double-redirect-fixed-maintenance' => 'This is the message in the log when the software (under the username {{msg|double-redirect-fixer}}) updates the redirects after running maintenance/fixDoubleRedirects.php. Compare with {{msg|double-redirect-fixed-move}}.',
'double-redirect-fixer'             => "This is the '''username''' of the user who updates the double redirects after a page move. A user is created with this username, so it is perhaps better to not change this message too often. See also {{msg|double-redirect-fixed-move}} and {{msg|fix-double-redirects}}.",

'brokenredirects'        => 'Name of [[Special:BrokenRedirects]] displayed in [[Special:SpecialPages]]',
'brokenredirectstext'    => 'Shown on top of [[Special:BrokenRedirects]].',
'brokenredirects-edit'   => 'Link in [[Special:BrokenRedirects]]

{{Identical|Edit}}',
'brokenredirects-delete' => 'Link in [[Special:BrokenRedirects]] for admins

{{Identical|Delete}}',

'withoutinterwiki'         => 'The title of the special page [[Special:WithoutInterwiki]].',
'withoutinterwiki-summary' => 'Summary of [[Special:WithoutInterwiki]].',
'withoutinterwiki-legend'  => 'Used on [[Special:WithoutInterwiki]] as title of fieldset.',
'withoutinterwiki-submit'  => '{{Identical|Show}}',

'fewestrevisions' => 'Name of a special page displayed in [[Special:SpecialPages]].',

# Miscellaneous special pages
'nbytes'                  => 'Message used on the history page of a wiki page. Each version of a page consist of a number of bytes. $1 is the number of bytes that the page uses. Uses plural as configured for a language based on $1.',
'ncategories'             => "Used in the special page '[[Special:MostCategories]]' in brackets after each entry on the list signifying how many categories a page is part of. $1 is the number of categories.",
'nlinks'                  => 'This appears in brackets after each entry on the special page [[Special:MostLinked]]. $1 is the number of wiki links.',
'nmembers'                => 'Appears in brackets after each category listed on the special page [[Special:WantedCategories]]. $1 is the number of members of the category.',
'nrevisions'              => 'Number of revisions.',
'nviews'                  => 'This message is used on [[Special:PopularPages]] to say how many times each page has been viewed. Parameter $1 is the number of views.',
'nimagelinks'             => 'Used on [[Special:MostLinkedFiles]] to indicate how often a specific file is used.',
'ntransclusions'          => 'Used on [[Special:MostLinkedTemplates]] to indicate how often a template is in use.',
'specialpage-empty'       => 'Used on a special page when there is no data. For example on [[Special:Unusedimages]] when all images are used.',
'lonelypages'             => 'Name of [[Special:LonelyPages]] displayed in [[Special:SpecialPages]]',
'lonelypagestext'         => 'Text displayed in [[Special:LonelyPages]]',
'uncategorizedpages'      => 'Name of a special page displayed in [[Special:SpecialPages]].',
'uncategorizedcategories' => 'Name of special page displayed in [[Special:SpecialPages]]',
'uncategorizedimages'     => 'The title of the special page [[Special:UncategorizedImages]].',
'uncategorizedtemplates'  => 'The title of the special page [[Special:UncategorizedTemplates]].',
'unusedcategories'        => 'Name of special page displayed in [[Special:SpecialPages]]',
'unusedimages'            => 'Name of special page displayed in [[Special:SpecialPages]]',
'popularpages'            => 'Name of special page displayed in [[Special:SpecialPages]]',
'wantedcategories'        => 'Name of special page displayed in [[Special:SpecialPages]]',
'wantedpages'             => 'Name of special page displayed in [[Special:SpecialPages]]',
'wantedpages-badtitle'    => "Error message shown when [[Special:WantedPages]] is listing a page with a title that shouldn't exist.

$1 is a page title",
'wantedfiles'             => 'Name of special page displayed in [[Special:SpecialPages]] and title of [[Special:WantedFiles]].',
'wantedtemplates'         => 'The page name of [[Special:WantedTemplates]].',
'mostlinked'              => 'Name of special page displayed in [[Special:SpecialPages]]',
'mostlinkedcategories'    => 'Name of special page displayed in [[Special:SpecialPages]]',
'mostlinkedtemplates'     => 'Name of special page displayed in [[Special:SpecialPages]]',
'mostcategories'          => 'Name of special page displayed in [[Special:SpecialPages]]',
'mostimages'              => 'Name of special page displayed in [[Special:SpecialPages]]',
'mostrevisions'           => 'Name of special page displayed in [[Special:SpecialPages]]',
'prefixindex'             => 'The page title of [[Special:PrefixIndex]]. When the user limits the list to a certain namespace, {{msg-mw|allinnamespace}} is used instead.',
'shortpages'              => 'Name of special page displayed in [[Special:SpecialPages]]',
'longpages'               => 'Name of special page displayed in [[Special:SpecialPages]]',
'deadendpages'            => 'Name of special page displayed in [[Special:SpecialPages]]',
'deadendpagestext'        => 'Introductory text for [[Special:DeadendPages]]',
'protectedpages'          => 'Name of special page displayed in [[Special:SpecialPages]]',
'protectedpages-indef'    => 'Option in [[Special:ProtectedPages]]',
'protectedpages-cascade'  => 'Option in [[Special:ProtectedPages]]',
'protectedpagestext'      => 'Shown on top of [[Special:ProtectedPages]]',
'protectedtitles'         => 'Name of special page displayed in [[Special:SpecialPages]]',
'protectedtitlestext'     => 'Shown on top of list of titles on [[Special:ProtectedTitles]]. If the list is empty the message [[MediaWiki:Protectedtitlesempty]] appears instead of this. See the [http://www.mediawiki.org/wiki/Project:Protected_titles help page on Mediawiki] for more information.',
'protectedtitlesempty'    => 'Used on [[Special:ProtectedTitles]]. This text appears if the list of protected titles is empty. See the [http://www.mediawiki.org/wiki/Project:Protected_titles help page on Mediawiki] for more information.',
'listusers'               => 'Name of special page displayed in [[Special:SpecialPages]]',
'listusers-editsonly'     => 'Option in [[Special:ListUsers]].',
'listusers-creationsort'  => 'Option in [[Special:ListUsers]].',
'usereditcount'           => 'Shown behind every username on [[Special:ListUsers]].',
'usercreated'             => 'Used in [[Special:ListUsers]].
* <code>$1</code> is a date
* <code>$2</code> is a time',
'newpages'                => 'Name of special page displayed in [[Special:SpecialPages]]
{{Identical|New page}}',
'newpages-username'       => '{{Identical|Username}}',
'ancientpages'            => 'The page title of [[Special:Ancientpages]]. [[mw:Manual:Interface/Special pages title|mw manual]]',
'move'                    => 'Name of Move tab. Should be in the imperative mood.

{{Identical|Move}}',
'movethispage'            => '{{Identical|Move this page}}',
'unusedimagestext'        => 'Header message of [[Special:UnusedFiles]]',
'nopagetitle'             => 'Used as title of [[Special:MovePage]], when the oldtitle does not exist.

The text is {{msg-mw|nopagetext}}.',
'nopagetext'              => 'Used as text of [[Special:MovePage]], when the oldtitle does not exist.

The title is {{msg-mw|nopagetitle}}.',
'pager-newer-n'           => "This is part of the navigation message on the top and bottom of Special pages which are lists of things in date order, e.g. the User's contributions page. It is passed as the second argument of {{msg-mw|Viewprevnext}}. $1 is the number of items shown per page.",
'pager-older-n'           => "This is part of the navigation message on the top and bottom of Special pages which are lists of things in date order, e.g. the User's contributions page. It is passed as the first argument of {{msg-mw|Viewprevnext}}. $1 is the number of items shown per page.",
'suppress'                => '{{Identical|Oversight}}',
'querypage-disabled'      => "On special pages that use expensive database queries but are not cacheable, this message is displayed when 'miser mode' is on (i.e. no expensive queries allowed).",

# Book sources
'booksources'               => 'Name of special page displayed in [[Special:SpecialPages]]',
'booksources-search-legend' => 'Box heading on [[Special:BookSources|book sources]] special page. The box is for searching for places where a particular book can be bought or viewed.',
'booksources-isbn'          => '{{optional}}',
'booksources-go'            => 'Name of button in [[Special:BookSources]]

{{Identical|Go}}',
'booksources-invalid-isbn'  => 'This message is displayed after an invalid ISBN is entered on Special:Booksources.',

# Special:Log
'specialloguserlabel'  => 'Used in [[Special:Log]].

{{Identical|User}}',
'speciallogtitlelabel' => 'Used in [[Special:Log]].

{{Identical|Title}}',
'log'                  => 'Name of special page displayed in [[Special:SpecialPages]]',
'all-logs-page'        => 'Title of [[Special:Log]].',
'alllogstext'          => 'Header of [[Special:Log]]',
'log-title-wildcard'   => '* Appears in: [[Special:Log]]
* Description: A check box to enable prefix search option',

# Special:AllPages
'allpages'          => 'First part of the navigation bar for the special page [[Special:AllPages]] and [[Special:PrefixIndex]]. The other parts are {{msg-mw|Prevpage}} and {{msg-mw|Nextpage}}.

{{Identical|All pages}}',
'alphaindexline'    => 'Used on [[Special:AllPages]] if the main namespace contains more than 960 pages. Indicates the page range displayed behind the link. "from page $1 to page $2". $1 is the source page name. $1 is the target page name.',
'nextpage'          => 'Third part of the navigation bar for the special page [[Special:AllPages]] and [[Special:PrefixIndex]]. $1 is a page title. The other parts are {{msg-mw|Allpages}} and {{msg-mw|Prevpage}}.

{{Identical|Next page}}',
'prevpage'          => 'Second part of the navigation bar for the special page [[Special:AllPages]] and [[Special:PrefixIndex]]. $1 is a page title. The other parts are {{msg-mw|Allpages}} and {{msg-mw|Nextpage}}.

{{Identical|Previous page}}',
'allpagesfrom'      => 'Option in [[Special:AllPages]]. See also {{msg|allpagesto}}.',
'allpagesto'        => 'Option in [[Special:AllPages]]. See also {{msg|allpagesfrom}}.',
'allarticles'       => 'The page title of [[Special:Allpages]]. When the user limit the list to a certain namespace, {{msg-mw|allinnamespace}} is used instead.

{{Identical|All pages}}',
'allinnamespace'    => 'The page title of [[Special:Allpages]] and [[Special:PrefixIndex]], when the user limits the display to a certain namespace. When not limited, {{msg-mw|allarticles}} and {{msg-mw|prefixindex}} is used respectively.

{{Identical|All pages}}',
'allnotinnamespace' => 'Presumably intended to be used as a page title of [[Special:Allpages]] and probably also in [[Special:PrefixIndex]] when the user limit the display to other than a certain namespace.

{{Identical|All pages}}',
'allpagesprev'      => "Allegedly used in [[Special:AllPages]], although I haven't seen it.

{{Identical|Previous}}",
'allpagesnext'      => "Allegedly used in [[Special:AllPages]], although I haven't seen it.

{{Identical|Next}}",
'allpagessubmit'    => 'Text on submit button in [[Special:AllPages]], [[Special:RecentChanges]], [[Special:RecentChangesLinked]], [[Special:NewPages]], [[Special:Log]], [[Special:ListUsers]], [[Special:ProtectedPages]], [[Special:ProtectedTitles]], [[Special:WhatLinksHere]] and [[Special:Watchlist]].

{{Identical|Go}}',
'allpagesprefix'    => "Used for the label of the input box of [[Special:PrefixIndex]]. On this page you can either write 'Name of namespace:string from which to begin display in alphabetical order' in the top box, or you can choose a namespace in the bottom box and put 'string from which to begin display in alphabetical order' in the top box. The result will be the same.",

# Special:Categories
'categories'                    => 'The page name of [[Special:Categories]].

{{Identical|Categories}}',
'categoriespagetext'            => "Text displayed in [[Special:Categories]]. Do not translate or change links. In order to translate ''Unused categories'' and ''wanted categories'' see {{msg|unusedcategories}} and {{msg|wantedcategories}}.",
'special-categories-sort-count' => 'This message is used on [[Special:Categories]] to sort the list by the number of members in the categories.',

# Special:DeletedContributions
'deletedcontributions'             => 'The message is shown as a link on user contributions page (like [[Special:Contributions/User]]) to the corresponding [[Special:DeletedContributions]] page.

{{Identical|Deleted user contributions}}',
'deletedcontributions-title'       => 'Title of [[Special:DeletedContributions]] (extension), a special page with a list of edits to pages which were deleted. Only viewable by sysops.

{{Identical|Deleted user contributions}}',
'sp-deletedcontributions-contribs' => 'Link to user’s contributions on [[Special:DeletedContributions]]',

# Special:LinkSearch
'linksearch'       => 'Title of [[Special:LinkSearch|special page]] and legend of fieldset on that page.

{{Identical|External link}}',
'linksearch-pat'   => 'Legend for input box on [[Special:LinkSearch]].',
'linksearch-ns'    => 'Legend for dropdown box on [[Special:LinkSearch|special page]].

{{Identical|Namespace}}',
'linksearch-ok'    => 'Button label on [[Special:LinkSearch|special page]]e

{{Identical|Search}}',
'linksearch-text'  => 'Introduction to special page [[Special:LinkSearch]].

$1 is a list of internet protocols.',
'linksearch-line'  => 'Text for the results of a search using [[Special:LinkSearch]].

*$1 is the address of an external link
*$2 is the page containing the external link',
'linksearch-error' => "Error message generated by [[Special:LinkSearch]].

You can apparently use 'URL' instead of 'hostname'.",

# Special:ListUsers
'listusersfrom'      => 'identical with {{msg-mw|activeusers-from}}',
'listusers-submit'   => 'Text displayed in the submission button of the [[Special:ListUsers]] form.
{{Identical|Go}}
{{Identical|Show}}',
'listusers-noresult' => 'identical with {{msg-mw|activeusers-noresult}}',
'listusers-blocked'  => 'Used on [[Special:ActiveUsers]] when a user has been blocked.
* $1 is a user name for use with GENDER (optional)',

# Special:ActiveUsers
'activeusers'          => 'Title of [[Special:ActiveUsers]]',
'activeusers-count'    => "Used in [[Special:ActiveUsers]] to show the active user's recent edit count in brackets ([]).
* $1 is the number of recent edits
* $2 is the user's name for use with GENDER (optional)
* $3 is the maximum number of days of the RecentChangesList",
'activeusers-from'     => 'identical with {{msg-mw|listusersfrom}}',
'activeusers-noresult' => 'identical with {{msg-mw|listusers-noresult}}',

# Special:Log/newusers
'newuserlogpage'     => 'Part of the "Newuserlog" extension. It is both the title of [[Special:Log/newusers]] and the link you can see in the recent changes.',
'newuserlogpagetext' => 'Part of the "Newuserlog" extension. It is the description you can see on [[Special:Log/newusers]].',

# Special:ListGroupRights
'listgrouprights'                  => 'The name of the special page [[Special:ListGroupRights]].',
'listgrouprights-summary'          => 'The description used on [[Special:ListGroupRights]].',
'listgrouprights-key'              => 'Footer note for the [[Special:ListGroupRights]] page',
'listgrouprights-group'            => "The title of the column in the table, about user groups (like you are in the ''translator'' group).

{{Identical|Group}}",
'listgrouprights-rights'           => "The title of the column in the table, about user rights (like you can ''edit'' this page).
{{Identical|Right}}",
'listgrouprights-helppage'         => "The link used on [[Special:ListGroupRights]]. Just translate \"Group rights\", and '''leave the \"Help:\" namespace exactly as it is'''.",
'listgrouprights-members'          => 'Used on [[Special:ListGroupRights]] and [[Special:Statistics]] as a link to [[Special:ListUsers|Special:ListUsers/"group"]], a list of members in that group.',
'listgrouprights-right-display'    => "{{optional}}
* $1 is the text from the 'right-...' messages, i.e. [[MediaWiki:right-edit]] = {{int:right-edit}}
* $2 is the codename of this right",
'listgrouprights-right-revoked'    => "{{optional}}
* $1 is the text from the 'right-...' messages, i.e. [[MediaWiki:right-edit]] = {{int:right-edit}}
* $2 is the codename of this right",
'listgrouprights-addgroup'         => 'This is an individual right for groups, used on [[Special:ListGroupRights]].
* $1 is an enumeration of group names.
* $2 is the number of group names in $1.
See also {{msg|listgrouprights-removegroup}}.',
'listgrouprights-removegroup'      => 'This is an individual right for groups, used on [[Special:ListGroupRights]].
* $1 is an enumeration of group names.
* $2 is the number of group names in $1.
See also {{msg|listgrouprights-addgroup}}.',
'listgrouprights-addgroup-all'     => 'Used on [[Special:ListGroupRights]].

See also {{msg-mw|listgrouprights-removegroup-all}}.',
'listgrouprights-removegroup-all'  => 'Used on [[Special:ListGroupRights]].

See also {{msg-mw|listgrouprights-addgroup-all}}.',
'listgrouprights-addgroup-self'    => 'This is an individual right for groups, used on [[Special:ListGroupRights]].
* $1 are the group names.
* $2 is the number of group names in $1.',
'listgrouprights-removegroup-self' => 'This is an individual right for groups, used on [[Special:ListGroupRights]].
* $1 are the group names.
* $2 is the number of group names in $1.',

# E-mail user
'emailuser'           => 'Link in the sidebar and title of [[Special:EmailUser|special page]]',
'emailpage'           => "Title of special page [[Special:EmailUser]], when it is the destination of the sidebar link {{msg-mw|Emailuser}} on a user's page.",
'emailpagetext'       => 'This is the text that is displayed above the e-mail form on [[Special:EmailUser]].

Special:EmailUser appears when you click on the link "E-mail this user" in the sidebar, but only if there is an e-mail address in the recipient\'s user preferences. If there isn\'t then the message [[Mediawiki:Noemailtext]] will appear instead of Special:EmailUser.',
'defemailsubject'     => 'The default subject of EmailUser emails.  The first parameter is the username of the user sending the email.',
'usermaildisabled'    => 'Caption for an error message ({{msg-mw|Usermaildisabledtext}}) shown when the user-to-user e-mail feature is disabled on the wiki (see [[mw:Manual:$wgEnableEmail]], [[mw:Manual:$wgEnableUserEmail]]).',
'noemailtitle'        => 'The title of the message that appears instead of Special:EmailUser after clicking the "E-mail this user" link in the sidebar, if no e-mail can be sent to the user.',
'noemailtext'         => 'The text of the message that appears in [[Special:EmailUser]] after clicking the "E-mail this user" link in the sidebar, if no e-mail can be sent to the user because he has not specified or not confirmed an e-mail address.',
'nowikiemailtext'     => 'This is an error message used in [[Special:Emailuser]] when called with a target user not consenting to be an e-mail recipient.',
'emailnotarget'       => 'This is an error message that may be used in [[Special:Emailuser]] when called without a (valid) target user for the e-mail.',
'emailtarget'         => 'This is an input group label being used in [[Special:Emailuser]] when called without a (valid) target user for the e-mail.',
'emailusername'       => 'This is a prompt being used in [[Special:Emailuser]] when called without a (valid) target user for the e-mail.

{{Identical|Username}}',
'emailusernamesubmit' => '{{Identical|Submit}}

This is a button text used in [[Special:Emailuser]] when called without a (valid) target user for the e-mail.',
'email-legend'        => 'Title of the box in [[Special:EmailUser]]',
'emailfrom'           => 'Field in [[Special:EmailUser]].
{{Identical|From}}',
'emailto'             => 'Field in [[Special:EmailUser]].
{{Identical|To}}',
'emailsubject'        => 'Field in [[Special:EmailUser]].

{{Identical|Subject}}',
'emailmessage'        => 'Field in [[Special:EmailUser]].

{{Identical|Message}}',
'emailsend'           => 'Button name in [[Special:EmailUser]].

{{Identical|Send}}',
'emailccme'           => 'Used at [[Special:Preferences]] > E-mail',
'emailccsubject'      => 'Subject of the carbon-copied  email for the sender sent through MediaWiki.',
'emailsent'           => 'Title of Special:Emailuser when it says you it sent an email',
'emailsenttext'       => 'When you send an e-mail, Special:Emailuser says you this (Your email has been sent).',
'emailuserfooter'     => 'This message is appended to every email sent through the "Email user" function.

* $1: username of the sender
* $2: username of the recipient',

# User Messenger
'usermessage-summary' => 'This message is used as an edit summary for any message that is posted because of a system event. Translate "leaving a message" in the sense of: to give a message to someone; to deliver a message somewhere; to deposit.',
'usermessage-editor'  => 'The user name for the user that is the editor of system messages. See [http://translatewiki.net/wiki/Thread:Support/Message_info_please discussion on Support].',

# Watchlist
'watchlist'            => '{{Identical|My watchlist}}',
'mywatchlist'          => 'Link at the upper right corner of the screen.

{{Identical|My watchlist}}',
'watchlistfor2'        => 'Subtitle on [[Special:Watchlist]].

*$1: Username of current user
*$2: Tool links (View relevant changes | View and edit watchlist | Edit raw watchlist)
{{Identical|For $1}}',
'nowatchlist'          => 'Displayed when there is no pages in the watchlist.',
'watchlistanontext'    => '* $1 is a link to [[Special:UserLogin]] with {{msg-mw|loginreqlink}} as link description',
'watchnologin'         => '{{Identical|Not logged in}}',
'addwatch'             => 'Link to a dialog box, displayed at the end of the list of categories at the foot of each page.',
'addedwatchtext'       => 'Explanation shown when clicking on the {{msg|watch}} tab. See also {{msg|addedwatch}}.',
'removedwatchtext'     => "After a page has been removed from a user's watchlist by clicking the {{msg|unwatch}} tab at the top of an article, this message appears just below the title of the article. $1 is the title of the article. See also {{msg|removedwatch}} and {{msg|addedwatchtext}}.",
'watch'                => 'Name of the Watch tab. Should be in the imperative mood.',
'watchthispage'        => '{{Identical|Watch this page}}',
'unwatch'              => 'Label of "Unwatch" tab.',
'notanarticle'         => "A 'content page' is a page that forms part of the purpose of the wiki. It includes the main page and pages in the main namespace and any other namespaces that are included when the wiki is customised. For example on Wikimedia Commons 'content pages' include pages in the file and category namespaces. On Wikinews 'content pages' include pages in the Portal namespace. For technical definition of 'content namespaces' see [http://www.mediawiki.org/wiki/Manual:Using_custom_namespaces#Content_namespaces Mediawiki].

Possible alternatives to the word 'content' are 'subject matter' or 'wiki subject' or 'wiki purpose'.

{{Identical|Content page}}",
'watchlist-details'    => 'Message on Special page: My watchlist. This is paired with the message [[Mediawiki:Nowatchlist]] which appears instead of Watchlist-details when $1 is 0.',
'wlheader-showupdated' => 'This message shows up near top of users watchlist page.',
'wlshowlast'           => "Appears on [[Special:Watchlist]]. Variable $1 gives a choice of different numbers of hours, $2 gives a choice of different numbers of days and $3 is '{{int:watchlistall2}}' ([[Mediawiki:watchlistall2/{{SUBPAGENAME}}]]). Clicking on your choice changes the list of changes you see (without changing the default in my preferences).",
'watchlist-options'    => 'Legend of the fieldset of [[Special:Watchlist]]',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'       => 'Text displayed when clicked on the watch tab: [[MediaWiki:Watch/{{SUBPAGENAME}}|{{int:watch}}]]. It means the wiki is adding that page to your watchlist.',
'unwatching'     => 'Text displayed when clicked on the unwatch tab: [[MediaWiki:Unwatch/{{SUBPAGENAME}}|{{int:unwatch}}]]. It means the wiki is removing that page from your watchlist.',
'watcherrortext' => 'When a user clicked the watch/unwatch tab and the action did not succeed, this message is displayed. See also {{msg|addedwatchtext}}. and {{msg|addedwatchtext}}. This message is used raw and should not contain wikitext.',

'enotif_newpagetext' => 'Part of text of a notification e-mail sent when a watched page has been created. See [[File:Screenshot_MediaWiki_e-mail_notifier.PNG|150px|right]]',
'changed'            => 'Possible value for $CHANGEDORCREATED in {{msg|enotif_subject}} and {{msg|enotif_body}}.',
'created'            => 'Possible value for $CHANGEDORCREATED in {{msg|enotif_subject}} and {{msg|enotif_body}}.',
'enotif_subject'     => '$CHANGEDORCREATED can be one of {{msg|changed}} and {{msg|created}}. Can also be {{msg-mw|blog-added}} or {{msg-mw|blog-edited}} from Wikia.',
'enotif_lastvisited' => '$1 is a URL address.',
'enotif_lastdiff'    => '* $1 is a link to diff, shown as a plainlink',
'enotif_body'        => 'Text of a notification e-mail sent when a watched page has been edited or deleted.[[File:Screenshot_MediaWiki_e-mail_notifier.PNG|150px|right]]

* <tt>$CHANGEDORCREATED</tt> can be one of {{msg-mw|changed}}, {{msg-mw|created}}, or {{msg-mw|deleted}}. Can also be {{msg-mw|blog-added}} or {{msg-mw|blog-edited}} from Wikia.',

# Delete
'confirm'                => 'Submit button text for protection confirmation

{{Identical|Confirm}}',
'excontent'              => 'Automated deletion reason when deleting a page for admins',
'excontentauthor'        => 'Automated deletion reason when deleting a page for admins providing that the page has one author only.',
'exbeforeblank'          => 'Automated deletion reason when deleting a page for admins providing that the page was blanked before deletion.',
'delete-confirm'         => 'The title of the form to delete a page.

$1 = the name of the page',
'delete-backlink'        => '{{optional}}',
'delete-legend'          => '{{Identical|Delete}}',
'historywarning'         => 'Warning when about to delete a page that has history.
$1 is the <b>approximate</b> number of revisions that the page has, the message should not claim to give an exact count.',
'confirmdeletetext'      => 'Introduction shown when deleting a page.',
'actioncomplete'         => 'Used in several situations, for example when a page has been deleted.',
'deletedtext'            => 'Parameters:
* $1 is a page that was deleted
* $2 is {{msg-mw|deletionlog}}',
'dellogpage'             => 'The name of the deletion log. Used as heading on [[Special:Log/delete]] and in the drop down menu for selecting logs on [[Special:Log]].

{{Identical|Deletion log}}',
'dellogpagetext'         => 'Text in [[Special:Log/delete]].',
'deletionlog'            => 'This message is used to link to the deletion log as parameter $1 of {{msg|Filewasdeleted}}, as parameter $2 of {{msg|deletedtext}}, and in log lines on [[Special:DeletedContributions]].

{{Identical|Deletion log}}',
'reverted'               => '{{Identical|Revert}}',
'deletecomment'          => '{{Identical|Reason}}',
'deleteotherreason'      => '{{Identical|Other/additional reason}}',
'deletereasonotherlist'  => '{{Identical|Other reason}}',
'deletereason-dropdown'  => 'Default reasons for deletion. Displayed as a drop-down list. Format:
<pre>* Group
** Common delete reason
** ...</pre>',
'delete-edit-reasonlist' => 'Shown beneath the page deletion form on the right side. It is a link to [[MediaWiki:Deletereason-dropdown]]. See also {{msg|Ipb-edit-dropdown}} and {{msg|Protect-edit-reasonlist}}.

{{Identical|Edit delete reasons}}',

# Rollback
'rollback'          => '{{Identical|Rollback}}',
'rollback_short'    => '{{Identical|Rollback}}',
'rollbacklink'      => '{{Identical|Rollback}}',
'rollbackfailed'    => '{{Identical|Rollback}}',
'cantrollback'      => '{{Identical|Revert}}
{{Identical|Rollback}}',
'alreadyrolled'     => "Appear when there's rollback and/or edit collision.
* $1: the page to be rollbacked
* $2: the editor to be rollbacked of that page
* $3: the editor that cause collision

{{Identical|Rollback}}",
'editcomment'       => "Only shown if there is an edit ''{{msg-mw|summary}}''",
'revertpage'        => '{{Identical|Revert}}
Additionally available:
* $3: revid of the revision reverted to,
* $4: timestamp of the revision reverted to,
* $5: revid of the revision reverted from,
* $6: timestamp of the revision reverted from',
'revertpage-nouser' => 'This is a confirmation message a user sees after reverting, when the username of the version is hidden with RevisionDelete.
In other cases the message {{msg-mw|revertpage}} is used.',
'rollback-success'  => 'This message shows up on screen after successful revert (generally visible only to admins). $1 describes user whose changes have been reverted, $2 describes user which produced version, which replaces reverted version.
{{Identical|Revert}}
{{Identical|Rollback}}',

# Protect
'protectlogpage'              => 'Title of [[Special:Log/protect]].',
'protectlogtext'              => 'Text in [[Special:Log/protect]].',
'protectedarticle'            => 'Text describing an action on [[Special:Log]]. $1 is a page title.',
'modifiedarticleprotection'   => 'Text describing an action on [[Special:Log]]. $1 is a page title.',
'movedarticleprotection'      => 'This is a log entry. It appears in the log if a protected page is renamed.

Example:
<code>00:51, 16 September 2010 Siebrand +(Talk • contribs • block) moved protection settings from "User:Siebrand/prot-move" to "User:Siebrand/prot-moved" ‎ (User:Siebrand/prot-move moved to User:Siebrand/prot-moved: prot_move test.)</code>',
'protect-title'               => 'Title for the protection form. $1 is the title of the page to be (un)protected.',
'prot_1movedto2'              => 'Message description: [[mw:Manual:Interface/1movedto2]]',
'protect-backlink'            => '{{optional|Translate it only if you have to change it, i.e. for RTL wikis}}

Shown as subtitle of the protection form. $1 is the title of the page to be (un)protected.',
'protect-legend'              => 'Legend of the fieldset around the input form of the protection form.',
'protectcomment'              => '{{Identical|Reason}}',
'protectexpiry'               => '{{Identical|Expires}}',
'protect-unchain-permissions' => 'This message is a label for a checkbox displayed in the "protect" form just below the first selector (generally the "edit" protection). If the checkbox is checked (default) other selectors will be locked and have the same value as the first selector, otherwise other selectors are editable by the user.
This message was something like "unlock move protection" in the past.',
'protect-text'                => 'Intro of the protection interface. See [[meta:Protect]] for more information.',
'protect-default'             => '{{Identical|Default}}',
'protect-fallback'            => 'This message is used as an option in the protection form on wikis were extra protection levels have been configured.',
'protect-summary-cascade'     => 'Used in edit summary when cascade protecting a page.',
'protect-expiring'            => 'Used in page history, and in [[Special:Protectedtitles]], [[Special:Protectedpages]], and extension FlaggedRevs.
* $1 is a date and time
* $2 is a date (optional)
* $3 is a time (optional)

{{Identical|Expires $1 (UTC)}}',
'protect-expiring-local'      => '$1 is a timestamp like "22:51, 23 July 2011 (UTC)" depending on the wiki content language.',
'protect-cascade'             => 'See [[meta:Protect]] for more information.',
'protect-othertime'           => 'Used on the page protection form as label for the following input field (text)
{{Identical|Other time}}',
'protect-othertime-op'        => 'Used on the page protection form in the drop down menu
{{Identical|Other time}}',
'protect-existing-expiry'     => 'Shows the existing expiry time in the drop down menu of the protection form ([http://translatewiki.net/w/i.php?title=User:Raymond/test&action=unprotect example])

* $1: date and time of the existing expiry time (kept for backward compatibility purposes)
* $2: date of the existing expiry time
* $3: time of the existing expiry time',
'protect-otherreason'         => 'Shown on the page protection form as label for the following input field (text)
{{Identical|Other/additional reason}}',
'protect-otherreason-op'      => 'Shown on the page protection form in the drop down menu
{{Identical|Other reason}}',
'protect-dropdown'            => 'Shown on the page protection form as drop down menu for protection reasons.

<tt><nowiki>* Groupname</nowiki></tt> - defines a new group<br />
<tt><nowiki>** Reason</nowiki></tt> - defines a reason in this group',
'protect-edit-reasonlist'     => 'Shown beneath the page protection form on the right side. It is a link to [[MediaWiki:Protect-dropdown]]. See also {{msg|Delete-edit-reasonlist}} and {{msg|Ipb-edit-dropdown}}.',
'protect-expiry-options'      => "* Description: Options for the duration of the page protection.
* <font color=\"red\">Be careful:</font> '''1 translation:1 english''', so the first part is the translation and the second part should stay in English.
* Example: See e.g. [[MediaWiki:Protect-expiry-options/nl]] if you still don't know how to do it.

{{Identical|Infinite}}",
'restriction-type'            => 'Used on [[Special:ProtectedPages]]. The text next to a drop-down box. See [[mw:Manual:Administrators|MediaWiki Manual]] for more information on protection.',
'restriction-level'           => 'Used on [[Special:ProtectedPages]] and [[Special:ProtectedTitles]]. The text next to a drop-down box. See the [http://www.mediawiki.org/wiki/Project:Protected_titles help page on Mediawiki] and on [http://meta.wikimedia.org/wiki/Protect Meta] for more information.',
'minimum-size'                => 'Used in [[Special:Protectedpages]] as a pair of radio buttons, with [[MediaWiki:Maximum-size]]. There is an input box to specify the minimum bites of the projected pages listed.',
'maximum-size'                => 'Used in [[Special:Protectedpages]] as a pair of radio buttons, with [[MediaWiki:Minimum-size]]. There is an input box to specify the maximum bites of the projected pages listed.',
'pagesize'                    => 'Used on [[Special:ProtectedPages]]. See the help page on [http://meta.wikimedia.org/wiki/Protect Meta] for more information on protection.',

# Restrictions (nouns)
'restriction-edit'   => "Used on [[Special:ProtectedPages]]. Option in the 'permission' drop-down box.

{{Identical|Edit}}",
'restriction-move'   => "Used on [[Special:ProtectedPages]]. Option in the 'permission' drop-down box.

{{Identical|Move}}",
'restriction-create' => 'Used on [[Special:ProtectedPages]]. An option in a drop-down box. See the help pages on [http://www.mediawiki.org/wiki/Project:Protected_titles MediaWiki] and [http://meta.wikimedia.org/wiki/Protect Meta] for more information on protection.

{{Identical|Create}}',

# Restriction levels
'restriction-level-sysop'         => "Used on [[Special:ProtectedPages]] and [[Special:ProtectedTitles]]. An option in the drop-down box 'Restriction level' and in brackets after each page name entry. See the [http://www.mediawiki.org/wiki/Project:Protected_titles help page on Mediawiki] and on [http://meta.wikimedia.org/wiki/Protect Meta] for more information.",
'restriction-level-autoconfirmed' => "Used on [[Special:ProtectedPages]] and [[Special:ProtectedTitles]]. An option in the drop-down box 'Restriction level', and in brackets after each page name entry. See the [http://www.mediawiki.org/wiki/Project:Protected_titles help page on Mediawiki] and on [http://meta.wikimedia.org/wiki/Protect Meta] for more information.",
'restriction-level-all'           => "Used on [[Special:ProtectedPages]] and [[Special:ProtectedTitles]]. An option in the drop-down box 'Restriction level'. See the [http://www.mediawiki.org/wiki/Project:Protected_titles help page on Mediawiki] and on [http://meta.wikimedia.org/wiki/Protect Meta] for more information.",

# Undelete
'undelete'                   => 'Name of special page for admins as displayed in [[Special:SpecialPages]].

{{Identical|View deleted pages}}',
'undeletepage'               => 'Title of special page [[Special:Undelete]]. This special page is only visible to administrators.',
'viewdeletedpage'            => 'Title of the [[Special:Undelete]].

{{Identical|View deleted pages}}',
'undeleteextrahelp'          => "Help message displayed when restoring history of a page. In your language, ''Restore'' is called ''[[MediaWiki:Undeletebtn/{{SUBPAGENAME}}|{{int:Undeletebtn}}]]'' ({{msg|Undeletebtn}}), The ''Reset'' button is called ''[[MediaWiki:Undeletereset/{{SUBPAGENAME}}|{{int:Undeletereset}}]]'' ({{msg|Undeletereset}}).",
'undelete-revision'          => 'Shown in "View and restore deleted pages" ([[Special:Undelete/$1]]).

* $1: deleted page name
* $3: user name (author of revision, not who deleted it)
* $4: date of the revision
* $5: time of the revision

\'\'Example:\'\' Deleted revision of [[Main Page]] (as of {{CURRENTDAY}} {{CURRENTMONTHNAME}} {{CURRENTYEAR}}, at {{CURRENTTIME}}) by [[User:Username|Username]]:',
'undeletebtn'                => 'Shown on [[Special:Undelete]] as button caption and on [[Special:Log/delete|deletion log]] after each entry (for sysops).

{{Identical|Restore}}',
'undeletelink'               => 'Display name of link to undelete a page used on [[Special:Log/delete]]

{{Identical|View}}
{{Identical|Restore}}',
'undeleteviewlink'           => 'First part of {{msg-mw|undeletelink}}',
'undeletereset'              => 'Shown on [[Special:Undelete]] as button caption.
{{Identical|Reset}}',
'undeleteinvert'             => '{{Identical|Invert selection}}',
'undeletecomment'            => '{{Identical|Reason}}',
'undelete-search-submit'     => '{{Identical|Search}}',
'undelete-show-file-confirm' => 'A confirmation message shown on Special:Undelete when the request does not contain a valid token (e.g. when a user clicks a link received in mail).
* <code>$1</code> is the name of the file being undeleted.
* <code>$2</code> is the date of the displayed revision.
* <code>$3</code> is the time of the displayed revision.

{{identical|Are you sure you want to view the deleted revision of the file...}}',
'undelete-show-file-submit'  => '{{Identical|Yes}}',

# Namespace form on various pages
'namespace'                     => 'This message is located at [[Special:Contributions]].',
'invert'                        => 'Displayed in [[Special:RecentChanges|RecentChanges]], [[Special:RecentChangesLinked|RecentChangesLinked]] and [[Special:Watchlist|Watchlist]]

{{Identical|Invert selection}}',
'tooltip-invert'                => 'Used in [[Special:Recentchanges]] as a tooltip for the invert checkbox. See also the message {{msg-mw|invert}}',
'namespace_association'         => 'Used in [[Special:Recentchanges]] with a checkbox which selects the associated namespace to be added to the selected namespace, so that both are searched (or excluded depending on another checkbox selection). The association is between a namespace and its talk namespace.',
'tooltip-namespace_association' => 'Used in [[Special:Recentchanges]] as a tooltip for the associated namespace checkbox. See also the message {{msg-mw|namespace_association}}',
'blanknamespace'                => 'Name for main namespace (blank namespace) in drop-down menus at [[Special:RecentChanges]] and other special pages.',

# Contributions
'contributions'       => "Display name for the 'User contributions', shown in the sidebar menu of all user pages and user talk pages. Also the page name of the target page. The target page shows an overview of the most recent contributions by a user.",
'contributions-title' => 'The page title in your browser bar, but not the page title. See also {{msg|contributions}}. Parameter $1 is the username.

{{Gender}}',
'mycontris'           => 'In the personal urls page section - right upper corner.',
'contribsub2'         => 'Contributions for "user" (links)',
'nocontribs'          => 'Optional parameter: $1 is the user name',
'uctop'               => 'This message is used in [[Special:Contributions]]. It is used to show that a particular edit was the last made to a page. Example: 09:57, 11 February 2008 (hist) (diff) Pagename‎ (edit summary) (top)',
'month'               => 'Used in [[Special:Contributions]] and history pages ([{{fullurl:Sandbox|action=history}} example]), as label for a dropdown box to select a specific month to view the edits made in that month, and the earlier months. See also {{msg|year}}.',
'year'                => 'Used in [[Special:Contributions]] and history pages ([{{fullurl:Sandbox|action=history}} example]), as label for a inputbox to select a specific year to view the edits made in that year, and the earlier years. See also {{msg|month}}.',

'sp-contributions-newbies'             => 'Text of radio button on special page [[Special:Contributions]].',
'sp-contributions-newbies-sub'         => "Note at the top of the page of results for a search on [[Special:Contributions]] where 'Show contributions for new accounts only' has been selected.",
'sp-contributions-newbies-title'       => 'The page title in your browser bar, but not the page title. See also {{msg|sp-contributions-newbies-sub}}.',
'sp-contributions-blocklog'            => 'Used as a display name for a link to the block log on for example [[Special:Contributions/Mediawiki default]]

{{Identical|Block log}}',
'sp-contributions-deleted'             => "This is a link anchor used in [[Special:Contributions]]/''name'', when user viewing the page has the right to delete pages, or to restore deleted pages.",
'sp-contributions-uploads'             => '{{Identical|Upload}}',
'sp-contributions-logs'                => "Appears as an action link in the header of the Special:Contributions/''Username'' pages (e.g. \"For Somebody (talk | block log | logs)\").",
'sp-contributions-talk'                => "This is a link anchor used in the [[Special:Contributions]]/''usernamename'' pages.
The link appears in a list of similar ones separated by {{msg-mw|pipe-separator}}, e.g. like this:<br />
( talk | block log | logs | deleted contributions | rights management )
{{Identical|Talk}}",
'sp-contributions-userrights'          => "This is a link anchor used in [[Special:Contributions]]/''name'', if the user viewing the page has the right to set or alter user rights.",
'sp-contributions-blocked-notice'      => 'Shown on top of contributions special page of currently blocked users. Parameters:
* $1 is the blocked user. Can be used for GENDER (optional)
Anon version: {{msg-mw|Sp-contributions-blocked-notice-anon}}',
'sp-contributions-blocked-notice-anon' => 'Same as {{msg-mw|Sp-contributions-blocked-notice}} but for anonymous users.',
'sp-contributions-search'              => 'Used on [[Special:Contributions]]',
'sp-contributions-username'            => 'This message appears whenever someone requests [[Special:Contributions]].

{{Identical/IP address or username}}',
'sp-contributions-toponly'             => '"top revision" means the "latest revision"',
'sp-contributions-submit'              => '{{Identical|Search}}',
'sp-contributions-explain'             => '{{optional}}',

# What links here
'whatlinkshere'            => 'The text of the link in the toolbox (on the left, below the search menu) going to [[Special:WhatLinksHere]].',
'whatlinkshere-title'      => "Title of the special page [[Special:WhatLinksHere]]. This page appears when you click on the 'What links here' button in the toolbox. $1 is the name of the page concerned.",
'whatlinkshere-page'       => '{{Identical|Page}}',
'whatlinkshere-backlink'   => '{{optional}}',
'linkshere'                => "This message is the header line of the [[Special:WhatLinksHere/$1]] page generated by clicking 'What links here' in the sidebar toolbox. It is followed by a navigation bar built using {{msg-mw|Viewprevnext}}.",
'nolinkshere'              => 'This appears on Whatlinkshere pages which are empty.

Parameter $1 is a page title.',
'isredirect'               => 'Displayed in Special:WhatLinksHere (see [{{fullurl:Special:WhatLinksHere/Project:Translator|hidelinks=1}} Special:WhatLinksHere/Project:Translator] for example).

{{Identical|Redirect page}}',
'istemplate'               => 'Means that a page (a template, specifically) is used as <code><nowiki>{{Page name}}</nowiki></code>.
Displayed in Special:WhatLinksHere (see [[Special:WhatLinksHere/Template:New portal]] for example).',
'isimage'                  => 'This message is displayed on [[Special:WhatLinksHere]] for images. It means that the image is used on the page (as opposed to just being linked to like an non-image page).',
'whatlinkshere-prev'       => 'This is part of the navigation message on the top and bottom of Whatlinkshere pages, where it is used as the first argument of {{msg-mw|Viewprevnext}}.
$1 is the number of items shown per page. It is not used when $1 is zero; not sure what happens when $1 is one.
Special pages use {{msg-mw|Prevn}} instead (still as an argument to {{msg-mw|Viewprevnext}}).

{{Identical|Previous}}',
'whatlinkshere-next'       => 'This is part of the navigation message on the top and bottom of Whatlinkshere pages, where it is used as the second argument of {{msg-mw|Viewprevnext}}.
$1 is the number of items shown per page. It is not used when $1 is zero; not sure what happens when $1 is one.
Special pages use {{msg-mw|Nextn}} instead (still as an argument to {{msg-mw|Viewprevnext}}).

{{Identical|Next}}',
'whatlinkshere-links'      => 'Used on [[Special:WhatLinksHere]]. It is a link to the WhatLinksHere page of that page.

Example line:
* [[Main Page]] ([[Special:WhatLinksHere/Main Page|{{int:whatlinkshere-links}}]])

{{Identical|Links}}',
'whatlinkshere-hideredirs' => 'Parameter $1 is the message "[[MediaWiki:Hide/{{SUBPAGENAME}}|hide]]" or "[[MediaWiki:Show/{{SUBPAGENAME}}|show]]".',
'whatlinkshere-hidetrans'  => 'Parameter $1 is the message "[[MediaWiki:Hide/{{SUBPAGENAME}}|hide]]" or "[[MediaWiki:Show/{{SUBPAGENAME}}|show]]".',
'whatlinkshere-hidelinks'  => 'Parameter $1 is the message "[[MediaWiki:Hide/{{SUBPAGENAME}}|hide]]" or "[[MediaWiki:Show/{{SUBPAGENAME}}|show]]".',
'whatlinkshere-hideimages' => 'This is the text of the option on [[Special:WhatLinksHere]] for image pages, allowing to hide/show pages which display the file inline.
Parameter $1 is the message "[[MediaWiki:Hide/{{SUBPAGENAME}}|hide]]" or "[[MediaWiki:Show/{{SUBPAGENAME}}|show]]".',
'whatlinkshere-filters'    => '{{Identical|Filter}}',

# Block/unblock
'block'                       => 'Name of the special page on [[Special:SpecialPages]]',
'unblock'                     => 'Name of the special page on [[Special:SpecialPages]]',
'blockip'                     => 'The title of the special page [[Special:BlockIP]].

{{Identical|Block user}}',
'blockip-title'               => '{{Identical|Block user}}',
'blockip-legend'              => 'Legend/Header for the fieldset around the input form of [[Special:BlockIP]].

{{Identical|Block user}}',
'ipaddress'                   => '{{Identical|IP Address}}',
'ipadressorusername'          => '{{Identical/IP address or username}}',
'ipbexpiry'                   => '{{Identical|Expiry}}',
'ipbreason'                   => 'Label of the block reason dropdown in [[Special:BlockIP]] and the unblock reason textfield in [{{fullurl:Special:IPBlockList|action=unblock}} Special:IPBlockList?action=unblock].

{{Identical|Reason}}',
'ipbreasonotherlist'          => '{{Identical|Other reason}}',
'ipbcreateaccount'            => '{{Identical|Prevent account creation}}',
'ipbemailban'                 => '{{Identical|Prevent user from sending e-mail}}',
'ipbenableautoblock'          => '{{Identical|Automatically block ...}}',
'ipbsubmit'                   => '{{Identical|Block this user}}',
'ipbother'                    => '{{Identical|Other time}}',
'ipboptions'                  => "* Description: Options for the duration of the block.
* <font color=\"red\">Be careful:</font> '''1 translation:1 english''', so the first part is the translation and the second part should stay in English.
* Example: See e.g. [[MediaWiki:Ipboptions/nl]] if you still don't know how to do it.

{{Identical|Infinite}}",
'ipbotheroption'              => '{{Identical|Other}}',
'ipbotherreason'              => '{{Identical|Other/additional reason}}',
'ipbhidename'                 => 'This is the label for a checkbox in the user block form on [[Special:Block]].',
'ipbwatchuser'                => 'This is an option on [[Special:BlockIP]] to watch the user page and talk page of the blocked user',
'ipb-change-block'            => 'Confirmation checkbox required for blocks that would override an earlier block. Appears together with {{msg|ipb-needreblock}}.',
'badipaddress'                => 'An error message shown when one entered an invalid IP address in blocking page.',
'blockipsuccesstext'          => '<nowiki>{{</nowiki>[[Gender|GENDER]]<nowiki>}}</nowiki> is supported.',
'ipb-edit-dropdown'           => 'Shown beneath the user block form on the right side. It is a link to [[MediaWiki:Ipbreason-dropdown]]. See also {{msg|Delete-edit-reasonlist}} and {{msg|Protect-edit-reasonlist}}.',
'ipusubmit'                   => 'Used as button text on Special:BlockList?action=unblock. To see the message:
* Go to [[Special:BlockList]]
* Click "unblock" for any block (but you can only see "unblock" if you have administrator rights)
* It is now the button below the form',
'unblocked'                   => 'Do not translate the namespace "User:".  $1 - the IP address or username that was unblocked.',
'unblocked-range'             => 'Shown when successfully lifting a rangeblock, so do not link to contributions.
* $1 - the range that was unblocked.',
'ipblocklist'                 => 'Title of [[Special:Ipblocklist]].',
'blocklist-rangeblocks'       => 'For an explanation of "range blocks", see http://www.mediawiki.org/wiki/Help:Range_blocks',
'blocklist-timestamp'         => 'This is a column header for dates and times in the table on the page [[Special:BlockList]].
{{Identical|Timestamp}}',
'blocklist-target'            => 'The table header for the column containing the block targets, that is user names or IP-addresses linked to their respective user pages, in the table on the page [[Special:BlockList]].',
'blocklist-expiry'            => 'This is a column header in the table on the page [[Special:BlockList]].',
'blocklist-by'                => 'This is a column header in the table on the page [[Special:BlockList]].',
'blocklist-params'            => 'This is a column header in the table on the page [[Special:BlockList]].',
'blocklist-reason'            => 'This is a column header in the table on the page [[Special:BlockList]].

{{Identical|Reason}}',
'ipblocklist-submit'          => '{{Identical|Search}}',
'ipblocklist-localblock'      => '[[File:Special IPBlockList new.png|thumb|Example]]
Used on [[Special:IPBlockList]] as header when global blocks exists too.',
'ipblocklist-otherblocks'     => '[[File:Special IPBlockList new.png|thumb|Example]]
Used on [[Special:IPBlockList]] as header for other blocks, i.e. from GlobalBlocking or TorBlocks.',
'infiniteblock'               => "* contents of $4 in {{msg-mw|Blocklistline|notext=1}}: (''{{int:Blocklistline}}'')
* contents of $4 in {{msg-mw|Globalblocking-blocked|notext=1}}:
<blockquote>''{{int:Globalblocking-blocked}}''</blockquote>
*See also {{msg-mw|Expiringblock}}

{{Identical|Infinite}}",
'expiringblock'               => 'Parameters:
* $1 is a date
* $2 is a time

Usage:
* Substituted as $4 in in {{msg-mw|Blocklistline|notext=1}}
* Substituted as $4 in  in {{msg-mw|Globalblocking-blocked|notext=1}}
* See also {{msg-mw|Infiniteblock}}',
'anononlyblock'               => 'Part of the log entry of user block.

{{Identical|Anon only}}',
'noautoblockblock'            => '{{Identical|Autoblock disabled}}',
'emailblock'                  => '{{Identical|E-mail blocked}}',
'blocklist-nousertalk'        => 'Used in [[Special:IPBlockList]] when "Allow this user to edit own talk page while blocked" option hasn\'t been flagged. See also {{msg-mw|Block-log-flags-nousertalk}}.',
'ipblocklist-empty'           => 'Shown on page [[Special:Blocklist]], if no blocks are to be shown.',
'blocklink'                   => "Display name for a link that, when selected, leads to a form where a user can be blocked. Used in page history and recent changes pages. Example: \"''UserName (Talk | contribs | '''block''')''\".",
'change-blocklink'            => 'Used to name the link on Special:Log',
'contribslink'                => 'Short for "contributions". Used as display name for a link to user contributions on history pages, [[Special:RecentChanges]], [[Special:Watchlist]], etc.',
'emaillink'                   => 'Used as display name for a link to send an e-mail to a user in the user tool links. Example: "(Talk | contribs | block | send e-mail)".

{{Identical|E-mail}}',
'blocklogpage'                => "The page name of [[Special:Log/block]]. Also appears in the drop down menu of [[Special:Log]] pages and in the action links of Special:Contributions/''Username'' pages (e.g. \"For Somebody (talk | block log | logs)\").

{{Identical|Block log}}",
'blocklog-showlog'            => 'Parameters:
* $1 is the blocked user. Can be used for GENDER (optional)',
'blocklog-showsuppresslog'    => 'Parameters:
* $1 is the blocked user. Can be used for GENDER (optional)',
'blocklogentry'               => 'This is the text of an entry in the Block log, and recent changes, after hour (and date, only in the Block log) and sysop name:
* $1 is the blocked user or IP (with link to contributions and talk)
* $2 is the duration of the block (hours, days etc.) or the specified expiry date
* $3 contains "(details) (\'\'reason\'\')"
See also {{msg-mw|Blocklistline}}.',
'reblock-logentry'            => 'This is the text of an entry in the Block log (and Recent Changes), after hour (and date, only in the Block log) and sysop name:
* $1 is the user being reblocked
* $2 is the expiry time of the block
* $3 are extra parameters like "account creation blocked" (they are automatically between brackets)',
'blocklogtext'                => 'Appears on top of [[Special:Log/block]].',
'unblocklogentry'             => 'This is the text of an entry in the Block log (and Recent Changes), after hour (and date, only in the Block log) and sysop name:
* $1 is the user being unblocked',
'block-log-flags-noautoblock' => '{{Identical|Autoblock disabled}}',
'block-log-flags-noemail'     => "Log message for [[Special:Log/block]] to note that a user cannot use the 'email another user' option.

{{Identical|E-mail blocked}}",
'block-log-flags-nousertalk'  => 'Used in [[Special:Log/block]] when "Allow this user to edit own talk page while blocked" option hasn\'t been flagged. See also {{msg-mw|Blocklist-nousertalk}}.',
'ipb_expiry_temp'             => 'Warning message displayed on [[Special:BlockIP]] if the option "hide username" is selected but the expiry time is not infinite.',
'ipb_already_blocked'         => '{{Identical|$1 is already blocked}}',
'ipb-otherblocks-header'      => '[[File:Special.Block with other blocks from GlobalBlocking and TorBlocks.png|thumb|Example]]
Used on [[Special:Block]] as header for other blocks, i.e. from GlobalBlocking or TorBlocks',
'blockme'                     => 'The page title of [[Special:Blockme]], a feature which is disabled by default.',
'proxyblocksuccess'           => '{{Identical|Done}}',
'sorbs'                       => '{{optional}}',
'cant-see-hidden-user'        => 'Used as (red) error message on Special:Block when you try to change (as sysop w/o the hideuser right) the block of a hidden user.',
'ipbblocked'                  => 'Error message shown when a user tries to alter block settings when they are themselves blocked.',
'ipbnounblockself'            => 'Error message shown when a user without the <tt>unblockself</tt> right tries to unblock themselves.',

# Developer tools
'lockdb'              => 'The title of the special page [[Special:LockDB]].

{{Identical|Lock database}}',
'unlockdb'            => 'The title of the special page [[Special:UnlockDB]].

{{Identical|Unlock database}}',
'lockbtn'             => 'The submit button on the special page [[Special:LockDB]].

{{Identical|Lock database}}',
'unlockbtn'           => 'The submit button on the special page [[Special:UnlockDB]].

{{Identical|Unlock database}}',
'lockfilenotwritable' => "'No longer needed' on wikipedia.",
'lockedbyandtime'     => 'Used as part of the message when a database is locked through [[Special:LockDB]]. Parameters:
* $1 is the user that locked the database.
* $2 is the date on which the lock was made
* $3 is the time at which the lock was made',

# Move page
'move-page'                    => 'Header of the special page to move pages. $1 is the name of the page to be moved.',
'move-page-legend'             => 'Legend of the fieldset around the input form of [[Special:MovePage/testpage]].

{{Identical|Move page}}',
'movepagetext'                 => 'Introduction shown when moving a page ([[Special:MovePage]]).

Special pages mentioned: {{msg-mw|Doubleredirects}}, {{msg-mw|Brokenredirects}}

See also {{msg-mw|Movepagetext-noredirectfixer|notext=1}}',
'movepagetext-noredirectfixer' => 'A variant of {{msg-mw|Movepagetext|notext=1}} displayed when the automatic redirect fixer is not enabled.',
'movepagetalktext'             => "Text on the special 'Move page'. This text only appears if the talk page is not empty.",
'movearticle'                  => 'The text before the name of the page that you are moving. Can be translated as "Page that is going to be moved"

{{Identical|Move page}}',
'movenologin'                  => '{{Identical|Not logged in}}',
'movenologintext'              => "Text of message on special page 'Permissions Errors', which appears when somebody tries to move a page without being logged in.",
'newtitle'                     => 'Used in the special page "[[Special:MovePage]]". The text for the inputbox to give the new page title.',
'move-watch'                   => 'The text of the checkbox to watch the pages you are moving from and to. If checked, both the destination page and the original page will be added to the watchlist, even if you decide not to leave a redirect behind.',
'movepagebtn'                  => "Button label on the special 'Move page'.

{{Identical|Move page}}",
'pagemovedsub'                 => 'Message displayed as aheader of the body, after succesfully moving a page from source to target name.',
'movepage-moved'               => 'Message displayed after succesfully moving a page from source to target name.
* $1 is the source page as a link with display name
* $2 is the target page as a link with display name
* $3 (optional) is the source page name without a link
* $4 (optional) is the target page name without a link',
'movepage-moved-noredirect'    => 'The message is shown after pagemove if checkbox "{{int:move-leave-redirect}}" was unselected before moving.',
'movetalk'                     => 'The text of the checkbox to watch the associated talk page to the page you are moving. This only appears when the talk page is not empty.',
'move-subpages'                => 'The text of an option on the special page [[Special:MovePage|MovePage]]. If this option is ticked, any subpages will be moved with the main page to a new title.',
'move-talk-subpages'           => 'The text of an option on the special page [[Special:MovePage|MovePage]]. If this option is ticked, any talk subpages will be moved with the talk page to a new title.',
'movelogpage'                  => 'Title of [[Special:Log/move]]. Used as heading on that page, and in the dropdown menu on log pages.',
'movelogpagetext'              => "Text on the special page 'Move log'.",
'movesubpage'                  => "This is a page header.
Parameters:
*'''$1''' = number of subpages
<!--{{Note|Plural is supported if you need it, the number of subpages is available in <code>$1</code>.}}-->",
'movereason'                   => 'Used in [[Special:MovePage]]. The text for the inputbox to give a reason for the page move.

{{Identical|Reason}}',
'revertmove'                   => '{{Identical|Revert}}',
'delete_and_move_text'         => 'Used when moving a page, but the destination page already exists and needs deletion. This message is to confirm that you really want to delete the page. See also {{msg|delete and move confirm}}.',
'delete_and_move_confirm'      => 'Used when moving a page, but the destination page already exists and needs deletion. This message is for a checkbox to confirm that you really want to delete the page. See also {{msg|delete and move text}}.',
'delete_and_move_reason'       => 'Shown as reason in content language in the deletion log. Parameter:
* $1: The page name for which this page was deleted.',
'immobile-target-namespace-iw' => "This message appears when attempting to move a page, if a person has typed an interwiki link as a namespace prefix in the input box labelled 'To new title'.  The special page 'Movepage' cannot be used to move a page to another wiki.

'Destination' can be used instead of 'target' in this message.",
'fix-double-redirects'         => 'This is a checkbox in [[Special:MovePage]] which allows to move all redirects from the old title to the new title.',
'protectedpagemovewarning'     => 'Related message: [[MediaWiki:protectedpagewarning/{{#titleparts:{{PAGENAME}}|1|2}}]]
{{Related|Semiprotectedpagewarning}}',
'semiprotectedpagemovewarning' => 'Related message: [[MediaWiki:Semiprotectedpagewarning/{{#titleparts:{{PAGENAME}}|1|2}}]]
{{Related|Semiprotectedpagewarning}}',
'move-over-sharedrepo'         => 'Notice when a file with that filename already exists on a shared repository, such as Wikimedia Commons.',

# Export
'export'           => 'Page title of [[Special:Export]], a page where a user can export pages from a wiki to a file.',
'exporttext'       => 'Main text on [[Special:Export]]. Leave the line <tt><nowiki>[[{{#Special:Export}}/{{MediaWiki:Mainpage}}]]</nowiki></tt> exactly as it is!',
'exportcuronly'    => 'A label of checkbox option in [[Special:Export]]',
'export-submit'    => 'Button name in [[Special:Export]].

{{Identical|Export}}',
'export-addcat'    => '{{Identical|Add}}',
'export-addnstext' => 'Message {{msg-mw|configure-setting-wgExportFromNamespaces}} refers to this one.',
'export-addns'     => '{{Identical|Add}}',
'export-download'  => 'A label of checkbox option in [[Special:Export]]',
'export-templates' => 'A label of checkbox option in [[Special:Export]]',
'export-pagelinks' => 'This is an input in [[Special:Export]]',

# Namespace 8 related
'allmessages'                   => 'The title of the special page [[Special:AllMessages]].',
'allmessagesname'               => 'Used on [[Special:Allmessages]] meaning "the name of the message".
{{Identical|Name}}',
'allmessagesdefault'            => 'The header for the lower row of each column in the table of [[Special:AllMessages]].',
'allmessagescurrent'            => 'The header for the upper row of each column in the table of [[Special:AllMessages]].',
'allmessagestext'               => 'Summary displayed at the top of [[Special:AllMessages]].',
'allmessagesnotsupportedDB'     => 'This message is displayed on [[Special:AllMessages]] on wikis were the configuration variable $wgUseDatabaseMessages is disabled. It means that the MediaWiki namespace is not used.',
'allmessages-filter-legend'     => 'Used in [[Special:AllMessages]].

{{Identical|Filter}}',
'allmessages-filter'            => 'Option used in [[Special:AllMessages]].',
'allmessages-filter-unmodified' => 'Used in [[Special:AllMessages]].',
'allmessages-filter-all'        => 'Used in [[Special:AllMessages]].
{{Identical|All}}',
'allmessages-filter-modified'   => 'Used in [[Special:AllMessages]].',
'allmessages-prefix'            => 'Used in [[Special:AllMessages]].',
'allmessages-language'          => 'Used on [[Special:Allmessages]].

{{Identical|Language}}',
'allmessages-filter-submit'     => 'Used on [[Special:Allmessages]].

{{Identical|Go}}',

# Thumbnails
'thumbnail-more'          => '[[Image:Yes.png|thumb|This:]]
Tooltip shown when hovering over a little sign of a thumb image, to go to the image page (where it is bigger). For example, see the image at the right:',
'thumbnail_error'         => 'Message shown in a thumbnail frame when creation of the thumbnail fails.
* $1 is the reason',
'thumbnail_image-type'    => 'This is the parameter 1 of the message {{msg-mw|thumbnail error}}',
'thumbnail_gd-library'    => 'This is the parameter 1 of the message {{msg-mw|thumbnail error}}.
*$1 is a function name of the GD library',
'thumbnail_image-missing' => 'This is the parameter 1 of the message {{msg-mw|thumbnail error}}.
*$1 is the path incl. filename of the missing image',

# Special:Import
'import'                   => 'The title of the special page [[Special:Import]];',
'import-interwiki-history' => 'This is an option on [[Special:Import]]. Usually, when unchecked, only the first version of a page is imported. When you check the option, all versions are imported. This is important often to check for licensing reasons.',
'import-interwiki-submit'  => '{{Identical|Import}}',
'import-upload-filename'   => 'Used on [[Special:Import]] as label for upload of an XML file containing the pages to import.',
'xml-error-string'         => ':$1: Some kind of message, perhaps name of the error?
:$2: line number
:$3: columm number
:$4: ?? $this->mByte . $this->mContext
:$5: error description
----
:Example
Import failed: XML import parse failure at line 1, col 1 (byte 3; "- <mediawiki xml"): Empty document',
'import-upload'            => 'Used on [[Special:Import]].

Related messages: {{msg|right-importupload|pl=yes}} (the user right for this).',
'import-error-edit'        => 'Import error message displayed when importing user has no edit rights for a page. Parameters:
* $1 is a page name.',
'import-error-create'      => 'Import error message displayed when importing user has no create rights for a page. Parameters:
* $1 is a page name.',

# Import log
'importlogpage'          => '',
'importlogpagetext'      => 'This text appears at the top of the [http://translatewiki.net/w/i.php?title=Special%3ALog&type=import&user=&page=&year=&month=-1 import log] special page.',
'import-logentry-upload' => 'This is the text of an entry in the Import log (and Recent Changes), after hour (and date, only in the Import log) and sysop name:
* $1 is the name of the imported file',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'This text appears in the tool-tip when you hover the mouse over your the tab with you User name on it',
'tooltip-pt-mytalk'               => 'Tooltip shown when hovering over the "my talk" link in your personal toolbox (upper right side).',
'tooltip-pt-preferences'          => 'Tooltip shown when hovering over the "my preferences" ([[MediaWiki:Mypreferences]]) link in your personal toolbox (upper right side).

{{Identical|My preferences}}',
'tooltip-pt-watchlist'            => 'Tooltip shown when hovering over the "my watchlist" link in your personal toolbox (upper right side).',
'tooltip-pt-mycontris'            => 'Tooltip shown when hovering over the "my contributions" link in your personal toolbox (upper right side).',
'tooltip-pt-login'                => "Tooltip shown when hovering over the link 'Log in / create account' in the upper right corner show on all pages while not logged in.",
'tooltip-pt-logout'               => 'Tooltip shown when hovering over the "Log out" link in your personal toolbox (upper right side).

{{Identical|Log out}}',
'tooltip-ca-talk'                 => "Tooltip shown when hovering over the \"[[MediaWiki:Talk/{{SUBPAGENAME}}|{{int:talk}}]]\" tab.

A 'content page' is a page that forms part of the purpose of the wiki. It includes the main page and pages in the main namespace and any other namespaces that are included when the wiki is customised. For example on Wikimedia Commons 'content pages' include pages in the file and category namespaces. On Wikinews 'content pages' include pages in the Portal namespace. For a technical definition of 'content namespaces' see [http://www.mediawiki.org/wiki/Manual:Using_custom_namespaces#Content_namespaces Mediawiki].

Possible alternatives to the word 'content' are 'subject matter' or 'wiki subject' or 'wiki purpose'.

{{Identical|Content page}}",
'tooltip-ca-edit'                 => 'The tooltip when hovering over the "[[MediaWiki:Edit/{{SUBPAGENAME}}|{{int:edit}}]]" tab.',
'tooltip-ca-addsection'           => 'Tooltip shown when hovering over the "addsection" tab (shown on talk pages).',
'tooltip-ca-viewsource'           => 'Tooltip displayed when hovering over the {{msg|viewsource}} tab.',
'tooltip-ca-protect'              => '{{Identical|Protect this page}}',
'tooltip-ca-unprotect'            => '{{Identical|Unprotect this page}}',
'tooltip-ca-delete'               => 'Tooltip shown when hovering over the "[[MediaWiki:Delete/{{SUBPAGENAME}}|{{int:delete}}]]" tab.

{{Identical|Delete this page}}',
'tooltip-ca-move'                 => '{{Identical|Move this page}}',
'tooltip-ca-watch'                => '{{Identical|Add this page to your watchlist}}',
'tooltip-ca-unwatch'              => 'Tooltip shown when hovering over the {{msg|unwatch}} tab.',
'tooltip-search'                  => 'The tooltip when hovering over the search menu.',
'tooltip-search-go'               => 'This is the text of the tooltip displayed when hovering the mouse over the “[[MediaWiki:Go|Go]]” button next to the search box.',
'tooltip-search-fulltext'         => 'This is the text of the tooltip displayed when hovering the mouse over the “[[MediaWiki:Search|Search]]” button under the search box.',
'tooltip-p-logo'                  => 'Tool tip shown when hovering the mouse over the logo that links to [[Main Page]].
{{Identical|Visit the main page}}',
'tooltip-n-mainpage'              => 'Tool tip shown when hovering the mouse over the link to [[{{MediaWiki:Mainpage}}]].
{{Identical|Visit the main page}}',
'tooltip-n-mainpage-description'  => '{{Identical|Visit the main page}}',
'tooltip-n-portal'                => "Tooltip shown when hovering over the link to 'Community portal' shown in the side bar menu on all pages.",
'tooltip-n-currentevents'         => 'Tooltip shown when hovering over {{msg|currentevents}} in the sidebar.',
'tooltip-n-recentchanges'         => 'The tooltip when hovering over the "[[MediaWiki:Recentchanges/{{SUBPAGENAME}}|{{int:recentchanges}}]]" link in the sidebar going to the special page [[Special:RecentChanges]].',
'tooltip-n-randompage'            => "Tooltip shown when hovering over the link to 'Random page' shown in the side bar menu on all pages. Clicking the link will show a random page in from the wiki's main namespace.",
'tooltip-n-help'                  => "Tooltip shown when hovering over the link 'help' shown in the side bar menu on all pages.",
'tooltip-t-whatlinkshere'         => 'Tooltip shown when hovering over the {{msg|whatlinkshere}} message in the toolbox.',
'tooltip-t-contributions'         => 'Tooltip shown when hovering over {{msg|contributions}} in the toolbox.',
'tooltip-t-emailuser'             => 'Tooltip shown when hovering over the {{msg|emailuser}} link in the toolbox (sidebar, below).',
'tooltip-t-upload'                => 'Tooltip shown when hovering over the link to upload files shown in the side bar menu on all pages.',
'tooltip-t-specialpages'          => 'The tooltip when hovering over the link "[[MediaWiki:Specialpages/{{SUBPAGENAME}}|{{int:specialpages}}]]" going to a list of all special pages available in the wiki.',
'tooltip-ca-nstab-main'           => "A 'content page' is a page that forms part of the purpose of the wiki. It includes the main page and pages in the main namespace and any other namespaces that are included when the wiki is customised. For example on Wikimedia Commons 'content pages' include pages in the file and category namespaces. On Wikinews 'content pages' include pages in the Portal namespace. For technical definition of 'content namespaces' see [http://www.mediawiki.org/wiki/Manual:Using_custom_namespaces#Content_namespaces Mediawiki].

Possible alternatives to the word 'content' are 'subject matter' or 'wiki subject' or 'wiki purpose'.

{{Identical|Content page}}",
'tooltip-ca-nstab-user'           => 'Tooltip shown when hovering over {{msg|nstab-user}} (User namespace tab).',
'tooltip-ca-nstab-image'          => 'Tooltip shown when hovering over {{msg|nstab-image}} (Image namespace tab).',
'tooltip-ca-nstab-template'       => 'Tooltip shown when hovering over the {{msg|nstab-template}} tab.',
'tooltip-ca-nstab-help'           => 'Tootip shown when hovering over the {{msg|nstab-help}} tab in the Help namespace.',
'tooltip-ca-nstab-category'       => 'Tooltip shown when hovering over the {{msg|nstab-category}} tab.',
'tooltip-minoredit'               => 'Tooltip shown when hovering over the "[[MediaWiki:Minoredit/{{SUBPAGENAME}}|{{int:minoredit}}]]" link below the edit form.',
'tooltip-save'                    => "This is the text that appears when you hover the mouse over the 'Save page' button on the edit page",
'tooltip-preview'                 => 'Tooltip shown when hovering over the "Show preview" button.

If the length of the translated message is over 60 characters (including spaces) then the end of the message will be cut off when using Firefox 2.0.0.7 browser, Linux operating system and the Monobook skin.',
'tooltip-diff'                    => 'This is the text (tooltip) that appears when you hover the mouse over the "Show changes" button ({{msg|showdiff}}) on the edit page.',
'tooltip-compareselectedversions' => 'Tooltip of {{msg|compareselectedversions}} (which is used as button in history pages).',
'tooltip-watch'                   => '{{Identical|Add this page to your watchlist}}',
'tooltip-rollback'                => 'Tooltip of the rollback link on the history page and the diff view
{{Identical|Rollback}}
{{Identical|Revert}}',
'tooltip-undo'                    => 'Tooltip of the undo link on the history page and the diff view
{{Identical|Undo}}{{Identical|Revert}}',

# Stylesheets
'common.css'   => 'CSS applied to all users.',
'monobook.css' => 'CSS applied to users using Monobook skin.',
'handheld.css' => 'Style that can be applied on [[w:handheld devices|handheld devices]] (e.g. mobile phones), <code>$wgHandheldStyle</code> is an optional configuration variable which specifies a style sheet file for handheld devices.',

# Scripts
'common.js'   => 'JS for all users.',
'monobook.js' => 'JS for users using Monobook skin.',

# Attribution
'anonymous'        => 'This message is shown when viewing the credits of a page (example: {{fullurl:Main Page|action=credits}}). Note that this action is disabled by default (currently enabled on translatewiki.net).
This message appears at the very end of the list of names in the message [[MediaWiki:Othercontribs/{{SUBPAGENAME}}|othercontribs]]. If there are no anonymous users in the credits list then this message does not appear at all.

* $1 is the number of anonymous users in the message',
'siteuser'         => "This message is shown when viewing the credits of a page ([{{fullurl:Main Page|action=credits}} example]). Note that this action is disabled by default, but currently enabled on translatewiki.net. This message is the variable $3 in the message {{msg-mw|lastmodifiedatby}}. This message only appears if a user has not entered their 'real name' in their preferences. See also {{msg-mw|Siteusers}}.

Parameters:
* $1 is a link to a user page with the user name as link text
* $2 is a user name for use with GENDER (optional)",
'anonuser'         => "This message is shown when viewing the credits of a page (example: {{fullurl:Main Page|action=credits}}). Note that this action is enabled by default but disabled on Wikimedia wikis.
This message is the variable $3 in the message {{msg-mw|lastmodifiedatby}}. This message only appears if the user is anonymous. The variable $1 in this message is a link to the user's contributions.

See also {{msg-mw|Anonusers}} and {{msg-mw|Siteuser}}.",
'lastmodifiedatby' => "This message is shown when viewing the credits of a page (example: {{fullurl:Main Page|action=credits}}). Note that this action is disabled by default (currently enabled on translatewiki.net).
* $1: date
* $2: time
* $3: if the user has entered his 'real name' in his preferences then this variable is his 'real name'. If the user has not entered his 'real name' in his preferences then this variable is the message [[Mediawiki:siteuser/{{SUBPAGENAME}}]], which includes his username.
* $4: username in plain text. Can be used for GENDER

See also [[MediaWiki:Lastmodifiedat/{{SUBPAGENAME}}]].",
'othercontribs'    => 'This message is shown when viewing the credits of a page (example: {{fullurl:Main Page|action=credits}}). Note that this action is disabled by default (currently enabled on translatewiki.net - to use type <nowiki>&action=credits</nowiki> at the end of any URL in the address bar).
* $1: the list of author(s) of the revisions preceding the current revision. It appears after the message [[Mediawiki:lastmodifiedatby/{{SUBPAGENAME}}]]. If there are no previous authors this message does not appear at all. If needed the messages [[Mediawiki:siteusers/{{SUBPAGENAME}}]], [[Mediawiki:anonymous/{{SUBPAGENAME}}]] and [[Mediawiki:and/{{SUBPAGENAME}}]] are part of the list of names.
* $2: optional, the count of names in $1',
'others'           => 'The following explanation is guesswork. This message is shown when viewing the credits of a page (example: {{fullurl:Main Page|action=credits}}). Note that this action is disabled by default (currently enabled on translatewiki.net - to use type <nowiki>&action=credits</nowiki> at the end of any URL in the address bar).

The message appears at the end of the list of credits given in the message [[Mediawiki:Othercontribs/{{SUBPAGENAME}}]] if the number of contributors is above a certain level.',
'siteusers'        => 'This message is shown when viewing the credits of a page (example: {{fullurl:Main Page|action=credits}}). Note that this action is disabled by default (currently enabled on translatewiki.net).
It should be in a form that fits with [[MediaWiki:Othercontribs/{{SUBPAGENAME}}|othercontribs]].

* $1 is a list of user names (example: "\'\'Jim, Janet, Jane, Joe\'\'") where the user has not put his \'real name\' in his preferences.
* $2 is the number of user names in $1

If there is more than one user in the list then the message {{msg-mw|and}} appears before the last name. If $2 is NIL then this message does not appear at all.

See also {{msg-mw|Siteuser}}.',
'anonusers'        => 'This message is shown when viewing the credits of a page (example: {{fullurl:Support|action=credits}}). Note that this action is disabled by default (currently enabled on translatewiki.net).
It should be in a form that fits with [[MediaWiki:Othercontribs/{{SUBPAGENAME}}|othercontribs]].

* $1 is a list of IP addresses
* $2 is the number of IP addresses in $1

If there is more than one user in the list then the message {{msg-mw|and}} appears before the last name. If $2 is NIL then this message does not appear at all.

See also {{msg-mw|Anonuser}} and {{msg-mw|Siteusers}}.',
'creditspage'      => "This message is the ''contentSub'' (the grey subtitle) shown when viewing credits of a page (example: {{fullurl:Project:News|action=credits}}). Note that the credits action is disabled by default (currently enabled on translatewiki.net).",
'nocredits'        => 'This message is shown when viewing the credits of a page (example: {{fullurl:Main Page|action=credits}}) but when there are no credits available. Note that the credits action is disabled by default (currently enabled on translatewiki.net).',

# Spam protection
'spam_reverting' => '{{Identical|Revert}}',

# Info page
'pageinfo-title'            => 'Page title for action=info.

* $1 is the page name',
'pageinfo-header-edits'     => 'Table section header in action=info.
{{Identical|Edit}}',
'pageinfo-header-watchlist' => 'Table section header in action=info.',
'pageinfo-header-views'     => 'Table section header in action=info.
{{Identical|View}}',
'pageinfo-subjectpage'      => 'Table header in action=info.
{{Identical|Page}}',
'pageinfo-talkpage'         => 'Table header in action=info.',

# Skin names
'skinname-standard'    => '{{optional}}',
'skinname-nostalgia'   => '{{optional}}',
'skinname-cologneblue' => '{{optional}}',
'skinname-monobook'    => '{{optional}}',
'skinname-myskin'      => '{{optional}}',
'skinname-chick'       => '{{optional}}',
'skinname-simple'      => '{{optional}}',
'skinname-modern'      => '{{optional}}',

# Patrolling
'markedaspatrolledtext' => '{{Identical|Markedaspatrolled}}',

# Patrol log
'patrol-log-page'      => 'Name of log.',
'patrol-log-header'    => 'Text that appears above the log entries on the [[Special:log|patrol log]].',
'log-show-hide-patrol' => '* $1 is one of {{msg|show}} or {{msg|hide}}',

# Browsing diffs
'previousdiff' => 'Used when viewing the difference between edits. See also {{msg|nextdiff}}.',
'nextdiff'     => 'Used when viewing the difference between edits. See also {{msg|previousdiff}}.',

# Media information
'mediawarning'         => 'Shows up on file description pages if the file type is not listed in [[mw:Manual:$wgTrustedMediaFormats|Manual:$wgTrustedMediaFormats]].',
'imagemaxsize'         => 'This is used in Special:Preferences, under Files.',
'widthheight'          => '{{optional}}',
'widthheightpage'      => 'This message is used on image pages in the dimensions column in the file history section for images  with more than one page.

Parameters:
* $1 is the width of the image pages in pixels.
* $2 is the height of the image pages in pixels.
* $3 is the number of pages in the file.',
'file-info'            => 'File info displayed on file description page.',
'file-info-size'       => 'File info displayed on file description page.

Parameters:
* $1 is the width of the image(s) in pixels.
* $2 is the height of the image(s) in pixels.
* $3 is the file size as a number followed by a unit — for example: 99 KB
* $4 is the MIME type, a formalized textual information — for example: <code>image/jpeg</code>',
'file-info-size-pages' => 'File info displayed on file description page, when the file is a multi-page document, with at least two pages. Like {{msg-mw|file-info-size}} but $5 is the total number of pages in the document.

Parameters:
* $1 is the width of the image pages in pixels.
* $2 is the height of the image pagess in pixels.
* $3 is the file size as a number followed by a unit — for example: 99 KB
* $4 is the MIME type, a formalized textual information — for example: <code>image/jpeg</code>
* $5 is the total number of pages in the document.',
'file-nohires'         => 'File info displayed on file description page. For example of message in use see [[:File:Mouse10.gif]].',
'svg-long-desc'        => 'Displayed under an SVG image at the image description page. Note that argument 3 is a string that includes the file size unit symbol. See for example [[:File:Yes check.svg]].',
'show-big-image'       => 'Displayed under an image at the image description page, when it is displayed smaller there than it was uploaded.',
'show-big-image-size'  => '
Parameters:
* $1 is the width of the image(s) in pixels.
* $2 is the height of the image(s) in pixels.',
'file-info-gif-looped' => 'Part of the information provided about a [http://en.wikipedia.org/wiki/Gif .gif file] on its file description page. Looped means repeating in the context of an animated gif. It is a sequence of images, each displayed after the other, and the first one displayed after the last, in a never ending loop. For example of message in use see [[:File:Mouse10.gif]].',
'file-info-gif-frames' => 'Part of the information provided about a [http://en.wikipedia.org/wiki/Gif .gif file] on its file description page.',
'file-info-png-looped' => 'Part of the information provided about a [http://en.wikipedia.org/wiki/APNG .apng file] on its file description page. Looped means repeating indefinetly in the context of an animated png. It is a sequence of images, each displayed after the other, and the first one displayed after the last, in a never ending loop.',
'file-info-png-repeat' => 'Part of the information provided about a [http://en.wikipedia.org/wiki/APNG .apng file] on its file description page. The sequence of images is repeating a limited amount of time. It is a sequence of images, each displayed after the other, and the first one displayed after the last, for $1 times.',
'file-info-png-frames' => 'Part of the information provided about a [http://en.wikipedia.org/wiki/APNG .apng file] on its file description page.

The variable $1 is the number of individual frames in an animated gif file.

For example of message in use see [[:File:Mouse10.gif]].',

# Special:NewFiles
'newimages'             => 'Page title of [[Special:NewImages]].',
'imagelisttext'         => 'This is text on [[Special:NewImages]]. $1 is the number of files. $2 is the message {{msg-mw|Bydate}}.',
'newimages-summary'     => 'This message is displayed at the top of [[Special:NewImages]] to explain what is shown on that special page.',
'newimages-legend'      => 'Caption of the fieldset for the filter on [[Special:NewImages]]

{{Identical|Filter}}',
'newimages-label'       => 'Caption of the filter editbox on [[Special:NewImages]]',
'showhidebots'          => 'This is shown on the special page [[Special:NewImages]]. The format is "{{int:showhidebots|[[MediaWiki:Hide/{{SUBPAGENAME}}|{{int:hide}}]]}}" or "{{int:showhidebots|[[MediaWiki:Show/{{SUBPAGENAME}}|{{int:show}}]]}}"

{{Identical|$1 bots}}',
'noimages'              => "This is shown on the special page [[Special:NewImages]], when there aren't any recently uploaded files.",
'ilsubmit'              => '{{Identical|Search}}',
'bydate'                => '{{Identical|Date}}',
'sp-newimages-showfrom' => "This is a link on [[Special:NewImages]] which takes you to a gallery of the newest files.
* $1 is a date (example: ''19 March 2008'')
* $2 is a time (example: ''12:15'')",

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'video-dims'     => '{{optional}}',
'seconds-abbrev' => '{{optional}}
Abbreviation for "seconds". $1 is the number of seconds.

See also {{msg-mw|seconds}}',
'minutes-abbrev' => '{{optional}}
Abbreviation for "minutes". $1 is the number of minutes.

See also {{msg-mw|minutes}}',
'hours-abbrev'   => '{{optional}}
Abbreviation for "hours". $1 is the number of hours.

See also {{msg-mw|hours}}',
'days-abbrev'    => '{{optional}}
Abbreviation for "days". $1 is the number of days.

See also {{msg-mw|days}}',
'seconds'        => 'Full word for "seconds". $1 is the number of seconds.

See also {{msg-mw|Seconds-abbrev}}

Part of variable $1 in {{msg-mw|Ago}}',
'minutes'        => 'Full word for "minutes". $1 is the number of minutes.

See also {{msg-mw|Minutes-abbrev}}

Part of variable $1 in {{msg-mw|Ago}}',
'hours'          => 'Full word for "hours". $1 is the number of hours.

See also {{msg-mw|Hours-abbrev}}

Part of variable $1 in {{msg-mw|Ago}}',
'days'           => 'Full word for "days". $1 is the number of days.

See also {{msg-mw|Days-abbrev}}

Part of variable $1 in {{msg-mw|Ago}}
{{Identical|Day}}',
'ago'            => 'Phrase for indicating how long ago something happened. $1 is something like "3 days 10 hours", taken from these messages:
*{{msg-mw|Seconds}}
*{{msg-mw|Minutes}}
*{{msg-mw|Hours}}
*{{msg-mw|Days}}',

# Bad image list
'bad_image_list' => 'This is only message appears to guide administrators to add links with right format. This will not appear anywhere else in Mediawiki.',

/*
Short names for language variants used for language conversion links.
To disable showing a particular link, set it to 'disable', e.g.
'variantname-zh-sg' => 'disable',
Variants for Chinese language
*/
'variantname-zh-hans' => '{{Optional}}

Variant option for wikis with variants conversion enabled.',
'variantname-zh-hant' => '{{Optional}}

Variant option for wikis with variants conversion enabled.',
'variantname-zh-cn'   => '{{Optional}}

Variant option for wikis with variants conversion enabled.',
'variantname-zh-tw'   => '{{Optional}}

Variant option for wikis with variants conversion enabled.',
'variantname-zh-hk'   => '{{Optional}}

Variant option for wikis with variants conversion enabled.',
'variantname-zh-mo'   => '{{Optional}}

Variant option for wikis with variants conversion enabled.',
'variantname-zh-sg'   => '{{Optional}}

Variant option for wikis with variants conversion enabled.',
'variantname-zh-my'   => '{{Optional}}

Variant option for wikis with variants conversion enabled.',
'variantname-zh'      => '{{Optional}}

Variant option for wikis with variants conversion enabled.',

# Variants for Gan language
'variantname-gan-hans' => '{{Optional}}

Variant option for wikis with variants conversion enabled.',
'variantname-gan-hant' => '{{Optional}}

Variant option for wikis with variants conversion enabled.',
'variantname-gan'      => '{{Optional}}

Variant option for wikis with variants conversion enabled.',

# Variants for Serbian language
'variantname-sr-ec' => '{{optional}}
Varient Option for wikis with variants conversion enabled.',
'variantname-sr-el' => '{{optional}}
Varient Option for wikis with variants conversion enabled.',
'variantname-sr'    => '{{optional}}
Varient Option for wikis with variants conversion enabled.',

# Variants for Kazakh language
'variantname-kk-kz'   => '{{optional}}
Varient Option for wikis with variants conversion enabled.',
'variantname-kk-tr'   => '{{optional}}
Varient Option for wikis with variants conversion enabled.',
'variantname-kk-cn'   => '{{optional}}
Varient Option for wikis with variants conversion enabled.',
'variantname-kk-cyrl' => '{{optional}}
Varient Option for wikis with variants conversion enabled.',
'variantname-kk-latn' => '{{optional}}
Varient Option for wikis with variants conversion enabled.',
'variantname-kk-arab' => '{{optional}}
Varient Option for wikis with variants conversion enabled.',
'variantname-kk'      => '{{optional}}
Varient Option for wikis with variants conversion enabled.',

# Variants for Kurdish language
'variantname-ku-arab' => '{{optional}}
Varient Option for wikis with variants conversion enabled.',
'variantname-ku-latn' => '{{optional}}
Varient Option for wikis with variants conversion enabled.',
'variantname-ku'      => '{{optional}}
Varient Option for wikis with variants conversion enabled.',

# Variants for Tajiki language
'variantname-tg-cyrl' => '{{optional}}',
'variantname-tg-latn' => '{{optional}}',
'variantname-tg'      => '{{optional}}',

# Variants for Inuktitut language
'variantname-ike-cans' => '{{optional}}',
'variantname-ike-latn' => '{{optional}}',
'variantname-iu'       => '{{optional}}',

# Variants for Tachelhit language
'variantname-shi-tfng' => '{{optional}}',
'variantname-shi-latn' => '{{optional}}',
'variantname-shi'      => '{{optional}}',

# Metadata
'metadata'                  => 'The title of a section on an image description page, with information and data about the image. For example of message in use see [http://commons.wikimedia.org/wiki/File:Titan-crystal_bar.JPG Commons].

{{Identical|Metadata}}',
'metadata-expand'           => 'On an image description page, there is mostly a table containing data (metadata) about the image. The most important data are shown, but if you click on this link, you can see more data and information. For the link to hide back the less important data, see "[[MediaWiki:Metadata-collapse/{{SUBPAGENAME}}|{{int:metadata-collapse}}]]".',
'metadata-collapse'         => 'On an image description page, there is mostly a table containing data (metadata) about the image. The most important data are shown, but if you click on the link "[[MediaWiki:Metadata-expand/{{SUBPAGENAME}}|{{int:metadata-expand}}]]", you can see more data and information. This message is for the link to hide back the less important data.',
'metadata-fields'           => "'''Warning:''' Do not translate list items, only translate the text! So leave \"<tt>* make</tt>\" and the other items exactly as they are.

The sentences are for explanation only and are not shown to the user.",
'metadata-langitem'         => '{{optional}}
This is used for constructing the list of translations when a metadata property is translated into multiple languages.

$1 is the value of the property (in one language), $2 is the language name that this translation is for (or language code if language name cannot be determined), $3 is the language code.',
'metadata-langitem-default' => '{{optional}}
Similar to "metadata-langitem" but for the case where a multilingual property has a default specified that does not specify what language the default is in. $1 is the value of the property.',

# EXIF tags
'exif-imagewidth'                  => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

{{Identical|Width}}',
'exif-imagelength'                 => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

{{Identical|Height}}',
'exif-bitspersample'               => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].',
'exif-compression'                 => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

This field labels what the compression of the image is. It is commonly seen in Tiff images. It uses messages like {{msg-mw|exif-compression-1}} for the value. http://en.wikipedia.org/wiki/TIFF#TIFF_Compression_Tag has information about this field.',
'exif-photometricinterpretation'   => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].',
'exif-orientation'                 => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

For specific information on the orientation tag, see http://sylvana.net/jpegcrop/exif_orientation.html',
'exif-samplesperpixel'             => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].',
'exif-planarconfiguration'         => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].',
'exif-ycbcrsubsampling'            => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].',
'exif-ycbcrpositioning'            => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].',
'exif-xresolution'                 => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

This is the horizontal resolution in either dots/inch or dots/cm.',
'exif-yresolution'                 => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

This is the vertical resolution in either dots/inch or dots/cm.',
'exif-stripoffsets'                => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].',
'exif-rowsperstrip'                => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].',
'exif-stripbytecounts'             => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].',
'exif-jpeginterchangeformat'       => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].',
'exif-jpeginterchangeformatlength' => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].',
'exif-whitepoint'                  => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].',
'exif-primarychromaticities'       => 'The chromaticity of the three primary colours of the image. Normally this tag is not necessary, since colour space is specified in the colour space information tag. This should probably be translated it as "Chromaticity of primary colours".

Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].',
'exif-ycbcrcoefficients'           => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].',
'exif-referenceblackwhite'         => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].',
'exif-datetime'                    => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

Note, this message is also used for the XMP:ModifyDate property in XMP metadata. See page 35 of http://www.adobe.com/content/dam/Adobe/en/devnet/xmp/pdfs/XMPSpecificationPart1.pdf

Datetime is the time that the digital file was last changed.',
'exif-imagedescription'            => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

This property is the description or caption of the image. It is used for the exif ImageDescription property, the dc:description property in XMP (see http://www.adobe.com/content/dam/Adobe/en/devnet/xmp/pdfs/XMPSpecificationPart1.pdf ), and the iptc-iim 2:120 caption/abstract property ( http://www.iptc.org/std/IIM/4.1/specification/IIMV4.1.pdf ).

When an image has multiple differing descriptions, mediawiki follows the MWG guidelines when deciding which to show (Which typically means Exif takes precedence).',
'exif-make'                        => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

The Manufacturer of the digital camera (or scanner) that took the photo.',
'exif-model'                       => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

The model of camera (or scanner) used to take the picture.',
'exif-software'                    => 'Short for "The software which was used to create or modify this image".

The property can come from the Exif Software tag, PNG software chunk, iptc-iim 2:65 Software field, or XMP\'s xmp:CreatorTool field.

Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].',
'exif-artist'                      => "Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

This message labels the author or artist of the work. Usually this means who took the photograph, or who drew the picture. The corresponding value field most commonly contains a single author, however it can contain an ordered (or unordered depending on which metadata standard is used to store the information) list of authors. Sometimes the persons position is prefixed before their name such as \"Photographer, John Smith\". The exif standard recommends multiple authors be specified by \"position, Author 1; position for author 2, Author 2's name\" however this doesn't seem to happen in practise very often. If multiple authors are specified using a non-exif standard, then a billeted (or numbered) list is used.

This property can be specified by exif Artist tag, XMP's tiff:Artist, XMP's dc:creator, iptc-iim's 2:80 byline, PNG's author textual chunk, PNG's (unofficial) artist textual chunk. XMP's photoshop:AuthorsPosition and iptc 2:85 byline-title can also affect display of this property.

{{Identical|Author}}",
'exif-copyright'                   => "Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

Label for information contained in exif Copyright tag, XMP dc:rights, IPTC-iim 2:116, or PNG copyright textual chunk.

Typically the copyright statement for the photograph/drawing/video (such as ''(c) 2010 John Smith. Released under GFDL''). Sometimes contains license information. See also {{msg-mw|exif-copyrightowner}}",
'exif-exifversion'                 => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

Version of exif standard photo uses. Typically this is 2.22',
'exif-flashpixversion'             => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

Version of flashpix used. Flashpix is a format used for storing some types of metadata in image. It is not as commonly used as EXIF, and mediawiki currently cannot read Flashpix data.',
'exif-colorspace'                  => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

The colorspace of the photo. This tells the computer how to make the colours in the photo be more true to the original photo. Typical values for this are sRGB or uncalibrated. This only gives information on colour information given in the exif-colorspace property. However, colour information is often stored elsewhere in the photo.',
'exif-componentsconfiguration'     => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

This contains how the information in the picture is stored. This is most commonly Y, Cr, Cb to specify luma, red, blue. RGB is also possible to specify Red, Green, Blue.',
'exif-compressedbitsperpixel'      => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].',
'exif-pixelydimension'             => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].',
'exif-pixelxdimension'             => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].',
'exif-usercomment'                 => "Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

Comments by user. Sometimes used like ImageDescription when the ImageDescription contained non-ascii characters. (Technically ImageDescription is supposed to contain ascii characters. In practise utf-8 is used in ImageDescription, so this field isn't used too much.)",
'exif-relatedsoundfile'            => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

Some cameras offer the option to record an audio "memo" for the photo they just took. If the user did that, the name of the file is labelled with this message.',
'exif-datetimeoriginal'            => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

The date and time when the original image data was generated. For example if it was a painting from 1773, scanned in to a computer in 2007, the datetimeoriginal would be 1773 and {{msg-mw|exif-datetimedigitized}} would have the 2007 date.',
'exif-datetimedigitized'           => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

The date and time when the image was stored as digital data.',
'exif-subsectime'                  => "Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

'DateTime subseconds' shows the detail of the fraction of a second (1/100s) at which the file was changed, when the tag {{msg-mw|Exif-datetime}} is recorded to the whole second.",
'exif-subsectimeoriginal'          => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

This tag shows the detail of the fraction of a second (1/100s) at which the file data was originally generated, when the tag {{msg-mw|Exif-datetimeoriginal}} is recorded to the whole second.',
'exif-subsectimedigitized'         => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

This tag shows the detail of the fraction of a second (1/100s) at which the file was stored as digital data, when the tag {{msg-mw|Exif-datetimedigitized}} is recorded to the whole second.',
'exif-exposuretime'                => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

The exposure time. Number of (or fraction of) seconds the film was exposed to light. The value for this property is formatted using {{msg-mw|exif-exposuretime-format}}',
'exif-exposuretime-format'         => "Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

*$1 is the exposure time written as a fraction of a second, for example 1/640 of a second.
*$2 is the exposure time written as a decimal, for example 0.0015625.
*'sec' is the abbreviation used in English for the unit of time 'second'.",
'exif-fnumber'                     => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

The [http://en.wikipedia.org/wiki/F_number F number] is the relative aperture of the camera.',
'exif-fnumber-format'              => "{{optional}}
Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

*$1 is a number
*f is the abbreviation used in English for 'f-number'.",
'exif-exposureprogram'             => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

How the camera figured out what exposure to use. (If it was manually set, if its optimizing for fast shutter speed, etc).',
'exif-spectralsensitivity'         => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

How sensitive each channel (colour) of the photo is to light. This tag is almost never used.',
'exif-isospeedratings'             => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

The iso speed of the film used in the camera. This is basically a measure of how sensitive the film in the camera is to light.',
'exif-shutterspeedvalue'           => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

[http://en.wikipedia.org/wiki/Shutter_speed Shutter speed] is the time that the camera shutter is open.

This is the shutter speed measured in APEX units (negative base 2 log of shutter speed in seconds). See {{msg-mw|exif-exposuretime}} for this property in more traditional units of seconds.',
'exif-aperturevalue'               => "Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

The [http://en.wikipedia.org/wiki/Aperture aperture] of a camera is the hole through which light shines. This message can be translated 'Aperture width'. Note, this is measured in APEX units which is 2*log<sub>2</sub>(f-number) . See {{msg-mw|exif-fnumber}} for this value in more traditional units.",
'exif-brightnessvalue'             => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

How intense the illumination of the scene photographed is. Measured in APEX brightness units. See Annex C of Exif standard for details on the measurement system in use.',
'exif-exposurebiasvalue'           => "Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

Another term for [http://en.wikipedia.org/wiki/Exposure_bias 'exposure bias'] is 'exposure compensation'.",
'exif-maxaperturevalue'            => "Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

The 'land' in a camera refers possibly to the inner surface of the barrel of the lens. An alternative phrasing for this message could perhaps be 'maximum width of the land aperture'.",
'exif-subjectdistance'             => "Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

The subject of a photograph is the person or thing on which the camera focuses. 'Subject distance' is the distance to the subject given in meters.",
'exif-meteringmode'                => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

See [http://en.wikipedia.org/wiki/Metering_mode Wikipedia article] on metering mode.',
'exif-lightsource'                 => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].',
'exif-flash'                       => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

See this [http://en.wikipedia.org/wiki/Flash_(photography) Wikipedia article] for an explanation of the term.

{{Identical|Flash}}',
'exif-focallength'                 => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

See this [http://en.wikipedia.org/wiki/Focal_length_(photography) Wikipedia article] for an explanation of the term.',
'exif-focallength-format'          => "{{optional}}
Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

*$1 is a number
*mm is the abbreviation used in English for the unit of measurement of length 'millimetre'.",
'exif-subjectarea'                 => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

This exif property contains the position of the main subject. The first two numbers is the position of the subject in the picture in pixels from the upper left corner. If a third number is specified, it is a circle centred at the first two numbers. If four numbers are specified, the first two are coordinates of the centre of the subject as before, the third is the width of the rectangle, and the fourth is the height of the rectangle. It is rare for a photo to use this tag.',
'exif-flashenergy'                 => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

How bright the flash is in beam candle power seconds.',
'exif-focalplanexresolution'       => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

Indicates the number of pixels in the image width (X) direction per FocalPlaneResolutionUnit on the camera focal plane.',
'exif-focalplaneyresolution'       => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].',
'exif-focalplaneresolutionunit'    => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].',
'exif-subjectlocation'             => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

Same as {{msg-mw|exif-subjectarea}} but only ever has two numbers as a value.',
'exif-exposureindex'               => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].',
'exif-sensingmethod'               => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].',
'exif-filesource'                  => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

Determines if the image was recorded by a digital camera adhering to DSC standard (which is almost all digital cameras).',
'exif-scenetype'                   => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

If the image is directly photographed (taken by a digital camera).',
'exif-customrendered'              => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

See also Wikipedia on [http://en.wikipedia.org/wiki/Image_processing image processing].',
'exif-exposuremode'                => "Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

See also Wikipedia on [http://en.wikipedia.org/wiki/Exposure_(photography) exposure in photography]. This tag shows if the photo's exposure was manually set or automatically determined.",
'exif-whitebalance'                => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

See also Wikipedia on [http://en.wikipedia.org/wiki/Color_balance color balance].',
'exif-digitalzoomratio'            => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

See also Wikipedia on [http://en.wikipedia.org/wiki/Digital_zoom digital zoom].',
'exif-focallengthin35mmfilm'       => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

See also Wikipedia on [http://en.wikipedia.org/wiki/Focal_length#In_photography focal length].',
'exif-imageuniqueid'               => 'A unique identifier for the image in the form of a 128-bit hexadecimal string. See http://www.exif.org/Exif2-2.PDF for details on exif properties.',
'exif-gpsversionid'                => 'Version of the GPS IFD used to store location information. This is usually 2.2.0.0',
'exif-gpslatituderef'              => 'In older versions of mediawiki this referred to if the latitude was North or South. This is no longer used in modern versions of mediawiki except for when using a foreign image repository that is using an older version of mediawiki since the information is now contained in {{msg-mw|exif-gpslatitude}}.',
'exif-gpslatitude'                 => 'The latitude of the location from where the picture was taken from.',
'exif-gpslongituderef'             => 'Same as {{msg-mw|exif-gpslatituderef}} but for longitude.',
'exif-gpslongitude'                => 'The longitude of the location from where the picture was taken from.

{{Identical|Longitude}}',
'exif-gpsaltituderef'              => 'No longer used except for when using foreign image repository with old version of mediawiki. 0 for above sea level, 1 for below sea level.',
'exif-gpsaltitude'                 => 'Altitude in meters that the image was taken at.',
'exif-gpstimestamp'                => 'Time (does not include date) that GPS measurement was taken, in UTC. Since often this is at the same time as photo was taken, this is sometimes more reliable than {{msg-mw|exif-datetimeoriginal}}.',
'exif-gpsmeasuremode'              => 'Is the measurement 2D (latitude and longitude) or 3D (latitude, longitude, and altitude).',
'exif-gpsdop'                      => 'How accurate the GPS information is. See http://en.wikipedia.org/wiki/Dilution_of_precision_(GPS)',
'exif-gpsdestlatitude'             => 'The latitude of the location shown in the picture, if it is different from latitude of the camera location. See {{msg-mw|exif-gpslatitude}}.

{{Identical|Latitude}}',
'exif-gpsdestlongitude'            => 'The longitude of the location shown in the picture, if it is different from longitude of the camera location. See {{msg-mw|exif-gpslongitude}}.

{{Identical|Longitude}}',
'exif-gpsdatestamp'                => 'Date (does not generally include time unless recorded in XMP) that GPS measurement was taken, in UTC. Since often this is at the same date as photo was taken, this is sometimes more reliable than {{msg-mw|exif-datetimeoriginal}}.',
'exif-coordinate-format'           => '{{optional}} For formatting GPS latitude coordinates. $1 is degrees, $2 is minutes, $3 is seconds (up to two decimal places), $4 is direction (N, S, W, or E), $5 is coordinate as a single positive or negative real number.',
'exif-jpegfilecomment'             => 'This is not a true exif tag, but the contents of the JPEG COM segment. This often contains a file source, but can potentially contain any comment about the file. This is similar to {{msg-mw|exif-usercomment}}, {{msg-mw|exif-pngfilecomment}}, and {{msg-mw|exif-giffilecomment}}.',
'exif-keywords'                    => "List of keywords for the photograph (or other media).

This can come from IPTC-iim 2:25 keyword field, or XMP's dc:subject field.",
'exif-worldregioncreated'          => "The world region (generally that means continent, but could also include 'World' as a whole) where the media was created.",
'exif-countrycreated'              => 'Country that the picture was taken in. Note this is where it was taken, not what country is depicted in the picture.',
'exif-countrycodecreated'          => 'ISO Code for the country that the picture was taken in. Note this is where it was taken, not what country is depicted in the picture.',
'exif-provinceorstatecreated'      => 'Province, state, territory, or other secondary political division (bigger than a city, smaller then a country) where that the picture was taken in.  Note this is where it was taken, not what province/state is depicted in the picture.',
'exif-citycreated'                 => 'City that the picture was taken in.  Note this is where it was taken, not what city is depicted in the picture. This is generally only used if different from the city depicted in photo.',
'exif-sublocationcreated'          => 'Sub-location of the city that the picture was taken in. This might be a street, a part of town, etc.  Note this is where it was taken, not what sub-location is depicted in the picture.',
'exif-worldregiondest'             => 'World region shown. This generally means the continent, but could have the value of world as well.',
'exif-countrydest'                 => 'Country shown. See also {{msg-mw|exif-countrycreated}}.',
'exif-countrycodedest'             => 'ISO Code for country shown',
'exif-provinceorstatedest'         => 'Province, state, territory, or other secondary political division shown.',
'exif-citydest'                    => 'City shown',
'exif-sublocationdest'             => 'Sub-location of city shown. This could be an address, a street, an area of town, etc.',
'exif-objectname'                  => "This is a short name for the image or other media. (As compared to {{msg-mw|exif-imagedescription}} which is a long description of the image). This is sometimes an id number used to identify the photo, or a (short) title of the photo.

This property is extracted based on XMP's dc:title property ( http://www.adobe.com/content/dam/Adobe/en/devnet/xmp/pdfs/XMPSpecificationPart1.pdf ), PNG's title keyword ( http://www.w3.org/TR/PNG/#11keywords ), or IPTC-iim 2:05 Object name property ( http://www.iptc.org/std/IIM/4.1/specification/IIMV4.1.pdf ).",
'exif-specialinstructions'         => 'Special instructions for how to use the image/media. This might include embargo notices, or other warnings.

This is IPTC-iim property 2:40. See http://www.iptc.org/std/IIM/4.1/specification/IIMV4.1.pdf for details.',
'exif-headline'                    => 'A short version of the image caption. The IPTC4XMP standard is clear that "this is not the same thing as title [ {{msg-mw|exif-objectname}} ]".

This is extracted from XMP\'s photoshop:headline ( http://www.iptc.org/std/photometadata/specification/IPTC-PhotoMetadata-201007_1.pdf ) and IPTC-iim: 2:105 Headline tag ( http://www.iptc.org/std/IIM/4.1/specification/IIMV4.1.pdf ).',
'exif-credit'                      => 'Provider/credit.

Who gave us the image. This might be different from the creator of the image. This is IPTC-iim property 2:110',
'exif-source'                      => 'See IPTC-iim standard 2:115 - http://www.iptc.org/std/IIM/4.1/specification/IIMV4.1.pdf.

This is who originally owned the image (a person, stock photo agency, etc). This does not refer to the image this image is based on.
{{Identical|Source}}',
'exif-editstatus'                  => 'Editorial status of image. This is more intended for use with people making news papers. This denotes whether the image is on the main page, is part of a correction, etc. See 2:07 of http://www.iptc.org/std/IIM/4.1/specification/IIMV4.1.pdf',
'exif-urgency'                     => 'Urgency. How urgent this image is. 1 is very urgent, 5 is normal, 8 is  very low priority.',
'exif-fixtureidentifier'           => 'Fixture name. Identifies frequently occuring object data, for example a regular column in a news paper.',
'exif-locationdest'                => 'Full printable name of location.',
'exif-locationdestcode'            => 'Code of location depicted. Typically this is an ISO country code, but the IPTC-iim standard also defines other codes like XSP for outer space. See appendix D (and tag 2:100) of http://www.iptc.org/std/IIM/4.1/specification/IIMV4.1.pdf',
'exif-objectcycle'                 => 'Time of day that media is intended for. Either morning only, evening only, or all day. Typically only used for news related things that might only be broadcast at a specific time of day. See {{msg-mw|exif-objectcycle-a}}, {{msg-mw|exif-objectcycle-p}} and {{msg-mw|exif-objectcycle-b}} for the values that this message labels.',
'exif-contact'                     => 'Contact information of the person responsible for the image.',
'exif-writer'                      => 'The person who wrote the caption of the image. See Description Writer on page 18 of http://www.iptc.org/std/photometadata/specification/IPTC-PhotoMetadata-201007_1.pdf',
'exif-languagecode'                => "Language of image/media.

This is taken from IPTC-iim 2:135 and XMP's dc:language.
{{Identical|Language}}",
'exif-iimversion'                  => 'IIM version number. Version of information interchange 2:xx records. 4 is current version. 2 is often seen as well. This is the value stored 2:00 field (Note, iptc-iim also stores a model version in 1:00. This version field displays the 2:00 record only)',
'exif-iimcategory'                 => 'Primary Category of image (or other media). Technically supposed to be limited to 3 characters, however that is not always followed. Some common 3 letter category abbreviations are expanded by mediawiki. Similar to {{msg-mw|exif-keywords}}.
{{identical|Category}}',
'exif-iimsupplementalcategory'     => 'Supplemental categories. Like {{msg-mw|exif-iimcategory}} but for categories beyond the main one.',
'exif-datetimeexpires'             => 'Date after which not to use the image (media). This is often used in news situations were certain things (like forecasts) should not be used after a specified date.',
'exif-datetimereleased'            => 'Earliest date the image (media) can be used. See 2:30 of http://www.iptc.org/std/IIM/4.1/specification/IIMV4.1.pdf',
'exif-originaltransmissionref'     => 'This is basically a job id. This could help an individual keep track of for what reason the image was created. See Job Id on page 19 of http://www.iptc.org/std/photometadata/specification/IPTC-PhotoMetadata-201007_1.pdf',
'exif-identifier'                  => 'A formal identifier for the image. Often this is a URL.',
'exif-lens'                        => 'Description of lens used. This is taken from aux:Lens XMP property. See http://www.adobe.com/content/dam/Adobe/en/devnet/xmp/pdfs/XMPSpecificationPart2.pdf',
'exif-serialnumber'                => 'Serial number of camera. See aux:SerialNumber in http://www.adobe.com/content/dam/Adobe/en/devnet/xmp/pdfs/XMPSpecificationPart2.pdf',
'exif-cameraownername'             => 'Who owns the camera.',
'exif-label'                       => 'Label given to the image for organizational purposes. This is very similar to {{msg-mw|exif-keywords}}. Label is more used by a person to organize their media, where keywords are used to describe the photo contents itself.

This property can come from xmp:Label in XMP ( http://www.adobe.com/content/dam/Adobe/en/devnet/xmp/pdfs/XMPSpecificationPart1.pdf ) or the label textual chunk in PNG.
{{Identical|Label}}',
'exif-datetimemetadata'            => 'Date metadata was last modified. Typically this refers to XMP metadata.',
'exif-nickname'                    => 'Short informal name of image. See http://www.adobe.com/content/dam/Adobe/en/devnet/xmp/pdfs/XMPSpecificationPart2.pdf',
'exif-rating'                      => 'This is a rating for how good the image is. The range is between 1 to 5 (5 highest), with an additional option of "reject".',
'exif-rightscertificate'           => 'URL of Rights management certificate. This comes from XMPRights:Certificate property. See http://www.adobe.com/content/dam/Adobe/en/devnet/xmp/pdfs/XMPSpecificationPart1.pdf',
'exif-copyrighted'                 => 'Copyright status. This is a true or false field showing either Copyrighted or Public Domain. It should be noted that Copyrighted includes freely-licensed works.',
'exif-copyrightowner'              => 'Copyright owner. Can have more than one person or entity.',
'exif-usageterms'                  => "Terms under which you're allowed to use the image/media.",
'exif-webstatement'                => "URL detailing the copyright status of the image, and how you're allowed to use the image. Often this is a link to a creative commons license, however the creative commons people recommend using a page that generally contains specific information about the image, and recommend using {{msg-mw|exif-licenseurl}} for linking to the license. See http://wiki.creativecommons.org/XMP",
'exif-originaldocumentid'          => 'A unique id of the original document (image) that this document (image) is based on.',
'exif-licenseurl'                  => 'URL for copyright license. This is almost always a creative commons license since this information comes from the creative commons namespace of XMP (but could be a link to any type of license). See also {{msg-mw|exif-webstatement}}',
'exif-morepermissionsurl'          => 'A url where you can "buy" (or otherwise negotiate) to get more rights for the image.',
'exif-attributionurl'              => "A url that you're supposed to use when re-using the image.",
'exif-preferredattributionname'    => 'The preferred name to give credit to when re-using this image.',
'exif-disclaimer'                  => 'Disclaimer for the image.',
'exif-contentwarning'              => 'Content warning for the image. For example if the image/media contains violent, sexual or otherwise offensive content.

This comes from the png warning textual chunk. See http://www.w3.org/TR/PNG/#11keywords',
'exif-intellectualgenre'           => 'The "intellectual genre" of the image/media item. This typically means the type of item it is, ignoring the actual content of the item. See http://cv.iptc.org/newscodes/genre/ for some examples of the types of values this field might have.',
'exif-subjectnewscode'             => 'A (or multiple) codes describing the content of the image/media. The code is an 8 digit number representing some sort of category. The code is hierarchical , with the first two digits being a broad category (this broad category is shown to the user. See {{msg-mw|exif-subjectnewscode-value}} for how the value this field labels is shown to user). See http://cv.iptc.org/newscodes/subjectcode for the full list of codes.',
'exif-scenecode'                   => 'IPTC (numeric) scene code. Contains information on what type of scene it is (like panoramic scene, close-up, etc). See http://cv.iptc.org/newscodes/scene/',
'exif-event'                       => 'The event depicted',
'exif-organisationinimage'         => 'Name of organisations shown in image',
'exif-personinimage'               => 'Name of person depicted in image',
'exif-originalimageheight'         => 'Height of image before it was cropped in pixels',
'exif-originalimagewidth'          => 'Width of image before it was cropped in pixels',

# Make & model, can be wikified in order to link to the camera and model name
'exif-contact-value'         => '{{optional}}
*$1 is email
*$2 is URL of website
*$3 is street address.
*$4 is city
*$5 is region
*$6 is postal code
*$7 is country
*$8 is telephone number
Note, not all fields are guaranteed to be present, some may be empty strings.',
'exif-subjectnewscode-value' => '{{Optional}}

*$1 is numeric IPTC subject news code (one of http://cv.iptc.org/newscodes/subjectcode )
*$2 is one of 17 broad categories that the code falls into. For example any code starting with 15 has the contents of {{msg-mw|exif-iimcategory-spo}} for $2.',

# EXIF attributes
'exif-compression-5'     => '{{optional}}
Lempel-Ziv & Welch algorithm',
'exif-compression-6'     => '{{optional}}',
'exif-compression-7'     => '{{optional}}',
'exif-compression-8'     => '{{optional}}',
'exif-compression-32773' => '{{optional}}',
'exif-compression-32946' => '{{optional}}',
'exif-compression-34712' => '{{optional}}',

'exif-copyrighted-true'  => 'The image is under copyright (including if its copyrighted but freely licensed)',
'exif-copyrighted-false' => 'The image is Public domain',

'exif-photometricinterpretation-2' => '{{optional}}',
'exif-photometricinterpretation-6' => '{{optional}}',

'exif-orientation-1' => '{{Identical|Normal}}
0th row: top; 0th column: left',
'exif-orientation-2' => '0th row: top; 0th column: right',
'exif-orientation-3' => '0th row: bottom; 0th column: right',
'exif-orientation-4' => '0th row: bottom; 0th column: left',
'exif-orientation-5' => '0th row: left; 0th column: top

CCW is an abbreviation for counter-clockwise',
'exif-orientation-6' => '0th row: right; 0th column: top

CCW is an abbreviation for counter-clockwise.',
'exif-orientation-7' => '0th row: right; 0th column: bottom

CW is an abbreviation for clockwise',
'exif-orientation-8' => '0th row: left; 0th column: bottom

CW is an abbreviation for clockwise.',

'exif-xyresolution-i' => '{{Optional}} Used to format {{msg-mw|exif-xresolution}} and {{msg-mw|exif-yresolution}} if the unit is dots per inch. $1 is the number of dots/in.',
'exif-xyresolution-c' => '{{Optional}} Used to format {{msg-mw|exif-xresolution}} and {{msg-mw|exif-yresolution}} if the unit is dots per centimetre. $1 is the number of dots/cm.',

'exif-colorspace-1'     => '{{Optional}} If it uses the standard sRGB colour space.',
'exif-colorspace-65535' => 'The photograph is not colour calibrated.',

'exif-componentsconfiguration-1' => '{{optional}}',
'exif-componentsconfiguration-2' => '{{optional}}',
'exif-componentsconfiguration-3' => '{{optional}}',
'exif-componentsconfiguration-4' => '{{optional}}',
'exif-componentsconfiguration-5' => '{{optional}}',
'exif-componentsconfiguration-6' => '{{optional}}',

'exif-exposureprogram-1' => "One of the exposure program types in the table of metadata on image description pages. See the Wikipedia article '[http://en.wikipedia.org/wiki/Mode_dial Mode dial]' for an explanation.",
'exif-exposureprogram-3' => 'One of the exposure program types in the table of metadata on image description pages. See the Wikipedia article for a definition of the term [http://en.wikipedia.org/wiki/Aperture_priority aperture priority].',
'exif-exposureprogram-4' => 'One of the exposure program types in the table of metadata on image description pages. See the Wikipedia article for a definition of the term [http://en.wikipedia.org/wiki/Shutter_priority shutter priority].',
'exif-exposureprogram-5' => "One of the exposure program types in the table of metadata on image description pages. See the Wikipedia article '[http://en.wikipedia.org/wiki/Mode_dial Mode dial]' for an explanation.",
'exif-exposureprogram-6' => "One of the exposure program types in the table of metadata on image description pages. See the Wikipedia article '[http://en.wikipedia.org/wiki/Mode_dial Mode dial]' for an explanation.",
'exif-exposureprogram-7' => "One of the exposure program types in the table of metadata on image description pages. See the Wikipedia article '[http://en.wikipedia.org/wiki/Mode_dial Mode dial]' for an explanation.",
'exif-exposureprogram-8' => "One of the exposure program types in the table of metadata on image description pages. See the Wikipedia article '[http://en.wikipedia.org/wiki/Mode_dial Mode dial]' for an explanation.",

'exif-subjectdistance-value' => '$1 is a distance measured in metres. The value can, and usually does, include decimal places.',

'exif-meteringmode-0'   => '{{Identical|Unknown}}',
'exif-meteringmode-1'   => '{{Identical|Average}}',
'exif-meteringmode-255' => '{{Identical|Other}}',

'exif-lightsource-0'  => '{{Identical|Unknown}}',
'exif-lightsource-4'  => '{{Identical|Flash}}',
'exif-lightsource-20' => '{{optional}}',
'exif-lightsource-21' => '{{optional}}',
'exif-lightsource-22' => '{{optional}}',
'exif-lightsource-23' => '{{optional}}',

# Flash modes
'exif-flash-return-0'   => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

"Strobe" and "flash" mean the same here.',
'exif-flash-return-2'   => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

"Strobe" and "flash" mean the same here.',
'exif-flash-return-3'   => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].

"Strobe" and "flash" mean the same here.',
'exif-flash-mode-1'     => 'This is when you have chosen that your camera must use a flash for this picture.',
'exif-flash-mode-2'     => "This is when you have chosen that your camera must ''not'' use a flash for this picture.",
'exif-flash-function-1' => 'Exif is a format for storing metadata in image files. See this [http://en.wikipedia.org/wiki/Exchangeable_image_file_format Wikipedia article] and the example at the bottom of [http://commons.wikimedia.org/wiki/File:Phalacrocorax-auritus-020.jpg this page on Commons]. The tags are explained [http://www.awaresystems.be/imaging/tiff/tifftags/privateifd/exif.html briefly] and [http://www.kodak.com/global/plugins/acrobat/en/service/digCam/exifStandard2.pdf in further detail].',

'exif-sensingmethod-5' => "''Color sequential'' means, that the three base colors are measured one after another (i.e. the sensor is first measuring red, than green, than blue).",
'exif-sensingmethod-8' => "''Color sequential'' means, that the three base colors are measured one after another (i.e. the sensor is first measuring red, than green, than blue).",

'exif-filesource-3' => '{{optional}}',

'exif-exposuremode-2' => "A type of exposure mode shown as part of the metadata on image description pages. The Wikipedia article on [http://en.wikipedia.org/wiki/Bracketing#Exposure_bracketing bracketing] says that 'auto bracket' is a camera exposure setting which automatically takes a series of pictures at slightly different light exposures.",

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
* {{msg|Exif-subjectdistancerange-3}}

Macro view is close-up photography. See [http://en.wikipedia.org/wiki/Macro_photography Wikipedia].',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Very rarely used. Only used when using an old version of Mediawiki as a foreign image repo.',
'exif-gpslatitude-s' => 'Very rarely used. Only used when using an old version of Mediawiki as a foreign image repo.',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Very rarely used. Only used when using an old version of Mediawiki as a foreign image repo.',
'exif-gpslongitude-w' => 'Very rarely used. Only used when using an old version of Mediawiki as a foreign image repo.',

'exif-gpsmeasuremode-2' => 'Only latitude and longitude recorded, no altitude.',
'exif-gpsmeasuremode-3' => 'Latitude, longitude, and altitude recorded.',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-n' => "Knots: ''Knot'' is a unit of speed on water used for ships, etc., equal to one nautical mile per hour.",

'exif-gpsdop-excellent' => '$1 is the actual HDOP/PDOP value (less than or equal to 2 for excellent). See http://en.wikipedia.org/wiki/Dilution_of_precision_(GPS)',
'exif-gpsdop-good'      => '$1 is the actual HDOP/PDOP value (2-5 for good). See http://en.wikipedia.org/wiki/Dilution_of_precision_(GPS)',
'exif-gpsdop-moderate'  => '$1 is the actual HDOP/PDOP value (5-10 for moderate). See http://en.wikipedia.org/wiki/Dilution_of_precision_(GPS)',
'exif-gpsdop-fair'      => '$1 is the actual HDOP/PDOP value (10-20 for fair). See http://en.wikipedia.org/wiki/Dilution_of_precision_(GPS)',
'exif-gpsdop-poor'      => '$1 is the actual HDOP/PDOP value (greater than 20 for poor). See http://en.wikipedia.org/wiki/Dilution_of_precision_(GPS)',

'exif-objectcycle-a' => 'Morning only (a is for AM). See also {{msg-mw|Exif-objectcycle}}.',
'exif-objectcycle-p' => 'Evening only (p is for PM). See also {{msg-mw|Exif-objectcycle}}.',
'exif-objectcycle-b' => 'Both morning and evening (b is for both). See also {{msg-mw|Exif-objectcycle}}.',

'exif-ycbcrpositioning-1' => 'If the Chrominance samples are centered with respect to the Luminance samples.',
'exif-ycbcrpositioning-2' => 'If the Chrominance samples are on top of to the Luminance samples.
See: http://www.awaresystems.be/imaging/tiff/tifftags/ycbcrpositioning.html',

'exif-dc-contributor' => 'People who helped make the resource, but are secondary in contribution to the author.',
'exif-dc-coverage'    => '"The extent or scope of the resource" see dc:coverage in http://www.adobe.com/content/dam/Adobe/en/devnet/xmp/pdfs/XMPSpecificationPart2.pdf',
'exif-dc-date'        => 'One or more dates associated with the image. How they are associated is not really defined. From the dc:date XMP property.',
'exif-dc-publisher'   => 'One or more publisher of resource',
'exif-dc-relation'    => "Something related to this image. Often a list of url's to related images.",
'exif-dc-rights'      => 'Copyright information about the image/media given in informal language.',
'exif-dc-source'      => 'Source of the image. This is another image that this image is based on. This does not refer to the person who provided the image.',
'exif-dc-type'        => 'Type or genre of image/media. This might be something like painting or photograph.',

'exif-rating-rejected' => 'If the rating field has a rating of -1 to mean that the file was totally "rejected"',

'exif-isospeedratings-overflow' => "Exif can't store iso speed ratings beyond 65535. This message is shown if the iso speed is too big to be stored.",

'exif-maxaperturevalue-value' => '{{Optional}}
$1 is maxaperture in APEX units (APEX aperture units = 2log<sub>2</sub>(f-number) ). $2 is the value in the more traditional f-number units.',

'exif-iimcategory-ace' => 'Displayed as part of the iimcategory field if the 3 letter code is recognized, or as part {{msg-mw|exif-subjectnewscode-value}}',
'exif-iimcategory-clj' => 'Displayed as part of the iimcategory field if the 3 letter code is recognized, or as part {{msg-mw|exif-subjectnewscode-value}}',
'exif-iimcategory-dis' => 'Displayed as part of the iimcategory field if the 3 letter code is recognized, or as part {{msg-mw|exif-subjectnewscode-value}}',
'exif-iimcategory-fin' => 'Displayed as part of the iimcategory field if the 3 letter code is recognized, or as part {{msg-mw|exif-subjectnewscode-value}}',
'exif-iimcategory-edu' => 'Displayed as part of the iimcategory field if the 3 letter code is recognized, or as part {{msg-mw|exif-subjectnewscode-value}}',
'exif-iimcategory-evn' => 'Displayed as part of the iimcategory field if the 3 letter code is recognized, or as part {{msg-mw|exif-subjectnewscode-value}}',
'exif-iimcategory-hth' => 'Displayed as part of the iimcategory field if the 3 letter code is recognized, or as part {{msg-mw|exif-subjectnewscode-value}}',
'exif-iimcategory-hum' => 'Displayed as part of the iimcategory field if the 3 letter code is recognized, or as part {{msg-mw|exif-subjectnewscode-value}}',
'exif-iimcategory-lab' => 'Displayed as part of the iimcategory field if the 3 letter code is recognized, or as part {{msg-mw|exif-subjectnewscode-value}}

"Labour" here refers to all news on labour issues; employment; unemployment; work relations; labour disputes; strikes; legislation; unions; job related issues; government policy. (at least, according to Reuters.)',
'exif-iimcategory-lif' => 'Displayed as part of the iimcategory field if the 3 letter code is recognized, or as part {{msg-mw|exif-subjectnewscode-value}}',
'exif-iimcategory-pol' => 'Displayed as part of the iimcategory field if the 3 letter code is recognized, or as part {{msg-mw|exif-subjectnewscode-value}}',
'exif-iimcategory-rel' => 'Displayed as part of the iimcategory field if the 3 letter code is recognized, or as part {{msg-mw|exif-subjectnewscode-value}}',
'exif-iimcategory-sci' => 'Displayed as part of the iimcategory field if the 3 letter code is recognized, or as part {{msg-mw|exif-subjectnewscode-value}}',
'exif-iimcategory-soi' => 'Displayed as part of the iimcategory field if the 3 letter code is recognized, or as part {{msg-mw|exif-subjectnewscode-value}}',
'exif-iimcategory-spo' => 'Displayed as part of the iimcategory field if the 3 letter code is recognized, or as part {{msg-mw|exif-subjectnewscode-value}}',
'exif-iimcategory-war' => 'Displayed as part of the iimcategory field if the 3 letter code is recognized, or as part {{msg-mw|exif-subjectnewscode-value}}',
'exif-iimcategory-wea' => 'Displayed as part of the iimcategory field if the 3 letter code is recognized, or as part {{msg-mw|exif-subjectnewscode-value}}',

'exif-urgency-normal' => '$1 is numeric priority (aka 5 for normal)',
'exif-urgency-low'    => '$1 is numeric priority (6-8 for low)',
'exif-urgency-high'   => '$1 is numeric priority (1-4 for high)',
'exif-urgency-other'  => '$1 is numeric priority. Most specs define 0 and 9 to either be reserved or not allowed. However the exiftool documentation defines 0 to be reserved and 9 to be user-defined priority.',

# External editor support
'edit-externally'      => 'Displayed on image description pages. See for example [[:Image:Yes.png#filehistory]].',
'edit-externally-help' => 'Displayed on image description pages. See for example [[:Image:Yes.png#filehistory]].

Please leave the link http://www.mediawiki.org/wiki/Manual:External_editors exactly as it is.',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'Appears on [[Special:Watchlist]]. It is variable $3 in the text message [[Mediawiki:Wlshowlast]].

{{Identical|All}}',
'namespacesall' => 'In special page [[Special:WhatLinksHere]]. Drop-down box option for namespace.

{{Identical|All}}',
'monthsall'     => 'Used in a drop-down box on [[Special:Contributions]] as an option for "all months". See also [[MediaWiki:Month/{{SUBPAGENAME}}]].

{{Identical|All}}',
'limitall'      => 'Used on [[Special:AllMessages]] (and potentially other TablePager based tables) to display "all" the messages.

{{Identical|All}}',

# E-mail address confirmation
'confirmemail'              => 'Title of [[Special:ConfirmEmail]] page.',
'confirmemail_noemail'      => 'Failure message on [[Special:ConfirmEmail]].',
'confirmemail_text'         => 'Explanation on [[Special:ConfirmEmail]]',
'confirmemail_pending'      => 'Message on [[Special:ConfirmEmail]] when confirmation of e-mail not yet complete.',
'confirmemail_send'         => 'Button label on [[Special:ConfirmEmail]]',
'confirmemail_sent'         => 'Message appearing after button {{msg-mw|Confirmemail_send}} activated on [[Special:ConfirmEmail]].',
'confirmemail_sendfailed'   => 'Failure message to do with [[Special:ConfirmEmail]].

$1 is a message from the e-mail delivery system.',
'confirmemail_needlogin'    => 'Used on [[Special:ConfirmEmail]] when you are logged out.
* $1 is a link to [[Special:UserLogin]] with {{msg-mw|loginreqlink}} as link description',
'confirmemail_loggedin'     => 'Explanation on [[Special:ConfirmEmail]] on completion of confirmation.',
'confirmemail_subject'      => 'Text in subject box of e-mail sent to user during e-mail address confirmation
{{Identical|SITENAME e-mail address confirmation}}',
'confirmemail_body'         => 'This message is sent as an e-mail to users when they add or change their e-mail adress in [[Special:Preferences]].
*$1 is the IP address of the user that changed the e-mail address
*$2 is the name of the user
*$3 is a URL to [[Special:ConfirmEmail]]
*$4 is a time and date (duplicated by $6 and $7)
*$5 is a URL to [[Special:InvalidateEmail]]
*$6 is a date
*$7 is a time',
'confirmemail_body_changed' => 'This message is sent as an e-mail to users when they add or change their e-mail adress in [[Special:Preferences]].

See also [[MediaWiki:Confirmemail_body_set]].

*$1 is the IP address of the user that changed the e-mail address
*$2 is the name of the user
*$3 is a URL to [[Special:ConfirmEmail]]
*$4 is a time and date (duplicated by $6 and $7)
*$5 is a URL to [[Special:InvalidateEmail]]
*$6 is a date
*$7 is a time',
'confirmemail_body_set'     => 'This is used in a confirmation e-mail sent when a contact e-mail address is set.

See also [[MediaWiki:Confirmemail_body_changed]].

*$1 is the IP address of the user that set the e-mail address
*$2 is the name of the user
*$3 is a URL to [[Special:ConfirmEmail]]
*$4 is a time and date (duplicated by $6 and $7)
*$5 is a URL to [[Special:InvalidateEmail]]
*$6 is a date
*$7 is a time',
'confirmemail_invalidated'  => 'This is the text of the special page [[Special:InvalidateEmail|InvalidateEmail]] (with the title in {{msg-mw|Invalidateemail}}) where user goes if he chooses the cancel e-mail confirmation link from the confirmation e-mail.',
'invalidateemail'           => "This is the '''name of the special page''' where user goes if he chooses the cancel e-mail confirmation link from the confirmation e-mail.",

# Trackbacks
'trackbackbox'     => '* $1 is the content of [[MediaWiki:Trackbackexcerpt]] or [[MediaWiki:Trackback]], depending if the trackback has an excerpt

{{doc-important|Do not remove the linebreak. $1 has to be the first character on a new line because it contains wiki markup}}

For information on trackback see [http://www.mediawiki.org/wiki/Manual:$wgUseTrackbacks mediawiki manual].',
'trackback'        => '{{optional}}

Do \'\'not\'\' change the leading ;  and the first : as it is wiki markup.

* $1: title of the trackback
* $2: URL of the trackback
* <span style="color:grey;">$3: unused in this message, see [[MediaWiki:trackbackexcerpt]] instead</span>
* $4: name of the trackback
* $5: a link to delete the trackback. The content of [[MediaWiki:Trackbackremove]] is injected here.',
'trackbackexcerpt' => "{{optional}}

Do ''not'' change the leading ;  and the first : as it is wiki markup.

* $1: title of the trackback
* $2: URL of the trackback
* $3: an excerpt of the trackback
* $4: name of the trackback
* $5: a link to delete the trackback. The content of [[MediaWiki:Trackbackremove]] is injected here.",

'unit-pixel' => '{{optional}}',

# action=purge
'confirm_purge_button' => '{{Identical|OK}}',

# action=watch/unwatch
'confirm-watch-button'   => '{{Identical|OK}}',
'confirm-unwatch-button' => '{{Identical|OK}}',

# Separators for various lists, etc.
'semicolon-separator' => '{{optional}}',
'comma-separator'     => '{{optional}}',
'colon-separator'     => "{{optional}}
Change it only if your language uses another character for ':' or it needs an extra space before the colon.",
'autocomment-prefix'  => '{{optional}}',
'pipe-separator'      => '{{optional}}',
'word-separator'      => '{{optional}}
This is a string which is (usually) put between words of the language. It is used, e.g. when messages are concatenated (appended to each other). Note that you must express a space as html entity &amp;#32; because the editing and updating process strips leading and trailing spaces from messages.

Most languages use a space, but some Asian languages, such as Thai and Chinese, do not.',
'percent'             => '{{optional}}',
'parentheses'         => '{{optional}}',

# Multipage image navigation
'imgmultipageprev' => '{{Identical|Previous page}}',
'imgmultipagenext' => '{{Identical|Next page}}',
'imgmultigo'       => '{{Identical|Go}}',

# Table pager
'ascending_abbrev'         => 'Abbreviation of Ascending power',
'table_pager_next'         => '{{Identical|Next page}}',
'table_pager_prev'         => '{{Identical|Previous page}}',
'table_pager_limit'        => "Do not use PLURAL in this message, because ''$1'' is not the actual number. ''$1'' is a limit selector drop-down list.",
'table_pager_limit_submit' => '{{Identical|Go}}',
'table_pager_empty'        => 'Used in a table pager when there are no results (e.g. when there are no images in the table on [[Special:ImageList]]).',

# Auto-summaries
'autosumm-blank'   => 'The auto summary when blanking the whole page. This is not the same as deleting the page.',
'autosumm-replace' => 'The auto summary when a user removes a lot of characters in the page.',
'autoredircomment' => 'The auto summary when making a redirect. $1 is the page where it redirects to. $2 is the first X number of characters of the redirect ($2 is usually only used when end users customize the message)',
'autosumm-new'     => 'The auto summary when creating a new page. $1 are the first X number of characters of the new page.',

# Size units
'size-bytes'     => '{{optional}}
Size (of a page, typically) in bytes.',
'size-kilobytes' => '{{optional}}
Size (of a page, typically) in kibibytes (1 kibibyte = 1024 bytes).',
'size-megabytes' => '{{optional}}
Size (of a file, typically) in mebibytes (1 mebibyte = 1024×1024 bytes).',
'size-gigabytes' => '{{optional}}
Size (of a file, typically) in gibibytes (1 gibibyte = 1024×1024×1024 bytes).',

# Live preview
'livepreview-loading' => '{{Identical|Loading}}',

# Watchlist editor
'watchlistedit-numitems'       => 'Message on Special page: Edit watchlist. This is paired with the message [[Mediawiki:Watchlistedit-noitems]] which appears instead of Watchlistedit-numitems when $1 is 0.',
'watchlistedit-noitems'        => "Message on [[Special:Watchlist/edit]], which only appears when a user's watchlist is empty.",
'watchlistedit-normal-title'   => 'Title of [[Special:Watchlist/edit|special page]].',
'watchlistedit-normal-legend'  => 'Heading of dialogue box on [[Special:Watchlist/edit]]',
'watchlistedit-normal-explain' => 'An introduction/explanation about the [[Special:Watchlist/edit|normal edit watchlist function]].
Hint: the text "Remove Titles" is in {{msg-mw|watchlistedit-normal-submit}}',
'watchlistedit-normal-submit'  => 'Text of submit button on [[Special:Watchlist/edit]].',
'watchlistedit-normal-done'    => 'Message on Special page: Edit watchlist after pages are removed from the watchlist.',
'watchlistedit-raw-title'      => 'Title of [[Special:Watchlist/raw|Special page]].

{{Identical|Edit raw watchlist}}',
'watchlistedit-raw-legend'     => 'Heading of dialogue box on [[Special:Watchlist/raw]].

{{Identical|Edit raw watchlist}}',
'watchlistedit-raw-explain'    => 'An introduction/explanation about the [[Special:Watchlist/raw|raw edit watchlist function]].',
'watchlistedit-raw-titles'     => 'Text above edit box containing items being watched on [[Special:Watchlist/raw]].',
'watchlistedit-raw-submit'     => 'Text of submit button on [[Special:Watchlist/raw]].',
'watchlistedit-raw-done'       => 'A message which appears after the raw watchlist has been updated using [[Special:Watchlist/raw]].',
'watchlistedit-raw-added'      => 'Message on special page: Edit raw watchlist. The message appears after at least 1 message is added to the raw watchlist.',
'watchlistedit-raw-removed'    => 'Message on special page: Edit raw watchlist. The message appears after at least 1 message is deleted from the raw watchlist.',

# Watchlist editing tools
'watchlisttools-view' => '[[Special:Watchlist]]: Navigation link under the title. See also {{msg|watchlisttools-edit}} and {{msg|watchlisttools-raw}}.',
'watchlisttools-edit' => '[[Special:Watchlist]]: Navigation link under the title. See also {{msg|watchlisttools-view}} and {{msg|watchlisttools-raw}}.',
'watchlisttools-raw'  => '[[Special:Watchlist]]: Navigation link under the title. See also {{msg|watchlisttools-view}} and {{msg|watchlisttools-edit}}.

{{Identical|Edit raw watchlist}}',

# Iranian month names
'iranian-calendar-m1'  => '{{optional}}
Name of month in Iranian calender.',
'iranian-calendar-m2'  => '{{optional}}
Name of month in Iranian calender.',
'iranian-calendar-m3'  => '{{optional}}
Name of month in Iranian calender.',
'iranian-calendar-m4'  => '{{optional}}
Name of month in Iranian calender.',
'iranian-calendar-m5'  => '{{optional}}
Name of month in Iranian calender.',
'iranian-calendar-m6'  => '{{optional}}
Name of month in Iranian calender.',
'iranian-calendar-m7'  => '{{optional}}
Name of month in Iranian calender.',
'iranian-calendar-m8'  => '{{optional}}
Name of month in Iranian calender.',
'iranian-calendar-m9'  => '{{optional}}
Name of month in Iranian calender.',
'iranian-calendar-m10' => '{{optional}}
Name of month in Iranian calender.',
'iranian-calendar-m11' => '{{optional}}
Name of month in Iranian calender.',
'iranian-calendar-m12' => '{{optional}}
Name of month in Iranian calender.',

# Hijri month names
'hijri-calendar-m1'  => '{{optional}}
Name of month in Islamic calender.',
'hijri-calendar-m2'  => '{{optional}}
Name of month in Islamic calender.',
'hijri-calendar-m3'  => '{{optional}}
Name of month in Islamic calender.',
'hijri-calendar-m4'  => '{{optional}}
Name of month in Islamic calender.',
'hijri-calendar-m5'  => '{{optional}}
Name of month in Islamic calender.',
'hijri-calendar-m6'  => '{{optional}}
Name of month in Islamic calender.',
'hijri-calendar-m7'  => '{{optional}}
Name of month in Islamic calender.',
'hijri-calendar-m8'  => '{{optional}}
Name of month in Islamic calender.',
'hijri-calendar-m9'  => '{{optional}}
Name of month in Islamic calender.',
'hijri-calendar-m10' => '{{optional}}
Name of month in Islamic calender.',
'hijri-calendar-m11' => '{{optional}}
Name of month in Islamic calender.',
'hijri-calendar-m12' => '{{optional}}
Name of month in Islamic calender.',

# Hebrew month names
'hebrew-calendar-m1'      => '{{optional}}
Name of month in Hebrew calender.',
'hebrew-calendar-m2'      => '{{optional}}
Name of month in Hebrew calender.',
'hebrew-calendar-m3'      => '{{optional}}
Name of month in Hebrew calender.',
'hebrew-calendar-m4'      => '{{optional}}
Name of month in Hebrew calender.',
'hebrew-calendar-m5'      => '{{optional}}
Name of month in Hebrew calender.',
'hebrew-calendar-m6'      => '{{optional}}
Name of month in Hebrew calender.',
'hebrew-calendar-m6a'     => '{{optional}}
Name of month in Hebrew calender.',
'hebrew-calendar-m6b'     => '{{optional}}
Name of month in Hebrew calender.',
'hebrew-calendar-m7'      => '{{optional}}
Name of month in Hebrew calender.',
'hebrew-calendar-m8'      => '{{optional}}
Name of month in Hebrew calender.',
'hebrew-calendar-m9'      => '{{optional}}
Name of month in Hebrew calender.',
'hebrew-calendar-m10'     => '{{optional}}
Name of month in Hebrew calender.',
'hebrew-calendar-m11'     => '{{optional}}
Name of month in Hebrew calender.',
'hebrew-calendar-m12'     => '{{optional}}
Name of month in Hebrew calender.',
'hebrew-calendar-m1-gen'  => '{{optional}}
Name of month in Hebrew calender.',
'hebrew-calendar-m2-gen'  => '{{optional}}
Name of month in Hebrew calender.',
'hebrew-calendar-m3-gen'  => '{{optional}}
Name of month in Hebrew calender.',
'hebrew-calendar-m4-gen'  => '{{optional}}
Name of month in Hebrew calender.',
'hebrew-calendar-m5-gen'  => '{{optional}}
Name of month in Hebrew calender.',
'hebrew-calendar-m6-gen'  => '{{optional}}
Name of month in Hebrew calender.',
'hebrew-calendar-m6a-gen' => '{{optional}}
Name of month in Hebrew calender.',
'hebrew-calendar-m6b-gen' => '{{optional}}
Name of month in Hebrew calender.',
'hebrew-calendar-m7-gen'  => '{{optional}}
Name of month in Hebrew calender.',
'hebrew-calendar-m8-gen'  => '{{optional}}
Name of month in Hebrew calender.',
'hebrew-calendar-m9-gen'  => '{{optional}}
Name of month in Hebrew calender.',
'hebrew-calendar-m10-gen' => '{{optional}}
Name of month in Hebrew calender.',
'hebrew-calendar-m11-gen' => '{{optional}}
Name of month in Hebrew calender.',
'hebrew-calendar-m12-gen' => '{{optional}}
Name of month in Hebrew calender.',

# Signatures
'signature'    => "This will be substituted in the signature (~<nowiki></nowiki>~~ or ~~<nowiki></nowiki>~~ excluding timestamp)
* $1: the username that are currently login
* $2: the customized signature which is specified in [[Special:Preferences|user's preferences]] as non-raw",
'timezone-utc' => '{{optional}}',

# Core parser functions
'unknown_extension_tag' => '* Description: This is an error shown when you use an unknown extension tag name. This feature allows tags like <tt><nowiki><pre></nowiki></tt> to be called with a parser like <tt><nowiki>{{#tag:pre}}</nowiki></tt>.
* Parameter $1: This is the unknown extension tag name.',
'duplicate-defaultsort' => 'Definition of [http://en.wikipedia.org/wiki/Sorting sort key] on Wikipedia.',

# Special:Version
'version'                       => 'Name of special page displayed in [[Special:SpecialPages]]

{{Identical|Version}}',
'version-extensions'            => 'Header on [[Special:Version]].',
'version-specialpages'          => 'Part of [[Special:Version]].

{{Identical|Special pages}}',
'version-parserhooks'           => 'This message is a heading at [[Special:Version]] for extensions that modifies the parser of wikitext.',
'version-variables'             => '{{Identical|Variable}}',
'version-skins'                 => '{{Identical|Skin}}',
'version-api'                   => '{{optional}}',
'version-other'                 => '{{Identical|Other}}',
'version-mediahandlers'         => 'Used in [[Special:Version]]. It is the title of a section for media handler extensions (e.g. [[mw:Extension:OggHandler]]).
There are no such extensions here, so look at [[wikipedia:Special:Version]] for an example.',
'version-hooks'                 => 'Shown in [[Special:Version]]',
'version-extension-functions'   => 'Shown in [[Special:Version]]',
'version-parser-function-hooks' => 'Shown in [[Special:Version]]',
'version-hook-name'             => 'Shown in [[Special:Version]]',
'version-hook-subscribedby'     => 'Shown in [[Special:Version]]',
'version-version'               => '{{Identical|Version}}',
'version-svn-revision'          => 'This is being used in [[Special:Version]], preceeding the subversion revision numbers of the extensions loaded inside brackets, like this: "({{int:version-revision}} r012345")

{{Identical|Revision}}',
'version-license'               => '{{Identical|License}}',
'version-poweredby-credits'     => 'Message shown on [[Special:Version]]. Parameters are:
*$1: the current year
*$2: a list of selected MediaWiki authors',
'version-poweredby-others'      => 'Used at the very end of {{msg-mw|version-poweredby-credits}} on [[Special:Version]]. First, there\'s a long list of selected MediaWiki authors, then the word "and" (from {{msg-mw|and}}) follows and then this translation, which is supposed to credit the many other people than developer helping with MediaWiki.',
'version-license-info'          => '[[wikipedia:GNU GPL|GNU GPL]] notice shown at [[Special:Version]]. See http://www.gnu.org/licenses/old-licenses/gpl-2.0-translations.html for available translations.',
'version-software-product'      => 'Shown in [[Special:Version]]',
'version-software-version'      => '{{Identical|Version}}',

# Special:FilePath
'filepath'         => 'Legend of fieldset around input box in [[Special:FilePath]].',
'filepath-page'    => 'Shown next to input box in [[Special:FilePath]]

{{Identical|File}}',
'filepath-submit'  => 'Button label in [[Special:FilePath]].
{{Identical|Go}}',
'filepath-summary' => 'Shown in [[Special:FilePath]]',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Name of special page [[Special:FileDuplicateSearch]].',
'fileduplicatesearch-summary'  => 'Summary of [[Special:FileDuplicateSearch]]',
'fileduplicatesearch-legend'   => 'Legend of the fieldset around the input form of [[Special:FileDuplicateSearch]]',
'fileduplicatesearch-filename' => 'Input form of [[Special:FileDuplicateSearch]]:

{{Identical|Filename}}',
'fileduplicatesearch-submit'   => 'Button label on [[Special:FileDuplicateSearch]].

{{Identical|Search}}',
'fileduplicatesearch-info'     => 'Information beneath the thumbnail on the right side shown after a successful search via [[Special:FileDuplicateSearch]]

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
'specialpages'                   => 'Display name of link to [[Special:SpecialPages]] shown on all pages in the toolbox, as well as the page title and header of [[Special:SpecialPages]].

{{Identical|Special pages}}',
'specialpages-note'              => 'Footer note for the [[Special:SpecialPages]] page',
'specialpages-group-maintenance' => 'Section heading in the list of [[Special:SpecialPages|Special pages]].',
'specialpages-group-other'       => 'Section heading in the list of [[Special:SpecialPages|Special pages]].',
'specialpages-group-login'       => 'Section heading in the list of [[Special:SpecialPages|Special pages]].',
'specialpages-group-changes'     => 'Section heading in the list of [[Special:SpecialPages|Special pages]].',
'specialpages-group-media'       => 'Section heading in the list of [[Special:SpecialPages|Special pages]].',
'specialpages-group-users'       => 'Section heading in the list of [[Special:SpecialPages|Special pages]].',
'specialpages-group-highuse'     => 'Section heading in the list of [[Special:SpecialPages|Special pages]].',
'specialpages-group-pages'       => 'Used on [[Special:SpecialPages]]. Title of the special pages group, containing pages like [[Special:AllPages]], [[Special:PrefixIndex]], [[Special:Categories]], [[Special:Disambiguations]], etc.',
'specialpages-group-pagetools'   => 'Title of the special pages group containing special pages like [[Special:MovePage]], [[Special:Undelete]], [[Special:WhatLinksHere]], [[Special:Export]] etc.',
'specialpages-group-wiki'        => 'Title of the special pages group, containing special pages like [[Special:Version]], [[Special:Statistics]], [[Special:LockDB]], etc.',
'specialpages-group-redirects'   => 'Title of the special pages group, containing special pages that redirect to another location, like [[Special:Randompage]], [[Special:Mypage]], [[Special:Mytalk]], etc.',

# Special:BlankPage
'intentionallyblankpage' => 'Text displayed in [[Special:BlankPage]].',

# External image whitelist
'external_image_whitelist' => "As usual please leave all the wiki markup, including the spaces, as they are. You can translate the text, including 'Leave this line exactly as it is'. The first line of this messages has one (1) leading space.

Definition of [http://en.wikipedia.org/wiki/Regular_expression regular expression] on Wikipedia.",

# Special:Tags
'tags'                    => "Shown on [[Special:Specialpages]] for page listing the tags that the software may mark an edit with, and their meaning. For more information on tags see [http://www.mediawiki.org/wiki/Manual:Tags Mediawiki].

It appears that the word 'valid' describes 'tags', not 'change'. It also appears that you could use the term 'defined' instead of 'valid', or perhaps use a phrase meaning 'Change tags in use'.",
'tag-filter'              => 'Caption of a filter shown on lists of changes (e.g. [[Special:Log]], [[Special:Contributions]], [[Special:Newpages]], [[Special:Recentchanges]], [[Special:Recentchangeslinked]], page histories)',
'tag-filter-submit'       => 'Caption of the submit button displayed next to the tag filter on lists of changes (e.g. [[Special:Log]], [[Special:Contributions]], [[Special:Newpages]], [[Special:Recentchanges]], [[Special:Recentchangeslinked]], page histories)

{{Identical|Filter}}',
'tags-title'              => 'The title of [[Special:Tags]]',
'tags-intro'              => 'Explanation on top of [[Special:Tags]]. For more information on tags see [http://www.mediawiki.org/wiki/Manual:Tags Mediawiki].',
'tags-tag'                => 'Caption of a column in [[Special:Tags]]. For more information on tags see [http://www.mediawiki.org/wiki/Manual:Tags Mediawiki].',
'tags-display-header'     => 'Caption of a column in [[Special:Tags]]. For more information on tags see [http://www.mediawiki.org/wiki/Manual:Tags Mediawiki].',
'tags-description-header' => 'Caption of a column in [[Special:Tags]]. For more information on tags see [http://www.mediawiki.org/wiki/Manual:Tags Mediawiki].',
'tags-hitcount-header'    => 'Caption of a column in [[Special:Tags]]. For more information on tags see [http://www.mediawiki.org/wiki/Manual:Tags Mediawiki].',
'tags-edit'               => '{{Identical|Edit}}
Used on [[Special:Tags]]. Verb. Used as display text on a link to create/edit a description.',
'tags-hitcount'           => 'Shown in the “Tagged changes” column in [[Special:Tags]]. For more information on tags see [http://www.mediawiki.org/wiki/Manual:Tags Mediawiki].

* <code>$1</code> is the number of changes marked with the tag',

# Special:ComparePages
'comparepages'     => 'The title of [[Special:ComparePages]]',
'compare-selector' => 'Header of the form on [[Special:ComparePages]]',
'compare-page1'    => 'Label for the field of the 1st page in the comparison for [[Special:ComparePages]]
{{Identical|Page}}',
'compare-page2'    => 'Label for the field of the 2nd page in the comparison for [[Special:ComparePages]]
{{Identical|Page}}',
'compare-rev1'     => 'Label for the field of the 1st revision in the comparison for [[Special:ComparePages]]
{{Identical|Revision}}',
'compare-rev2'     => 'Label for the field of the 2nd revision in the comparison for [[Special:ComparePages]]
{{Identical|Revision}}',
'compare-submit'   => 'Submit button on [[Special:ComparePages]]',

# Database error messages
'dberr-header'    => 'This message does not allow any wiki nor html markup.',
'dberr-problems'  => 'This message does not allow any wiki nor html markup.',
'dberr-again'     => 'This message does not allow any wiki nor html markup.',
'dberr-info'      => 'This message does not allow any wiki nor html markup.',
'dberr-usegoogle' => 'This message does not allow any wiki nor html markup.',
'dberr-outofdate' => "In this sentence, '''their''' indexes refers to '''Google's''' indexes. This message does not allow any wiki nor html markup.",

# HTML forms
'htmlform-submit'              => '{{Identical|Submit}}',
'htmlform-reset'               => '{{Identical|Undo}}',
'htmlform-selectorother-other' => 'Used in drop-down boxes in [[Special:Preferences]] as follows:
* selection of timezone (date and time tab)
* stub threshold (appearance tab)

{{Identical|Other}}',

# SQLite database support
'sqlite-has-fts' => 'Shown on Special:Version, $1 is version',
'sqlite-no-fts'  => 'Shown on Special:Version, $1 is version',

# New logging system
'logentry-delete-delete'              => '{{Logentry}}',
'logentry-delete-restore'             => '{{Logentry}}',
'logentry-delete-event'               => '{{Logentry}}
{{Logentryparam}}
* $3 is the name of the log page inside parenthesis',
'logentry-delete-revision'            => '{{Logentry}}
{{Logentryparam}}
* $5 is the number of affected revisions of the page $3.',
'logentry-delete-event-legacy'        => '{{Logentry}}
$3 is the name of the log page inside parenthesis',
'logentry-delete-revision-legacy'     => '{{Logentry}}',
'logentry-suppress-delete'            => "{{Logentry}}

'Hid' is a possible alternative to 'suppressed' in this message.",
'logentry-suppress-event'             => '{{Logentry}}
{{Logentryparam}}
$3 is the name of the log page inside parenthesis',
'logentry-suppress-revision'          => '{{Logentry}}
{{Logentryparam}}
* $5 is the number of affected revisions of the page $3.',
'logentry-suppress-event-legacy'      => '{{Logentry}}
$3 is the name of the log page inside parenthesis',
'logentry-suppress-revision-legacy'   => '{{Logentry}}',
'revdelete-content-hid'               => 'Used on
* {{msg-mw|logentry-delete-event}}
* {{msg-mw|logentry-delete-revision}}
* {{msg-mw|logentry-suppress-event}}
* {{msg-mw|logentry-suppress-event}}',
'revdelete-summary-hid'               => 'Used on
* {{msg-mw|logentry-delete-event}}
* {{msg-mw|logentry-delete-revision}}
* {{msg-mw|logentry-suppress-event}}
* {{msg-mw|logentry-suppress-event}}',
'revdelete-uname-hid'                 => 'Used on
* {{msg-mw|logentry-delete-event}}
* {{msg-mw|logentry-delete-revision}}
* {{msg-mw|logentry-suppress-event}}
* {{msg-mw|logentry-suppress-event}}',
'revdelete-content-unhid'             => 'Used on
* {{msg-mw|logentry-delete-event}}
* {{msg-mw|logentry-delete-revision}}
* {{msg-mw|logentry-suppress-event}}
* {{msg-mw|logentry-suppress-event}}',
'revdelete-summary-unhid'             => 'Used on
* {{msg-mw|logentry-delete-event}}
* {{msg-mw|logentry-delete-revision}}
* {{msg-mw|logentry-suppress-event}}
* {{msg-mw|logentry-suppress-event}}',
'revdelete-uname-unhid'               => 'Used on
* {{msg-mw|logentry-delete-event}}
* {{msg-mw|logentry-delete-revision}}
* {{msg-mw|logentry-suppress-event}}
* {{msg-mw|logentry-suppress-event}}',
'revdelete-restricted'                => 'This message is used as parameter $4 when setting visibility restrictions for administrators in:
* {{msg-mw|logentry-delete-event}}
* {{msg-mw|logentry-delete-revision}}
* {{msg-mw|logentry-suppress-event}}
* {{msg-mw|logentry-suppress-event}}',
'revdelete-unrestricted'              => 'This message is used as parameter $4 when setting visibility restrictions for administrators in:
* {{msg-mw|logentry-delete-event}}
* {{msg-mw|logentry-delete-revision}}
* {{msg-mw|logentry-suppress-event}}
* {{msg-mw|logentry-suppress-event}}',
'logentry-move-move'                  => '{{Logentry}}
Parameter $4, the target page, is also not visible to parser functions.',
'logentry-move-move-noredirect'       => '{{Logentry}}
Parameter $4, the target page, is also not visible to parser functions.',
'logentry-move-move_redir'            => '{{Logentry}}
Parameter $4, the target page, is also not visible to parser functions.',
'logentry-move-move_redir-noredirect' => '{{Logentry}}
Parameter $4, the target page, is also not visible to parser functions.',
'logentry-patrol-patrol'              => '{{Logentry}}
* $4 is a formatted revision number, maybe linked to the diff.',
'logentry-patrol-patrol-auto'         => '{{Logentry}}
* $4 is a formatted revision number, maybe linked to the diff.
"Automatically" refers to users with autopatrol right who mark revisions automatically patrolled when editing.',

);

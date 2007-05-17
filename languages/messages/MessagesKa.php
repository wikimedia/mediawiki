<?php
/** Georgian (ქართული)
  *
  * @addtogroup Language
  */
$namespaceNames = array(
	NS_MEDIA            => 'მედია',
	NS_SPECIAL          => 'სპეციალური',
	NS_MAIN             => '',
	NS_TALK             => 'განხილვა',
	NS_USER             => 'მომხმარებელი',
	NS_USER_TALK        => 'მომხმარებელი_განხილვა',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => '$1_განხილვა',
	NS_IMAGE            => 'სურათი',
	NS_IMAGE_TALK       => 'სურათი_განხილვა',
	NS_MEDIAWIKI        => 'მედიავიკი',
	NS_MEDIAWIKI_TALK   => 'მედიავიკი_განხილვა',
	NS_TEMPLATE         => 'თარგი',
	NS_TEMPLATE_TALK    => 'თარგი_განხილვა',
	NS_HELP             => 'დახმარება',
	NS_HELP_TALK        => 'დახმარება_განხილვა',
	NS_CATEGORY         => 'კატეგორია',
	NS_CATEGORY_TALK    => 'კატეგორია_განხილვა'
);

$linkPrefixExtension = true;

$linkTrail = '/^([a-zაბგდევზთიკლმნოპჟრსტუფქღყშჩცძწჭხჯჰ“»]+)(.*)$/sDu';

# Note to translators:
#   Please include the English words as synonyms.  This allows people
#   from other wikis to contribute more easily.
#
$magicWords = array(
	'redirect' => array( 0   , '#REDIRECT', '#გადამისამართება' ),
);

$messages = array(

# User preference toggles
'tog-underline' => 'ბმულებზე გაზის გასმა:',
'tog-highlightbroken' => 'აჩვენე არარსებული ბმულები <a href="" class="new">როგორც ეს</a> (ალტერნატივა: როგორც ეს<a href="" class="internal">?</a>).',
#TODO: 'tog-justify'	=> 'Justify paragraphs',
'tog-hideminor' => 'უკანასკნელ ცვლილებებში მცირე რედაქტირებების დამალვა',
'tog-extendwatchlist' => 'განავრცეთ კონტროლის სია ისე, რომ აჩვენოთ ყველა შესაძლებელი ცვლილება',
'tog-usenewrc' => 'გაზარდეთ ბოლო ცვლილებების სია (ჯავასკრიპტი)',
'tog-numberheadings' => 'სათაურების ავტომატურად გადანომვრა',
'tog-showtoolbar'		=> 'სარედაქტორო ინსტრუმენტების პანელის (ჯავასკრიპტი) ჩვენება',
'tog-editondblclick' => 'გვერდების რედაქტირება მოახდინეთ ორმაგი დაჭერით (ჯავასკრიპტი)',
'tog-editsection'		=> 'ნება დართეთ სექციის რედაქტირებაზე \'[რედაქტირება]\' ბმულების გავლით',
'tog-editsectiononrightclick'	=> 'ნება დართეთ სექციის რედაქტირებაზე მარჯვენა ღილაკზე დაჭერით<br />სექციის სათაურებზე (ჯავასკრიპტი)',
'tog-showtoc'			=> 'აჩვენეთ სარჩევი ცხრილი (იმ გვერდებისათვის, რომელსაც სამზე მეტი სათაური აქვთ)',
'tog-rememberpassword' => 'სესიებს შორის პაროლის დამახსოვრება',
'tog-editwidth' => 'სარედაქტირო ყუთს აქვს სრული სიგანე',
'tog-watchcreations' => 'გვერდები, რომელიც მე გავხსენი, დაამატეთ ჩემს საკონტროლო სიას',
'tog-watchdefault' => 'დამალეთ რობოტის რედაქტირებები საკონტროლო სიიდან',
#TODO: 'tog-watchmoves' => 'Add pages I move to my watchlist',
#TODO: 'tog-watchdeletion' => 'Add pages I delete to my watchlist',
#TODO: 'tog-minordefault' => 'Mark all edits minor by default',
'tog-previewontop' => 'რედაქტირების ფანჯრამდე წინასწარი ხედვის ჩვენება',
'tog-previewonfirst' => 'პირველი რედაქტიებისას წინასწარი გადახედვის ჩვენება',
#TODO: 'tog-nocache' => 'Disable page caching',
'tog-enotifwatchlistpages' 	=> 'მომწერეთ როდესაც გვერდი, რომელსაც მე ვაკონტროლებ შეიცვლება',
'tog-enotifusertalkpages' 	=> 'მომწერეთ, როდესაც ჩემი მომხმარებლის განხილვის გვერდი შეიცვლება',
'tog-enotifminoredits' 		=> 'მომწერეთ ასევე მცირე რედაქტირებების შესახებ გვერდებზე',
'tog-enotifrevealaddr' 		=> 'ჩემი ელ. ფოსტის მისამართი შეხსენებების წერილებში აჩვენეთ',
'tog-shownumberswatching' 	=> 'კონტროლის ქვეშ მყოფი მომხმარებელთა რაოდენობის ჩვენება',
'tog-fancysig' => 'გამოუყენებელი ხელმოწერები (ავტომატური ბმულის გარეშე)',
#TODO: 'tog-externaleditor' => 'Use external editor by default',
#TODO: 'tog-externaldiff' => 'Use external diff by default',
'tog-showjumplinks' => 'დამხმარე ბმულების "გადასვლა -კენ" ჩართვა',
'tog-uselivepreview' => 'გამოიყენეთ ახალი წინასწარი გადახედვა (ჯავასკრიპტი) (ექსპერიმენტული)',
#TODO: 'tog-forceeditsummary' => 'Prompt me when entering a blank edit summary',
'tog-watchlisthideown' => 'დამალეთ საკონტროლო სიიდან ჩემი რედაქტირებები',
'tog-watchlisthidebots' => 'დამალეთ საკონტროლო სიიდან ჩემი რედაქტირებები',
'tog-watchlisthideminor' => 'დამალეთ საკონტროლო სიიდან მცირე რედაქტირებები',
#TODO: 'tog-nolangconversion'		=> 'Disable variants conversion',
#TODO: 'tog-ccmeonemails' => 'Send me copies of emails I send to other users',
#TODO: 'tog-diffonly' => "Don't show page content below diffs",

'underline-always' => 'ყოველთვის',
'underline-never' => 'არასოდეს',
#TODO: 'underline-default' => 'Browser default',

'skinpreview' => '(წინასწარი გადახედვა)',

# Dates
'sunday'	=> 'კვირა',
'monday'	=> 'ორშაბათი',
'tuesday'	=> 'სამშაბათი',
'wednesday'	=> 'ოთხშაბათი',
'thursday'	=> 'ხუთშაბათი',
'friday'	=> 'პარასკევი',
'saturday'	=> 'შაბათი',
'sun'		=> 'კვი',
'mon'		=> 'ორშ',
'tue'		=> 'სამ',
'wed'		=> 'ოთხ',
'thu'		=> 'ხუთ',
'fri'		=> 'პარ',
'sat'		=> 'შაბ',
'january'	=> 'იანვარი',
'february'	=> 'თებერვალი',
'march'		=> 'მარტი',
'april'		=> 'აპრილი',
'may_long'	=> 'მაისი',
'june'		=> 'ივნისი',
'july'		=> 'ივლისი',
'august'	=> 'აგვისტო',
'september'	=> 'სექტემბერი',
'october'	=> 'ოქტომბერი',
'november'	=> 'ნოემბერი',
'december'	=> 'დეკემბერი',
'january-gen'	=> 'იანვრის',
'february-gen'	=> 'თებერვლის',
'march-gen'	=> 'მარტის',
'april-gen'	=> 'აპრილის',
'may-gen'	=> 'მაისის',
'june-gen'	=> 'ივნისის',
'july-gen'	=> 'ივლისის',
'august-gen'	=> 'აგვისტოს',
'september-gen'	=> 'სექტემბრის',
'october-gen'	=> 'ოქტომბრის',
'november-gen'	=> 'ნოემბრის',
'december-gen'	=> 'დეკემბრის',
'jan'		=> 'იან',
'feb'		=> 'თებ',
'mar'		=> 'მარ',
'apr'		=> 'აპრ',
'may'		=> 'მაი',
'jun'		=> 'ივნ',
'jul'		=> 'ივლ',
'aug'		=> 'აგვ',
'sep'		=> 'სექ',
'oct'		=> 'ოქტ',
'nov'		=> 'ნოე',
'dec'		=> 'დეკ',

# Bits of text used by many pages:
'categories' => 'კატეგორიები',
'pagecategories' => '{{PLURAL:$1|კატეგორია|კატეგორიები}}',
'category_header' => 'სტატიები კატეგორიაში "$1"',
'subcategories' => 'ქვეკატეგორიები',


'linkprefix' => '/^(.*?)(„|«)$/sD',
'mainpage'		=> 'მთავარი გვერდი',
#TODO: 'mainpagetext'	=> "<big>'''MediaWiki has been successfully installed.'''</big>",
/*TODO: 'mainpagedocfooter' => "Consult the [http://meta.wikimedia.org/wiki/Help:Contents User's Guide] for information on using the wiki software.

== Getting started ==

* [http://www.mediawiki.org/wiki/Help:Configuration_settings Configuration settings list]
* [http://www.mediawiki.org/wiki/Help:FAQ MediaWiki FAQ]
* [http://mail.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki release mailing list]",*/

'portal'		=> 'საზოგადოების პორტალი',
'portal-url'		=> '{{ns:project}}:საზოგადოების პორტალი',
'about'			=> 'შესახებ',
'aboutsite'		=> '{{SITENAME}}-ის შესახებ',
'aboutpage'		=> 'პროექტი:შესახებ',
'article'		=> 'სტატია',
'help'			=> 'დახმარება',
'helppage'		=> '{{ns:project}}:დახმარება',
'bugreports'	=> 'ანგარიში შეცდომის შესახებ',
'bugreportspage' => '{{ns:project}}:ანგარიში შეცდომის შესახებ',
'sitesupport'   => 'შეწირულობები',
'sitesupport-url' => '{{ns:project}}:შეწირულობები',
'faq'			=> 'ხშირი შეკითხვები',
'faqpage'		=> '{{ns:project}}:ხშირი შეკითხვები',
'edithelp'		=> 'რედაქტირების დახმარება',
'newwindow'		=> '(ახალ ფანჯარაში)',
'edithelppage'	=> '{{ns:project}}:რედაქტირების დახმარება',
'cancel'		=> 'გაუქმება',
'qbfind'		=> 'ძებნა',
'qbbrowse'		=> 'მიმოხილვა',
'qbedit'		=> 'რედაქტირება',
'qbpageoptions' => 'ეს გვერდი',
'qbpageinfo'	=> 'კონტექსტი',
'qbmyoptions'	=> 'ჩემი გვერდები',
'qbspecialpages'	=> 'სპეციალური გვერდები',
'moredotdotdot'	=> 'მეტი...',
'mypage'		=> 'ჩემი გვერდი',
'mytalk'		=> 'ჩემი განხილვა',
'anontalk'		=> 'ამ IP-ს განხილვა',
'navigation' => 'ნავიგაცია',

# Metadata in edit box
#TODO: 'metadata_help' => 'Metadata (see [[{{ns:project}}:Metadata]] for an explanation):',

'currentevents' => 'ახალი ამბები',
'currentevents-url' => 'ახალი ამბები',

'disclaimers' => 'პასუხისმგებლობის უარყოფა',
'disclaimerpage' => '{{ns:project}}:პასუხისმგებლობის უარყოფა',
'privacy' => 'კონფიდენციალურობის პოლიტიკა',
'privacypage' => '{{ns:project}}:კონფიდენციალურობის პოლიტიკა',
'errorpagetitle' => 'შეცდომა',
'returnto'		=> '$1-ზე დაბრუნება.',
'tagline'      	=> '{{SITENAME}}დან',
'help'			=> 'დახმარება',
'search'		=> 'ძიება',
'searchbutton'		=> 'ძიება',
'go'		=> 'გვერდი',
'searcharticle'		=> 'გვერდი',
'history'		=> 'გვერდის ისტორია',
'history_short' => 'ისტორია',
'updatedmarker' => 'ჩემი უკანასკნელი შემოსვლიდან ცვლილებები',
'info_short'	=> 'ინფორმაცია',
'printableversion' => 'დასაბეჭდი ვერსია',
'permalink'     => 'მუდმივი ბმული',
'print' => 'ბეჭდვა',
'edit' => 'რედაქტირება',
'editthispage'	=> 'ამ გვერდის რედაქტირება',
'delete' => 'წაშლა',
'deletethispage' => 'ამ გვერდის წაშლა',
'undelete_short' => '$1 ცვლილების აღდგენა',
'protect' => 'დაცვა',
#TODO: 'protectthispage' => 'Protect this page',
'unprotect' => 'დაცვის მოხსნა',
'unprotectthispage' => 'გვერდის დაცვის მოხსნა',
'newpage' => 'ახალი გვერდი',
'talkpage'		=> 'განიხილეთ ეს გვერდი',
'specialpage' => 'სპეციალური გვერდი',
#TODO: 'personaltools' => 'Personal tools',
'postcomment'   => 'დაურთეთ კომენტარი',

'articlepage'	=> 'სტატიის ნახვა',
'talk' => 'განხილვა',
#TODO: 'views' => 'Views',
'toolbox' => 'ხელსაწყოები',
'userpage' => 'მომხმარებლის გვერდის ხილვა',
'projectpage' => 'პროექტის გვერდის ხილვა',
'imagepage' => 	'სურათის გვერდის ნახვა',
#TODO: 'mediawikipage' => 	'View message page',
#TODO: #'templatepage' => 	'View template page',
#TODO: 'viewhelppage' => 	'View help page',
'categorypage' => 	'კატეგორიის გვერდის ხილვა',
#TODO: 'viewtalkpage' => 'View discussion',
'otherlanguages' => 'სხვა ენებზე',
#TODO: 'redirectedfrom' => '(Redirected from $1)',
'autoredircomment' => 'გადამისამართება [[$1]]-ზე',
'redirectpagesub' => 'გადამისამართების გვერდი',
'lastmodifiedat'		=> 'ეს გვერდი ბოლოს განახლდა $2, $1.',	//$1 date, $2 time
#TODO: 'viewcount'		=> 'This page has been accessed {{plural:$1|one time|$1 times}}.',
#TODO: 'copyright'	=> 'Content is available under $1.',
#TODO: 'protectedpage' => 'Protected page',
#TODO: 'jumpto' => 'Jump to:',
'jumptonavigation' => 'ნავიგაცია',
'jumptosearch' => 'ძიება',

'badaccess'        => 'აკრძალული მოქმედება',
#'badaccess-group0' => 'You are not allowed to execute the action you have requested.',
#'badaccess-group1' => 'The action you have requested is limited to users in the group $1.',
#'badaccess-group2' => 'The action you have requested is limited to users in one of the groups $1.',
#'badaccess-groups' => 'The action you have requested is limited to users in one of the groups $1.',

#'versionrequired' => 'Version $1 of MediaWiki required',
#'versionrequiredtext' => 'Version $1 of MediaWiki is required to use this page. See [[Special:Version]]',

#DONT: 'widthheight'		=> '$1×$2',
#'ok'			=> 'OK',
#'sitetitle'		=> '{{SITENAME}}',
#'pagetitle'		=> '$1 - {{SITENAME}}',
#'sitesubtitle'	=> '',
#'retrievedfrom' => 'Retrieved from "$1"',
'youhavenewmessages' => 'თქვენ გაქვთ $1 ($2).',
'newmessageslink' => 'ახალი შეტყობინებები',
'newmessagesdifflink' => 'განსხვავება უკანასკნელ მდგომარეობას შორის',
'editsection'=>'რედაქტირება',
'editold'=>'რედაქტირება',
'editsectionhint' => 'სექციის რედაქტირება: $1',
'toc' => 'სარჩევი',
'showtoc' => 'ჩვენება',
'hidetoc' => 'დამალვა',
'thisisdeleted' => 'გსურთ განიხილოთ ან აღადგინოთ $1?',
'viewdeleted' => 'იხილე $1?',
'restorelink' => '{{PLURAL:$1|ერთი წაშლილი რედაქტირება|$1 წაშლილი რედაქტირება}}',
#'feedlinks' => 'Feed:',
#'feed-invalid' => 'Invalid subscription feed type.',
#'sitenotice'	=> '-', # the equivalent to wgSiteNotice
#'anonnotice' => '-',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main' => 'სტატია',
'nstab-user' => 'მომხმარებლის გვერდი',
'nstab-media' => 'მედია',
'nstab-special' => 'სპეციალური',
'nstab-project' => 'პროექტის გვერდი',
'nstab-image' => 'ფაილი',
'nstab-mediawiki' => 'შეტყობინება',
'nstab-template' => 'თარგი',
'nstab-help' => 'დახმარება',
'nstab-category' => 'კატეგორია',

# Main script and global functions

# General errors

# Login and logout pages

# Edit page toolbar

# Edit pages

# History pages

# Revision deletion

# Diffs
'difference'    => '(სხვაობა ვერსიებს შორის)',
#TODO: 'loadingrev'	=> 'loading revision for diff',
'lineno'                => "ხაზი $1:",
'editcurrent'   => 'ამ გვერდის ამჟამინდელი ვერსიის რედაქტირება',
#TODO: 'selectnewerversionfordiff' => 'Select a newer version for comparison',
#TODO: 'selectolderversionfordiff' => 'Select an older version for comparison',
'compareselectedversions' => 'არჩეული ვერსიების შედარება',

# Search results
#
#TODO: 'searchresults' => 'Search results',
#TODO: 'searchresulttext' => "For more information about searching {{SITENAME}}, see [[{{MediaWiki:helppage}}|{{int:help}}]].",
#TODO: 'searchsubtitle' => "You searched for '''[[:$1]]'''",
#TODO: 'searchsubtitleinvalid' => "You searched for '''$1'''",
#TODO: 'badquery'		=> 'Badly formed search query',
/*#TODO: 'badquerytext'	=> 'We could not process your query.
This is probably because you have attempted to search for a
word fewer than three letters long, which is not yet supported.
It could also be that you have mistyped the expression, for
example "fish and and scales".
Please try another query.',*/
/*#TODO: 'matchtotals'	=> "The query \"$1\" matched $2 page titles
and the text of $3 pages.",*/
#TODO: 'noexactmatch' => "'''There is no page titled \"$1\".''' You can [[:$1|create this page]].",
#TODO: 'titlematches'	=> 'Article title matches',
#TODO: 'notitlematches' => 'No page title matches',
#TODO: 'textmatches'	=> 'Page text matches',
#TODO: 'notextmatches'	=> 'No page text matches',
#TODO: 'prevn'			=> "previous $1",
#TODO: 'nextn'			=> "next $1",
#TODO: 'viewprevnext'	=> "View ($1) ($2) ($3).",
#TODO: 'showingresults' => "Showing below up to <b>$1</b> results starting with #<b>$2</b>.",
#TODO: 'showingresultsnum' => "Showing below <b>$3</b> results starting with #<b>$2</b>.",
/*#TODO: 'nonefound'		=> "'''Note''': Unsuccessful searches are
often caused by searching for common words like \"have\" and \"from\",
which are not indexed, or by specifying more than one search term (only pages
containing all of the search terms will appear in the result).",*/
#TODO: 'powersearch' => 'Search',
#TODO: 'powersearchtext' => "Search in namespaces:<br />$1<br />$2 List redirects<br />Search for $3 $9",
#TODO: 'searchdisabled' => '{{SITENAME}} search is disabled. You can search via Google in the meantime. Note that their indexes of {{SITENAME}} content may be out of date.',

/*#TODO: 'googlesearch' => '
<form method="get" action="http://www.google.com/search" id="googlesearch">
    <input type="hidden" name="domains" value="{{SERVER}}" />
    <input type="hidden" name="num" value="50" />
    <input type="hidden" name="ie" value="$2" />
    <input type="hidden" name="oe" value="$2" />

    <input type="text" name="q" size="31" maxlength="255" value="$1" />
    <input type="submit" name="btnG" value="$3" />
  <div>
    <input type="radio" name="sitesearch" id="gwiki" value="{{SERVER}}" checked="checked" /><label for="gwiki">{{SITENAME}}</label>
    <input type="radio" name="sitesearch" id="gWWW" value="" /><label for="gWWW">WWW</label>
  </div>
</form>',*/
'blanknamespace' => '(მთავარი)',

# Preferences page
'preferences'	=> 'კონფიგურაცია',
'mypreferences'	=> 'ჩემი კონფიგურაცია',
#TODO: 'prefsnologin' => 'Not logged in',
#TODO: 'prefsnologintext'	=> "You must be [[Special:Userlogin|logged in]] to set user preferences.",
#TODO: 'prefsreset'	=> 'Preferences have been reset from storage.',
'qbsettings'	=> 'სწრაფი ზოლი',
'changepassword' => 'პაროლის შეცვლა',
#TODO: 'skin'			=> 'Skin',
#TODO: 'math'			=> 'Math',
'dateformat'		=> 'თარიღის ფორმატი',
#TODO: 'datedefault'		=> 'No preference',
'datetime'		=> 'თარიღი და დრო',
#TODO: 'math_failure'		=> 'Failed to parse',
#TODO: 'math_unknown_error'	=> 'unknown error',
#TODO: 'math_unknown_function'	=> 'unknown function',
#TODO: 'math_lexing_error'	=> 'lexing error',
#TODO: 'math_syntax_error'	=> 'syntax error',
#TODO: 'math_image_error'	=> 'PNG conversion failed; check for correct installation of latex, dvips, gs, and convert',
#TODO: 'math_bad_tmpdir'	=> 'Can\'t write to or create math temp directory',
#TODO: 'math_bad_output'	=> 'Can\'t write to or create math output directory',
#TODO: 'math_notexvc'	=> 'Missing texvc executable; please see math/README to configure.',
'prefs-personal' => 'მომხმარებლის მონაცემები',
'prefs-rc' => 'ბოლო ცვლილებები',
'prefs-watchlist' => 'კონტროლის სია',
#TODO: 'prefs-watchlist-days' => 'Number of days to show in watchlist:',
#TODO: 'prefs-watchlist-edits' => 'Number of edits to show in expanded watchlist:',
#TODO: 'prefs-misc' => 'Misc',
'saveprefs'		=> 'შენახვა',
'resetprefs'	=> 'გადატვირთვა',
'oldpassword'	=> 'ძველი პაროლი:',
'newpassword'	=> 'ახალი პაროლი:',
#TODO: 'retypenew'		=> 'Retype new password:',
'textboxsize'	=> 'რედაქტირება',
'rows'			=> 'რიგები:',
'columns'		=> 'სვეტები:',
'searchresultshead' => 'ძიება',
#TODO: 'resultsperpage' => 'Hits per page:',
'contextlines'	=> 'სტრიქონები შედეგის მიხედვით:',
'contextchars'	=> 'კონტექსტი სტრიქონების მიხედვით:',
#TODO: 'stubthreshold' => 'Threshold for stub display:',
#TODO: 'recentchangescount' => 'Titles in recent changes:',
'savedprefs'	=> 'თქვენს მიერ შერჩეული პარამეტრები დამახსოვრებულია.',
#TODO: 'timezonelegend' => 'Time zone',
#TODO: 'timezonetext'	=> 'The number of hours your local time differs from server time (UTC).',
'localtime'	=> 'ლოკალური დრო',
#TODO: 'timezoneoffset' => 'Offset¹',
#TODO: 'servertime'	=> 'Server time',
'guesstimezone' => 'ბრაუზერიდან შევსება',
'allowemail'		=> 'შესაძლებელია ელ. წერილების მიღება სხვა მომხმარებლებისაგან',
'defaultns'		=> 'სტანდარტული ძიება ამ სახელთა სივრცეებში:',
'default'		=> 'სტანდარტული',
'files'			=> 'ფაილები',

# User rights

# Groups
'group'                   => 'ჯგუფი:',
'group-bot'               => 'რობოტები',
'group-sysop'             => 'ადმინისტრატორები',
'group-bureaucrat'        => 'ბიუროკრატები',
'group-all'               => '(ყველა)',

'group-bot-member'        => 'რობოტი',
'group-sysop-member'      => 'ადმინისტრატორი',
'group-bureaucrat-member' => 'ბიუროკრატი',

'grouppage-bot' => '{{ns:project}}:რობოტები',
'grouppage-sysop' => '{{ns:project}}:ადმინისტრატორები',
'grouppage-bureaucrat' => '{{ns:project}}:ბიუროკრატები',

# Recent changes
'changes' => 'ცვლილებები',
'recentchanges' => 'ბოლო ცვლილებები',
#DONT: 'recentchanges-url' => 'Special:Recentchanges',
#TODO: 'recentchangestext' => 'Track the most recent changes to the wiki on this page.',
#TODO: 'rcnote'		=> "Below are the last <strong>$1</strong> changes in the last <strong>$2</strong> days, as of $3.",
#TODO: 'rcnotefrom'	=> "Below are the changes since <b>$2</b> (up to <b>$1</b> shown).",
'rclistfrom'	=> "ახალი ცვლილებების ჩვენება დაწყებული $1-დან",
'rcshowhideminor' => 'მცირე რედაქტირების $1',
'rcshowhidebots' => 'რობოტების $1',
'rcshowhideliu' => 'რეგისტრირებული მომხმარებლების $1',
'rcshowhideanons' => 'ანონიმური მომხმარებლების $1',
#TODO: 'rcshowhidepatr' => '$1 patrolled edits',
'rcshowhidemine' => 'ჩემი რედაქტირების $1',
'rclinks'		=> "ბოლო $1 ცვლილების ჩვენება უკანასკნელი $2 დღის მანძილზე<br />$3",
'diff'			=> 'განსხ.',
'hist'			=> 'ისტ.',
'hide'			=> 'დამალვა',
'show'			=> 'ჩვენება',
'minoreditletter' => 'მ',
'newpageletter' => 'ა',
'boteditletter' => 'რ',
'sectionlink' => '→',
#TODO: 'number_of_watching_users_RCview' 	=> '[$1]',
#TODO: 'number_of_watching_users_pageview' 	=> '[$1 watching user/s]',
#TODO: 'rc_categories'	=> 'Limit to categories (separate with "|")',
#TODO: 'rc_categories_any'	=> 'Any',

# Upload

# Image list
'imagelist'		=> 'ფაილების სია',
'imagelisttext' => "ქვემოთ მოცემულია '''$1''' ფაილის სია დახარისხებული მომხმარებლის $2 მიერ.",
'imagelistforuser' => "აქ მხოლოდ ნაჩვენებია მომხმარებლის $1 მიერ ჩატვირთული სურათები.",
'getimagelist'	=> 'ფაილთა სიის ჩამოტვირთვა',
'ilsubmit'		=> 'ძიება',
#TODO: 'showlast'		=> 'Show last $1 files sorted $2.',
'byname'		=> 'სახელით',
'bydate'		=> 'თარიღით',
'bysize'		=> 'ზომით',
'imgdelete'		=> 'წაშ.',
'imgdesc'		=> 'აღწ.',
'imgfile'       => 'ფაილი',
#TODO: 'imglegend'		=> 'Legend: (desc) = show/edit file description.',
'imghistory'	=> 'ფაილის ისტორია',
#TODO: 'revertimg'		=> 'rev',
'deleteimg'		=> 'წაშ.',
#TODO: 'deleteimgcompletely'		=> 'Delete all revisions of this file',
/*TODO: 'imghistlegend' => 'Legend: (cur) = this is the current file, (del) = delete
this old version, (rev) = revert to this old version.
<br /><i>Click on date to see the file uploaded on that date</i>.',*/
'imagelinks'	=> 'ბმულები',
'linkstoimage'	=> 'ამ ფაილზე ბმული მოცემულია შემდეგ გვერდებზე:',
'nolinkstoimage' => 'არ არსებობს ამ ფაილთან დაკავშირებული გვერდები.',
#TODO: 'sharedupload' => 'This file is a shared upload and may be used by other projects.',
'shareduploadwiki' => 'გთხოვთ, იხილოთ $1 შემდგომი ინფორმაციის მისაღებად.',
#TODO: 'shareduploadwiki-linktext' => 'file description page',
#DONT: 'shareddescriptionfollows' => '-',
'noimage'       => 'ამ სახელის მქონე ფაილი არ არსებობს, თქვენ შეგიძლიათ $1.',
'noimage-linktext'       => 'ფაილის ატვირთვა',
'uploadnewversion-linktext' => 'ამ ფაილის ახალი ვერსიის ატვირთვა',
'imagelist_date' => 'თარიღი',
'imagelist_name' => 'სახელი',
'imagelist_user' => 'მომხმარებელი',
'imagelist_size' => 'ზომა (ბაიტები)',
'imagelist_description' => 'აღწერილობა',
'imagelist_search_for' => 'ძიება სურათის სახელის მიხედვით:',

# Mime search

# Unwatchedpages

# List redirects

# Unused templates

# Random redirect
'randomredirect' => 'ნებისმიერი გადამისამართება',

# Statistics

# Miscellaneous special pages
#
'nbytes'		=> '$1 ბაიტი',
'ncategories'		=> '$1 კატეგორია',
'nlinks'		=> '$1 ბმული',
'nmembers'		=> '$1 წევრი',
#TODO: 'nrevisions'		=> '$1 {{PLURAL:$1|revision|revisions}}',
#TODO: 'nviews'		=> '$1 {{PLURAL:$1|view|views}}',
#TODO: 'specialpage-empty'     => 'This page is empty.',
#TODO: 'lonelypages'	=> 'Orphaned pages',
#'lonelypages-summary'	=> '',
#TODO: 'lonelypagestext'	=> 'The following pages are not linked from other pages in this wiki.',
'uncategorizedpages'	=> 'გვერდები კატეგორიის გარეშე',
#'uncategorizedpages-summary' => '',
'uncategorizedcategories'	=> 'კატეგორიები კატეგორიის გარეშე',
#'uncategorizedcategories-summary' => '',
'uncategorizedimages' => 'სურათები კატეგორიის გარეშე',
#'uncategorizedimages-summary' => '',
'unusedcategories' => 'გამოუყენებელი კატეგორიები',
'unusedimages'	=> 'გამოუყენებელი სურათები',
'popularpages'	=> 'პოპულარული გვერდები',
#'popularpages-summary' => '',
'wantedcategories' => 'მოთხოვნილი კატეგორიები',
#'wantedcategories-summary' => '',
'wantedpages'	=> 'მოთხოვნილი გვერდები',
#'wantedpages-summary' => '',
#TODO: 'mostlinked'	=> 'Most linked to pages',
#'mostlinked-summary' => '',
#TODO: 'mostlinkedcategories' => 'Most linked to categories',
#'mostlinkedcategories-summary' => '',
'mostcategories' => 'ყველაზე მეტი კატეგორიის მქონე სტატიები',
#'mostcategories-summary' => '',
#TODO: 'mostimages'	=> 'Most linked to images',
#'mostimages-summary' => '',
'mostrevisions' => 'ყველაზე მეტად რედაქტირებული სტატიები',
#'mostrevisions-summary' => '',
'allpages'		=> 'ყველა გვერდი',
#'allpages-summary'	=> '',
#TODO: 'prefixindex'   => 'Prefix index',
#'prefixindex-summary' => '',
'randompage'	=> 'ნებისმიერი გვერდი',
#DONT: 'randompage-url'=> 'სპეციალური:Random',
'shortpages'	=> 'მოკლე გვერდები',
#'shortpages-summary'     => '',
'longpages'		=> 'გრძელი გვერდები',
#'longpages-summary'	=> '',
'deadendpages'  => 'ჩიხის გვერდები',
#'deadendpages-summary'	=> '',
#TODO: 'deadendpagestext'	=> 'The following pages do not link to other pages in this wiki.',
#TODO: 'protectedpages' => 'Protected pages',
#'protectedpages-summary' => '',
#TODO: 'protectedpagestext' => 'The following pages are protected from moving or editing',
#TODO: 'protectedpagesempty' => 'No pages are currently protected',
'listusers'		=> 'მომხმარებლების სია',
#'listusers-summary'	=> '',
'specialpages'	=> 'სპეციალური გვერდები',
#'specialpages-summary'	=> '',
'spheading'		=> 'სპეციალური გვერდები ყველა მომხმარებლისათვის',
'restrictedpheading'	=> 'შეზღუდული სპეციალური გვერდები',
'recentchangeslinked' => 'დაკავშირებული ცვლილებები',
#TODO: 'rclsub'		=> "(to pages linked from \"$1\")",
'newpages'		=> 'ახალი გვერდები',
#'newpages-summary'	=> '',
'newpages-username' => 'მომხმარებლის სახელი:',
'ancientpages'		=> 'ხანდაზმული გვერდები',
#'ancientpages-summary'	=> '',
'intl'		=> 'ენათშორისი ბმულები',
'move' => 'გადატანა',
'movethispage'	=> 'ამ გვერდის გადატანა',
'unusedimagestext' => '<p>გთხოვთ გაითვალისწინოთ, რომ შეიძლება სხვა ვიკი ზოგიერთ ამ გამოსახულებას იყენებს.</p>',
#TODO: 'unusedcategoriestext' => 'The following category pages exist although no other article or category make use of them.',

# Special:Allpages
'nextpage'          => 'შემდეგი გვერდი ($1)',
'prevpage'          => 'წინა გვერდი ($1)',
'allpagesfrom'		=> 'გვერდების ჩვენება დაწყებული:',
'allarticles'		=> 'ყველა სტატია',
'allinnamespace'	=> 'ყველა გვერდი ($1 სახელთა სივრცეში)',
'allnotinnamespace'	=> 'ყველა გვერდი ($1 სახელთა სივრცის გარეშე)',
'allpagesprev'		=> 'წინა',
'allpagesnext'		=> 'შემდეგი',
'allpagessubmit'	=> 'ჩვენება',
'allpagesprefix'	=> 'ასახე გვერდები პრეფიქსით:',
'allpagesbadtitle'	=> 'მოცემული გვერდის სათაური არასწორია ან აქვს ინტერვიკი ან ნათშორისი პრეფიქსი. 
იგი შესაძლოა შეიცავდეს ერთ ან მეტ სიმბოლოს, რომელიც არ შეიძლება გამოყენებულ იქნას სათაურში.',

# Special:Listusers

# Email this user

# Watchlist

# Delete/protect/revert

# restrictions (nouns)
'restriction-edit' => 'რედაქტირება',
#TODO: 'restriction-move' => 'Move',

# restriction levels


# Undelete
'undelete' => 'აჩვენე წაშლილი გვერდები',
'undeletepage' => 'იხილეთ და აღადგინეთ წაშლილი გვერდები',
'viewdeletedpage' => 'იხილეთ წაშლილი გვერდები',
'undeletepagetext' => 'მომდევნო გვრდები წაშლილია, მაგრამ ჯერ კიდევ არქივშია და 
შესაძლებელია აღდგენა. არქივი შესაძლებელია პერიოდულად გასუფთავდეს.',
'undeleteextrahelp' => "ამ მთლიანი გვერდის აღსადგენად, დატოვეთ ყველა მოსანიშნი უჯრა მოუნიშნავად და 
დააწკაპუნეთ '''''აღდგენა'''''. იმისათვის, რომ მოახდინოთ შერჩევითი აღდგენა მონიშნეთ უჯრები ჩასატარებელი 
ვერსიების შესაბამისად და დააწკაპუნეთ '''''აღდგენა'''''. '''''გადატვირთვაზე''''' დაწკაპუნებით გაუქმდება ყველა 
კომენტარის ველი და ყველა მოსანიშნი უჯრა.",
'undeletearticle' => 'აღადგინე წაშლილი გვერდი',
'undeleterevisions' => "$1 ვერსიები დაარქივებულია",
'undeletehistory' => 'თუ თქვენ აღადგენთ გვერდს, ყველა ვერსია აღდგება ისტორიაში. 
თუ ახალი გვერდი იგივე სახელით შეიქმნა მისი წაშლის შემდეგ, აღდგენილი 
ვერსიები გამოჩნდება წინა ისტორიაში და მიმდინარე ვერსია 
ავტომატურად არ ჩანაცვლდება.',
'undeletehistorynoadmin' => 'ეს სტატია წაშლილია. წაშლის მიზეზი ნაჩვენებია მოკლე ანოტაციაში ქვემოთ, იმ 
მომხმარებელთა დეტალებთან ერთად ვინც რედაქტირება გაუკეთა ამ გვერდს წაშლის წინ. 
იმ წაშლილი ტექსტების აქტუალური ვერსიები მიღწევადია მხოლოდ ადმინისტრატორებისათვის.',
#TODO: 'undelete-revision' => 'Deleted revision of $1 from $2:',
/*#TODO: 'undeleterevision-missing' => "Invalid or missing revision. You may have a bad link, or the
revision may have been restored or removed from the archive.",*/
'undeletebtn' => 'აღდგენა',
'undeletereset' => 'გადატვირთეთ',
'undeletecomment' => 'კომენტარი:',
'undeletedarticle' => 'აღდგენილია "[[$1]]"',
'undeletedrevisions' => "$1 ვერსია აღდგენილია",
'undeletedrevisions-files' => "$1 ვერსია და $2 ფაილი აღდგენილია",
'undeletedfiles' => "$1 ფაილი აღდგენილია",
'cannotundelete' => 'აღდგენა ვერ შედგა; შესაძლოა უკვე ვიღაცამ აღადგინა ეს გვერდი.',
'undeletedpage' => "<big>'''$1 აღდგენილია'''</big>

უკანასკნელი წაშლილთა და აღდგენის სია შეგიძლიათ ნახოთ [[Special:Log/delete|წაშლილთა სიაში]].",
#TODO: 'undelete-header' => 'See [[Special:Log/delete|the deletion log]] for recently deleted pages.',
#TODO: 'undelete-search-box' => 'Search deleted pages',
#TODO: 'undelete-search-prefix' => 'Show pages starting with:',
'undelete-search-submit' => 'ძიება',
#TODO: 'undelete-no-results' => 'No matching pages found in the deletion archive.',

# Namespace form on various pages
'namespace' => 'სახელთა სივრცე:',
'invert' => 'ყველა, მონიშნულის გარდა',

# Contributions

# What links here
#
'whatlinkshere'	=> 'სადაა მითითებული ეს გვერდი',
#'whatlinkshere-summary'	=> '',
#'whatlinkshere-barrow' => '&lt;',
'notargettitle' => 'სამიზნე არაა',
'notargettext'	=> 'თქვენ არ მიუთითეთ სამიზნე გვერდი ან მომხმარებელი 
ამ ფუნქციის შესასრულებლად.',
#TODO: 'linklistsub'	=> '(List of links)',
#TODO: 'linkshere' => "The following pages link to '''[[:$1]]''':",
'nolinkshere' => "'''[[:$1]]'''-ზე ბმული არ არის.",
#TODO: 'isredirect'	=> 'redirect page',
#TODO: 'istemplate'	=> 'inclusion',

# Block/unblock IP

# Developer tools

# Make sysop

# Move page
#
'movepage'		=> 'გვერდის გადატანა',
/*TODO: 'movepagetext'	=> 'Using the form below will rename a page, moving all
of its history to the new name.
The old title will become a redirect page to the new title.
Links to the old page title will not be changed; be sure to
check for double or broken redirects.
You are responsible for making sure that links continue to
point where they are supposed to go.

Note that the page will \'\'\'not\'\'\' be moved if there is already
a page at the new title, unless it is empty or a redirect and has no
past edit history. This means that you can rename a page back to where
it was just renamed from if you make a mistake, and you cannot overwrite
an existing page.

<b>WARNING!</b>
This can be a drastic and unexpected change for a popular page;
please be sure you understand the consequences of this before
proceeding.',*/
/*TODO: 'movepagetalktext' => 'The associated talk page will be automatically moved along with it \'\'\'unless:\'\'\'
*A non-empty talk page already exists under the new name, or
*You uncheck the box below.

In those cases, you will have to move or merge the page manually if desired.',*/
'movearticle'	=> 'გვერდის გადატანა',
'movenologin'	=> 'რეგისტრაცია ვერ გაიარა',
/*TODO: 'movenologintext' => "You must be a registered user and [[Special:Userlogin|logged in]]
to move a page.",*/
'newtitle'		=> 'ახალი სათაური',
#TODO: 'move-watch' => 'Watch this page',
'movepagebtn'	=> 'გვერდის გადატანა',
#TODO: 'pagemovedsub'	=> 'Move succeeded',
'pagemovedtext' => "გვერდი \"[[$1]]\" გადავიდა \"[[$2]]\".",
'articleexists' => 'ამ დასახელების გვერდი უკვე არსებობს, 
ან თქვენს მიერ მითითებული დასახელება არასწორია. 
თუ შეიძლება, მიუთითეთ სხვა სახელი.',
#TODO: 'talkexists'	=> "'''The page itself was moved successfully, but the talk page could not be moved because one already exists at the new title. Please merge them manually.'''",
'movedto'		=> 'გადატანილია',
'movetalk'		=> 'დაკავშირებული განხილვის გადატანა',
#TODO: 'talkpagemoved' => 'The corresponding talk page was also moved.',
#TODO: 'talkpagenotmoved' => 'The corresponding talk page was <strong>not</strong> moved.',
'1movedto2'		=> '[[$1]] გადატანილია [[$2]]-ზე',
'1movedto2_redir' => '[[$1]] გადატანილია [[$2]]-ზე გადამისამართებულ გვერდში',
'movelogpage' => 'გადატანის ჟურნალი',
#TODO: 'movelogpagetext' => 'Below is a list of page moved.',
'movereason'	=> 'მიზეზი',
#TODO: 'revertmove'	=> 'revert',
'delete_and_move' => 'წაშლა და გადატანა',
'delete_and_move_text'	=>
'==საჭიროა წაშლა==

სტატია დასახელებით "[[$1]]" უკვე არსებობს. გსურთ მისი წაშლა გადატანისთვის ადგილის დასათმობად?',
'delete_and_move_confirm' => 'დიახ, წაშალეთ ეს გვერდი',
'delete_and_move_reason' => 'წაშლილია გადატანისთვის ადგილის დასათმობად',
#TODO: 'selfmove' => "Source and destination titles are the same; can't move a page over itself.",
#TODO: 'immobile_namespace' => "Source or destination title is of a special type; cannot move pages from and into that namespace.",*/

# Export

# Namespace 8 related

'allmessages'   => 'სისტემური შეტყობინება',
'allmessagesname' => 'დასახელება',
'allmessagesdefault' => 'სტანდარტული ტექსტი',
'allmessagescurrent' => 'მიმდინარე ტექსტი',
'allmessagestext'       => 'ეს არის სახელთა სივრცე მედიავიკიში არსებული სისტემური შეტყობინებების ჩამონათვალი.',
'allmessagesnotsupportedUI' => 'თქვენს ამჟამინდელ ინტერფეისის ენას <b>$1</b> არ აქვს სპეციალური:AllMessages-ის უზრუნველყოფა ამ საიტზე.',
'allmessagesnotsupportedDB' => 'სპეციალური:AllMessages-ის უზრუნველყოფა არ ხდება, ვინაიდან wgUseDatabaseMessages გამორთულია.',
'allmessagesfilter' => 'ფილტრი შეტყობინების სახელის მიხედვით:',
'allmessagesmodified' => 'აჩვენე მხოლოდ შეცვლილი',

# Thumbnails
'thumbnail-more'	=> 'გაზარდეთ',
#TODO: 'missingimage'		=> '<b>Missing image</b><br /><i>$1</i>',
'filemissing'		=> 'ფაილი ვერ მოიძებნა',
'thumbnail_error'   => 'ესკიზის შექმნის შეცდომა: $1',

# Special:Import

# import log

# tooltip help for some actions, most are in Monobook.js

# stylesheets

# Metadata

# Attribution
'anonymous' => '{{SITENAME}}-ის ანონიმური მომხმარებლები',
'siteuser' => '{{SITENAME}} მომხმარებელი $1',
#TODO: 'lastmodifiedatby' => 'This page was last modified $2, $1 by $3.',	// $1 date, $2 time. $3 user
'and' => 'და',
#TODO: 'othercontribs' => 'Based on work by $1.',
'others' => 'სხვები',
'siteusers' => '{{SITENAME}} მომხმარებლები $1',
#TODO: 'creditspage' => 'წვლილი',
#TODO: 'nocredits' => 'There is no credits info available for this page.',

# Spam protection

# Info page

# Math options

# Patrolling

# Monobook.js: tooltips and access keys for monobook

# image deletion

# browsing diffs

# labels for User: and Title: on Special:Log pages
'specialloguserlabel' => 'მომხმარებელი:',
'speciallogtitlelabel' => 'სათაური:',

'passwordtooshort' => 'თქვენი პაროლი ძალიან მოკლეა. მასში უნდა შედიოდეს არანაკლებ $1 ასო-ნიშანი.',

# Media Warning
#TODO: 'mediawarning' => '\'\'\'Warning\'\'\': This file may contain malicious code, by executing it your system may be compromised.<hr />',

'fileinfo' => '$1KB, MIME ტიპი: <code>$2</code>',

# Metadata

# Exif tags

# Make & model, can be wikified in order to link to the camera and model name

# Exif attributes

# external editor support

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'ყველა',
'imagelistall' => 'ყველა',
'watchlistall1' => 'ყველა',
'watchlistall2' => 'ყველა',
'namespacesall' => 'ყველა',

# E-mail address confirmation

# Inputbox extension, may be useful in other contexts as well
'tryexact' => 'სცადეთ ზუსტი ძიება',
'searchfulltext' => 'სრული ტექსტის ძიება',
'createarticle' => 'სტატიის შექმნა',

# Scary transclusion

# Trackbacks

# delete conflict

'deletedwhileediting' => '[[მომხმარებელი:$1|$1]] მომხმარებელმა ([[მომხმარებელი განხილვა:$1|განხილვა]]) წაშალა თქვენი რედაქტირების შემდეგ. მიზეზი:
: \'\'$2\'\'
გთხოვთ დაადასტუროთ რომ ნამდვილად გსურთ ამ გვერდის თავიდან შექმნა.',
#TODO: 'recreate' => 'Recreate',

#TODO: 'unit-pixel' => 'px',

# HTML dump

# action=purge
#TODO: 'confirm_purge' => "Clear the cache of this page?\n\n$1",
#TODO: 'confirm_purge_button' => 'OK',

'youhavenewmessagesmulti' => "თქვენ გაქვთ ახალი შეტყობინება $1-ზე",

#TODO: 'searchcontaining' => "Search for articles containing ''$1''.",
#TODO: 'searchnamed' => "Search for articles named ''$1''.",
'articletitles' => "სტატიები დაწყებული ''$1''-ით",
'hideresults' => 'შედეგების დამალვა',

# DISPLAYTITLE
'displaytitle' => '(ამ გვერდის ბმული როგორც [[$1]])',

'loginlanguagelabel' => 'ენა: $1',

# Multipage image navigation

# Table pager
#TODO: 'ascending_abbrev' => 'asc',
#TODO: 'descending_abbrev' => 'desc',
'table_pager_next' => 'შემდეგი გვერდი',
'table_pager_prev' => 'წინა გვერდი',
'table_pager_first' => 'პირველი გვერდი',
'table_pager_last' => 'ბოლო გვერდი',
#TODO: 'table_pager_limit' => 'Show $1 items per page',
'table_pager_limit_submit' => 'აჩვენე',
'table_pager_empty' => 'შედეგები არაა',

# Auto-summaries
'autosumm-blank' => 'გვერდი დაცარიელდა',
'autosumm-replace' => 'შინაარსი შეიცვალა \'$1\'-ით',
'autoredircomment' => 'გადამისამართება [[$1]]-ზე', # This should be changed to the new naming convention, but existed beforehand.
'autosumm-new' => 'ახალი გვერდი: $1',

# Size units
'size-bytes' => '$1 ბ',
'size-kilobytes' => '$1 კბ',
'size-megabytes' => '$1 მბ',
'size-gigabytes' => '$1 გბ',


);

?>

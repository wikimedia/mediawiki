<?php
/** Manx (Gaelg)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Alison
 * @author Kaganer
 * @author MacTire02
 * @author Shimmin Beg
 */

$namespaceNames = array(
	NS_MEDIA            => 'Meanyn',
	NS_SPECIAL          => 'Er_lheh',
	NS_TALK             => 'Resooney',
	NS_USER             => 'Ymmydeyr',
	NS_USER_TALK        => 'Resooney_ymmydeyr',
	NS_PROJECT_TALK     => 'Resooney_$1',
	NS_FILE             => 'Coadan',
	NS_FILE_TALK        => 'Resooney_coadan',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Resooney_MediaWiki',
	NS_TEMPLATE         => 'Clowan',
	NS_TEMPLATE_TALK    => 'Resooney_clowan',
	NS_HELP             => 'Cooney',
	NS_HELP_TALK        => 'Resooney_cooney',
	NS_CATEGORY         => 'Ronney',
	NS_CATEGORY_TALK    => 'Resooney_ronney',
);

$messages = array(
# User preference toggles
'tog-underline' => 'Cur linnaghyn fo chianglaghyn:',
'tog-justify' => 'Cur meeryn ayns un linney',
'tog-hideminor' => "Follee myn-arraghyn ayns caghlaaghyn s'noa",
'tog-hidepatrolled' => "Follee arraghyn er nyn scrutaghey ayns caghlaaghyn s'noa",
'tog-newpageshidepatrolled' => "Follee duillagyn er nyn scrutaghey 'sy rolley duillagyn noa",
'tog-extendwatchlist' => 'Lheeadee y rolley arrey dys taishbyney dagh caghlaa bentyn da',
'tog-numberheadings' => 'Cur earrooyn gyn smooinaght er kione-linnaghyn',
'tog-showtoolbar' => 'Taishbyn barr greieyn (ta feme ec er JavaScript)',
'tog-editondblclick' => 'Reagh duillagyn lesh crig dooblit (ta feme ec er JavaScript)',
'tog-rememberpassword' => 'Cooinnee my fys loggal stiagh er y cho-earrooder shoh (rish wheesh as $1 {{PLURAL:$1|laa|laa|laa|laaghyn}})',
'tog-watchcreations' => 'Cur duillagyn ta crooit aym rish my rolley arrey',
'tog-watchdefault' => 'Cur duillagyn ta reaghit aym rish my rolley arrey',
'tog-watchmoves' => 'Cur duillagyn ta scughit aym rish my rolley arrey',
'tog-watchdeletion' => 'Cur duillagyn ta scryssit aym rish my rolley arrey',
'tog-minordefault' => 'Myr roie-hoieaghey, cowree dagh arraghey myr myn-arraghey',
'tog-previewontop' => 'Taishbyn y roie-haishbynys roish y chishtey reaghee',
'tog-previewonfirst' => 'Taishbyn roie-haishbynys lurg y chied reaghey',
'tog-nocache' => 'Ny sauail duillagyn ayns tasht y jeeagheyder',
'tog-enotifwatchlistpages' => 'Cur post-l dou tra ta duillag er y rolley arrey aym goll er reaghey',
'tog-enotifusertalkpages' => 'Cur post-l dou my vees y duillag ymmydeyr aym caghlaa',
'tog-enotifminoredits' => 'Cur dou post-l er myn-arraghey duillagyn chammah',
'tog-enotifrevealaddr' => 'Taishbyney my enmys post-l ayns postyn-l fogrey',
'tog-shownumberswatching' => 'Taishbyn earroo ny h-ymmydeyryn ta freill arrey',
'tog-oldsig' => "Roie-haishbynys jeh'n screeuys t'ayn hannah:",
'tog-fancysig' => 'Stiur y screeuys myr wikiteks (gyn kiangley seyr-obbragh)',
'tog-watchlisthideown' => 'Follee my arraghyn hene er my rolley arrey',
'tog-watchlisthidebots' => 'Follee arraghyn botyn er my rolley arrey',
'tog-watchlisthideminor' => 'Follee myn-arraghyn er my rolley arrey',
'tog-watchlisthidepatrolled' => 'Follee arraghyn er nyn scrutaghey er my rolley arrey',
'tog-ccmeonemails' => 'Cur coip dou jeh dagh post-l verrym da ymmydeyr elley',
'tog-showhiddencats' => 'Taishbyn ny ronnaghyn follit',
'tog-useeditwarning' => 'Cur raaue dou my ta mee faagail duillag reaghey gyn sauail yn obbyr jeant aym',

'underline-always' => 'Dagh keayrt',
'underline-never' => 'Ny jean eh arragh',
'underline-default' => 'Rere roie-hoiaghey y yeeagheyder',

# Font style option in Special:Preferences
'editfont-style' => 'Sorçh clou yn rheynn reaghee',
'editfont-default' => 'Rere roie-hoiaghey y yeeagheyder',
'editfont-monospace' => 'Clou un-lheead',
'editfont-sansserif' => 'Clou gyn trasnane',
'editfont-serif' => 'Clou lesh trasnane',

# Dates
'sunday' => 'Jedoonee',
'monday' => 'Jelune',
'tuesday' => 'Jemayrt',
'wednesday' => 'Jecrean',
'thursday' => 'Jerdein',
'friday' => 'Jeheiney',
'saturday' => 'Jesarn',
'sun' => 'Jedoonee',
'mon' => 'Jelune',
'tue' => 'Jemayrt',
'wed' => 'Jecrean',
'thu' => 'Jerdein',
'fri' => 'Jeheiney',
'sat' => 'Jesarn',
'january' => 'Jerrey Geuree',
'february' => 'Toshiaght Arree',
'march' => 'Mayrnt',
'april' => 'Averil',
'may_long' => 'Boaldyn',
'june' => 'Mean Souree',
'july' => 'Jerrey Souree',
'august' => 'Luanistyn',
'september' => 'Mean Fouyir',
'october' => 'Jerrey Fouyir',
'november' => 'Mee Houney',
'december' => 'Mee ny Nollick',
'january-gen' => 'Jerrey Geuree',
'february-gen' => 'Toshiaght Arree',
'march-gen' => 'Mayrnt',
'april-gen' => 'Averil',
'may-gen' => 'Boaldyn',
'june-gen' => 'Mean Souree',
'july-gen' => 'Jerrey Souree',
'august-gen' => 'Luanistyn',
'september-gen' => 'Mean Fouyir',
'october-gen' => 'Jerrey Fouyir',
'november-gen' => 'Mee Houney',
'december-gen' => 'Mee ny Nollick',
'jan' => 'JGeu',
'feb' => 'TArr',
'mar' => 'Mayrnt',
'apr' => 'Ave',
'may' => 'Boal',
'jun' => 'MSou',
'jul' => 'JSou',
'aug' => 'Luan',
'sep' => 'MFou',
'oct' => 'JFou',
'nov' => 'Soun',
'dec' => 'Noll',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Ronney|Ronnaghyn}}',
'category_header' => 'Duillagyn \'sy ronney "$1"',
'subcategories' => 'Fo-ronnaghyn',
'category-media-header' => 'Meanyn \'sy ronney "$1"',
'category-empty' => "''Cha nel duillagyn ny meanyn 'sy ronney shoh ec y traa t'ayn.''",
'hidden-categories' => '{{PLURAL:$1|Ronney follit|Ronnaghyn follit}}',
'hidden-category-category' => 'Ronnaghyn follit',
'category-subcat-count' => "{{PLURAL:$2|Ta {{PLURAL:$1|ny $1 fo-ronney|yn $1 'o-ronney|yn $1 'o-ronney|ny $1 fo-ronnaghyn}} shoh ec y ronney shoh, jeh'n lane-sym $2.}}",
'category-subcat-count-limited' => "{{PLURAL:$1|Ta{{PLURAL:$1|&nbsp;ny $1 fo-ronney|'n $1 'o-ronney|'n $1 'o-ronney|&nbsp;ny $1 fo-ronnaghyn}} shoh ec y ronney shoh.}}",
'category-article-count' => "Ta {{PLURAL:$1|yn $1 duillag|yn $1 duillag|yn $1 ghuillag| ny $1 duillagyn}} heese 'sy ronney shoh, jeh'n lane-sym $2.",
'category-article-count-limited' => "Ta{{PLURAL:$1|'n $1 duillag|'n $1 duillag|'n $1 ghuillag|&nbsp;ny $1 duillagyn}} heese 'sy ronney shoh.",
'category-file-count-limited' => "Ta{{PLURAL:$1|'n $1 coadan|'n $1 choadan|'n $1 choadan|&nbsp;ny $1 coadanyn}} heese 'sy ronney shoh.",
'listingcontinuesabbrev' => 'tooil.',
'index-category' => 'Duillagyn er ayndagh',
'noindex-category' => 'Duillagyn nagh vel er ayndagh',
'broken-file-category' => 'Duillagyn as kianglaghyn brishtey coadan oc',

'about' => 'Mychione',
'article' => 'Duillag chummal',
'newwindow' => "(t'eh foshley ayns uinnag elley)",
'cancel' => 'Doll magh',
'moredotdotdot' => 'Tooilley...',
'mypage' => 'My ghuillag',
'mytalk' => 'My resoonaght',
'anontalk' => "Cur loayrtys da'n IP shoh",
'navigation' => 'Stiureydys',
'and' => '&#32;as',

# Cologne Blue skin
'qbfind' => 'Fow',
'qbbrowse' => 'Ronsee',
'qbedit' => 'Reagh',
'qbpageoptions' => 'Yn duillag shoh',
'qbmyoptions' => 'My ghuillagyn',
'qbspecialpages' => 'Duillagyn er lheh',
'faq' => 'FC',
'faqpage' => 'Project:FC',

# Vector skin
'vector-action-addsection' => 'Cur cooish noa rish',
'vector-action-delete' => 'Scryss',
'vector-action-move' => 'Scugh',
'vector-action-protect' => 'Coadee',
'vector-action-undelete' => 'Jee-scryss',
'vector-action-unprotect' => 'Caghlaa coadey',
'vector-view-create' => 'Croo',
'vector-view-edit' => 'Reagh',
'vector-view-history' => 'Jeeagh er shennaghys',
'vector-view-view' => 'Lhaih',
'vector-view-viewsource' => 'Jeeagh er bun',
'actions' => 'Jantyssyn',
'namespaces' => 'Reamyssyn',
'variants' => 'Cummaghyn elley',

'errorpagetitle' => 'Marranys',
'returnto' => 'Gow er ash gys $1.',
'tagline' => 'Ass {{SITENAME}}.',
'help' => 'Cooney',
'search' => 'Ronsee',
'searchbutton' => 'Ronsee',
'go' => 'Gow',
'searcharticle' => 'Gow',
'history' => 'Shennaghys y duillag',
'history_short' => 'Shennaghys',
'printableversion' => 'Lhieggan clou',
'permalink' => 'Kiangley beayn',
'print' => 'Clou',
'view' => 'Lhaih',
'edit' => 'Reagh',
'create' => 'Croo',
'editthispage' => 'Reagh yn duillag shoh',
'create-this-page' => 'Croo yn duillag shoh',
'delete' => 'Scryss',
'deletethispage' => 'Scryss y duillag shoh',
'undelete_short' => 'Jee-scryss {{PLURAL:$1|$1 caghlaa|$1 chaghlaa|$1 chaghlaa|$1 caghlaaghyn}}',
'viewdeleted_short' => 'Jeeagh er {{PLURAL:$1|$1 caghlaa scryssit magh|$1 chaghlaa scryssit magh|$1 chaghlaa scryssit magh|$1 caghlaaghyn scryssit magh}}',
'protect' => 'Coadee',
'protect_change' => 'ceaghil',
'protectthispage' => 'Coadee yn duillag shoh',
'unprotect' => 'Jee-choadee',
'unprotectthispage' => 'Jee-choadee yn duillag shoh',
'newpage' => 'Duillag noa',
'talkpage' => 'Resoon magh y duillag shoh',
'talkpagelinktext' => 'Resoonaght',
'specialpage' => 'Duillag er lheh',
'personaltools' => 'Greienyn persoonagh',
'postcomment' => 'Meer noa',
'articlepage' => 'Jeeagh er y duillag chummal',
'talk' => 'Resoonaght',
'views' => 'Reayrtyn',
'toolbox' => 'Kishtey greie',
'userpage' => 'Jeeagh er duillag yn ymmydeyr',
'projectpage' => 'Jeeagh er duillag ny shalee',
'imagepage' => 'Jeeagh er duillag y choadan',
'mediawikipage' => 'Jeeagh er duillag ny çhaghteraght',
'templatepage' => 'Jeeagh er duillag y chlowan',
'viewhelppage' => 'Jeeagh er y duillag choonee',
'categorypage' => 'Jeeagh er duillag ny ronnaghyn',
'viewtalkpage' => 'Jeeagh er resoonaght',
'otherlanguages' => 'Ayns çhengaghyn elley',
'redirectedfrom' => '(Aa-enmyssit ass $1)',
'redirectpagesub' => 'Duillag aa-enmys',
'lastmodifiedat' => 'Hie yn duillag shoh er ny reaghey er $1, ec $2.',
'protectedpage' => 'Duillag choadit',
'jumpto' => 'Gow gys:',
'jumptonavigation' => 'stiureydys',
'jumptosearch' => 'ronsee',
'pool-errorunknown' => 'Doilleeid gyn enney',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage).
'aboutsite' => 'Mychione {{SITENAME}}',
'aboutpage' => 'Project:Mychione',
'copyright' => 'Ta stoo ry-gheddyn rere $1.',
'copyrightpage' => '{{ns:project}}:Coip-chiartyn',
'currentevents' => 'Cooishyn y laa',
'currentevents-url' => 'Project:Cooishyn y laa',
'disclaimers' => 'Jiooldeyderyn',
'disclaimerpage' => 'Project:Obbalys cadjin',
'edithelp' => 'Cooney reaghee',
'helppage' => 'Help:Cummal',
'mainpage' => 'Ard-ghuillag',
'mainpage-description' => 'Ard-ghuillag',
'policy-url' => 'Project:Polasee',
'portal' => 'Ynnyd y phobble',
'portal-url' => 'Project:Ynnyd y phobble',
'privacy' => 'Polasee preevaadjys',
'privacypage' => 'Project:Polasee preevaadjys',

'badaccess' => 'Marranys y chied',

'versionrequired' => 'Ta feme ayd er lhieggan $1 VediaWiki',

'ok' => 'OK',
'retrievedfrom' => 'Feddynit ass "$1"',
'youhavenewmessages' => 'Ta $1 ayd ($2).',
'newmessageslink' => 'çhaghteraghtyn noa',
'newmessagesdifflink' => "caghlaa s'jerree",
'youhavenewmessagesmulti' => 'Ta çhaghteraghtyn noa ayd er $1',
'editsection' => 'reagh',
'editold' => 'reagh',
'viewsourceold' => 'jeeagh er bun',
'editlink' => 'reagh',
'viewsourcelink' => 'jeeagh er bun',
'editsectionhint' => 'Reagh y rheynn: $1',
'toc' => 'Cummal',
'showtoc' => 'taishbyn',
'hidetoc' => 'follee',
'collapsible-collapse' => 'Follee',
'collapsible-expand' => 'Taishbyn',
'viewdeleted' => 'Jeeagh er $1?',
'feedlinks' => 'Stroo:',
'site-rss-feed' => 'Scoltey RSS $1',
'site-atom-feed' => 'Scoltey Atom $1',
'page-rss-feed' => 'Scoltey RSS "$1"',
'page-atom-feed' => 'Atom Feed "$1"',
'red-link-title' => '$1 (cha nel y duillag ayn)',
'sort-descending' => 'Sorçhee veih smoo',
'sort-ascending' => 'Sorçhee veih sloo',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Duillag',
'nstab-user' => 'Duillag ymmydeyr',
'nstab-special' => 'Er lheh',
'nstab-project' => 'Duillag halee',
'nstab-image' => 'Coadan',
'nstab-mediawiki' => 'Çhaghteraght',
'nstab-template' => 'Clowan',
'nstab-help' => 'Duillag choonee',
'nstab-category' => 'Ronney',

# Main script and global functions
'nosuchaction' => 'Cha nel lheid yn obbyr ayn',
'nosuchspecialpage' => 'Cha nel y duillag er lheh shoh ayn',

# General errors
'error' => 'Marranys',
'databaseerror' => "Marranys 'sy stoyr-fysseree",
'readonly' => 'Stoyr-fysseree fo ghlass',
'missing-article' => 'Cha row teks duillag, lhisagh ve er ngeddyn lesh yn ennym "$1" $2, feddynit ec y stoyr-fysseree.

Dy cadjin, shen eiyrtys criggal er kiangley caghlaaee ny shennaghys ta ass daayt as kianglt rish dys duillag va scryssit magh roish shoh.

Mannagh vel, foddee dy vel doghan \'sy chooid vog feddynit magh eu.<br />
Cur-shiu coontey jeh da [[Special:ListUsers/sysop|reireyder]], as goaill stiagh yn URL my sailliu.',
'missingarticle-rev' => '(caghlaa#: $1)',
'missingarticle-diff' => '(Caghlaa: $1, $2)',
'internalerror' => 'Marranys ynveanagh',
'internalerror_info' => 'Marranys yn-veanagh: $1',
'filenotfound' => 'Cha dooar shin y coadan "$1".',
'fileexistserror' => 'Cha dod shin screeu da\'n choadan "$1": t\'eh ayn hannah',
'badarticleerror' => 'Cha nod oo jannoo yn obbyr shen er y duillag shoh.',
'cannotdelete-title' => 'Gyn jargaght y duillag "$1" y scryssey',
'badtitle' => 'Drogh-ennym',
'badtitletext' => "Va marranys ayn bentyn rish ennym y duillag v'ou shirrey. Foddee dy row eh follym ny gyn vree, ny kianglt dy moal myr kiangley eddyr-wiki. Foddee dy vel cowraghyn 'syn ennym nagh nod oo jannoo ymmyd jej ayns enmyn.",
'viewsource' => 'Jeeagh er bun',
'viewsource-title' => 'Jeeagh er bun $1',
'actionthrottled' => 'Obbyr er ny phlooghey',
'actionthrottledtext' => "Myr saase noi-spam, cha nod oo jannoo yn obbyr shoh rouyr keayrtyn ayns tammylt beg, as t'ou er roshtyn yn earroo smoo.  Jean eab noa dy gerrid, my saillt.",
'protectedpagetext' => "Ta'n duillag shoh fo ghlass, as cha nod oo eshyn y reaghey.",
'viewsourcetext' => 'Foddee oo jeeagh as jean aascreeuyn er bun ny duillag shoh:',
'namespaceprotected' => "Cha nel kiart ayd duillagyn 'sy reamys '''$1''' y reaghey.",
'ns-specialprotected' => 'Cha nod oo reaghey duillagyn er lheh.',

# Virus scanner
'virus-badscanner' => 'Drogh reaghys: ronseyder noi-veerys gyn enney: "$1"',
'virus-scanfailed' => 'vrish y ronsaght (coad $1)',
'virus-unknownscanner' => 'ronseyder noi-veerys gyn enney',

# Login and logout pages
'yourname' => "Dt'ennym ymmydeyr:",
'yourpassword' => 'Fockle yn arrey:',
'yourpasswordagain' => "Aascreeu dt'ockle arrey:",
'remembermypassword' => "Cooinnee m'ockle arrey (rish wheesh as $1 {{PLURAL:$1|laa|laa|laa|laaghyn}})",
'login' => 'Log stiagh',
'nav-login-createaccount' => 'Log stiagh / croo coontys',
'loginprompt' => 'Shegin dhyt cur pooar da minniagyn dy loggal stiagh ayns {{SITENAME}}.',
'userlogin' => 'Log stiagh / croo coontys',
'userloginnocreate' => 'Log stiagh',
'logout' => 'Log magh',
'userlogout' => 'Log magh',
'notloggedin' => 'Cha nel ou loggit stiagh',
'nologin' => "Nagh vel loggal stiagh ayd? '''$1'''.",
'nologinlink' => 'Croo coontys',
'createaccount' => 'Croo coontys',
'gotaccount' => "Vel coontys ayd hannah? '''$1'''.",
'gotaccountlink' => 'Log stiagh',
'createaccountmail' => "Croo fockle arrey shallidagh gyn tort as cur eh da'n post-l reiht ayd",
'createaccountreason' => 'Fa:',
'loginerror' => 'Marranys loggal stiagh',
'createaccounterror' => 'Cha nod shin croo coontys: $1',
'noname' => 'Cha honree uss ennym ymmydeyr fondagh.',
'loginsuccesstitle' => "T'ou loggit stiagh",
'loginsuccess' => "'''T'ou loggit stiagh ayns {{SITENAME}} myr \"\$1\".'''",
'nosuchuser' => 'Cha nel ymmydeyr ayn lesh yn ennym "$1".<br />
Ta case ny lettyryn ayns enmyn ymmydeyr dendeaysagh.<br />
Cur streean er dty lettraghey, ny [[Special:UserLogin/signup|croo coontys noa]].',
'nosuchusershort' => 'Cha nel ymmydeyr ayn lesh yn ennym "$1".
Cur streean er dty lettraghey.',
'nouserspecified' => 'Shegin diu ennym ymmydeyr y honraghey.',
'login-userblocked' => "Ta'n ymmydeyr shoh fo ghlass.  Cha lowit {{GENDER:|da|jee|da}} loggal stiagh.",
'wrongpassword' => 'Va fockle arrey neuchiart screeuit eu.<br />
Screeu eh reesht eh.',
'wrongpasswordempty' => "Va'n fockle arrey screeuit bane.
Aascreeu, my sailliu.",
'mailmypassword' => "Cur dou m'ockle arrey er post-L",
'passwordremindertitle' => 'Fockle arrey noa shallidagh son {{SITENAME}}',
'noemail' => 'Cha nel enmys post-L recortyssit da\'n ymmydeyr "$1".',
'noemailcreate' => 'Shegin diu enmys post-l fondagh y honraghey',
'passwordsent' => 'Va fockle arrey noa currit da enmys post-L ta recortyssit da "$1".<br />
Tra t\'eh eu, log stiagh my sailliu.',
'acct_creation_throttle_hit' => "Va {{PLURAL:$1|$1 coontys|$1 choontys|$1 choontys|$1 coontyssyn}} crooit ec keayrtee da'n wiki shoh lesh yn enmys IP eu 'sy laa ain, as cha nel ny smoo coontyssyn y chroo lhiggalagh.<br />
Myr eiyrtys, cha nod keayrtee lesh yn enmys IP shoh ny smoo coontyssyn noa y chroo nish.",
'emailconfirmlink' => 'Shickyree yn enmys post-l eu',
'accountcreated' => 'Coontys crooit',
'accountcreatedtext' => 'Ta coontys ymmydeyr da $1 crooit.',
'createaccount-title' => 'Coontys crooit son {{SITENAME}}',
'loginlanguagelabel' => 'Çhengey: $1',

# Change password dialog
'resetpass' => 'Caghlaa fockle yn arrey',
'resetpass_header' => 'Caghlaa fockle arrey yn choontys',
'oldpassword' => 'Shenn-ockle yn arrey:',
'newpassword' => 'Fockle noa yn arrey:',
'retypenew' => "Aascreeu d'ockle arrey noa:",
'resetpass_submit' => 'Cur sheese fockle yn arrey as loggal stiagh',
'resetpass_forbidden' => 'Cha nod focklyn arrey y arraghey er {{SITENAME}}',
'resetpass-submit-loggedin' => 'Caghlaa fockle yn arrey',

# Special:PasswordReset
'passwordreset-username' => "Dt'ennym ymmydeyr:",
'passwordreset-email' => 'Enmys post-L:',
'passwordreset-emailelement' => 'Ennym ymmydeyr: $1
Fockle arrey shallidagh: $2',
'passwordreset-emailsent' => 'Ta post-l cur gys cooinaghtyn er ny chur dhyt.',
'passwordreset-emailsent-capture' => 'Ta post-l cur gys cooinaghtyn er ny chur dhyt, as eshyn heese.',
'passwordreset-emailerror-capture' => 'Ta post-l cur gys cooinaghtyn er ny chur dhyt, as eshyn heese, agh cha rosh eh yn ymmydeyr: $1',

# Special:ChangeEmail
'changeemail-none' => '(gyn)',

# Edit page toolbar
'bold_sample' => 'Clou trome',
'bold_tip' => 'Clou trome',
'italic_sample' => 'Clou iddaalagh',
'italic_tip' => 'Clou iddaalagh',
'link_sample' => 'Ennym y chianglee',
'link_tip' => 'Kiangley ynveanagh',
'extlink_sample' => 'http://www.example.com ennym chianglee',
'extlink_tip' => 'Kiangley mooie (cooiney roie-ockle http://)',
'headline_sample' => 'Teks y chione-linney',
'headline_tip' => 'Kione-linney corrym 2',
'nowiki_sample' => 'Cur stiagh teks gyn cummey ayns shoh',
'nowiki_tip' => 'Ny chur tastey da cummey wiki',
'image_tip' => 'Coadan jingit',
'media_tip' => 'Kiangley yn choadan',
'sig_tip' => "Dt'ennym screeuit lesh clouag hraa",
'hr_tip' => 'Linney cochruinnagh (ymmyd dy spaarailagh)',

# Edit pages
'summary' => 'Giare-choontey:',
'subject' => 'Cooish/kione-linney:',
'minoredit' => 'She myn-reaghey eh shoh',
'watchthis' => 'Freill arrey er y duillag shoh',
'savearticle' => 'Sauail y duillag',
'preview' => 'Roie-haishbynys',
'showpreview' => 'Taishbyn roie-haishbynys',
'showlivepreview' => 'Roie-haishbynys bio',
'showdiff' => 'Taishbyn caghlaaghyn',
'anoneditwarning' => "'''Raaue:''' Cha nel oo loggit stiagh.
Bee dt'enmys IP recortyssit ayns shennaghys reaghee yn duillag shoh.",
'missingcommenttext' => 'Taggloo er heese, my sailt.',
'summary-preview' => 'Roie-haishbynys y ghiare-choontey:',
'subject-preview' => 'Roie-haishbynys cooish/kione-linney:',
'blockedtitle' => "Ta'n ymmydeyr glast magh",
'blockedtext' => "'''Ta dt'ennym ymmydeyr ny dt'enmys IP currit fo ghlass.'''

V'ou glassit magh ec $1. T'eh yn oyr na ''$2''.

* Toshiaght y ghlass: $8
* Jerrey yn ghlass: $6
* Currit da: $7

Foddee oo cur fys er $1 ny [[{{MediaWiki:Grouppage-sysop}}|reireyder]] elley dy resooney magh y ghlass.
Cha nod oo jannoo ymmyd jeh'n chummey 'cur post-L da'n ymmydeyr shoh' mannagh vel eh sonrit ayns dty [[Special:Preferences|choontys tosheeaghtyn]] as mannagh vel ou glasst magh.<br />
She $3 dt'enmys IP roie, as she dt'enney ghlass na #$5. Cur ad lesh dagh ooilley eysht.",
'blockednoreason' => 'cha nel fa currit',
'loginreqtitle' => 'Shegin dhyt loggal stiagh',
'loginreqlink' => 'loggal stiagh',
'loginreqpagetext' => 'Shegin dhyt $1 dys jeeagh er duillagyn elley.',
'accmailtitle' => 'Fockle yn arrey currit.',
'accmailtext' => "Ta fockle arrey gyn tort er son [[User talk:$1|$1]] er ve currit dys $2.

Foddee oo ceaghley yn fockle arrey noa da'n choontys noa shoh er duillag ''[[Special:ChangePassword|cheaghley fockle yn arrey]]'' lurg loggal stiagh.",
'newarticle' => '(Noa)',
'newarticletext' => 'T’ou er jeet trooid kiangley dys duillag nagh vel ayn foast.
My vel oo geearree yn duillah shoh y chroo, gow toshiaght screeuyn ‘sy chishtey çheu heese jeh shoh (jeeagh er [[{{MediaWiki:Helppage}}|y duillag choonee]] son tooilley oayllys).
My haink oo dys shoh fo marranys, crig er cramman ‘erash’ jeh’n jeeagheyder ayd.',
'noarticletext' => "Cha nel teks 'sy duillag shoh ec y traa t'ayn.
Foddee oo [[Special:Search/{{PAGENAME}}|ronsaghey enmys y duillag shoh]] ayns duillagyn elley,
<span class=\"plainlinks\">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} jannoo ronsaghey 'sy lioaryn cooishyn ta bentyn r'ee],
ny [{{fullurl:{{FULLPAGENAME}}|action=edit}} reaghey yn duillag shoh]</span>.",
'noarticletext-nopermission' => "Cha nel teks 'sy duillag shoh ec y traa t'ayn.
Foddee oo [[Special:Search/{{PAGENAME}}|ronsaghey enmys y duillag shoh]] ayns duillagyn elley,
<span class=\"plainlinks\">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} ny jannoo ronsaghey 'sy lioaryn cooishyn ta bentyn r'ee]</span>,
agh cha nel kied ayd yn duillag y chroo.",
'note' => "'''Note:'''",
'previewnote' => "'''Cooinnee nagh vel agh roie-haishbynys eh shoh;
cha nel dty chaghlaaghyn sauailt foast!'''",
'editing' => 'Reaghey $1',
'editingsection' => 'Reaghey $1 (rheynn)',
'editingcomment' => 'Reaghey $1 (meer noa)',
'yourtext' => 'Dty heks',
'storedversion' => 'Lhieggan stoyrit',
'yourdiff' => 'Anchaslyssyn',
'copyrightwarning' => "Cur tastey my saillt: my t’ou cur red erbee da {{SITENAME}}, t’eh toiggit dy vel oo cur magh eh rere yn $2 (jeeagh er $1 son ny smoo fys).  Mannagh by vie lhiat dy beagh sleih elley reaghey dty obbyr gyn myghin as skeaylley eh dy seyr, ny chur roish eh ayns shoh.
<br />
Chammah as shen, t’ou gialdyn dooin dy screeu oo hene eh, ny ren oo coip jeh ny ta fo çhiarnys y theay, ny ry-gheddyn dy seyr.
'''NY CHUR ROISH GYN KIED OBBYR TA FO COIP-CHIART! '''",
'templatesused' => '{{PLURAL:$1|Clowan|Chlowan|Chlowan|Clowanyn}} ymmydit er y duillag shoh:',
'templatesusedpreview' => "{{PLURAL:$1|Clowan|Chlowan|Chlowan|Clowanyn}} ymmydit 'sy roie-haishbynys shoh:",
'template-protected' => '(glast)',
'template-semiprotected' => '(lieh-ghlast)',
'hiddencategories' => "Ta'n duillag shoh ayns {{PLURAL:$1|ronney follit|ronney follit|ronney follit|ronnaghyn follit}}",
'nocreatetext' => "Ta ablid duillagyn noa y chroo lhiettalit ec {{SITENAME}}.<br />
Foddee shiu goll er ash as reaghey duillag t'ayn nish, ny [[Special:UserLogin|loggal stiagh ny croo coontys]].",
'nocreate-loggedin' => 'Cha nel kied ayd duillagyn noa y chroo er {{SITENAME}}.',
'permissionserrorstext-withaction' => 'Cha nel kiart ayd $2, er {{PLURAL:$1|y fa|y fa|y fa|ny faghyn}} heese:',
'recreate-moveddeleted-warn' => "'''Raaue: T’ou aachroo duillag as eh er ve scrysst hannah hene.'''

By chair dhyt smooinagh vel eh kiart goll er oai lesh reaghey yn duillag shoh.<br />
Ta lioar ny scryssaghyn magh kiarit ayns shoh rere dty chaays hene:",
'editwarning-warning' => "My faagys oo y duillag, hed caghlaaghyn erbee er coayl, foddee.
My t'ou uss loggalt stiagh, foddee oo lhiettal y raaue shoh 'sy tosheeaghtyn ayd, 'sy rheynn \"Reaghey\".",

# Account creation failure
'cantcreateaccounttitle' => 'Cha nod coontys y chroo',

# History pages
'viewpagelogs' => 'Jeeagh er ny lioaryn cooishyn son y duillag shoh',
'currentrev' => 'Aavriwnys roie',
'currentrev-asof' => "Aavriwnys s'noa er $1",
'revisionasof' => 'Aavriwnys veih $1',
'revision-info' => 'Aavriwnys veih $1 ec $2',
'previousrevision' => '←Aavriwnys ny shinney',
'nextrevision' => 'Aavriwnys ny saa→',
'currentrevisionlink' => 'Aavriwnys s’noa',
'cur' => 'bio',
'next' => 'nah',
'last' => 'roish',
'page_first' => 'Kied',
'page_last' => 'roish',
'histlegend' => "Reih anchaslys: jean reih kiarkil reih ny lhiegganyn by vie lhiat cosoylaghey ad, as crig er \"enter\", ny er y cramman ec cass y rolley.<br />
Ogher: '''({{int:cur}})''' = anchaslyssyn rish y lhieggan t'ayn nish,
'''({{int:last}})''' = anchaslyssyn rish y lhieggan roish, '''{{int:minoreditletter}}''' = myn-reaghey",
'history-fieldset-title' => 'Femblal shennaghys',
'histfirst' => 'By hoshee',
'histlast' => 'By yerree',
'historyempty' => '(follym)',

# Revision feed
'history-feed-title' => 'Shennaghys yn aavriwnys',
'history-feed-description' => 'Shennaghys aavriwnys y duillag shoh er yn wiki',
'history-feed-item-nocomment' => '$1 ec $2',

# Revision deletion
'rev-deleted-comment' => '(cohaggloo scughit)',
'rev-deleted-user' => '(ennym yn ymmydeyr scughit)',
'rev-delundel' => 'taishbyn/follee',
'rev-showdeleted' => 'taishbyn',
'revdelete-hide-image' => 'Follee cummal y choadan',
'revdelete-log' => 'Fa:',
'revdel-restore' => 'ceaghil leayrid',
'pagehist' => 'Shennaghys y duillag',
'deletedhist' => 'Shennaghys scryssit',

# History merging
'mergehistory' => 'Shennaghys ny duillagyn y chochiangley',
'mergehistory-from' => 'Bun-ghuillag:',
'mergehistory-into' => 'Kione-ghuillag:',
'mergehistory-submit' => 'Aavriwnyssyn y chochiangley',
'mergehistory-autocomment' => 'Ta [[:$1]] cochianglit stiagh ayns [[:$2]]',
'mergehistory-comment' => 'Ta [[:$1]] cochianglit stiagh ayns [[:$2]]: $3',
'mergehistory-reason' => 'Fa:',

# Merge log
'revertmerge' => 'Jee-vestey',

# Diffs
'history-title' => 'Shennaghys aavriwnys dy "$1"',
'lineno' => 'Linney $1:',
'compareselectedversions' => 'Cosoylee ny lhiegganyn reiht',
'editundo' => 'rass',

# Search results
'searchresults' => 'Eiyrtyssyn y ronsee',
'searchresults-title' => 'Eiyrtyssyn y ronsee son "$1"',
'searchresulttext' => 'Son ny smoo oayllys mychione ronsaghtyn er {{SITENAME}}, jeeagh er [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle' => 'Ren oo ronsaghey er \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|dagh duillag ta toshiaghey lesh "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|dagh duillag ta kianglt rish "$1"]])',
'searchsubtitleinvalid' => "Ren oo ronsaghey er '''$1'''",
'notitlematches' => 'Cha nel shen ennym ghuillag erbee',
'notextmatches' => 'Cha nel shen ry-lhaih er duillag erbee',
'prevn' => '{{PLURAL:$1|$1}} roish shoh',
'nextn' => 'nah {{PLURAL:$1|$1}}',
'shown-title' => 'Taishbyn $1 {{PLURAL:$1|eiyrtys|eiyrtyssyn}} er dagh duillag',
'viewprevnext' => 'Jeeagh er ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-new' => "'''Croo yn duillag \"[[:\$1]]\" er y wiki shoh!'''",
'searchprofile-articles' => 'Duillagyn cummal',
'searchprofile-project' => 'Duillagyn Coonee as Shalee',
'searchprofile-everything' => 'Dagh red',
'searchprofile-articles-tooltip' => 'Ronsee ayns $1',
'searchprofile-project-tooltip' => 'Ronsee ayns $1',
'searchprofile-images-tooltip' => 'Ronsee coadanyn',
'searchprofile-everything-tooltip' => 'Ronsee dagh red (goaill stiagh duillagyn resoonaght)',
'search-result-size' => "$1 ({{PLURAL:$2|1 fockle|$2 'ockle|$2 'ockle|$2 focklyn}})",
'search-result-score' => 'Bentynys: $1%',
'search-redirect' => '(aa-enmyssit ass $1)',
'search-section' => '(rheynn $1)',
'search-suggest' => "R'ou çheet er: $1",
'search-interwiki-caption' => 'Shuyr-haleeghyn',
'search-interwiki-default' => '{{PLURAL:$1|$1 eiyrtys|$1 eiyrtys|$1 eiyrtys|$1 eiyrtyssyn}}:',
'search-interwiki-more' => '(ny smoo)',
'search-relatedarticle' => 'Bentyn rish',
'mwsuggest-disable' => 'Lhiettal coyrle AJAX',
'searchrelated' => 'bentyn rish',
'searchall' => 'yn clane',
'nonefound' => "'''Notey''':Cha nel eh ronsaghey dagh reamys gyn reih.
My t'ou uss son ronsaghey dagh cooid (as shen goaill stiagh duillagyn resooney, clowanyn, a.r.e.), cur ''all:'' ec y toshiaght, ny ennym y reamys reih ayd myr roie-ockle (m.s., ''Clowan:'').",
'powersearch' => 'Ard-ronsaghey',
'powersearch-legend' => 'Ard-ronsaghey',
'powersearch-ns' => 'Ronsee ayns boayl-enmyn:',
'powersearch-redir' => 'Cur aa-enmyssyn er y rolley',
'powersearch-field' => 'Ronsee er son',
'search-external' => 'Ronsaghey mooie',

# Preferences page
'preferences' => 'Tosheeaghtyn',
'mypreferences' => 'My hosheeaghtyn',
'prefs-edits' => 'Earroo caghlaaghyn:',
'prefsnologin' => 'Cha nel oo loggit stiagh',
'changepassword' => 'Fockle yn arrey y cheaghley',
'prefs-skin' => 'Crackan',
'skin-preview' => 'Roie-haishbynys',
'prefs-beta' => 'Troyn as greieyn beta',
'prefs-datetime' => 'Date as am',
'prefs-labs' => 'Troyn as greieyn prowaltagh',
'prefs-personal' => 'Gruaie yn ymmydeyr',
'prefs-rc' => "Caghlaaghyn s'noa",
'prefs-watchlist' => 'Rolley arrey',
'prefs-watchlist-days' => 'Laaghyn y haishbyney ayns rolley arrey:',
'prefs-misc' => 'Elley',
'prefs-rendering' => 'Cummey',
'saveprefs' => 'Sauail',
'prefs-editing' => 'Reaghey',
'columns' => 'Collooyn:',
'searchresultshead' => 'Ronsee',
'recentchangesdays' => "Laaghyn y haishbyney ayns caghlaaghyn s'noa:",
'savedprefs' => 'Ta dty hosheeaghtyn sauailt.',
'timezonelegend' => 'Cryss hraa:',
'localtime' => 'Traa ynnydagh:',
'timezoneoffset' => 'Ashchlou¹:',
'timezoneregion-africa' => 'Yn Affrick',
'timezoneregion-america' => 'America',
'timezoneregion-antarctica' => 'Yn Antarctagh',
'timezoneregion-arctic' => 'Yn Arctagh',
'timezoneregion-asia' => 'Yn Aishey',
'timezoneregion-atlantic' => 'Y Keayn Sheear',
'timezoneregion-australia' => 'Yn Austrail',
'timezoneregion-europe' => 'Yn Oarpey',
'timezoneregion-indian' => 'Y Keayn Injinagh',
'timezoneregion-pacific' => 'Y Keayn Sheealtagh',
'prefs-searchoptions' => 'Reihyssyn y ronsaghey',
'default' => 'roie-chiartagh',
'prefs-files' => 'Coadanyn',
'youremail' => 'Post-L:',
'username' => "Dt'ennym ymmydeyr:",
'uid' => 'Enney ymmydeyr:',
'prefs-memberingroups' => 'Oltey {{PLURAL:$1|possan|phossan|phossan|possanyn}} heese:',
'prefs-registration' => 'Traa listal',
'yourrealname' => 'Feer-ennym:',
'yourlanguage' => 'Çhengey:',
'yournick' => 'Far-ennym:',
'yourgender' => 'Keintys:',
'gender-unknown' => 'Neuhoilshit',
'gender-male' => 'Firrinagh',
'gender-female' => 'Bwoirrinagh',
'email' => 'Post-L',
'prefs-help-realname' => "Ta dt'eer ennym reihyssagh.<br />
My bailliu eh y chiarail, bee eh ymmydit son cur gys lieh y chur dhyt er son yn obbyr ayd.",
'prefs-help-email-required' => 'Ta feme ayn er enmys post-l.',
'prefs-info' => 'Fys bunneydagh',
'prefs-i18n' => 'Çhengey obbree',
'prefs-signature' => 'Ennym screeuit',
'prefs-dateformat' => 'Cummey date',
'prefs-timeoffset' => 'Kiartaghey traa',
'prefs-advancedediting' => 'Tosheeaghtyn crampey',
'prefs-advancedrc' => 'Tosheeaghtyn crampey',
'prefs-advancedrendering' => 'Tosheeaghtyn crampey',
'prefs-advancedsearchoptions' => 'Tosheeaghtyn crampey',
'prefs-advancedwatchlist' => 'Tosheeaghtyn crampey',
'prefs-displayrc' => 'Toshiaghtyn taishbyney',
'prefs-displaysearchoptions' => 'Toshiaghtyn taishbyney',
'prefs-displaywatchlist' => 'Toshiaghtyn taishbyney',
'prefs-diffs' => 'Cosoylaghey caghlaaghyn',

# User rights
'userrights' => 'Reireydys kiartyn ymmydeyr',
'userrights-lookup-user' => 'Possanyn ymmydeyr y stiurey',
'userrights-user-editname' => 'Screeu stiagh ennym ymmydeyr:',
'editusergroup' => 'Possanyn ymmydeyr y reaghey',
'userrights-editusergroup' => 'Possanyn ymmydeyr y reaghey',
'saveusergroups' => 'Possanyn ymmydeyr y sauail',
'userrights-groupsmember' => 'Oltey jeh:',
'userrights-reason' => 'Fa:',

# Groups
'group' => 'Possan:',
'group-user' => 'Ymmydeyryn',
'group-autoconfirmed' => 'Ymmydeyryn er nyn shickyraghey gyn thort',
'group-bot' => 'Botyn',
'group-sysop' => 'Reireyderyn',
'group-bureaucrat' => 'Oikreilleyderyn',
'group-suppress' => 'Oaseiryn',
'group-all' => '(yn clane)',

'group-user-member' => 'Ymmydeyr',
'group-bot-member' => 'Robot',
'group-sysop-member' => 'Reireyder',
'group-bureaucrat-member' => 'Oikreilleyder',
'group-suppress-member' => 'Oaseir',

'grouppage-user' => '{{ns:project}}:Ymmydeyryn',
'grouppage-bot' => '{{ns:project}}:Robotyn',
'grouppage-sysop' => '{{ns:project}}:Reireyderyn',

# Rights
'right-read' => 'Duillag y lhaih',
'right-edit' => 'Duillag y reaghey',
'right-createpage' => 'Duillag y chroo (nagh vel ny ghuillag resoonaght)',
'right-createtalk' => 'Duillag resoonaght y chroo',
'right-move' => 'Duillag y scughey',
'right-movefile' => 'Coadan y scughey',
'right-upload' => 'Laadey neese coadanyn',
'right-delete' => 'Duillag y scryssey',

# Special:Log/newusers
'newuserlogpage' => 'Lioar chooishyn ny h-ymmydeyryn noa',

# User rights log
'rightslog' => 'Lioar chooishyn kiartyn ymmydeyr',

# Associated actions - in the sentence "You do not have permission to X"
'action-read' => 'y duillag shoh y lhaih',
'action-edit' => 'yn duillag shoh y reaghey',
'action-createpage' => 'duillagyn y chroo',
'action-createtalk' => 'duillagyn resoonaght y chroo',
'action-createaccount' => 'y coontys ymmydeyr shoh y chroo',
'action-minoredit' => 'yn caghlaa shoh y chowraghey myr myn-arraghey',
'action-move' => 'yn duillag shoh y scughey',
'action-move-subpages' => 'yn duillag shoh as ny fo-ghuillagyn echey y scughey',
'action-movefile' => 'y coadan shoh y scughey',
'action-upload' => 'y coadan shoh y laadey neese',
'action-reupload' => "dy screeu harrish y choadan shoh t'ayn hannah",
'action-delete' => 'yn duillag shoh y scryssey',
'action-browsearchive' => 'duillagyn scrysst y ronsaghey',
'action-undelete' => 'yn duillag shoh y yee-scryssey',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|caghlaa|chaghlaa|chaghlaa|caghlaaghyn}}',
'recentchanges' => "Caghlaaghyn s'noa",
'recentchanges-legend' => "Reihyssyn da ny caghlaaghyn s'noa",
'recentchanges-summary' => "Shirrey ny caghlaaghyn s'noa da'n wiki er y duillag shoh.",
'recentchanges-feed-description' => "Shirr ny caghlaaghyn s'noa er y wiki 'sy scoltey shoh.",
'recentchanges-label-newpage' => 'Ren y reaghey shoh croo duillag noa',
'recentchanges-label-minor' => 'She myn-reaghey eh shoh',
'recentchanges-label-bot' => 'Ren bot y reaghey shoh',
'rcnote' => "Ny ta heese, she {{PLURAL:$1|ny '''$1''' caghlaa|yn '''$1''' chaghlaa|ny '''$1''' chaghlaa|ny '''$1''' caghlaaghyn}} s'jerree ayns {{PLURAL:$2|ny '''$2''' laa|yn '''$2''' laa|ny '''$2''' laa|ny '''$2''' laaghyn}} s'jerree, kiart ec $4, $5.",
'rcnotefrom' => "Shoh heese ny caghlaaghyn veih '''$2''' (gys '''$1''' taishbynit).",
'rclistfrom' => "Taishbyn ny caghlaaghyn s'noa veih $1",
'rcshowhideminor' => '{{PLURAL:$1|$1 myn-arraghey|$1 vyn-arraghey|$1 vyn-arraghey|$1 myn-arraghyn}}',
'rcshowhidebots' => '{{PLURAL:$1|$1 robot|$1 robot|$1 robot|$1 robotyn}}',
'rcshowhideliu' => '{{PLURAL:$1|$1 ymmydeyr|$1 ymmydeyr|$1 ymmydeyr|$1 ymmydeyryn}} ta loggit stiagh',
'rcshowhideanons' => '{{PLURAL:$1|$1 ymmydeyr|$1 ymmydeyr|$1 ymmydeyr|$1 ymmydeyryn}} neuenmyssit',
'rcshowhidepatr' => '$1 arraghyn patrolit',
'rcshowhidemine' => "$1 m'arraghyn",
'rclinks' => "Soilshee {{PLURAL:$1|ny $1 caghlaa|yn $1 chaghlaa|ny $1 chaghlaa|ny $1 caghlaaghyn}} s'jerree ayns {{PLURAL:$2|ny $2 laa|yn $2 laa|ny $2 laa|ny $2 laaghyn}} s'jerree<br />$3",
'diff' => 'anch',
'hist' => 'shen',
'hide' => 'Follee',
'show' => 'Taishbyn',
'minoreditletter' => 'm',
'newpageletter' => 'N',
'boteditletter' => 'r',
'rc_categories_any' => 'Ronney erbee',
'rc-enhanced-expand' => 'Taishbyn sonreeaghtyn (ta feme er JavaScript)',
'rc-enhanced-hide' => 'Follee sonreeaghtyn',

# Recent changes linked
'recentchangeslinked' => 'Caghlaaghyn conastagh',
'recentchangeslinked-feed' => 'Caghlaaghyn-vooinjerys',
'recentchangeslinked-toolbox' => 'Caghlaaghyn conastagh',
'recentchangeslinked-title' => 'Caghlaaghyn bentyn rish "$1"',
'recentchangeslinked-summary' => "Shoh rolley caghlaaghyn va jeant er duillagyn kianglt veih duillag sonrit (ny er olteynyn ronney sonrit).<br />
Ta duillagyn er [[Special:Watchlist|dty rolley arrey]] ayns '''clou trome'''.",
'recentchangeslinked-page' => 'Ennym y duillag:',
'recentchangeslinked-to' => 'Taishbyn caghlaaghyn da ny duillagyn ta kianglt rish y duillag hene, ayns ynnyd jeh shoh',

# Upload
'upload' => 'Laad neese coadan',
'uploadbtn' => 'Laadey neese coadan',
'uploadnologin' => 'Cha nel oo loggit stiagh',
'uploadlogpage' => 'Lioar ny laadyn neese',
'filename' => 'Ennym y choadan',
'filedesc' => 'Giare-choontey',
'fileuploadsummary' => 'Giare-choontey:',
'filestatus' => 'Stayd choip-chiart:',
'filesource' => 'Bun:',
'uploadedfiles' => 'Coadanyn ta laadit neese',
'badfilename' => 'T\'ennym y choadan aa-enmyssit myr "$1".',
'savefile' => 'Sauail y coadan',
'uploadedimage' => '"[[$1]]" laadit neese',
'uploadvirus' => "Ta veerys 'sy choadan! Mynphoyntyn: $1",
'watchthisupload' => 'Freill arrey er y choadan shoh',

'upload-file-error' => 'Marranys ynveanagh',

'license' => 'Kieddagh:',
'license-header' => 'Kieddagh:',
'license-nopreview' => '(Cha nel roie-haishbynys ry-gheddyn)',
'upload_source_file' => ' (coadan er dty cho-earrooder)',

# Special:ListFiles
'imgfile' => 'coadan',
'listfiles' => 'Rolley coadanyn',
'listfiles_date' => 'Date',
'listfiles_name' => 'Ennym',
'listfiles_user' => 'Ymmydeyr',
'listfiles_size' => 'Mooadys',
'listfiles_description' => 'Coontey',

# File description page
'file-anchor-link' => 'Coadan',
'filehist' => 'Shennaghys y choadan',
'filehist-help' => 'Crig er daayt/am ennagh son fakin er y choadan myr v’eh ec y traa shen.',
'filehist-deleteall' => 'scryss ooilley',
'filehist-deleteone' => 'scryss eh shoh',
'filehist-revert' => 'cur er ash',
'filehist-current' => 'bio',
'filehist-datetime' => 'Daayt/Am',
'filehist-thumb' => 'Ingin-ordaag',
'filehist-thumbtext' => "Ingin-ordaag da'n lhieggan shoh ec $1",
'filehist-user' => 'Ymmydeyr',
'filehist-dimensions' => 'Towshanyn',
'filehist-filesize' => 'Mooadys y choadan',
'filehist-comment' => 'Cohaggloo',
'imagelinks' => 'Ymmyd y choadan',
'linkstoimage' => 'Ta {{PLURAL:$1|ny $1 duillag|yn $1 duillag|ny $1 ghuillag|ny $1 duillagyn}} eiyrtyssagh kianglt rish y choadan shoh:',
'nolinkstoimage' => 'Cha nel duillag erbee kianglt rish y choadan shoh.',
'sharedupload' => "Ta'n coadan shoh çheet ass $1, as foddee dy beagh eh ymmydit ayns shaleeyn elley.",
'uploadnewversion-linktext' => "Laad neese lhieggan noa jeh'n choadan shoh",

# File reversion
'filerevert-comment' => 'Fa:',

# File deletion
'filedelete' => 'Scryss $1',
'filedelete-legend' => 'Scryss y coadan',
'filedelete-submit' => 'Scryss',
'filedelete-nofile' => "Cha nel '''$1''' ayn.",
'filedelete-otherreason' => 'Fa elley/tooilley:',
'filedelete-reason-otherlist' => 'Oyr elley',
'filedelete-reason-dropdown' => '*Fa scryssey cadjin
** Brishey choip-chiart
** Coadan doobyl',

# MIME search
'mimesearch' => 'Sorçh MIME',
'mimetype' => 'sorçh MIME:',
'download' => 'laad neose',

# Unwatched pages
'unwatchedpages' => 'Duillagyn gyn arrey',

# List redirects
'listredirects' => 'Rolley duillagyn aa-enmyssit',

# Unused templates
'unusedtemplates' => 'Clowanyn neuymmydit',
'unusedtemplateswlh' => 'kianglaghyn elley',

# Random page
'randompage' => 'Duillag gyn tort',

# Random redirect
'randomredirect' => 'Aa-enmys gyn tort',

# Statistics
'statistics' => 'Staydraa',
'statistics-header-users' => 'Staydraa ymmydeyr',
'statistics-pages' => 'Duillagyn',

'doubleredirects' => 'Aa-enmyssyn dooblagh',

'brokenredirects' => 'Aa-enmyssyn brisht',
'brokenredirects-edit' => 'reaghey',
'brokenredirects-delete' => 'scryss',

'withoutinterwiki' => 'Duillagyn gyn kianglaghyn eddyrwiki',
'withoutinterwiki-legend' => 'Roie-ockle',
'withoutinterwiki-submit' => 'Taishbyn',

'fewestrevisions' => 'Duillagyn lesh ny caghlaaghyn sloo',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|byte|vyte|byte|byteyn}}',
'ncategories' => '$1 {{PLURAL:$1|ronney|ronnaghyn}}',
'nlinks' => '$1 {{PLURAL:$1|kiangley|chiangley|chiangley|kianglaghyn}}',
'nmembers' => '$1 {{PLURAL:$1|oltey|oltey|oltey|olteynyn}}',
'lonelypages' => 'Duillagyn treoghe',
'uncategorizedpages' => 'Duillagyn gyn ronney',
'uncategorizedcategories' => 'Ronnaghyn gyn ronney',
'uncategorizedimages' => 'Coadanyn gyn ronney',
'uncategorizedtemplates' => 'Clowanyn gyn ronney',
'unusedcategories' => 'Ronnaghyn neuymmydit',
'unusedimages' => 'Coadanyn neuymmydit',
'popularpages' => 'Duillagyn cadjin',
'wantedcategories' => 'Ronnaghyn ry-laccal',
'wantedpages' => 'Duillagyn ry-laccal',
'wantedfiles' => 'Coadanyn ry-laccal',
'wantedtemplates' => 'Clowanyn ry-laccal',
'mostlinked' => 'Duillagyn as mooarane kianglaghyn daue',
'mostlinkedcategories' => 'Ronnaghyn as mooarane kianglaghyn daue',
'mostlinkedtemplates' => 'Clowanyn as mooarane kianglaghyn daue',
'mostcategories' => 'Duillagyn lesh ronnaghyn smoo',
'mostimages' => 'Coadanyn as mooarane kianglaghyn daue',
'mostrevisions' => 'Duillagyn lesh aavriwnyssyn smoo',
'prefixindex' => 'Dagh duillag lesh roie-ockle',
'shortpages' => 'Duillagyn giarey',
'longpages' => 'Duillagyn liauyr',
'deadendpages' => 'Duillagyn kione kyagh',
'protectedpages' => 'Duillagyn fo ghlass',
'protectedpages-indef' => 'Cha nel agh coadey neuyerrinagh',
'protectedpages-cascade' => 'Cha nel agh coadey eiraghtagh',
'protectedtitles' => 'Enmyn coadit',
'listusers' => 'Rolley ymmydeyryn',
'usercreated' => 'Crooit liorish {{GENDER:$3|}} er $1 ec $2',
'newpages' => 'Duillagyn noa',
'newpages-username' => 'Ennym ymmydeyr:',
'ancientpages' => 'Duillagyn by hinney',
'move' => 'Scugh',
'movethispage' => 'Yn duillag shoh y scughey',
'pager-newer-n' => "{{PLURAL:$1|1 ny s'noa|$1 ny s'noa}}",
'pager-older-n' => '{{PLURAL:$1|1 ny shinney|$1 ny shinney}}',
'suppress' => 'Oaseirys',

# Book sources
'booksources' => 'Bun-gheillyn lioar',
'booksources-search-legend' => 'Jean ronsaghey er bun-gheillyn lioar',
'booksources-go' => 'Gow',

# Special:Log
'specialloguserlabel' => 'Ymmydeyr:',
'speciallogtitlelabel' => 'Ennym (duillag ny ymmydeyr):',
'log' => 'Lioaryn cooishyn',
'all-logs-page' => 'Dagh ooilley lioar chooishyn',

# Special:AllPages
'allpages' => 'Dagh ooilley ghuillag',
'alphaindexline' => '$1 gys $2',
'nextpage' => 'Yn chied duillag elley ($1)',
'prevpage' => 'Yn duillag roish ($1)',
'allpagesfrom' => 'Taishbyn ny duillagyn ta toshiaghey lesh:',
'allpagesto' => 'Taishbyn ny duillagyn ta jannoo jerrey lesh:',
'allarticles' => 'Dagh ooilley ghuillag',
'allpagessubmit' => 'Gow',
'allpagesprefix' => 'Taishbyn ny duillagyn lesh roie-ockle:',

# Special:Categories
'categories' => 'Ronnaghyn',
'special-categories-sort-count' => 'sorçhaghey rere coontey',
'special-categories-sort-abc' => 'sorçhaghey rere lettyr',

# Special:LinkSearch
'linksearch' => 'Ronsaghey kianglaghyn çheumooie',
'linksearch-ok' => 'Ronsee',
'linksearch-line' => 'Ta kiangley ayn veih $2 gys $1',

# Special:ListUsers
'listusers-submit' => 'Taishbyn',

# Special:ListGroupRights
'listgrouprights-group' => 'Possan',
'listgrouprights-rights' => 'Kiartyn',
'listgrouprights-helppage' => 'Help:Kiartyn y phossan',
'listgrouprights-members' => '(rolley olteynyn)',

# Email user
'emailuser' => "Cur post-L da'n ymmydeyr shoh",
'emailfrom' => 'Veih:',
'emailto' => 'Da:',
'emailsubject' => 'Bun-chooish:',
'emailmessage' => 'Çhaghteraght:',
'emailsend' => 'Cur',
'emailccsubject' => 'Aascreeuyn dty haghteraght dys $1: $2',
'emailsent' => 'Post-L currit',
'emailsenttext' => 'Ta dty phost-L currit.',

# Watchlist
'watchlist' => 'My rolley arrey',
'mywatchlist' => 'My rolley arrey',
'watchnologin' => 'Cha nel oo loggit stiagh',
'addedwatchtext' => "Va'n duillag \"[[:\$1]]\" currit rish dty [[Special:Watchlist|rolley arrey]].<br />
Bee caghlaaghyn jeant er y duillag shoh as e ghuillag resoonaght ry-akin ayns y rolley shoh, as bee '''clou trome''' er ayns rolley ny [[Special:RecentChanges|caghlaaghyn s'noa]].",
'removedwatchtext' => 'Va\'n duillag "[[:$1]]" goit veih dty [[Special:Watchlist|rolley arrey]].',
'watch' => 'Freill arrey',
'watchthispage' => 'Freill arrey er y duillag shoh',
'unwatch' => 'Cur stap er arrey',
'unwatchthispage' => 'Cur stap er arrey',
'notanarticle' => 'Cha nel eh shoh ny ghuillag cummal',
'notvisiblerev' => "Va'n aavriwnys scryssit",
'watchlist-details' => 'Ta {{PLURAL:$1|$1 duillag|$1 duillag|$1 ghuillag|$1 duillagyn}} er dty rolley arrey, faagail magh duillagyn resoonaght.',
'watchlistcontains' => 'Ta $1 {{PLURAL:$1|duillag|duillagyn}} ayns dty rolley arrey.',
'wlshowlast' => "Taishbyn ny kied $1 ooryn $2 laaghyn $3 s'jerree",
'watchlist-options' => "Reihyn da'n rolley arrey",

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'Jannoo arrey...',
'unwatching' => 'Stap y chur er arrey...',

'enotif_impersonal_salutation' => '{{SITENAME}} ymmydeyr',
'enotif_anon_editor' => 'ymmydeyr $1 neuenmyssit',
'enotif_body' => '$WATCHINGUSERNAME veen,


Va\'n duillag $PAGETITLE er {{SITENAME}} $CHANGEDORCREATED er $PAGEEDITDATE liorish $PAGEEDITOR, jeeagh er $PAGETITLE_URL son y lhieggan roie dy akin.

$NEWPAGE

Giare-choontey yn reagheyder: $PAGESUMMARY $PAGEMINOREDIT

Cur fys er y reagheyder:
post: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Cha bee fograghyn elley er caghlaaghyn elley agh my jigys shiu dys y duillag shen ynrican.
Foddee shiu aajeshaghey bratteeyn ny fograghyn ry hoi dagh duillag er dty rolley arrey.

             Dty chorys fograghyn caarjoil ec {{SITENAME}}

--

Dys toshiaghtyn fys post-y y chaghlaa, cur keayrt er
{{canonicalurl:{{#special:Preferences}}}}

Dys toshiaghtyn dty rolley arrey y chaghlaa, cur keayrt er
{{canonicalurl:{{#special:EditWatchlist}}}}

Dys scryssey duillag ass dty rolley arrey, cur keayrt er
$UNWATCHURL

Aaveaghey as cooney s\'odjey:
{{canonicalurl:{{MediaWiki:Helppage}}}}',
'created' => 'crooit',
'changed' => 'ceaghlit',

# Delete
'deletepage' => 'Scryss y duillag',
'confirm' => 'Feeraghey',
'excontent' => "v'eh ny chummal na: '$1'",
'exblank' => "va'n duillag follym",
'delete-confirm' => 'Scryss "$1"',
'delete-legend' => 'Scryss',
'historywarning' => 'Raaue: Ta shennaghys ec y duillag ta shiu er-chee scryssey magh, as mysh $1 {{PLURAL:$1|caghlaa|chaghlaa|chaghlaa|caghlaaghyn}} er:',
'confirmdeletetext' => 'Ta shiu er-çhee scryssey magh duillag myrane lesh y shennaghys eck.<br />
Feeraghey dy vel eh y çhalee ayd eh y yannoo, dy vel ny scanshyn toiggit ayd, as dy vel oo jannoo eh ayns coardailys rish [[{{MediaWiki:Policy-url}}|y pholasee]].',
'actioncomplete' => 'Jantys creaghnit',
'deletedtext' => 'Ta "$1" scrysst.<br />
Jeeagh er $2 son recortys ny scryssaghyn magh jeianagh.',
'dellogpage' => 'Lioar ny scryssaghyn magh',
'deletecomment' => 'Fa:',
'deleteotherreason' => 'Fa elley/tooilley:',
'deletereasonotherlist' => 'Fa elley',
'deletereason-dropdown' => '*Fa scryssey cadjin
** Aghin yn ughtar
** Brishey choip-chiart
** Cragheydys',

# Rollback
'rollback_short' => 'Aahogherys',
'rollbacklink' => 'aahogher',
'editcomment' => "V'eh \"''\$1''\" giare-choontys y reaghey.",

# Protect
'protectlogpage' => 'Lioar choadee',
'protectedarticle' => '"[[$1]]" glast',
'modifiedarticleprotection' => 'er gaghlaa keim coadee "[[$1]]"',
'prot_1movedto2' => '[[$1]] aa-enmyssit myr [[$2]]',
'protectcomment' => 'Fa:',
'protectexpiry' => 'Jerrey:',
'protect_expiry_invalid' => 'Ta jerrey yn amm gyn vree.',
'protect_expiry_old' => 'Ta jerrey yn amm er ngoll shaghey hannah.',
'protect-text' => "Foddee oo jeeagh er as arraghey yn rea choadee ayns shoh son y duillag '''$1'''.",
'protect-locked-access' => "Cha nel kied ec dty choontys dys arraghey cormidyn coadee.<br />
Shoh ny reaghaghyn roie da'n duillag '''$1''':",
'protect-cascadeon' => "Ta'n duillag shoh coadit nish, er y fa dy vel eh goit stiagh {{PLURAL:$1|'sy $1 duillag|'syn $1 duillag| 'sy $1 ghuillag|ayns ny $1 duillagyn}} heese as adsyn fo coadey eiraghtagh.  Ga dy nod oo caghlaa keim coadee y ghuillag shoh, cha jean eh bentyn rish y coadey eiraghtagh.",
'protect-default' => 'Lhig da dagh ymmydeyr',
'protect-fallback' => 'Ta feme er kied "$1" ayd',
'protect-level-autoconfirmed' => 'Cur ymmydeyryn noa as ymmydeyryn neu-recortit fo ghlass',
'protect-level-sysop' => 'Reireyderyn ynrican',
'protect-summary-cascade' => 'spooytey',
'protect-expiring' => 'jerrey jeant ec $1 (UTC)',
'protect-cascade' => "Cur fo ghlass ny duillagyn t'ayns y duillag shoh (coadee spooytal)",
'protect-cantedit' => 'Cha nod oo caghlaa keim choadey ny duillag shoh.  Cha nel kied ayd ee y reaghey.',
'protect-expiry-options' => '1 oor:1 hour,1 laa:1 day,1 hiaghtin:1 week,2 hiaghtin:2 weeks,1 vee:1 month,3 meeghyn:3 months,6 meeghyn:6 months,1 vlein:1 year,neuyerrinagh:infinite',
'restriction-type' => 'Kied:',
'restriction-level' => 'Rea teorey:',

# Restrictions (nouns)
'restriction-edit' => 'Reaghey',
'restriction-move' => 'Scughey',
'restriction-create' => 'Croo',

# Restriction levels
'restriction-level-sysop' => 'lane glast',
'restriction-level-autoconfirmed' => 'lieh-ghlast',

# Undelete
'undelete' => 'Jeeagh er duillagyn scrysst',
'undeletepage' => 'Jeeagh er as cur er ash duillagyn scrysst',
'viewdeletedpage' => 'Jeeagh er duillagyn scrysst',
'undeletebtn' => 'Cur er ash',
'undeletelink' => 'jeeagh/cur er ash',
'undeleteviewlink' => 'jeeagh',
'undeletereset' => 'Aahoiaghey',
'undeletecomment' => 'Fa:',
'undelete-search-box' => 'Ronsee ny duillagyn scrysst',
'undelete-search-submit' => 'Ronsee',

# Namespace form on various pages
'namespace' => 'Reamys:',
'invert' => 'Cur y reih bun ry-skyn',
'blanknamespace' => '(Cadjin)',

# Contributions
'contributions' => 'Cohortyssyn ymmydeyr',
'contributions-title' => 'Cohortyssyn liorish $1',
'mycontris' => 'My chohortyssyn',
'contribsub2' => 'Da $1 ($2)',
'uctop' => '(baare)',
'month' => "Veih'n vee (as ny s'aa):",
'year' => "Veih'n vlein (as ny s'aa):",

'sp-contributions-newbies' => 'Taishbyn cohortyssyn ec coontyssyn noa ny lomarcan',
'sp-contributions-newbies-sub' => 'Son coontyssyn noa',
'sp-contributions-blocklog' => 'Lioar chooishyn ny glassaghyn magh',
'sp-contributions-talk' => 'resoonaght',
'sp-contributions-userrights' => 'Reireydys kiartyn ymmydeyr',
'sp-contributions-search' => 'Ronsee cohortyssyn',
'sp-contributions-username' => 'Enmys IP ny ennym ymmydeyr:',
'sp-contributions-submit' => 'Ronsee',

# What links here
'whatlinkshere' => 'Cre ta kiangley rish shoh',
'whatlinkshere-title' => 'Duillagyn ta kianglt rish $1',
'whatlinkshere-page' => 'Duillag:',
'linkshere' => "Ta ny duillagyn shoh kianglt rish '''[[:$1]]''':",
'nolinkshere' => "Cha nel duillag erbee kianglt rish '''[[:$1]]'''.",
'isredirect' => 'duillag aa-enmyssit',
'istemplate' => 'goaill stiagh',
'isimage' => 'kiangley coadan',
'whatlinkshere-prev' => '{{PLURAL:$1|roish|y $1 roish}}',
'whatlinkshere-next' => '{{PLURAL:$1|y nah|y nah $1}}',
'whatlinkshere-links' => '← kianglaghyn',
'whatlinkshere-hideredirs' => '$1 duillagyn aa-enmyssit',
'whatlinkshere-hidetrans' => '$1 duillagyn er nyn ngoaill stiagh',
'whatlinkshere-hidelinks' => '$1 kianglaghyn',
'whatlinkshere-filters' => 'Shollaneyn',

# Block/unblock
'blockip' => 'Glass magh yn ymmydeyr',
'blockip-legend' => 'Glass magh yn ymmydeyr',
'ipadressorusername' => 'Enmys IP ny ennym ymmydeyr:',
'ipbexpiry' => 'Jerrey:',
'ipbreason' => 'Fa:',
'ipbreasonotherlist' => 'Fa elley',
'ipbreason-dropdown' => '* Oyr glassey cadjin
** Inserting false information
** Removing content from pages
** Spamming links to external sites
** Inserting nonsense/gibberish into pages
** Intimidating behavior/harassment
** Abusing multiple accounts
* Oyr elley
** Ennym ymmydeyryn neuchooie
** Feyshtyn eddyr-wiki',
'ipbcreateaccount' => 'Crooaght coontys y chumrail',
'ipbsubmit' => 'Glass magh yn ymmydeyr shoh',
'ipbother' => 'Mooad elley am:',
'ipboptions' => '2 oor:2 hours,1 laa:1 day,3 laaghyn:3 days,1 hiaghtin:1 week,2 hiaghtin:2 weeks,1 vee:1 month,3 meeghyn:3 months,6 meeghyn:6 months,1 vlein:1 year,neuyerrinagh:infinite',
'ipbotheroption' => 'elley',
'ipbotherreason' => 'Fa elley/tooilley:',
'badipaddress' => 'Enmys IP gyn vree',
'ipblocklist' => 'Ymmydeyryn fo ghlass',
'blocklist-reason' => 'Fa:',
'ipblocklist-submit' => 'Ronsee',
'infiniteblock' => 'neuyerrinagh',
'createaccountblock' => 'crooaght coontys glasst',
'blocklink' => 'glass magh',
'unblocklink' => 'jee-ghlass',
'change-blocklink' => 'ceaghil glass',
'contribslink' => 'cohortyssyn',
'blocklogpage' => 'Lioar chooishyn ny glassaghyn magh',
'blocklogentry' => 'er nglassey magh [[$1]] rish/derrey $2 $3',
'unblocklogentry' => '$1 er ny neughlassey magh',
'block-log-flags-anononly' => 'ymmydeyryn neuenmyssit ynrican',
'block-log-flags-nocreate' => 'gyn kiart coontyssyn y chroo',

# Move page
'move-page' => '$1 y scughey',
'move-page-legend' => 'Duillag y scughey',
'movepagetext' => "Ta'n form heese lhiggey dhyt duillag y aa-enmys, as y shennaghys echey y scughey dys yn ennym noa.
Hig y shenn ennym y ve duillag aastiurey dys yn ennym noa.
My ta aastiuraghyn ayn hannah dys y shenn ennym, foddee oo adsyn y chaghlaa dy seyr-obbragh.  Mannagh nee oo shen, jean shickyr dy hirrey er [[Special:DoubleRedirects|dooble-aastiuraghyn]] as [[Special:BrokenRedirects|aastiuraghyn brishtey]].
T'eh ort y yannoo shickyr dy bee kianglaghyn kiangley rish y dean kiart foast.

Gow tashtey '''nagh''' jed y duillag er scughey my ta duillag ayn as yn ennym noa echey hannah, '''mannagh''' nee duillag follym ny aastiurey t'ayn gyn shennaghys reaghey erbee.
Myr shen, foddee oo duillag y chur erash 'syn chenn ynnyd echey my nee uss marrantys, as cha nod oo screeu harrish duillag t'ayn hannah.

'''Raaue!'''
She caghlaa trome as doaltattym t'ayn er son duillag mie er enney.  Jean shickyr dy vel oo toiggal ny h-eiyrtyssyn roish my nee uss y caghlaa shoh.",
'movepagetalktext' => "Hed y duillag resooney eck er scughey lesh y duillag hene '''mannagh:'''
*Vel duillag resooney ayn nagh vel follym as yn ennym noa er;
*Nee uss jee-reih y kishtey heese.

Foddee oo y duillag resooney y scughey er lheh ny yei shen.",
'movearticle' => 'Duillag y scughey:',
'movenologin' => 'Cha nel oo loggit stiagh',
'newtitle' => 'Gys ard-ennym noa:',
'move-watch' => 'Freill arrey er y duillag shoh',
'movepagebtn' => 'Yn duillag y scughey',
'pagemovedsub' => "Va'n scughey rahoil",
'movepage-moved' => 'Va \'\'\'"$1" aa-enmyssit myr "$2"\'\'\'',
'articleexists' => 'Ta duillag ayn lesh yn ennym shen, ny ta ennym mee-chiart reiht ayd.<br />
Reih ennym elley, my sailliu.',
'talkexists' => "'''Va'n duillag hene scughit, agh cha nod y duillag resoonaght y scughey er yn oyr dy row fer ec yn enmys shen hannah.<br />
Jean covestey eddyr oc er laueyn, my sailliu.'''",
'movedto' => 'aa-enmyssit myr',
'movetalk' => 'Scugh yn duillag resoonaght eck',
'movelogpage' => 'Scugh y Lioar chooishyn',
'movereason' => 'Fa:',
'revertmove' => 'cur er ash',
'delete_and_move' => 'Scryss as scughey',
'delete_and_move_confirm' => 'Ta, scryss magh y duillag',

# Export
'export' => 'Assphurtal duillagyn',
'export-submit' => 'Assphurtal',
'export-download' => 'Sauail myr coadan',

# Namespace 8 related
'allmessages' => 'Çhaghteraghtyn corys',
'allmessagesname' => 'Ennym',
'allmessagesdefault' => 'Teks cadjinit',
'allmessagescurrent' => 'Teks roie',

# Thumbnails
'thumbnail-more' => 'Mooadee',
'filemissing' => 'Coadan ersooyl',
'thumbnail_error' => 'Marranys ingin-ordaag y chroo: $1',

# Special:Import
'import-comment' => 'Cohaggloo:',
'importbadinterwiki' => 'Droghchiangley eddyrwiki',
'importnotext' => 'Follym ny gyn teks',

# Import log
'importlogpage' => 'Cur lioar chooishyn stiagh',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'My ghuillag ymmydeyr',
'tooltip-pt-mytalk' => 'My ghuillag resoonaght',
'tooltip-pt-preferences' => 'My hosheeaghtyn',
'tooltip-pt-watchlist' => "Rolley ny duillagyn t'er dty rolley arrey",
'tooltip-pt-mycontris' => 'Rolley my chohortyssyn',
'tooltip-pt-login' => 'Ta shin greinnaghey shiu loggal stiagh; ansherbee, cha nel feme er.',
'tooltip-pt-logout' => 'Log magh',
'tooltip-ca-talk' => 'Resoonaght mychione y duillag chummal',
'tooltip-ca-edit' => 'Foddee oo reaghey yn duillag shoh. Crig er y chramman roie-haishbynys roish eh y hauail.',
'tooltip-ca-addsection' => 'Cur toshiaght rish rheynn noa',
'tooltip-ca-viewsource' => "Ta'n duillag shoh fo ghlass. Foddee oo jeeagh er e bun.",
'tooltip-ca-history' => "Shenn aavriwnyssyn jeh'n duillag shoh",
'tooltip-ca-protect' => 'Coadee yn duillag shoh',
'tooltip-ca-delete' => 'Scryss y duillag shoh',
'tooltip-ca-move' => 'Scugh y duillag',
'tooltip-ca-watch' => 'Cur y duillag shoh rish dty rolley arrey',
'tooltip-ca-unwatch' => 'Gow y duillag shoh magh ass dty rolley arrey',
'tooltip-search' => 'Ronsee {{SITENAME}}',
'tooltip-search-go' => 'Immee gys duillag as yn ennym cruinn shoh er, my vees eh ayn',
'tooltip-search-fulltext' => 'Ronsee ny duillagyn er son y teks shoh',
'tooltip-p-logo' => 'Gow gys yn ard-ghuillag',
'tooltip-n-mainpage' => 'Gow gys yn ard-ghuillag',
'tooltip-n-mainpage-description' => 'Gow gys yn ard-ghuillag',
'tooltip-n-portal' => "Mychione y çhalee, jean dty chooid share, c'raad reddyn y gheddyn",
'tooltip-n-currentevents' => 'Fow fysseree shaghadys er cooishyn yn laa',
'tooltip-n-recentchanges' => "Rolley ny caghlaaghyn s'noa er y wiki.",
'tooltip-n-randompage' => 'Laad duillag gyn tort',
'tooltip-n-help' => 'Boayl gys feddyn magh.',
'tooltip-t-whatlinkshere' => 'Rolley dagh ooilley ghuillag wiki ta kiangley rish shoh',
'tooltip-t-recentchangeslinked' => "Caghlaaghyn s'noa er ny duillagyn ta kianglt rish y duillag shoh",
'tooltip-feed-rss' => "Beaghey RSS da'n duillag shoh",
'tooltip-feed-atom' => "Beaghey Atom da'n duillag shoh",
'tooltip-t-contributions' => 'Jeeagh er cohortyssyn yn ymmydeyr shoh',
'tooltip-t-emailuser' => "Cur post-L da'n ymmydeyr shoh",
'tooltip-t-upload' => 'Laad neese coadanyn',
'tooltip-t-specialpages' => 'Rolley dagh ooilley ghuillag er lheh',
'tooltip-t-print' => "Lhieggan so-chlou jeh'n duillag shoh",
'tooltip-t-permalink' => 'Kiangley beayn da aavriwnys y duillag shoh',
'tooltip-ca-nstab-main' => 'Jeeagh er y duillag chummal',
'tooltip-ca-nstab-user' => 'Jeeagh er duillag yn ymmydeyr',
'tooltip-ca-nstab-special' => "She duillag er lheh t'ayn; cha nod oo reaghey y duillag hene",
'tooltip-ca-nstab-project' => 'Jeeagh er y duillag halee',
'tooltip-ca-nstab-image' => 'Jeeagh er duillag y choadan',
'tooltip-ca-nstab-mediawiki' => 'Jeeagh er çhaghteraght y chorys',
'tooltip-ca-nstab-template' => 'Jeeagh er y chlowan',
'tooltip-ca-nstab-help' => 'Jeeagh er duillag y chooney',
'tooltip-ca-nstab-category' => 'Jeeagh er duillag y ronney',
'tooltip-minoredit' => 'She myn-reaghey eh shoh',
'tooltip-save' => 'Sauail dty chaghlaaghyn',
'tooltip-preview' => 'Roie-haishbyn ny caghlaaghyn eu; jean-shiu ymmyd jeh roish sauail, my sailliu!',
'tooltip-diff' => 'Taishbyn caghlaaghyn y teks ta jeant eu.',
'tooltip-compareselectedversions' => "Jeeagh er ny caghlaaghyn eddyr y daa lhieggan reiht jeh'n ghuillag shoh.",
'tooltip-watch' => 'Cur y duillag shoh rish dty rolley arrey',
'tooltip-rollback' => 'Ta "aahogher" rassey dagh cohoyrtys jeh\'n ymmydeyr s\'jerree er y duillag shoh.',
'tooltip-undo' => "Ta \"rass\" cur y reaghey shoh er ash as t'eh foshley yn form reaghee ayns mod roie-haishbynys.<br />
T'eh lhiggey da oyr y chur 'sy ghiare-choontey.",
'tooltip-summary' => 'Cur giare-choontey stiagh',

# Attribution
'anonymous' => '{{PLURAL:$1|Ymmeyder|Ymmeyderny}} neuenmyssit dy {{SITENAME}}',
'siteuser' => 'ymmydeyr {{SITENAME}} $1',
'others' => 'sleih elley',
'siteusers' => '{{PLURAL:$2|Ymmydeyr|Ymmydeyryn}} ec {{SITENAME}} $1',

# Browsing diffs
'previousdiff' => '← Y caghlaa ny shinney',
'nextdiff' => 'Y caghlaa ny snoa →',

# Media information
'widthheightpage' => '$1 × $2, $3 {{PLURAL:$3|duillag|duillagyn}}',
'file-info-size' => '$1 × {{PLURAL:$2|$2 pixel|$2 phixel|$2 phixel|$2 pixelyn}}, mooadys y choadan: $3, sorçh MIME: $4',
'file-nohires' => 'Cha nel jeeskeaylley ny smoo ry-gheddyn.',
'svg-long-desc' => 'coadan SVG, $1 × {{PLURAL:$2|$2 pixel|$2 phixel|$2 phixel|$2 pixelyn}} dy ennymagh, mooadys y choadan: $3',
'show-big-image' => 'Jeeskeaylley ymlane',

# Special:NewFiles
'newimages' => 'Laaragh coadanyn noa',
'showhidebots' => '($1 botyn)',
'ilsubmit' => 'Ronsee',
'bydate' => 'rere date',

# Bad image list
'bad_image_list' => "Shoh yn aght:

Cha nel agh meeryn ayns rolley (linnaghyn as * ec y toshiaght) ta goll er smooinaghtyn er.
Shegin da'n chied chiangley er linney ve ny chiangley da drogh choadan.
Kianglaghyn eiyrtyssagh erbee er yn linney shoh, t'ad goll er loaghtey myr lhimmaghyn, shen gra, duillagyn as ta'n coadan ayns-linney, foddee.",

# Metadata
'metadata' => 'Metadata',
'metadata-help' => "Ta'n coadan shoh goaill tooilley fysseree stiagh, currit veih'n çhamraig vun-earrooagh ny yn scanreyder, as eh ymmydit dys y coadan y chroo ny y yannoo bun-earrooagh, s'liklee.<br />
My vel y coadan ceaghlit veih'n chummey bunneydagh, foddee nagh beagh mynphoyntyn ennagh cohoilshaghey yn coadan ceaghlit.",
'metadata-expand' => 'Taishbyn ny sonreeaghtyn sheeynt',
'metadata-collapse' => 'Follee ny sonreeaghtyn sheeynt',
'metadata-fields' => 'Ny magheryn metafysseree jalloo heese, hed ad er goaill stiagh er duillag taishbyney jalloo tra ta taabyl ny metafysseree fillit.<br />
Bee adsyn elley follit dy seyr-obbragh.<br />
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength
* artist
* copyright
* imagedescription
* gpslatitude
* gpslongitude
* gpsaltitude',

# Exif tags
'exif-imagewidth' => 'Lheead',
'exif-imagelength' => 'Yrjid',
'exif-ycbcrpositioning' => 'Soie Y as C',
'exif-xresolution' => 'Cruinnys co-chruinnagh',
'exif-yresolution' => 'Cruinnys pontreilagh',
'exif-imagedescription' => 'Ennym y chochaslys',
'exif-make' => 'Jeantagh y hamraig',
'exif-artist' => 'Ughtar',
'exif-copyright' => 'Shellooder y choip-chiart',
'exif-fnumber' => 'Earroo F',
'exif-flash' => 'Çhenney',
'exif-gpslatitude' => 'Dowan-lheead',
'exif-gpslongitude' => 'Dowan-liurid',
'exif-gpsaltitude' => 'Yrdjid',
'exif-gpstimestamp' => 'Am GPS (clag breneenagh)',
'exif-gpsspeedref' => 'Unnid vieauid',
'exif-gpsdatestamp' => 'Date GPS',

'exif-unknowndate' => 'Date gyn enney',

'exif-subjectdistance-value' => '$1 meteryn',

'exif-meteringmode-0' => 'Gyn enney',
'exif-meteringmode-1' => 'Mean',
'exif-meteringmode-255' => 'Elley',

'exif-lightsource-0' => 'Gyn enney',

'exif-focalplaneresolutionunit-2' => 'oarleeyn',

'exif-sensingmethod-1' => 'Neuenmyssit',

'exif-scenecapturetype-1' => 'Reayrt çheerey',
'exif-scenecapturetype-2' => 'Cochaslys',
'exif-scenecapturetype-3' => 'Reayrtys oie',

'exif-gaincontrol-0' => 'Veg',

'exif-contrast-1' => 'Bog',
'exif-contrast-2' => 'Creoi',

'exif-sharpness-1' => 'Bog',
'exif-sharpness-2' => 'Creoi',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => "Kilometeryn 'syn oor",
'exif-gpsspeed-m' => "Meeillaghyn 'syn oor",

# External editor support
'edit-externally' => 'Reagh yn coadan shoh lesh sheeyntagh mooie',
'edit-externally-help' => 'Jeeagh er [//www.mediawiki.org/wiki/Manual:External_editors saraghyn soiaghey seose] son tooilley oayllys.',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'yn clane',
'namespacesall' => 'yn clane',
'monthsall' => 'yn clane',

# Delete conflict
'recreate' => 'Aachroo',

# action=purge
'confirm_purge_button' => 'OK',

# Multipage image navigation
'imgmultigo' => 'Gow!',

# Table pager
'table_pager_first' => 'Yn chied duillag',
'table_pager_last' => "Yn duillag s'jerree",
'table_pager_limit_submit' => 'Gow',
'table_pager_empty' => 'Gyn eiyrtys',

# Auto-summaries
'autosumm-new' => 'Duillag crooit lesh: $1',

# Watchlist editor
'watchlistedit-numitems' => 'Ta {{PLURAL:$1|1 ard-ennym|$1 ard-ennym|$1 ard-ennym|$1 ard-enmyn}} ayns dty rolley arrey, magh voish duillagyn resoonaght.',
'watchlistedit-noitems' => 'Cha nel ard-enmyn ayns dty rolley arrey.',
'watchlistedit-normal-title' => 'Rolley arrey y reaghey',
'watchlistedit-normal-legend' => 'Enmyn y scughey ass y rolley arrey',
'watchlistedit-normal-submit' => 'Enmyn y scughey',
'watchlistedit-normal-done' => 'Va {{PLURAL:$1|1 ard-ennym|$1 ard-enmyn}} scrysst ass dty rolley arrey:',
'watchlistedit-raw-titles' => 'Enmyn:',

# Watchlist editing tools
'watchlisttools-view' => 'Jeeagh er caghlaaghyn conastagh',
'watchlisttools-edit' => 'Jeeagh er as reagh yn rolley arrey',
'watchlisttools-raw' => 'Reagh yn aw-rolley arrey',

# Special:Version
'version' => 'Lhieggan',
'version-specialpages' => 'Duillagyn er lheh',
'version-other' => 'Elley',
'version-version' => '(Lhieggan $1)',
'version-license' => 'Kiedoonys',
'version-software-version' => 'Lhieggan',

# Special:FileDuplicateSearch
'fileduplicatesearch-filename' => 'Ennym y choadan:',
'fileduplicatesearch-submit' => 'Ronsee',

# Special:SpecialPages
'specialpages' => 'Duillagyn er lheh',
'specialpages-group-maintenance' => 'Coontaghyn meansal',
'specialpages-group-other' => 'Duillagyn elley er lheh',
'specialpages-group-login' => 'Log stiagh / croo coontys',
'specialpages-group-users' => 'Ymmydeyryn as kiartyn',

# Special:Tags
'tags-edit' => 'reaghey',

# New logging system
'rightsnone' => '(veg)',

# Search suggestions
'searchsuggest-search' => 'Ronsaghey',
'searchsuggest-containing' => 'goaill stiagh...',

);

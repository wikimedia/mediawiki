<?php
# $Id$
#
require_once( "LanguageUtf8.php" );


/* private */ $wgQuickbarSettingsLt = array(
        "Nerodyti", "Fiksuoti kair&#279;je", "Fiksuoti de&#353;in&#279;je", "Plaukiojantis kair&#279;je"
);

/* private */ $wgSkinNamesLt = array(
        'standard' => "Standartin&#279;",
        'nostalgia' => "Nostalgija",
        'cologneblue' => "Kiolno M&#279;lyna",
        'davinci' => "Da Vin&#269;i",
        'mono' => "Mono",
        'monobook' => "MonoBook",
	'myskin' => "MySkin"
);

/* private */ $wgMathNamesLt = array(
        "Always render PNG",
        "HTML if very simple or else PNG",
        "HTML if possible or else PNG",
        "Leave it as TeX (for text browsers)",
        "Recommended for modern browsers"
);

/* private */ $wgDateFormatsLt = array(
        "Nesvarbu",
        "Sausio 15, 2001",
        "15 Sausio 2001",
        "2001 Sausio 15",
        "2001-01-15"
);

/* private */ $wgWeekdayNamesLt = array(
        "Sekmadienis", "Pirmadienis", "Antradienis", "Tre&#269;iadienis", "Ketvirtadienis",
        "Penktadienis", "&#352;e&#353;tadienis"
);

/* private */ $wgMonthNamesLt = array(
        "Sausio", "Vasario", "Kovo", "Baland&#382;io", "Gegu&#382&#279;s", "Bir&#382elio",
        "Liepos", "Rugpj&#363;&#269;io", "Rugs&#279;jo", "Spalio", "Lapkri&#269io",
        "Gruod&#382;io"
);

/* private */ $wgMonthAbbreviationsLt = array(
        "Sau", "Vas", "Kov", "Bal", "Geg", "Bir", "Lie", "Rgp",
        "Rgs", "Spa", "Lap", "Gru"
);


# Note to translators: 
#   Please include the English words as synonyms.  This allows people 
#   from other wikis to contribute more easily.
# 
/* private */ $wgMagicWordsLt = array(
#   ID                                 CASE  SYNONYMS
    MAG_REDIRECT             => array( 0,    "#redirect"              ),
    MAG_NOTOC                => array( 0,    "__NOTOC__"              ),
    MAG_FORCETOC             => array( 0,    "__FORCETOC__"           ),
    MAG_NOEDITSECTION        => array( 0,    "__NOEDITSECTION__"      ),
    MAG_START                => array( 0,    "__START__"              ),
    MAG_CURRENTMONTH         => array( 1,    "CURRENTMONTH"           ),
    MAG_CURRENTMONTHNAME     => array( 1,    "CURRENTMONTHNAME"       ),
    MAG_CURRENTDAY           => array( 1,    "CURRENTDAY"             ),
    MAG_CURRENTDAYNAME       => array( 1,    "CURRENTDAYNAME"         ),
    MAG_CURRENTYEAR          => array( 1,    "CURRENTYEAR"            ),
    MAG_CURRENTTIME          => array( 1,    "CURRENTTIME"            ),
    MAG_NUMBEROFARTICLES     => array( 1,    "NUMBEROFARTICLES"       ),
    MAG_CURRENTMONTHNAMEGEN  => array( 1,    "CURRENTMONTHNAMEGEN"    ),
        MAG_MSG                  => array( 0,    "MSG:"                   ),
        MAG_SUBST                => array( 0,    "SUBST:"                 ),
    MAG_MSGNW                => array( 0,    "MSGNW:"                 ),
        MAG_END                  => array( 0,    "__END__"                ),
    MAG_IMG_THUMBNAIL        => array( 1,    "thumbnail", "thumb"     ),
    MAG_IMG_RIGHT            => array( 1,    "right"                  ),
    MAG_IMG_LEFT             => array( 1,    "left"                   ),
    MAG_IMG_NONE             => array( 1,    "none"                   ),
    MAG_IMG_WIDTH            => array( 1,    "$1px"                   ),
    MAG_IMG_CENTER           => array( 1,    "center", "centre"       ),
    MAG_INT                  => array( 0,    "INT:"                   ),
    MAG_SITENAME             => array( 1,    "SITENAME"               ),
    MAG_NS                   => array( 0,    "NS:"                    ),
        MAG_LOCALURL             => array( 0,    "LOCALURL:"              ),
        MAG_LOCALURLE            => array( 0,    "LOCALURLE:"             ),
        MAG_SERVER               => array( 0,    "SERVER"                 )
);


$wgAllMessagesLt = array(
'1movedto2' => "Straipsnis '$1' pervadintas į '$2'",
#'1movedto2_redir' => "$1 moved to $2 over redirect",
#'Monobook.css' => "/* edit this file to customize the monobook skin for the entire site */",
'Monobook.js' => "/* tooltips and access keys */
ta = new Object();
ta['pt-userpage'] = new Array('.','Mano vartotojo puslapis'); 
ta['pt-anonuserpage'] = new Array('.','Vartotojo puslapis jūsų ip ardesui'); 
ta['pt-mytalk'] = new Array('n','Mano diskusijų puslapis'); 
ta['pt-anontalk'] = new Array('n','Diskusijos apie pakeitimus, darytus naudojant šį IP adresą'); 
ta['pt-preferences'] = new Array('','Mano nustatymai'); 
ta['pt-watchlist'] = new Array('l','Sąrašas straipsnių, kuriuos jūs pasirinkote stebėti.'); 
ta['pt-mycontris'] = new Array('y','Mano darytų keitimų sąrašas'); 
ta['pt-login'] = new Array('o','Rekomenduojame prisijungti, nors tai nėra privaloma.'); 
ta['pt-anonlogin'] = new Array('o','Rekomenduojame prisijungti, nors tai nėra privaloma.'); 
ta['pt-logout'] = new Array('o','Atsijungti'); 
ta['ca-talk'] = new Array('t','Diskusijos apie šį straipsnį'); 
ta['ca-edit'] = new Array('e','Jūs galite redaguoti šį straipsnį. Nepamirškite peržiūrėti pakeitimų prieš užsaugodami.'); 
ta['ca-addsection'] = new Array('+','Pradėti naują temą diskusijose.'); 
ta['ca-viewsource'] = new Array('e','Puslapis yra užrakintas. Galite tik pažiūrėti turinį.'); 
ta['ca-history'] = new Array('h','Ankstesnės puslapio versijos.'); 
ta['ca-protect'] = new Array('=','Uždrausti šį puslapį'); 
ta['ca-delete'] = new Array('d','Ištrinti šį puslapį'); 
ta['ca-undelete'] = new Array('d','Atstatyti puslapį su visais darytais keitimais'); 
ta['ca-move'] = new Array('m','Pervadinti straipsnį'); 
ta['ca-nomove'] = new Array('','Neturite teisių pervadinti šį straipsnį'); 
ta['ca-watch'] = new Array('w','Pridėti straipsnį į stebimųjų sąrašą'); 
ta['ca-unwatch'] = new Array('w','Išimti straipsnį iš stebimųjų sąrašo'); 
ta['search'] = new Array('f','Ieškoti lietuviškoje Wikipedijoje'); 
ta['p-logo'] = new Array('','Į pradinį puslapį'); 
ta['n-mainpage'] = new Array('z','Apsilankykite pradiniame puslapyje'); 
ta['n-portal'] = new Array('','About the project, what you can do, where to find things'); 
ta['n-currentevents'] = new Array('','Find background information on current events'); 
ta['n-recentchanges'] = new Array('r','Sąrašas paskutinių keitimų.'); 
ta['n-randompage'] = new Array('x','Parinkti atsitiktinį straipsnį'); 
ta['n-help'] = new Array('','Vieta, kur rasite rūpimus atsakymus.'); 
ta['n-sitesupport'] = new Array('','Aukokite projektui'); 
ta['t-whatlinkshere'] = new Array('j','Sąrašas straipsnių, rodančių į čia'); 
ta['t-recentchangeslinked'] = new Array('k','Paskutiniai keitimai straipsniuose, pasiekiamuose iš šio straipsnio'); 
ta['feed-rss'] = new Array('','RSS feed for this page'); 
ta['feed-atom'] = new Array('','Atom feed for this page'); 
ta['t-contributions'] = new Array('','Pažiūrėti vartotojo įnašą - darytus keitimus'); 
ta['t-emailuser'] = new Array('','Siųsti el.laišką vartotojui'); 
ta['t-upload'] = new Array('u','Įdėti paveikslėlį ar media failą'); 
ta['t-specialpages'] = new Array('q','Specialiųjų puslapių sąrašas'); 
ta['ca-nstab-main'] = new Array('c','Pereiti į straipsnio turinį'); 
ta['ca-nstab-user'] = new Array('c','Rodyti vartotojo puslapį'); 
ta['ca-nstab-media'] = new Array('c','Rodyti media puslapį'); 
ta['ca-nstab-special'] = new Array('','Šis puslapis yra specialusis - jo negalima redaguoti.'); 
ta['ca-nstab-wp'] = new Array('a','Rodyti projekto puslapį'); 
ta['ca-nstab-image'] = new Array('c','Rodyti paveikslėlio puslapį'); 
ta['ca-nstab-mediawiki'] = new Array('c','Rodyti sisteminį pranešimą'); 
ta['ca-nstab-template'] = new Array('c','Rodyti šabloną'); 
ta['ca-nstab-help'] = new Array('c','Rodyti pagalbos puslapį'); 
ta['ca-nstab-category'] = new Array('c','Rodyti kategorijos puslapį');",
'about' => "Apie",
'aboutpage' => "Wikipedia:About",
'aboutsite'      => "Apie Wikipediją",
#'accesskey-compareselectedversions' => "v",
#'accesskey-minoredit' => "i",
#'accesskey-preview' => "p",
#'accesskey-save' => "s",
#'accesskey-search' => "f",
'accmailtext' => "Vartotojo '$1' slaptažodis nusiųstas į $2.",
'accmailtitle' => "Slaptažodis išsiųstas.",
#'acct_creation_throttle_hit' => "Sorry, you have already created $1 accounts. You can't make any more.",
'actioncomplete' => "Veiksmas atliktas",
'addedwatch' => "Pridėta prie Stebimų",
'addedwatchtext' => "Straipsnis \"$1\" pridėtas į [[Special:Watchlist|stebimųjų sąrašą]].
Būsimi straipsnio bei atitinkamo diskusijų puslapio pakeitimai bus rodomi stebimųjų puslapių sąraše,
taip pat bus '''paryškinti''' [[Special:Recentchanges|naujausių keitimų sąraše]] kad išsiskirtų iš kitų straipsnių.

<p>Jei bet kuriuo metu užsinorėtumėte nustoti stebėti straipsnį, spustelkite \"nebestebėti\" viršutiniame meniu.",
#'addsection' => "+",
'administrators' => "Wikipedia:Administrators",
'affirmation' => "Aš patvirtinu, kad šio failo autorius ar teisių turėtojas
sutinka jog failas bus viešinamas $1 licenzijos sąlygomis.",
'all' => "visus",
'allmessages' => "Visi sistemos tekstai bei pranešimai",
'allmessagestext' => "Čia pateikiami visi sisteminiai tekstai bei pranešimai, esantys MediaWiki: vardų ervėje.",
'allpages' => "Visi straipsniai",
'alphaindexline' => "Nuo $1 iki $2",
'alreadyloggedin' => "<font color=red><b>Jūs jau esate prisijungęs kaip vartotojas User $1!</b></font><br />",
'alreadyrolled' => "Nepavyko atmesti paskutinio [[User:$2|$2]] ([[User talk:$2|Diskusijos]]) daryto straipsnio [[$1]] keitimo; kažkas jau pakeitė straipsnį arba suspėjo pirmas atmesti keitimą. 

Paskutimas keitimas darytas vartotojo [[User:$3|$3]] ([[User talk:$3|Diskusijos]]).",
'ancientpages' => "Seniausi straipsniai",
#'and' => "and",
'anontalk' => "Šio IP diskusijų puslapis",
#'anontalkpagetext' => "----''This is the discussion page for an anonymous user who has not created an account yet or who does not use it. We therefore have to use the numerical [[IP address]] to identify him/her. Such an IP address can be shared by several users. If you are an anonymous user and feel that irrelevant comments have been directed at you, please [[Special:Userlogin|create an account or log in]] to avoid future confusion with other anonymous users.'' ",
#'anonymous' => "Anonymous user(s) of Wikipedia",
#'article' => "Content page",
'articleexists' => "Straipsnis tokiu pavadinimu jau egzistuoja
arba pasirinktas vardas yra neteisingas.
Pasirinkite kitą pavadinimą.",
'articlepage' => "Rodyti straipsnį",
#'asksql' => "SQL query",
/*#'asksqltext' => "Use the form below to make a direct query of the
database.
Use single quotes ('like this') to delimit string literals.
This can often add considerable load to the server, so please use
this function sparingly.",*/
'autoblocker' => "Automatinis užblokavimas, nes dalinatės IP adresu su vartotoju \"$1\". Priežastis - \"$2\".",
#'badarticleerror' => "This action cannot be performed on this page.",
#'badfilename' => "Image name has been changed to \"$1\".",
'badfiletype' => "\".$1\" yra nerekomenduojamas paveikslėlio bylos formatas.",
#'badipaddress' => "Invalid IP address",
#'badquery' => "Badly formed search query",
/*#'badquerytext' => "We could not process your query.
This is probably because you have attempted to search for a
word fewer than three letters long, which is not yet supported.
It could also be that you have mistyped the expression, for
example \"fish and and scales\".
Please try another query.",*/
'badretype' => "Įvesti slaptažodžiai nesutampa.",
#'badtitle' => "Bad title",
/*#'badtitletext' => "The requested page title was invalid, empty, or
an incorrectly linked inter-language or inter-wiki title.",*/
#'blanknamespace' => "(Main)",
'blockedtext' => "Your user name or IP address has been blocked by $1.
The reason given is this:<br />''$2''<p>You may contact $1 or one of the other
[[Wikipedia:Administrators|administrators]] to discuss the block.

Note that you may not use the \"email this user\" feature unless you have a valid email address registered in your [[Special:Preferences|user preferences]].

Your IP address is $3. Please include this address in any queries you make.
",
#'blockedtitle' => "User is blocked",
'blockip' => "Blokuoti vartotoją",
'blockipsuccesssub' => "Užblokavimas pavyko",
/*#'blockipsuccesstext' => "\"$1\" has been blocked.
<br />See [[Special:Ipblocklist|IP block list]] to review blocks.",
'blockiptext' => "Use the form below to block write access
from a specific IP address or username.
This should be done only only to prevent vandalism, and in
accordance with [[Wikipedia:Policy|policy]].
Fill in a specific reason below (for example, citing particular
pages that were vandalized).",*/
'blocklink' => "blokuoti",
#'blocklistline' => "$1, $2 blocked $3 (expires $4)",
#'blocklogentry' => "blocked \"$1\" with an expiry time of $2",
#'blocklogpage' => "Block_log",
'blocklogtext' => "This is a log of user blocking and unblocking actions. Automatically 
blocked IP addresses are not be listed. See the [[Special:Ipblocklist|IP block list]] for
the list of currently operational bans and blocks.",
'bold_sample' => "Paryškintas tekstas",
'bold_tip' => "Paryškinti tekstą",
'booksources' => "Knygų paieška",
'booksourcetext' => "Below is a list of links to other sites that
sell new and used books, and may also have further information
about books you are looking for.Wikipedia is not affiliated with any of these businesses, and
this list should not be construed as an endorsement.",
'brokenredirects' => "Peradresavimai į niekur",
'brokenredirectstext' => "Žemiau išvardinti peradresavimo puslapiai rodo į neegzistuojančius straipsnius.",
'bugreports' => "Pranešti apie klaidą",
'bugreportspage' => "Wikipedia:Bug_reports",
#'bureaucratlog' => "Bureaucrat_log",
#'bureaucratlogentry' => "Rights for user \"$1\" set \"$2\"",
/*#'bureaucrattext' => "The action you have requested can only be
performed by sysops with  \"bureaucrat\" status.",*/
#'bureaucrattitle' => "Bureaucrat access required",
#'bydate' => "by date",
#'byname' => "by name",
#'bysize' => "by size",
#'cachederror' => "The following is a cached copy of the requested page, and may not be up to date.",
'cancel' => "Atšaukti",
'cannotdelete' => "Nepavyko ištrinti nurodyto straipsnio ar paveikslėlio. (Gali būti, kad kažkas kitas ištrynė pirmas)",
#'cantrollback' => "Cannot revert edit; last contributor is only author of this page.",
'categories' => "Kategorijos",
'category' => "kategorija",
'category_header' => "Kategorijos \"$1\" straipsniai",
'categoryarticlecount' => "Kategorijoje straipsnių - $1",
'changepassword' => "Pakeisti slaptažodį",
#'changes' => "changes",
#'clearyourcache' => "'''Note:''' After saving, you have to clear your browser cache to see the changes: '''Mozilla:''' click ''reload''(or ''ctrl-r''), '''IE / Opera:''' ''ctrl-f5'', '''Safari:''' ''cmd-r'', '''Konqueror''' ''ctrl-r''.",
#'columns' => "Columns",
#'commentedit' => " (comment)",
'compareselectedversions' => "Palyginti pasirinktas versijas",
'confirm' => "Tvirtinu",
'confirmcheck' => "Taip, aš tikrai noriu tai ištrinti.",
'confirmdelete' => "Trynimo veiksmo patvirtinimas",
'confirmdeletetext' => "Jūs pasirinkote ištrinti straipsnį ar paveikslėlį
kartu su visa istorija iš duomenų bazės.
Prašome patvirtinti kad jūs norite tai padaryti,
žinote kokios yra veiksmo pasekmės,
ir kad jūs tai darote nenusižengdamas 
[[Wikipedia:Policy|Wikipedijos Politikai]].",
'confirmprotect' => "Užrakinimo patvirtinimas",
'confirmprotecttext' => "Ar jūs tikrai norite užrakinti šį straipsnį?",
'confirmunprotect' => "Atrakinimo patvirtinimas",
'confirmunprotecttext' => "Ar tikrai norite atrakinti šį straipsnį?",
#'contextchars' => "Characters of context per line",
#'contextlines' => "Lines to show per hit",
'contribslink' => "įnašas",
#'contribsub' => "For $1",
'contributions' => "Vartotojo indėlis",
'copyright' => "Turinys pateikiamas su $1 licenzija.",
'copyrightpage' => "Wikipedia:Copyrights",
'copyrightpagename' => "Wikipedia copyright",
'copyrightwarning' => "Atkreipkite dėmesį, kad viskam, kas patenka į Wikipediją, yra taikoma GNU Laisvos Documentacijos Licenzija
(detaliau - $1).
Jei nepageidaujate, kad jūsų įvestas turinys būtų negailestingai redaguojamas ir platinamas, nerašykite čia.<br>
Jūs taip pat pasižadate, kad tai jūsų pačių rašytas turinys arba kopijuotas iš viešų ar panašių nemokamų šaltinių.
<strong>NEKOPIJUOKITE AUTORINĖMIS TEISĖMIS APSAUGOTŲ DARBŲ BE LEIDIMO!</strong>",
#'couldntremove' => "Couldn't remove item '$1'...",
'createaccount' => "Sukurti vartotoją",
#'createaccountmail' => "by email",
#'creditspage' => "Page credits",
#'cur' => "cur",
'currentevents' => "-",
'currentrev' => "Dabartinė versija",
#'databaseerror' => "Database error",
'dateformat' => "Datos formatas",
'deadendpages' => "Straipsniai-aklavietės",
#'debug' => "Debug",
'defaultns' => "Pagal nutylėjimą ieškoti šiose vardų erdvėse:",
'defemailsubject' => "Wikipedia e-mail",
'delete' => "trinti",
'deletecomment' => "Trynimo priežastis",
'deletedarticle' => "ištrinta \"$1\"",
/*#'deletedtext' => "\"$1\" has been deleted.
See $2 for a record of recent deletions.",*/
#'deleteimg' => "del",
#'deletepage' => "Delete page",
'deletesub' => "(Trinama \"$1\")",
'deletethispage' => "Ištrinti straipsnį",
#'deletionlog' => "deletion log",
#'dellogpage' => "Deletion_log",
/*#'dellogpagetext' => "Below is a list of the most recent deletions.
All times shown are server time (UTC).
<ul>
</ul>
",*/
#'developerspheading' => "For developer use only",
/*#'developertext' => "The action you have requested can only be
performed by users with \"developer\" status.
See $1.",*/
#'developertitle' => "Developer access required",
#'diff' => "diff",
'difference' => "(Skirtumai tarp versijų)",
'disambiguations' => "Nukreipiamieji puslapiai",
'disambiguationspage' => "Wikipedia:Links_to_disambiguating_pages",
'disambiguationstext' => "Žemiau išvardinti straipsniai, rodantys į <i>nukreipiamuosius puslapius</i>. Nuorodos turėtų būti patikslintos kad rodytų į konkretų straipsnį.<br />Puslapis skaitomas nukreipiamuoju, jei nuoroda į jį yra $1.<br />",
'disclaimerpage' => "Wikipedia:General_disclaimer",
'disclaimers' => "Jokių Garantijų",
'doubleredirects' => "Dvigubi peradresavimai",
/*#'doubleredirectstext' => "<b>Attention:</b> This list may contain false positives. That usually means there is additional text with links below the first #REDIRECT.<br />
Each row contains links to the first and second redirect, as well as the first line of the second redirect text, usually giving the \"real\" target page, which the first redirect should point to.",*/
'edit' => "Redaguoti",
#'editcomment' => "The edit comment was: \"<i>$1</i>\".",
'editconflict' => "Išpręskite konfliktą: $1",
#'editcurrent' => "Edit the current version of this page",
'edithelp' => "Kaip Redaguoti",
'edithelppage' => "Help:Editing",
'editing' => "Taisomas straipsnis - $1",
'editingsection' => "Taisomas straipsnis - $1 (skyrius)",
'editingcomment' => "Taisomas straipsnis - $1 (comment)",
'editingold' => "<strong>ĮSPĖJIMAS: Jūs keičiate ne naujausią puslapio versiją.
Jei išsaugosite savo keitimus, prieš tai daryti pakeitimai pradings.</strong>",
'editsection' => "taisyti",
'editthispage' => "Taisyti straipsnį",
#'emailflag' => "Disable e-mail from other users",
'emailforlost' => "* Elektroninio pašto adresas nėra privalomas. Tačiau jei įvesite, kiti vartotojai galės siųsti jums laiškus nesužinodami adreso. Taip pat pašto adresas gelbsti pamiršus slaptažodį.",
#'emailfrom' => "From",
#'emailmessage' => "Message",
#'emailpage' => "E-mail user",
/*#'emailpagetext' => "If this user has entered a valid e-mail address in
his or her user preferences, the form below will send a single message.
The e-mail address you entered in your user preferences will appear
as the \"From\" address of the mail, so the recipient will be able
to reply.",*/
#'emailsend' => "Send",
#'emailsent' => "E-mail sent",
#'emailsenttext' => "Your e-mail message has been sent.",
#'emailsubject' => "Subject",
#'emailto' => "To",
'emailuser' => "Rašyti laišką",
#'emptyfile' => "The file you uploaded seems to be empty. This might be due to a typo in the file name. Please check whether you really want to upload this file.",
/*#'enterlockreason' => "Enter a reason for the lock, including an estimate
of when the lock will be released",*/
#'error' => "Error",
#'errorpagetitle' => "Error",
#'exbeforeblank' => "content before blanking was:",
#'exblank' => "page was empty",
'excontent' => "buvęs turinys:",
/*#'explainconflict' => "Someone else has changed this page since you
started editing it.
The upper text area contains the page text as it currently exists.
Your changes are shown in the lower text area.
You will have to merge your changes into the existing text.
<b>Only</b> the text in the upper text area will be saved when you
press \"Save page\".
<p>",*/
'export' => "Eksportuoti puslapius",
#'exportcuronly' => "Include only the current revision, not the full history",
/*#'exporttext' => "You can export the text and editing history of a particular
page or set of pages wrapped in some XML; this can then be imported into another
wiki running MediaWiki software, transformed, or just kept for your private
amusement.",*/
'extlink_sample' => "http://www.pavyzdys.lt Nuorodos pavadinimas",
'extlink_tip' => "Išorinė nuoroda (nepamirškite http:// prefikso)",
#'faq' => "FAQ",
'faqpage' => "Wikipedia:FAQ",
#'feedlinks' => "Feed:",
#'filecopyerror' => "Could not copy file \"$1\" to \"$2\".",
#'filedeleteerror' => "Could not delete file \"$1\".",
#'filedesc' => "Summary",
#'fileexists' => "A file with this name exists already, please check $1 if you are not sure if you want to change it.",
#'filename' => "Filename",
#'filenotfound' => "Could not find file \"$1\".",
#'filerenameerror' => "Could not rename file \"$1\" to \"$2\".",
#'filesource' => "Source",
#'filestatus' => "Copyright status",
'fileuploaded' => "File \"$1\" uploaded successfully.
Please follow this link: $2 to the description page and fill
in information about the file, such as where it came from, when it was
created and by whom, and anything else you may know about it.",
#'formerror' => "Error: could not submit form",
'tagline'       => "<small>Straipsnis iš Wikipedijos, laisvosios enciklopedijos.</small>",
#'getimagelist' => "fetching image list",
'go' => "Rodyk",
'googlesearch' => "
<!-- SiteSearch Google -->
<FORM method=GET action=\"http://www.google.com/search\">
<TABLE bgcolor=\"#FFFFFF\"><tr><td>
<A HREF=\"http://www.google.com/\">
<IMG SRC=\"http://www.google.com/logos/Logo_40wht.gif\"
border=\"0\" ALT=\"Google\"></A>
</td>
<td>
<INPUT TYPE=text name=q size=31 maxlength=255 value=\"$1\">
<INPUT type=submit name=btnG VALUE=\"Google Search\">
<font size=-1>
<input type=hidden name=domains value=\"http://lt.wikipedia.org\"><br /><input type=radio name=sitesearch value=\"\"> WWW <input type=radio name=sitesearch value=\"http://lt.wikipedia.org\" checked> http://lt.wikipedia.org <br />
<input type='hidden' name='ie' value='$2'>
<input type='hidden' name='oe' value='$2'>
</font>
</td></tr></TABLE>
</FORM>
<!-- SiteSearch Google -->",
#'guesstimezone' => "Fill in from browser",
'headline_sample' => "Skyriaus Pavadinimas",
'headline_tip' => "Skyriaus pavadinimas (2-o lygio)",
'help' => "Pagalba",
'helppage' => "Help:Contents",
'hide' => "paslėpti",
'hidetoc' => "slėpti",
'hist' => "ist",
/*#'histlegend' => "Diff selection: mark the radio boxes of the versions to compare and hit enter or the button at the bottom.<br/>
Legend: (cur) = difference with current version,
(last) = difference with preceding version, M = minor edit.",*/
'history' => "Straipsnio istorija",
#'history_copyright' => "-",
'history_short' => "Istorija",
#'historywarning' => "Warning: The page you are about to delete has a history: ",
'hr_tip' => "Horizontali linija (nepernaudoti)",
#'ignorewarning' => "Ignore warning and save file anyway.",
#'illegalfilename' => "The filename \"$1\" contains characters that are not allowed in page titles. Please rename the file and try uploading it again.",
#'ilshowmatch' => "Show all images with names matching",
#'ilsubmit' => "Search",
'image_sample' => "Pavyzdys.jpg",
'image_tip' => "Įdėti paveiksėlį",
#'imagelinks' => "Image links",
'imagelist' => "Paveikslėlių sąrašas",
#'imagelisttext' => "Below is a list of $1 images sorted $2.",
#'imagepage' => "View image page",
#'imagereverted' => "Revert to earlier version was successful.",
#'imgdelete' => "del",
#'imgdesc' => "desc",
/*#'imghistlegend' => "Legend: (cur) = this is the current image, (del) = delete
this old version, (rev) = revert to this old version.
<br /><i>Click on date to see image uploaded on that date</i>.",*/
#'imghistory' => "Image history",
#'imglegend' => "Legend: (desc) = show/edit image description.",
#'import' => "Import pages",
#'importfailed' => "Import failed: $1",
#'importhistoryconflict' => "Conflicting history revision exists (may have imported this page before)",
#'importnotext' => "Empty or no text",
#'importsuccess' => "Import succeeded!",
#'importtext' => "Please export the file from the source wiki using the Special:Export utility, save it to your disk and upload it here.",
#'info_short' => "Information",
#'infobox' => "Click a button to get an example text",
#'infobox_alert' => "Please enter the text you want to be formatted.\n It will be shown in the infobox for copy and pasting.\nExample:\n$1\nwill become:\n$2",
#'infosubtitle' => "Information for page",
#'internalerror' => "Internal error",
#'intl' => "Interlanguage links",
#'ip_range_invalid' => "Invalid IP range.",
#'ipaddress' => "IP Address/username",
#'ipb_expiry_invalid' => "Expiry time invalid.",
#'ipbexpiry' => "Expiry",
'ipblocklist' => "Blokuotų IP adresų bei vartotojų sąrašas",
#'ipbreason' => "Reason",
#'ipbsubmit' => "Block this user",
#'ipusubmit' => "Unblock this address",
#'ipusuccess' => "\"$1\" unblocked",
#'isbn' => "ISBN",
#'isredirect' => "redirect page",
'italic_sample' => "Tekstas kursyvu",
'italic_tip' => "Išskirti kursyvu",
#'iteminvalidname' => "Problem with item '$1', invalid name...",
#'largefile' => "It is recommended that images not exceed 100k in size.",
#'last' => "last",
'lastmodified' => "Paskutinį kartą keista $1.",
#'lastmodifiedby' => "This page was last modified $1 by $2.",
'lineno' => "Eilutė $1:",
'link_sample' => "Straipsnio pavadinimas",
'link_tip' => "Vidinė nuoroda",
'linklistsub' => "(Nuorodų sąrašas)",
#'linkshere' => "The following pages link to here:",
#'linkstoimage' => "The following pages link to this image:",
'linktrail' => "/^([a-z]+)(.*)\$/sD",
#'listadmins' => "Admins list",
#'listform' => "list",
'listusers' => "Vartotojų sąrašas",
#'loadhist' => "Loading page history",
#'loadingrev' => "loading revision for diff",
#'localtime' => "Local time display",
#'lockbtn' => "Lock database",
#'lockconfirm' => "Yes, I really want to lock the database.",
#'lockdb' => "Lock database",
#'lockdbsuccesssub' => "Database lock succeeded",
/*#'lockdbsuccesstext' => "The database has been locked.
<br />Remember to remove the lock after your maintenance is complete.",
#'lockdbtext' => "Locking the database will suspend the ability of all
users to edit pages, change their preferences, edit their watchlists, and
other things requiring changes in the database.
Please confirm that this is what you intend to do, and that you will
unlock the database when your maintenance is done.",*/
#'locknoconfirm' => "You did not check the confirmation box.",
'login' => "Prisijungti",
#'loginend' => "&nbsp;",
#'loginerror' => "Login error",
#'loginpagetitle' => "User login",
#'loginproblem' => "<b>There has been a problem with your login.</b><br />Try again!",
'loginprompt' => "Norėdami prisijungti prie Wikipedijos, privalote įsijungti '''cookies''' savo naršyklėje.",
#'loginreqtext' => "You must [[special:Userlogin|login]] to view other pages.",
#'loginreqtitle' => "Login Required",
'loginsuccess' => "Šiuo metu jūs prisijungęs prie Wikipedijos kaip \"$1\".",
'loginsuccesstitle' => "Sėkmingai prisijungėte",
'logout' => "Atsijungti",
'logouttext' => "Jūs atsijungėte nuo Wikipedijos.
Galite toliau naudoti Wikipediją anonimiškai arba prisijunkite iš naujo tuo pačiu ar kitu vartotoju.<br>
P.S.:  kai kuriuose puslapiuose ir toliau gali rodyti lyg būtumėte prisijungęs iki tol, kol išvalysite savo naršyklės išsaugotas puslapių kopijas",
#'logouttitle' => "User logout",
'lonelypages' => "Vieniši straipsniai",
'longpages' => "Ilgiausi puslapiai",
/*#'longpagewarning' => "WARNING: This page is $1 kilobytes long; some
browsers may have problems editing pages approaching or longer than 32kb.
Please consider breaking the page into smaller sections.",*/
#'mailerror' => "Error sending mail: $1",
'mailmypassword' => "Siųsti naują slaptažodį paštu",
#'mailnologin' => "No send address",
'mailnologintext' => "You must be <a href=\"{{localurl:Special:Userlogin\">logged in</a>
and have a valid e-mail address in your <a href=\"/wiki/Special:Preferences\">preferences</a>
to send e-mail to other users.",
'mainpage' => "Pradžia",
/*#'mainpagedocfooter' => "Please see [http://meta.wikipedia.org/wiki/MediaWiki_i18n documentation on customizing the interface]
and the [http://meta.wikipedia.org/wiki/MediaWiki_User%27s_Guide User's Guide] for usage and configuration help.",*/
#'mainpagetext' => "Wiki software successfully installed.",
'maintenance' => "Įrankių puslapis",
#'maintenancebacklink' => "Back to Maintenance Page",
#'maintnancepagetext' => "This page includes several handy tools for everyday maintenance. Some of these functions tend to stress the database, so please do not hit reload after every item you fixed ;-)",
#'makesysop' => "Make a user into a sysop",
#'makesysopfail' => "<b>User \"$1\" could not be made into a sysop. (Did you enter the name correctly?)</b>",
#'makesysopname' => "Name of the user:",
#'makesysopok' => "<b>User \"$1\" is now a sysop</b>",
#'makesysopsubmit' => "Make this user into a sysop",
'makesysoptext' => "This form is used by bureaucrats to turn ordinary users into administrators. 
Type the name of the user in the box and press the button to make the user an administrator",
#'makesysoptitle' => "Make a user into a sysop",
/*#'matchtotals' => "The query \"$1\" matched $2 page titles
and the text of $3 pages.",*/
#'math' => "Rendering math",
#'math_bad_output' => "Can't write to or create math output directory",
#'math_bad_tmpdir' => "Can't write to or create math temp directory",
#'math_failure' => "Failed to parse",
#'math_image_error' => "PNG conversion failed; check for correct installation of latex, dvips, gs, and convert",
#'math_lexing_error' => "lexing error",
#'math_notexvc' => "Missing texvc executable; please see math/README to configure.",
'math_sample' => "Įveskite formulę",
#'math_syntax_error' => "syntax error",
'math_tip' => "Matematinė formulė (LaTeX formatu)",
#'math_unknown_error' => "unknown error",
#'math_unknown_function' => "unknown function ",
#'media_sample' => "Example.mp3",
#'media_tip' => "Media file link",
#'minlength' => "Image names must be at least three letters.",
'minoredit' => "Smulkus pataisymas",
'minoreditletter' => "S",
#'mispeelings' => "Pages with misspellings",
#'mispeelingspage' => "List of common misspellings",
#'mispeelingstext' => "The following pages contain a common misspelling, which are listed on $1. The correct spelling might be given (like this).",
/*#'missingarticle' => "The database did not find the text of a page
that it should have found, named \"$1\".

<p>This is usually caused by following an outdated diff or history link to a
page that has been deleted.

<p>If this is not the case, you may have found a bug in the software.
Please report this to an administrator, making note of the URL.",*/
#'missingimage' => "<b>Missing image</b><br /><i>$1</i>",
#'missinglanguagelinks' => "Missing Language Links",
#'missinglanguagelinksbutton' => "Find missing language links for",
#'missinglanguagelinkstext' => "These pages do <i>not</i> link to their counterpart in $1. Redirects and subpages are <i>not</i> shown.",
#'moredotdotdot' => "More...",
'move' => "Pervadinti",
#'movearticle' => "Move page",
#'movedto' => "moved to",
#'movenologin' => "Not logged in",
'movenologintext' => "You must be a registered user and <a href=\"/wiki/Special:Userlogin\">logged in</a>
to move a page.",
#'movepage' => "Move page",
#'movepagebtn' => "Move page",
/*#'movepagetalktext' => "The associated talk page, if any, will be automatically moved along with it '''unless:'''
*You are moving the page across namespaces,
*A non-empty talk page already exists under the new name, or
*You uncheck the box below.

In those cases, you will have to move or merge the page manually if desired.",*/
/*#'movepagetext' => "Using the form below will rename a page, moving all
of its history to the new name.
The old title will become a redirect page to the new title.
Links to the old page title will not be changed; be sure to
[[Special:Maintenance|check]] for double or broken redirects.
You are responsible for making sure that links continue to
point where they are supposed to go.

Note that the page will '''not''' be moved if there is already
a page at the new title, unless it is empty or a redirect and has no
past edit history. This means that you can rename a page back to where
it was just renamed from if you make a mistake, and you cannot overwrite
an existing page.

<b>WARNING!</b>
This can be a drastic and unexpected change for a popular page;
please be sure you understand the consequences of this before
proceeding.",*/
#'movetalk' => "Move \"talk\" page as well, if applicable.",
'movethispage' => "Pervadinti straipsnį",
'mycontris' => "Mano įnašas",
#'mypage' => "My page",
'mytalk' => "mano diskusijos",
'navigation' => "Navigacija",
'nbytes' => "$1 B",
#'nchanges' => "$1 changes",
#'newarticle' => "(New)",
'newarticletext' => "Jūs patekote į neegzistuojančio straipsnio puslapį.
Norėdami sukurti straipsnį, pradėkite žemiau esančiame įvedimo lauke 
(daugiau informacijos [[Wikipedia:Help|pagalbos puslapyje]]).
Jei patekote čia per klaidą, paprasčiausiai spustelkite  naršyklės mygtuką 'atgal' ('''back''').",
#'newmessages' => "You have $1.",
#'newmessageslink' => "new messages",
#'newpage' => "New page",
#'newpageletter' => "N",
'newpages' => "Naujausi straipsniai",
#'newpassword' => "New password",
#'newtitle' => "To new title",
'newusersonly' => " (tik naujiems vartotojams)",
#'newwindow' => "(opens in new window)",
#'next' => "next",
'nextn' => "sekančius $1",
#'nextpage' => "Next page ($1)",
'nlinks' => "$1 k.",
#'noaffirmation' => "You must affirm that your upload does not violate any copyrights.",
#'noarticletext' => "(There is currently no text in this page)",
#'noblockreason' => "You must supply a reason for the block.",
#'noconnect' => "Sorry! The wiki is experiencing some technical difficulties, and cannot contact the database server. <br />$1",
#'nocontribs' => "No changes were found matching these criteria.",
'nocookieslogin' => "Wikipedia uses cookies to log in users. You have cookies disabled. Please enable them and try again.",
'nocookiesnew' => "The user account was created, but you are not logged in. Wikipedia uses cookies to log in users. You have cookies disabled. Please enable them, then log in with your new username and password.",
#'nocreativecommons' => "Creative Commons RDF metadata disabled for this server.",
#'nocredits' => "There is no credits info available for this page.",
#'nodb' => "Could not select database $1",
#'nodublincore' => "Dublin Core RDF metadata disabled for this server.",
#'noemail' => "There is no e-mail address recorded for user \"$1\".",
/*#'noemailtext' => "This user has not specified a valid e-mail address,
or has chosen not to receive e-mail from other users.",*/
#'noemailtitle' => "No e-mail address",
#'nogomatch' => "No page with this exact title exists, trying full text search.",
#'nohistory' => "There is no edit history for this page.",
#'nolinkshere' => "No pages link to here.",
#'nolinkstoimage' => "There are no pages that link to this image.",
#'noname' => "You have not specified a valid user name.",
/* #'nonefound' => "<strong>Note</strong>: unsuccessful searches are
often caused by searching for common words like \"have\" and \"from\",
which are not indexed, or by specifying more than one search term (only pages
containing all of the search terms will appear in the result).",*/
/*#'nospecialpagetext' => "You have requested a special page that is not
recognized by the wiki.",*/
#'nosuchaction' => "No such action",
/*#'nosuchactiontext' => "The action specified by the URL is not
recognized by the wiki",*/
#'nosuchspecialpage' => "No such special page",
/*#'nosuchuser' => "There is no user by the name \"$1\".
Check your spelling, or use the form below to create a new user account.",*/
#'notacceptable' => "The wiki server can't provide data in a format your client can read.",
#'notanarticle' => "Not a content page",
/*#'notargettext' => "You have not specified a target page or user
to perform this function on.",*/
#'notargettitle' => "No target",
'note' => "<strong>Pastaba:</strong>",
#'notextmatches' => "No page text matches",
#'notitlematches' => "No page title matches",
'notloggedin' => "Neprisijungęs",
'nowatchlist' => "Neturite nei vieno stebimo straipsnio.",
#'nowiki_sample' => "Insert non-formatted text here",
#'nowiki_tip' => "Ignore wiki formatting",
'nstab-category' => "Kategorija",
'nstab-help' => "Pagalba",
#'nstab-image' => "Image",
'nstab-main' => "Straipsnis",
#'nstab-media' => "Media",
#'nstab-mediawiki' => "Message",
#'nstab-special' => "Special",
'nstab-template' => "Šablonas",
#'nstab-user' => "User page",
#'nstab-wp' => "About",
#'numauthors' => "Number of distinct authors (article): ",
#'numedits' => "Number of edits (article): ",
#'numtalkauthors' => "Number of distinct authors (discussion page): ",
#'numtalkedits' => "Number of edits (discussion page): ",
#'numwatchers' => "Number of watchers: ",
#'nviews' => "$1 views",
#'ok' => "OK",
#'oldpassword' => "Old password",
#'orig' => "orig",
#'orphans' => "Orphaned pages",
#'othercontribs' => "Based on work by $1.",
'otherlanguages' => "Kitomis kalbomis",
#'others' => "others",
#'pagemovedsub' => "Move succeeded",
#'pagemovedtext' => "Page \"[[$1]]\" moved to \"[[$2]]\".",
'pagetitle' => "$1 - Wikipedia",
'passwordremindertext' => "Someone (probably you, from IP address $1)
requested that we send you a new Wikipedia login password.
The password for user \"$2\" is now \"$3\".
You should log in and change your password now.",
'passwordremindertitle' => "Password reminder from Wikipedia",
/*#'passwordsent' => "A new password has been sent to the e-mail address
registered for \"$1\".
Please log in again after you receive it.",*/
'perfcached' => "Rodoma išsaugota duomenų kopija, todėl duomenys gali būti ne patys naujausi:",
/*#'perfdisabled' => "Sorry! This feature has been temporarily disabled
because it slows the database down to the point that no one can use
the wiki.",*/
#'perfdisabledsub' => "Here's a saved copy from $1:",
#'personaltools' => "Personal tools",
#'popularpages' => "Popular pages",
'portal' => "-",
'portal-url' => "Wikipedia:Community Portal",
#'postcomment' => "Post a comment",
'poweredby' => "Wikipedia is powered by [http://www.mediawiki.org/ MediaWiki], an open source wiki engine.",
#'powersearch' => "Search",
/*#'powersearchtext' => "
Search in namespaces :<br />
$1<br />
$2 List redirects &nbsp; Search for $3 $9",*/
'preferences' => "Nustatymai",
'prefs-help-userdata' => "* <strong>Real name</strong> (optional): if you choose to provide it this will be used for giving you attribution for your work.<br/>
* <strong>Email</strong> (optional): Enables people to contact you through the website without you having to reveal your 
email address to them, and it can be used to send you a new password if you forget it.",
#'prefs-misc' => "Misc settings",
#'prefs-personal' => "User data",
#'prefs-rc' => "Recent changes and stub display",
'prefslogintext' => "Jūs esate prisijungęs kaip \"$1\".
Jūsų vidinis numeris yra $2.

<!--See [[Wikipedia:User preferences help]] for help deciphering the options.-->",
#'prefsnologin' => "Not logged in",
'prefsnologintext' => "You must be <a href=\"/wiki/Special:Userlogin\">logged in</a>
to set user preferences.",
#'prefsreset' => "Preferences have been reset from storage.",
'preview' => "Peržiūra",
/*#'previewconflict' => "This preview reflects the text in the upper
text editing area as it will appear if you choose to save.",*/
'previewnote' => "Nepamirškite, kad tai tik peržiūra, pakeitimai dar nėra išsaugoti!",
'prevn' => "ankstesnius $1",
'printableversion' => "Versija spausdinimui",
'printsubtitle' => "(From http://lt.wikipedia.org)",
#'protect' => "Protect",
#'protectcomment' => "Reason for protecting",
#'protectedarticle' => "protected $1",
#'protectedpage' => "Protected page",
'protectedpagewarning' => "WARNING:  This page has been locked so that only
users with sysop privileges can edit it. Be sure you are following the
<a href='/w/wiki.phtml/Wikipedia:Protected_page_guidelines'>protected page
guidelines</a>.",
'protectedtext' => "This page has been locked to prevent editing; there are
a number of reasons why this may be so, please see
[[Wikipedia:Protected page]].

You can view and copy the source of this page:",
#'protectlogpage' => "Protection_log",
'protectlogtext' => "Below is a list of page locks/unlocks.
See [[Wikipedia:Protected page]] for more information.",
#'protectpage' => "Protect page",
#'protectreason' => "(give a reason)",
#'protectsub' => "(Protecting \"$1\")",
#'protectthispage' => "Protect this page",
#'proxyblocker' => "Proxy blocker",
#'proxyblockreason' => "Your IP address has been blocked because it is an open proxy. Please contact your Internet service provider or tech support and inform them of this serious security problem.",
#'proxyblocksuccess' => "Done.",
#'qbbrowse' => "Browse",
#'qbedit' => "Edit",
#'qbfind' => "Find",
#'qbmyoptions' => "My pages",
#'qbpageinfo' => "Context",
#'qbpageoptions' => "This page",
#'qbsettings' => "Quickbar settings",
#'qbsettingsnote' => "This preference only works in the 'Standard' and the 'CologneBlue' skin.",
'qbspecialpages' => "Specialieji puslapiai",
#'querybtn' => "Submit query",
#'querysuccessful' => "Query successful",
'randompage' => "Atsitiktinis straipsnis",
#'range_block_disabled' => "The sysop ability to create range blocks is disabled.",
'rchide' => "in $4 form; smulkių pataisymų - $1; $2 secondary namespaces; $3 multiple edits.",
'rclinks' => "Rodyti paskutinius $1 pakeitimų per paskutiniąsias $2 dienas(ų); $3",
'rclistfrom' => "Rodyti pakeitimus pradedant $1",
#'rcliu' => "; $1 edits from logged in users",
#'rcloaderr' => "Loading recent changes",
'rclsub' => "(straipsnių, pasiekiamų iš \"$1\")",
'rcnote' => "Pateikiamas <strong>$1</strong> paskutinių pakeitimų sąrašas per paskutiniąsias <strong>$2</strong> dienas(ų).",
'rcnotefrom' => "Žemiau yra pakeitimai pradedant <b>$2</b> (rodoma ne daugiau <b>$1</b> pakeitimų).",
#'readonly' => "Database locked",
/*#'readonlytext' => "The database is currently locked to new
entries and other modifications, probably for routine database maintenance,
after which it will be back to normal.
The administrator who locked it offered this explanation:
<p>$1",*/
/*#'readonlywarning' => "WARNING: The database has been locked for maintenance,
so you will not be able to save your edits right now. You may wish to cut-n-paste
the text into a text file and save it for later.",*/
'recentchanges' => "Naujausi keitimai",
#'recentchangescount' => "Number of titles in recent changes",
'recentchangeslinked' => "Susiję keitimai",
'recentchangestext' => "Naujausių Wikipedijos straipsnių pataisymų bei keitimų sąrašas.",
'redirectedfrom' => "(Nukreipta iš puslapio $1)",
'remembermypassword' => "Atsiminti slaptažodį.",
'removechecked' => "Išmesti pažymėtus straipsnius iš stebimų sąrašo",
'removedwatch' => "Išmesta iš stebimų",
'removedwatchtext' => "Straipsnis \"$1\" išmestas iš jūsų stebimų straipsnių sąrašo.",
'removingchecked' => "Pasirinkti straipsniai išmetami iš stebimų sąrašo...",
#'resetprefs' => "Reset preferences",
#'restorelink' => "$1 deleted edits",
#'resultsperpage' => "Hits to show per page",
'retrievedfrom' => "Rodomas puslapis \"$1\"",
'returnto' => "Grįžti į $1.",
'retypenew' => "Pakartokite naują slaptažodį",
#'reupload' => "Re-upload",
#'reuploaddesc' => "Return to the upload form.",
#'reverted' => "Reverted to earlier revision",
#'revertimg' => "rev",
'revertpage' => "Atmestas $2 pakeitimas, grąžinta paskutinė versija (vartotojo $1 keitimas)",
#'revhistory' => "Revision history",
'revisionasof' => "$1 versija",
#'revnotfound' => "Revision not found",
/*#'revnotfoundtext' => "The old revision of the page you asked for could not be found.
Please check the URL you used to access this page.
",*/
#'rfcurl' => "http://www.faqs.org/rfcs/rfc$1.html",
#'rights' => "Rights:",
#'rollback' => "Roll back edits",
'rollback_short' => "Atmesti",
#'rollbackfailed' => "Rollback failed",
'rollbacklink' => "atmesti",
#'rows' => "Rows",
'savearticle' => "Išsaugoti",
#'savedprefs' => "Your preferences have been saved.",
#'savefile' => "Save file",
#'saveprefs' => "Save preferences",
'search' => "Paieška",
#'searchdisabled' => "<p>Sorry! Full text search has been disabled temporarily, for performance reasons. In the meantime, you can use the Google search below, which may be out of date.</p>",
#'searchquery' => "For query \"$1\"",
#'searchresults' => "Search results",
#'searchresultshead' => "Search result settings",
'searchresulttext' => "For more information about searching {{SITENAME}}, see [[Project:Searching|Searching {{SITENAME}}]].",
#'selectnewerversionfordiff' => "Select a newer version for comparison",
#'selectolderversionfordiff' => "Select an older version for comparison",
#'selectonly' => "Only read-only queries are allowed.",
#'selflinks' => "Pages with Self Links",
#'selflinkstext' => "The following pages contain a link to themselves, which they should not.",
#'seriousxhtmlerrors' => "There were serious xhtml markup errors detected by tidy.",
#'servertime' => "Server time is now",
#'set_rights_fail' => "<b>User rights for \"$1\" could not be set. (Did you enter the name correctly?)</b>",
#'set_user_rights' => "Set user rights",
#'setbureaucratflag' => "Set bureaucrat flag",
'shortpages' => "Trumpiausi straipsniai",
'show' => "rodyti",
'showhideminor' => "$1 smulkius pataisymus",
'showingresults' => "Rodoma <b>$1</b> rezultatų pradedant #<b>$2</b>.",
#'showingresultsnum' => "Showing below <b>$3</b> results starting with #<b>$2</b>.",
#'showlast' => "Show last $1 images sorted $2.",
'showpreview' => "Kaip atrodys",
'showtoc' => "rodyti",
#'sig_tip' => "Your signature with timestamp",
'sitenotice' => "", #empty
'sitestats' => "Tinklalapio statistika",
'sitestatstext' => "Duomenų bazėje šiuo metu esančių puslapių - '''$1'''.
Į šį skaičių įeina diskusijų puslapiai, pagalbiniai Wikipedijos puslapiai, peradresavimo puslapiai ir kiti, neskaičiuojami kaip straipsniai.
Be šių puslapių, tikrų straipsnių yra apie '''$2'''.

Nuo wiki pradžios yra '''$3''' puslapių peržiūrų, ir '''$4''' puslapių redagavimų.
Taigi vidutiniškai kiekvienas puslapis keistas '''$5''' kartų, o žiūrėtas '''$6''' kartų.",
#'sitesubtitle' => "The Free Encyclopedia",
'sitesupport' => "Aukojimai",
'sitetitle' => "Wikipedia",
#'siteuser' => "Wikipedia user $1",
#'siteusers' => "Wikipedia user(s) $1",
#'skin' => "Skin",
'spamprotectiontext' => "The page you wanted to save was blocked by the spam filter. This is probably caused by a link to an external site. 

You might want to check the following regular expression for patterns that are currently blocked:",
#'spamprotectiontitle' => "Spam protection filter",
'specialpage' => "Specialusis Puslapis",
'specialpages' => "Specialieji puslapiai",
'spheading' => "Specialieji visiems vartotojams prieinami puslapiai",
#'sqlislogged' => "Please note that all queries are logged.",
#'sqlquery' => "Enter query",
'statistics' => "Statistika",
#'storedversion' => "Stored version",
#'stubthreshold' => "Threshold for stub display",
'subcategories' => "Subkategorijos",
'subcategorycount' => "Kategorijoje esančių kategorijų - $1",
#'subject' => "Subject/headline",
#'subjectpage' => "View subject",
#'successfulupload' => "Successful upload",
'summary' => "Komentaras",
'sysopspheading' => "Administratoriams skirti puslapiai",
/*#'sysoptext' => "The action you have requested can only be
performed by users with \"sysop\" status.
See $1.",*/
#'sysoptitle' => "Sysop access required",
#'tableform' => "table",
'talk' => "Diskusijos",
/*#'talkexists' => "The page itself was moved successfully, but the
talk page could not be moved because one already exists at the new
title. Please merge them manually.",*/
'talkpage' => "Aptarti straipsnį",
#'talkpagemoved' => "The corresponding talk page was also moved.",
#'talkpagenotmoved' => "The corresponding talk page was <strong>not</strong> moved.",
#'talkpagetext' => "<!-- MediaWiki:talkpagetext -->",
'textboxsize' => "Textbox dimensions",
#'textmatches' => "Page text matches",
#'thisisdeleted' => "View or restore $1?",
#'thumbnail-more' => "Enlarge",
#'timezonelegend' => "Time zone",
#'timezoneoffset' => "Offset",
/*#'timezonetext' => "Enter number of hours your local time differs
from server time (UTC).",*/
#'titlematches' => "Article title matches",
'toc' => "Turinys",
'toolbox' => "Įrankiai",
'tooltip-compareselectedversions' => "Žiūrėti dviejų pasirinktų puslapio versijų skirtumus. [alt-v]",
'tooltip-minoredit' => "Pažymėti keitimą kaip smulkų [alt-i]",
'tooltip-preview' => "Pakeitimų peržiūra, labai prašome pažiūrėti prieš išsaugant! [alt-p]",
'tooltip-save' => "Išsaugoti pakeitimus [alt-s]",
'tooltip-search' => "Ieškoti lietuviškame wiki [alt-f]",
#'uclinks' => "View the last $1 changes; view the last $2 days.",
#'ucnote' => "Below are this user's last <b>$1</b> changes in the last <b>$2</b> days.",
#'uctop' => " (top)",
#'unblockip' => "Unblock user",
/*#'unblockiptext' => "Use the form below to restore write access
to a previously blocked IP address or username.",*/
#'unblocklink' => "unblock",
#'unblocklogentry' => "unblocked \"$1\"",
#'uncategorizedpages' => "Uncategorized pages",
#'undelete' => "Restore deleted page",
#'undelete_short' => "Undelete $1 edits",
#'undeletearticle' => "Restore deleted page",
#'undeletebtn' => "Restore!",
#'undeletedarticle' => "restored \"$1\"",
'undeletedtext' => "[[$1]] has been successfully restored.
See [[Wikipedia:Deletion_log]] for a record of recent deletions and restorations.",
/*#'undeletehistory' => "If you restore the page, all revisions will be restored to the history.
If a new page with the same name has been created since the deletion, the restored
revisions will appear in the prior history, and the current revision of the live page
will not be automatically replaced.",*/
#'undeletepage' => "View and restore deleted pages",
/*#'undeletepagetext' => "The following pages have been deleted but are still in the archive and
can be restored. The archive may be periodically cleaned out.",*/
#'undeleterevision' => "Deleted revision as of $1",
#'undeleterevisions' => "$1 revisions archived",
#'unexpected' => "Unexpected value: \"$1\"=\"$2\".",
#'unlockbtn' => "Unlock database",
#'unlockconfirm' => "Yes, I really want to unlock the database.",
#'unlockdb' => "Unlock database",
#'unlockdbsuccesssub' => "Database lock removed",
#'unlockdbsuccesstext' => "The database has been unlocked.",
/*#'unlockdbtext' => "Unlocking the database will restore the ability of all
users to edit pages, change their preferences, edit their watchlists, and
other things requiring changes in the database.
Please confirm that this is what you intend to do.",*/
#'unprotect' => "Unprotect",
#'unprotectcomment' => "Reason for unprotecting",
#'unprotectedarticle' => "unprotected $1",
#'unprotectsub' => "(Unprotecting \"$1\")",
#'unprotectthispage' => "Unprotect this page",
'unusedimages' => "Nenaudojami paveikslėliai",
/*#'unusedimagestext' => "<p>Please note that other web sites may link to an image with
a direct URL, and so may still be listed here despite being
in active use.",*/
'unwatch' => "Nebe stebėti",
'unwatchthispage' => "Nustoti stebėti",
#'updated' => "(Updated)",
'upload' => "Įdėti failą",
#'uploadbtn' => "Upload file",
#'uploaddisabled' => "Sorry, uploading is disabled.",
#'uploadedfiles' => "Uploaded files",
'uploadedimage' => "įdėta \"$1\"",
#'uploaderror' => "Upload error",
#'uploadfile' => "Upload images, sounds, documents etc.",
#'uploadlink' => "Upload images",
#'uploadlog' => "upload log",
#'uploadlogpage' => "Upload_log",
/*#'uploadlogpagetext' => "Below is a list of the most recent file uploads.
All times shown are server time (UTC).
<ul>
</ul>
",*/
#'uploadnologin' => "Not logged in",
'uploadnologintext' => "You must be <a href=\"/wiki/Special:Userlogin\">logged in</a>
to upload files.",
#'uploadwarning' => "Upload warning",
/*#'usenewcategorypage' => "1

Set first character to \"0\" to disable the new category page layout.",*/
#'user_rights_set' => "<b>User rights for \"$1\" updated</b>",
#'usercssjsyoucanpreview' => "<strong>Tip:</strong> Use the 'Show preview' button to test your new css/js before saving.",
#'usercsspreview' => "'''Remember that you are only previewing your user css, it has not yet been saved!'''",
#'userexists' => "The user name you entered is already in use. Please choose a different name.",
#'userjspreview' => "'''Remember that you are only testing/previewing your user javascript, it has not yet been saved!'''",
'userlogin' => "Prisijungti",
'userlogout' => "Atsijungti",
#'usermailererror' => "Mail object returned error: ",
'userpage' => "Vartotojo puslapis",
'userstats' => "Vartotojų statistika",
'userstatstext' => "Šiuo metu registruotų vartotojų - '''$1'''.
Iš jų administratoriaus teises turi - '''$2''' (žr. $3).",
#'version' => "Version",
#'viewcount' => "This page has been accessed $1 times.",
'viewprevnext' => "Žiūrėti ($1) ($2) ($3).",
'viewsource' => "Žiūrėti kodą",
#'viewtalkpage' => "View discussion",
'wantedpages' => "Trokštamiausi straipsniai",
'watch' => "Stebėti",
'watchdetails' => "(stebimų straipsnių - $1, neskaitant diskusijų puslapių;
šiuo metu pasirinkote žiūrėti $2 pakeitimus;
$3...
<a href='$4'>parodyti ir redaguoti pilną sąrašą</a>.)",
'watcheditlist' => "Žemiau pateiktame stebimų straipsnių sąraše
pažymėkite varneles prie straipsnių,
kurių nebenorite stebėti ir spauskite apačioje
esantį mygtuką 'Išmesti iš stebimų'.",
'watchlist' => "Stebimi straipsniai",
'watchlistcontains' => "Straipsnių jūsų stebimųjų straipsnių sąraše - $1.",
'watchlistsub' => "(vartotojo \"$1\")",
'watchmethod-list' => "ieškoma naujausių keitimų stebimuose puslapiuose",
#'watchmethod-recent' => "checking recent edits for watched pages",
'watchnochange' => "Pasirinktu laikotarpiu nebuvo redaguotas nei vienas stebimas straipsnis.",
#'watchnologin' => "Not logged in",
'watchnologintext' => "You must be <a href=\"/wiki/Special:Userlogin\">logged in</a>
to modify your watchlist.",
'watchthis' => "Stebėti straipsnį",
'watchthispage' => "Stebėti puslapį",
'welcomecreation' => "<h2>Welcome, $1!</h2><p>Your account has been created.
Don't forget to change your Wikipedia preferences.",
'whatlinkshere' => "Susiję straipsniai",
#'whitelistacctext' => "To be allowed to create accounts in this Wiki you have to [[Special:Userlogin|log]] in and have the appropriate permissions.",
#'whitelistacctitle' => "You are not allowed to create an account",
#'whitelistedittext' => "You have to [[Special:Userlogin|login]] to edit pages.",
#'whitelistedittitle' => "Login required to edit",
#'whitelistreadtext' => "You have to [[Special:Userlogin|login]] to read pages.",
#'whitelistreadtitle' => "Login required to read",
#'wikipediapage' => "View project page",
'wikititlesuffix' => "Wikipedia",
'wlnote' => "Rodomi paskutiniai $1 pakeitimai, padaryti per paskutines <b>$2</b> valandas.",
#'wlsaved' => "This is a saved version of your watchlist.",
'wlshowlast' => "Rodyti paskutinių $1 valandų, $2 dienų ar $3 pakeitimus",
/*#'wrong_wfQuery_params' => "Incorrect parameters to wfQuery()<br />
Function: $1<br />
Query: $2
",*/
'wrongpassword' => "Įvestas neteisingas slaptažodis. Pamėginkite dar kartą.",
#'yourdiff' => "Differences",
'youremail' => "Elektroninio pašto adresas*",
'yourname' => "Jūsų vartotojo vardas",
'yournick' => "Jūsų slapyvardis (parašams)",
'yourpassword' => "Pasirinktas slaptažodis",
'yourpasswordagain' => "Pakartokite slaptažodį",
#'yourrealname' => "Your real name*",
#'yourtext' => "Your text",
);

#--------------------------------------------------------------------------
# Internationalisation code
#--------------------------------------------------------------------------

class LanguageLt extends LanguageUtf8  {
        # Inherent default user options unless customization is desired

        function getQuickbarSettings() {
                global $wgQuickbarSettingsLt;
                return $wgQuickbarSettingsLt;
        }

        function getSkinNames() {
                global $wgSkinNamesLt;
                return $wgSkinNamesLt;
        }

        function getMathNames() {
                global $wgMathNamesLt;
                return $wgMathNamesLt;
        }
        
        function getDateFormats() {
                global $wgDateFormatsLt;
                return $wgDateFormatsLt;
        }

        function getMonthName( $key )
        {
                global $wgMonthNamesLt;
                return $wgMonthNamesLt[$key-1];
        }

        function getMonthAbbreviation( $key )
        {
                global $wgMonthAbbreviationsLt;
                return $wgMonthAbbreviationsLt[$key-1];
        }

        function getWeekdayName( $key )
        {
                global $wgWeekdayNamesLt;
                return $wgWeekdayNamesLt[$key-1];
        }

    function fallback8bitEncoding() {
            return "windows-1257";
    }

        function getMessage( $key )
        {
                global $wgAllMessagesLt;

                if(array_key_exists($key, $wgAllMessagesLt))
                        return $wgAllMessagesLt[$key];
                else
                        return Language::getMessage($key);
        }

        function getAllMessages()
        {
                global $wgAllMessagesLt;
                return $wgAllMessagesLt;
        }

}
?>

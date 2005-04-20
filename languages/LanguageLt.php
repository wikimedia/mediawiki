<?php
/**
  * @package MediaWiki
  * @subpackage Language
  */

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
#        "Nesvarbu",
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
'aboutsite'      => "Apie Wikipediją",
'accmailtext' => "Vartotojo '$1' slaptažodis nusiųstas į $2.",
'accmailtitle' => "Slaptažodis išsiųstas.",
'actioncomplete' => "Veiksmas atliktas",
'addedwatch' => "Pridėta prie Stebimų",
'addedwatchtext' => "Straipsnis \"$1\" pridėtas į [[Special:Watchlist|stebimųjų sąrašą]].
Būsimi straipsnio bei atitinkamo diskusijų puslapio pakeitimai bus rodomi stebimųjų puslapių sąraše,
taip pat bus '''paryškinti''' [[Special:Recentchanges|naujausių keitimų sąraše]] kad išsiskirtų iš kitų straipsnių.

<p>Jei bet kuriuo metu užsinorėtumėte nustoti stebėti straipsnį, spustelkite \"nebestebėti\" viršutiniame meniu.",
'administrators' => "Wikipedia:Administrators",
'affirmation' => "Aš patvirtinu, kad šio failo autorius ar teisių turėtojas
sutinka jog failas bus viešinamas $1 licenzijos sąlygomis.",
'allmessages' => "Visi sistemos tekstai bei pranešimai",
'allmessagestext' => "Čia pateikiami visi sisteminiai tekstai bei pranešimai, esantys MediaWiki: vardų ervėje.",
'allpages' => "Visi straipsniai",
'alphaindexline' => "Nuo $1 iki $2",
'alreadyloggedin' => "<font color=red><b>Jūs jau esate prisijungęs kaip vartotojas User $1!</b></font><br />",
'alreadyrolled' => "Nepavyko atmesti paskutinio [[User:$2|$2]] ([[User talk:$2|Diskusijos]]) daryto straipsnio [[$1]] keitimo; kažkas jau pakeitė straipsnį arba suspėjo pirmas atmesti keitimą. 

Paskutimas keitimas darytas vartotojo [[User:$3|$3]] ([[User talk:$3|Diskusijos]]).",
'ancientpages' => "Seniausi straipsniai",
'anontalk' => "Šio IP diskusijų puslapis",
'articleexists' => "Straipsnis tokiu pavadinimu jau egzistuoja
arba pasirinktas vardas yra neteisingas.
Pasirinkite kitą pavadinimą.",
'articlepage' => "Rodyti straipsnį",
'autoblocker' => "Automatinis užblokavimas, nes dalinatės IP adresu su vartotoju \"$1\". Priežastis - \"$2\".",
'badfiletype' => "\".$1\" yra nerekomenduojamas paveikslėlio bylos formatas.",
'badretype' => "Įvesti slaptažodžiai nesutampa.",
'blockip' => "Blokuoti vartotoją",
'blockipsuccesssub' => "Užblokavimas pavyko",
'blocklink' => "blokuoti",
'bold_sample' => "Paryškintas tekstas",
'bold_tip' => "Paryškinti tekstą",
'booksources' => "Knygų paieška",
'brokenredirects' => "Peradresavimai į niekur",
'brokenredirectstext' => "Žemiau išvardinti peradresavimo puslapiai rodo į neegzistuojančius straipsnius.",
'bugreports' => "Pranešti apie klaidą",
'cancel' => "Atšaukti",
'cannotdelete' => "Nepavyko ištrinti nurodyto straipsnio ar paveikslėlio. (Gali būti, kad kažkas kitas ištrynė pirmas)",
'categories' => "Kategorijos",
'category' => "kategorija",
'category_header' => "Kategorijos \"$1\" straipsniai",
'categoryarticlecount' => "Kategorijoje straipsnių - $1",
'changepassword' => "Pakeisti slaptažodį",
'compareselectedversions' => "Palyginti pasirinktas versijas",
'confirm' => "Tvirtinu",
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
'contribslink' => "įnašas",
'contributions' => "Vartotojo indėlis",
'copyright' => "Turinys pateikiamas su $1 licenzija.",
'copyrightpage' => "Wikipedia:Copyrights",
'copyrightpagename' => "Wikipedia copyright",
'copyrightwarning' => "Atkreipkite dėmesį, kad viskam, kas patenka į Wikipediją, yra taikoma GNU Laisvos Documentacijos Licenzija
(detaliau - $1).
Jei nepageidaujate, kad jūsų įvestas turinys būtų negailestingai redaguojamas ir platinamas, nerašykite čia.<br />
Jūs taip pat pasižadate, kad tai jūsų pačių rašytas turinys arba kopijuotas iš viešų ar panašių nemokamų šaltinių.
<strong>NEKOPIJUOKITE AUTORINĖMIS TEISĖMIS APSAUGOTŲ DARBŲ BE LEIDIMO!</strong>",
'createaccount' => "Sukurti vartotoją",
'currentevents' => "-",
'currentrev' => "Dabartinė versija",
'dateformat' => "Datos formatas",
'deadendpages' => "Straipsniai-aklavietės",
'defaultns' => "Pagal nutylėjimą ieškoti šiose vardų erdvėse:",
'defemailsubject' => "Wikipedia e-mail",
'delete' => "trinti",
'deletecomment' => "Trynimo priežastis",
'deletedarticle' => "ištrinta \"$1\"",
'deletesub' => "(Trinama \"$1\")",
'deletethispage' => "Ištrinti straipsnį",
'difference' => "(Skirtumai tarp versijų)",
'disambiguations' => "Nukreipiamieji puslapiai",
'disambiguationspage' => "Wikipedia:Links_to_disambiguating_pages",
'disambiguationstext' => "Žemiau išvardinti straipsniai, rodantys į <i>nukreipiamuosius puslapius</i>. Nuorodos turėtų būti patikslintos kad rodytų į konkretų straipsnį.<br />Puslapis skaitomas nukreipiamuoju, jei nuoroda į jį yra $1.<br />",
'disclaimerpage' => "Wikipedia:General_disclaimer",
'disclaimers' => "Jokių Garantijų",
'doubleredirects' => "Dvigubi peradresavimai",
'editconflict' => "Išpręskite konfliktą: $1",
'edithelp' => "Kaip Redaguoti",
'editing' => "Taisomas straipsnis - $1",
'editingsection' => "Taisomas straipsnis - $1 (skyrius)",
'editingcomment' => "Taisomas straipsnis - $1 (comment)",
'editingold' => "<strong>ĮSPĖJIMAS: Jūs keičiate ne naujausią puslapio versiją.
Jei išsaugosite savo keitimus, prieš tai daryti pakeitimai pradings.</strong>",
'editsection' => "taisyti",
'editthispage' => "Taisyti straipsnį",
'emailforlost' => "* Elektroninio pašto adresas nėra privalomas. Tačiau jei įvesite, kiti vartotojai galės siųsti jums laiškus nesužinodami adreso. Taip pat pašto adresas gelbsti pamiršus slaptažodį.",
'emailuser' => "Rašyti laišką",
'excontent' => "buvęs turinys:",
'export' => "Eksportuoti puslapius",
'extlink_sample' => "http://www.pavyzdys.lt Nuorodos pavadinimas",
'extlink_tip' => "Išorinė nuoroda (nepamirškite http:// prefikso)",
'tagline'       => "<small>Straipsnis iš Wikipedijos, laisvosios enciklopedijos.</small>",
'go' => "Rodyk",
'headline_sample' => "Skyriaus Pavadinimas",
'headline_tip' => "Skyriaus pavadinimas (2-o lygio)",
'help' => "Pagalba",
'helppage' => "Help:Contents",
'hide' => "paslėpti",
'hidetoc' => "slėpti",
'hist' => "ist",
'history' => "Straipsnio istorija",
'history_short' => "Istorija",
'hr_tip' => "Horizontali linija (nepernaudoti)",
'image_sample' => "Pavyzdys.jpg",
'image_tip' => "Įdėti paveiksėlį",
'imagelist' => "Paveikslėlių sąrašas",
'ipblocklist' => "Blokuotų IP adresų bei vartotojų sąrašas",
'italic_sample' => "Tekstas kursyvu",
'italic_tip' => "Išskirti kursyvu",
'lastmodified' => "Paskutinį kartą keista $1.",
'lineno' => "Eilutė $1:",
'link_sample' => "Straipsnio pavadinimas",
'link_tip' => "Vidinė nuoroda",
'linklistsub' => "(Nuorodų sąrašas)",
'linktrail' => "/^([a-z]+)(.*)\$/sD",
'listusers' => "Vartotojų sąrašas",
'login' => "Prisijungti",
'loginprompt' => "Norėdami prisijungti prie Wikipedijos, privalote įsijungti '''cookies''' savo naršyklėje.",
'loginsuccess' => "Šiuo metu jūs prisijungęs prie Wikipedijos kaip \"$1\".",
'loginsuccesstitle' => "Sėkmingai prisijungėte",
'logout' => "Atsijungti",
'logouttext' => "Jūs atsijungėte nuo Wikipedijos.
Galite toliau naudoti Wikipediją anonimiškai arba prisijunkite iš naujo tuo pačiu ar kitu vartotoju.<br />
P.S.:  kai kuriuose puslapiuose ir toliau gali rodyti lyg būtumėte prisijungęs iki tol, kol išvalysite savo naršyklės išsaugotas puslapių kopijas",
'lonelypages' => "Vieniši straipsniai",
'longpages' => "Ilgiausi puslapiai",
'mailmypassword' => "Siųsti naują slaptažodį paštu",
'mainpage' => "Pradžia",
'maintenance' => "Įrankių puslapis",
'math_sample' => "Įveskite formulę",
'math_tip' => "Matematinė formulė (LaTeX formatu)",
'minoredit' => "Smulkus pataisymas",
'minoreditletter' => "S",
'move' => "Pervadinti",
'movethispage' => "Pervadinti straipsnį",
'mycontris' => "Mano įnašas",
'mytalk' => "mano diskusijos",
'navigation' => "Navigacija",
'nbytes' => "$1 B",
'newarticletext' => "Jūs patekote į neegzistuojančio straipsnio puslapį.
Norėdami sukurti straipsnį, pradėkite žemiau esančiame įvedimo lauke 
(daugiau informacijos [[Wikipedia:Help|pagalbos puslapyje]]).
Jei patekote čia per klaidą, paprasčiausiai spustelkite  naršyklės mygtuką 'atgal' ('''back''').",
'newpages' => "Naujausi straipsniai",
'newusersonly' => " (tik naujiems vartotojams)",
'nextn' => "sekančius $1",
'nlinks' => "$1 k.",
'note' => "<strong>Pastaba:</strong>",
'notloggedin' => "Neprisijungęs",
'nowatchlist' => "Neturite nei vieno stebimo straipsnio.",
'nstab-category' => "Kategorija",
'nstab-help' => "Pagalba",
'nstab-main' => "Straipsnis",
'nstab-template' => "Šablonas",
'otherlanguages' => "Kitomis kalbomis",
'perfcached' => "Rodoma išsaugota duomenų kopija, todėl duomenys gali būti ne patys naujausi:",
'preferences' => "Nustatymai",
'prefslogintext' => "Jūs esate prisijungęs kaip \"$1\".
Jūsų vidinis numeris yra $2.",
'preview' => "Peržiūra",
'previewnote' => "Nepamirškite, kad tai tik peržiūra, pakeitimai dar nėra išsaugoti!",
'prevn' => "ankstesnius $1",
'printableversion' => "Versija spausdinimui",
'qbspecialpages' => "Specialieji puslapiai",
'randompage' => "Atsitiktinis straipsnis",
'rchide' => "in $4 form; smulkių pataisymų - $1; $2 secondary namespaces; $3 multiple edits.",
'rclinks' => "Rodyti paskutinius $1 pakeitimų per paskutiniąsias $2 dienas(ų); $3",
'rclistfrom' => "Rodyti pakeitimus pradedant $1",
'rclsub' => "(straipsnių, pasiekiamų iš \"$1\")",
'rcnote' => "Pateikiamas <strong>$1</strong> paskutinių pakeitimų sąrašas per paskutiniąsias <strong>$2</strong> dienas(ų).",
'rcnotefrom' => "Žemiau yra pakeitimai pradedant <b>$2</b> (rodoma ne daugiau <b>$1</b> pakeitimų).",
'recentchanges' => "Naujausi keitimai",
'recentchangeslinked' => "Susiję keitimai",
'recentchangestext' => "Naujausių Wikipedijos straipsnių pataisymų bei keitimų sąrašas.",
'redirectedfrom' => "(Nukreipta iš puslapio $1)",
'remembermypassword' => "Atsiminti slaptažodį.",
'removechecked' => "Išmesti pažymėtus straipsnius iš stebimų sąrašo",
'removedwatch' => "Išmesta iš stebimų",
'removedwatchtext' => "Straipsnis \"$1\" išmestas iš jūsų stebimų straipsnių sąrašo.",
'removingchecked' => "Pasirinkti straipsniai išmetami iš stebimų sąrašo...",
'retrievedfrom' => "Rodomas puslapis \"$1\"",
'returnto' => "Grįžti į $1.",
'retypenew' => "Pakartokite naują slaptažodį",
'revertpage' => "Atmestas $2 pakeitimas, grąžinta paskutinė versija (vartotojo $1 keitimas)",
'revisionasof' => "$1 versija",
'rollback_short' => "Atmesti",
'rollbacklink' => "atmesti",
'savearticle' => "Išsaugoti",
'search' => "Paieška",
'shortpages' => "Trumpiausi straipsniai",
'show' => "rodyti",
'showhideminor' => "$1 smulkius pataisymus",
'showingresults' => "Rodoma <b>$1</b> rezultatų pradedant #<b>$2</b>.",
'showpreview' => "Kaip atrodys",
'showtoc' => "rodyti",
'sitestats' => "Tinklalapio statistika",
'sitestatstext' => "Duomenų bazėje šiuo metu esančių puslapių - '''$1'''.
Į šį skaičių įeina diskusijų puslapiai, pagalbiniai Wikipedijos puslapiai, peradresavimo puslapiai ir kiti, neskaičiuojami kaip straipsniai.
Be šių puslapių, tikrų straipsnių yra apie '''$2'''.

Nuo wiki pradžios yra '''$3''' puslapių peržiūrų, ir '''$4''' puslapių redagavimų.
Taigi vidutiniškai kiekvienas puslapis keistas '''$5''' kartų, o žiūrėtas '''$6''' kartų.",
'sitesupport' => "Aukojimai",
'sitetitle' => "Wikipedia",
'specialpage' => "Specialusis Puslapis",
'specialpages' => "Specialieji puslapiai",
'spheading' => "Specialieji visiems vartotojams prieinami puslapiai",
'statistics' => "Statistika",
'subcategorycount' => "Kategorijoje esančių kategorijų - $1",
'summary' => "Komentaras",
'sysopspheading' => "Administratoriams skirti puslapiai",
'talk' => "Diskusijos",
'talkpage' => "Aptarti straipsnį",
'toc' => "Turinys",
'toolbox' => "Įrankiai",
'tooltip-compareselectedversions' => "Žiūrėti dviejų pasirinktų puslapio versijų skirtumus. [alt-v]",
'tooltip-minoredit' => "Pažymėti keitimą kaip smulkų [alt-i]",
'tooltip-preview' => "Pakeitimų peržiūra, labai prašome pažiūrėti prieš išsaugant! [alt-p]",
'tooltip-save' => "Išsaugoti pakeitimus [alt-s]",
'tooltip-search' => "Ieškoti lietuviškame wiki [alt-f]",
'unusedimages' => "Nenaudojami paveikslėliai",
'unwatch' => "Nebe stebėti",
'unwatchthispage' => "Nustoti stebėti",
'upload' => "Įdėti failą",
'uploadedimage' => "įdėta \"[[$1]]\"",
'userlogin' => "Prisijungti",
'userlogout' => "Atsijungti",
'userpage' => "Vartotojo puslapis",
'userstats' => "Vartotojų statistika",
'userstatstext' => "Šiuo metu registruotų vartotojų - '''$1'''.
Iš jų administratoriaus teises turi - '''$2''' (žr. $3).",
'viewprevnext' => "Žiūrėti ($1) ($2) ($3).",
'viewsource' => "Žiūrėti kodą",
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
'watchnochange' => "Pasirinktu laikotarpiu nebuvo redaguotas nei vienas stebimas straipsnis.",
'watchthis' => "Stebėti straipsnį",
'watchthispage' => "Stebėti puslapį",
'whatlinkshere' => "Susiję straipsniai",
'wlnote' => "Rodomi paskutiniai $1 pakeitimai, padaryti per paskutines <b>$2</b> valandas.",
'wlshowlast' => "Rodyti paskutinių $1 valandų, $2 dienų ar $3 pakeitimus",
'wrongpassword' => "Įvestas neteisingas slaptažodis. Pamėginkite dar kartą.",
'youremail' => "Elektroninio pašto adresas*",
'yourname' => "Jūsų vartotojo vardas",
'yournick' => "Jūsų slapyvardis (parašams)",
'yourpassword' => "Pasirinktas slaptažodis",
'yourpasswordagain' => "Pakartokite slaptažodį",
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
                        return parent::getMessage($key);
        }

        function getAllMessages()
        {
                global $wgAllMessagesLt;
                return $wgAllMessagesLt;
        }
	
	function formatNum( $number ) {
		return strtr($number, '.,', ',.' );
	}

}
?>

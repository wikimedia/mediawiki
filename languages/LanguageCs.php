<?php

# See language.doc

# The names of the namespaces can be set here, but the numbers
# are magical, so don't change or move them!  The Namespace class
# encapsulates some of the magic-ness.
#
/* private */ $wgNamespaceNamesCs = array(
	-2	=> "Media",
	-1	=> "Speciální", # FIXME Is it safe to change this?
	0	=> "",
	1	=> "Diskuse", # neb diskutuj?
	2	=> "Wikipedista",
	3	=> "Wikipedista_diskuse",
	4	=> "Wikipedie",
	5	=> "Wikipedie_diskuse",
	6	=> "Soubor", #FIXME: Check the magic for Image: and Media:
	7	=> "Soubor_diskuse",
	8	=> "MediaWiki",
	9	=> "MediaWiki_diskuse",
	10  => "Template",
	11  => "Template_talk"

) + $wgNamespaceNamesEn;

# Zdědit apriorní preference: wgDefaultUserOptionsEn

/* private */ $wgQuickbarSettingsCs = array(
	"Žádný", "Leží vlevo", "Leží vpravo", "Visí vlevo"
);

/* private */ $wgSkinNamesCs = array(
	'standard' => "Standard",
	'nostalgia' => "Nostalgie",
	'cologneblue' => "Kolínská modř",
	'smarty' => "Paddington",
	'montparnasse' => "Montparnasse",
	'davinci' => "DaVinci",
	'mono' => "Mono",
	'monobook' => "MonoBook",
 "myskin" => "MySkin" 
);

/* private */ $wgUserTogglesCs = array(
	"hover"		=> "Ukázat odkazovou adresu (hoverbox) nad wikiovými odkazy",
	"underline" => "Podtrhnout odkazy",
	"highlightbroken" => "Začervenit odkazy na neexistující stránky",
	"justify"	=> "Zarámovat řádky",
	"hideminor" => "Ukrýt malé redakční úpravy <i>Poslední změny</i>",
	"numberheadings" => "Automaticky spočítat sekce",
	"showtoolbar" => "Show edit toolbar",
	"rememberpassword" => "Pamatovat si mé heslo od návštěvy k návětěvě",
	"editwidth" => "Redakční okno roztáhnout na celou šíři.",
	"editondblclick" => "Redigovat dvojím kliknutím (JavaScript)",
	"watchdefault" => "Náhled na nové a modifikované články",
	"minordefault" => "Mark all edits minor by default" #TRADUKU MIN
);

# Se eble, trovu Chehxajn libroservojn traserceblaj lau ISBN
# $wgBookstoreListCs = ..

# Note capitalization; also some uses may require addition of final -n
/* private */ $wgWeekdayNamesCs = array(
	"neděle", "pondělí", "úterý", "středa" , "čtvrtek",
	"pátek", "sobota"
);

# Double-check capitalization
/* private */ $wgMonthNamesCs = array(
	"leden", "únor", "březen", "duben", "květen", "červen",
	"červenec", "srpen", "září", "říjen", "listopad",
	"prosinec"
);

# There are no month abbreviations in Czech language.
/* private */ $wgMonthAbbreviationsCs = array(
	"1.", "2.", "3.", "4.", "5.", "6.",
	"7.", "8.", "9.", "10.", "11.", "12."
);

# All special pages have to be listed here: a description of ""
# will make them not show up on the "Special Pages" page, which
# is the right thing for some of them (such as the "targeted" ones).
#
# *Neměnit jména v levém sloupci, jsou to interní jména programových funkcí. Pravý sloupec  obsahuje několik mezer, které mají tak zůstat, aby se tyto funkce nezařadily do seznamu speciálních stránek.
/* private */ $wgValidSpecialPagesCs = array(
	"Userlogin"		=> "",
	"Userlogout"	=> "",
	"Preferences"	=> "Změnit moje uživatelské preference", 
	"Watchlist"		=> "Moje preference", # Seznam stránek, které si uživatel vybral jako oblíbené
	"Recentchanges" => "Poslední změny stránek",
	"Upload"		=> "Načti obrázky a soubory",
	"Imagelist"		=> "Načtené soubory",
	"Listusers"		=> "Zapsaní uživatelé",
	"Statistics"	=> "Statistika stránek",
	"Randompage"	=> "Náhodná stránka",

	"Lonelypages"	=> "Sirotčí stránky",
	"Unusedimages"	=> "Sirotčí obrázky",
	"Popularpages"	=> "Nejvíce navštěvované stránky",
	"Wantedpages"	=> "Nejvíce žádané stránky",
	"Shortpages"	=> "Krátké články",
	"Longpages"		=> "Dlouhé stránky",
	"Newpages"		=> "Nově vytvořené stránky",
	"Ancientpages"	=> "Oldest articles",
	"Allpages"		=> "Každá stránka podle titulu",

	"Ipblocklist"	=> "Blokované adresy IP",
    "Maintenance" => "Opravy a údržba", # angle "Maintenance page"
	"Specialpages"  => "",
	"Contributions" => "",
    "Emailuser"     => "",
	"Whatlinkshere" => "",
	"Recentchangeslinked" => "",
	"Movepage"		=> "",
	"Booksources"	=> "Vnější knihovny",
#	"Categories	=> "Page categories",
	"Export"	=> "XML page export",
	"Version"	=> "Version",
);

/* private */ $wgSysopSpecialPagesCs = array(
	"Blockip"		=> "Zablokuj adresu IP",
	"Asksql"		=> "Objednávka z databáze",
	"Undelete"		=> "Obnov odstraněnou stránku"
);

# FIXME
/* private */ $wgDeveloperSpecialPagesCs = array(
	"Lockdb"		=> "Odemknout data",
	"Unlockdb"		=> "Povolit změnu dat",
);

/* private */ $wgAllMessagesCs = array(

# Části textu používané různými stránkami:
#
# Písmena, která se mají objevit jako část odkazu ve formě "[[jazyk]]y" atd:
"linktrail"     => "/^([a-z]+)(.*)$/sD",
"mainpage"		=> "Hlavní strana",
"about"			=> "Úvod",
"aboutwikipedia" => "O Wikipedii", #FIXME
"aboutpage"		=> "Wikipedie:Úvod",
"help"			=> "Nápověda",
"helppage"		=> "Wikipedie:Nápověda",
"wikititlesuffix" => "Wikipedie",
"bugreports"	=> "Oznam mouchy",
"bugreportspage" => "Wikipedie:Oznam_mouchy",
"faq"			=> "Časté otázky",
"faqpage"		=> "Wikipedie:Časté otázky",
"edithelp"		=> "Pomoc při redigování",
"edithelppage"	=> "Wikipedie:Jak_redigovat_stránku", #FIXME: Kontroluj
"cancel"		=> "Rezignuj",
"qbfind"		=> "Hledej",
"qbbrowse"		=> "Listování", # FIXME
"qbedit"		=> "Redigování", #FIXME
"qbpageoptions" => "Parametra stránky", #FIXME
"qbpageinfo"	=> "Info stránky", #FIXME
"qbmyoptions"	=> "Osobní údaje", #FIXME
"mypage"		=> "Moje stránka", #FIXME
"mytalk"        => "Moje diskuse",
"currentevents" => "Aktuality", #FIXME - Novinky? Aktuální novinky? Aktuální události?
"errorpagetitle" => "Chyba", #FIXME - Arero? ;)
"returnto"		=> "Vrať se na $1.",
"fromwikipedia"	=> "Z Wikipedie, otevřené encyklopedie.",
"whatlinkshere"	=> "Sem odkazy", #FIXME: cu ligantaj?
"help"			=> "Nápověda",
"search"		=> "Hledej",
"history"		=> "Historie", #FIXME
"printableversion" => "Verze k tisku", # FIXME: cu printebla?
"editthispage"	=> "Rediguj stránku",
"deletethispage" => "Odstraň stránku",
"protectthispage" => "Chraň stránku", #FIXME: Cu 'gardu'
"unprotectthispage" => "Neochraňuj stránku", #FIXME: cu 'malgardu', 'ne plu', ktp?
"talkpage"		=> "Diskusní stránka",
"subjectpage"	=> "Stránka námětu", #FIXME: ?
"otherlanguages" => "Jiné jazyky",
"redirectedfrom" => "(Přesměrováno z $1)",
"lastmodified"	=> "Stránka byla naposledy redigována $1.",
"viewcount"		=> "Stránka se ukázala $1-krát.",
"printsubtitle" => "(Z http://cs.wikipedia.org)",
"protectedpage" => "Chráněná stránka", #FIXME: cu "gardita" ktp?
"administrators" => "Wikipedie:Správci", # FIXME?
"sysoptitle"	=> "Účet správce nutný",
"sysoptext"		=> "Žádaný úkon je proveditelný pouze  \"správci\".
Čtěte prosím $1.", #FIXME
"developertitle" => "Účet správce nutný",
"developertext"	=> "Žádaný úkonLa je proveditelný pouze programátory projekty, aby se zabránilo poškození dat.
Čtěte prosím $1.",
"nbytes"		=> "$1 bajty",
"go"			=> "Do toho!", #FIXME
"ok"			=> "OK", #FIXME
"sitetitle"		=> "Wikipedie", # Wikipedia
"sitesubtitle"	=> "Wikipedie: Otevřená Encyklopedie",
"retrievedfrom" => "Citováno z \"$1\"", #FIXME: Ukazuje se po tisku strany

# Main script and global functions
#
"nosuchaction"	=> "Žádný podobný úkon", 
"nosuchactiontext" => "Úkon ('action') specifikovaný pomocí nepodporuje programové vybavení Wikipedie",
"nosuchspecialpage" => "Žádná taková speciální stránka",
"nospecialpagetext" => "Žádal jsi speciální stránku podporovanou Wikipedií",

# General errors
#
"error"         => "Chyba",
"databaseerror" => "Databázová chyba",
"dberrortext"	=> "Syntaktická chyba se stala při dotazu na databázi. Možná ji způsobila nedovolená otázka. (viz $1), nebo to indikuje chybu v programovém vybavení. Jako poslední jste volil(a) otázku:
<blockquote><tt>$1</tt></blockquote>
z funkce \"<tt>$2</tt>\". 
MySQL vrátil chybu  \"<tt>$3: $4</tt>\".",
"noconnect"		=> "Nebylo možné se připojit k databázi $1",
"nodb"			=> "Nebylo možné vybrat databázi $1",
#"readonly"		=> "Data uzamčena pouze ke čtení" => "Vysvětlete prosím, jak se uzamykají data, a očekávaný čas odemykání.",
"readonlytext"	=> "Data Wikipedie jsou nyní uzavřena pro nové doplňky a změny, pravděpodobně kvůli pravidelné údržbě dat. Zkuste se připojit znovu po malé době. Uzamykatel zanechal následující zprávu:
<p>$1\n",
"missingarticle" => "Databázo nenašla text článku, který měla najít, nazvaný \"$1\".
Toto není chyba databáze, ale patrně chyba v programu. Oznamte to správci systému a upozorněte na URL.",
"internalerror" => "Vnitřní chyba",
"filecopyerror" => "Nebylo možné zkopírovat soubor  \"$1\" na \"$2\".",
"filerenameerror" => "Nebylo možné přejmenovat soubor \"$1\" na \"$2\".",
"filedeleteerror" => "Nebylo možné odstranit soubor \"$1\".",
"filenotfound"	=> "Nebylo možné najít soubor \"$1\".",
"unexpected"	=> "Neočekávaná hodnota: \"$1\"=\"$2\".",
"formerror"		=> "Chyba: nebylo možné dotat formulář",	
"badarticleerror" => "Tento úkon nelze použít u tohoto článku.",
"cannotdelete"  => "Nebylo možné odstranit zvolenou stránku ani soubor.",
"badtitle"		=> "Neplatný titul",
"badtitletext"	=> "Požadovaný titul stránky byl neplatný, prázdný nebo nesprávně adresovaný na jinojazyčný titul nebo jiný článek Wikipedie.",

# Login and logout pages
#
"logouttitle"	=> "Na shledanou!",
"logouttext"	=> "Skončil jste svou seanci.
Můžete pracovat ve Wikipedii anonymně, nebo se přihlásit znovu jako stejný nebo jiný uživatel..\n", #FIXME

"welcomecreation" => "<h2>Vítejte, $1!</h2> Váš účet je vytvořen.
<font color=\"red\">Nezapomeňte uvést své preference ve Wikipedii!</font>",

"loginpagetitle" => "Přihlaste se", #FIXME
"yourname"		=> "Název vašeho účtu", #FIXME buď heslo nebo jméno uživatele nebo název účtu atd.?
"yourpassword"	=> "Vaše heslo",
"yourpasswordagain" => "Napište heslo znovu",
"newusersonly"	=> " (pouze noví uživatelé)",
"remembermypassword" => "Pamatuj si mé heslo od seance k seanci.",
"loginproblem"	=> "<b>Nastal problém při vašem přihlášení.</b><br>Zkuste to znovu!",
"alreadyloggedin" => "<font color=\"red\"><b>Uživateli $1, již jste přihlášen!</b></font><br>\n",

"login"			=> "Přihlašte se", #FIXME, what exactly do the following go to?
"userlogin"		=> "Přihlašte se",
"logout"		=> "Na shledanou",
"userlogout"	=> "Na shledanou",
"createaccount"	=> "Vytvořte nový účet",
"badretype"		=> "Vámi napsaná hesla nesouhlasí.",
"userexists"	=> "Uživatel se stejným jménem je už registrován. Zvolte jiné jméno.",
"youremail"		=> "Vaše e-mailová adresa",
"yournick"		=> "Zdrobnělina vašho jména (pro podpisy)", #FIXME - cu kasbude to přezdívka, literární jméno atd.?
"emailforlost"	=> "Pokud zapomenete své heslo, můžeme vám zaslat nové na vaši mailovou adresu.",
"loginerror"	=> "Chyba při přihlašování", #FIXME
"noname"		=> "Je nutné úvést jméno svého účtu.",
"loginsuccesstitle" => "Přihlášení uspělo",
"loginsuccess"	=> "Nyní jste přihlášen ve Wikipedii jako uživatel \"$1\".",
"nosuchuser"	=> "Žádný uživatel nemá jméno \"$1\".
Zkontrolujte prosím správnost zápisu nebo vytvořte účet dle níže uvedeného formuláře.",
"wrongpassword"	=> "Heslo vámi uvedené nesouhlasí. Zkuste to znovu.",
"mailmypassword" => "Zašlete mi mailem nové heslo",
"passwordremindertitle" => "Připomenutí ztraceného hesla z Wikipedie", #FIXME
"passwordremindertext" => "Někdo (patrně vy, z IP-adresy $1) žádal, abychom vám poslali nové heslo pro přihlášení do Wikipedie. Heslo pro uživatele  \"$2\" je nyní \"$3\".
Doporučujeme, abyste se nyní přihlásil a raději změnil heslo.", #FIXME
"noemail"		=> "E-mailová adresa není zaregistrována pro uživatele \"$1\".",
#"passwordsent"	=> "Oni sendis novan pasvorton al la retpostadreso"Bylo zasláno nové helo pro uživatele \"$1\". Po obdržení nového hesla se znovu
#přihlaste.",

# Edit pages
#
"summary"		=> "Resumé",
"minoredit"		=> "Tato změna je malá redakční úpravu.",
"savearticle"	=> "Uchovej změny",
"preview"		=> "Náhled",
"showpreview"	=> "Ukaž náhled", #FIXME eh?
"blockedtitle"	=> "Uživatel odblokován", #FIXME cu 'Konto forbarita'?
"blockedtext"	=> "Váš účet nebo IP-adresa byly odblokovány osobou ,
která popsala příčinu takto:<br><p>Máte právo se spojit se správcem systému a prodiskutovat odblokování.", #FIXME - sistemestro?
"newarticle"	=> "(Nový)",
"newarticletext" => "Vložte sem text nového článku.", #FIXME?
"noarticletext" => "(Článek zatím neobsahuje text)", #FIXME
"updated"		=> "(Změna zaregistrována)", #FIXME: ?
"note"			=> "<strong>Noto:</strong> ", #FIXME: Where does this come from?
"previewnote"	=> "Pamatujte, že toto je pouze náhled, ne uložení!",
"previewconflict" => "Tento náhled ukazuje výše uvedený text, jak bude vypadat po uložení stránky.", #FIXME
"editing"		=> "Redakce stránky $1",
"editconflict"	=> "Redakční konflikt: ",
"explainconflict" => "Někdo změnil stránku poté, co jste ji napsal(a). Výše vidíte aktuální text článku. Vaše změny jsou uvedeny dole. Musíte sloučit své změny se stávajícím článkem.
<b>Poue</b> výše uvedený text zůstane uchováná po kliknutí na  \"Uložit\".\n<p>" , #FIXME - double-check that this makes sense
"yourtext"		=> "Váš text",
"storedversion" => "Registrovaná verze",
"editingold"	=> "<strong>VAROVÁNÍ: nyní redigujete starou verzi tohoto článku. Když ji uložíte, všechny změny provedené po zmíněné revizi se ztratí.</strong>\n",
"yourdiff"		=> "Rozdíly",
"copyrightwarning" => "Pamatujte, že každý příspěvek pro Wikipedii je považován jako zveřejněný dle <i>GNU Free Documentation License</i> (viz ). Pokud chcete, aby váš příspěvek nebyl redigován a rozšiřován, ne klikejte na \"Uložit\". Zároveň tím přísaháte, že příspěvek je vaším dílem nebo jste ho opsal(a) ze zdroje, na který se nevztahuje copyright.. <strong>NE POUŹÍVEJTE BEZ DOVOLENÍ DÍLA VÁZANÁ COPYRIGHTem!</strong>",


# History pages
#
"revhistory"	=> "Historie redakcí",
"nohistory"		=> "O této stránce neexistuje historie redakcí.", #FIXME
"revnotfound"	=> "Revize nenalezena", #fixme
"revnotfoundtext" => "Nelze najít starou revizi, kterou hledáte. Zkuste prosím zkontrolovat URL hledané stránky.\b",
"loadhist"		=> "Načítání stránky historie redakcí", #FIXME Apparently not used
"currentrev"	=> "Aktuální revize", #FIXME cu "plej lasta"?
"revisionasof"	=> "Jak zaregistrováno na ",
"cur"			=> "teď",
"next"			=> "násl",
"last"			=> "předch",
"orig"			=> "orig",
"histlegend"	=> "Vysvětlení: (teď) = viz rozdíly oproti nynější verzi,
(předch) = rozdíly oproti předchozí verzi, M = malá redakční změna",

# Diffs
#
"difference"	=> "(Rozdíly mezi revizemi)",
"loadingrev"	=> "načíst revize pro rozdíly", #FIXME Apparently not used
"lineno"		=> "Linie :",
"editcurrent"	=> "Zrediguj současnou verzi stránky",

# Search results
#
"searchresults" => "Výsledek hledání",
"searchhelppage" => "Wikipedie:Hledání",
"searchingwikipedia" => "Hledání ve Wikipedii",
"searchresulttext" => "Nápovědu, jak účinně hledat ve Wikipedii, čtěte na  .",
"searchquery"	=> "Zadání pro vyhledávání \"$1\"",
"badquery"		=> "Zkreslené zadání pro vyhledávání",
"badquerytext"	=> "Via sercmendo ne estis Vaše zadání pro vyhledávání není splnitelné. Může to být tím, že hledáte slovo kratší než tři písmena, nebo jste zadání napsal nesprávně".
#", ekzemple \"fisoj kaj kaj skaloj\"".   # FIXME ? eblas
". Bonvolu reserci per alia mendo.",
"maZkuste zadat nové zadání"	=> "Zadání \"$1\" poskytlo články podle názvu a články dle obsahu.",
"titlematches"	=> "Nalezeno dle názvů",
"notitlematches" => "Nic nebylo nalezeno dle názvu",
"textmatches"	=> "Nalezeno podle obsahu",
"notextmatches"	=> "Nic nebylo nalezeno podle obsahu",
"prevn"			=> "$1 předchozí",
"nextn"			=> "$1 následující",
"viewprevnext"	=> "Ukaž ($1) ($2) ($3).",
"showingresults" => "Ukazuje <b></b> nalezené od <b></b>-a.",
"nonefound"		=> "<strong>Poznámka</strong>: neúspěšné hledání zaviňuje často zadání slov, které nejsou v indexu, nebo mnoha slov najednou (jen stránky, které obsahují všechna zadaná slova, se objeví ve výsledku).",
"powersearch" => "Sercu",
#"powersearchtext" => "Hledej"
#Sercu en sekcioj: :<br>
#<br>
# Kun alidirektiloj &nbsp; Sercu  ",

# Preferences page
#
"preferences"	=> "Preference",
"prefsnologin" => "Dosud nepřihlášen!",
"prefsnologintext"	=> "Určite <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">se přihlaste</a>
dříve než je možno měnit priority.",
"prefslogintext" => "Přihlásil jste se \"$1\".
Vaše interní identifikační číslo je  .",
"prefsreset"	=> "Priority odstraněny z registru.", #FIXME: Hmm...
"qbsettings"	=> "Priority na liště nástrojů", 
"changepassword" => "Změňte heslo",
"skin"			=> "Etos",
"saveprefs"		=> "Uložit priority",
"resetprefs"	=> "Obnovit předchozí priority",
"oldpassword"	=> "Staré hesle",
"newpassword"	=> "Nové heslo",
"retypenew"		=> "Napište znovu nové heslo",
"textboxsize"	=> "Velikost redakčního okna",
"rows"			=> "Linie",
"columns"		=> "Sloupce",
"searchresultshead" => "Sladění výsledku hledání",
"resultsperpage" => "Ukázat nalezené po",
"contextlines"	=> "Ukázat linie ze stránek po",
"contextchars"	=> "Ukázat písmena z linií až po",
"recentchangescount" => "Ukázat množství názvů v Posledních Změnách",
"savedprefs"	=> "Vaše priority jsou zaregistrovány.",
"timezonetext"	=> "Označte, o kolik se vaše časové pásmo liší od serveru (UTC).
Například, pro Střední Evropu Časové pásmo, označte \"1\" v zimě nebo \"2\" v létě.",
"localtime"	=> "Místní časové pásmo",
"timezoneoffset" => "Rozdíl", #FIXME (?)
"emailflag"     => "Přijměte mailovou poštu od jiných wikipediistů",

# Recent changes
#
"recentchanges" => "Poslední změny",
"recentchangestext" => "Sledujte poslední změny ve Wikipedii na této stránce.
[[Vítejte, nováčci]]!
Přečtěte prosím tuto stránku: [[wikipedie:Časté otázky|Časté otázky]],
[[wikipedie:Drobné rady|Drobné rady]]
(zvláště [[wikipedie:Názvy titulů|Názvy titulů]]
a [[wikipedie:Neutrální hledisko|Neutrální hledisko]]),
a [[wikipedie:Časté chyby|Časté chyby ve Wikipedii]].

Pokud chcete, aby Wikipedie uspěla, je velice důležité, abyste nevkládali články vázané na  [[copyright]] někoho jiného. Zákonná odpovědnost by skutečně mohla ohrozit celý projekt, proto to prosím nedělejte.

Také se podívejte na 
[http://meta.wikipedia.org/wiki/Special:Recentchanges poslední diskusi o Wikipedii]
(plurlingve)",
"rcloaderr"		=> "Načti poslední změny",
"rcnote"		=> "Poslední <b></b> změny během posledních<b></b> dní.",
"rclinks"		=> "Ukázat poslední změny; ukázat změny během posledních dní.",
"diff"			=> "rozdíl",
"hist"			=> "historie",
"hide"			=> "skrýt",
"show"			=> "ukázat",
"tableform"		=> "tabulka",
"listform"		=> "seznam",
"nchanges"		=> "změny",

"minoreditletter" => "M",
"newpageletter" => "N",

# Upload
#
"upload"		=> "Načti",
"uploadbtn"		=> "Načti soubor",
"uploadlink"	=> "Načti obrázek", # Cu neuzata?
"reupload"		=> "Načti znovu",
"reuploaddesc"	=> "Vrať se k načtení.",
"uploadnologin" => "Nepřihlášený",
"uploadnologintext"	=> "Musíte mít účet a <a href=\"" .
  wfLocalUrl( "Speciala:Userlogin" ) . "\">přihlaste se</a>
pro načtení souboru.",
"uploadfile"	=> "Načti soubor",
"uploaderror"	=> "Při načítání došlo k chybě",
"uploadtext"	=> "Pro prohlížení a hledání již načtených souborů, jděte na  <a href=\"" . wfLocalUrl( "Special:Imagelist" ) .
"\">seznam načteného</a>.
Každé načtení a odstranění je registrováno u  <a href=\"" .
wfLocalUrl( "Wikipedie:Načtení_log" ) ."\">Načtení_log</a>.</p>


<p>Použij tento formulář pro načtení nového obrázku nebo jiných souborů jako ilustrací ke svým článkům. U běžných prohlížečů se dole objeví buton \"Procházet...\" apod; tím se otevřou adresáře tvého pevného disku, kde si vybereš svůj soubor, jehož název vyplní pole vedle butonu; musíš také potvrdit prohlášení, že neporušuješ ničí copyright. Vlastní načtení provedeš kliknutím na buton \"Načti\". Může to trvat i delší dobu, pokud je soubor velký a počítač pomalý.</p>

<p>Wikipedie upřednostňuje formát JPEG pro fotografie,
PNG pro grafiku, diagramy, apod.; a OGG pro zvukové nahrávky.
Pojmenuj svůj soubor informativním způsobem, aby se vyloučila nedorozumění.
Pro vložení obrázku do článku napiš odkaz ve formě
<b>[[obrázek:soubor.jpg]]</b> nebo <b>[[image:obraz.png|text pro prohlížeče bez grafiky]]</b>,
nebo <b>[[soubor:soubor.ogg]]</b> por sounds.</p>

<p>Všimni si, že články ve Wikipedii mohou redigovat i ostatní wikipediisté. Mohou přidávat, nahrazovat, i odstraňovat, pokud to prospěje encyklopedii. Pokud někdo bude zlomyslně poškozovat soubory jiných autorů,
může mu být zablokován přístup k redigování.</p>",
"uploadlog"		=> "kniha nahrávek ",
"uploadlogpage" => "kniha_nahrávek ",
"uploadlogpagetext" => "Přehled naposledy načtených souborů. Všechny časové údaje se ukazují podle časového pásma UTC.
<ul>
</ul>
",
"filename"		=> "Soubor",
"filedesc"		=> "Popis",
"affirmation"	=> "Potvrzuji, že zákonný vlastník copyrightu na tento soubor souhlasí se zveřejněním podle $1.",
"copyrightpage" => "Wikipedie:Copyright",
"copyrightpagename" => "povolenka GFDL používaná ve Wikipedii ",
"uploadedfiles"	=> "Načtené soubory ",
"noaffirmation" => "Bezpodmínečně musíte potvrdit, že váš příspěvek neporušuje zákony o copyrightu.",
"ignorewarning"	=> "Ignoruj varování a ulož soubor.",
"minlength"		=> "Jméno souboru se musí skládat nejméně ze tří písmen.",
"badfilename"	=> "Jméno souboru bylo změněno na \"$1\".",
"badfiletype"	=> "\".$1\" jedná se o nedoporučený typ souboru.",
"largefile"		=> "Doporučuje se, aby soubor nepřesahoval 100 kbytů.",
"successfulupload" => "Načtení úspěšně provedeno!",
"fileuploaded"	=> "Úspěšně jsi načetl soubor \"$1\".
Věnuj pozornost následujícímu odkazu: ($2) na stránku popisu a napiš pár informací o souboru. Např. odkud pochází, kdy a kdo ho vytvořil či cokoliv dalšího, co o něm víš..",
"uploadwarning" => "Varování",
"savefile"		=> "Ulož soubor $1",
"uploadedimage" => "načetl \"$1\"",

# Image list
#
"imagelist"		=> "Seznam načtených souborů ",
"imagelisttext"	=> "Seznam souborů, seřazených dle .",
"getimagelist"	=> "získává seznam souborů ",
"ilshowmatch"	=> "Ukaž soubory, jejich jména vyhovují",
"ilsubmit"		=> "Hledej",
"showlast"		=> "Ukaž poslední soubor dle .",
"all"			=> "všichni",
"byname"		=> "jméno",
"bydate"		=> "datum",
"bysize"		=> "velikost",
"imgdelete"		=> "odstranit",
"imgdesc"		=> "pri",
"imglegend"		=> "(pri) = ukaž/zrediguj popis souboru.",
"imghistory"	=> "Historie načtených souborů",
"revertimg"		=> "res",
"deleteimg"		=> "for",
"deleteimgcompletely"		=> "for",
"imghistlegend" => "(nun) = toto je současná verze souboru, (for) = odstranit tuto starou verzi, (res) = obnovit starou verzi.
<br><i>Click on date to see image uploaded on that date</i>.",
"imagelinks"	=> "Odkazy k souboru ",
"linkstoimage"	=> "K souboru odkazují tyto stránky:",
"nolinkstoimage" => "Žádná stránka neodkazuje na tento soubor.",

# Statistics
#
"statistics"	=> "Statistika",
"sitestats"		=> "O síti ",
"userstats"		=> "O uživatelích ",
"sitestatstext" => "V naší sbírce souborů se nachází celkem <b></b> stránek.
Toto číslo obsahuje \"diskusní stránky\", stránky o Wikipedii, droboučké
\"podčlánky\", přesměrování, a další, které nejsou články v pravém slova smyslu. Pomineme-li je, zbývá <b></b> skutečných článků.</p>

<p>Bylo navštíveno celkem <b></b> stránek, a zredigováno celkem <b></b> stránek od zavedení tohoto programu Wikipedie (Listopad 2002).
To je v průměru jedna stránka na <b></b> návštěv, a na <b></b> redakcí.",
"userstatstext" => "Zapsalo se <b></b> uživatelů. Z nich, <b></b> jsou spoluvedoucí 
(viz ).",

# Maintenance Page
#
"maintenance"		=> "Nástroje pro opravy a údržbu",
"maintnancepagetext"	=> "Zde jsou různé nástroje pro opravy a všeobecnou údržbu dat. Některé funkce mohou otřást databází, nenačítejte proto po každé drobné opravě!",
"maintenancebacklink"	=> "Návrat k nástrojům",
"disambiguations"	=> "Špatně odkázané oddělovače ",
"disambiguationspage"	=> "Wikipedie:Oddělovače",
"disambiguationstext"	=> "Tyto stránky odkazují na <i>stránkový oddělovač</i>. Měly by místo toho odkazovat na správný subjekt.<br>Bereme do úvahy stránky, které odkazují na oddělovač.<br>Odkazy na sekci nečlánkových souborů <i>ne</i> se zapisují zde.",
"doubleredirects"	=> "Dvojité přesměrování",
"doubleredirectstext"	=> "<b>Pozor:</b> Může se stát, že tento seznam bude obsahovat falešné pozitivy. Všeobecně to znamená, že existuje další text s odkazy po #REDIERCT.<br>
Každý řádek ukazuje odkaz k prvnímu a druhému přesměrování, plus první řádek textu druhého přesměrování, který všeobecně ukazuje \"skutečný\" hlavní článek, na který odkazuje první přesměrování.",
"brokenredirects"	=> "Přerušené přesměrování",
"brokenredirectstext"	=> "Tato přesměrování se vztahují na neexistující články.",
"selflinks"		=> "Stránky samoodkazující",
"selflinkstext"		=> "Tyto stránky obsahují neužitečný odkaz samy na sebe.",
"mispeelings"           => "Stránky se špatnou výslovností",
"mispeelingstext"               => "Tyto stránky obsahují jednu z nesprávných výslovností uvedených v . Správná výslovnost se ukáže (takto).",
"mispeelingspage"       => "Seznam častých chyb výslovnosti",
"missinglanguagelinks"  => "Chybějící mezijazykové odkazy",
"missinglanguagelinksbutton"    => "Ukaž chybějící mezijazykové odkazy pro",
"missinglanguagelinkstext"      => "Tyto články <i>ne</i> odkazují na svůj ekvivalent v jazyce . Přesměrování a podstránky se <i>ne</i> ukazují.",
# Miscellaneous special pages
#
"orphans"		=> "Sirotci",
"lonelypages"	=> "Sirotci",
"unusedimages"	=> "Nepoužívané obrázky a soubory",
"popularpages"	=> "Nejvíce navštěvované stránky",
"nviews"		=> " jednou",
"wantedpages"	=> "Žádoucí stránky ",
"nlinks"		=> " odkazy",
"allpages"		=> "Celý komplex stran",
"randompage"	=> "Náhodná stránka",
"shortpages"	=> "Drobné stránky",
"longpages"		=> "Dlouhé stránky",
"listusers"		=> "Uživatelé",
"specialpages"	=> "Speciální stránky",
"spheading"		=> "Speciální stránky",
"sysopspheading" => "Speciální stránky pro spoluadministrátory",
"developerspheading" => "Speciální stránky pro programátory",
"protectpage"	=> "Ochrana stránky",
"recentchangeslinked" => "Ukaž odkazy",
"rclsub"		=> "(ke stráncej odkazy z \"$1\")",
"debug"			=> "Proti mouchám",
"newpages"		=> "Nové stránky",
"movethispage"	=> "Přemístit stránku",
"unusedimagestext" => "<p>Ostatní WWW-stránky, např. jinojazyčné Wikipedie, mohou udělat přímé odkazy pomocí URL, ty se nezapočítávají do tohoto seznamu.",
"booksources"	=> "Knižní služby",
"booksourcetext" => "Odkazy na jiné WWW-stránky, které prodávají knihy a/nebo informují o knize, na níž je odkaz. Wikipedie není na tyto prodejny obchodně vázána, takže odkazy nelze chápat jako doporučení nebo reklamu.", 

# Email this user
#
"mailnologin"	=> "Žádná adresa k zaslání",
"mailnologintext" => "Určitě uveď <a href=\"" .
  wfLocalUrl( "Speciala:Userlogin" ) . "\">jméno</a>
a měj platnou e-mailovou adresu ve svých <a href=\"" .
  wfLocalUrl( "Speciala:Preferences" ) . "\">preferencích</a>
abys mohl mailovat jiným wikipediistům.",
"emailuser"		=> "Pošli mail",
"emailpage"		=> "Pošli mail",
"emailpagetext"	=> "Pokud wikipediista-adresát uvedl platnou e-mailovou adresu v preferencích, můžeš mu poslat zprávu tímto formulářem. 
Mailová adresa tebou uvedená v preferencích se objeví jako \"Od\"-adresa
pošty, aby adresát mohl odpovědět.",
"noemailtitle"	=> "Žádná mailová adresa ",
"noemailtext"	=> "Tento wikipediista buď nedal platnou adresu nebo zvolil režim nepřijímat zprávy od jiných wikipediistů.",
"emailfrom"		=> "Od",
"emailto"		=> "Komu",
"emailsubject"	=> "Předmět",
"emailmessage"	=> "Zpráva",
"emailsend"		=> "Odeslat",
"emailsent"		=> "Odeslaná pošta",
"emailsenttext" => "Tvá pošta byla odeslána.",



# Watchlist
#
"watchlist"		=> "Oblíbené stránky",
"watchlistsub"	=> "(uživatele \"$1\")",
"nowatchlist"	=> "Zatím jsi neuvedl žádné oblíbené stránky.",
"watchnologin"	=> "Neuvedeno jméno",
"watchnologintext"	=> "Nutno uvést <a href=\"" .
  wfLocalUrl( "Speciala:Userlogin" ) . "\">jméno</a>
pro aktivaci tvých oblíbených stránek.",
"addedwatch"	=> "Přidáno k oblíbeným",
"addedwatchtext" => "Stránka \"$1\" je přidána k tvým <a href=\"" .
  wfLocalUrl( "Speciala:Watchlist" ) . "\">oblíbeným</a>.
Budoucí změny této stránky se objeví <b>tučně</b> v  <a href=\"" .
  wfLocalUrl( "Speciala:Recentchanges" ) . "\">seznamu Poslední Změny </a>,
a bude počítány v seznamu tvých Oblíbených.

<p>Pokud později budeš chtít odstranit stránku ze seznamu Oblíbených, klikni na \"Ignoruj stránku \" v liště nástrojů.",
"removedwatch"	=> "Vytaženo z Oblíbených",
"removedwatchtext" => "Stránka \"$1\" vytažena z tvých Oblíbených.",
"watchthispage"	=> "Věnovat pozornost této stránce",
"unwatchthispage" => "Ignorovat tuto stránku",
"notanarticle"	=> "Toto není článek",

# Delete/protect/revert
#
"deletepage"	=> "Odstranit stránku",
"confirm"		=> "Potvrdit",
"confirmdelete" => "Potvrdit odstranění",
"deletesub"		=> "(Odstraňuje se \"$1\")",
"confirmdeletetext" => "Odstraníš článek nebo soubor a smažeš celou jeho historii z databáze.<br>
Potvrď prosím, že to opravdu chceš, že si uvědomuješ důsledky a že dodržuješ [[Wikipedie:Pravidla o odstraňování]].",
"confirmcheck"	=> "Ano, jsem naprosto jist, že chci toto odstranit.",
"actioncomplete" => "Provedeno",
"deletedtext"	=> "\"$1\" je odstraněno.
Pohleď na záznam posledních odstranění.",
"deletedarticle" => "odstraněno \"$1\"",
"dellogpage"	=> "Kniha_odstraněných_souborů",
"dellogpagetext" => "Zde je seznam posledních odstranění z databáze.
Všechny časové údaje uvedeny podle časového pásma serveru. (UTC)
<ul>
</ul>
",
"deletionlog"	=> "kniha odstranění",
"reverted"		=> "Obnovení předchozí verze",
"deletecomment"	=> "Důvod odstranění",
"imagereverted" => "Obnovení předchozí verze úspěšně provedeno.",

# Undelete
"undelete" => "Obnov odstraněnou stránku",
"undeletepage" => "Ukaž a obnov odstraněnou stránku",
"undeletepagetext" => "Tyto stránky jsou odstraněny, avšak dosud archivovány, je možno je obnovit. Archiv se vyprazdňuje pravidelně.",
"undeletearticle" => "Obnovit odstraněný článek",
"undeleterevisions" => " revize archivovány",
"undeletehistory" => "Pokud stránku obnovíš, všechny revize budou v historii obnoveny. Pokud byla vytvořena nová stránka se stejným jménem jako odstraněná, obnovené revize se zapíší na starší místo v historii a nová stránka nebude nahrazena.",
"undeleterevision" => "Odstraněná revize z ", # ( uveden čas)
"undeletebtn" => "Obnovit!",
"undeletedarticle" => "obnoveno \"$1\"",
"undeletedtext"   => "Článek [[]] je úspěšně obnoven.
Pohleď do [[Wikipedie:Kniha odstranění]] pro záznam posledních odstranění a obnovení.",

# Contributions
#
"contributions"	=> "Příspěvky wikipediisty",
"contribsub"	=> "Od ",
"nocontribs"	=> "Nenalezeny žádné redakce podle těchto kritérií.",
"ucnote"		=> "Zde jsou <b></b> poslední redakce tohoto wikipediisty během <b></b> posledních dní.",
"uclinks"		=> "Ukaž poslední redakce; ukaž poslední dny.",

# What links here
#
"whatlinkshere"	=> "Přihlašovaní sem",
"notargettitle" => "Bez cílové stránky",
"notargettext"	=> "Neupřesnil jsi, kterou stránku nebo kterého uživatele zamýšlíš.",
"linklistsub"	=> "(Seznam odkazů)",
"linkshere"		=> "Tyto stránky odkazují sem:",
"nolinkshere"	=> "Žádná stránka sem neodkazuje.",
"isredirect"	=> "přesměrovač",

# Block/unblock IP
#
"blockip"		=> "Zablokuj adresu IP",
"blockiptext"	=> "Tímto formulářem můžeš zablokovat adresu IP a zbavit ji práva přispívat do wikie. To lze učinit ''pouze'' v případě vandalizmu, a při dodržení [[Wikipedie:Pravidla pro zablokování|pravidel pro zablokování]].
Níže objasni přesný důvod (např. uveď stránku, která se stala terčem vandalského útoku).",
"ipaddress"		=> "Adresa IP",
"ipbreason"		=> "Důvod",
"ipbsubmit"		=> "Zablokuj adresu",
"badipaddress"	=> "Adresa IP je překroucena.",
"noblockreason" => "Nutno uvést důvod zablokování.",
"blockipsuccesssub" => "Úspěšné zablokování",
"blockipsuccesstext" => "Adresa IP \"$1\" je zablokována.
<br>Viz [[Special:Ipblocklist|seznam zablokování IP]].",
"unblockip"		=> "Zrušit blokování adresy IP",
"unblockiptext"	=> "Tímto formulářem možno obnovit právo blokované adresy IP opět přispívat do wikipedie.",
"ipusubmit"		=> "Zrušit blokování adresy",
"ipusuccess"	=> "Adresa IP \"$1\" byla uvolněna z blokování",
"ipblocklist"	=> "Seznam blokovaných adres IP",
"blocklistline"	=> "Dne ,  zablokováno ",
"blocklink"		=> "zablokuj",
"unblocklink"	=> "uvolni",
"contribslink"	=> "příspěvky",

# Developer tools
#
"lockdb"		=> "Uzamčít databázi",
"unlockdb"		=> "Odemčít databázi",
"lockdbtext"	=> "Pokud uzamčeš databázi, znemožníš odstatním provádět redakce, volit preference, oblíbené a jiné věci. Potvrď, že to opravdu chceš udělat a že odemčeš databázi hned po opravách.",
"unlockdbtext"	=> " Pokud odemčeš databázi, umožníš odstatním provádět redakce, volit preference, oblíbené a jiné věci. Potvrď, že to opravdu chceš udělat.",
"lockconfirm"	=> "Ano, opravdu chci uzamknout databázi.",
"unlockconfirm"	=> "Ano, opravdu chci odemknout databázi.",
"lockbtn"		=> "Zamknout databázi",
"unlockbtn"		=> "Odemknout databázi",
"locknoconfirm" => "Ne potvrdil jsi.",
"lockdbsuccesssub" => "Databáze uzamknuta",
"unlockdbsuccesssub" => "Databáze odemknuta",
"lockdbsuccesstext" => "Databáze wikipedie je uzamknuta.
<br>Nezapomeň ji odemknout po opravách.",
"unlockdbsuccesstext" => "Databáze wikipedie je odemknuta.",

# SQL query
#
"asksql"		=> "Žádost o informace SQL",
"asksqltext"	=> "Tímto formulářem můžeš přímo požádat databázi o informaci SQL.
Toto může velmi otřást serverem, proto používěj málo a opatrně.",
"sqlquery"		=> "Napiš žádost o informaci",
"querybtn"		=> "Žádej!",
"selectonly"	=> "Žádosti o informace kromě \"SELECT\" jsou omzeny pouze na programátory wikipedie.",
"querysuccessful" => "Žádost o inforaci byla úspěšná",

# Move page
#
"movepage"		=> "Přesuň stránku",
"movepagetext"	=> "Touto formulí můžeš změnit název stránky a přenést i seznam její historie na nový název. Původní název se stane přesměrovačem na nový název.
Odkazy na předchozí název <i>ne</i>budou změněny.
<b>VAROVÁNÍ!</b>
Může to být drastická a nečekaná změna pro oblíbené stránky. Ujisti se, že si uvědomuješ důsledky, než změnu provedeš.",
"movearticle"	=> "Přesuň stránku",
"movenologin"	=> "Neuvedeno přihlašovací jméno",
"movenologintext" => "Musíš být přihlášeným uživatelem a <a href=\"" .
  wfLocalUrl( "Speciala:Userlogin" ) . "\">uveď přihlašovací jméno</a>
abys mohl stránku přesunout.",
"newtitle"		=> "Na nový název",
"movepagebtn"	=> "Přesuň stránku",
"pagemovedsub"	=> "Úspěšně přesunuto",
"pagemovedtext" => "Stránka \"[[$1]]\" přesunuta na \"[[$2]]\".",
"articleexists" => "Takto nazvaná stránky již existuje nebo tebou zvolený název je neplatný. Zvol jiný název.",
"movedto"		=> "přesunuto na",
"movetalk"		=> "Přesuň také \"diskusní\" stránku, pokud existuje.",
"talkpagemoved" => "Diskusní stránka také přesunuta.",
"talkpagenotmoved" => "Diskusní stránka <strong>není</strong> přesunuta."

);

global $IP;
require_once("LanguageUtf8.php");

class LanguageCs extends LanguageUtf8 {

    function getNamespaces() {
		global $wgNamespaceNamesCs;
		return $wgNamespaceNamesCs;
	}

	function getNsText( $index ) {
		global $wgNamespaceNamesCs;
		return $wgNamespaceNamesCs[$index];
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesCs;

		foreach ( $wgNamespaceNamesCs as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		if( 0 == strcasecmp( "Special", $text ) ) return -1;
		if( 0 == strcasecmp( "Wikipedia", $text ) ) return 4;
		if( 0 == strcasecmp( "Wikipediista", $text ) ) return 2;
		if( 0 == strcasecmp( "Wikipediista_diskuse", $text ) ) return 3;
		return false;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsCs;
		return $wgQuickbarSettingsCs;
	}

	function getSkinNames() {
		global $wgSkinNamesCs;
		return $wgSkinNamesCs;
	}

	function getUserToggles() {
		global $wgUserTogglesCs;
		return $wgUserTogglesCs;
	}

	function getMonthName( $key )
	{
		global $wgMonthNamesCs;
		return $wgMonthNamesCs[$key-1];
	}

	function getMonthAbbreviation( $key )
	{
		global $wgMonthAbbreviationsCs;
		return $wgMonthAbbreviationsCs[$key-1];
	}

	function getWeekdayName( $key )
	{
		global $wgWeekdayNamesCs;
		return $wgWeekdayNamesCs[$key-1];
	}

	# Zdědit userAdjust()
 
	# Datové a časové funkce možno upřesnit podle jazyka
	function date( $ts, $adj = false )
	{
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$d = (0 + substr( $ts, 6, 2 )) . ". " .
		$this->getMonthAbbreviation( substr( $ts, 4, 2 ) ) .
		  " " . 
		  substr( $ts, 0, 4 );
		return $d;
	}

	function time( $ts, $adj = false )
	{
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$t = substr( $ts, 8, 2 ) . ":" . substr( $ts, 10, 2 );
		return $t;
	}

	function timeanddate( $ts, $adj = false )
	{
		return $this->time( $ts, $adj ) . ", " . $this->date( $ts, $adj );
	}

	# Heredu rfs1123()

	function getValidSpecialPages()
	{
		global $wgValidSpecialPagesCs;
		return $wgValidSpecialPagesCs;
	}

	function getSysopSpecialPages()
	{
		global $wgSysopSpecialPagesCs;
		return $wgSysopSpecialPagesCs;
	}

	function getDeveloperSpecialPages()
	{
		global $wgDeveloperSpecialPagesCs;
		return $wgDeveloperSpecialPagesCs;
	}

	function getMessage( $key )
	{
		global $wgAllMessagesCs;
		if(array_key_exists($key, $wgAllMessagesCs))
			return $wgAllMessagesCs[$key];
		else
			return Language::getMessage($key);
	}

	# Heredu iconv(), ucfirst(), ktp

	function checkTitleEncoding( $s ) {
		global $wgInputEncoding;

		# Check for non-UTF-8 URLs; assume they are WinLatin2
		$ishigh = preg_match( '/[\x80-\xff]/', $s);
		$isutf = ($ishigh ? preg_match( '/^([\x00-\x7f]|[\xc0-\xdf][\x80-\xbf]|' .
		'[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xf7][\x80-\xbf]{3})+$/', $s ) : true );

		if( $ishigh and !$isutf )
			return iconv( "cp1250", "utf-8", $s );

		return $s;
	}

}

?>

<?php

require_once("LanguageUtf8.php");

# NOTE: To turn off "Current Events" in the sidebar,
# set "currentevents" => "-"

# The names of the namespaces can be set here, but the numbers
# are magical, so don't change or move them!  The Namespace class
# encapsulates some of the magic-ness.
#
/* private */ $wgNamespaceNamesRo = array(
	NS_MESIA		=> 'Media',
	NS_SPECIAL		=> 'Special',
	NS_MAIN			=> '',
	NS_TALK			=> 'Discuţie',
	NS_USER			=> 'Utilizator',
	NS_USER_TALK		=> 'Discuţie_Utilizator',
	NS_WIKIPEDIA		=> 'Wikipedia',
	NS_WIKIPEDIA_TALK	=> 'Discuţie_Wikipedia',
	NS_IMAGE		=> 'Imagine',
	NS_IMAGE_TALK		=> 'Discuţie_Imagine',
	NS_MEDIAWIKI		=> 'MediaWiki',
	NS_MEDIAWIKI_TALK	=> 'Discuţie_MediaWiki',
	NS_TEMPLATE		=> 'Format',
	NS_TEMPLATE_TALK	=> 'Discuţie_Format',
	NS_HELP			=> 'Ajutor',
	NS_HELP_TALK		=> 'Discuţie_Ajutor',
	NS_CATEGORY		=> 'Categorie',
	NS_CATEGORY_TALK	=> 'Discuţie_Categorie'

) + $wgNamespaceNamesEn;

/* private */ $wgQuickbarSettingsRo = array(
	"Fără", "Fixă, în stânga", "Fixă, în dreapta", "Liberă"
);

/* private */ $wgSkinNamesRo = array(
	'standard' => "Normală",
	'nostalgia' => "Nostalgie",
	'cologneblue' => "Cologne Blue",
	'smarty' => "Paddington",
	'montparnasse' => "Montparnasse",
	'davinci' => "DaVinci",
	'mono' => "Mono",
	'monobook' => "MonoBook",
 "myskin" => "MySkin" 
);

/* private */ $wgMathNamesRo = array(
	"Întotdeauna PNG",
	"HTML dacă e foarte simplu sau PNG altfel",
	"HTML dacă e posibil sau PNG altfel",
	"Lasă-l TeX (pentru browsere text)",
	"Recomandat pentru browsere moderne"
);

/* private */ $wgDateFormatsRo = array(
	"Nici o preferinţă",
	"Ianuarie 15, 2001",
	"15 Ianuarie 2001",
	"2001 Ianuarie 15"
);

/* private */ $wgUserTogglesRo = array(
	"hover"		=> "Arată info deasupra legăturilor",
	"underline" => "Subliniază legăturile",
	"highlightbroken" => "Formatează legăturile inexistente <a href=\"\" class=\"new\">în felul acesta</a> (alternativa este aşa<a href=\"\" class=\"internal\">?</a>).",
	"justify"	=> "Aliniază paragrafele",
	"hideminor" => "Ascunde schimbările minore în pagina de schimbări recente",
	"usenewrc" => "Îmbunătăţeşte structura paginii de schimbări minore<br>(nu merge în toate browserele)",
	"numberheadings" => "Auto-numerotează titlurile",
	"showtoolbar" => "Show edit toolbar",
	"editondblclick" => "Editează paginile cu dublu clic (JavaScript)",
        "editsection" => "Permite editarea secţiunilor folosind legături [editează] pe pagină",
	"editsectiononrightclick"=>"Permite editarea secţiunilor la apăsarea<br>butonului din dreapta al mouse-ului pe titlu<br>(necesită JavaScript)",
        "showtoc" => "Arată cuprinsul paginilor<br>(pentru pagini cu cel puţin trei titluri)",
	"rememberpassword" => "Păstrează parola între sesiuni",
	"editwidth" => "Lăţime maximă pentru caseta de editare",
	"watchdefault" => "Urmăreşte articolele pe care le creezi sau le editezi",
	"minordefault" => "Marchează implicit toate editările ca minore",
	"previewontop" => "Arată pagina după caseta de editare, nu înainte",
        "nocache" => "Nu folosi cache (conexiunea merge mai greu,<br>dar sunt afişate toate modificările paginilor)"
	
);

/* private */ $wgBookstoreListRo = array(
	"AddALL" => "http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN",
	"PriceSCAN" => "http://www.pricescan.com/books/bookDetail.asp?isbn=$1",
	"Barnes & Noble" => "http://shop.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1",
	"Amazon.com" => "http://www.amazon.com/exec/obidos/ISBN=$1"
);

/* private */ $wgWeekdayNamesRo = array(
	"Duminică", "Luni", "Marţi", "Miercuri", "Joi",
	"Vineri", "Sâmbătă"
);
# Yes, the month names start with small letters in Romanian.
/* private */ $wgMonthNamesRo = array(
	"ianuarie", "februarie", "martie", "aprilie", "mai", "iunie",
	"iulie", "august", "septembrie", "octombrie", "noiembrie",
	"decembrie"
);

/* private */ $wgMonthAbbreviationsRo = array(
	"Ian", "Feb", "Mar", "Apr", "Mai", "Iun", "Iul", "Aug",
	"Sep", "Oct", "Noi", "Dec"
);

/* private */ $wgMagicWordsRo = array(
#   ID                                 CASE  SYNONYMS
    MAG_REDIRECT             => array( 0,    "#redirect"                                       ),
    MAG_NOTOC                => array( 0,    "__NOTOC__", "__FARACUPRINS__"                    ),
    MAG_NOEDITSECTION        => array( 0,    "__NOEDITSECTION__", "__FARAEDITSECTIUNE__"       ),
    MAG_START                => array( 0,    "__START__"                                       ),
    MAG_CURRENTMONTH         => array( 1,    "CURRENTMONTH", "{{NUMARLUNACURENTA}}"            ),
    MAG_CURRENTMONTHNAME     => array( 1,    "CURRENTMONTHNAME", "{{NUMELUNACURENTA}}"         ),
    MAG_CURRENTDAY           => array( 1,    "CURRENTDAY", "{{NUMARZIUACURENTA}}"              ),   
    MAG_CURRENTDAYNAME       => array( 1,    "CURRENTDAYNAME", "{{NUMEZIUACURENTA}}"           ),
    MAG_CURRENTYEAR          => array( 1,    "CURRENTYEAR", "{{ANULCURENT}}"                   ),
    MAG_CURRENTTIME          => array( 1,    "CURRENTTIME", "{{ORACURENTA}}"                   ),
    MAG_NUMBEROFARTICLES     => array( 1,    "NUMBEROFARTICLES", "{{NUMARDEARTICOLE}}"         ),
    MAG_CURRENTMONTHNAMEGEN  => array( 1,    "CURRENTMONTHNAMEGEN", "{{NUMELUNACURENTAGEN}}"   ),
	MAG_MSG                  => array( 0,    "MSG:", "MSJ:"                                    ),
	MAG_SUBST                => array( 0,    "SUBST:"                                          ),
    MAG_MSGNW                => array( 0,    "MSGNW:", "MSJNOU:"                               ),
	MAG_END                  => array( 0,    "__END__", "__FINAL__"                            ),
    MAG_IMG_THUMBNAIL        => array( 1,    "thumbnail", "thumb"                              ),
    MAG_IMG_RIGHT            => array( 1,    "right"                                           ),
    MAG_IMG_LEFT             => array( 1,    "left"                                            ),
    MAG_IMG_NONE             => array( 1,    "none"                                            ),
    MAG_IMG_WIDTH            => array( 1,    "$1px"                                            ),
    MAG_IMG_CENTER           => array( 1,    "center", "centre"                                ),
    MAG_INT                  => array( 0,    "INT:"                                            )


);

# All special pages have to be listed here: a description of ""
# will make them not show up on the "Special Pages" page, which
# is the right thing for some of them (such as the "targeted" ones).
#
/* private */ $wgValidSpecialPagesRo = array(
	"Userlogin"		=> "",
	"Userlogout"	=> "",
	"Preferences"	=> "Preferinţele mele",
	"Watchlist"		=> "Articole urmărite",
	"Recentchanges" => "Pagini actualizate recent",
	"Upload"		=> "Trimite imagini",
	"Imagelist"		=> "Lista imaginilor",
	"Listusers"		=> "Utilizatori înregistraţi",
	"Statistics"	=> "Statistici pentru site",
	"Randompage"	=> "Articol aleator",

	"Lonelypages"	=> "Articole orfane",
	"Unusedimages"	=> "Imagini orfane",
	"Popularpages"	=> "Articole populare",
	"Wantedpages"	=> "Cele mai dorite articole",
	"Shortpages"	=> "Articole scurte",
	"Longpages"		=> "Articole lungi",
	"Newpages"		=> "Articole noi",
	"Ancientpages"	=> "Cele mai vechi articole",
        "Deadendpages"  => "Pagini fără legături",
#	"Intl"	=> "Legături între limbi",
	"Allpages"		=> "Toate paginile după titlu",

	"Ipblocklist"	=> "Adrese IP blocate",
	"Maintenance" => "Pagina de întreţinere",
	"Specialpages"  => "Pagini speciale",
	"Contributions" => "Contribuţii",
	"Emailuser"		=> "Trimite e-mail utilizatorului",
	"Whatlinkshere" => "Ce pagini se leagă aici",
	"Recentchangeslinked" => "",
	"Movepage"		=> "Mută pagina",
	"Booksources"	=> "Surse externe de cărţi",
	"Categories"	=> "Categorii de pagini",
	"Export"	=> "XML export",
	"Version"	=> "Version",
);

/* private */ $wgSysopSpecialPagesRo = array(
	"Blockip"		=> "Blochează adresa IP",
	"Asksql"		=> "Efectuează un query în baza de date",
	"Undelete"		=> "Afişează şi restaurează pagini şterse"
);

/* private */ $wgDeveloperSpecialPagesRo = array(
	"Lockdb"		=> "Blochează baza de date la scriere",
	"Unlockdb"		=> "Deblochează baza de date",
);

/* private */ $wgAllMessagesRo = array(

# Bits of text used by many pages:
#
"categories"	=> "Categorii de pagini",
"category"	=> "categoria",
"category_header"	=> "Articole din categoria \"$1\"",
"subcategories"	=> "Subcategorii",
"linktrail"		=> "/^([a-z]+)(.*)\$/sD",
"mainpage"		=> "Pagina principală",
"mainpagetext"	=> "Programul Wiki a fost instalat cu succes",
"about"			=> "Despre",
"aboutwikipedia" => "Despre Wikipedia",
"aboutpage"		=> "Wikipedia:Despre",
"help"			=> "Ajutor",
"helppage"		=> "Wikipedia:Ajutor",
"wikititlesuffix" => "Wikipedia",
"bugreports"	=> "Raportare probleme",
"bugreportspage" => "Wikipedia:Rapoarte_probleme",
"sitesupport"   => "Donaţii",
"sitesupportpage" => "", # If not set, won't appear. Can be wiki page or URL
"faq"			=> "Întrebări frecvente",
"faqpage"		=> "Wikipedia:Întrebări_frecvente",
"edithelp"		=> "Ajutor pentru editare",
"edithelppage"	=> "Wikipedia:Cum_să_editezi_o_pagină",
"cancel"		=> "Renunţă",
"qbfind"		=> "Găseşte",
"qbbrowse"		=> "Răsfoieşte",
"qbedit"		=> "Editează",
"qbpageoptions" => "Opţiuni ale paginii",
"qbpageinfo"	=> "Informaţii ale paginii",
"qbmyoptions"	=> "Opţiunile mele",
"qbspecialpages"	=> "Pagini speciale",
"moredotdotdot"	=> "Altele...",
"mypage"		=> "Pagina mea",
"mytalk"		=> "Discuţiile mele",
"currentevents" => "Evenimente curente",
"errorpagetitle" => "Eroare",
"returnto"		=> "Înapoi la $1.",
"fromwikipedia"	=> "De la Wikipedia, enciclopedia liberă.",
"whatlinkshere"	=> "Pagini care se leagă aici",
"help"			=> "Ajutor",
"search"		=> "Caută",
"go"		=> "Du-te",
"history"		=> "Versiuni mai vechi",
"printableversion" => "Versiune tipărire",
"editthispage"	=> "Editează pagina",
"deletethispage" => "Şterge pagina",
"protectthispage" => "Protejează pagina",
"unprotectthispage" => "Deprotejează pagina",
"newpage" => "Pagină nouă",
"talkpage"		=> "Discută pagina",
"postcomment"	=> "Adaugă comentariu",
"articlepage"	=> "Vezi articolul",
"subjectpage"	=> "Vezi subiectul", # For compatibility
"userpage" => "Vezi pagina utilizatorului",
"wikipediapage" => "Vezi pagina meta",
"imagepage" => 	"Vezi pagina imaginii",
"viewtalkpage" => "Vezi discuţia",
"otherlanguages" => "În alte limbi",
"redirectedfrom" => "(Redirectat de la $1)",
"lastmodified"	=> "Ultima modificare $1.",
"viewcount"		=> "Această pagină a fost vizitată de $1 ori.",
"gnunote" => "Tot textul este disponibil în termenii licenţei <a class=internal href='/wiki/GNU_FDL'>GNU Free Documentation License</a>.",
"printsubtitle" => "(De la http://ro.wikipedia.org)",
"protectedpage" => "Pagină protejată",
"administrators" => "Wikipedia:Administratori",
"sysoptitle"	=> "Aveţi nevoie de acces ca operator",
"sysoptext"		=> "Acţiunea pe care aţi încercat-o necesită drepturi de operator.
Vezi $1.",
"developertitle" => "Aveţi nevoie de acces ca dezvoltator",
"developertext"	=> "Acţiunea pe care aţi încercat-o necesită drepturi de dezvoltator.
Vezi $1.",
"nbytes"		=> "$1 octeţi",
"go"			=> "Du-te",
"ok"			=> "OK",
"sitetitle"		=> "Wikipedia",
"sitesubtitle"	=> "Enciclopedia liberă",
"retrievedfrom" => "Adus de la \"$1\"",
"newmessages" => "Aveţi $1.",
"newmessageslink" => "mesaje noi",
"editsection"=>"editează",
"toc" => "Cuprins",
"showtoc" => "arată",
"hidetoc" => "ascunde",
"thisisdeleted" => "Vezi sau recuperează $1?",
"restorelink" => "$1 editări şterse",

# Main script and global functions
#
"nosuchaction"	=> "Această acţiune nu există",
"nosuchactiontext" => "Acţiunea specificată în adresă nu este recunoscută de Wikipedia.",
"nosuchspecialpage" => "Această pagină specială nu există",
"nospecialpagetext" => "Aţi cerut o pagină specială care nu este recunoscută de Wikipedia.",

# General errors
#
"error"			=> "Eroare",
"databaseerror" => "Eroare la baza de date",
"dberrortext"	=> "A apărut o eroare în execuţia query-ului.
Aceasta se poate datora unui query ilegal (vezi $5),
sau poate indica o problemă în program.
Ultimul query încercat a fost:
<blockquote><tt>$1</tt></blockquote>
în cadrul funcţiei \"<tt>$2</tt>\".
MySQL a returnat eroarea \"<tt>$3: $4</tt>\".",
"dberrortextcl"	=> "A aoărut o eroare de sintaxă în query.
Ultimul query încercat a fost:
\"$1\"
din funcţia \"$2\".
MySQL a returnat eroarea \"$3: $4\".\n",
"noconnect"		=> "Nu s-a putut conecta baza de date pe $1",
"nodb"			=> "Nu s-a putut selecta baza de date $1",
"cachederror"	=> "Aceasta este o versiune din cache a paginii cerute şi este posibil să nu fie ultima variantă a acesteia.",
"readonly"		=> "Baza de date este blocată la scriere",
"enterlockreason" => "Introduceţi motivul pentru blocare, incluzând o estimare a termenului când veţi debloca baza de date",
"readonlytext"	=> "Baza de date Wikipedia este momentan blocată la scriere, probabil pentru o operaţiune de rutină, după care va fi deblocată şi se va reveni la starea normală.
Administratorul care a blocat-o a oferit această explicaţie:
<p>$1",
"missingarticle" => "Textul \"$1\" nu a putut fi găsit în baza de date, aşa cum ar fi trebuit. Aceasta nu este o problemă legată de programul care gestionează baza de date, ci probabil o problemă in programul care administrează Wikipedia. Vă rugăm să raportaţi această problemă unui administrator, incluzând şi adresa acestei pagini.",
"internalerror" => "Eroare internă",
"filecopyerror" => "Fisierul \"$1\" nu a putut fi copiat la \"$2\".",
"filerenameerror" => "Fişierul \"$1\" nu a putut fi mutat la \"$2\".",
"filedeleteerror" => "Fişierul \"$1\" nu a putut fi şters.",
"filenotfound"	=> "Fişierul \"$1\" nu a putut fi găsit.",
"unexpected"	=> "Valoare neaşteptată: \"$1\"=\"$2\".",
"formerror"		=> "Eroare: datele nu au putut fi trimise",
"badarticleerror" => "Această acţiune nu poate fi efectuată pe această pagină.",
"cannotdelete"	=> "Nu s-a putut şterge pagina sau imaginea (poate a şters-o altcineva deja?)",
"badtitle"		=> "Titlu invalid",
"badtitletext"	=> "Titlul căutat a fost invalid, gol sau o legătură invalidă inter-linguală sau inter-wiki.",
"perfdisabled" => "Ne pare rău! Această funcţionalitate a fost dezactivată temporar în timpul orelor de vârf din motive de performanţă. Vă rugăm să reveniţi la altă oră şi încercaţi din nou.", // Didn't provide any off-peak hours because they may differ on the Romanian Wikipedia.
"perfdisabledsub"	=> "Iată o copie salvată de la $1:",
"wrong_wfQuery_params" => "Număr incorect de parametri pentru wfQuery()<br>
Funcţia: $1<br>
Query: $2
",
"viewsource" => "Vezi sursa",
"protectedtext" => "Această pagină a fost protejată la editare;
există mai multe motive posibile pentru aceasta, vezi
[[$wgMetaNamespace:Pagină protejată]].

Puteţi vedea şi copia sursa acestei pagini:",

# Login and logout pages
#
"logouttitle"	=> "Sesiune închisă",
"logouttext"	=> "Sesiunea Dvs. în Wikipedia a fost închisă.
Puteţi continua să folosiţi Wikipedia anonim, sau puteţi să vă reautentificaţi ca acelaşi sau ca alt utilizator.\n",
"welcomecreation" => "<h2>Bun venit, $1!</h2><p>A fost creat un cont pentru Dvs.
Nu uitaţi să vă personalizaţi preferinţele în Wikipedia.",
"loginpagetitle" => "Autentificare utilizator",
"yourname"		=> "Numele de utilizator",
"yourpassword"	=> "Parola",
"yourpasswordagain" => "Repetaţi parola",
"newusersonly"	=> " (doar pentru utilizatori noi)",
"remembermypassword" => "Reţine-mi parola între sesiuni.",
"loginproblem"	=> "<b>A fost o problemă cu autentificarea Dvs.</b><br>Încercaţi din nou!",
"alreadyloggedin" => "<font color=red><b>Sunteţi deja autentificat ca $1!</b></font><br>\n",

"notloggedin" => "Nu sunteţi autentificat",

"login"			=> "Autentificare",
"loginprompt"           => "Trebuie să aveţi cookies activate în browser pentru a vă putea autentifica pe $wgSitename.",
"userlogin"		=> "Autentificare",
"logout"		=> "Închide sesiunea",
"userlogout"	=> "Închide sesiunea",
"createaccount"	=> "Creează cont nou",
"createaccountmail"	=> "după e-mail",
"badretype"		=> "Parolele pe care le-aţi introdus diferă.",
"userexists"	=> "Numele de utilizator pe care l-aţi introdus există deja. Încercaţi cu un alt nume.",
"youremail"		=> "Adresa de mail",
"yournick"		=> "Versiune scurtă a numelui, pentru semnături",
"emailforlost"	=> "Dacă vă pierdeţi parola, puteţi cere să vi se trimită una nouă la adresa de mail.",
"loginerror"	=> "Eroare de autentificare",
"nocookiesnew"	=> "Contul a fost creat, dar Dvs. nu sunteţi autentificat(ă). $wgSitename foloseşte cookies pentru a reţine utilizatorii autentificaţi. Browser-ul Dvs. are cookies neactivate (disabled). Vă rugăm să le activaţi şi să vă reautentificaţi folosind noul nume de utilizator şi noua parolă.",
"nocookiestext"	=> "Wiki foloseşte cookie-uri pentru a autentifica utilizatorii. Browser-ul Dvs. are cookies dezactivate. Vă rugăm să le activaţi în browser şi să încercaţi din nou.",
"nocookieslogin"	=> "$wgSitename foloseşte cookies pentru a autentifica utilizatorii. Browser-ul Dvs. are cookies dezactivate. Vă rugăm să le activaţi şi să incercaţi din nou.",
"noname"		=> "Numele de utilizator pe care l-aţi specificat este invalid.",
"loginsuccesstitle" => "Autentificare reuşită",
"loginsuccess"	=> "Aţi fost autentificat în Wikipedia ca \"$1\".",
"nosuchuser"	=> "Nu există nici un utilizator cu numele \"$1\".
Verificaţi dacă aţi scris corect sau folosiţi această pagină pentru a crea un nou utilizator.",
"wrongpassword"	=> "Parola pe care aţi introdus-o este greşită. Vă rugăm încercaţi din nou.",
"mailmypassword" => "Trimiteţi-mi parola pe mail!",
"passwordremindertitle" => "Amintirea parolei pe Wikipedia",
"passwordremindertext" => "Cineva (probabil Dvs., de la adresa $1)
a cerut să vi se trimită o nouă parolă pentru Wikipedia.
Parola pentru utilizatorul \"$2\" este acum \"$3\".
Este recomandat să intraţi pe Wikipedia şi să vă schimbaţi parola cât mai curând.",
"noemail"		=> "Nu este nici o adresă de mail înregistrată pentru utilizatorul \"$1\".",
"passwordsent"	=> "O nouă parolă a fost trimisă la adresa de mail a utilizatorului \"$1\".
Vă rugăm să vă autentificaţi pe Wikipedia după ce o primiţi.",

# Edit pages
#
"summary"		=> "Sumar",
"subject"		=> "Subiect/titlu",
"minoredit"		=> "Aceasta este o editare minoră",
"watchthis"		=> "Urmăreşte această pagină",
"savearticle"	=> "Salvează pagina",
"preview"		=> "Previzualizare",
"showpreview"	=> "Arată previzualizare",
"blockedtitle"	=> "Utilizatorul este blocat",
"blockedtext"	=> "Utilizatorul sau parola Dvs. au fost blocate de $1.
Motivul oferit pentru blocare a fost:<br>''$2''<p>Puteţi contacta pe $1 sau pe unul dintre ceilalţi
[[Wikipedia:administratori|administratori]] pentru a discuta această blocare.",
"whitelistedittitle" => "Este necesară autentificarea pentru a edita",
"whitelistedittext" => "Trebuie să vă [[Special:Userlogin|autentificaţi]] pentru a edita articole.",
"whitelistreadtitle" => "Este necesară autentificarea pentru a citi",
"whitelistreadtext" => "Trebuie să vă [[Special:Userlogin|autentificaţi]] pentru a citi articole.",
"whitelistacctitle" => "Nu aveţi dreptul de a crea conturi",
"whitelistacctext" => "Trebuie să vă [[Special:Userlogin|autentificaţi]] şi să aveţi permisiunile corecte pentru a crea conturi.",
"accmailtitle" => "Parola a fost trimisă.",
"accmailtext" => "Parola pentru '$1' a fost trimisă la $2.",
"newarticle"	=> "(Nou)",
"newarticletext" =>
"Aţi ajuns la o pagină care nu există.
Pentru a o crea, începeţi să scrieţi în caseta de mai jos
(vezi [[Wikipedia:Ajutor|pagina de ajutor]] pentru mai multe informaţii).
Dacă aţi ajuns aici din greşeală, întoarceţi-vă folosind controalele browser-ului Dvs.",
"anontalkpagetext" => "---- ''Aceasta este pagina de discuţii pentru un utilizator care nu şi-a creat un cont încă, sau care nu s-a autentificat. De aceea trebuie să folosim [[adresa IP]] pentru a identifica această persoană. O adresă IP poate fi împărţită între mai mulţi utilizatori. Dacă sunteţi un astfel de utilizator şi credeţi că vi se adresează mesaje irelevante, vă rugăm să [[Special:Userlogin|vă creaţi un cont sau să vă autentificaţi]] pentru a evita confuzii cu alţi utilizatori anonimi în viitor.'' ",
"noarticletext" => "(Nu există text în această pagină)",
"updated"		=> "(Actualizat)",
"note"			=> "<strong>Notă:</strong> ",
"previewnote"	=> "Reţineţi că aceasta este doar o previzualizare - articolul încă nu este salvat! Trebuie să apăsaţi butonul \"Salvează pagina\" de sub caseta de editare pentru a salva. Nu uitaţi să introduceţi şi o descriere sumară a modificărilor!",
"previewconflict" => "Această pre-vizualizare reflectă textul din caseta de sus, respectiv felul în care va arăta articolul dacă alegeţi să salvaţi acum.",
"editing"		=> "Editare $1",
"sectionedit"	=> " (secţiune)",
"commentedit"	=> " (comentariu)",
"editconflict"	=> "Conflict de editare: $1",
"explainconflict" => "Altcineva a modificat această pagină de când aţi început s-o editaţi.
Caseta de text de sus conţine pagina aşa cum este ea acum (după editarea celeilalte persoane).
Pagina cu modificările Dvs. (aşa cum aţi încercat s-o salvaţi) se află în caseta de jos.
Va trebui să editaţi manual caseta de sus pentru a reflecta modificările pe care tocmai le-aţi făcut în cea de jos.
<b>Numai</b> textul din caseta de sus va fi salvat atunci când veţi apăsa pe  \"Salvează pagina\".\n<p>",
"yourtext"		=> "Textul Dvs.",
"storedversion" => "Versiunea curentă",
"editingold"	=> "<strong>ATENŢIE! Editaţi o variantă mai veche a acestei pagini! Orice modificări care s-au făcut de la această versiune şi până la cea curentă se vor pierde!</strong>\n",
"yourdiff"		=> "Diferenţe",
"copyrightwarning" => "Ajutor pentru editare, caractere speciale: ă â î ş ţ Ă Â Î Ş Ţ<br><br>Reţineţi că toate contribuţiile la Wikipedia sunt considerate ca respectând licenţa GNU Free Documentation License
(vezi $1 pentru detalii).
Dacă nu doriţi ca ceea ce scrieţi să fie editat fără milă şi redistribuit în voie, atunci nu trimiteţi materialele respective aici.<br>
De asemenea, trimiţând aceste materiale aici vă angajaţi că le-aţi scris Dvs. sau că sunt copiate dintr-o sursă care permite includerea materialelor sub această licenţă.
<strong>NU TRIMITEŢI MATERIALE PROTEJATE DE DREPTURI DE AUTOR FĂRĂ PERMISIUNE!</strong>",
"longpagewarning" => "ATENŢIE! Conţinutul acestei pagini are $1 KB; unele browsere au probleme la editarea paginilor în jur de 32 KB sau mai mari.
Vă rugăm să luaţi în considerare posibilitatea de a împărţi pagina în mai multe secţiuni.",
"readonlywarning" => "ATENŢIE! Baza de date a fost blocată pentru întreţinere,
deci vă nu veţi putea salva editările în acest moment. Puteţi copia textul
într-un fişier text local pentru a modifica conţinutul în Wikipedia când va fi posibil.",
"protectedpagewarning" => "ATENŢIE! Această pagină a fost blocată şi numai utilizatorii
cu privilegii de administrator o pot edita. Vă rugăm urmaţi sugestiile
<a href='Wikipedia:Despre_pagini_protejate'>despre pagini protejate</a> când editaţi.",

# History pages
#
"revhistory"	=> "Istoria versiunilor",
"nohistory"		=> "Nu există istorie pentru această pagină.",
"revnotfound"	=> "Versiunea nu a fost găsită",
"revnotfoundtext" => "Versiunea mai veche a paginii pe care aţi cerut-o nu a fost găsită. Vă rugăm să verificaţi legătura pe care aţi folosit-o pentru a accesa această pagină.\n",
"loadhist"		=> "Încarc istoria versiunilor",
"currentrev"	=> "Versiunea curentă",
"revisionasof"	=> "Versiunea de la data $1",
"cur"			=> "actuală",
"next"			=> "următoarea",
"last"			=> "prec",
"orig"			=> "orig",
"histlegend"	=> "Legendă: (actuală) = diferenţe faţă de versiunea curentă,
(prec) = diferenţe faţă de versiunea precedentă, M = editare minoră",

# Diffs
#
"difference"	=> "(Diferenţa dintre versiuni)",
"loadingrev"	=> "se încarcă diferenţa dintre versiuni",
"lineno"		=> "Linia $1:",
"editcurrent"	=> "Editarea versiunii curente a acestei pagini",

# Search results
#
"searchresults" => "Rezultatele căutării",
"searchhelppage" => "Wikipedia:Searching",
"searchingwikipedia" => "Căutare în Wikipedia",
"searchresulttext" => "Pentru mai multe detalii despre căutarea în Wikipedia, vezi $1.",
"searchquery"	=> "Pentru căutarea \"$1\"",
"badquery"		=> "Căutare invalidă",
"badquerytext"	=> "Căutarea Dvs. nu a putut fi procesată.
Asta se întâmplă probabil din cauză că aţi încercat să căutaţi un cuvânt cu mai puţin de trei litere.
E posibil şi să fi scris greşit o expresie sau un nume, cum ar fi \"Mircea cel cel Bătrân\".
Vă rugăm să încercaţi o altă căutare.",
"matchtotals"	=> "Căutarea \"$1\" a produs $2 rezultate în titluri de articole şi $3 rezultate în texte de articole.",
"nogomatch" => "Nici o pagină cu acest titlu nu a fost găsită, încercaţi să căutaţi textul şi în pagini. ",
"titlematches"	=> "Rezultate în titluri de articole",
"notitlematches" => "Nici un rezultat în titlurile articolelor",
"textmatches"	=> "Rezultate în textele articolelor",
"notextmatches"	=> "Nici un rezultat în textele articolelor",
"prevn"			=> "anterioarele $1",
"nextn"			=> "următoarele $1",
"viewprevnext"	=> "Vezi ($1) ($2) ($3).",
"showingresults" => "Mai jos apar <b>$1</b> rezultate începând cu numărul <b>$2</b>.",
"showingresultsnum" => "Mai jos apar <b>$3</b> rezultate începând cu numărul <b>$2</b>.",
"nonefound"		=> "<strong>Notă</strong>: căutările nereuşite sunt în general datorate căutării unor cuvinte prea comune care nu sunt indexate, sau cautărilor a mai multe cuvinte (numai articolele care conţin ''toate'' cuvintele specificate apar ca rezultate).",
"powersearch" => "Caută",
"powersearchtext" => "
Caută în secţiunile:<br>
$1<br>
$2 Redirecţionări&nbsp; Căutări după $3 $9",

"searchdisabled" => "<p>Ne pare rău! Căutarea după text a fost dezactivată temporar, din motive de performanţă. Între timp puteţi folosi căutarea prin Google mai jos, însă aceasta poate să dea rezultate învechite.</p>

",
"googlesearch" => "<!-- SiteSearch Google -->
<FORM method=GET action=\"http://www.google.com/search\">
<TABLE bgcolor=\"#FFFFFF\"><tr><td>
<A HREF=\"http://www.google.com/\">
<IMG SRC=\"http://www.google.com/logos/Logo_40wht.gif\"
border=\"0\" ALT=\"Google\"></A>
</td>
<td>
<INPUT TYPE=text name=q size=31 maxlength=255 value=\"$1\">
<INPUT type=submit name=btnG VALUE=\"Caută pe Google\">
<font size=-1>
<input type=hidden name=domains value=\"{$wgServer}\"><br><input type=radio name=sitesearch value=\"\"> WWW <input type=radio name=sitesearch value=\"{$wgServer}\" checked> {$wgServer} <br>
<input type='hidden' name='ie' value='$2'>
<input type='hidden' name='oe' value='$2'>
</font>
</td></tr></TABLE>
</FORM>
<!-- SiteSearch Google -->",

"blanknamespace" => "(Principală)",


# Preferences page
#
"preferences"	=> "Preferinţe",
"prefsnologin" => "Neautentificat",
"prefsnologintext"	=> "Trebuie să fiţi <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">autentificat</a>
pentru a vă putea salva preferinţele.",
"prefslogintext" => "Sunteţi autentificat ca \"$1\".
Numărul intern de identificare este $2 (nu trebuie să-l reţineţi decât dacă aveţi probleme).",
"prefsreset"	=> "Preferinţele au fost resetate.",
"qbsettings"	=> "Setări pentru quickbar", 
"changepassword" => "Schimbă parola",
"skin"			=> "Aparenţă",

"math"			=> "Apareţă formule",
"dateformat" => "Formatul datelor",
"math_failure"		=> "Nu s-a putut interpreta",
"math_unknown_error"	=> "eroare necunoscută",
"math_unknown_function"	=> "funcţie necunoscută ",
"math_lexing_error"	=> "eroare lexicală",
"math_syntax_error"	=> "eroare de sintaxă",
"saveprefs"		=> "Salvează preferinţele",

"resetprefs"	=> "Resetează preferinţele",
"oldpassword"	=> "Parola veche",
"newpassword"	=> "Parola nouă",
"retypenew"		=> "Repetă parola nouă",
"textboxsize"	=> "Dimensiunile casetei de text",
"rows"			=> "Rânduri",
"columns"		=> "Coloane",
"searchresultshead" => "Setări de căutare",
"resultsperpage" => "Numărul de rezultate per pagină",
"contextlines"	=> "Numărul de linii per rezultat",
"contextchars"	=> "Numărul de caractere per linie",
"stubthreshold" => "Limita de caractere pentru un ciot",
"recentchangescount" => "Numărul de articole pentru schimbări recente",
"savedprefs"	=> "Preferinţele Dvs. au fost salvate.",
"timezonetext"	=> "Introduceţi numărul de ore diferenţă între ora locală şi ora serverului (UTC, timp universal - pentru România, cifra este 3).",
"localtime"	=> "Ora locală",
"timezoneoffset" => "Diferenţa",
"servertime"	=> "Ora serverului (UTC)",
"guesstimezone" => "Încearcă determinarea automată a diferenţei",
"emailflag"		=> "Dezactivează serviciul de e-mail de la alţi utilizatori",
"defaultns" => "Caută în aceste secţiuni implicit:",

# Recent changes
#
"changes" => "schimbări",
"recentchanges" => "Schimbări recente",
"recentchangestext" => "Aceată pagină permite vizualizarea ultimelor modificări ale paginilor Wikipedia în română.

[[Wikipedia:bun venit|Bun venit pe Wikipedia]]! Nu ezitaţi să vizitaţi secţiunile de [[Wikipedia:întrebări frecvente|întrebări frecvente]], [[Wikipedia:politica|politica Wikipedia]] (în special [[Wikipedia:convenţii pentru denumiri|convenţii pentru denumiri]] şi [[Wikipedia:punct de vedere neutru|punct de vedere neutru]]), şi cele mai comune [[Wikipedia:greşeli frecvente|greşeli în Wikipedia]].

Este foarte important să nu adăugaţi în Wikipedia materiale protejate de [[drepturi de autor]]. Problemele legale rezultate ar putea prejudicia în mod serios proiectul în întregime, aşa că vă rugăm insistent să aveţi grijă să nu faceţi asta.",
"rcloaderr"		=> "Încarc ultimele modificări",
"rcnote"		=> "Dedesubt găsiţi ultimele <strong>$1</strong> modificări din ultimele <strong>$2</strong> zile.",
"rcnotefrom"	=> "Dedesubt sunt modificările de la <b>$2</b> (maxim <b>$1</b> de modificări sunt afişate - schimbaţi numărul maxim de linii alegând altă valoare mai jos).",
"rclistfrom"	=> "Arată modificările începând de la $1",
# "rclinks"		=> "Arată ultimele $1 modificări din ultimele $2 ore / ultimele $3 zile",
"showhideminor"         => "$1 editări minore",
"rclinks"		=> "Arată ultimele $1 modificări din ultimele $2 zile.",

"rchide"		=> "în in $4 form; $1 minor edits; $2 secondary namespaces; $3 multiple edits.",
"rcliu"			=> "; $1 editări operate de utilizatori autentificaţi",
"diff"			=> "diferenţă",
"hist"			=> "istorie",
"hide"			=> "ascunde",
"show"			=> "arată",
"tableform"		=> "tabel",
"listform"		=> "listă",
"nchanges"		=> "$1 modificări",
"minoreditletter" => "M",
"newpageletter" => "N",

# Upload
#
"upload"		=> "Trimite fişier",
"uploadbtn"		=> "Trimite fişier",
"uploadlink"	=> "Trimite imagine",
"reupload"		=> "Re-trimite",
"reuploaddesc"	=> "Întoarcere la formularul de trimitere.",
"uploadnologin" => "Nu sunteţi autentificat",
"uploadnologintext"	=> "Trebuie să foţi <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">autentificat</a>
ca să trimiteţi fişiere.",
"uploadfile"	=> "Trimite fişier",
"uploaderror"	=> "Eroare la trimitere fişier",
"uploadtext"	=> "<strong>STOP!</strong> Înainte de a trimite un fişier aici,
vă rugăm să citiţi şi să respectaţi <a href=\"" .
wfLocalUrlE("Wikipedia:Politica_de_utilizare_a_imaginilor" ) . "\">politica de utilizare a imaginilor</a>.
<p>Pentru a vizualiza sau căuta imagini deja trimise, mergeţi la <a href=\"" . wfLocalUrlE( "Special:Imagelist" ) .
"\">lista de imagini</a>.
Fişierele noi şi cele şterse sunt contorizate pe paginile de <a href=\"" .
wfLocalUrlE( "Wikipedia:Upload_log" ) . "\">raport de trimiteri</a>.
<p>Folosiţi formularul de mai jos pentru a trimite imagini noi
pe care le veţi putea folosi pentru a vă ilustra articolele.
În majoritatea browserelor veţi vedea un buton \"Browse...\"
care vă va deschide fereastra standard dialog a sistemului Dvs. de operare
pentru alegerea de fişiere.
Când alegeţi un fişier în acest fel, caseta de dialog se va completa cu calea locală către acesta.
Este de asemenea necesar să bifaţi căsuţa asociată textului
în care confirmaţi că nu violaţi nici un drept de autor trimiţând această imagine.
În final, apăsaţi pe butonul \"Trimite\" petru a trimite efectiv fişierul.
Această operaţiune poate dura, mai ales dacă aveţi o legătură lentă la Internet.
<p>Formatele preferate sunt JPEG pentru imagini fotografice,
PNG pentru desene şi alte imagini cu contururi clare şi OGG pentru fişiere de sunet.

Vă rugăm să folosiţi nume explicite pentru fişiere ca să evitaţi confuziile.
Pentru a include o imagine într-un articol, folosiţi o legătură de forma <b>[[image:fişier.jpg]]</b> sau <b>[[image:fişier.png|text alternativ]]</b>
sau <b>[[media:fişier.ogg]]</b> pentru fişiere de sunet.
<p>Vă rugăm să reţineţi că, la fel ca şi în cazul celorlalte secţiuni din Wikipedia, alte persoane pot edita sau şterge fişierele pe care le trimiteţi dacă e în interesul enciclopediei, şi vi se poate chiar bloca accesul la trimiterea de fişiere dacă abuzaţi de sistem.",
"uploadlog"		=> "raport de trimitere fişiere",
"uploadlogpage" => "Raport de trimitere fişiere",
"uploadlogpagetext" => "Găsiţi mai jos lista ultimelor fişiere trimise.
Toate datele/orele sunt afişate ca timp universal (UTC).
<ul>
</ul>
",
"filename"		=> "Nume fişier",
"filedesc"		=> "Sumar",
"filestatus" => "Statutul drepturilor de autor",
"filesource" => "Sursa",
"affirmation"	=> "Afirm că persoana care deţine drepturile de autor asupra acestui fişier este de acord cu termenii licenţei $1.",
"copyrightpage" => "Wikipedia:Drepturi_de_autor",
"copyrightpagename" => "Drepturi de autor în Wikipedia",
"uploadedfiles"	=> "Fişiere trimise",
"noaffirmation" => "Trebuie să afirmaţi că fişierul pe care în trimiteţi nu violează drepturi de autor (trebuie să bifaţi căsuţa aferentă de pe pagina anterioară).",
"ignorewarning"	=> "Ignoră atenţionarea şi salvează.",
"minlength"		=> "Numele imaginilor trebuie să aibă cel puţin trei litere.",
"badfilename"	=> "Numele imaginii a fost schimbat; noul nume este \"$1\".",
"badfiletype"	=> "\".$1\" nu este un format recomandat pentru imagini.",
"largefile"		=> "Este recomandat ca imaginile să nu depăşească 100 KB ca mărime.",
"successfulupload" => "Fişierul a fost trimis",
"fileuploaded"	=> "Fişierul \"$1\" a fost trimis.
Vă rugăm să vizitaţi această legătură: ($2) pentru a descrie fişierul şi pentru a completa informaţii despre acesta, ca de exemplu de unde provine, când a fost creat şi de către cine, cât şi orice alte informaţii doriţi să adăugaţi.",
"uploadwarning" => "Avertizare la trimiterea fişierului",
"savefile"		=> "Salvează fişierul",
"uploadedimage" => "trimis \"$1\"",
"uploaddisabled" => "Ne pare rău, trimiterea de imagini este dezactivată.",

# Image list
#
"imagelist"		=> "Lista imaginilor",
"imagelisttext"	=> "Dedesubt găsiţi lista a $1 imagini ordonate $2.",
"getimagelist"	=> "încarc lista de imagini",
"ilshowmatch"	=> "Arată imaginile ale căror nume includ",
"ilsubmit"		=> "Caută",
"showlast"		=> "Arată ultimele $1 imagini ordonate $2.",
"all"			=> "toate",
"byname"		=> "după nume",
"bydate"		=> "după dată",
"bysize"		=> "după mărime",
"imgdelete"		=> "şterge",
"imgdesc"		=> "desc",
"imglegend"		=> "Legendă: (desc) = arată/editează descrierea imaginii.",
"imghistory"	=> "Istoria imaginii",
"revertimg"		=> "rev",
"deleteimg"		=> "şterg",
"deleteimgcompletely"		=> "şterg",
"imghistlegend" => "Legend: (actuală) = versiunea curentă a imaginii, (şterg) = şterge această versiune veche, (rev) = revino la această versiune veche.
<br><i>Apăsaţi pe dată pentru a vedea versiunea trimisă la data respectivă</i>.",
"imagelinks"	=> "Legăturile imaginii",
"linkstoimage"	=> "Următoarele pagini leagă la această imagine:",
"nolinkstoimage" => "Nici o pagină nu se leagă la această imagine.",

# Statistics
#
"statistics"	=> "Statistici",
"sitestats"		=> "Statisticile sitului",
"userstats"		=> "Statistici legate de utilizatori",
"sitestatstext" => "Există un număr total de <b>$1</b> pagini în baza de date.
Acest număr include paginile de \"discuţii\", paginile despre Wikipedia, pagini minimale (\"cioturi\"), pagini de redirecţionare şi altele care probabil că nu intră de fapt în categoria articolelor reale.
În afară de acestea, există <b>$2</b> pagini care sunt probabil articole (numărate automat, în funcţie strict de mărime).<p>
În total au fost <b>$3</b> vizite (accesări) şi <b>$4</b> editări
de la ultima actualizare a programului (July 20, 2002).
În medie rezultă că fiecare pagină a fost editată de <b>$5</b>ori şi că au fost <b>$6</b> vizualizări la fiecare editare.",
"userstatstext" => "Există un număr de <b>$1</b> utilizatori înregistraţi.
Dintre aceştia <b>$2</b> sunt administratori (vezi $3).",

# Maintenance Page
#
"maintenance"		=> "Pagina administrativă",
"maintnancepagetext"	=> "Această pagină conţine diverse unelte create pentru administrare cotidiană. Unele dintre acestea solicită în mod deosebit baza de date, aşa că vă rugăm să evitaţi suprasolicitarea lor.",
"maintenancebacklink"	=> "Înapoi la pagina administrativă",
"disambiguations"	=> "Pagini de dezambiguizare",
"disambiguationspage"	=> "Wikipedia:Legături_către_paginile_de_dezambiguizare",
"disambiguationstext"	=> "Următoarele articole conţin legături către cel puţin o <i>pagină de dezambiguizare</i>. Legăturile respective ar trebui făcute către paginile specifice.<br>O pagină este considerată ca fiind de dezambiguizare dacă există o legătură în ea dinspre $1.<br>Legăturile dinspre alte secţiuni Wikipedia <i>nu sunt</i> luate în considerare aici.",
"doubleredirects"	=> "Redirectări duble",
"doubleredirectstext"	=> "<b>Atenţie:</b> Această listă poate conţine articole care nu sunt în fapt duble redirectări. Asta înseamnă de obicei că există text adiţional sub primul #REDIRECT.<br>\nFiecare rând care conţine legături către prima sau a doua redirectare, ca şi prima linie din textul celei de-a doua redirectări, de obicei conţinând numele \"real\" al articolului ţintă, către care ar trebui să arate prima redirectare.",
"brokenredirects"	=> "Redirectări greşite",
"brokenredirectstext"	=> "Următoarele redirectări arată către articole inexistente.",
"selflinks"		=> "Pagini cu legături ciclice",
"selflinkstext"		=> "Următoarele pagini conţin legături către ele însele, ceea ce n-ar trebui să se întâmple.",
"mispeelings"           => "Pagini conţinând greşeli comune",
"mispeelingstext"               => "Următoarele pagini conţin unele dintre greşelile obişnuite de scriere care apar la $1. Forma corectă poate fi dată (în acest fel).",
"mispeelingspage"       => "Lista de greţeli comune",
"missinglanguagelinks"  => "Legături care inexistente către alte limbi",
"missinglanguagelinksbutton"    => "Caută limbi inexistente pentru",
"missinglanguagelinkstext"      => "Aceste articole nu se leagă către perechile lor din $1. Redirectările şi sub-paginile <i>nu apar</i> aici.",


# Miscellaneous special pages
#
"orphans"		=> "Pagini orfane",
"lonelypages"	=> "Pagini orfane",
"unusedimages"	=> "Pagini neutilizate",
"popularpages"	=> "Pagini populare",
"nviews"		=> "$1 accesări",
"wantedpages"	=> "Pagini dorite",
"nlinks"		=> "$1 legături",
"allpages"		=> "Toate paginile",
"randompage"	=> "Pagină aleatoare",
"shortpages"	=> "Pagini scurte",
"longpages"		=> "Pagini lungi",
"deadendpages"  => "Pagini fără legături",
"listusers"		=> "Lista de utilizatori",
"specialpages"	=> "Pagini speciale",
"spheading"		=> "Pagini speciale",
"sysopspheading" => "Pegini speciale pentru operatori",
"developerspheading" => "Pagini speciale pentru dezvoltatori",
"protectpage"	=> "Protejează pagina",
"recentchangeslinked" => "Modificări corelate",
"rclsub"		=> "(cu pagini legate de la \"$1\")",
"debug"			=> "Debug",
"newpages"		=> "Pagini noi",
"ancientpages"		=> "Cele mai vechi articole",
"intl"		=> "Legături între limbi",
"movethispage"	=> "Mută această pagină",
"unusedimagestext" => "<p>Vă rugăm să ţineţi cont de faptul că alte situri, inclusiv Wikipedii în alte limbi pot să aibă legături aici fără ca aceste pagini să fie listate aici - această listă se referă strict la Wikipedia în română.",
"booksources"	=> "Surse de cărţi",
"booksourcetext" => "Dedesubt găsiţi o listă de surse de cărţi noi şi vechi, şi e posibil să găsiţi şi informaţii adiţionale legate de titlurile pe care le căutaţi.
Wikipedia nu este afiliată niciuneia dintre aceste afaceri,
iar lista de mai jos nu constituie nici un fel de garanţie sau validare a serviciilor respective din partea Wikipedia.",
"alphaindexline" => "$1 către $2",

# Email this user
#
"mailnologin"	=> "Nu există adresă de trimitere",
"mailnologintext" => "Trebuie să fiţi <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">autentificat</a>
şi să aveţi o adresă validă de mail în <a href=\"" .
  wfLocalUrl( "Special:Preferences" ) . "\">preferinţe</a>
pentru a trimite mail altor utilizatori.",
"emailuser"		=> "Trimite e-mail acestui utilizator",
"emailpage"		=> "E-mail către utilizator",
"emailpagetext"	=> "Dacă acest utilizator a introdus o adresă de mail validă în pagina de preferinţe atunci formularul de mai jos poate fi folosit pentru a-i trimte un mesaj prin e-mail.
Adresa pe care aţi introdus-o în pagina Dvs. de preferinţe va apărea ca adresa
de origine a mesajului, astfel încât destinatarul să vă poată răspunde direct.",
"noemailtitle"	=> "Fără adresă de e-mail",
"noemailtext"	=> "Utilizatorul nu a specificat o adresă validă de e-mail,
sau a ales să nu primească e-mail de la alţi utilizatori.",
"emailfrom"		=> "De la",
"emailto"		=> "Către",
"emailsubject"	=> "Subiect",
"emailmessage"	=> "Mesaj",
"emailsend"		=> "Trimite",
"emailsent"		=> "E-mail trimis",
"emailsenttext" => "E-mailul Dvs. a fost trimis.",

# Watchlist
#
"watchlist"		=> "Articole urmărite",
"watchlistsub"	=> "(pentru utilizatorul \"$1\")",
"nowatchlist"	=> "Nu aţi ales să urmăriţi nici un articol.",
"watchnologin"	=> "Nu sunteţi autentificat",
"watchnologintext"	=> "Trebuie să fiţi <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">autentificat</a>
pentru a vă modifica lista de articole urmărite.",
"addedwatch"	=> "Adăugată la lista de pagini urmărite",
"addedwatchtext" => "Pagina \"$1\" a fost adăugată la lista Dvs. de <a href=\"" . wfLocalUrl( "Special:Watchlist" ) . "\">articole urmărite</a>.
Modificările viitoare ale acestei pagini şi a paginii asociate de discuţii
vor fi listate aici, şi în plus ele vor apărea cu <b>caractere îngroşate</b> în pagina de <a href=\"" .
  wfLocalUrl( "Special:Recentchanges" ) . "\">modificări recente</a> pentru evidenţiere.</p>

<p>Dacă doriţi să eliminaţi această pagină din lista Dvs. de pagini urmărite
în viitor, apăsaţi pe \"Nu mai urmări\" în bara de comenzi în timp ce această pagină este vizibilă.",
"removedwatch"	=> "Ştearsă din lista de pagini urmărite",
"removedwatchtext" => "Pagina \"$1\" a fost eliminată din lista de pagini urmărite.",
"watchthispage"	=> "Urmăreşte pagina",
"unwatchthispage" => "Nu mai urmări",
"notanarticle"	=> "Nu este un articol",
"watchnochange" => "Nici unul dintre articolele pe care le urmăriţi nu a fost modificat în perioada de timp afişată.",
"watchdetails" => "($1 pagini urmărite în afară de paginile de discuţie;
$2 pagini editate în total;
$3...
<a href='$4'>lista completă</a>.)",
"watchmethod-recent" => "căutarea schimbărilor recente pentru paginile urmărite",
"watchmethod-list" => "căutarea paginilor urmărite pentru schimbări recente",
"removechecked" => "Elimină elementele bifate din lista de pagini urmărite",
"watchlistcontains" => "Lista de articole urmărite conţine $1 pagini",
"watcheditlist" => "Aceasta este lista alfabetică a tuturor paginilor pe care le urmăriţi.
Bifaţi căsuţele corespunzătoare paginilor pe care doriţi să le eliminaţi din lista de
pagini urmărite şi apăsaţi pe butonul corespunzător din partea de jos a paginii.",
"removingchecked" => "Se elimină elementele cerute din lista de articole urmărite...",
"couldntremove" => "Elementul '$1' nu a putut fi şters...",
"iteminvalidname" => "E o problemă cu elementul '$1', numele este invalid...",
"wlnote" => "Dedesubt găsiţi ultimele $1 schimbări din ultimele <b>$2</b> ore.",
"wlshowlast" => "Arată ultimele $1 ore $2 zile $3",

# Delete/protect/revert
#
"deletepage"	=> "Şterge pagina",
"confirm"		=> "Confirmă",
"excontent" => "conţinutul era:",
"exbeforeblank" => "conţinutul înainte de golire era:",
"exblank" => "pagina era goală",
"confirmdelete" => "Confirmă ştergere",
"deletesub"		=> "(Şterg \"$1\")",
"confirmdeletetext" => "Sunteţi pe cale să ştergeţi permanent o pagină
sau imagine din baza de date, împreună cu istoria asociată.
Vă rugăm să vă confirmaţi intenţia de a face asta, faptul că
înţelegeţi consecinţele acestei acţiuni şi faptul că o faceţi
în conformitate cu [[Wikipedia:Politica]].",
"confirmcheck"	=> "Da, chiar vreau să şterg.",
"actioncomplete" => "Acţiune finalizată",
"deletedtext"	=> "\"$1\" a fost ştearsă.
Vezi $2 pentru o listă a elementelor şterse recent.",
"deletedarticle" => "\"$1\" a fost şters",
"dellogpage"	=> "Raport_ştergeri",
"dellogpagetext" => "Găsiţi dedesubt o listă a celor mai recente elemente şterse. Toate datele/orele sunt listate în timp universal (UTC).
<ul>
</ul>
",
"deletionlog"	=> "raport de ştergeri",
"reverted"		=> "Revenit la o versiune mai veche",
"deletecomment"	=> "Motiv pentru ştergere",
"imagereverted" => "S-a revenit la o versiune veche.",
"rollback"		=> "Editări de revenire",
"rollbacklink"	=> "revenire",
"rollbackfailed" => "Revenirea nu s-a putut face",
"cantrollback"	=> "Nu se poate reveni; ultimul contribuitor este autorul acestui articol.",
"alreadyrolled"	=> "Nu se poate reveni peste ultima editare a [[$1]]
făcută de către [[Utilizator:$2|$2]] ([[Discuţie utilizator:$2|Discuţie]]); altcineva a editat articolul sau a revenit deja.

Ultima editare a fost făcută de către [[Utilizator:$3|$3]] ([[Discuţie utilizator:$3|Discuţie]]).",
#   only shown if there is an edit comment
"editcomment" => "Comentariul de editare a fost: \"<i>$1</i>\".",
"revertpage"	=> "Revenit la ultima editare de către $1",
"protectlogpage" => "Jurnal_protecţii",
"protectlogtext" => "Dedesubt găsiţi lista de blocări/deblocări ale paginilor.
Vezi [[$wgMetaNamespace:Pagină protejată]] pentru mai multe informaţii.",
"historywarning" => "Atenţie! Pagina pe care o ştergeţi are istorie: ",
"protectedarticle" => "protejat [[:$1]]",
"unprotectedarticle" => "deprotejat [[:$1]]",

# Undelete
"undelete" => "Recuperează pagina ştearsă",
"undeletepage" => "Vizualizează şi recuperează pagini şterse",
"undeletepagetext" => "Următoarele pagini au fost şterse dar încă se află în
arhivă şi pot fi recuperate. Reţineţi că arhiva se poate şterge din timp în timp.",
"undeletearticle" => "Recuperează articol şters",
"undeleterevisions" => "$1 versiuni arhivate",
"undeletehistory" => "Dacă recuperaţi pagina, toate versiunile asociate
vor fi adăugate retroactiv în istorie. Dacă o pagină nouă cu acelaşi nume
a fost creată de la momentul ştergerii acesteia, versiunile recuperate
vor apărea în istoria paginii, iar versiunea curentă a paginii nu va
fi înlocuită automat de către versiunea recuperată.",
"undeleterevision" => "Versiunea ştearsă la $1",
"undeletebtn" => "Recuperează!",
"undeletedarticle" => "\"$1\" a fost recuperat",
"undeletedtext"   => "Articolul [[$1]] a fost recuperat.
Vezi [[Wikipedia:Raport_ştergeri]] pentru o listă a ştergerilor şi recuperărilor recente.",

# Contributions
#
"contributions"	=> "Contribuţii ale utilizatorului",
"mycontris" => "Contribuţiile mele",
"contribsub"	=> "Pentru $1",
"nocontribs"	=> "Nu a fost găsită nici o modificare să satisfacă acest criteriu.",
"ucnote"		=> "Găsiţi dedesubt ultimele <b>$1</b> modificări ale utilizatorului din ultimele <b>$2</b> zile.",
"uclinks"		=> "Vezi ultimele $1 modificări; vezi ultimele $2 zile.",
"uctop"		=> " (sus)" ,

# What links here
#
"whatlinkshere"	=> "Ce se leagă aici",
"notargettitle" => "Lipsă ţintă",
"notargettext"	=> "Nu aţi specificat nici un pagină sau utilizator ţintă pentru care să se efectueze această funcţie.",
"linklistsub"	=> "(Lista de legături)",
"linkshere"		=> "Următoarele pagini conţin legături către aceasta:",
"nolinkshere"	=> "Nici o pagină nu se leagă aici.",
"isredirect"	=> "pagină de redirectare",

# Block/unblock IP
#
"blockip"		=> "Blocheză adresa IP",
"blockiptext"	=> "Folosiţi chestionarul de mai jos pentru a bloca
la scriere o adresă IP. Această funţie trebuie folosită numai pentru
a preveni vandalismul conform [[Wikipedia:Politica|politicii Wikipedia]].
Includeţi un motiv specific mai jos (de exemplu citând paginile care
au fost vandalizate de acest utilizator).",
"ipaddress"		=> "Adresa IP",
"ipbreason"		=> "Motiv",
"ipbsubmit"		=> "Blochează această adresă",
"badipaddress"	=> "Adresa IP este invalidă.",
"noblockreason" => "Trebuie să includeţi un motiv pentru blocare.",
"blockipsuccesssub" => "Utilizatorul a fost blocat",
"blockipsuccesstext" => "Adresa IP \"$1\" a fost blocată.
<br>Vezi [[Special:Ipblocklist|lista de adrese IP blocate]] pentru a revizui adresele blocate.",
"unblockip"		=> "Deblochează adresă IP",
"unblockiptext"	=> "Folosiţi chestionarul de mai jos pentru a restaura
drepturile de scriere pentru o adresă IP blocată anterior..",
"ipusubmit"		=> "Deblochează adresa",
"ipusuccess"	=> "Adresa IP \"$1\" a fost deblocată",
"ipblocklist"	=> "Lista de adrese IP blocate",
"blocklistline"	=> "$1, $2 a blocat $3",
"blocklink"		=> "blochează",
"unblocklink"	=> "deblochează",
"contribslink"	=> "contribuţii",
"autoblocker"	=> "Autoblocat fiindcă folosiţi aceeaşi [[adresă IP]] ca şi \"$1\". Motivul este \"$2\".",
"blocklogpage"	=> "Jurnal_blocări",
"blocklogentry"	=> 'blocat "$1"',
"blocklogtext"	=> "Acesta este un jurnal al acţiunilor de blocare şi deblocare.
[[Adresă IP|Adresele IP]] blocate automat nu sunt afişate.
Vedeţi [[Special:Ipblocklist|Lista de adrese blocate]] pentru o listă explicită a adreselor blocate în acest moment.",
"unblocklogentry"	=> 'deblocat "$1"',

# Developer tools
#
"lockdb"		=> "Blochează baza de date",
"unlockdb"		=> "Deblochează baza de date",
"lockdbtext"	=> "Blocarea bazei de date va împiedica pe toţi utilizatorii
să editeze pagini, să-şi schimbe preferinţele, să-şi editeze listele de
pagini urmărite şi orice alte operaţiuni care ar necesita schimări
în baza de date.
Vă rugăm să confirmaţi că intenţionaţi acest lucru şi faptul că veţi debloca
baza de date atunci când veţi încheia operaţiunile de întreţinere.",
"unlockdbtext"	=> "Deblocarea bazei de date va permite tuturor utilizatorilor
să editeze pagini, să-şi schimbe preferinţele, să-şi editeze listele de
pagini urmărite şi orice alte operaţiuni care ar necesita schimări
în baza de date.
Vă rugăm să confirmaţi că intenţionaţi acest lucru.",
"lockconfirm"	=> "Da, chiar vreau să blochez baza de date.",
"unlockconfirm"	=> "Da, chiar vreau să deblochez baza de date.",
"lockbtn"		=> "Blochează naza de date",
"unlockbtn"		=> "Deblochează baza de date",
"locknoconfirm" => "Nu aţi confirmat căsuţa de confirmare.",
"lockdbsuccesssub" => "Baza de date a fost blocată",
"unlockdbsuccesssub" => "Baza de date a fost deblocată",
"lockdbsuccesstext" => "Baza de date Wikipedia a fost blocată la scriere.
<br>Nu uitaţi să o deblocaţi după ce terminaţi operaţiunile administrative pentru care aţi blocat-o.",
"unlockdbsuccesstext" => "Baza de date Wikipedia a fost deblocată.",

# SQL query
#
"asksql"		=> "Query SQL",
"asksqltext"	=> "Folosiţi chestionarul de mai jos pentru a efectua un query direct către baza de date Wikipedia (MySQL).
Folosiţi apostrofuri ('în felul acesta') pentru a delimita şiruri de text.
Această funcţionalitate poate solicita în mod deosebit server-ul,
aşa că vă rugăm să nu o folosiţi în exces.",
"sqlislogged"	=> "Vă rugăm reţineţi că toate query-urile sunt reţinute în server (logged).",
"sqlquery"		=> "Introduceţi query",
"querybtn"		=> "Trimiteţi query",
"selectonly"	=> "Alte query-uri în afară de \"SELECT\" sunt accesibile numai pentru dezvoltatorii Wikipedia.",
"querysuccessful" => "Query efectuat",

# Move page
#
"movepage"		=> "Mută pagina",
"movepagetext"	=> "Puteţi folosi formularul de mai jos pentru a redenumi
o pagină, mutându-i toată istoria sub noul nume.
Pagina veche va deveni o pagină de redirectare către pagina nouă.
Legăturile către pagina veche nu vor fi redirectate către cea nouă;
aveţi grijă să [[Special:Maintenance|verificaţi]] dacă nu există redirectări duble sau invalide.

Vă rugăm să reţineţi că Dvs. sunteţi responsabil(ă) pentru a face legăturile vechi să rămână valide.

Reţineţi că pagina '''nu va fi mutată''' dacă există deja o
pagină cu noul titlu, afară de cazul că este complet goală sau este
o redirectare şi în plus nu are nici o istorie de editare.
Cu alte cuvinte, veţi putea muta înapoi o pagină pe care aţi mutat-o
greşit, dar nu veţi putea suprascrie o pagină validă existentă prin
mutarea alteia.

<b>ATENŢIE!</b>
Aceasta poate fi o schimbare drastică şi neaşteptată pentru o pagină populară;
vă rugăm să vă asiguraţi că înţelegeţi toate consecinţele înainte de a continua.",
"movepagetalktext" => "Pagina asociată de discuţii, dacă există, va fi mutată
automat odată cu aceasta '''afară de cazul că''':
* Mutaţi pagina în altă secţiune a Wikipedia
* Există deja o pagină de discuţii cu conţinut (care nu este goală), sau
* Deifaţi căsuţa de mai jos.

În oricare din cazurile de mai sus va trebui să mutaţi sau să unificaţi
manual paginile de discuţii, dacă doriţi acest lucru.",
"movearticle"	=> "Mută pagina",
"movenologin"	=> "Nu sunteţi autentificat",
"movenologintext" => "Trebuie să fiţi un utilizator înregistrat şi să vă <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">autentificaţi</a>
pentru a muta o pagină.",
"newtitle"		=> "Titlul nou",
"movepagebtn"	=> "Mută pagina",
"pagemovedsub"	=> "Pagina a fost mutată",
"pagemovedtext" => "Pagina \"[[$1]]\" a fost mutată la \"[[$2]]\".",
"articleexists" => "O pagină cu acelaşi nume există deja,
sau numele pe care l-aţi ales este invalid. Vă rugăm să alegeţi un alt nume.",
"talkexists"	=> "Pagina în sine a fost mutată, dar pagina de discuţii
nu a putut fi mutată deoarece deja există o alta cu acelaşi nume. Vă rugăm
să unificaţi manual cele două pagini de discuţii.",
"movedto"		=> "mutată la",
"movetalk"		=> "Mută şi pagina de \"discuţii\" dacă se poate.",
"talkpagemoved" => "Şi pagina de discuţii asociată a fost mutată.",
"talkpagenotmoved" => "Pagina asociată de discuţii <strong>nu</strong> a fost mutată.",
"export"		=> "Exportă pagini",
"exporttext"	=> "Puteţi exporta textul şi istoria unei pagini anume sau ale unui grup
de pagini în XML. Acesta poate fi apoi importat în alt Wiki care rulează software MediaWiki,
pate fi transformat sau păstrat pur şi simplu fiindcă doriţi Dvs. să-l păstraţi.",
"exportcuronly"	=> "Include numai versiunea curentă, nu şi toată istoria",

# Namespace 8 related

"allmessages"	=> "Toate_mesajele",
"allmessagestext"	=> "Aceasta este lista completă a mesajelor disponibile în domeniul \"MediaWiki:\"",
);

class LanguageRo extends LanguageUtf8 {

	function getBookstoreList () {
		global $wgBookstoreListRo ;
		return $wgBookstoreListRo ;
	}

	function getNamespaces() {
		global $wgNamespaceNamesRo;
		return $wgNamespaceNamesRo;
	}

	function getNsText( $index ) {
		global $wgNamespaceNamesRo;
		return $wgNamespaceNamesRo[$index];
	}

	function getNsIndex( $text ) {
		global $wgNamespaceNamesRo;

		foreach ( $wgNamespaceNamesRo as $i => $n ) {
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		return false;
	}

	function getQuickbarSettings() {
		global $wgQuickbarSettingsRo;

		return $wgQuickbarSettingsRo;
	}

	function getSkinNames() {
		global $wgSkinNamesRo;
		return $wgSkinNamesRo;
	}

	function getMathNames() {
		global $wgMathNamesRo;
		return $wgMathNamesRo;
	}

        function getDateFormats() {
                global $wgDateFormatsRo;
                return $wgDateFormatsRo;
        }

	function getUserToggles() {
		global $wgUserTogglesRo;
		return $wgUserTogglesRo;
	}

	function getMonthName( $key )
	{
		global $wgMonthNamesRo;
		return $wgMonthNamesRo[$key-1];
	}

	function getMonthAbbreviation( $key )
	{
		global $wgMonthAbbreviationsRo;
		return $wgMonthAbbreviationsRo[$key-1];
	}

	function getWeekdayName( $key )
	{
		global $wgWeekdayNamesRo;
		return $wgWeekdayNamesRo[$key-1];
	}

	function timeanddate( $ts, $adj = false )
	{
		return $this->date( $ts, $adj ) . " " . $this->time( $ts, $adj );
	}

	function getValidSpecialPages()
	{
		global $wgValidSpecialPagesRo;
		return $wgValidSpecialPagesRo;
	}

	function getSysopSpecialPages()
	{
		global $wgSysopSpecialPagesRo;
		return $wgSysopSpecialPagesRo;
	}

	function getDeveloperSpecialPages()
	{
		global $wgDeveloperSpecialPagesRo;
		return $wgDeveloperSpecialPagesRo;
	}

	function getMessage( $key )
	{
		global $wgAllMessagesRo;
		if($wgAllMessagesRo[$key])
			return $wgAllMessagesRo[$key];
		else
			return Language::getMessage( $key );
	}
	
	function fallback8bitEncoding() {
		return "iso8859-2";
	}

	function getMagicWords() 
	{
		global $wgMagicWordsRo;
		return $wgMagicWordsRo;
	}
}

?>

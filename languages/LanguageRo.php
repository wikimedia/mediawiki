<?

# NOTE: To turn off "Current Events" in the sidebar,
# set "currentevents" => "-"

# The names of the namespaces can be set here, but the numbers
# are magical, so don't change or move them!  The Namespace class
# encapsulates some of the magic-ness.
#
/* private */ $wgNamespaceNamesRo = array(
	-1	=> "Special",
	0	=> "",
	1	=> "Discutie",
	2	=> "Utilizator",
	3	=> "Discutie_Utilizator",
	4	=> "Wikipedia",
	5	=> "Discutie_Wikipedia",
	6	=> "Imagine",
	7	=> "Discutie_Imagine"
);

/* private */ $wgQuickbarSettingsRo = array(
	"Fara", "Fixa, în stânga", "Fixa, în dreapta", "Libera"
);

/* private */ $wgSkinNamesRo = array(
	"Normala", "Nostalgie", "Cologne Blue"
);

/* private */ $wgMathNamesRo = array(
	"Întotdeauna PNG",
	"HTML daca e foarte simplu sau PNG altfel",
	"HTML daca e posibil sau PNG altfel",
	"Lasa-l TeX (pentru browsere text)",
	"Recomandat pentru browsere moderne"
);

/* private */ $wgUserTogglesRo = array(
	"hover"		=> "Arata info deasupra legaturilor",
	"underline" => "Subliniaza legaturile",
	"highlightbroken" => "Formateaza legaturile inexistente <a href=\"\" class=\"new\">în felul acesta</a> (alternativa este asa<a href=\"\" class=\"internal\">?</a>).",
	"justify"	=> "Aliniaza paragrafele",
	"hideminor" => "Ascunde schimbarile minore în pagina de schimbari recente",
	"usenewrc" => "Îmbunatateste structura paginii de schimbari minore (nu merge în toate browserele)",
	"numberheadings" => "Auto-numeroteaza titlurile",
	"rememberpassword" => "Pastreaza parola între sesiuni",
	"editwidth" => "Latime maxima pentru caseta de editare",
	"editondblclick" => "Editeaza paginile cu dublu clic (JavaScript)",
	"watchdefault" => "Urmareste articolele pe care le creezi sau le editezi",
	"minordefault" => "Marcheaza implicit toate editarile ca minore",
	"previewontop" => "Arata pagina dupa caseta de editare, nu înainte"
	
);

/* private */ $wgBookstoreListRo = array(
	"AddALL" => "http://www.addall.com/New/Partner.cgi?query=$1&type=ISBN",
	"PriceSCAN" => "http://www.pricescan.com/books/bookDetail.asp?isbn=$1",
	"Barnes & Noble" => "http://shop.barnesandnoble.com/bookSearch/isbnInquiry.asp?isbn=$1",
	"Amazon.com" => "http://www.amazon.com/exec/obidos/ISBN=$1"
);

/* private */ $wgLanguageNamesRo = array(
	"aa"    => "Afar",
	"ab"    => "Abkhazian",
	"af"	=> "Afrikaans",
	"am"	=> "Amharic",
	"ar" => "Araba",
	"as"	=> "Assamese",
	"ay"	=> "Aymara",
	"az"	=> "Azerbaijana",
	"ba"	=> "Bashkira",
	"be" => "Bielorusa",
	"bh"	=> "Bihara",
	"bi"	=> "Bislama",
	"bn"	=> "Bengali",
	"bo"	=> "Tibetan",
	"br" => "Brezhoneg",
	"ca" => "Catalana",
	"ch" => "Chamoru",
	"co"	=> "Corsicana",
	"cs" => "Ceha",
	"cy" => "Cymraeg",
	"da" => "Daneza", # Note two different subdomains.
	"dk" => "Daneza", # 'da' is correct for the language.
	"de" => "Germana",
	"dz"	=> "Bhutani",
	"el" => "Greaca",
	"en"	=> "Engleza",
	"eo"	=> "Esperanto",
	"es" => "Spaniola",
	"et" => "Eesti",
	"eu" => "Euskara",
	"fa" => "Farsi",
	"fi" => "Suomi",
	"fj"	=> "Fijian",
	"fo"	=> "Faeroeza",
	"fr" => "Franceza",
	"fy" => "Frysk",
	"ga" => "Gaelige",
	"gl"	=> "Galician",
	"gn"	=> "Guarani",
	"gu" => "Gujarati",
	"ha"	=> "Hausa",
	"he" => "Ebraica",
	"hi" => "Hindusa",
	"hr" => "Hrvatski",
	"hu" => "Maghiara",
	"hy"	=> "Armeniana",
	"ia"	=> "Interlingua",
	"id"	=> "Indonesiana",
	"ik"	=> "Inupiak",
	"is" => "Islandeza",
	"it" => "Italiana",
	"iu"	=> "Inuktitut",
	"ja" => "Nihongo",
	"jv"	=> "Javaneza",
	"ka" => "Kartuli",
	"kk"	=> "Cazaca",
	"kl"	=> "Groenlandeza",
	"km"	=> "Cambogiana",
	"kn"	=> "&#3221;&#3240;&#3277;&#3240;&#3233; (Kannada)",
	"ko" => "&#54620;&#44397;&#50612; (Hangukeo)",
	"ks"	=> "Kashmiri",
	"kw" => "Kernewek",
	"ky"	=> "Kirghiza",
	"la" => "Latina",
	"ln"	=> "Lingala",
	"lo"	=> "Laotiana",
	"lt" => "Lietuvi&#371;",
	"lv"	=> "Latvian",
	"mg" => "Malagasy",
	"mi"	=> "Maora",
	"mk"	=> "Macedoneana",
	"ml"	=> "Malaeziana",
	"mn"	=> "Mongola",
	"mo"	=> "Moldoveneste",
	"mr"	=> "Marathi",
	"ms" => "Bahasa Melayu",
	"my"	=> "Burmese",
	"na"	=> "Nauru",
	"ne" => "&#2344;&#2375;&#2346;&#2366;&#2354;&#2368; (Nepali)",
	"nl" => "Olandeza",
	"no" => "Norsk",
	"oc"	=> "Occitan",
	"om"	=> "Oromo",
	"or"	=> "Oriya",
	"pa"	=> "Punjabi",
	"pl" => "Poloneza",
	"ps"	=> "Pashto",
	"pt" => "Portugheza",
	"qu"	=> "Quechua",
	"rm"	=> "Rhaeto-Romance",
	"rn"	=> "Kirundi",
	"ro" => "Rom&#226;n&#259;",
	"ru" => "Rusa",
	"rw"	=> "Kinyarwanda",
	"sa" => "Sanscrita",
	"sd"	=> "Sindhi",
	"sg"	=> "Sangro",
	"sh"	=> "Serbocroatian",
	"si"	=> "Sinhalese",
	"simple" => "Simple English",
	"sk"	=> "Slovak",
	"sl"	=> "Slovensko",
	"sm"	=> "Samoan",
	"sn"	=> "Shona",
	"so" => "Soomaali",
	"sq" => "Shqiptare",
	"sr" => "Srpski",
	"ss"	=> "Siswati",
	"st"	=> "Sesotho",
	"su"	=> "Sudanese",
	"sv" => "Svenska",
	"sw" => "Kiswahili",
	"ta"	=> "&#2980;&#2990;&#3007;&#2996;&#3021; (Tamil)",
	"te"	=> "&#3108;&#3142;&#3122;&#3137;&#3095;&#3137; (Telugu)",
	"tg"	=> "Tajik",
	"th"	=> "Thai",
	"ti"	=> "Tigrinya",
	"tk"	=> "Turkmen",
	"tl"	=> "Tagalog",
	"tn"	=> "Setswana",
	"to"	=> "Tonga",
	"tr" => "T&#252;rk&#231;e",
	"ts"	=> "Tsonga",
	"tt"	=> "Tatar",
	"tw"	=> "Twi",
	"ug"	=> "Uighur",
	"uk" => "&#1059;&#1082;&#1088;&#1072;&#1111;&#1085;&#1089;&#1100;&#1082;&#1072; (Ucraineana)",

	"ur"	=> "Urdu",
	"uz"	=> "Uzbek",
	"vi"	=> "Vietnameza",
	"vo" => "Volap&#252;k",
	"wo"	=> "Wolof",
	"xh" => "isiXhosa",
	"yi"	=> "Yiddish",
	"yo"	=> "Yoruba",
	"za"	=> "Zhuang",
	"zh" => "&#20013;&#25991; (Zhongwen)",
	"zu"	=> "Zulu"
);

/* private */ $wgWeekdayNamesRo = array(
	"Duminica", "Luni", "Marti", "Miercuri", "Joi",
	"Vineri", "Sâmbata"
);

/* private */ $wgMonthNamesRo = array(
	"Ianuarie", "Februarie", "Martie", "Aprilie", "Mai", "Iunie",
	"Iulie", "August", "Septembrie", "Octombrie", "Noiembrie",
	"Decembrie"
);

/* private */ $wgMonthAbbreviationsRo = array(
	"Ian", "Feb", "Mar", "Apr", "Mai", "Iun", "Iul", "Aug",
	"Sep", "Oct", "Noi", "Dec"
);

# All special pages have to be listed here: a description of ""
# will make them not show up on the "Special Pages" page, which
# is the right thing for some of them (such as the "targeted" ones).
#
/* private */ $wgValidSpecialPagesRo = array(
	"Userlogin"		=> "",
	"Userlogout"	=> "",
	"Preferences"	=> "Preferintele mele",
	"Watchlist"		=> "Articolele pe care le urmaresc",
	"Recentchanges" => "Pagini actualizate recent",
	"Upload"		=> "Trimite imagini",
	"Imagelist"		=> "Lista imaginilor",
	"Listusers"		=> "Utilizatori înreistrati",
	"Statistics"	=> "Statistici pentru sit",
	"Randompage"	=> "Articol aleator",

	"Lonelypages"	=> "Articole orfane",
	"Unusedimages"	=> "Imagini orfane",
	"Popularpages"	=> "Articole populare",
	"Wantedpages"	=> "Cele mai dorite articole",
	"Shortpages"	=> "Articole scurte",
	"Longpages"		=> "Articole lungi",
	"Newpages"		=> "Articole noi",
	"Allpages"		=> "Toate paginile dupa titlu",

	"Ipblocklist"	=> "Adrese IP blocate",
	"Maintenance" => "Pagina de întretinere",
	"Specialpages"  => "Pagini speciale",
	"Contributions" => "Contributii",
	"Emailuser"		=> "Trimite e-mail utilizatorului",
	"Whatlinkshere" => "Ce pagini se leaga aici",
	"Recentchangeslinked" => "",
	"Movepage"		=> "Muta pagina",
	"Booksources"	=> "Surse externe de carti"
);

/* private */ $wgSysopSpecialPagesRo = array(
	"Blockip"		=> "Blocheaza adresa IP",
	"Asksql"		=> "Efectueaza un query în baza de date",
	"Undelete"		=> "Afiseaza si restaureaza pagini sterse"
);

/* private */ $wgDeveloperSpecialPagesRo = array(
	"Lockdb"		=> "Blocheaza baza de date la scriere",
	"Unlockdb"		=> "Deblocheaza baza de date",
	"Debug"			=> "Informatie pentru debugging"
);

/* private */ $wgAllMessagesRo = array(

# Bits of text used by many pages:
#
"linktrail"		=> "/^([a-z]+)(.*)\$/sD",
"mainpage"		=> "Pagina principala",
"about"			=> "Despre",
"aboutwikipedia" => "Despre Wikipedia",
"aboutpage"		=> "Wikipedia:Despre",
"help"			=> "Ajutor",
"helppage"		=> "Wikipedia:Ajutor",
"wikititlesuffix" => "Wikipedia",
"bugreports"	=> "Rapoarte despre probleme",
"bugreportspage" => "Wikipedia:Rapoarte_probleme",
"faq"			=> "Întrebari frecvente",
"faqpage"		=> "Wikipedia:Întrebari_frecvente",
"edithelp"		=> "Ajutor pentru editare",
"edithelppage"	=> "Wikipedia:Cum_sa_editezi_o_pagina",
"cancel"		=> "Renunta",
"qbfind"		=> "Gaseste",
"qbbrowse"		=> "Rasfoieste",
"qbedit"		=> "Editeaza",
"qbpageoptions" => "Optiuni ale paginii",
"qbpageinfo"	=> "Informatii ale paginii",
"qbmyoptions"	=> "Optiunile mele",
"mypage"		=> "Pagina mea",
"mytalk"		=> "Discutiile mele",
"currentevents" => "Evenimente curente",
"errorpagetitle" => "Eroare",
"returnto"		=> "Întoarce-te la $1.",
"fromwikipedia"	=> "De la Wikipedia, enciclopedia libera.",
"whatlinkshere"	=> "Pagini care se leaga aici",
"help"			=> "Ajutor",
"search"		=> "Cauta",
"go"		=> "Du-te",
"history"		=> "Versiuni mai vechi",
"printableversion" => "Versiune pentru tiparire",
"editthispage"	=> "Editeaza aceasta pagina",
"deletethispage" => "Sterge aceasta pagina",
"protectthispage" => "Protejeaza aceasta pagina",
"unprotectthispage" => "Deprotejeaza aceasta pagina",
"newpage" => "Pagina noua",
"talkpage"		=> "Discuta aceasta pagina",
"articlepage"	=> "Vezi articolul",
"subjectpage"	=> "Vezi subiectul", # For compatibility
"userpage" => "Vezi pagina utilizatorului",
"wikipediapage" => "Vezi pagina meta",
"imagepage" => 	"Vezi pagina imaginii",
"viewtalkpage" => "Vezi discutia",
"otherlanguages" => "În alte limbi",
"redirectedfrom" => "(Redirectat de la $1)",
"lastmodified"	=> "Ultima modificare $1.",
"viewcount"		=> "Aceasta pagina a fost modificata de $1 ori.",
"gnunote" => "Tot textul este disponibil în termenii licentei <a class=internal href='/wiki/GNU_FDL'>GNU Free Documentation License</a>.",
"printsubtitle" => "(De la http://ro.wikipedia.org)",
"protectedpage" => "Pagina protejata",
"administrators" => "Wikipedia:Administratori",
"sysoptitle"	=> "Aveti nevoie de acces ca operator",
"sysoptext"		=> "Actiunea pe care ati încercat-o necesita drepturi de operator.
Vezi $1.",
"developertitle" => "Aveti nevoie de acces ca dezvoltator",
"developertext"	=> "Actiunea pe care ati încercat-o necesita drepturi de dezvoltator.
Vezi $1.",
"nbytes"		=> "$1 octeti",
"go"			=> "Du-te",
"ok"			=> "OK",
"sitetitle"		=> "Wikipedia",
"sitesubtitle"	=> "Enciclopedia libera",
"retrievedfrom" => "Adus de la \"$1\"",
"newmessages" => "Aveti $1.",
"newmessageslink" => "mesaje noi",

# Main script and global functions
#
"nosuchaction"	=> "Aceasta actiune nu exista",
"nosuchactiontext" => "Actiunea specificata în adresa nu este recunoscuta de Wikipedia.",
"nosuchspecialpage" => "Aceasta pagina speciala nu exista",
"nospecialpagetext" => "Ati cerut o pagina speciala care nu este recunoscuta de Wikipedia.",

# General errors
#
"error"			=> "Eroare",
"databaseerror" => "Eroare la baza de date",
"dberrortext"	=> "A aparut o eroare în executia query-ului.
Aceasta se poate datora unui query ilegal (vezi $5),
sau poate indica o problema în program.
Ultimul query încercat a fost:
<blockquote><tt>$1</tt></blockquote>
în cadrul functiei \"<tt>$2</tt>\".
MySQL a returnat eroarea \"<tt>$3: $4</tt>\".",
"noconnect"		=> "Nu s-a putut conecta baza de date pe $1",
"nodb"			=> "Nu c-a putut selecta baza de date $1",
"readonly"		=> "Baza de date este blocata la scriere",
"enterlockreason" => "Introduceti motivul pentru blocare, incluzând o estimare a termenului când veti debloca baza de date",
"readonlytext"	=> "Baza de date Wikipedia este momentan blocata la scriere, probabil pentru o operatiune de rutina, dupa care va fi deblocata si se va reveni la starea normala.
Administratorul care a blocat-o a oferit aceasta explicatie:
<p>$1",
"missingarticle" => "Textul \"$1\" nu a putut fi gasit în baza de date, asa cum ar fi trebuit. Aceasta nu este o problema legata de programul care gestioneaza baza de date, ci probabil o problema in programul care administreaza Wikipedia. Va rugam sa raportati aceasta problema unui administrator, incluzând si adresa acestei pagini.",
"internalerror" => "Eroare interna",
"filecopyerror" => "Fisierul \"$1\" nu a putut fi copiat la \"$2\".",
"filerenameerror" => "Fisierul \"$1\" nu a putut fi mutat la \"$2\".",
"filedeleteerror" => "Fisierul \"$1\" nu a putut fi sters.",
"filenotfound"	=> "Fisierul \"$1\" nu a putut fi gasit.",
"unexpected"	=> "Valoare neasteptata: \"$1\"=\"$2\".",
"formerror"		=> "Eroare: datele nu au putut fi trimise",
"badarticleerror" => "Aceasta actiune nu poate fi efectuata pe aceasta pagina.",
"cannotdelete"	=> "Nu s-a putut sterge pagina sau imaginea (poate a sters-o altcineva deja?)",
"badtitle"		=> "Titlu invalid",
"badtitletext"	=> "Titlul cautat a fost invalid, gol sau o legatura invalida inter-linguala sau inter-wiki.",
"perfdisabled" => "Ne pare rau! Aceasta functionalitate a fost dezactivata temporar în timpul orelor de vârf din motive de performanta. Va rugam sa reveniti la alta ora si încercati din nou.", // Didn't provide any off-peak hours because they may differ on the Romanian Wikipedia.

# Login and logout pages
#
"logouttitle"	=> "Închieiere sesiune",
"logouttext"	=> "Sesiunea Dvs. în Wikipedia a fost închisa.
Puteti continua sa folositi Wikipedia anonim, sau puteti sa va reautentificati ca acelasi sau ca alt utilizator.\n",
"welcomecreation" => "<h2>Bun venit, $1!</h2><p>A fost creat un cont pentru Dvs.
Nu uitati sa va personalizati preferintele în Wikipedia.",
"loginpagetitle" => "Autentificare utilizator",
"yourname"		=> "Numele de utilizator",
"yourpassword"	=> "Parola",
"yourpasswordagain" => "Repetati parola",
"newusersonly"	=> " (doar pentru utilizatori noi)",
"remembermypassword" => "Retine-mi parola între sesiuni.",
"loginproblem"	=> "<b>A fost o problema cu autentificarea Dvs.</b><br>Încercati din nou!",
"alreadyloggedin" => "<font color=red><b>Sunteti deja autentificat ca $1!</b></font><br>\n",
"areyounew"		=> "Daca sunteti nou în Wikipedia si doriti un cont de utilizator, introduceti un nume de utilizator, apoi scrieti-va parola în casuta urmatoare, si repetati-o în a treia pentru confirmare.
Adresa de mail este optionala; daca va pierdeti parola o puteti cere la adresa de mail pe care o introduceti.<br>\n",

"login"			=> "Autentificare",
"userlogin"		=> "Autentificare",
"logout"		=> "Închide sesiunea",
"userlogout"	=> "Încehide sesiunea",
"createaccount"	=> "Creeaza cont nou",
"badretype"		=> "Parolele pe care le-ati introdus difera.",
"userexists"	=> "Numele de utilizator pe care l-ati introdus exista deja. Încercati cu un alt nume.",
"youremail"		=> "Adresa de mail",
"yournick"		=> "Versiune scurta a numelui, pentru semnaturi",
"emailforlost"	=> "Daca va pierdeti parola, puteti cere sa vi se trimita una noua la adresa de mail.",
"loginerror"	=> "Eroare de autentificare",
"noname"		=> "Numele de utilizator pe care l-ati specificat este invalid.",
"loginsuccesstitle" => "Autentificare reusita",
"loginsuccess"	=> "Ati fost autentificat în Wikipedia ca \"$1\".",
"nosuchuser"	=> "Nu exista nici un utilizator cu numele \"$1\".
Verificati daca ati scris corect sau folositi aceasta pagina pentru a crea un nou utilizator.",
"wrongpassword"	=> "Parola pe care ati introdus-o este gresita. Va rugam încercati din nou.",
"mailmypassword" => "Trimiteti-mi parola pe mail!",
"passwordremindertitle" => "Amintirea parolei pe Wikipedia",
"passwordremindertext" => "Cineva (probabil Dvs., de la adresa $1)
a cerut sa vi se trimita o noua parola pentru Wikipedia.
Parola pentru utilizatorul \"$2\" este acum \"$3\".
Este recomandat sa intrati pe Wikipedia si sa va schimbati parola cât mai curând.",
"noemail"		=> "Nu este nici o adresa de mail înregistrata pentru utilizatorul \"$1\".",
"passwordsent"	=> "O noua parola a fost trimisa la adresa de mail a utilizatorului \"$1\".
Va rugam sa va autentificati pe Wikipedia dupa ce o primiti.",

# Edit pages
#
"summary"		=> "Sumar",
"minoredit"		=> "Aceasta este o editare minora",
"watchthis"		=> "Urmareste aceasta pagina",
"savearticle"	=> "Salveaza pagina",
"preview"		=> "Pre-vizualizare",
"showpreview"	=> "Arata pre-vizualizarea",
"blockedtitle"	=> "Utilizatorul este blocat",
"blockedtext"	=> "Utilizatorul sau parola Dvs. au fost blocate de $1.
Motivul oferit pentru blocare a fost:<br>''$2''<p>Puteti contacta pe $1 sau pe unul dintre ceilalti
[[Wikipedia:administratori|administratori]] pentru a discuta aceasta blocare.",
"newarticle"	=> "(Nou)",
"newarticletext" =>
"Ati ajuns la o pagina care nu exista.
Pentru a o crea, începeti sa scrieti în caseta de mai jos
(vezi [[Wikipedia:Ajutor|pagina de ajutor]] pentru mai multe informatii).
Daca ati ajuns aici din greseala, întoarceti-va folosind controalele browser-ului Dvs.",
"anontalkpagetext" => "---- ''Aceasta este pagina de discutii pentru un utilizator care nu si-a creat un cont înca, sau care nu s-a autentificat. De aceea trebuie sa folosim [[adresa IP]] pentru a identifica aceasta persoana. O adresa IP poate fi împartita între mai multi utilizatori. Daca sunteti un astfel de utilizator si credeti ca vi se adreseaza mesaje irelevante, va rugam sa [[Special:Userlogin|va creati un cont sau sa va autentificati]] pentru a evita confuzii cu alti utilizatori anonimi în viitor.'' ",
"noarticletext" => "(Nu exista text în aceasta pagina)",
"updated"		=> "(Actualizat)",
"note"			=> "<strong>Nota:</strong> ",
"previewnote"	=> "Retineti ca aceasta este o pre-vizualizare si articolul înca nu este salvat!",
"previewconflict" => "Aceasta pre-vizualizare reflecta textul din caseta de sus, respectiv felul în care va arata articolul daca alegeti sa salvati acum.",
"editing"		=> "Editare $1",
"editconflict"	=> "Conflict de editare: $1",
"explainconflict" => "Altcineva a modificat aceasta pagina de când ati început s-o editati.
Caseta de text de sus contine pagina asa cum este ea acum (dupa editarea celeilalte persoane).
Pagina cu modificarile Dvs. (asa cum ati încercat s-o salvati) se afla în caseta de jos.
Va trebui sa editati manual caseta de sus pentru a reflecta modificarile pe care tocmai le-ati facut în cea de jos.
<b>Numai</b> textul din caseta de sus va fi salvat atunci când veti apasa pe  \"Salveaza pagina\".\n<p>",
"yourtext"		=> "Textul Dvs.",
"storedversion" => "Versiunea curenta",
"editingold"	=> "<strong>ATENTIE! Editati o varianta mai veche a acestei pagini! Orice modificari care s-au facut de la aceasta versiune si pâna la cea curenta se vor pierde!</strong>\n",
"yourdiff"		=> "Diferente",
"copyrightwarning" => "Retineti ca toate contributiile la Wikipedia sunt considerate ca respectând licenta GNU Free Documentation License
(vezi $1 pentru detalii).
Daca nu doriti ca ceea ce scrieti sa fie editat fara mila si redistribuit în voie, atunci nu trimiteti materialele respective aici.<br>
De asemenea, trimitând aceste materiale aici va angajati ca le-ati scris Dvs. sau ca sunt copiate dintr-o sursa care permite includerea materialelor sub aceasta licenta.
<strong>NU TRIMITETI MATERIALE PROTEJATE DE DREPTURI DE AUTOR FARA PERMISIUNE!</strong>",
"longpagewarning" => "ATENTIE! Continutul acestei pagini are $1 KB; unele browsere au probleme la editarea paginilor în jur de 32 KB sau mai mari.
Va rugam sa luati în considerare posibilitatea de a împarti pagina în mai multe sectiuni.",

# History pages
#
"revhistory"	=> "Istoria versiunilor",
"nohistory"		=> "Nu exista istorie pentru aceasta pagina.",
"revnotfound"	=> "Versiunea nu a fost gasita",
"revnotfoundtext" => "Versiunea mai veche a paginii pe care ati cerut-o nu a fost gasita. Va rugam sa verificati legatura pe care ati folosit-o pentru a accesa aceasta pagina.\n",
"loadhist"		=> "Încarc istoria versiunilor",
"currentrev"	=> "Versiunea curenta",
"revisionasof"	=> "Versiunea de la data $1",
"cur"			=> "cur",
"next"			=> "urmatoarea",
"last"			=> "precedenta",
"orig"			=> "orig",
"histlegend"	=> "Legenda: (cur) = diferente fata de versiunea curenta,
(precedenta) = diferente fata de versiunea precedenta, M = editare minora",

# Diffs
#
"difference"	=> "(Diferenta dintre versiuni)",
"loadingrev"	=> "se încarca diferenta dintre versiuni",
"lineno"		=> "Linia $1:",
"editcurrent"	=> "Editarea versiunii curente a acestei pagini",

# Search results
#
"searchresults" => "Rezultatele cautarii",
"searchhelppage" => "Wikipedia:Searching",
"searchingwikipedia" => "Cautare în Wikipedia",
"searchresulttext" => "Pentru mai multe detalii despre cautarea în Wikipedia, vezi $1.",
"searchquery"	=> "Pentru cautarea \"$1\"",
"badquery"		=> "Cautare invalida",
"badquerytext"	=> "Cautarea Dvs. nu a putut fi procesata.
Asta se întâmpla probabil din cauza ca ati încercat sa cautati un cuvânt cu mai putin de trei litere.
E posibil si sa fi scris gresit o expresie sau un nume, cum ar fi \"Mircea cel cel Batrân\".
Va rugam sa încercati o alta cautare.",
"matchtotals"	=> "Cautarea \"$1\" a produs $2 rezultate în titluri de articole si $3 rezultate în texte de articole.",
"nogomatch" => "Nici o pagina cu acest titlu nu a fost gasita, încercati sa cautati textul si în pagini. ",
"titlematches"	=> "Rezultate în titluri de articole",
"notitlematches" => "Nici un rezultat în titlurile articolelor",
"textmatches"	=> "Rezultate în textele articolelor",
"notextmatches"	=> "Nici un rezultat în textele articolelor",
"prevn"			=> "anterioarele $1",
"nextn"			=> "urmatoarele $1",
"viewprevnext"	=> "Vezi ($1) ($2) ($3).",
"showingresults" => "Mai jos apar <b>$1</b> rezultate începând cu nr. <b>$2</b>.",
"nonefound"		=> "<strong>Nota</strong>: cautarile nereusite sunt în general datorate cautarii unor cuvinte prea comune care nu sunt indexate, sau cautarilor a mai multe cuvinte (numai articolele care contin ''toate'' cuvintele specificate apar ca rezultate).",
"powersearch" => "Cauta",
"powersearchtext" => "
Cauta în sectiunile:<br>
$1<br>
$2 Redirectionari&nbsp; Cautari dupa $3 $9",


# Preferences page
#
"preferences"	=> "Preferinte",
"prefsnologin" => "Neautentificat",
"prefsnologintext"	=> "Trebuie sa fiti <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">autentificat</a>
pentru a va putea salva preferintele.",
"prefslogintext" => "Sunteti autentificat ca \"$1\".
Numarul intern de identificare este $2 (nu trebuie sa-l retineti decât daca aveti probleme).",
"prefsreset"	=> "Preferintele au fost resetate.",
"qbsettings"	=> "Setari pentru quickbar", 
"changepassword" => "Schimba parola",
"skin"			=> "Aparenta",
"math"			=> "Apareta formule",
"math_failure"		=> "Nu s-a putut interpreta",
"math_unknown_error"	=> "eroare necunoscuta",
"math_unknown_function"	=> "functie necunoscuta ",
"math_lexing_error"	=> "eroare lexicala",
"math_syntax_error"	=> "eroare de sintaxa",
"saveprefs"		=> "Salveaza preferintele",
"resetprefs"	=> "Reseteaza preferintele",
"oldpassword"	=> "Parola veche",
"newpassword"	=> "Parola noua",
"retypenew"		=> "Repeta parola noua",
"textboxsize"	=> "Dimensiunile casetei de text",
"rows"			=> "Rânduri",
"columns"		=> "Coloane",
"searchresultshead" => "Setari de cautare",
"resultsperpage" => "Numarul de rezultate per pagina",
"contextlines"	=> "Numarul de linii per rezultat",
"contextchars"	=> "Numarul de caractere per linie",
"stubthreshold" => "Limita de caractere pentru un ciot",
"recentchangescount" => "Numarul de articole pentru schimbari recente",
"savedprefs"	=> "Preferintele Dvs. au fost salvate.",
"timezonetext"	=> "Introduceti numarul de ore diferenta între ora locala si ora serverului (UTC, timp universal - pentru România, cifra este 3).",
"localtime"	=> "Ora locala",
"timezoneoffset" => "Diferenta",
"emailflag"		=> "Dezactiveaza serviciul de e-mail de la alti utilizatori",

# Recent changes
#
"changes" => "schimbari",
"recentchanges" => "Schimbari recente",
"recentchangestext" => "Aceata pagina permite vizualizarea ultimelor modificari ale paginilor Wikipedia în româna.

[[Wikipedia:bun venit|Bun venit pe Wikipedia]]! Nu ezitati sa vizitati sectiunile de [[Wikipedia:întrebari frecvente|întrebari frecvente]], [[Wikipedia:politica|politica Wikipedia]] (în special [[Wikipedia:conventii pentru denumiri|conventii pentru denumiri]] si [[Wikipedia:punct de vedere neutru|punct de vedere neutru]]), si cele mai comune [[Wikipedia:greseli frecvente|greseli în Wikipedia]].

Este foarte important sa nu adaugati în Wikipedia materiale protejate de [[drepturi de autor]]. Problemele legale rezultate ar putea prejudicia în mod serios proiectul în întregime, asa ca va rugam insistent sa aveti grija sa nu faceti asta.",
"rcloaderr"		=> "Încarc ultimele modificari",
"rcnote"		=> "Dedesubt gasiti ultimele <strong>$1</strong> modificari din ultimele <strong>$2</strong> zile.",
"rcnotefrom"	=> "Dedesubt sunt modificarile de la <b>$2</b> (vizibile numai pâna la <b>$1</b>).",
"rclistfrom"	=> "Arata modificarile începând de la $1",
# "rclinks"		=> "Arata ultimele $1 modificari din ultimele $2 ore / ultimele $3 zile",
"rclinks"		=> "Arata ultimele $1 modificari din ultimele $2 zile.",
"rchide"		=> "în in $4 form; $1 minor edits; $2 secondary namespaces; $3 multiple edits.",
"diff"			=> "diferenta",
"hist"			=> "istorie",
"hide"			=> "ascunde",
"show"			=> "arata",
"tableform"		=> "tabel",
"listform"		=> "lista",
"nchanges"		=> "$1 modificari",
"minoreditletter" => "M",
"newpageletter" => "N",

# Upload
#
"upload"		=> "Trimite fisier",
"uploadbtn"		=> "Trimite fisier",
"uploadlink"	=> "Trimite imagine",
"reupload"		=> "Re-trimite",
"reuploaddesc"	=> "Întoarcere la formularul de trimitere.",
"uploadnologin" => "Nu sunteti autentificat",
"uploadnologintext"	=> "Trebuie sa foti <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">autentificat</a>
ca sa trimiteti fisiere.",
"uploadfile"	=> "Trimite fisier",
"uploaderror"	=> "Eroare la trimitere fisier",
"uploadtext"	=> "<strong>STOP!</strong> Înainte de a trimite un fisier aici,
va rugam sa cititi si sa respectati <a href=\"" .
wfLocalUrlE("Wikipedia:Politica_de_utilizare_a_imaginilor" ) . "\">politica de utilizare a imaginilor</a>.
<p>Pentru a vizualiza sau cauta imagini deja trimise, mergeti la <a href=\"" . wfLocalUrlE( "Special:Imagelist" ) .
"\">lista de imagini</a>.
Fisierele noi si cele sterse sunt contorizate pe paginile de <a href=\"" .
wfLocalUrlE( "Wikipedia:Upload_log" ) . "\">raport de trimiteri</a>.
<p>Folositi formularul de mai jos pentru a trimite imagini noi
pe care le veti putea folosi pentru a va ilustra articolele.
În majoritatea browserelor veti vedea un buton \"Browse...\"
care va va deschide fereastra standard dialog a sistemului Dvs. de operare
pentru alegerea de fisiere.
Când alegeti un fisier în acest fel, caseta de dialog se va completa cu calea locala catre acesta.
Este de asemenea necesar sa bifati casuta asociata textului
în care confirmati ca nu violati nici un drept de autor trimitând aceasta imagine.
În final, apasati pe butonul \"Trimite\" petru a trimite efectiv fisierul.
Aceasta operatiune poate dura, mai ales daca aveti o legatura lenta la Internet.
<p>Formatele preferate sunt JPEG pentru imagini fotografice,
PNG pentru desene si alte imagini cu contururi clare si OGG pentru fisiere de sunet.
Va rugam sa folositi nume explicite pentru fisiere ca sa evitati confuziile.
Pentru a include o imagine într-un articol, folositi o legatura de forma <b>[[image:fisier.jpg]]</b> sau <b>[[image:fisier.png|text alternativ]]</b>
sau <b>[[media:fisier.ogg]]</b> pentru fisiere de sunet.
<p>Va rugam sa retineti ca, la fel ca si în cazul celorlalte sectiuni din Wikipedia, alte persoane pot edita sau sterge fisierele pe care le trimiteti daca e în interesul enciclopediei, si vi se poate chiar bloca accesul la trimiterea de fisiere daca abuzati de sistem.",
"uploadlog"		=> "raport de trimitere fisiere",
"uploadlogpage" => "Raport de trimitere fisiere",
"uploadlogpagetext" => "Gasiti mai jos lista ultimelor fisiere trimise.
Toate datele/orele sunt afisate ca timp universal (UTC).
<ul>
</ul>
",
"filename"		=> "Nume fisier",
"filedesc"		=> "Sumar",
"affirmation"	=> "Afirm ca persoana care detine drepturile de autor asupra acestui fisier este de acord cu termenii licentei $1.",
"copyrightpage" => "Wikipedia:Drepturi_de_autor",
"copyrightpagename" => "Drepturi de autor în Wikipedia",
"uploadedfiles"	=> "Fisiere trimise",
"noaffirmation" => "Trebuie sa afirmati ca fisierul pe care în trimiteti nu violeaza drepturi de autor (trebuie sa bifati casuta aferenta de pe pagina anterioara).",
"ignorewarning"	=> "Ignora atentionarea si salveaza.",
"minlength"		=> "Numele imaginilor trebuie sa aiba cel putin trei litere.",
"badfilename"	=> "Numele imaginii a fost schimbat; noul nume este \"$1\".",
"badfiletype"	=> "\".$1\" nu este un format recomandat pentru imagini.",
"largefile"		=> "Este recomandat ca imaginile sa nu depaseasca 100 KB ca marime.",
"successfulupload" => "Fisierul a fost trimis",
"fileuploaded"	=> "Fisierul \"$1\" a fost trimis.
Va rugam sa vizitati aceasta legatura: ($2) pentru a descrie fisierul si pentru a completa informatii despre acesta, ca de exemplu de unde provine, când a fost creat si de catre cine si orice alte informatii doriti sa adaugati.",
"uploadwarning" => "Avertizare la trimiterea fisierului",
"savefile"		=> "Salveaza fitierul",
"uploadedimage" => "trimis \"$1\"",

# Image list
#
"imagelist"		=> "Lista imaginilor",
"imagelisttext"	=> "Dedesubt gasiti lista a $1 imagini ordonate $2.",
"getimagelist"	=> "încarc lista de imagini",
"ilshowmatch"	=> "Arata imaginile ale caror nume includ",
"ilsubmit"		=> "Cauta",
"showlast"		=> "Arata ultimele $1 imagini ordonate $2.",
"all"			=> "toate",
"byname"		=> "dupa nume",
"bydate"		=> "dupa data",
"bysize"		=> "dupa marime",
"imgdelete"		=> "sterge",
"imgdesc"		=> "desc",
"imglegend"		=> "Legenda: (desc) = arata/editeaza descrierea imaginii.",
"imghistory"	=> "Istoria imaginii",
"revertimg"		=> "rev",
"deleteimg"		=> "sterg",
"imghistlegend" => "Legend: (cur) = versiunea curenta a imaginii, (sterg) = sterge aceasta versiune veche, (rev) = revino la aceasta versiune veche.
<br><i>Apasati pe data pentru a vedea versiunea trimisa la data respectiva</i>.",
"imagelinks"	=> "Legaturile imaginii",
"linkstoimage"	=> "Urmatoarele pagini leaga la aceasta imagine:",
"nolinkstoimage" => "Nici o pagina nu se leaga la aceasta imagine.",

# Statistics
#
"statistics"	=> "Statistici",
"sitestats"		=> "Statisticile sitului",
"userstats"		=> "Statistici legate de utilizatori",
"sitestatstext" => "Exista un numar total de <b>$1</b> pagini în baza de date.
Acest numar include paginile de \"discutii\", paginile despre Wikipedia, pagini minimale (\"cioturi\"), pagini de redirectionare si altele care probabil ca nu intra de fapt în categoria articolelor reale.
În afara de acestea, exista <b>$2</b> pagini care sunt probabil articole (numarate automat, în functie strict de marime).<p>
În total au fost <b>$3</b> vizite (accesari) si <b>$4</b> editari
de la ultima actualizare a programului (July 20, 2002).
În medie rezulta <b>$5</b> editari la fiecare vizionare sau <b>$6</b> vizualizari la fiecare editare.",
"userstatstext" => "Exista un numar de <b>$1</b> utilizatori înregistrati.
Dintre acestia <b>$2</b> sunt administratori (vezi $3).",

# Maintenance Page
#
"maintenance"		=> "Pagina administrativa",
"maintnancepagetext"	=> "Aceasta pagina contine diverse unelte create pentru administrare cotidiana. Unele dintre acestea solicita în mod deosebit baza de date, asa ca va rugam sa evitati suprasolicitarea lor.",
"maintenancebacklink"	=> "Înapoi la pagina administrativa",
"disambiguations"	=> "Pagini de dezambiguizare",
"disambiguationspage"	=> "Wikipedia:Legaturi_catre_paginile_de_dezambiguizare",
"disambiguationstext"	=> "Urmatoarele articole contin legaturi catre cel putin o <i>pagina de dezambiguizare</i>. Legaturile respective ar trebui facute catre paginile specifice.<br>O pagina este considerata ca fiind de dezambiguizare daca exista o legatura în ea dinspre $1.<br>Legaturile dinspre alte sectiuni Wikipedia <i>nu sunt</i> luate în considerare aici.",
"doubleredirects"	=> "Redirectari duble",
"doubleredirectstext"	=> "<b>Atentie:</b> Aceasta lista poate contine articole care nu sunt în fapt duble redirectari. Asta înseamna de obicei ca exista text aditional sub primul #REDIRECT.<br>\nFiecare rând care contine legaturi catre prima sau a doua redirectare, ca si prima linie din textul celei de-a doua redirectari, de obicei continând numele \"real\" al articolului tinta, catre care ar trebui sa arate prima redirectare.",
"brokenredirects"	=> "Redirectari gresite",
"brokenredirectstext"	=> "Urmatoarele redirectari arata catre articole inexistente.",
"selflinks"		=> "Pagini cu legaturi ciclice",
"selflinkstext"		=> "Urmatoarele pagini contin legaturi catre ele însele, ceea ce n-ar trebui sa se întâmple.",
"mispeelings"           => "Pagini continând greseli comune",
"mispeelingstext"               => "Urmatoarele pagini contin unele dintre greselile obisnuite de scriere care apar la $1. Forma corecta poate fi data (în acest fel).",
"mispeelingspage"       => "Lista de greteli comune",
"missinglanguagelinks"  => "Legaturi care inexistente catre alte limbi",
"missinglanguagelinksbutton"    => "Cauta limbi inexistente pentru",
"missinglanguagelinkstext"      => "Aceste articole nu se leaga catre perechile lor din $1. Redirectarile si sub-paginile <i>nu apar</i> aici.",


# Miscellaneous special pages
#
"orphans"		=> "Pagini orfane",
"lonelypages"	=> "Pagini orfane",
"unusedimages"	=> "Pagini neutilizate",
"popularpages"	=> "Pagini populare",
"nviews"		=> "$1 accesari",
"wantedpages"	=> "Pagini dorite",
"nlinks"		=> "$1 legaturi",
"allpages"		=> "Toate paginile",
"randompage"	=> "Pagina aleatoare",
"shortpages"	=> "Pagini scurte",
"longpages"		=> "Pagini lungi",
"listusers"		=> "Lista de utilizatori",
"specialpages"	=> "Pagini speciale",
"spheading"		=> "Pagini speciale",
"sysopspheading" => "Pegini speciale pentru operatori",
"developerspheading" => "Pagini speciale pentru dezvoltatori",
"protectpage"	=> "Protejeaza pagina",
"recentchangeslinked" => "Modificari corelate",
"rclsub"		=> "(cu pagini legate de la \"$1\")",
"debug"			=> "Debug",
"newpages"		=> "Pagini noi",
"movethispage"	=> "Muta aceasta pagina",
"unusedimagestext" => "<p>Va rugam sa tineti cont de faptul ca alte situri, inclusiv Wikipedii în alte limbi pot sa aiba legaturi aici fara ca aceste pagini sa fie listate aici - aceasta lista se refera strict la Wikipedia în româna.",
"booksources"	=> "Surse de carti",
"booksourcetext" => "Dedesubt gasiti o lista de surse de carti noi si vechi, si e posibil sa gasiti si informatii aditionale legate de titlurile pe care le cautati.
Wikipedia nu este afiliata niciuneia dintre aceste afaceri,
iar lista de mai jos nu constituie nici un fel de garantie sau validare a serviciilor respective din partea Wikipedia.",

# Email this user
#
"mailnologin"	=> "Nu exista adresa de trimitere",
"mailnologintext" => "Trebuie sa fiti <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">autentificat</a>
si sa aveti o adresa valida de mail în <a href=\"" .
  wfLocalUrl( "Special:Preferences" ) . "\">preferinte</a>
pentru a trimite mail altor utilizatori.",
"emailuser"		=> "Trimite e-mail acestui utilizator",
"emailpage"		=> "E-mail catre utilizator",
"emailpagetext"	=> "Daca acest utilizator a introdus o adresa de mail valida în pagina de preferinte atunci formularul de mai jos poate fi folosit pentru a-i trimte un mesaj prin e-mail.
Adresa pe care ati introdus-o în pagina Dvs. de preferinte va aparea ca adresa
de origine a mesajului, astfel încât destinatarul sa va poata raspunde direct.",
"noemailtitle"	=> "Fara adresa de e-mail",
"noemailtext"	=> "Utilizatorul nu a specificat o adresa valida de e-mail,
sau a ales sa nu primeasca e-mail de la alti utilizatori.",
"emailfrom"		=> "De la",
"emailto"		=> "Catre",
"emailsubject"	=> "Subiect",
"emailmessage"	=> "Mesaj",
"emailsend"		=> "Trimite",
"emailsent"		=> "E-mail trimis",
"emailsenttext" => "E-mailul Dvs. a fost trimis.",

# Watchlist
#
"watchlist"		=> "Articolele pe care le urmaresc",
"watchlistsub"	=> "(pentru utilizatorul \"$1\")",
"nowatchlist"	=> "Nu ati ales sa urmariti nici un articol.",
"watchnologin"	=> "Nu sunteti autentificat",
"watchnologintext"	=> "Trebuie sa fiti <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">autentificat</a>
pentru a va modifica lista de articole urmarite.",
"addedwatch"	=> "Adaugata la lista de pagini urmarite",
"addedwatchtext" => "Pagina \"$1\" a fost adaugata la lista Dvs. de <a href=\"" . wfLocalUrl( "Special:Watchlist" ) . "\">articole urmarite</a>.
Modificarile viitoare ale acestei pagini si a paginii asociate de discutii
vor fi listate aici, si în plus ele vor aparea cu <b>caractere îngrosate</b> în pagina de <a href=\"" .
  wfLocalUrl( "Special:Recentchanges" ) . "\">modificari recente</a> pentru evidentiere.</p>

<p>Daca doriti sa eliminati aceasta pagina din lista Dvs. de pagini urmarite
în viitor, apasati pe \"Nu mai urmari\" în bara de comenzi în timp ce aceasta pagina este vizibila.",
"removedwatch"	=> "Stearsa din lista de pagini urmarite",
"removedwatchtext" => "Pagina \"$1\" a fost eliminata din lista de pagini urmarite.",
"watchthispage"	=> "Urmareste pagina",
"unwatchthispage" => "Nu mai urmari",
"notanarticle"	=> "Nu este un articol",

# Delete/protect/revert
#
"deletepage"	=> "Sterge pagina",
"confirm"		=> "Confirma",
"confirmdelete" => "Confirma stergere",
"deletesub"		=> "(Sterg \"$1\")",
"confirmdeletetext" => "Sunteti pe cale sa stergeti permanent o pagina
sau imagine din baza de date, împreuna cu istoria asociata.
Va rugam sa va confirmati intentia de a face asta, faptul ca
întelegeti consecintele acestei actiuni si faptul ca o faceti
în conformitate cu [[Wikipedia:Politica]].",
"confirmcheck"	=> "Da, chiar vreau sa sterg.",
"actioncomplete" => "Actiune finalizata",
"deletedtext"	=> "\"$1\" a fost stearsa.
Vezi $2 pentru o lista a elementelor sterse recent.",
"deletedarticle" => "\"$1\" a fost sters",
"dellogpage"	=> "Raport_stergeri",
"dellogpagetext" => "Gasiti dedesubt o lista a celor mai recente elemente sterse. Toate datele/orele sunt listate în timp universal (UTC).
<ul>
</ul>
",
"deletionlog"	=> "raport de stergeri",
"reverted"		=> "Revenit la o versiune mai veche",
"deletecomment"	=> "Motiv pentru stergere",
"imagereverted" => "S-a revenit la o versiune veche.",
"rollback"		=> "Editari de revenire",
"rollbacklink"	=> "revenire",
"cantrollback"	=> "Nu se poate reveni; ultimul contribuitor este autorul acestui articol.",
"revertpage"	=> "Revenit la ultima editare de catre $1",

# Undelete
"undelete" => "Recupereaza pagina stearsa",
"undeletepage" => "Vizualizeaza si recupereaza pagini sterse",
"undeletepagetext" => "Urmatoarele pagini au fost sterse dar înca se afla în
arhiva si pot fi recuperate. Retineti ca arhiva se poate sterge din timp în timp.",
"undeletearticle" => "Recupereaza articol sters",
"undeleterevisions" => "$1 versiuni arhivate",
"undeletehistory" => "Daca recuperati pagina, toate versiunile asociate
vor fi adaugate retroactiv în istorie. Daca o pagina noua cu acelasi nume
a fost creata de la momentul stergerii acesteia, versiunile recuperate
vor aparea în istoria paginii, iar versiunea curenta a paginii nu va
fi înlocuita automat de catre versiunea recuperata.",
"undeleterevision" => "Versiunea stearsa la $1",
"undeletebtn" => "Recupereaza!",
"undeletedarticle" => "\"$1\" a fost recuperat",
"undeletedtext"   => "Articolul [[$1]] a fost recuperat.
Vezi [[Wikipedia:Raport_stergeri]] pentru o lista a stergerilor si recuperarilor recente.",

# Contributions
#
"contributions"	=> "Contributii ale utilizatorului",
"mycontris" => "Contributiile mele",
"contribsub"	=> "Pentru $1",
"nocontribs"	=> "Nu a fost gasita nici o modificare sa satisfaca acest criteriu.",
"ucnote"		=> "Gasiti dedesubt ultimele <b>$1</b> modificari ale utilizatorului din ultimele <b>$2</b> zile.",
"uclinks"		=> "Vezi ultimele $1 modificari; vezi ultimele $2 zile.",
"uctop"		=> " (sus)" ,

# What links here
#
"whatlinkshere"	=> "Ce se leaga aici",
"notargettitle" => "Lipsa tinta",
"notargettext"	=> "Nu ati specificat nici un pagina sau utilizator tinta pentru care sa se efectueze aceasta functie.",
"linklistsub"	=> "(Lista de legaturi)",
"linkshere"		=> "Urmatoarele pagini contin legaturi catre aceasta:",
"nolinkshere"	=> "Nici o pagina nu se leaga aici.",
"isredirect"	=> "pagina de redirectare",

# Block/unblock IP
#
"blockip"		=> "Blocheza adresa IP",
"blockiptext"	=> "Folositi chestionarul de mai jos pentru a bloca
la scriere o adresa IP. Aceasta funtie trebuie folosita numai pentru
a preveni vandalismul conform [[Wikipedia:Politica|politicii Wikipedia]].
Includeti un motiv specific mai jos (de exemplu citând paginile care
au fost vandalizate de acest utilizator).",
"ipaddress"		=> "Adresa IP",
"ipbreason"		=> "Motiv",
"ipbsubmit"		=> "Blocheaza aceasta adresa",
"badipaddress"	=> "Adresa IP este invalida.",
"noblockreason" => "Trebuie sa includeti un motiv pentru blocare.",
"blockipsuccesssub" => "Utilizatorul a fost blocat",
"blockipsuccesstext" => "Adresa IP \"$1\" a fost blocata.
<br>Vezi [[Special:Ipblocklist|lista de adrese IP blocate]] pentru a revizui adresele blocate.",
"unblockip"		=> "Deblocheaza adresa IP",
"unblockiptext"	=> "Folositi chestionarul de mai jos pentru a restaura
drepturile de scriere pentru o adresa IP blocata anterior..",
"ipusubmit"		=> "Deblocheaza adresa",
"ipusuccess"	=> "Adresa IP \"$1\" a fost deblocata",
"ipblocklist"	=> "Lista de adrese IP blocate",
"blocklistline"	=> "$1, $2 a blocat $3",
"blocklink"		=> "blocheaza",
"unblocklink"	=> "deblocheaza",
"contribslink"	=> "contributii",

# Developer tools
#
"lockdb"		=> "Blocheaza baza de date",
"unlockdb"		=> "Deblocheaza baza de date",
"lockdbtext"	=> "Blocarea bazei de date va împiedica pe toti utilizatorii
sa editeze pagini, sa-si schimbe preferintele, sa-si editeze listele de
pagini urmarite si orice alte operatiuni care ar necesita schimari
în baza de date.
Va rugam sa confirmati ca intentionati acest lucru si faptul ca veti debloca
baza de date atunci când veti încheia operatiunile de întretinere.",
"unlockdbtext"	=> "Deblocarea bazei de date va permite tuturor utilizatorilor
sa editeze pagini, sa-si schimbe preferintele, sa-si editeze listele de
pagini urmarite si orice alte operatiuni care ar necesita schimari
în baza de date.
Va rugam sa confirmati ca intentionati acest lucru.",
"lockconfirm"	=> "Da, chiar vreau sa blochez baza de date.",
"unlockconfirm"	=> "Da, chiar vreau sa deblochez baza de date.",
"lockbtn"		=> "Blocheaza naza de date",
"unlockbtn"		=> "Deblocheaza baza de date",
"locknoconfirm" => "Nu ati confirmat casuta de confirmare.",
"lockdbsuccesssub" => "Baza de date a fost blocata",
"unlockdbsuccesssub" => "Baza de date a fost deblocata",
"lockdbsuccesstext" => "Baza de date Wikipedia a fost blocata la scriere.
<br>Nu uitati sa o deblocati dupa ce terminati operatiunile administrative pentru care ati blocat-o.",
"unlockdbsuccesstext" => "Baza de date Wikipedia a fost deblocata.",

# SQL query
#
"asksql"		=> "Query SQL",
"asksqltext"	=> "Folositi chestionarul de mai jos pentru a efectua un query direct catre baza de date Wikipedia (MySQL).
Folositi apostrofuri ('în felul acesta') pentru a delimita siruri de text.
Aceasta functionalitate poate solicita în mod deosebit server-ul,
asa ca va rugam sa nu o folositi în exces.",
"sqlquery"		=> "Introduceti query",
"querybtn"		=> "Trimiteti query",
"selectonly"	=> "Alte query-uri în afara de \"SELECT\" sunt accesibile numai pentru dezvoltatorii Wikipedia.",
"querysuccessful" => "Query efectuat",

# Move page
#
"movepage"		=> "Muta pagina",
"movepagetext"	=> "Puteti folosi formularul de mai jos pentru a redenumi
o pagina, mutându-i toata istoria sub noul nume.
Pagina veche va deveni o pagina de redirectare catre pagina noua.
Legaturile catre pagina veche nu vor fi redirectate catre cea noua;
aveti grija sa [[Special:Maintenance|verificati]] daca nu exista redirectari duble sau invalide.

Va rugam sa retineti ca Dvs. sunteti responsabil(a) pentru a face legaturile vechi sa ramâna valide.

Retineti ca pagina '''nu va fi mutata''' daca exista deja o
pagina cu noul titlu, afara de cazul ca este complet goala sau este
o redirectare si în plus nu are nici o istorie de editare.
Cu alte cuvinte, veti putea muta înapoi o pagina pe care ati mutat-o
gresit, dar nu veti putea suprascrie o pagina valida existenta prin
mutarea alteia.

<b>ATENTIE!</b>
Aceasta poate fi o schimbare drastica si neasteptata pentru o pagina populara;
va rugam sa va asigurati ca întelegeti toate consecintele înainte de a continua.",
"movepagetalktext" => "Pagina asociata de discutii, daca exista, va fi mutata
automat odata cu aceasta '''afara de cazul ca''':
* Mutati pagina în alta sectiune a Wikipedia
* Exista deja o pagina de discutii cu continut (care nu este goala), sau
* Deifati casuta de mai jos.

În oricare din cazurile de mai sus va trebui sa mutati sau sa unificati
manual paginile de discutii, daca doriti acest lucru.",
"movearticle"	=> "Muta pagina",
"movenologin"	=> "Nu sunteti autentificat",
"movenologintext" => "Trebuie sa fiti un utilizator înregistrat si sa va <a href=\"" .
  wfLocalUrl( "Special:Userlogin" ) . "\">autentificati</a>
pentru a muta o pagina.",
"newtitle"		=> "Titlul nou",
"movepagebtn"	=> "Muta pagina",
"pagemovedsub"	=> "Pagina a fost mutata",
"pagemovedtext" => "Pagina \"[[$1]]\" a fost mutata la \"[[$2]]\".",
"articleexists" => "O pagina cu acelasi nume exista deja,
sau numele pe care l-ati ales este invalid. Va rugam sa alegeti un alt nume.",
"talkexists"	=> "Pagina în sine a fost mutata, dar pagina de discutii
nu a putut fi mutata deoarece deja exista o alta cu acelasi nume. Va rugam
sa unificati manual cele doua pagini de discutii.",
"movedto"		=> "mutata la",
"movetalk"		=> "Muta si pagina de \"discutii\" daca se poate.",
"talkpagemoved" => "Si pagina de discutii asociata a fost mutata.",
"talkpagenotmoved" => "Pagina asociata de discutii <strong>nu</strong> a fost mutata.",

);

class LanguageRo extends LanguageUtf8 {

	function getDefaultUserOptions () {
		global $wgDefaultUserOptionsRo ;
		return $wgDefaultUserOptionsRo ;
		}

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

	function getUserToggles() {
		global $wgUserTogglesRo;
		return $wgUserTogglesRo;
	}

	function getLanguageNames() {
		global $wgLanguageNamesRo;
		return $wgLanguageNamesRo;
	}

	function getLanguageName( $code ) {
		global $wgLanguageNamesRo;
		if ( ! array_key_exists( $code, $wgLanguageNamesRo ) ) {
			return "";
		}
		return $wgLanguageNamesRo[$code];
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
		return $wgAllMessagesRo[$key];
	}
	
	function fallback8bitEncoding() {
		return "iso8859-2";
	}
}

?>

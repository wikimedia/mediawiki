<?

// $Id$

// The names of the namespaces can be set here, but the numbers
// are magical, so don't change or move them!  The Namespace class
// encapsulates some of the magic-ness.

/* private */ $wgNamespaceNamesFr = array(
	-2	=> "Media",
	-1	=> "Special",
	0	=> "",
	1	=> "Discuter",
	2	=> "Utilisateur",
	3	=> "Discussion_Utilisateur",
	4	=> "Wikip�dia",
	5	=> "Discussion_Wikip�dia",
	6	=> "Image",
	7	=> "Discussion_Image",
	8	=> "MediaWiki",
	9	=> "Discussion_MediaWiki",
);

/* private */ $wgQuickbarSettingsFr = array(
	"Aucune", "Gauche", "Droite", "Flottante � gauche"
);

/* private */ $wgSkinNamesFr = array(
	"Normal", "Nostalgie", "Cologne Blue"
);

/* private */ $wgMathNamesFr = array(
	"Toujours produire une image PNG",  
	"HTML si tr�s simple, autrement PNG", 
	"HTML si possible, autrement PNG", 
	"Laisser le code TeX original",
	"Pour les navigateurs modernes"
);

/* private */ $wgUserTogglesFr = array(
	"hover"  => "Afficher des info-bulles sur les liens wiki",
	"underline" => "Liens soulign�s",
	"highlightbroken" => "Liens vers les sujets non existants en rouge",
	"justify" => "Paragraphes justifi�s",
	"hideminor" => "Cacher les <i>Modifications r�centes</i> mineures",
	"usenewrc" => "Modifications r�centes am�lior�es<br> (certains navigateurs seulement)",
	"numberheadings" => "Num�rotation automatique des titres",
	"showtoolbar" => "Show edit toolbar",
	"editondblclick" => "Double cliquer pour �diter une page (JavaScript)",
	"editsection"	=> "�diter une section via les liens [�diter]",
	"editsectiononrightclick"	=> "�diter une section en cliquant � droite<br> sur le titre de la section",
	"showtoc"	=> "Afficher la table des mati�res<br> (pour les articles ayant plus de 3 sections)",
	"rememberpassword" => "Se souvenir de mon mot de passe (cookie)",
	"editwidth" => "La fen�tre d'�dition s'affiche en pleine largeur",
	"watchdefault" => "Suivre les articles que je cr�e ou modifie",
	"minordefault" => "Mes modifications sont consid�r�es<br> comme mineures par d�faut",
	"previewontop" => "La pr�visualisation s'affiche au<br> dessus de la boite de r�daction",
	"nocache" => "D�sactiver le cache des pages"
);

/* private */ $wgBookstoreListFr = array(
	"Amazon.fr" => "http://www.amazon.fr/exec/obidos/ISBN=$1",
	"alapage.fr"	=> "http://www.alapage.com/mx/?tp=F&type=101&l_isbn=$1&donnee_appel=ALASQ&devise=&",
	"fnac.com"	=> "http://search.fnac.com/search/quick/Default.asp?Isbn=1&SearchType=AdvQuick&Recherche=-1&Restrictions=$isbn",
	"chapitre.com"	=> "http://www.chapitre.com/frame_rec.asp?isbn=$isbn",
);

/* private */ $wgWeekdayNamesFr = array(
	"dimanche", "lundi", "mardi", "mercredi", "jeudi",
	"vendredi", "samedi"
);

/* private */ $wgMonthNamesFr = array(
	"janvier", "f�vrier", "mars", "avril", "mai", "juin",
	"juillet", "ao�t", "septembre", "octobre", "novembre",
	"d�cembre"
);

/* private */ $wgMonthAbbreviationsFr = array(
	"jan", "f�v", "mar", "avr", "mai", "jun", "jul", "ao�",
	"sep", "oct", "nov", "d�c"
);

// All special pages have to be listed here: a description of ""
// will make them not show up on the "Special Pages" page, which
// is the right thing for some of them (such as the "targeted" ones).

/* private */ $wgValidSpecialPagesFr = array(
	"Userlogin"     => "",
	"Userlogout"    => "",
	"Preferences"   => "Pr�f�rences",
	"Watchlist"     => "Liste de suivi",
	"Recentchanges" => "Modifications r�centes",
	"Upload"        => "Copier un fichier",
	"Imagelist"     => "Liste des images",
	"Listusers"     => "Liste des participants",
	"Statistics"    => "Statistiques",
	"Randompage"    => "Une page au hasard",

	"Lonelypages"   => "Pages orphelines",
	"Unusedimages"  => "Images orphelines",
	"Popularpages"  => "Les plus populaires",
	"Wantedpages"   => "Les plus demand�es",
	"Shortpages"    => "Articles courts",
	"Longpages"     => "Articles longs",
	"Newpages"      => "Nouvelles pages",
	"Ancientpages"	=> "Anciennes pages",
	"Allpages"      => "Toutes les pages",

	"Ipblocklist"   => "Adresses IP bloqu�es",
	"Maintenance"   => "Page de maintenance",
	"Specialpages"  => "", // ces pages doivent rester vides !
	"Contributions" => "",
	"Emailuser"     => "",
	"Whatlinkshere" => "",
	"Recentchangeslinked" => "",
	"Movepage"      => "",
	"Booksources"   => "Librairies en ligne",
//	"Categories"	=> "Page des cat�gories"	// Looxix "Page categories"
	"Export"	=> "Exporter par XML",
);

/* private */ $wgSysopSpecialPagesFr = array(
	"Blockip"       => "Bloquer une adresse IP",
	"Asksql"        => "Acc�s SQL",
	"Undelete"      => "G�rer les pages effac�es"
);

/* private */ $wgDeveloperSpecialPagesFr = array(
	"Lockdb"        => "Bloquer la base de donn�es",
	"Unlockdb"      => "D�bloquer la base de donn�es",
	"Debug"         => "Informations de d�boguage"
);

/* private */ $wgAllMessagesFr = array(

# Bits of text used by many pages:
#
"categories"	=> "Cat�gories de la page", // Looxix "Page categories",
"category"	=> "cat�gorie",			// Looxix "category",
"category_header" => "Articles dans la cat�gorie \"$1\"", // Looxix "Articles in category \"$1\"",
"subcategories"	=> "Sous-cat�gories", // Looxix "Subcategories",


"linktrail"     => "/^([a-z���������]+)(.*)\$/sD",
"mainpage"      => "Accueil",
"mainpagetext"	=> "Logiciel Wikip�dia install�.",
"about"         => "� propos",
"aboutwikipedia" => "� propos de Wikip�dia",
"aboutpage"     => "Wikip�dia:� propos",
"help"          => "Aide",
"helppage"      => "Wikip�dia:Aide",
"wikititlesuffix" => "Wikip�dia",
"bugreports"    => "Rapport d'erreurs",
"bugreportspage" => "Wikip�dia:Rapport d'erreurs",
"sitesupport"	=> "Participer en faisant un don",
"sitesupportpage"	=> "Wikip�dia:Dons",
"faq"           => "FAQ",
"faqpage"       => "Wikip�dia:FAQ",
"edithelp"      => "Aide",
"edithelppage"  => "Wikip�dia:Comment �diter une page",
"cancel"        => "Annuler",
"qbfind"        => "Rechercher",
"qbbrowse"      => "D�filer",
"qbedit"        => "�diter",
"qbpageoptions" => "Page d'option",
"qbpageinfo"    => "Page d'information",
"qbmyoptions"   => "Mes options",
"qbspecialpages"	=> "Pages sp�ciales",
"moredotdotdot"	=> "Et plus...",
"mypage"        => "Ma page",
"mytalk"        => "Ma page de discussion",
"currentevents" => "Actualit�s",
"errorpagetitle" => "Erreur",
"returnto"      => "Revenir � la page $1.",
"fromwikipedia" => "Un article de Wikip�dia, l'encyclop�die libre.",
"whatlinkshere" => "R�f�rences � cette page",
"help"          => "Aide",
"search"        => "Rechercher",
"history"       => "Historique",
"printableversion" => "Version imprimable",
"editthispage"  => "Modifier cette page",
"deletethispage" => "Supprimer cette page",
"protectthispage" => "Prot�ger cette page",
"unprotectthispage" => "D�prot�ger cette page",
"newpage"       => "Nouvelle page",
"talkpage"      => "Page de discussion",
"postcomment"	=> "Ajouter un commentaire",
"articlepage"	=> "Voir l'article",
"subjectpage"   => "Page sujet",
"userpage"      => "Page utilisateur",
"wikipediapage" => "Page m�ta",
"imagepage"     => "Page image",
"viewtalkpage"  => "Page de discussion",
"otherlanguages" => "Autres langues",
"redirectedfrom" => "(Redirig� depuis $1)",
"lastmodified"  => "Derni�re modification de cette page : $1.",
"viewcount"     => "Cette page a �t� consult�e $1 fois.",
"gnunote"       => "Tous les textes sont disponibles sous les termes de la <a class=internal href='/wiki/GFDL'>Licence de documentation libre GNU</a>.",
"printsubtitle" => "(de http://www.wikipedia.org)",
"protectedpage" => "Page prot�g�e",
"administrators" => "Wikip�dia:Administrateurs",
"sysoptitle"    => "Acc�s administrateur requis",
"sysoptext"     => "L'action que vous avez tent�e ne peut �tre effectu�e que par un utilisateur ayant le statut d'\"administrateur\".
Voir $1.",
"developertitle" => "Acc�s d�veloppeur requis",
"developertext" => "L'action que vous avez tent�e ne peut �tre effectu�e que par un utilisateur ayant le statut de \"d�veloppeur\".
Voir $1.",
"nbytes"        => "$1 octets",
"go"            => "Consulter",
"ok"            => "OK",
"sitetitle"     => "Wikip�dia",
"sitesubtitle"  => "L'encyclop�die libre",
"retrievedfrom" => "R�cup�r�e de \"$1\"",
"newmessages"   => "Vous avez des $1.",
"newmessageslink" => "nouveaux messages",
"editsection"	=> "modifier",
"toc"		=> "Sommaire",
"showtoc"	=> "montrer",
"hidetoc"	=> "cacher",
"thisisdeleted" => "Afficher ou restaurer $1?",
"restorelink"	=> "$1 modifications effac�es",

# Main script and global functions
#
"nosuchaction"	=> "Action inconnue",
"nosuchactiontext" => "L'action sp�cifi�e dans l'Url n'est pas reconnue par le logiciel Wikip�dia.",
"nosuchspecialpage" => "Page sp�ciale inexistante",
"nospecialpagetext" => "Vous avez demand� une page sp�ciale qui n'est pas reconnue par le logiciel Wikip�dia.",

# General errors
#
"error"		=> "Erreur",
"databaseerror" => "Erreur base de donn�es",
"dberrortext"	=> "Erreur de syntaxe dans la base de donn�es. Cette erreur peut �tre caus�e par une requ�te de recherche incorrecte (voir $5), ou une erreur dans le logiciel. La derni�re requ�te trait�e par la base de donn�es �tait :
<blockquote><tt>$1</tt></blockquote>
depuis la fonction \"<tt>$2</tt>\".
MySQL a renvoy� l'erreur \"<tt>$3: $4</tt>\".",
"noconnect"	=> "D�sol�! Suite � des probl�mes techniques, il est impossible de se connecter � la base de donn�es pour le moment.", //"Connexion impossible � la base de donn�es sur $1",
"nodb"		=> "S�lection impossible de la base de donn�es $1",
"cachederror"	=> "Ceci est une copie de la page demand�e et peut ne pas �tre � jour",
"readonly"	=> "Mises � jour bloqu�es sur la base de donn�es",
"enterlockreason" => "Indiquez la raison du blocage, ainsi qu'une estimation de la dur�e de blocage ",
"readonlytext"	=> "Les ajouts et mises � jour sur la base de donn�es Wikip�dia sont actuellement bloqu�s, probablement pour permettre la maintenance de la base, apr�s quoi, tout rentrera dans l'ordre. Voici la raison pour laquelle l'administrateur a bloqu� la base :
<p>$1",
"missingarticle" => "La base de donn�es n'a pas pu trouver le texte d'une page existante, dont le titre est \"$1\".
Ce n'est pas une erreur de la base de donn�es, mais plus probablement un bogue du logiciel Wikip�dia.
Veuillez rapporter cette erreur � un administrateur, en lui indiquant l'adresse de la page fautive.",
"internalerror" => "Erreur interne",
"filecopyerror" => "Impossible de copier \"$1\" vers \"$2\".",
"filerenameerror" => "Impossible de renommer \"$1\" en \"$2\".",
"filedeleteerror" => "Impossible de supprimer \"$1\".",
"filenotfound"	=> "Fichier \"$1\" introuvable.",
"unexpected"	=> "Valeur inattendue : \"$1\"=\"$2\".",
"formerror"	=> "Erreur: Impossible de soumettre le formulaire",
"badarticleerror" => "Cette action ne peut pas �tre effectu�e sur cette page.",
"cannotdelete"	=> "Impossible de supprimer la page ou l'image indiqu�e.",
"badtitle"	=> "Mauvais titre",
"badtitletext"	=> "Le titre de la page demand�e est invalide, vide ou le lien interlangue est invalide",
"perfdisabled" => "D�sol�! Cette fonctionnalit� est temporairement d�sactiv�e
car elle ralentit la base de donn�es � un point tel que plus personne
ne peut utiliser le wiki.",
"perfdisabledsub" => "Ceci est une copie de sauvegarde de $1:",
"viewsource"	=> "Voir le texte source",
"protectedtext"	=> "Cette page a �t� bloqu�e pour emp�cher sa modification. Consultez [[Wikip�dia:Page prot�g�e]] pour voir les diff�rentes raisons possibles.",

# Login and logout pages
#
"logouttitle"	=> "D�connexion",
"logouttext"	=> "Vous �tes � pr�sent d�connect�(e).
Vous pouvez continuer � utiliser Wikip�dia de fa�on anonyme, ou vous reconnecter, �ventuellement sous un autre nom.\n",

"welcomecreation" => "<h2>Bienvenue, $1!</h2><p>Votre compte d'utilisateur a �t� cr��.
N'oubliez pas de personnaliser votre Wikip�dia en consultant la page Pr�f�rences.",

"loginpagetitle"     => "Votre identifiant",
"yourname"           => "Votre nom d'utilisateur",
"yourpassword"       => "Votre mot de passe",
"yourpasswordagain"  => "Entrez � nouveau votre mot de passe",
"newusersonly"       => " (nouveaux utilisateurs uniquement)",
"remembermypassword" => "Se souvenir de mon mot de passe (cookie)",
"loginproblem"       => "<b>Probl�me d'identification.</b><br>Essayez � nouveau !",
"alreadyloggedin"    => "<font color=red><b>Utilisateur $1, vous �tes d�j� identifi�!</b></font><br>\n",

"login"         => "Identification",
"userlogin"     => "Identification",
"logout"        => "D�connexion",
"userlogout"    => "D�connexion",
"notloggedin"	=> "Non connect�",
"createaccount" => "Cr�er un nouveau compte",
"createaccountmail"	=> "par courriel", // Looxix "by eMail",
"badretype"     => "Les deux mots de passe que vous avez saisis ne sont pas identiques.",
"userexists"    => "Le nom d'utilisateur que vous avez saisi est d�j� utilis�. Veuillez en choisir un autre.",
"youremail"     => "Mon adresse �lectronique",
"yournick"      => "Mon surnom (pour les signatures)",
"emailforlost"  => "Si vous �garez votre mot de passe, vous pouvez demander � ce qu'un nouveau vous soit envoy� � votre adresse �lectronique.",
"loginerror"    => "Probl�me d'identification",
"noname"        => "Vous n'avez pas saisi de nom d'utilisateur.",
"loginsuccesstitle" => "Identification r�ussie.",
"loginsuccess"  => "Vous �tes actuellement connect�(e) sur Wikip�dia en tant que \"$1\".",
"nosuchuser"    => "L'utilisateur \"$1\" n'existe pas.
V�rifiez que vous avez bien orthographi� le nom, ou utilisez le formulaire ci-dessous pour cr�er un nouveau compte utilisateur.",
"wrongpassword" => "Le mot de passe est incorrect. Essayez � nouveau.",
"mailmypassword" => "Envoyez-moi un nouveau mot de passe",
"passwordremindertitle" => "Votre nouveau mot de passe sur Wikip�dia",
"passwordremindertext" => "Quelqu'un (probablement vous) ayant l'adresse IP $1 a demand� � ce qu'un nouveau mot de passe vous soit envoy� pour votre acc�s � Wikip�dia.
Le mot de passe de l'utilisateur \"$2\" est � pr�sent \"$3\".
Nous vous conseillons de vous connecter et de modifier ce mot de passe d�s que possible.",
"noemail"  => "Aucune adresse �lectronique n'a �t� enregistr�e pour l'utilisateur \"$1\".",
"passwordsent" => "Un nouveau mot de passe a �t� envoy� � l'adresse �lectronique de l'utilisateur \"$1\".
Veuillez vous identifier d�s que vous l'aurez re�u.",

# Edit pages
#
"summary"      => "R�sum�",
"subject"	=> "Sujet/titre", // Looxix "Subject/headline",
"minoredit"    => "Modification mineure.",
"watchthis"    => "Suivre cet article",
"savearticle"  => "Sauvegarder",
"preview"      => "Pr�visualiser",
"showpreview"  => "Pr�visualisation",
"blockedtitle" => "Utilisateur bloqu�",
"blockedtext"  => "Votre compte utilisateur ou votre adresse IP ont �t� bloqu�s par $1 pour la raison suivante :<br>$2<p>Vous pouvez contacter $1 ou un des autres [[Wikip�dia:Administrateurs|administateurs]] pour en discuter.",
"whitelistedittitle" => "Login requis pour r�diger", // Looxix "Login required to edit",
"whitelistedittext" => "Vous devez �tre [[Special:Userlogin|connect�]] pour pouvoir r�diger", // Looxix 
"whitelistreadtitle" => "Login requis pour lire", // Looxix "Login required to read",
"whitelistreadtext" => "Vous devez �tre [[Special:Userlogin|connect�]] pour pouvoir lire les articles", // Looxix 
"whitelistacctitle" => "Vous n'�tes pas autoris� � cr�er un compte", // Looxix 
"whitelistacctext" => "Pour pouvoir cr�er un compte sur ce Wiki vous devez �tre [[Special:Userlogin|connect�]] et avoir les permissions appropri�es", // Looxix 
"accmailtitle" => "Mot de passe envoy�.", // Looxix "Password sent.",
"accmailtext" => "Le mot de passe de '$1' a �t� envoy� � $2.", // Looxix 

"newarticle"   => "(Nouveau)",
"newarticletext" => "Saisissez ici le texte de votre article.",
"anontalkpagetext" => "---- ''Ceci est la page de discussion pour un utilisateur anonyme qui n'a pas encore cr�� un compte ou qui ne l'utilise pas. Pour cette raison, nous devons utiliser l'[[adresse IP]] num�rique pour l'identifier. Une adresse de ce type peut �tre partag�e entre plusieurs utilisateurs. Si vous �tes un utilisateur anonyme et si vous constatez que des commentaires qui ne vous concernent pas vous ont �t� adress�s, vous pouvez [[Special:Userlogin|cr�er un compte ou vous connecter]] afin d'�viter toute future confusion � l'avenir.", 
"noarticletext" => "(Il n'y a pour l'instant aucun texte sur cette page)",
"updated"      => "(Mis � jour)",
"note"         => "<strong>Note :</strong> ",
"previewnote"  => "Attention, ce texte n'est qu'une pr�visualisation et n'a pas encore �t� sauvegard�!",
"previewconflict" => "La pr�visualisation montre le texte de cette page tel qu'il appara�tra une fois sauvegard�.",
"editing"      => "modification de $1",
"section edit"	=> " (section)",
"comment edit"	=> " (commentaire)",
"editconflict" => "Conflit de modification : $1",
"explainconflict" => "<b>Cette page a �t� sauvegard�e apr�s que vous avez commenc� � la modifier.
La zone d'�dition sup�rieure contient le texte tel qu'il est enregistr� actuellement dans la base de donn�es. Vos modifications apparaissent dans la zone d'�dition inf�rieure. Vous allez devoir apporter vos modifications au texte existant. Seul le texte de la zone sup�rieure sera sauvegard�.\n<p>",
"yourtext"     => "Votre texte",
"storedversion" => "Version enregistr�e",
"editingold"   => "<strong>Attention : vous �tes en train de modifier une version obsol�te de cette page. Si vous sauvegardez, toutes les modifications effectu�es depuis cette version seront perdues.</strong>\n",
"yourdiff"  => "Diff�rences",
"copyrightwarning" => "Toutes les contributions � Wikip�dia sont consid�r�es comme publi�es sous les termes de la GNU Free Documentation Licence, une licence de documentation libre (Voir $1 pour plus de d�tails). Si vous ne d�sirez pas que vos �crits soient �dit�s et distribu�s � volont�, ne les envoyez pas. De m�me, merci de ne contribuer qu'en apportant vos propres �crits ou des �crits issus d'une source libre de droits. <b>N'UTILISEZ PAS DE TRAVAUX SOUS COPYRIGHT SANS AUTORISATION EXPRESSE!</b>",
"longpagewarning" => "AVERTISSEMENT : cette page a une longueur de $1 ko;
quelques navigateurs g�rent mal les pages approchant ou d�passant 32 ko lors de leur r�daction.
Peut-�tre serait-il mieux que vous divisiez la page en sections plus petites.", // Panoramix
"readonlywarning" => "AVERTISSEMENT : cette page a �t� bloqu�e pour maintenance,
vous ne pourrez donc pas sauvegarder vos modifications maintenant. Vous pouvez copier le texte dans un fichier et le sauver pour plus tard.",
"protectedpagewarning" => "AVERTISSEMENT : cette page a �t� bloqu�e.
Seuls les utilisateurs ayant le statut d'administrateur peuvent la modifier. Soyez certain que
vous suivez les <a href='/wiki/Wikip�dia:Page prot�g�e'>directives concernant les pages prot�g�es</a>.",

# History pages
#
"revhistory"   => "Versions pr�c�dentes",
"nohistory"    => "Il n'existe pas d'historique pour cette page.",
"revnotfound"  => "Version introuvable",
"revnotfoundtext" => "La version pr�c�dente de cette page n'a pas pu �tre retrouv�e. V�rifiez l'URL que vous avez utilis�e pour acc�der � cette page.\n",

"loadhist"     => "Chargement de l'historique de la page",
"currentrev"   => "Version actuelle",
"revisionasof" => "Version du $1",
"cur"    => "actu",
"next"   => "suiv",
"last"   => "dern",
"orig"   => "orig",
"histlegend" => "L�gende : (actu) = diff�rence avec la version actuelle ,
(dern) = diff�rence avec la version pr�c�dente, M = modification mineure",

#  Diffs
#
"difference" => "(Diff�rences entre les versions)",
"loadingrev" => "chargement de l'ancienne version pour comparaison",
"lineno"  => "Ligne $1:",
"editcurrent" => "Modifier la version actuelle de cette page",


# Search results
#
"searchresults" => "R�sultat de la recherche",
"searchhelppage" => "Wikip�dia:Recherche",
"searchingwikipedia" => "Chercher dans Wikip�dia",
"searchresulttext" => "Pour plus d'informations sur la recherche dans Wikip�dia, voir $1.",
"searchquery" => "Pour la requ�te \"$1\"",
"badquery"  => "Requ�te mal formul�e",
"badquerytext" => "Nous n'avons pas pu traiter votre requ�te.
Vous avez probablement recherch� un mot d'une longueur inf�rieure
� trois lettres, ce qui n'est pas encore possible. Vous avez
aussi pu faire une erreur de syntaxe, telle que \"poisson et
et �cailles\".
Veuillez essayer une autre requ�te.",
"matchtotals" => "La requ�te \"$1\" correspond � $2 titre(s)
d'article et au texte de $3 article(s).",
"nogomatch" => "Aucune page avec ce titre n'existe, essai avec la recherche compl�te.",
"titlematches" => "Correspondances dans les titres",
"notitlematches" => "Aucun titre d'article ne contient le(s) mot(s) demand�(s)",
"textmatches" => "Correspondances dans les textes",
"notextmatches" => "Aucun texte d'article ne contient le(s) mot(s) demand�(s)",
"prevn"   => "$1 pr�c�dents",
"nextn"   => "$1 suivants",
"viewprevnext" => "Voir ($1) ($2) ($3).",
"showingresults" => "Affichage de <b>$1</b> r�sultats � partir du #<b>$2</b>.",
"showingresultsnum" => "Affichage de <b>$3</b> r�sultats � partir du #<b>$2</b>.",
"nonefound"  => "<strong>Note</strong>: l'absence de r�sultat est souvent due � l'emploi de termes de recherche trop courants, comme \"�\" ou \"de\",
qui ne sont pas index�s, ou � l'emploi de plusieurs termes de recherche (seules les pages
contenant tous les termes apparaissent dans les r�sultats).",
"powersearch" => "Recherche",
"powersearchtext" => "
Rechercher dans les espaces :<br>
$1<br>
$2 Inclure les page de redirections &nbsp; Rechercher $3 $9",
"searchdisabled" => "<p>La fonction de recherche sur l'enti�ret� du texte a �t� temporairement d�sactiv�e � cause de la grande charge que cela impose au serveur. Nous esp�rons la r�tablir prochainement lorsque nous disposerons d'un serveur plus puissant. En attendant, vous pouvez faire la recherche avec Google:</p>
                                                                                                                                                        
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
<input type=hidden name=domains value=\"{$wgServer}\"><br><input type=radio
name=sitesearch value=\"\"> WWW <input type=radio name=sitesearch
value=\"{$wgServer}\" checked> {$wgServer} <br>
<input type='hidden' name='ie' value='$2'>
<input type='hidden' name='oe' value='$2'>
</font>
</td></tr></TABLE>
</FORM>
<!-- SiteSearch Google -->
",
"blanknamespace" => "(Principal)",	// FIXME FvdP: trad de "(Main)"

# Preferences page
#
"preferences"       => "Pr�f�rences",
"prefsnologin"      => "Non connect�",
"prefsnologintext"  => "Vous devez �tre <a href=\"" .
		wfLocalUrl( "Special:Userlogin" ) . "\">connect�</a>
pour modifier vos pr�f�rences d'utilisateur.",
"prefslogintext" => "Je suis connect�(e) en tant que $1 avec le num�ro d'utilisateur $2.

Voir [[Wikip�dia:Aide pour les pr�f�rences]] pour les explications concernant les options.",
"prefsreset"        => "Les pr�f�rences ont �t� r�tablies � partir de la version enregistr�e.",
"qbsettings"        => "Personnalisation de la barre outils",
"changepassword"    => "Modification du mot de passe",
"skin"              => "Apparence",
"math"			=> "Rendu des maths",	// Looxix "Rendering math",
"dateformat"		=> "Format de date",
"math_failure"		=> "Erreur math",	// Looxix "Failure toparse",
"math_unknown_error"	=> "erreur ind�termin�e",   // FvdP+Looxix "unknown error",
"math_unknown_function"	=> "fonction inconnue",
"math_lexing_error"	=> "erreur lexicale",   // Looxxi "lexing error",
"math_syntax_error"	=> "erreur de syntaxe",
"saveprefs"         => "Enregistrer les pr�f�rences",
"resetprefs"        => "R�tablir les pr�f�rences",
"oldpassword"       => "Ancien mot de passe",
"newpassword"       => "Nouveau mot de passe",
"retypenew"         => "Confirmer le nouveau mot de passe",
"textboxsize"       => "Taille de la fen�tre d'�dition",
"rows"              => "Rang�es",
"columns"           => "Colonnes",
"searchresultshead" => "Affichage des r�sultats de recherche",
"resultsperpage"    => "Nombre de r�ponses par page",
"contextlines"      => "Nombre de lignes par r�ponse",
"contextchars"      => "Nombre de caract�res de contexte par ligne",
"stubthreshold"     => "Taille minimale des articles courts",
"recentchangescount" => "Nombre de titres dans les modifications r�centes",
"savedprefs"        => "Les pr�f�rences ont �t� sauvegard�es.",
"timezonetext"      => "Si vous ne pr�cisez pas de d�calage horaire, c'est l'heure de l'Europe de l'ouest qui sera utilis�e.",
"localtime"         => "Heure locale",
"timezoneoffset"    => "D�calage horaire",
"servertime"	    => "Heure du serveur", //Looxix (Server time is now)
"guesstimezone"     => "Utiliser la valeur du navigateur", //Looxix (Fill in from browser)
"emailflag"         => "Ne pas recevoir de courrier �lectronique<br> des autres utilisateurs",
"defaultns"         => "Par d�faut, rechercher dans ces espaces :", //Looxix (Search in these namespaces by default)

# Recent changes
#
"changes"	=> "modifications",
"recentchanges" => "Modifications r�centes",
"recentchangestext" => "Suivez sur cette page les derni�res modifications de Wikip�dia.
[[Wikip�dia:Bienvenue|Bienvenue]] aux nouveaux participants!
Jetez un coup d'&oelig;il sur ces pages&nbsp;: [[Wikip�dia:FAQ|foire aux questions]],
[[Wikip�dia:Recommandations et r�gles � suivre|recommandations et r�gles � suivre]]
(notamment [[Wikip�dia:R�gles de nommage|conventions de nommage]],
[[Wikip�dia:La neutralit� de point de vue|la neutralit� de point de vue]]),
et [[Wikip�dia:Les faux-pas les plus courants|les faux-pas les plus courants]].

Si vous voulez que Wikip�dia connaisse le succ�s, merci de ne pas y inclure pas de mat�riaux prot�g�s par des [[Wikip�dia:Copyright|copyrights]]. La responsabilit� juridique pourrait en effet compromettre le projet. ",
"rcloaderr"  => "Chargement des derni�res modifications",
"rcnote"  => "Voici les <strong>$1</strong> derni�res modifications effectu�es au cours des <strong>$2</strong> derniers jours.",
"rcnotefrom"	=> "Voici les modifications effectu�es depuis le <strong>$2</strong> (<b>$1</b> au maximum).",
"rclistfrom"	=> "Afficher les nouvelles modifications depuis le $1.",
# "rclinks"  => "Afficher les $1 derni�res modifications effectu�es au cours des $2 derni�res heures / $3 derniers jours",
# "rclinks"  => "Afficher les $1 derni�res modifications effectu�es au cours des $2 derniers jours.",
"rclinks"	=> "Afficher les $1 derni�res modifications effectu�es au cours des $2 derniers jours; $3 modifications mineures.",	// Looxix
"rchide"  => "in $4 form; $1 modifications mineures; $2 espaces secondaires; $3 modifications multiples.", // FIXME
"rcliu"		=> "; $1 modifications par des contributeurs connect�s",
"diff"            => "diff",
"hist"            => "hist",
"hide"            => "cacher",
"show"            => "montrer",
"tableform"       => "table",
"listform"        => "liste",
"nchanges"        => "$1 modification(s)",
"minoreditletter" => "M",
"newpageletter"   => "N",

# Upload
#
"upload"       => "Copier sur le serveur",
"uploadbtn"    => "Copier un fichier",
"uploadlink"   => "Copier des images",
"reupload"     => "Copier � nouveau",
"reuploaddesc" => "Retour au formulaire.",

"uploadnologin" => "Non connect�(e)",
"uploadnologintext" => "Vous devez �tre <a href=\"" .
		wfLocalUrl( "Special:Userlogin" ) . "\">connect�</a>
pour copier des fichiers sur le serveur.",
"uploadfile"   => "Copier un fichier",
"uploaderror"  => "Erreur",
"uploadtext"   => "<strong>STOP !</strong> Avant de copier votre fichier sur le serveur,
prenez connaissance des <a href=\"" .wfLocalUrlE( "Wikip�dia:r�gles d'utilisation des images" ) . "\">r�gles d'utilisation des images</a> de Wikip�dia et assurez-vous que vous les respectez.<br>N'oubliez pas de remplir la <a href=\"" .wfLocalUrlE( "Wikip�dia:Page de description d'une image" ). "\">page de description de l'image</a> quand celle-ci sera sur le serveur.
<p>Pour voir les images d�j� plac�es sur le serveur ou pour effectuer une recherche parmi celles-ci,
allez � la <a href=\"" . wfLocalUrlE( "Special:Imagelist" ) ."\">liste des images</a>.
Les uploads et les suppressions sont list�s dans le <a href=\"" .wfLocalUrlE( "Wikip�dia:Journal_des_uploads" ) . "\">journal des uploads</a>.
<p>Utilisez le formulaire ci-dessous pour copier sur le serveur de nouvelles images destin�es � illustrer vos articles.
Sur la plupart des navigateurs, vous verrez un bouton \"Browse...\" qui ouvre la fen�tre de dialogue standard de votre syst�me d'exploitation pour l'ouverture des fichiers.
S�lectionnez un fichier, son nom appara�tra dans le champ situ� � c�t� du bouton.
Vous devez �galement confirmer, en cochant la case pr�vue � cet effet, que la copie de ce fichier ne viole aucun copyright.
Cliquez sur le bouton \"Envoyer\" pour terminer.
Si votre connexion est lente, l'op�ration peut prendre un certain temps.
<p>Les formats recommand�s sont JPEG pour les photos, PNG
pour les dessins et les autres images, et OGG pour les fichiers sonores.
Donnez � vos fichiers des noms descriptifs clairs, afin d'�viter toute confusion.
Pour incorporer l'image dans un article, placez dans celui-ci un lien r�dig� comme suit:
<b>[[image:nom_du_fichier.jpg]]</b> ou <b>[[image:nom_du_fichier.png|autre texte]]</b>
ou <b>[[media:nom_du_fichier.ogg]]</b> pour les sons.
<p>N'oubliez pas que, comme toutes les pages de Wikip�dia, les fichiers que vous copiez peuvent �tre modifi�s ou supprim�s par les autres utilisateurs s'ils estiment que cela est dans l'int�r�t de l'encyclop�die. Sachez aussi que votre acc�s au serveur peut �tre bloqu� si vous faites un mauvais usage du syst�me.",
"uploadlog"  => "log d'upload",		// FIXME
"uploadlogpage" => "Log_d'upload",	// FIXME
"uploadlogpagetext" => "Voici la liste des derniers fichiers copi�s sur le serveur.
L'heure indiqu�e est celle du serveur (UTC).
<ul>
</ul>
",
"filename"	=> "Nom",
"filedesc"	=> "Description",
"filestatus"	=> "Statut du copyright",
"filesource"	=> "Source",	
"affirmation"	=> "Je d�clare que le d�tenteur du copyright de ce fichier accepte de le diffuser selon les termes de la $1.",
"copyrightpage" => "Wikip�dia:Copyright",
"copyrightpagename" => "licence Wikip�dia",
"uploadedfiles" => "Fichiers copi�s",
"noaffirmation" => "Vous devez confirmer que la copie de ce fichier ne viole aucun copyright.",
"ignorewarning" => "Ignorer l'avertissement et copier le fichier quand m�me.",
"minlength"	=> "Les noms des images doivent comporter au moins trois lettres.",
"badfilename" => "L'image a �t� renomm�e \"$1\".",
"badfiletype" => "\".$1\" n'est pas un format recommand� pour les fichiers images.",
"largefile"  => "La taille maximale conseill�e pour les images est de 100Ko.",
"successfulupload" => "Copie r�ussie",
"fileuploaded" => "Le fichier \"$1\" a �t� copi� sur le serveur.
Suivez ce lien: ($2) pour acc�der � la page de description, et donner des informations sur le fichier, par exemple son origine, sa date de cr�ation, son auteur, ou tout autre renseignement en votre possession.",
"uploadwarning" => "Attention !",
"savefile"  => "Sauvegarder le fichier",
"uploadedimage" => " \"$1\" copi� sur le serveur",

# Image list
#
"imagelist"  => "Liste des images",
"imagelisttext" => "Voici une liste de $1 images class�es $2.",
"getimagelist" => "R�cup�ration de la liste des images",
"ilshowmatch" => "Afficher toutes les images dont le nom contient ",
"ilsubmit"  => "Chercher",
"showlast"  => "Afficher les $1 derni�res images class�es $2.",
"all"   => "toutes",
"byname"  => "par nom",
"bydate"  => "par date",
"bysize"  => "par taille",
"imgdelete"  => "suppr",
"imgdesc"  => "descr",
"imglegend"  => "L�gende: (descr) = afficher/modifier la description de l'image.",
"imghistory" => "Historique de l'image",
"revertimg"  => "r�tab",
"deleteimg"  => "suppr",
"imghistlegend" => "L�gende: (actu) = ceci est l'image actuelle, (suppr) = supprimer
cette ancienne version, (r�tab) = r�tablir cette ancienne version.
<br><i>Cliquez sur la date pour voir l'image copi�e � cette date</i>.",
"imagelinks" => "Liens vers l'image",
"linkstoimage" => "Les pages ci-dessous comportent un lien vers cette image:",
"nolinkstoimage" => "Aucune page ne comporte de lien vers cette image.",

# Statistics

"statistics" => "Statistiques",
"sitestats"  => "Statistiques du site",
"userstats"  => "Statistiques utilisateur",
"sitestatstext" => "La base de donn�es contient actuellement <b>$1</b> pages.

Ce chiffre inclut les pages \"discussion\", les pages relatives � Wikip�dia, les pages minimales (\"bouchons\"),  les pages de redirection, ainsi que d'autres pages qui ne peuvent sans doute pas �tre consid�r�es comme des articles.
Si l'on exclut ces pages, il reste <b>$2</b> pages qui sont probablement de v�ritables articles.<p>
<b>$3</b> pages ont �t� consult�es et <b>$4</b> pages modifi�es

depuis la mise � jour du logiciel (31 octobre 2002).
Cela repr�sente une moyenne de <b>$5</b> modifications par page et de <b>$6</b> consultations pour une modification.",
"userstatstext" => "Il y a <b>$1</b> utilisateurs enregistr�s.
Parmi ceux-ci, <b>$2</b> ont le statut d'administrateur (voir $3).",


# Maintenance Page
#
"maintenance"		=> "Page de maintenance",
"maintnancepagetext"	=> "Cette page inclut plusieurs utilitaires pour la maintenance quotidienne. Certains de ces outils ont tendance � charger la base de donn�es; ne rechargez pas la page a chaque modification.",
"maintenancebacklink"	=> "Retour � la page de maintenance",
"disambiguations"	=> "Pages d'homonymie",
"disambiguationspage"	=> "Wikip�dia:Liens_aux_pages_d'homonymie",
"disambiguationstext"	=> "Les articles suivants sont li�s � une <i>page d'homonymie</i>. Or, ils devraient �tre li�s au sujet.<br>Une page est consid�r�e comme page d'homonymie si elle est li�e � partir de $1.<br>Les liens � partir d'autres <i>espaces</i> ne sont pas pris en compte.",
"doubleredirects"	=> "Double redirection",
"doubleredirectstext"	=> "<b>Attention:</b> cette liste peut contenir des \"faux positifs\". Dans ce cas, c'est probablement la page du premier #REDIRECT contient aussi du texte.<br>Chaque ligne contient les liens � la 1re et 2e page de redirection, ainsi que la premi�re ligne de cette derni�re, qui donne normalement la \"vraie\" destination. Le premier #REDIRECT devrait lier vers cette destination.",
"brokenredirects"	=> "Redirections cass�es", 
"brokenredirectstext"	=> "Ces redirections m�nent a une page qui n'existe pas.",
"selflinks"		=> "Page avec un lien circulaire",
"selflinkstext"		=> "Les pages suivantes contiennent un lien vers elles-m�mes, ce qui n'est pas permis.",
"mispeelings"           => "Pages avec fautes d'orthographe",
"mispeelingstext"               => "Les pages suivantes contiennent une faute d'orthographe courante (la liste de celles-ci est sur $1). L'orthographe correcte est peut-�tre (ceci).",
"mispeelingspage"       => "Liste de fautes d'orthographe courantes",

# les 3 messages suivants ne sont plus utilis�s (plus de page Special:Intl)
"missinglanguagelinks"  => "Liens inter-langues manquants",
"missinglanguagelinksbutton"    => "Je n'ai pas trouv� de lien/langue pour cette page",
"missinglanguagelinkstext"      => "Ces articles ne lient pas � leur 'contrepartie' in $1. Les redirections et les liens ne sont pas affich�s.",


# Miscellaneous special pages
#
"orphans"       => "Pages orphelines",
"lonelypages"   => "Pages orphelines",
"unusedimages"  => "Images orphelines",
"popularpages"  => "Pages les plus consult�es",
"nviews"        => "$1 consultations",
"wantedpages"   => "Pages les plus demand�es",
"nlinks"        => "$1 r�f�rences",
"allpages"      => "Toutes les pages",
"randompage"    => "Une page au hasard",
"shortpages"    => "Articles courts",
"longpages"     => "Articles longs",
"listusers"     => "Liste des participants",
"specialpages"  => "Pages sp�ciales",
"spheading"     => "Pages sp�ciales",
"sysopspheading" => "Pages sp�ciales � l'usage des administrateurs",
"developerspheading" => "Pages sp�ciales � l'usage des d�veloppeurs",
"protectpage"   => "Prot�ger la page",
"recentchangeslinked" => "Suivi des liens",
"rclsub"        => "(des pages li�es � \"$1\")",
"debug"         => "D�boguer",
"newpages"      => "Nouvelles pages",
"ancientpages"	=> "Articles les plus anciens",
"movethispage"  => "D�placer la page",
"unusedimagestext" => "<p>N'oubliez pas que d'autres sites, comme certains Wikip�dias non francophones, peuvent contenir un lien direct vers cette image, et que celle-ci peut �tre plac�e dans cette liste alors qu'elle est en r�alit� utilis�e.",
"booksources"   => "Ouvrages de r�f�rence",
"booksourcetext" => "Voici une liste de liens vers d'autres sites qui vendent des livres neufs et d'occasion et sur lesquels vous trouverez peut-�tre des informations sur les ouvrages que vous cherchez. Wikip�dia n'�tant li�e � aucune de ces soci�t�s, elle n'a aucunement l'intention d'en faire la promotion.",
"alphaindexline" => "$1 � $2",

# Email this user
#
"mailnologin" => "Pas d'adresse",
"mailnologintext" => "Vous devez �tre <a href=\"" .
		wfLocalUrl( "Special:Userlogin" ) . "\">connect�</a>
et avoir indiqu� une adresse �lectronique valide dans vos <a href=\"" .
		wfLocalUrl( "Special:Preferences" ) . "\">pr�f�rences</a>
pour pouvoir envoyer un message � un autre utilisateur.",
"emailuser"  => "Envoyer un message � cet utilisateur",
"emailpage"  => "Email user",
"emailpagetext" => "Si cet utilisateur a indiqu� une adresse �lectronique valide dans ses pr�f�rences, le formulaire ci-dessous lui enverra un message.
L'adresse �lectronique que vous avez indiqu�e dans vos pr�f�rences appara�tra dans le champ \"Exp�diteur\" de votre message, afin que le destinataire puisse vous r�pondre.",
"noemailtitle" => "Pas d'adresse �lectronique",
"noemailtext" => "Cet utilisateur n'a pas sp�cifi� d'adresse �lectronique valide ou a choisi de ne pas recevoir de courrier �lectronique des autres utilisateurs.",

"emailfrom"  => "Exp�diteur",
"emailto"  => "Destinataire",
"emailsubject" => "Objet",
"emailmessage" => "Message",
"emailsend"  => "Envoyer",
"emailsent"  => "Message envoy�",
"emailsenttext" => "Votre message a �t� envoy�.",

# Watchlist
#
"watchlist"	=> "Liste de suivi",
"watchlistsub"	=> "(pour l'utilisateur \"$1\")",
"nowatchlist"	=> "Votre liste de suivi ne contient aucun article.",
"watchnologin"	=> "Non connect�",
"watchnologintext" => "Vous devez �tre <a href=\"" .
		wfLocalUrl( "Special:Userlogin" ) . "\">connect�</a>
pour modifier votre liste.",
"addedwatch"	=> "Ajout� � la liste",
"addedwatchtext" => "La page \"$1\" a �t� ajout�e � votre <a href=\"" .
		wfLocalUrl( "Special:Watchlist" ) . "\">liste de suivi</a>.
Les prochaines modifications de cette page et de la page discussion associ�e seront r�pertori�es ici, et la page appara�tra <b>en gras</b> dans la <a href=\"" .
		wfLocalUrl( "Special:Recentchanges" ) . "\">liste des modifications r�centes</a> pour �tre rep�r�e plus facilement.</p>

<p>Pour supprimer cette page de votre liste de suivi, cliquez sur \"Ne plus suivre\" dans le cadre de navigation.",
"removedwatch"	=> "Supprim�e de la liste de suivi",
"removedwatchtext" => "La page \"$1\" a �t� supprim�e de votre liste de suivi.",
"watchthispage"	=> "Suivre cette page",
"unwatchthispage" => "Ne plus suivre",
"notanarticle"	=> "Aucun article",
"watchnochange" => "Aucune des pages que vous suivez n'a �t� modifi�e pendant la p�riode affich�e",
// "watchdetails" => "($1 pages suivies, sans compter les pages de discussion; $2 pages en total modifi�es depuis la limite; $3...  <a href='$4'>afficher et modifier la liste compl�te</a>.)", // Looxix 
"watchdetails" => "Vous suivez $1 pages, sans compter les pages de discussion.  <a href='$4'>Afficher et modifier la liste compl�te</a>.", // Looxix 
"watchmethod-recent" => "v�rification des modifications r�centes des pages suivies", // Looxix 
"watchmethod-list" => "v�rification des pages suivies pour des modifications r�centes", // Looxix 
"removechecked" => "Retirer de la liste de suivi les articles s�lectionn�s",
"watchlistcontains" => "Votre liste de suivi contient $1 pages",
"watcheditlist" => "Ceci est votre liste de suivi par ordre alphab�tique. S�lectionnez les pages que vous souhaitez retirer de la liste et cliquez le bouton \"retirer de la liste de suivi\" en bas de l'�cran.",
"removingchecked" => "Les articles s�lectionn�s sont retir�s de votre liste de suivi...",
"couldntremove" => "Impossible de retirer l'article '$1'...",
"iteminvalidname" => "Probl�me avec l'article '$1': le nom est invalide...",
"wlnote" => "Ci-dessous se trouve les $1 derni�res modifications depuis les <b>$2</b> derni�res heures.", // Looxix 


# Delete/protect/revert
#
"deletepage"	=> "Supprimer une page",
"confirm"	=> "Confirmer",
"excontent"	=> "contenant",
"exbeforeblank" => "le contenu avant effacement �tait :",
"exblank"	=> "page vide",
"confirmdelete" => "Confirmer la suppression",
"deletesub"	=> "(Suppression de \"$1\")",
"historywarning" => "Attention: La page que vous �tes sur le point de supprimer � un historique: ",
"confirmdeletetext" => "Vous �tes sur le point de supprimer d�finitivement de la base de donn�es une page
ou une image, ainsi que toutes ses versions ant�rieures.
Veuillez confirmer que c'est bien l� ce que vous voulez faire, que vous en comprenez les cons�quences et que vous faites cela en accord avec les [[Wikip�dia:Recommandations Et R�gles �  Suivre|recommandations et r�gles � suivre]].",
"confirmcheck"	=> "Oui, je confirme la suppression.",
"actioncomplete" => "Suppression effectu�e",
"deletedtext"	=> "\"$1\" a �t� supprim�.
Voir $2 pour une liste des suppressions r�centes.",
"deletedarticle" => "effacement de \"$1\"",
"dellogpage"	=> "Historique des effacements",
"dellogpagetext" => "Voici la liste des suppressions r�centes.
L'heure indiqu�e est celle du serveur (UTC).
<ul>
</ul>
",
"deletionlog"	=> "historique des effacements",
"reverted"	=> "R�tablissement de la version pr�c�dente",
"deletecomment" => "Motif de la suppression",
"imagereverted" => "La version pr�c�dente a �t� r�tablie.",
"rollback"	=> "r�voquer modifications",
"rollbacklink"	=> "r�voquer",
"rollbackfailed" => "La r�vocation a �chou�",
"cantrollback"	=> "Impossible de r�voquer: dernier auteur est le seul � avoir modifi� cet article",
"alreadyrolled"	=> "Impossible de r�voquer la derni�re modification de [[$1]]
par  [[User:$2|$2]] ([[User talk:$2|Talk]]); quelqu'un d'autre � d�j� modifer ou r�voquer l'article. 

La derni�re modificaion �tait de [[User:$3|$3]] ([[User talk:$3|Talk]]). ", //Looxix 
#   only shown if there is an edit comment
"editcomment" => "Le r�sum� de la modification �tait: \"<i>$1</i>\".", //Looxix 
"revertpage"	=> "restitution de la derni�re modification de $1",
"protectlogpage" => "Log_de_protection",
"protectlogtext" => "Voir les [[Wikip�dia:Page prot�g�e|directives concernant les pages prot�g�es]].",
"protectedarticle" => "a prot�g�e [[$1]]",
"unprotectedarticle" => "a d�prot�g� [[$1]]",

# Undelete
#
"undelete"	=> "Restaurer la page effac�e",
"undeletepage"	=> "Voir et restaurer la page effac�e",
"undeletepagetext" => "Ces pages ont �t� effac�es et se trouvent dans la corbeille, elles sont toujours dans la base de donn�e et peuvent �tre restaur�es.
La corbeille peut �tre effac�e p�riodiquement.",

"undeletearticle" => "Restaurer les articles effac�s",
"undeleterevisions" => "$1 r�visions archiv�es", // Looxix "$1 revisions archived",
"undeletehistory" => "Si vous restaurez la page, toutes les r�visions seront restaur�es dans l'historique.
Si une nouvelle page avec le m�me nom a �t� cr�e depuis la suppression,
les r�visions restaur�es appara�tront dans l'historique ant�rieur et la version courante ne sera pas automatiquement remplac�e.",
"undeleterevision" => "Version effac�e ($1)", // Looxix "Deleted revision as of $1",	
"undeletebtn"	=> "Restaurer!",
"undeletedarticle" => "restaur� \"$1\"",	// FvdP "restored \"$1\""
"undeletedtext"   => "L'article [[$1]] a �t� restaur� avec succ�s.
Voir [[Wikipedia:Trace des effacements]] pour la liste des suppressions et des restaurations r�centes.",
# Contributions
#
"contributions"	=> "Contributions",
"mycontris"	=> "Mes contributions",
"contribsub"	=> "Pour $1",
"nocontribs"	=> "Aucune modification correspondant � ces crit�res n'a �t� trouv�e.",
"ucnote"	=> "Voici les <b>$1</b> derni�res modifications effectu�es par cet utilisateur au cours des <b>$2</b> derniers jours.",
"uclinks"	=> "Afficher les $1 derni�res modifications; afficher les $2 derniers jours.",
"uctop"		=> " (derni�re)",	// FvdP " (top)"

# What links here
#
"whatlinkshere" => "Pages li�es",
"notargettitle" => "Pas de cible",
"notargettext"	=> "Indiquez une page cible ou un utilisateur cible.",
"linklistsub"	=> "(Liste de liens)",
"linkshere"	=> "Les pages ci-dessous contiennent un lien vers celle-ci:",
"nolinkshere"	=> "Aucune page ne contient de lien vers celle-ci.",
"isredirect"	=> "page de redirection",

# Block/unblock IP
#
"blockip"	=> "Bloquer une adresse IP",
"blockiptext"	=> "Utilisez le formulaire ci-dessous pour bloquer l'acc�s en �criture � partir d'une adresse IP donn�e.
Une telle mesure ne doit �tre prise que pour emp�cher le vandalisme et en accord avec [[Wikip�dia:Recommandations et r�gles � suivre|recommandations et r�gles � suivre]].
Donnez ci-dessous une raison pr�cise (par exemple en indiquant les pages qui ont �t� vandalis�es).",
"ipaddress"	=> "Adresse IP",
"ipbreason"	=> "Motif",
"ipbsubmit"	=> "Bloquer cette adresse",
"badipaddress"	=> "L'adresse IP n'est pas correcte.",
"noblockreason" => "Vous devez indiquer le motif du blocage.",
"blockipsuccesssub" => "Blocage r�ussi",
"blockipsuccesstext" => "L'adresse IP \"$1\" a �t� bloqu�e.
<br>Vous pouvez consulter sur cette [[Special:Ipblocklist|page]] la liste des adresses IP bloqu�es.",
"unblockip"	=> "D�bloquer une adresse IP",
"unblockiptext" => "Utilisez le formulaire ci-dessous pour r�tablir l'acc�s en �criture
� partir d'une adresse IP pr�c�demment bloqu�e.",
"ipusubmit"	=> "D�bloquer cette adresse",
"ipusuccess"	=> "Adresse IP \"$1\" d�bloqu�e",
"ipblocklist"	=> "Liste des adresses IP bloqu�es",
"blocklistline" => "$1, $2 a bloqu� $3",
"blocklink"	=> "bloquer",
"unblocklink"	=> "d�bloquer",
"contribslink"	=> "contribs",
"autoblocker"	=> "Autobloqu� parce que vous partagez une adresse IP avec \"$1\". Raison : \"$2\".",
"blocklogpage"	=> "Log de blocage",
"blocklogentry"	=> 'blocage de "$1"',
"blocklogtext"	=> "Ceci est la trace des blocages et d�blocages des utilisateurs. Les adresses IP automatiquement bloqu�es ne sont pas list�es. Consultez la [[Special:Ipblocklist|liste des utilisateurs bloqu�s]] pour voir qui est actuellement effectivement bloqu�.",
"unblocklogentry"	=> 'd�blocage de "$1"',


# Developer tools
#
"lockdb"  => "Verrouiller la base",
"unlockdb"  => "D�verrouiller la base",
"lockdbtext" => "Le verrouillage de la base de donn�es emp�chera tous les utilisateurs de modifier des pages, de sauvegarder leurs pr�f�rences, de modifier leur liste de suivi et d'effectuer toutes les autres op�rations n�cessitant des modifications dans la base de donn�es.
Veuillez confirmer que c'est bien l� ce que vous voulez faire et que vous d�bloquerez la base d�s que votre op�ration de maintenance sera termin�e.",
"unlockdbtext" => "Le d�verrouillage de la base de donn�es permettra � nouveau � tous les utilisateurs de modifier des pages, de mettre � jour leurs pr�f�rences et leur liste de suivi, ainsi que d'effectuer les autres op�rations n�cessitant des modifications dans la base de donn�es.
Veuillez confirmer que c'est bien l� ce que vous voulez faire.",
"lockconfirm" => "Oui, je confirme que je souhaite verrouiller la base de donn�es.",
"unlockconfirm" => "Oui, je confirme que je souhaite d�verrouiller la base de donn�es.",

"lockbtn"  => "Verrouiller la base",
"unlockbtn"  => "D�verrouiller la base",
"locknoconfirm" => "Vous n'avez pas coch� la case de confirmation.",
"lockdbsuccesssub" => "Verrouillage de la base r�ussi.",
"unlockdbsuccesssub" => "Base d�verrouill�e.",
"lockdbsuccesstext" => "La base de donn�es de Wikip�dia est verrouill�e.

<br>N'oubliez pas de la d�verrouiller lorsque vous aurez termin� votre op�ration de maintenance.",
"unlockdbsuccesstext" => "La base de donn�es de Wikip�dia est d�verrouill�e.",

# SQL query
#
"asksql"	=> "Requ�te SQL",
"asksqltext"	=> "Utilisez le formulaire ci-dessous pour faire une requ�te directe sur la base de donn�es de Wikip�dia.
Utilisez des guillemets simples ('comme ceci') pour d�limiter les cha�nes de caract�res.
Cette op�ration peut surcharger consid�rablement le serveur, faites en usage
avec mod�ration.",
"sqlislogged"	=> "Veillez noter que toutes les requ�tes sont logu�es", // Looxix "Please note that all queries are logged.",	
"sqlquery"	 => "Saisir la requ�te",

"querybtn"	=> "Envoyer la requ�te",
"selectonly"	=> "Les requ�tes autres que \"SELECT\" sont r�serv�es aux d�veloppeurs de
Wikip�dia.",
"querysuccessful" => "Requ�te r�ussie",

# Move page
#
"movepage"  => "D�placer un article",
"movepagetext" => "Utilisez le formulaire ci-dessous pour renommer un article, en d�pla�ant toutes ses versions ant�rieures vers le nouveau nom.
Le titre pr�c�dent deviendra une page de redirection vers le nouveau titre.
Les liens vers l'ancien titre ne seront pas modifi�s et la page discussion, si elle existe, ne sera pas d�plac�e.<br>
<b>ATTENTION!</b>
Il peut s'agir d'un changement radical et inattendu pour un article souvent consult�;
assurez-vous que vous en comprenez bien les cons�quences avant de proc�der.",
"movepagetalktext" => "La page de discussion associ�, si pr�sente, sera automatiquement d�plac�e avec '''sauf si:'''
*Vous d�placez une page vers un autre espace,
*Une page de discussion existe d�j� avec le nouveau nom, ou
*Vous avez d�s�lectionn� le bouton ci-dessous.

Dans ce cas, vous devrez d�placer ou fusionner la page manuellement si vous le d�sirez.",

"movearticle"	=> "D�placer l'article",
"movenologin"	=> "Non connect�",
"movenologintext" => "Pour pouvoir d�placer un article, vous devez �tre <a href=\"" .
		wfLocalUrl( "Special:Userlogin" ) . "\">connect�</a>
en tant qu'utilisateur enregistr�.",
"newtitle"	=> "Nouveau titre",
"movepagebtn"	=> "D�placer l'article",
"pagemovedsub" => "D�placement r�ussi",
"pagemovedtext" => "L'article \"[[$1]]\" a �t� d�plac� vers \"[[$2]]\".",
"articleexists" => "Il existe d�j� un article portant ce titre, ou le titre que vous avez choisi n'est pas valide.
Veuillez en choisir un autre.",
"talkexists"	=> "La page elle-m�me a �t� d�plac�e avec succ�s, mais
la page de discussion n'a pas pu �tre d�plac�e car il en existait d�j� une
sous le nouveau nom. S'il vous plait, fusionnez les manuellement.",

"movedto"  => "d�plac� vers",
"movetalk"  => "D�placer aussi la page \"discussion\", s'il y a lieu.",
"talkpagemoved" => "La page discussion correspondante a �galement �t� d�plac�e.",
"talkpagenotmoved" => "La page discussion correspondante n'a <strong>pas</strong> �t� d�plac�e.",

"export"	=> "Exporter des pages",
"exporttext"	=> "Vous pouvez exporter en XML le texte et l'historique d'une page ou d'un ensemble de pages; le r�sultat peut alores �tre import� dans un autre wiki fonctionnant avec le logiciel MediaWiki, transform� ou sauvegard� pour votre usage personnel.",
"exportcuronly"	=> "Exporter uniquement la version courante sans l'historique",

# Namespace 8 related

"allmessages"	=> "Tous les messages",
"allmessagestext"	=> "Ceci est la liste de tous les messages disponibles dans l'espace MediaWiki"
);

class LanguageFr extends Language
{

	function getDefaultUserOptions()
	{
	        $opt = Language::getDefaultUserOptions();
                return $opt;
	}

        function getBookstoreList () {
                global $wgBookstoreListFr ;
                return $wgBookstoreListFr ;
        }

	function getNamespaces()
	{
		global $wgNamespaceNamesFr;
		return $wgNamespaceNamesFr;
	}


	function getNsText( $index )
	{
		global $wgNamespaceNamesFr;
		return $wgNamespaceNamesFr[$index];
	}

	function getNsIndex( $text ) 
	{
		global $wgNamespaceNamesFr;

		foreach ( $wgNamespaceNamesFr as $i => $n ) 
		{
			if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
		}
		if( 0 == strcasecmp( "Wikipedia", $text ) ) return 4;
		if( 0 == strcasecmp( "Discussion_Wikipedia", $text ) ) return 5;
		return false;
	}

	function specialPage( $name ) 
	{
		return $this->getNsText( Namespace::getSpecial() ) . ":" . $name;
	}

	function getQuickbarSettings() 
	{
		global $wgQuickbarSettingsFr;
		return $wgQuickbarSettingsFr;
	}

	function getSkinNames()
	{
		global $wgSkinNamesFr;
		return $wgSkinNamesFr;
	}

	function getMathNames() {
		global $wgMathNamesFr;
		return $wgMathNamesFr;
	}


	function getUserToggles()
	{
		global $wgUserTogglesFr;
		return $wgUserTogglesFr;
	}

	function getMonthName( $key )
	{
		global $wgMonthNamesFr;
		return $wgMonthNamesFr[$key-1];
	}

	function getMonthAbbreviation( $key )
	{
		global $wgMonthAbbreviationsFr;
		return $wgMonthAbbreviationsFr[$key-1];
	}

	function getWeekdayName( $key )
	{
		global $wgWeekdayNamesFr;
		return $wgWeekdayNamesFr[$key-1];
	}

	// Inherit userAdjust()

	function date( $ts, $adj = false )
	{
		if ( $adj ) { $ts = $this->userAdjust( $ts ); }

		$d = (0 + substr( $ts, 6, 2 )) . " " .
		  $this->getMonthAbbreviation( substr( $ts, 4, 2 ) ) .
		  " " . substr( $ts, 0, 4 );
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
		return $this->date( $ts, $adj ) . " � " . $this->time( $ts, $adj );
	}

	function getValidSpecialPages()
	{
		global $wgValidSpecialPagesFr;
		return $wgValidSpecialPagesFr;
	}

	function getSysopSpecialPages()
	{
		global $wgSysopSpecialPagesFr;
		return $wgSysopSpecialPagesFr;
	}

	function getDeveloperSpecialPages()
	{
		global $wgDeveloperSpecialPagesFr;
		return $wgDeveloperSpecialPagesFr;
	}

	function getMessage( $key )
	{
		global $wgAllMessagesFr, $wgAllMessagesEn;
		$m = $wgAllMessagesFr[$key];

		if ( "" == $m ) { return $wgAllMessagesEn[$key]; }
		else return $m;

	}
}

?>

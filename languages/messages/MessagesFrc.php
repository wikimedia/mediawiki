<?php
/** Cajun French (français cadien)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Ebe123
 * @author JeanVoisin
 * @author Peter17
 * @author PieRRoMaN
 * @author RoyAlcatraz
 * @author Urhixidur
 * @author Zetud
 */

$fallback = 'fr';

$messages = array(
# User preference toggles
'tog-underline' => 'Souligner les liens:',
'tog-justify' => 'Égalisez les paragraphes',
'tog-hideminor' => 'Cachez les petits changements dans la liste des derniers changements',
'tog-hidepatrolled' => '↓ Cachez les petits changements dans la liste des derniers changements',
'tog-newpageshidepatrolled' => '↓Cache pages patrollés de la list des pages nouveau',
'tog-extendwatchlist' => 'Agrandir la liste des pages guettées pour montrer tous les changements',
'tog-usenewrc' => 'User les derniers changements improuvés (JavaScript)',
'tog-numberheadings' => 'Mettre les numéros sus les en-têtes',
'tog-showtoolbar' => "Montrer la barre d'outils des changements (JavaScript)",
'tog-editondblclick' => 'Faire un clic-double pour changer la page (JavaScript)',
'tog-editsection' => 'Changer une section avec les liens [changer]',
'tog-editsectiononrightclick' => 'Changer une section en faisant un clic droit sus son nom (JavaScript)',
'tog-showtoc' => 'Montrer la table des matières (pour les pages avec plus que 3 têtes)',
'tog-rememberpassword' => 'Garder mon mot de passe (for a maximum of $1 {{PLURAL:$1|day|days}})',
'tog-watchcreations' => 'Additionner les pages que je crée à ma liste des pages guettées',
'tog-watchdefault' => 'Additionner les pages que je change à ma liste des pages guettées',
'tog-watchmoves' => 'Additionner les pages que je renomme à ma liste des pages guettées',
'tog-watchdeletion' => "Additionner les pages que j'ôte à ma liste des pages guettées",
'tog-minordefault' => "Marquer tous les petits changements d'après le réglage",
'tog-previewontop' => "Montrer la vue d'avance au-dessus la boëte de changement",
'tog-previewonfirst' => "Montrer la vue d'avance au temps du premier changement",
'tog-nocache' => 'Arrêter le cache des pages',
'tog-enotifwatchlistpages' => 'Envoyer un e-mail quand une de mes pages guettées est changée',
'tog-enotifusertalkpages' => 'Envoyer un e-mail quand ma page de discussion est changée',
'tog-enotifminoredits' => 'Envoyer un e-mail même pour les petits changements',
'tog-enotifrevealaddr' => "Montrer mon adresse e-mail dans les e-mails d'avertissement",
'tog-shownumberswatching' => 'Montrer le montant de guetteurs',
'tog-oldsig' => '↓ Preview du signature existant:',
'tog-fancysig' => 'Signature brute (sans liens préparés)',
'tog-externaleditor' => 'Utiliser par défaut un éditeur de texte externe (pour les utilisateurs avancés, nécessite des réglages spécifiques sur votre ordinateur)',
'tog-externaldiff' => 'User un autre comparateur comme réglage ordinaire',
'tog-showjumplinks' => 'Mettre les "liens vites" en haut de la page',
'tog-uselivepreview' => "User la vue d'avance vite (JavaScript) (En assai)",
'tog-forceeditsummary' => "M'avertir quand j'ai laissé la boëte de commentaires vide",
'tog-watchlisthideown' => 'Cacher mes changements dans la liste des pages guettées',
'tog-watchlisthidebots' => 'Cacher les changements faits par les bots dans la liste des pages guettées',
'tog-watchlisthideminor' => 'Cacher les petits changements dans la liste des pages guettées',
'tog-watchlisthideliu' => 'Cacher édites de useurs anonymes du liste de pages guettées',
'tog-watchlisthideanons' => 'Cacher édites de useurs anonymes du liste de pages guettées',
'tog-watchlisthidepatrolled' => 'Cacher les changements faits par les bots dans la liste des pages guettées',
'tog-ccmeonemails' => "Envoyer une copie des e-mails que j'envoye aux autres useurs",
'tog-diffonly' => 'Couper la page sous les diffs',

'underline-always' => 'Tout le temps',
'underline-never' => 'Jamais',
'underline-default' => "D'après le réglage du navigateur",

# Dates
'sunday' => 'dimanche',
'monday' => 'lundi',
'tuesday' => 'mardi',
'wednesday' => 'mercredi',
'thursday' => 'jeudi',
'friday' => 'vendredi',
'saturday' => 'samedi',
'sun' => 'dim',
'mon' => 'lun',
'tue' => 'mar',
'wed' => 'mer',
'thu' => 'jeu',
'fri' => 'ven',
'sat' => 'sam',
'january' => 'de janvier',
'february' => 'de février',
'march' => 'de mars',
'april' => "d'avril",
'may_long' => 'de mai',
'june' => 'de juin',
'july' => 'de juliette',
'august' => "d'août",
'september' => 'de septembre',
'october' => "d'octobre",
'november' => 'de novembre',
'december' => 'de décembre',
'january-gen' => 'janvier',
'february-gen' => 'février',
'march-gen' => 'mars',
'april-gen' => 'avril',
'may-gen' => 'mai',
'june-gen' => 'juin',
'july-gen' => 'juliette',
'august-gen' => 'août',
'september-gen' => 'septembre',
'october-gen' => 'octobre',
'november-gen' => 'novembre',
'december-gen' => 'décembre',
'jan' => 'jan',
'feb' => 'fév',
'mar' => 'mar',
'apr' => 'avr',
'may' => 'mai',
'jun' => 'jui',
'jul' => 'jul',
'aug' => 'aoû',
'sep' => 'sep',
'oct' => 'oct',
'nov' => 'nov',
'dec' => 'déc',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Classe|Classes}}',
'category_header' => 'Articles dans classe "$1"',
'subcategories' => 'Sous-classes',
'category-media-header' => 'Média dans classe "$1"',
'category-empty' => "''À présent, cette classe a ni articles ni média.''",

'about' => 'Info',
'article' => 'Page des matières',
'newwindow' => '(va ouverre une nouvelle fenêtre)',
'cancel' => 'Arrêter',
'moredotdotdot' => 'Plus...',
'mypage' => 'Ma page',
'mytalk' => 'Ma page de discussion',
'anontalk' => 'Discussion avec cette adresse IP',
'navigation' => 'Navigation',

# Cologne Blue skin
'qbfind' => 'Charcher',
'qbbrowse' => 'Regarder',
'qbedit' => 'Changer',
'qbpageoptions' => 'Page de choix',
'qbpageinfo' => "Page d'information",
'qbmyoptions' => 'Mes options',
'qbspecialpages' => 'Pages espéciales',
'faq' => 'Questions Communes',
'faqpage' => 'Project:Questions Communes',

# Vector skin
'vector-action-delete' => 'Supprimer',
'vector-action-protect' => 'Protéger',

'errorpagetitle' => 'Erreur',
'returnto' => 'Retourner back à la page $1.',
'tagline' => 'Un article de {{SITENAME}}',
'help' => 'Aide',
'search' => 'Charcher',
'searchbutton' => 'Charcher',
'go' => 'Aller',
'searcharticle' => 'Aller',
'history' => 'Changements',
'history_short' => 'Changements',
'updatedmarker' => 'changé depuis ma dernière visite',
'printableversion' => 'Version imprimable',
'permalink' => 'Lien permanent',
'print' => 'Imprimer',
'edit' => 'Changer',
'editthispage' => 'Faire un changement',
'delete' => 'Supprimer',
'deletethispage' => 'Supprimer cette page',
'undelete_short' => 'Rétablir {{PLURAL:$1|un changement|$1 changements}}',
'protect' => 'Protéger',
'protect_change' => 'Changer le niveau de protection',
'protectthispage' => 'Protéger cette page',
'unprotect' => 'déprotéger',
'unprotectthispage' => 'Déprotéger cette page',
'newpage' => 'Nouvelle page',
'talkpage' => 'Discuter cette page',
'talkpagelinktext' => 'Discuter',
'specialpage' => 'Page espéciale',
'personaltools' => 'Outils personnels',
'postcomment' => 'Nouvelle section',
'articlepage' => "Voir l'article",
'talk' => 'Discussion',
'views' => 'Vues',
'toolbox' => "Boëte d'outils",
'userpage' => "Page d'useur",
'projectpage' => 'Page des projets',
'imagepage' => 'Page des images',
'mediawikipage' => 'Page des messages',
'templatepage' => 'Page de patron',
'viewhelppage' => "Page d'aide",
'categorypage' => 'Page des classes',
'viewtalkpage' => 'Page de discussion',
'otherlanguages' => 'Autres langues',
'redirectedfrom' => '(Envoyé ici de la page $1)',
'redirectpagesub' => 'Page de redirection',
'lastmodifiedat' => 'Cette page a été changée le $1 à $2.',
'viewcount' => 'Cette page a été visitée {{PLURAL:$1|$1 fois|$1 fois}}.',
'protectedpage' => 'Page protégée',
'jumpto' => 'Aller à:',
'jumptosearch' => 'charcher',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite' => "Qui c'est {{SITENAME}}?",
'aboutpage' => 'Project:Info',
'copyright' => 'Les matières sont avalable en accord avec $1',
'copyrightpage' => '{{ns:project}}:Protection de Droits',
'currentevents' => 'Événements',
'currentevents-url' => 'Project:Événements',
'disclaimers' => 'Avertissements',
'disclaimerpage' => 'Project:Avertissements ordinaires',
'edithelp' => 'Aide',
'edithelppage' => 'Help:Comment changer une page',
'helppage' => 'Help:Aide',
'mainpage' => 'Page Principale',
'mainpage-description' => 'Page Principale',
'policy-url' => 'Project:Régulations',
'portal' => 'Communauté',
'portal-url' => 'Project:Communauté',
'privacy' => 'Régulations des affaires privées',
'privacypage' => 'Project:Régulations des affaires privées',

'badaccess' => 'Erreur de permission',
'badaccess-group0' => 'Vous avez pas assez de permission de faire ça que vous demandez.',
'badaccess-groups' => "L'action que vous avez demandé est juste pour les useurs dans une des groupes $1.",

'versionrequired' => 'Vous avez besoin de la version $1 de MediaWiki.',
'versionrequiredtext' => 'Vous avez besoin de la version $1 de MediaWiki pour utiliser cette page. Voir [[Special:Version]].',

'retrievedfrom' => 'Pris de "$1"',
'youhavenewmessages' => 'Vous avez $1 ($2).',
'newmessageslink' => 'nouveaux messages',
'newmessagesdifflink' => 'dernier changement',
'youhavenewmessagesmulti' => 'Vous avez des nouveaux messages sus $1.',
'editsection' => 'changer',
'editold' => 'changer',
'viewsourcelink' => 'Voir la source',
'editsectionhint' => 'Changer la section: $1',
'toc' => 'Matières',
'showtoc' => 'montrer',
'hidetoc' => 'cacher',
'thisisdeleted' => 'Vous aimerait mieux voir ou rétablir $1?',
'viewdeleted' => 'Voir $1?',
'restorelink' => '{{PLURAL:$1|1 changement ôté|$1 changements ôtés}}',
'feedlinks' => 'Distribution RSS:',
'feed-invalid' => 'Mauvaise qualité de distribution RSS.',
'red-link-title' => "$1 (page n'existe pas)",

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Page',
'nstab-user' => 'Useur',
'nstab-media' => 'Média',
'nstab-special' => 'Page espécial',
'nstab-project' => 'Info',
'nstab-image' => 'Dossier',
'nstab-template' => 'Patron',
'nstab-help' => 'Aide',
'nstab-category' => 'Classe',

# Main script and global functions
'nosuchaction' => 'Action inconnue',
'nosuchactiontext' => "L'action demandée dans l'adresse URL est pas reconnue par le wiki.",
'nosuchspecialpage' => 'Page espéciale inconnue',
'nospecialpagetext' => 'La page que vous avez demandée est pas une bonne page espéciale.  Vous pouvez trouver une liste des bonnes pages espéciales dans la [[Special:SpecialPages|liste des pages espéciales]].',

# General errors
'error' => 'Erreur',
'databaseerror' => "Erreur de la base d'information",
'dberrortext' => 'Erreur de syntaxe dans la base d\'information.

Ça pourrait vouloir dire qu\'y a une imperfection dans le software.<br />
La dernière demande faite dans la base d\'information était:
<blockquote><tt>$1</tt></blockquote>
dedans la fonction "<tt>$2</tt>".<br />
MySQL a retourné l\'erreur "<tt>$3: $4</tt>".',
'dberrortextcl' => 'Erreur de syntaxe dans la base d\'information.

La dernière demande faite dans la base d\'information était:
"$1"
dedans la fonction "$2".
MySQL a retourné l\'erreur "$3: $4".',
'laggedslavemode' => 'Avertissement: La page est peut-être pas renouvelée.',
'readonly' => "La base d'information est barrée.",
'enterlockreason' => "Écrire une raison pour le barrage avec un temps estimé
d'équand le barrage va être ôté.",
'readonlytext' => "À présent, la base d'information est barrée aux nouveaux changements, peut-être pour le soutien habituel de la base d'information, et ça va retourner à normal t'à l'heure.

L'administrateur qui l'a barrée a donné cette explication: $1",
'readonly_lag' => "La base d'information s'a barré pendant que les serveurs de la base d'information rapprochont le maître.",
'internalerror' => 'Erreur internelle',
'internalerror_info' => 'Erreur intérieur: $1',
'filecopyerror' => 'Impossible de copier le dossier de "$1" à "$2".',
'filerenameerror' => 'Impossible de renommer le dossier de "$1" à "$2".',
'filedeleteerror' => 'Impossible d\'ôter le dossier "$1".',
'directorycreateerror' => 'Impossible de créer le directoire "$1".',
'filenotfound' => 'Impossible de trouver le dossier "$1".',
'fileexistserror' => 'Impossible d\'écrire dans le dossier "$1": le dossier est là',
'unexpected' => 'Valeur pas prévue: "$1"="$2".',
'formerror' => "Erreur: impossible d'envoyer l'application",
'badarticleerror' => 'Cette action peut pas être faite sus cette page.',
'cannotdelete' => "Impossible d'ôter la page ou le dossier choisi.  (Il est possible que quèqu'une d'autre l'a déjà ôté.)",
'badtitle' => 'Mauvais titre',
'badtitletext' => 'Le titre que vous avez demandé était pas bon, vide, ou y avait un mauvais titre entre-langue ou entre-wiki.  Ça pourrait avoir des caractères qui pouvont pas être usés dans les titres.',
'perfcached' => 'Cette information est en cache et pourrait pas être courante. A maximum of {{PLURAL:$1|one result is|$1 results are}} available in the cache.',
'perfcachedts' => 'Cette information est en cache et le dernier changement a été fait $1. A maximum of {{PLURAL:$4|one result is|$4 results are}} available in the cache.',
'querypage-no-updates' => "À présent, les renouvelages pour cette page sont barrés.  L'information ici va pas être renouvelée t'à l'heure.",
'wrong_wfQuery_params' => 'Informaton incorrecte sus le wfQuery()<br />
Fonction: $1<br />
Demande: $2',
'viewsource' => 'Voir la source',
'protectedpagetext' => 'Cette page est barrée pour empêcher des changements.',
'viewsourcetext' => 'Vous pouvez voir et copier la source de cette page:',
'protectedinterface' => "Cette page crée le texte de l'interface pour le software, et est barrée pour empêcher l'abus.",
'editinginterface' => "'''Warning:''' You are editing a page which is used to provide interface text for the software.
Changes to this page will affect the appearance of the user interface for other users.
For translations, please consider using [//translatewiki.net/wiki/Main_Page?setlang=en translatewiki.net], the MediaWiki localisation project.",
'sqlhidden' => '(Demande SQL cachée)',
'cascadeprotected' => 'Cette page est protégée parce qu’elle est incluse par {{PLURAL:$1|la page suivante, qui est protégée|les pages suivantes, qui sont protégées}} avec l’option « protection en cascade » activée :
$2',
'namespaceprotected' => 'Vous avez pas la permission de changer les pages dans l\'espace de noms "$1".',
'ns-specialprotected' => "Vous pouvez pas changer les pages dans l'espace de noms {{ns:special}}.",

# Login and logout pages
'logouttext' => "'''Vous êtes déconnecté asteur.'''

Vous pouvez continuer à user {{SITENAME}} sans nom ou vous pouvez connecter encore une fois avec le même nom ou un autre nom.
Notez: certaines pages pourriont être vues comme si vous êtes connecté, jusqu'à vous videz l'information de votre navigateur.",
'welcomecreation' => '== Bonjour, $1! ==

Votre compte a été créé.  Oubliez pas de changer votre réglage sus {{SITENAME}}.',
'yourname' => "Nom d'useur:",
'yourpassword' => 'Mot de passe:',
'yourpasswordagain' => 'Mot de passe encore:',
'remembermypassword' => 'Garder mon mot de passe dans cette browser (pour un maximum of $1 {{PLURAL:$1|jour|jours}})',
'yourdomainname' => 'Votre domaine:',
'externaldberror' => "Soit y avait une erreur avec la base d'information de certification extérieur, soit vous avez pas la permission de renouveler votre compte extérieur.",
'login' => 'Connecter',
'nav-login-createaccount' => 'Connecter / créer un compte',
'loginprompt' => 'Il faut partir les cookies pour connecter à {{SITENAME}}.',
'userlogin' => 'Connecter / créer un compte',
'logout' => 'Déconnecter',
'userlogout' => 'Déconnecter',
'notloggedin' => 'Pas connecté',
'nologin' => "Vous avez pas de compte? '''$1'''.",
'nologinlink' => 'Créez un compte',
'createaccount' => 'Créer un compte',
'gotaccount' => "Vous avez un compte déjà? '''$1'''.",
'gotaccountlink' => 'Connectez',
'userlogin-resetlink' => 'Oublié vôtre détailes de log in?',
'createaccountmail' => 'par e-mail',
'badretype' => 'Les mots de passe que vous avez mis sont pas pareils.',
'userexists' => "Le nom d'useur choisi est déjà usé.  Choissez donc un autre nom.",
'loginerror' => "Erreur d'identification",
'nocookiesnew' => "Votre compte a été créé, mais vous êtes pas connecté.  {{SITENAME}} use les cookies pour connecter les useurs.  Partez les cookies et connectez avec votre nouveau nom d'useur et votre mot de passe, s'il vous plaît.",
'nocookieslogin' => '{{SITENAME}} use les cookies pour connecter les useurs.  Partez donc les cookies et assayez encore.',
'nocookiesfornew' => "Le conte d'useur n'était pas fait, à cause qu'on pouvait pas confirmer la source.  Ensurer que tu as des cookies, reload ce page et essayer encore.",
'noname' => "Vous avez pas mis un bon nom d'useur.",
'loginsuccesstitle' => 'Vous êtes connecté',
'loginsuccess' => "'''Asteur vous êtes connecté à {{SITENAME}} comme \"\$1\".'''",
'nosuchuser' => 'Y a aucun utilisateur avec le nom "$1".  Les noms d\'utilisateur respectent les majuscules et minuscules. Vérifiez l\'orthographe, ou créez un nouveau compte.',
'nosuchusershort' => 'Y a aucun useur avec le nom "$1".  Regardez donc l\'espellage.',
'nouserspecified' => "Il faut mettre un nom d'useur.",
'login-userblocked' => 'Cet useur est blockée.  Log in pas accépté.',
'wrongpassword' => "Mauvais mot de passe.  Assayez encore s'il vous plaît.",
'wrongpasswordempty' => "Le mot de passe était vide.  Assayez encore s'il vous plaît.",
'passwordtooshort' => "Votre mot de passe est soit pas bon ou trop court.  Un mot de passe devrait avoir au moins $1 caractères et être différent de votre nom d'useur.",
'mailmypassword' => 'Envoyer mon mot de passe par e-mail',
'passwordremindertitle' => 'Votre nouveau mot de passe sus {{SITENAME}}',
'passwordremindertext' => 'Quèqu\'une (peut-être vous, de l\'adresse IP $1) a demandé qu\'on vous envoye un nouveau mot de passe pour {{SITENAME}} ($4).  Le mot de passe pour useur "$2" est "$3" asteur.  Vous devrait connecter et changer votre mot de passe asteur.

Si quèqu\'une d\'autre a demandé ce mot de passe ou si vous vous rappelez de votre mot de passe et vous voulez p\'us le changer, vous pouvez ignorer ce message et continuer à user votre vieux mot de passe.',
'noemail' => 'Y a aucune adresse e-mail pour l\'useur "$1".',
'passwordsent' => 'Un nouveau mot de passe a été envoyé à l\'adresse e-mail de l\'useur "$1".  Reconnectez donc après que vous l\'avez reçu.',
'blocked-mailpassword' => "Votre adresse IP est bloquée.  Pour empêcher l'abus, vous pouvez ni faire des changements ni user la fonction de rappel du mot de passe.",
'eauthentsent' => "Un message de confirmation a été envoyé à l'adresse e-mail choisie.  Avant qu'on peuve envoyer des autres e-mails à ce compte, il faut que vous suivez les instructions dans le message pour confirmer que le compte est le vôtre.",
'throttled-mailpassword' => "On a déjà envoyé un e-mail de rappel avec votre mot de passe dans les $1 dernières heures.  Pour empêcher l'abus, juste un seul e-mail de rappel va être envoyé en $1 heures.",
'mailerror' => 'On pouvait pas envoyer votre e-mail: $1',
'acct_creation_throttle_hit' => "Ça fait de la peine, vous avez déjà créé $1 comptes.  Vous pouvez pas en créer d'autres.",
'emailauthenticated' => 'Votre adresse e-mail a été certifiée le $1.',
'emailnotauthenticated' => 'Votre adresse e-mail est pas encore certifiée.  On va pas envoyer des e-mails pour les fonctions suivantes.',
'noemailprefs' => 'Il faut donner une adresse e-mail pour partir ces fonctions.',
'emailconfirmlink' => 'Confirmez votre adresse e-mail',
'invalidemailaddress' => "L'adresse e-mail peut pas être acceptée parce que c'est pas bien écrit.  Mettez donc une bonne adresse ou laissez-lé vide.",
'accountcreated' => 'Compte créé',
'accountcreatedtext' => "Le compte d'useur pour $1 a été créé.",
'loginlanguagelabel' => 'Langue: $1',

# Change password dialog
'resetpass' => 'Rétablir le mot de passe du compte',
'resetpass_announce' => "Pour le moment, vous êtes connecté avec un mot de passe qu'a été envoyé par e-mail.  Pour finir de vous connecter, il faut créer un nouveau mot de passe ici:",
'resetpass_text' => '<!-- Mettez du texte ici -->',
'resetpass_header' => 'Rétablir le mot de passe',
'resetpass_submit' => 'Créer le mot de passe et connecter',
'resetpass_success' => 'Vous avez bien changé votre mot de passe!  On est après vous connecter...',
'resetpass_forbidden' => 'Vous pouvez pas changer votre mot de passe sus ce wiki ici.',

# Edit page toolbar
'bold_sample' => 'Gras',
'bold_tip' => 'Gras',
'italic_sample' => 'Italique',
'italic_tip' => 'Italique',
'link_sample' => 'Nom du lien',
'link_tip' => 'Lien intérieur',
'extlink_sample' => 'http://www.example.com nom du lien',
'extlink_tip' => 'Lien extérieur (Oubliez pas de mettre http:// avant.)',
'headline_sample' => 'Sujet',
'headline_tip' => 'Sujet niveau 2',
'nowiki_sample' => 'Mettez du texte non-préparé ici',
'nowiki_tip' => 'Ignorez le code wiki',
'image_sample' => 'Exemple.jpg',
'image_tip' => 'Mettez un portrait',
'media_sample' => 'Exemple.ogg',
'media_tip' => 'Lien dossier',
'sig_tip' => 'Votre signature avec la date',
'hr_tip' => 'Ligne horizontale (Abusez-lé pas.)',

# Edit pages
'summary' => 'Description:',
'subject' => 'Sujet:',
'minoredit' => "Ça ici, c'est un petit changement.",
'watchthis' => 'Guetter cette page',
'savearticle' => 'Sauver la page',
'preview' => "Vue d'avance",
'showpreview' => "Vue d'avance",
'showlivepreview' => "Vue d'avance vite",
'showdiff' => 'Montrer les changements',
'anoneditwarning' => "'''Attention:''' Vous êtes pas connecté.  Votre adresse IP vas être sauvée dans la liste des changements pour cette page.",
'missingsummary' => "'''Attention:'''  Vous avez pas mis de description.  Si vous cliquez le bouton \"Sauver\" encore, votre changement va être sauvé sans description.",
'missingcommenttext' => 'Mettez donc un commentaire en bas.',
'missingcommentheader' => "'''Attention :''' Vous avez pas mis de sujet pour ce commentaire. Si vous cliquez le bouton \"Sauver\" encore, votre changement va être sauvé sans sujet.",
'summary-preview' => "Vue d'avance de la description:",
'subject-preview' => "Vue d'avance du sujet:",
'blockedtitle' => "L'useur est bloqué",
'blockedtext' => "'''Votre compte d'useur (ou votre adresse IP) est bloqué.'''

Le blocage a été fait par $1.  La raison donnée est ''$2''.

* La date du blocage: $8
* Le blocage va être ôté: $6
* L'useur bloqué: $7

Vous pouvez contacter $1 ou un autre [[{{MediaWiki:Grouppage-sysop}}|administrateur]] pour discuter le blocage.  Vous pouvez pas user la fonction 'envoyer un e-mail à cet useur' hormis que vous avez une adresse e-mail confirmée dans votre [[Special:Preferences|réglage de compte]] et vous avez la permission de l'user.  Votre adresse IP est $3, et le numéro du blocage est #$5.  Mettez donc cette information dans toutes vos demandes.",
'autoblockedtext' => "Le système a bloqué votre adresse IP parce qu'alle a été usée par un autre useur qu'était bloqué par $1.

La raison donnée est: ''$2''

* La date du blocage: $8
* Le blocage va être ôté: $6

Vous pouvez contacter $1 ou un autre [[{{MediaWiki:Grouppage-sysop}}|administrateur]] pour discuter le blocage.

Notez donc que vous pouvez pas user la fonction 'envoyer un e-mail à cet useur' hormis que vous avez une adresse e-mail confirmée dans votre [[Special:Preferences|réglage de compte]] et vous avez la permission de l'user.

Votre numéro de blocage est #$5.  Mettez donc cette information dans toutes vos demandes.",
'whitelistedittext' => 'Il faut $1 pour faire des changements.',
'confirmedittext' => "Il faut confirmer votre adresse e-mail pour faire des changements.  Mettez et confirmez un adresse e-mail dans votre [[Special:Preferences|réglage de compte]], s'il vous plaît.",
'nosuchsectiontitle' => 'Aucune section pareille',
'nosuchsectiontext' => "Vous avez assayé de faire des changements dans une section qu'existe pas.",
'loginreqtitle' => 'Il faut connecter.',
'loginreqlink' => 'connecter',
'loginreqpagetext' => 'Il faut $1 pour voir des autres pages.',
'accmailtitle' => 'Mot de passe envoyé.',
'accmailtext' => 'Le mot de passe pour "$1" a été envoyé à $2.',
'newarticle' => '(Nouveau)',
'newarticletext' => "Vous avez suit un lien à une page qu'existe pas encore.
Pour créer la page, mettez des mots dans la boëte en bas (voyez la [[{{MediaWiki:Helppage}}|page d'aide]] pour plus d'information).
Si vous êtes ici par erreur, cliquez le bouton \"back\" sus votre navigateur.",
'anontalkpagetext' => "----''Ça ici, c'est la page de discussion pour un useur sans nom qu'a pas encore créé un compte ou qui l'use pas.  Ça fait, il faut user l'adresse IP numérique pour l'identifier.  Une adresse comme ça pourrait être usée par plusieurs useurs.  Si vous êtes un useur sans nom et vous croyez que des messages sans rapport ont été envoyés à vous, [[Special:UserLogin|créer un compte ou connecter]] pour empêcher la confusion avec des autres useurs sans nom dans l'avenir.''",
'noarticletext' => 'À présent, y a pas de texte sus cette page.
Vous pouvez [[Special:Search/{{PAGENAME}}|charcher pour le titre de cette page]] dans des autres pages, <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} charcher dans les notes parents], ou [{{fullurl:{{FULLPAGENAME}}|action=edit}} changer cette page]</span>.',
'clearyourcache' => "'''Notez:''' Après que vous avez sauvé votres changements, il foudra peut-être dépasser le cache de votre navigateur pour voir les changements.  '''Mozilla / Firefox / Safari:''' Tenez le bouton ''Shift'' en pèsant ''Reload'', ou pèsez ''Ctrl-Shift-R'' (''Cmd-Shift-R'' sus Apple Mac); '''IE:''' Tenez ''Ctrl'' en pèsant ''Refresh'', ou pèsez ''Ctrl-F5''; '''Konqueror:''' Simplement pèsez le bouton ''Reload'', ou pèsez ''F5''; Pour les useurs de '''Opera''', il foudra peut-être vider complètement le cache dans ''Tools→Preferences''.",
'usercssyoucanpreview' => "'''Conseil:''' Usez le bouton \"Vue d'avance\" pour tester votre nouvelle feuille CSS avant de la sauver.",
'userjsyoucanpreview' => "'''Conseil:''' Usez le bouton \"Vue d'avance\" pour tester votre nouvelle feuille JS avant de la sauver.",
'usercsspreview' => "'''Rappelez-vous que vous êtes après regarder votre feuille CSS qu'a pas encore été sauvée!'''",
'userjspreview' => "'''Rappelez-vous que vous êtes juste après regarder ou tester votre code JavaScript qu'a pas encore été sauvé!'''",
'userinvalidcssjstitle' => "'''Attention:''' Y a pas de style \"\$1\".  Rappelez-vous qu'il faut user les petites lettres dans le sujet des pages personnelles avec les extensions .css et .js.
Exemple:  {{ns:user}}:Foo/vector.css (bon)  {{ns:user}}:Foo/Vector.css (mauvais)",
'updated' => '(Renouvelé)',
'note' => "'''Notez:'''",
'previewnote' => "'''Ça ici, c'est juste une vue d'avance; les changements ont pas encore été sauvés!'''",
'previewconflict' => "Cette vue d'avance montre le texte qu'est dans la boëte de changements en haut comme ça serait si vous choisirait de le sauver.",
'session_fail_preview' => "'''Ça fait de la peine!  Votre changement pouvait pas être sauvé à cause d'une perte d'information de la session.  Assayez donc encore.  Si ça travaille pas toujours, assayez de déconnecter et reconnecter.'''",
'session_fail_preview_html' => "'''Ça fait de la peine!  Votre changement pouvait pas être sauvé à cause d'une perte d'information de votre session.'''

''La vue d'avance est cachée pour empêcher les attaques JavaScript parce que ce wiki use le HTML brute.''

'''Si ça ici est un changement juste, assayez donc encore.  Si ça travaille pas toujours, assayez de déconnecter et reconnecter.'''",
'token_suffix_mismatch' => "'''Votre changement pouvait pas être accepté parce que votre navigateur a mélangé les caractères de ponctuation dans l'indication des changements.  Le changement a été rejeté pour empêcher la corruption du texte de l'article.  Ça arrive des fois quand vous êtes après user un proxy sans nom.'''",
'editing' => 'Changement de $1',
'editingsection' => 'Changement de $1 (section)',
'editingcomment' => 'Changement de $1 (remarque)',
'editconflict' => 'Dispute de changement: $1',
'explainconflict' => "Quèqu'une d'autre a changé cette page depuis vous avez commencé à la changer.
La boëtte de changements en haut contient le texte de la page comme c'est asteur.
Vos changements sont montrés dans la boëtte de changements en bas.
Il foudra que vous mettez vos changements dans le texte qu'est là asteur.
'''Juste''' le texte dans la boëtte de changements en haut va être sauvé quand vous pèsez \"{{int:savearticle}}\".",
'yourtext' => 'Votre texte',
'storedversion' => 'Version sauvée',
'nonunicodebrowser' => "'''ATTENTION: Votre navigateur supporte pas les caractères unicode.  Une solution de peu de durée a été trouvée pour que vous peuvez changer des articles sans problèmes.  Les caractères qui sont pas ASCII va apparaître dans la boëte de changements comme des codes hexadécimaux.'''",
'editingold' => "'''ATTENTION: Vous êtes après changer une vieille version de cette page.  Si vous le sauverait, vous perdrait n'importe quels changements faits depuis cette version.'''",
'yourdiff' => 'Différences',
'copyrightwarning' => "Notez donc que toutes les contributions à {{SITENAME}} sont considérées d’être libérées sous le $2 (voyez $1 pour les détails). Si vous voulez pas que votre écriture soye changée sans pitié et redistribuée à volonté, mettez donc pas votre écriture ici.<br 
/>Vous êtes après nous promettre aussi que vous l’avez écrit vous-même ou que vous l’avez copié du domaine public ou un autre ressource libre. ''' METTEZ PAS DE L’OUVRAGE SOUS COPYRIGHT ICI SANS PERMISSION !'''",
'copyrightwarning2' => "Notez donc que toutes les contributions à {{SITENAME}} pourriont être changées ou ôtées par des autres useurs. Si vous voulez pas que votre écriture soye changée sans pitié, mettez donc pas votre écriture ici.<br 
/>Vous êtes après nous promettre aussi que vous l’avez écrit vous-même ou que vous l’avez copié du domaine public ou un autre ressource libre (voyez $1 pour les détails). ''' METTEZ PAS DE L’OUVRAGE SOUS COPYRIGHT ICI SANS PERMISSION !'''",
'longpageerror' => "'''ERREUR: Le texte que vous avec mis a une taille de $1 kilobytes qu'est plus grande que le maximum de $2 kilobytes.  Ça peut pas être sauvé.'''",
'readonlywarning' => "'''ATTENTION: La base d'information a été barrée, ça fait, vous serez pas capable de sauver votres changements asteur.  Vous ferait mieux de copier et coller le texte dans un dossier texte et le sauver pour plus tard.'''",
'protectedpagewarning' => "'''ATTENTION: Cette page a été barrée pour que ça peuve être changée juste par les administrateurs.'''",
'semiprotectedpagewarning' => "'''Notez:'''  Cette page a été barrée pour que ça peuve être changé juste par les useurs connectés.",
'cascadeprotectedwarning' => "'''Attention :''' Cette page est protégée (ne peut être modifiée que par les administrateurs) parce qu’elle est incluse par {{PLURAL:$1|une page protégée|des pages protégées}} avec la protection en cascade activée :",
'templatesused' => 'Patrons usés sus cette page:',
'templatesusedpreview' => "Patrons usés dans cette vue d'avance:",
'templatesusedsection' => 'Patrons usés dans cette section:',
'template-protected' => '(protégé)',
'template-semiprotected' => '(demi-protégé)',
'edittools' => "<!-- Le texte que vous mettez ici va être montré sous les boëttes de changements ou d'import de dossier. -->",
'nocreatetitle' => 'Création de page limitée',
'nocreatetext' => "La création des pages est limitée.  Vous pouvez changer une page qu'a été déjà créée ou [[Special:UserLogin|connecter ou créer un compte]].",
'nocreate-loggedin' => 'Vous avez pas la permission de créer des nouvelles pages.',
'permissionserrors' => 'Erreur de permissions',
'permissionserrorstext' => 'Vous avez pas la permission de faire ça pour {{PLURAL:$1|cette raison|ces raisons}}:',
'permissionserrorstext-withaction' => 'Vous avez pas la permission de faire ça pour {{PLURAL:$1|cette raison|ces raisons}}:',
'recreate-moveddeleted-warn' => "'''Attention: Vous êtes après recréer une page qu'a déjà été ôtée.'''

Vous devrait considérer si c'est à propos de continuer à changer cette page.
Les notes d'ôtage pour cette page sont données ici pour vous aider:",

# "Undo" feature
'undo-success' => "Le changement peut être renversé.  Regardez donc la comparaison en bas pour être sûr que c'est comme vous voulez, et puis sauvez les changements en bas pour finir le renversage du changement.",
'undo-failure' => "Le changement pouvait pas être renversé à cause d'une dispute de changements.",
'undo-summary' => 'Défaire la révision $1 par [[Special:Contributions/$2|$2]] ([[User talk:$2|Discussion]])',

# Account creation failure
'cantcreateaccounttitle' => 'Impossible de créer le compte',

# History pages
'viewpagelogs' => 'Voir les notes pour cette page',
'nohistory' => 'Y a pas de changements pour cette page.',
'currentrev' => 'Version courante',
'revisionasof' => 'Version depuis le $1',
'revision-info' => 'Version depuis le $1 par $2',
'previousrevision' => '←Version avant',
'nextrevision' => 'Version après→',
'currentrevisionlink' => 'Version courante',
'cur' => 'cour.',
'next' => 'prochain',
'last' => 'dernier',
'page_first' => 'premier',
'page_last' => 'dernier',
'histlegend' => 'Choisir une version: Marquez les ronds pour les versions que vous voulez comparer et cliquez "enter" ou le bouton en bas.<br />
Clé: (cour.) = différence avec la version courante, (dernier) = différence avec la version avant, m = petit changement.',
'histfirst' => 'Premiers changements',
'histlast' => 'Derniers changements',
'historyempty' => '(vide)',

# Revision feed
'history-feed-title' => 'Détails des changements',
'history-feed-description' => 'Détails des changements pour cette page sus le wiki',
'history-feed-item-nocomment' => '$1 le $2',
'history-feed-empty' => 'La page que vous avez demandé est pas là.
Ça a peut-être été ôté du wiki, ou renommé.
Assayez de [[Special:Search|charcher dans le wiki]] pour des nouvelles pages.',

# Revision deletion
'rev-deleted-comment' => '(remarque ôtée)',
'rev-deleted-user' => "(nom d'useur ôté)",
'rev-deleted-event' => '(message ôté)',
'rev-deleted-text-permission' => "Cette page a été ôtée de la liste des pages publiques.  Il pourrait y avoir des détails dans les [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} notes d'ôtage].",
'rev-deleted-text-view' => "Cette version de la page a été ôtée de la liste des pages publiques.  Comme administrateur sus ce wiki vous pouvez le voir; il pourrait y avoir des détails dans les [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} notes d'ôtage].",
'rev-delundel' => 'montrer/cacher',
'revisiondelete' => 'Supprimer/rétablir les changements',
'revdelete-nooldid-title' => 'Aucun changement choisi',
'revdelete-nooldid-text' => 'Vous avez pas choisi le ou les changement(s) pour user cette fonction.',
'revdelete-selected' => "'''{{PLURAL:$2|Changement choisi|Changements choisis}} de [[:$1]]'''",
'logdelete-selected' => "'''{{PLURAL:$1|Événement de notes choisi|Événements de notes choisis}}:'''",
'revdelete-text' => "'''La liste des changements va sauver les versions ôtées, mais le public pourra pas voir certains morceaux de ces versions.'''

Des autres administrateurs sus ce wiki ont la permission de voir et de rétablir les morceaux cachés hormis qu'y aye des restrictions.",
'revdelete-legend' => 'Mettre des restrictions',
'revdelete-hide-text' => 'Cacher le texte de la version',
'revdelete-hide-image' => "Cacher l'information du dossier",
'revdelete-hide-name' => "Cacher l'action et l'objet",
'revdelete-hide-comment' => 'Cacher la remarque du changement',
'revdelete-hide-user' => "Cacher le nom ou l'adresse IP de l'useur",
'revdelete-hide-restricted' => 'Appliquer ces restrictions aux administrateurs et les autres useurs',
'revdelete-suppress' => 'Supprimer les données des administrateurs ainsi que celles des autres utilisateurs',
'revdelete-unsuppress' => 'Enlever les restrictions des versions rétablies',
'revdelete-log' => 'Raison:',
'revdelete-submit' => 'Appliquer à la version choisie',
'revdelete-success' => "'''Vous avez bien changé la visibilité des versions.'''",
'logdelete-success' => "'''Vous avez bien changé la visibilité des événements.'''",

# Diffs
'lineno' => 'Ligne $1:',
'compareselectedversions' => 'Comparer les versions choisies',
'editundo' => 'renverser',
'diff-multi' => '({{PLURAL:$1|Un changement moyen caché|$1 changements moyens cachés}})',

# Search results
'searchresults' => 'Résultats de la charche',
'searchresults-title' => 'Résultats de charche pour « $1 »',
'searchresulttext' => "Pour plus d'information pour vous aider à charcher dans {{SITENAME}}, voyez [[{{MediaWiki:Helppage}}|{{int:help}}]].",
'searchhelp-url' => 'Help:Aide',
'searchprofile-everything' => 'Tout',
'search-result-size' => '$1 ({{PLURAL:$2|1 mot|$2 mots}})',

# Preferences page
'preferences' => 'Réglage',
'mypreferences' => 'Mon réglage',
'skin-preview' => "Vue d'avance",
'prefs-custom-css' => 'Custom CSS',
'prefs-custom-js' => 'Custom JavaScript',
'youremail' => 'E-mail:',
'username' => "Nom d'useur:",
'uid' => "Numéro d'useur:",
'yourrealname' => 'Vrai nom:',
'yourlanguage' => 'Langue:',
'yourvariant' => 'Différent:',
'yournick' => "'Tit nom:",
'badsig' => 'Votre signature brute est pas bonne.  Regardez-voir les tags HTML.',
'badsiglength' => "Votre 'tit nom est trop long.  Il faut que ça soye moins que $1 caractères.",
'gender-male' => 'Male',
'gender-female' => 'Female',
'prefs-help-realname' => 'Votre vrai nom est pas nécessaire.  Si vous choisirait de le mettre, ça serait usé pour vous donner du crédit pour votre ouvrage.',
'prefs-help-email' => "Votre adresse e-mail est pas nécessaire, mais ça quitte le monde vous contacter par votre page d'useur ou votre page de discussion sans montrer votre identité.",

# User rights
'editinguser' => "Changement de '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]] | [[Special:Contributions/$1|{{int:contribslink}}]])",

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'Faire un changement',

# Recent changes
'recentchanges' => 'Changements récent',
'rcshowhidepatr' => '$1 les modifications patrouillés',

# File description page
'file-anchor-link' => 'Dossier',

# Random page
'randompage' => 'Page au hasard',

# Miscellaneous special pages
'nbytes' => '$1 octet{{PLURAL:$1||s}}',
'move' => 'Renommer',
'movethispage' => 'Renommer cette page',

# Special:Categories
'categories' => 'Classes',

# E-mail user
'emailuser' => 'E-mail cet useur',

# Watchlist
'watchlist' => 'Mes pages guettées',
'mywatchlist' => 'Mes pages guettées',
'watch' => 'Guetter',
'unwatch' => "guettez p'us",

# Namespace form on various pages
'blanknamespace' => '(Principal)',

# Contributions
'contributions' => "Changements de l'useur",
'mycontris' => 'Mes changements',

'sp-contributions-talk' => 'Discuter',

# Block/unblock
'ipblocklist' => 'Useurs bloqués',
'blocklink' => 'bloquer',
'contribslink' => 'changes',
'block-log-flags-nocreate' => 'création de compte interdite',

# Tooltip help for the actions
'tooltip-pt-login' => 'Vous êtes encouragé{{GENDER:||e|(e)}} à vous identifier mais ce n’est pas obligatoire.',
'tooltip-ca-talk' => 'Discussion de cette page de contenu',
'tooltip-search' => 'Charche {{SITENAME}}',
'tooltip-search-fulltext' => 'Charche les pages pour ce texte',
'tooltip-p-logo' => "Visitez la page d'acceuil",
'tooltip-n-portal' => 'À propos du projet, quoi faire, où trouver tout',
'tooltip-n-recentchanges' => 'La liste de changement récent dans ce wiki',
'tooltip-n-randompage' => 'Afficher un page au hasard',
'tooltip-n-help' => 'La place pour savoir',
'tooltip-t-specialpages' => 'Liste de tout les pages speciales',

# Bad image list
'bad_image_list' => 'Le format est le suivant :

Seules les listes d’énumération (commençant par *) sont prises en compte. Le premier lien d’une ligne doit être celui d’une mauvaise image.
Les autres liens sur la même ligne sont considérés comme des exceptions, par exemple des pages sur lesquelles l’image peut apparaître.',

);

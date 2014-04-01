<?php
/** Mirandese (Mirandés)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Alchimista
 * @author Cecílio
 * @author MCruz
 * @author Malafaya
 * @author Romaine
 * @author Urhixidur
 */

$fallback = 'pt';

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Special',
	NS_TALK             => 'Cumbersa',
	NS_USER             => 'Outelizador',
	NS_USER_TALK        => 'Cumbersa_outelizador',
	NS_PROJECT_TALK     => '$1_cumbersa',
	NS_FILE             => 'Fexeiro',
	NS_FILE_TALK        => 'Cumbersa_fexeiro',
	NS_MEDIAWIKI        => 'Biqui',
	NS_MEDIAWIKI_TALK   => 'Cumbersa_Biqui',
	NS_TEMPLATE         => 'Modelo',
	NS_TEMPLATE_TALK    => 'Cumbersa_Modelo',
	NS_HELP             => 'Ajuda',
	NS_HELP_TALK        => 'Cumbersa_ajuda',
	NS_CATEGORY         => 'Catadorie',
	NS_CATEGORY_TALK    => 'Cumbersa_catadorie',
);

$namespaceAliases = array(
	'Especial' => NS_SPECIAL,
	'Discussão' => NS_TALK,
	'Usuário' => NS_USER,
	'Usuário_Discussão' => NS_USER_TALK,
	'$1_Discussão' => NS_PROJECT_TALK,
	'Ficheiro' => NS_FILE,
	'Ficheiro_Discussão' => NS_FILE_TALK,
	'Imagem' => NS_FILE,
	'Imagem_Discussão' => NS_FILE_TALK,
	'MediaWiki_Discussão' => NS_MEDIAWIKI_TALK,
	'Predefinição' => NS_TEMPLATE,
	'Predefinição_Discussão' => NS_TEMPLATE_TALK,
	'Ajuda_Discussão' => NS_HELP_TALK,
	'Categoria' => NS_CATEGORY,
	'Categoria_Discussão' => NS_CATEGORY_TALK,
);

$specialPageAliases = array(
	'CreateAccount'             => array( 'Criar Cuonta' ),
	'Lonelypages'               => array( 'Páiginas Uorfanas' ),
	'Uncategorizedcategories'   => array( 'Catadories sien catadories' ),
	'Uncategorizedimages'       => array( 'Eimaiges sien catadories' ),
	'Userlogin'                 => array( 'Antrar' ),
	'Userlogout'                => array( 'Salir' ),
);

$magicWords = array(
	'redirect'                  => array( '0', '#ANCAMINAR', '#REDIRECIONAMENTO', '#REDIRECT' ),
	'img_right'                 => array( '1', 'dreita', 'direita', 'right' ),
	'img_left'                  => array( '1', 'squierda', 'esquerda', 'left' ),
	'img_none'                  => array( '1', 'nanhun', 'nenhum', 'none' ),
	'img_center'                => array( '1', 'centro', 'center', 'centre' ),
	'img_middle'                => array( '1', 'meio', 'middle' ),
	'language'                  => array( '0', '#LHENGUA:', '#IDIOMA:', '#LANGUAGE:' ),
	'filepath'                  => array( '0', 'CAMINOFEXEIRO:', 'CAMINHODOARQUIVO', 'FILEPATH:' ),
	'tag'                       => array( '0', 'eitiqueta', 'tag' ),
	'pagesize'                  => array( '1', 'TAMANHOFEXEIRO', 'TAMANHODAPAGINA', 'TAMANHODAPÁGINA', 'PAGESIZE' ),
	'staticredirect'            => array( '1', '_ANCAMINARSTATICO_', '__REDIRECIONAMENTOESTATICO__', '__REDIRECIONAMENTOESTÁTICO__', '__STATICREDIRECT__' ),
);


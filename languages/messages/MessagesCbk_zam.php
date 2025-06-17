<?php
/** Chavacano de Zamboanga (Chavacano de Zamboanga)
 *
 * @file
 * @ingroup Languages
 */

$fallback = 'es';

$namespaceNames = [
	NS_MEDIA          => 'Medio',
	NS_SPECIAL        => 'Especial',
	NS_TALK           => 'Discusión',
	NS_USER           => 'Usuario',
	NS_USER_TALK      => 'Discusión_del_usuario',
	NS_PROJECT_TALK   => 'Discusión_del_$1',
	NS_FILE           => 'File',
	NS_FILE_TALK      => 'Discusión_del_file',
	NS_MEDIAWIKI      => 'MediaWiki',
	NS_MEDIAWIKI_TALK => 'Discusión_del_MediaWiki',
	NS_TEMPLATE       => 'Plantilla',
	NS_TEMPLATE_TALK  => 'Discusión_del_plantilla',
	NS_HELP           => 'Ayuda',
	NS_HELP_TALK      => 'Discusión_del_ayuda',
	NS_CATEGORY       => 'Categoría',
	NS_CATEGORY_TALK  => 'Discusión_del_categoría',
];

$namespaceAliases = [
	'Imagen'              => NS_FILE,
	'Imagen_discusión'    => NS_FILE_TALK,
	'Usuaria'             => NS_USER,
	'Usuario_discusión'   => NS_USER_TALK,
	'Usuaria_discusión'   => NS_USER_TALK,
	'Archivo'             => NS_FILE,
	'Archivo_discusión'   => NS_FILE_TALK,
	'MediaWiki_discusión' => NS_MEDIAWIKI_TALK,
	'Plantilla_discusión' => NS_TEMPLATE_TALK,
	'Ayuda_discusión'     => NS_HELP_TALK,
	'Categoría_discusión' => NS_CATEGORY_TALK,
];

$namespaceGenderAliases = [];
$separatorTransformTable = [ ',' => ',', '.' => '.' ]; // T395724

$linkTrail = '/^([a-záéíóúñ]+)(.*)$/sDu';

<?php

/**
 *
 * @package phpBB Extension - Sort Topics
 * @copyright (c) 2015 kasimi
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'SORTTOPICS_TITLE'						=> 'Ordenar Temas',
	'SORTTOPICS_CONFIG'						=> 'Configuración',
	'SORTTOPICS_CONFIG_UPDATED'				=> 'Extensión <strong>Ordenar Temas</strong><br />» Configuración actualizada',

	'SORTTOPICS_UCP_ENABLED'				=> 'Permitir a los usuarios globalmente ordenar temas por fecha de creación',
	'SORTTOPICS_UCP_ENABLED_EXPLAIN'		=> 'Dar a los usuarios la opción en el PCU para ordenar los temas por fecha de creación en todos los foros.',

	'SORTTOPICS_SORT_TOPICS_BY'				=> 'Ordenar temas por',
	'SORTTOPICS_SORT_TOPICS_BY_EXPLAIN'		=> 'Un valor distinto de “Por defecto del usuario” obliga a los temas en este foro ser ordenados inicialmente por la clave especificada, sin tener en cuenta las preferencias de ordenación del usuario en el PCU. El usuario todavía puede cambiar temporalmente la ordenación en la parte inferior de cada página viendo un foro.',
	'SORTTOPICS_SORT_TOPICS_ORDER'			=> 'Orden de temas ordenados',
	'SORTTOPICS_SORT_TOPICS_ORDER_EXPLAIN'	=> 'Esta opción sólo está en efecto si la opción anterior se establece en un valor distinto de “Por defecto del usuario”.',
	'SORTTOPICS_USER_DEFAULT'				=> 'Por defecto del usuario',
	'SORTTOPICS_CREATED_TIME'				=> 'Fecha de creación',
));

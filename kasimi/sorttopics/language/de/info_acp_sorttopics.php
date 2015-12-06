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
	'SORTTOPICS_TITLE'						=> 'Themen sortieren',
	'SORTTOPICS_CONFIG'						=> 'Konfiguration',
	'SORTTOPICS_CONFIG_UPDATED'				=> '<strong>Sort Topics</strong>Extension<br />» Konfiguration aktualisiert',

	'SORTTOPICS_UCP_ENABLED'				=> 'Erlaube Benutzern global, Themen nach Eröffnungsdatum zu sortieren',
	'SORTTOPICS_UCP_ENABLED_EXPLAIN'		=> 'Gibt Benutzern im persönlichen Bereich die Möglichkeit, in allen Foren die Themen nach Eröffnungsdatum zu sortieren.',

	'SORTTOPICS_SORT_TOPICS_BY'				=> 'Sortiere Themen nach',
	'SORTTOPICS_SORT_TOPICS_BY_EXPLAIN'		=> 'Jeder andere Wert als “Benutzer-Standard” erzwingt die anfängliche Sortierung wie hier angegeben, unabhängig von der Standard-Einstellung des Benutzers im persönlichen Bereich. Der Benutzer hat dennoch die Möglichkeit, die Reihenfolge am Ende der Forenindex-Seite temporär umzuschalten.',
	'SORTTOPICS_SORT_TOPICS_ORDER'			=> 'Reihenfolge',
	'SORTTOPICS_SORT_TOPICS_ORDER_EXPLAIN'	=> 'Diese Option wirkt sich nur aus, wenn oben etwas anderes als “Benutzer-Standard” gewählt wurde.',
	'SORTTOPICS_USER_DEFAULT'				=> 'Benutzer-Standard',
	'SORTTOPICS_CREATED_TIME'				=> 'Eröffnungsdatum',
));

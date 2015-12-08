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
	'SORTTOPICS_TITLE'						=> 'Ordina argomenti',
	'SORTTOPICS_CONFIG'						=> 'Configurazione',
	'SORTTOPICS_CONFIG_UPDATED'				=> 'Estensione <strong>Ordina argomenti</strong><br />» Configurazione aggiornata',

	'SORTTOPICS_UCP_ENABLED'				=> 'Permette agli utenti di ordinare globalmente gli argomenti per data d’apertura',
	'SORTTOPICS_UCP_ENABLED_EXPLAIN'		=> 'Fornisce agli utenti l’opzione nel <abbr title="Pannello di controllo utente">PCU</abbr> di ordinare gli argomenti per data d’apertura in tutti i forum.',

	'SORTTOPICS_SORT_TOPICS_BY'				=> 'Ordina argomenti per',
	'SORTTOPICS_SORT_TOPICS_BY_EXPLAIN'		=> 'Un’impostazione diversa da “Predefinito utente” forza gli argomenti di questo forum a essere ordinari inizialmente in base alla chiave specificata, ignorando le preferenze di ordinamento specificate dall’utente nel PCU. L’utente può, in ogni caso, cambiare temporaneamente l’ordine in fondo ad ogni pagina <em>viewtopic</em>.',
	'SORTTOPICS_SORT_TOPICS_ORDER'			=> 'Ordine argomenti',
	'SORTTOPICS_SORT_TOPICS_ORDER_EXPLAIN'	=> 'Quest’opzione ha effetto solo se l’opzione precedente è impostata ad un valore diverso da “Predefinito utente”.',
	'SORTTOPICS_APPLY_TO_SUBFORUMS'			=> 'Applica l’ordinamento di questo forum a tutti i subforum',
	'SORTTOPICS_APPLY_TO_SUBFORUMS_EXPLAIN'	=> 'Se impostata su “Sì”, le perferenze di ordinamento qui specificate si applicheranno al forum, ai suoi subforum e ai loro subforum.',
	'SORTTOPICS_USER_DEFAULT'				=> 'Predefinito utente',
	'SORTTOPICS_CREATED_TIME'				=> 'Data apertura',
));

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
	'SORTTOPICS_TITLE'						=> 'Ordina Argomenti',
	'SORTTOPICS_CONFIG'						=> 'Configurazione',
	'SORTTOPICS_CONFIG_UPDATED'				=> '<strong>Estensione</strong> Ordina Argomenti<br />» Configurazione aggiornata',

	'SORTTOPICS_UCP_ENABLED'				=> 'Consenti agli utenti di ordinare i messaggi creati a livello globale',
	'SORTTOPICS_UCP_ENABLED_EXPLAIN'		=> 'Darà agli utenti la possibilità in PCU di ordinare i messaggi creati in tutti i forum.',

	'SORTTOPICS_SORT_TOPICS_BY'				=> 'Ordina argomenti per',
	'SORTTOPICS_SORT_TOPICS_BY_EXPLAIN'		=> 'Un valore diverso “default utente” forza gli argomenti in questo forum da essere inizialmente ordinati per la chiave specificata, ignorando le preferenze di ordinamento utente/i in PCU. L’utente è ancora in grado di modificare temporaneamente l’ordinamento in fondo a ogni pagina in viewforum.',
	'SORTTOPICS_SORT_TOPICS_ORDER'			=> 'Ordinamento argomenti',
	'SORTTOPICS_SORT_TOPICS_ORDER_EXPLAIN'	=> 'Questa opzione ha effetto solo se l’opzione precedente è impostata su un valore diverso in “default etente”.',
	'SORTTOPICS_APPLY_TO_SUBFORUMS'			=> 'Applica ordinamento informazione argomento di questo forum e per tutti i sub-forum',
	'SORTTOPICS_APPLY_TO_SUBFORUMS_EXPLAIN'	=> 'Se impostato su “SI”, le preferenze di ordinamento di cui sopra si applicano a questo forum e tutti i sub-forum (e i loro sub-forum).',
	'SORTTOPICS_USER_DEFAULT'				=> 'Default utente',
	'SORTTOPICS_CREATED_TIME'				=> 'Ora di creazione',
));

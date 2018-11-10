<?php
/**
 *
 * Sort Topics. An extension for the phpBB Forum Software package.
 * French translation by Galixte (http://www.galixte.com)
 *
 * @copyright (c) 2017 kasimi <https://kasimi.net>
 * @license GNU General Public License, version 2 (GPL-2.0-only)
 *
 */

/**
 * DO NOT CHANGE
 */
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = [];
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ « » “ ” …
//

$lang = array_merge($lang, [
	'SORTTOPICS_TITLE'						=> 'Tri des sujets',
	'SORTTOPICS_CONFIG'						=> 'Paramètres',
	'SORTTOPICS_CONFIG_UPDATED'				=> 'Extension « <strong>Méthode de tri des sujets</strong> »<br />» Paramètres mis à jour',

	'SORTTOPICS_UCP_ENABLED'				=> 'Autoriser les membres à trier l’ensemble des sujets selon la date de création',
	'SORTTOPICS_UCP_ENABLED_EXPLAIN'		=> 'Permet d’ajouter une option dans le PCU (Panneau de l’utilisateur) des membres pour activer la méthode de tri des sujets selon la date de création.',

	'SORTTOPICS_SORT_TOPICS_BY'				=> 'Trier les sujets selon',
	'SORTTOPICS_SORT_TOPICS_BY_EXPLAIN'		=> 'Permet de sélectionner une méthode de tri par défaut. Une valeur différente de « Méthode de tri par défaut de l’utilisateur » force les sujets dans ce forum à être par défaut triés selon la méthode sélectionnée, sans tenir compte des préférences de tri définies dans le PCU (Panneau de l’utilisateur) des membres. Toutefois, les membres peuvent toujours modifier la méthode de tri temporairement depuis le bas de page lors de la vue d’un forum (emplacement par défaut du tri).',
	'SORTTOPICS_SORT_TOPICS_ORDER'			=> 'Ordre de tri des sujets',
	'SORTTOPICS_SORT_TOPICS_ORDER_EXPLAIN'	=> 'Permet de définir l’ordre croissant ou son contraire pour la méthode de tri des sujets. Cette option est effective uniquement lorsque l’option précédente est définie sur une méthode autre que « Méthode de tri par défaut de l’utilisateur ».',
	'SORTTOPICS_APPLY_TO_SUBFORUMS'			=> 'Appliquer la méthode de tri des sujets de ce forum à tous ses sous-forums',
	'SORTTOPICS_APPLY_TO_SUBFORUMS_EXPLAIN'	=> 'Permet de définir sur « Oui » la méthode de tri des sujets à ce forum et ses sous-forums, ainsi que leurs sous-forums.',
	'SORTTOPICS_USER_DEFAULT'				=> 'Méthode de tri par défaut de l’utilisateur',
]);

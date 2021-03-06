<?php

/**
 *
 * @package phpBB Extension - Sort Topics
 * @copyright (c) 2016 kasimi - https://kasimi.net
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace kasimi\sorttopics\event;

use phpbb\db\driver\driver_interface as db_interface;
use phpbb\event\data;
use phpbb\language\language;
use phpbb\request\request_interface;
use phpbb\template\template;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class acp_listener implements EventSubscriberInterface
{
	/** @var language */
	protected $lang;

	/** @var request_interface */
	protected $request;

	/** @var db_interface */
	protected $db;

	/** @var template */
	protected $template;

	/** @var string */
	protected $default_sort_by;

	/** @var string */
	protected $default_sort_order;

	public function __construct(
		language $lang,
		request_interface $request,
		db_interface $db,
		template $template,
		$default_sort_by,
		$default_sort_order
	)
	{
		$this->lang 				= $lang;
		$this->request				= $request;
		$this->db					= $db;
		$this->template				= $template;
		$this->default_sort_by		= $default_sort_by;
		$this->default_sort_order	= $default_sort_order;
	}

	public static function getSubscribedEvents(): array
	{
		return [
			'core.acp_manage_forums_display_form' => 'acp_manage_forums_display_form',
			'core.acp_manage_forums_request_data' => 'acp_manage_forums_request_data',
		];
	}

	/**
	 * Add <select>s to forum preferences page
	 */
	public function acp_manage_forums_display_form(data $event): void
	{
		$this->lang->add_lang('acp', 'kasimi/sorttopics');

		$topic_sort_options = $this->gen_topic_sort_options($event['forum_data']);

		$this->template->assign_vars([
			'S_SORTTOPICS_BY_OPTIONS'		=> $topic_sort_options['by'],
			'S_SORTTOPICS_ORDER_OPTIONS'	=> $topic_sort_options['order'],
		]);
	}

	/**
	 * Store user input
	 */
	public function acp_manage_forums_request_data(data $event): void
	{
		$sort_topics_by = $this->request->variable('sk', $this->default_sort_by);
		$sort_topics_order = $this->request->variable('sd', $this->default_sort_order);
		$sort_topics_subforums = $this->request->variable('sort_topics_subforums', false);

		$sort_options = [
			'sort_topics_by'	=> $sort_topics_by,
			'sort_topics_order'	=> $sort_topics_order,
		];

		$event['forum_data'] = array_merge($event['forum_data'], $sort_options);

		// Apply this forum's sorting to all sub-forums
		if ($event['action'] == 'edit' && $sort_topics_subforums)
		{
			$subforum_ids = [];
			foreach (get_forum_branch($event['forum_data']['forum_id'], 'children', 'descending', false) as $subforum)
			{
				$subforum_ids[] = (int) $subforum['forum_id'];
			}

			if ($subforum_ids)
			{
				$sql = 'UPDATE ' . FORUMS_TABLE . '
					SET ' . $this->db->sql_build_array('UPDATE', $sort_options) . '
					WHERE ' . $this->db->sql_in_set('forum_id', $subforum_ids);
				$this->db->sql_query($sql);
			}
		}
	}

	/**
	 * Generate <select> markup
	 */
	protected function gen_topic_sort_options(array $forum_data): array
	{
		$this->lang->add_lang('common', 'kasimi/sorttopics');

		// Dummy variables
		$sort_days = 0;
		$limit_days = [];

		$sort_by_text = [
			'x' => $this->lang->lang('SORTTOPICS_USER_DEFAULT'),
			'a' => $this->lang->lang('AUTHOR'),
			't' => $this->lang->lang('POST_TIME'),
			'c' => $this->lang->lang('SORTTOPICS_CREATED_TIME'),
			'r' => $this->lang->lang('REPLIES'),
			's' => $this->lang->lang('SUBJECT'),
			'v' => $this->lang->lang('VIEWS'),
		];

		$sort_key = isset($forum_data['sort_topics_by']) ? $forum_data['sort_topics_by'] : $this->default_sort_by;
		$sort_dir = isset($forum_data['sort_topics_order']) ? $forum_data['sort_topics_order'] : $this->default_sort_order;
		$s_limit_days = $s_sort_key = $s_sort_dir = $u_sort_param = '';
		gen_sort_selects($limit_days, $sort_by_text, $sort_days, $sort_key, $sort_dir, $s_limit_days, $s_sort_key, $s_sort_dir, $u_sort_param, false, $this->default_sort_by, $this->default_sort_order);

		return [
			'by'	=> $s_sort_key,
			'order'	=> $s_sort_dir,
		];
	}
}

<?php

/**
 *
 * @package phpBB Extension - Sort Topics
 * @copyright (c) 2015 kasimi
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace kasimi\sorttopics\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class acp_listener implements EventSubscriberInterface
{
	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\request\request_interface */
	protected $request;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var string */
	protected $default_sort_by;

	/** @var string */
	protected $default_sort_order;

	/**
	 * Constructor
	 *
	 * @param \phpbb\user							$user
	 * @param \phpbb\request\request_interface		$request
	 * @param \phpbb\config\config					$config
	 * @param \phpbb\db\driver\driver_interface		$db
	 * @param \phpbb\template\template				$template
	 * @param string								$default_sort_by
	 * @param string								$default_sort_order
	 */
	public function __construct(
		\phpbb\user $user,
		\phpbb\request\request_interface $request,
		\phpbb\config\config $config,
		\phpbb\db\driver\driver_interface $db,
		\phpbb\template\template $template,
		$default_sort_by,
		$default_sort_order
	)
	{
		$this->user 				= $user;
		$this->request				= $request;
		$this->config				= $config;
		$this->db					= $db;
		$this->template				= $template;
		$this->default_sort_by		= $default_sort_by;
		$this->default_sort_order	= $default_sort_order;
	}

	/**
	 * Register hooks
	 *
	 * @return array
	 */
	static public function getSubscribedEvents()
	{
		return array(
			'core.acp_manage_forums_display_form' => 'acp_manage_forums_display_form',
			'core.acp_manage_forums_request_data' => 'acp_manage_forums_request_data',
		);
	}

	/**
	 * Add <select>s to forum preferences page
	 *
	 * @param $event
	 */
	public function acp_manage_forums_display_form($event)
	{
		if ($event['forum_data']['forum_type'] == FORUM_POST)
		{
			$topic_sort_options = $this->gen_topic_sort_options($event['forum_data']);
			$this->template->assign_vars(array(
				'SORTTOPICS_VERSION'			=> $this->config['kasimi.sorttopics.version'],
				'S_SORTTOPICS_BY_OPTIONS'		=> $topic_sort_options['by'],
				'S_SORTTOPICS_ORDER_OPTIONS'	=> $topic_sort_options['order'],
			));
		}
	}

	/**
	 * Store user input
	 *
	 * @param $event
	 */
	public function acp_manage_forums_request_data($event)
	{
		$sort_topics_by = $this->request->variable('sk', $this->default_sort_by);
		$sort_topics_order = $this->request->variable('sd', $this->default_sort_order);
		$sort_topics_subforums = $this->request->variable('sort_topics_subforums', 0);

		$event['forum_data'] = array_merge($event['forum_data'], array(
			'sort_topics_by'	=> $sort_topics_by,
			'sort_topics_order'	=> $sort_topics_order,
		));

		// Apply this forum's sorting to all sub-forums
		if ($sort_topics_subforums)
		{
			$subforum_ids = array();
			foreach (get_forum_branch($event['forum_data']['forum_id'], 'children', 'descending', false) as $subforum)
			{
				$subforum_ids[] = (int) $subforum['forum_id'];
			}

			if (!empty($subforum_ids))
			{
				$this->db->sql_transaction('begin');

				foreach ($subforum_ids as $subforum_id)
				{
					$sql_ary = array(
						'sort_topics_by'	=> $sort_topics_by,
						'sort_topics_order'	=> $sort_topics_order,
					);

					$sql = 'UPDATE ' . FORUMS_TABLE . '
						SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . '
						WHERE forum_id = ' . (int) $subforum_id;

					$this->db->sql_query($sql_ary);
				}

				$this->db->sql_transaction('commit');
			}
		}
	}

	/**
	 * Generate <select> markup
	 *
	 * @param $forum_data
	 * @return array
	 */
	protected function gen_topic_sort_options($forum_data) {
		$this->user->add_lang_ext('kasimi/sorttopics', 'common');

		// Dummy variables
		$sort_days = 0;
		$limit_days = array();

		$sort_by_text = array(
			'x' => $this->user->lang('SORTTOPICS_USER_DEFAULT'),
			'a' => $this->user->lang('AUTHOR'),
			't' => $this->user->lang('POST_TIME'),
			'c' => $this->user->lang('SORTTOPICS_CREATED_TIME'),
			'r' => $this->user->lang('REPLIES'),
			's' => $this->user->lang('SUBJECT'),
			'v' => $this->user->lang('VIEWS'),
		);

		$sort_key = isset($forum_data['sort_topics_by']) ? $forum_data['sort_topics_by'] : $this->default_sort_by;
		$sort_dir = isset($forum_data['sort_topics_order']) ? $forum_data['sort_topics_order'] : $this->default_sort_order;
		$s_limit_days = $s_sort_key = $s_sort_dir = $u_sort_param = '';
		gen_sort_selects($limit_days, $sort_by_text, $sort_days, $sort_key, $sort_dir, $s_limit_days, $s_sort_key, $s_sort_dir, $u_sort_param, false, $this->default_sort_by, $this->default_sort_order);

		return array(
			'by'	=> $s_sort_key,
			'order'	=> $s_sort_dir,
		);
	}
}

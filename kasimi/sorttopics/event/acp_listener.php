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
	protected $user;
	protected $request;
	protected $template;
	protected $default_sort_by;
	protected $default_sort_order;

	/**
 	 * Constructor
	 *
	 * @param \phpbb\user							$user
	 * @param \phpbb\request\request_interface		$request
	 * @param \phpbb\template\template				$template
	 * @param string								$default_sort_by
	 * @param string								$default_sort_order
	 */
	public function __construct(
		\phpbb\user $user,
		\phpbb\request\request_interface $request,
		\phpbb\template\template $template,
		$default_sort_by,
		$default_sort_order
	)
	{
		$this->user = $user;
		$this->request = $request;
		$this->template = $template;
		$this->default_sort_by = $default_sort_by;
		$this->default_sort_order = $default_sort_order;
	}

	/**
	 * Register hooks
	 */
	static public function getSubscribedEvents()
	{
		return array(
			// ACP
			'core.acp_manage_forums_display_form'	=> 'acp_manage_forums_display_form',
			'core.acp_manage_forums_request_data'	=> 'acp_manage_forums_request_data',
		);
	}

	/**
	 * Add <select>s to forum preferences page
	 *
	 * Event: core.acp_manage_forums_display_form
	 */
	public function acp_manage_forums_display_form($event)
	{
		if ($event['forum_data']['forum_type'] == FORUM_POST) {
			$this->user->add_lang_ext('kasimi/sorttopics', 'common');
			$topic_sort_options = $this->gen_topic_sort_options($event['forum_data']);
			$this->template->assign_vars(array(
				'S_SORTTOPICS_BY_OPTIONS'		=> $topic_sort_options['by'],
				'S_SORTTOPICS_ORDER_OPTIONS'	=> $topic_sort_options['order'],
			));
		}
	}

	/**
	 * Generate <select> markup
	 */
	protected function gen_topic_sort_options($forum_data) {
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
			'by' => $s_sort_key,
			'order' => $s_sort_dir,
		);
	}

	/**
	 * Store user input
	 *
	 * Event: core.acp_manage_forums_request_data
	 */
	public function acp_manage_forums_request_data($event)
	{
		$event['forum_data'] = array_merge($event['forum_data'], array(
			'sort_topics_by'		=> $this->request->variable('sk', $this->default_sort_by),
			'sort_topics_order'		=> $this->request->variable('sd', $this->default_sort_order),
		));
	}
}

<?php

/**
 *
 * @package phpBB Extension - Sort Topics
 * @copyright (c) 2016 kasimi - https://kasimi.net
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace kasimi\sorttopics\event;

use kasimi\sorttopics\core\sort_core;
use phpbb\auth\auth;
use phpbb\config\config;
use phpbb\event\data;
use phpbb\request\request_interface;
use phpbb\template\template;
use phpbb\user;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class viewforum_listener extends sort_core implements EventSubscriberInterface
{
	/** @var user */
	protected $user;

	/** @var auth */
	protected $auth;

	/** @var request_interface */
	protected $request;

	/** @var config */
	protected $config;

	/** @var template */
	protected $template;

	/** @var string */
	protected $default_sort_by;

	/** @var string */
	protected $sort_options_source = null;

	/** @var string */
	protected $sort_key = null;

	/** @var string */
	protected $sort_dir = null;

	/** @var string */
	protected $custom_sort_key = null;

	/** @var string */
	protected $custom_sort_dir = null;

	public function __construct(
		user $user,
		auth $auth,
		request_interface $request,
		config $config,
		template $template,
		$default_sort_by
	)
	{
		$this->user				= $user;
		$this->auth				= $auth;
		$this->request			= $request;
		$this->config			= $config;
		$this->template			= $template;
		$this->default_sort_by	= $default_sort_by;
	}

	public static function getSubscribedEvents(): array
	{
		return [
			'core.viewforum_get_topic_data'			=> 'viewforum_get_topic_data',
			'core.viewforum_get_topic_ids_data'		=> 'viewforum_get_topic_ids_data',
			'core.pagination_generate_page_link'	=> 'pagination_generate_page_link',
		];
	}

	/**
	 * Remember default sorting for later
	 */
	public function viewforum_get_topic_data(data $event): void
	{
		$this->sort_key = $event['sort_key'];
		$this->sort_dir = $event['sort_dir'];
	}

	/**
	 * Apply custom sorting to SQL query
	 */
	public function viewforum_get_topic_ids_data(data $event): void
	{
		$this->sort_options_source = 'default';
		$this->custom_sort_key = $this->user->data['user_topic_sortby_type'];
		$this->custom_sort_dir = $this->user->data['user_topic_sortby_dir'];

		if ($event['forum_data']['sort_topics_by'] != $this->default_sort_by)
		{
			// Forum-specific sorting
			$this->sort_options_source = 'forum';
			$this->custom_sort_key = $event['forum_data']['sort_topics_by'];
			$this->custom_sort_dir = $event['forum_data']['sort_topics_order'];
		}
		else if ($this->user->data['is_registered'] && $this->config['kasimi.sorttopics.ucp_enabled'] && $this->user->data['sort_topics_by_created_time'])
		{
			// UCP-specific sorting by created time
			$this->sort_options_source = 'ucp';
			$this->custom_sort_key = 'c';
		}

		// Temporary sorting if the user used the options at the bottom of viewforum
		if ($this->request->is_set('sk'))
		{
			$this->sort_options_source = 'request';
			$this->custom_sort_key = $this->request->variable('sk', $this->custom_sort_key);
			$this->custom_sort_dir = $this->request->variable('sd', $this->custom_sort_dir);
		}

		$this->inject_created_time_select_option('S_SELECT_SORT_KEY', $this->custom_sort_key, 'S_SELECT_SORT_DIR', $this->custom_sort_dir);

		// Bail out if we don't need to adjust sorting
		if ($this->custom_sort_key == $this->sort_key && $this->custom_sort_dir == $this->sort_dir)
		{
			return;
		}

		// This forum requires custom topic sorting, let's get our hands dirty
		$sort_by_sql = [
			'a' => 't.topic_first_poster_name',
			't' => ['t.topic_last_post_time', 't.topic_last_post_id'],
			'c' => ['t.topic_time', 't.topic_id'],
			'r' => $this->auth->acl_get('m_approve', $event['forum_data']['forum_id']) ? 't.topic_posts_approved + t.topic_posts_unapproved + t.topic_posts_softdeleted' : 't.topic_posts_approved',
			's' => 't.topic_title',
			'v' => 't.topic_views',
		];

		$store_reverse = $event['store_reverse'];

		if ($store_reverse)
		{
			$direction = $this->custom_sort_dir == 'd' ? 'ASC' : 'DESC';
		}
		else
		{
			$direction = $this->custom_sort_dir == 'd' ? 'DESC' : 'ASC';
		}

		if (is_array($sort_by_sql[$this->custom_sort_key]))
		{
			$sql_sort_order = implode(' ' . $direction . ', ', $sort_by_sql[$this->custom_sort_key]) . ' ' . $direction;
		}
		else
		{
			$sql_sort_order = $sort_by_sql[$this->custom_sort_key] . ' ' . $direction;
		}

		$sql_ary = $event['sql_ary'];
		$sql_ary['ORDER_BY'] = 't.topic_type ' . (!$store_reverse ? 'DESC' : 'ASC') . ', ' . $sql_sort_order;

		$event['sql_sort_order'] = $sql_sort_order;
		$event['sql_ary'] = $sql_ary;

		if ($this->sort_options_source == 'request' && $this->custom_sort_key == 'c')
		{
			$this->template->assign_var('U_VIEW_FORUM', $this->template->retrieve_var('U_VIEW_FORUM') . '&amp;sk=c');
		}
	}

	/**
	 * Add the sorting parameter to the pagination links
	 */
	public function pagination_generate_page_link(data $event): void
	{
		if ($this->sort_options_source == 'request' && $this->custom_sort_key == 'c' && strpos($event['base_url'], '/viewforum.') !== false)
		{
			$event['base_url'] .= '&amp;sk=c';
		}
	}
}

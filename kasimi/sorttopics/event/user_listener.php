<?php

/**
 *
 * @package phpBB Extension - Sort Topics
 * @copyright (c) 2016 kasimi
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace kasimi\sorttopics\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class user_listener implements EventSubscriberInterface
{
	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\request\request_interface */
	protected $request;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\template\context */
	protected $template_context;

	/** @var string */
	protected $default_sort_by;

	/** @var string */
	private $sort_key;

	/** @var string */
	private $sort_dir;

	/** @var boolean */
	private $ucp_sortby_created_time = null;

	/**
 	 * Constructor
	 *
	 * @param \phpbb\user						$user
	 * @param \phpbb\auth\auth					$auth
	 * @param \phpbb\request\request_interface	$request
	 * @param \phpbb\config\config				$config
	 * @param \phpbb\template\context			$template_context
	 * @param string							$default_sort_by
	 */
	public function __construct(
		\phpbb\user $user,
		\phpbb\auth\auth $auth,
		\phpbb\request\request_interface $request,
		\phpbb\config\config $config,
		\phpbb\template\context $template_context,
		$default_sort_by
	)
	{
		$this->user				= $user;
		$this->auth				= $auth;
		$this->request			= $request;
		$this->config			= $config;
		$this->template_context	= $template_context;
		$this->default_sort_by	= $default_sort_by;
	}

	/**
	 * Register hooks
	 *
	 * @return array
	 */
	static public function getSubscribedEvents()
	{
		return array(
			// Viewforum
			'core.viewforum_get_topic_data'			=> 'viewforum_get_topic_data',
			'core.viewforum_get_topic_ids_data'		=> 'viewforum_get_topic_ids_data',
			// UCP
			'core.ucp_prefs_modify_common'			=> 'ucp_prefs_modify_common',
			'core.ucp_prefs_view_data'				=> 'ucp_prefs_view_data',
			'core.ucp_prefs_view_update_data'		=> 'ucp_prefs_view_update_data',
		);
	}

	/**
	 * @param $event
	 */
	public function viewforum_get_topic_data($event)
	{
		$this->sort_key = $event['sort_key'];
		$this->sort_dir = $event['sort_dir'];
	}

	/**
	 * @param $event
	 */
	public function viewforum_get_topic_ids_data($event)
	{
		$custom_sorting = array(
				'by'		=> $this->user->data['user_topic_sortby_type'],
				'order'		=> $this->user->data['user_topic_sortby_dir'],
			);

		// Forum-specific sorting
		if ($event['forum_data']['sort_topics_by'] != $this->default_sort_by)
		{
			$custom_sorting = array(
				'by'		=> $event['forum_data']['sort_topics_by'],
				'order'		=> $event['forum_data']['sort_topics_order'],
			);
		}
		// UCP-specific sorting by created time
		else if ($this->user->data['is_registered'] && !$this->user->data['is_bot'] && $this->config['kasimi.sorttopics.ucp_enabled'] && $this->user->data['sort_topics_by_created_time'])
		{
			$custom_sorting['by'] = 'c';
		}

		// Temporary sorting if the user used the options at the bottom of viewforum
		if ($this->request->is_set('sk'))
		{
			$custom_sorting['by'] = $this->request->variable('sk', '');
		}
		if ($this->request->is_set('sd'))
		{
			$custom_sorting['order'] = $this->request->variable('sd', '');
		}

		$this->inject_created_time_select_option('S_SELECT_SORT_KEY', $custom_sorting['by'], 'S_SELECT_SORT_DIR', $custom_sorting['order']);

		// Bail out if we don't need to adjust sorting
		if ($custom_sorting['by'] == $this->sort_key && $custom_sorting['order'] == $this->sort_dir)
		{
			return;
		}

		// This forum requires custom topic sorting, let's get our hands dirty
		$sort_by_sql = array(
			'a' => 't.topic_first_poster_name',
			't' => array('t.topic_last_post_time', 't.topic_last_post_id'),
			'c' => array('t.topic_time', 't.topic_id'),
			'r' => (($this->auth->acl_get('m_approve', $event['forum_data']['forum_id'])) ? 't.topic_posts_approved + t.topic_posts_unapproved + t.topic_posts_softdeleted' : 't.topic_posts_approved'),
			's' => 't.topic_title',
			'v' => 't.topic_views',
		);

		$sort_sql = $sort_by_sql[$custom_sorting['by']];
		$direction = ($custom_sorting['order'] == 'd') ? 'DESC' : 'ASC';
		$sql_sort_order = (is_array($sort_sql) ? implode(' ' . $direction . ', ', $sort_sql) : $sort_sql) . ' ' . $direction;
		$sql_ary = $event['sql_ary'];
		$store_reverse = $event['store_reverse'];
		$sql_ary['ORDER_BY'] = 't.topic_type ' . ((!$store_reverse) ? 'DESC' : 'ASC') . ', ' . $sql_sort_order;

		$event['sql_sort_order'] = $sql_sort_order;
		$event['sql_ary'] = $sql_ary;
	}

	/**
	 * Adds the 'Created time' <option> to the template
	 *
	 * @param $event
	 */
	public function ucp_prefs_modify_common($event)
	{
		if ($this->config['kasimi.sorttopics.ucp_enabled'] && $event['mode'] == 'view')
		{
			$sort_key = $this->user->data['sort_topics_by_created_time'] ? 'c' : $this->user->data['user_topic_sortby_type'];
			$this->inject_created_time_select_option('S_TOPIC_SORT_KEY', $sort_key);
		}
	}

	/**
	 * Updates the template data by inserting the (possibly selected) 'Created time' <option> into the $select tag right after the 'Post time' <option>
	 *
	 * @param $template_select_key_var
	 * @param $sort_key
	 * @param bool $template_select_dir_var
	 * @param bool $sort_dir
	 */
	protected function inject_created_time_select_option($template_select_key_var, $sort_key, $template_select_dir_var = false, $sort_dir = false)
	{
		$rootref = &$this->template_context->get_root_ref();
		$select = $rootref[$template_select_key_var];

		// Insert 'Created time'
		$this->user->add_lang_ext('kasimi/sorttopics', 'common');
		$new_option = '<option value="c">' . $this->user->lang('SORTTOPICS_CREATED_TIME') . '</option>';
		$select = preg_replace("/(value=\"t\".*?<\/option>)/su", "$1" . $new_option, $select);

		// Fix selection
		$select = $this->set_selected($select, $sort_key);

		$rootref[$template_select_key_var] = $select;

		if ($template_select_dir_var !== false && $sort_dir !== false)
		{
			$rootref[$template_select_dir_var] = $this->set_selected($rootref[$template_select_dir_var], $sort_dir);
		}
	}

	/**
	 * Marks the option with the specified value as selected
	 *
	 * @param $select
	 * @param $selected_option_value
	 * @return string
	 */
	protected function set_selected($select, $selected_option_value)
	{
		$selected = ' selected="selected"';
		$select = str_replace($selected, '', $select);
		return preg_replace("/(value=\"" . $selected_option_value . "\")/su", "$1" . $selected, $select);
	}

	/**
	 * Sanitize user input
	 *
	 * @param $event
	 */
	public function ucp_prefs_view_data($event)
	{
		if ($this->config['kasimi.sorttopics.ucp_enabled'] && $event['submit'])
		{
			// If the user submitted 'c' we need to reset it to a known value
			// so that data validation in ucp_prefs.php doesn't fail
			$this->ucp_sortby_created_time = $event['data']['topic_sk'] == 'c';
			if ($this->ucp_sortby_created_time)
			{
				$data = $event['data'];
				$data['topic_sk'] = 't';
				$event['data'] = $data;
			}
		}
	}

	/**
	 * Store user input
	 *
	 * @param $event
	 */
	public function ucp_prefs_view_update_data($event)
	{
		if (!is_null($this->ucp_sortby_created_time))
		{
			$sql_ary = $event['sql_ary'];
			$sql_ary['sort_topics_by_created_time'] = $this->ucp_sortby_created_time;
			$event['sql_ary'] = $sql_ary;
		}
	}
}

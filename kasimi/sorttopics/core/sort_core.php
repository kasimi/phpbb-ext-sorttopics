<?php

/**
 *
 * @package phpBB Extension - Sort Topics
 * @copyright (c) 2016 kasimi
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace kasimi\sorttopics\core;

abstract class sort_core
{
	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\template\context */
	protected $template_context;

	/**
	 * @param \phpbb\user $user
	 */
	public function set_user(\phpbb\user $user)
	{
		$this->user = $user;
	}

	/**
	 * @param \phpbb\template\context $template_context
	 */
	public function set_template_context(\phpbb\template\context $template_context)
	{
		$this->template_context = $template_context;
	}

	/**
	 * Updates the template data by inserting the (possibly selected) 'Created time' <option> into the $select tag right after the 'Post time' <option>
	 *
	 * @param string $template_select_key_var
	 * @param string $sort_key
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
	 * @param string $select
	 * @param string $selected_option_value
	 * @return string
	 */
	protected function set_selected($select, $selected_option_value)
	{
		$selected = ' selected="selected"';
		$select = str_replace($selected, '', $select);
		return preg_replace("/(value=\"" . $selected_option_value . "\")/su", "$1" . $selected, $select);
	}
}

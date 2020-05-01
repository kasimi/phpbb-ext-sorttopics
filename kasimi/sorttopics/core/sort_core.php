<?php

/**
 *
 * @package phpBB Extension - Sort Topics
 * @copyright (c) 2016 kasimi - https://kasimi.net
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace kasimi\sorttopics\core;

use phpbb\language\language;
use phpbb\template\template;

abstract class sort_core
{
	/** @var language */
	protected $lang;

	/** @var template */
	protected $template;

	public function set_language(language $lang): void
	{
		$this->lang = $lang;
	}

	public function set_template(template $template): void
	{
		$this->template = $template;
	}

	/**
	 * Updates the template data by inserting the (possibly selected) 'Created time' <option> into the $select tag right after the 'Post time' <option>
	 */
	protected function inject_created_time_select_option(string $template_select_key_var, string $sort_key, bool $template_select_dir_var = false, bool $sort_dir = false): void
	{
		$select = $this->template->retrieve_var($template_select_key_var);

		// Insert 'Created time'
		$this->lang->add_lang('common', 'kasimi/sorttopics');
		$new_option = '<option value="c">' . $this->lang->lang('SORTTOPICS_CREATED_TIME') . '</option>';
		$select = preg_replace("/(value=\"t\".*?<\/option>)/su", "$1" . $new_option, $select);

		// Fix selection
		$select = $this->set_selected($select, $sort_key);

		$this->template->assign_var($template_select_key_var, $select);

		if ($template_select_dir_var !== false && $sort_dir !== false)
		{
			$this->template->assign_var($template_select_dir_var, $this->set_selected($this->template->retrieve_var($template_select_dir_var), $sort_dir));
		}
	}

	/**
	 * Marks the option with the specified value as selected
	 */
	protected function set_selected(string $select, string $selected_option_value): string
	{
		$selected = ' selected="selected"';
		$select = str_replace($selected, '', $select);
		return preg_replace("/(value=\"" . $selected_option_value . "\")/su", "$1" . $selected, $select);
	}
}

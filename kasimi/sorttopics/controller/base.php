<?php

/**
 *
 * @package phpBB Extension - Sort Topics
 * @copyright (c) 2018 kasimi - https://kasimi.net
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace kasimi\sorttopics\controller;

abstract class base
{
	/** @var string */
	protected $tpl_name;

	/** @var string */
	protected $page_title;

	/**
	 * @param string $id
	 * @param string $mode
	 * @param string $u_action
	 */
	abstract public function main($id, $mode, $u_action);

	/**
	 * @return string
	 */
	public function get_tpl_name()
	{
		return $this->tpl_name;
	}

	/**
	 * @return string
	 */
	public function get_page_title()
	{
		return $this->page_title;
	}
}

<?php

/**
 *
 * @package phpBB Extension - Sort Topics
 * @copyright (c) 2018 kasimi - https://kasimi.net
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace kasimi\sorttopics\acp;

use kasimi\sorttopics\controller\base as controller_base;

abstract class base
{
	/** @var string */
	var $u_action;

	/** @var string */
	var $tpl_name;

	/** @var string */
	var $page_title;

	/** @var controller_base */
	protected $controller;

	/**
	 * @return string
	 */
	abstract protected function get_controller_service_id();

	/**
	 * @param string $id
	 * @param string $mode
	 * @throws \Exception
	 */
	public function main($id, $mode)
	{
		global $phpbb_container;

		$controller_service  = $this->get_controller_service_id();
		$this->controller = $phpbb_container->get($controller_service);
		$this->controller->main($id, $mode, $this->u_action);

		$this->tpl_name = $this->controller->get_tpl_name();
		$this->page_title = $this->controller->get_page_title();
	}
}

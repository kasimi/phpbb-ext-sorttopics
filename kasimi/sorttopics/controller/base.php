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

	abstract public function main(string $id, string $mode, string $u_action): void;

	public function get_tpl_name(): string
	{
		return $this->tpl_name;
	}

	public function get_page_title(): string
	{
		return $this->page_title;
	}
}

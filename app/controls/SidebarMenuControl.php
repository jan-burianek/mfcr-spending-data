<?php

namespace App\Controls;

use Nette\Application\UI\Control;

/**
 *
 *
 * SidebarMenuControl
 * @author Jan Buriánek <mail@janburianek.com>
 */
class SidebarMenuControl extends Control
{
	/**
	 * @var array
	 */
	private $lists = array();

	/**
	 * Renders the component
	 */
	public function render ()
	{
		// Podědění parametrů od nadřazené šablony
		$this->template->setParameters($this->presenter->template->getParameters());

		$this->template->lists = $this->lists;
		$this->template->setFile(__DIR__ . '/../presenters/templates/controls/sidebarMenu.latte');
		$this->template->render();
	}

	/**
	 *
	 *
	 * @param \App\Controls\SidebarList $list
	 */
	public function addList (SidebarList $list) {
		$this->lists[] = $list;
	}

	/**
	 * @param string $info
	 */
	public function setInfo($info)
	{
		$this->template->info = $info;
	}
}
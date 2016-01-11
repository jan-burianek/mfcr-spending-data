<?php

namespace App\Controls;

use Nette\Object;

/**
 * Class Setting
 */
class SidebarList extends Object
{
	/**
	 * @var
	 */
	private $id;

	/**
	 * @var
	 */
	private $heading;

	/**
	 * @var
	 */
	private $buttons;

	/**
	 * @param $id
	 * @param $heading
	 */
	function __construct($id, $heading)
	{
		$this->id = $id;
		$this->heading = $heading;
	}

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return mixed
	 */
	public function getHeading()
	{
		return $this->heading;
	}

	/**
	 * @return mixed
	 */
	public function getButtons()
	{
		return $this->buttons;
	}

	/**
	 *
	 *
	 * @param \App\Controls\SidebarButton $button
	 */
	public function addButton (SidebarButton $button) {
		$button->setParent($this);
		$this->buttons[] = $button;
	}

}
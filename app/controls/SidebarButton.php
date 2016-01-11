<?php

namespace App\Controls;

use Nette\Object;

/**
 * Class Setting
 */
class SidebarButton extends Object
{
	/**
	 * @var String
	 */
	private $id;
	/**
	 * @var String
	 */
	private $label;
	/**
	 * @var String
	 */
	private $icon;

	private $attributes = array();

	/**
	 * @var \App\Controls\SidebarList
	 */
	private $parent;

	/**
	 * @var \App\Controls\SidebarList
	 */
	private $children;

	/**
	 * @var String
	 */
	private $link;

	/** @var String */
	private $href;

	/**
	 * SidebarButton constructor.
	 *
	 * @param                                $id
	 * @param                                $label
	 * @param                                $link
	 * @param null                           $href
	 * @param null                           $icon
	 * @param \App\Controls\SidebarList|null $children
	 * @param null                           $attributes
	 * @param \App\Controls\SidebarList|null $parent
	 */
	function __construct($id, $label, $link = null, $href = null, $icon = null, SidebarList $children = null, $attributes = null, SidebarList $parent = null)
	{
		$this->id = $id;
		$this->label = $label;
		$this->icon = $icon;
		$this->attributes = $attributes;
		$this->parent = $parent;
		$this->children = $children;
		$this->link = $link;
		$this->href = $href;
	}

	/**
	 * @return mixed
	 */
	public function getLabel()
	{
		return $this->label;
	}

	/**
	 * @return mixed
	 */
	public function getIcon()
	{
		return $this->icon;
	}

	/**
	 * @return mixed
	 */
	public function getParent()
	{
		return $this->parent;
	}

	/**
	 * @return array
	 */
	public function getAttributes()
	{
		return $this->attributes;
	}

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param mixed $parent
	 */
	public function setParent($parent)
	{
		$this->parent = $parent;
	}

	/**
	 * @return SidebarList
	 */
	public function getChildren()
	{
		return $this->children;
	}

	/**
	 * @param SidebarList $children
	 */
	public function setChildren(SidebarList $children)
	{
		$this->children = $children;
	}

	/**
	 * @return String
	 */
	public function getLink()
	{
		return $this->link;
	}

	/**
	 * @param String $link
	 */
	public function setLink($link)
	{
		$this->link = $link;
	}

	/**
	 * @return String
	 */
	public function getHref()
	{
		return $this->href;
	}

	/**
	 * @param String $href
	 */
	public function setHref($href)
	{
		$this->href = $href;
	}

}
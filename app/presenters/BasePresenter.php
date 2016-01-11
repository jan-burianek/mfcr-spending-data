<?php

namespace App\Presenters;

use App\Controls\SidebarButton;
use App\Controls\SidebarList;
use App\Controls\SidebarMenuControl;
use Nette;
use App\Model;


/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
	protected $GRAPH_URI = 'http://janburianek.com/graph';
	protected $HOST = 'localhost';
	protected $PORT = '8890';

	/**
	 *
	 *
	 * @param null $class
	 *
	 * @return mixed
	 */
	protected function createTemplate($class = NULL)
	{
		$template = parent::createTemplate($class);
		$template->addFilter('format', function ($number) {
			return number_format($number, 0, ',', ' ');
		});
		return $template;
	}

	protected function getSparqlClient() {
		return new \EasyRdf_Sparql_Client(
			'http://'.$this->HOST.
			':'.$this->PORT.
			'/sparql?default-graph-uri=' . urlencode($this->GRAPH_URI)
		);
	}

	public function createComponentSidebarMenu () {
		$menu = new SidebarMenuControl();

		// List
		$list = new SidebarList('a', 'b');

		// Overview dashboard
		$list->addButton(new SidebarButton('g', 'Přehled', 'Homepage:', $this->link('Homepage:'), 'dashboard'));

		// Charts
		$chartsButton = new SidebarButton('c', 'Grafy', null, null, 'line-chart');

		$chartsList = new SidebarList('d', 'e');

		$chartsList->addButton(new SidebarButton('f', '2010', 'Charts:view', $this->link('Charts:view', 2010), 'clock-o'));
		$chartsList->addButton(new SidebarButton('g', '2011', 'Charts:view', $this->link('Charts:view', 2011), 'clock-o'));
		$chartsList->addButton(new SidebarButton('h', '2012', 'Charts:view', $this->link('Charts:view', 2012), 'clock-o'));
		$chartsList->addButton(new SidebarButton('i', '2013', 'Charts:view', $this->link('Charts:view', 2013), 'clock-o'));
		$chartsList->addButton(new SidebarButton('j', '2014', 'Charts:view', $this->link('Charts:view', 2014), 'clock-o'));

		$chartsButton->setChildren($chartsList);

		$list->addButton($chartsButton);

		// Stats
		$statsButton = new SidebarButton('k', 'Statistiky', null, null, 'table');

		$statsList = new SidebarList('l', 'm');

		$statsList->addButton(new SidebarButton('o', '2010', 'Stats:view', $this->link('Stats:view', 2010), 'clock-o'));
		$statsList->addButton(new SidebarButton('p', '2011', 'Stats:view', $this->link('Stats:view', 2011), 'clock-o'));
		$statsList->addButton(new SidebarButton('q', '2012', 'Stats:view', $this->link('Stats:view', 2012), 'clock-o'));
		$statsList->addButton(new SidebarButton('r', '2013', 'Stats:view', $this->link('Stats:view', 2013), 'clock-o'));
		$statsList->addButton(new SidebarButton('s', '2014', 'Stats:view', $this->link('Stats:view', 2014), 'clock-o'));

		$statsButton->setChildren($statsList);

		$list->addButton($statsButton);


		$menu->addList($list);

		return $menu;
	}
}

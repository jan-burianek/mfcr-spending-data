<?php

namespace App\Presenters;

use App\Controls\SidebarButton;
use App\Controls\SidebarList;
use App\Controls\SidebarMenuControl;
use Nette;
use App\Model;
use Nette\Application\Responses\JsonResponse;
use Nette\Application\Responses\TextResponse;


class ChartsPresenter extends BasePresenter
{
	public function renderView ($id) {
		$this->template->year = intval($id);
	}

	/**
	 *
	 *
	 * @param $offset
	 */
	public function actionTableToppartners ($offset) {
		if (!$this->isAjax()) {
			return;
		};

		$template = $this->createTemplate();
		$template->setFile(__DIR__ . '/templates/components/toppartners.latte');

		$template->rows = $this->getSparqlClient()->query(
			file_get_contents(__DIR__ . '/../../static-sparql-queries/stat.sp')
			. ' OFFSET ' . intval($offset)
		);

		$this->sendResponse(new TextResponse( (string) $template));
	}

}

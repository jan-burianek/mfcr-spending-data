<?php

namespace App\Presenters;

use Nette;
use App\Model;
use Nette\Application\Responses\TextResponse;


class StatsPresenter extends BasePresenter
{
	public function renderView ($id) {
		$this->template->year = intval($id);
	}

	/**
	 *
	 *
	 * @param $offset
	 * @param $year
	 */
	public function actionTableToppartners ($year, $offset) {
		if (!$this->isAjax()) {
			return;
		};

		$template = $this->createTemplate();
		$template->setFile(__DIR__ . '/templates/components/toppartners.latte');

		$query = $this->setUpYear(file_get_contents(__DIR__ . '/../../static-sparql-queries/time-top-partners.sp')
			. ' OFFSET ' . intval($offset), $year);

		$template->rows = $this->getSparqlClient()->query($query);

		$this->sendResponse(new TextResponse( (string) $template));
	}

	private function setUpYear($query, $year) {
		$f = str_replace("<year-from>", $year, $query);
		return str_replace("<year-to>", $year + 1, $f);
	}

}

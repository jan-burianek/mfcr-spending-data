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
	public function actionTableToppartners ($offset, $year) {
		if (!$this->isAjax()) {
			return;
		};

		$template = $this->createTemplate();
		$template->setFile(__DIR__ . '/templates/components/toppartners.latte');

		$query = file_get_contents(__DIR__ . '/../../static-sparql-queries/time-top-partners.sp')
			. ' OFFSET ' . intval($offset);

		$query = Nette\Utils\Strings::replace($query, '<date-from>', intval($year));
		$query = Nette\Utils\Strings::replace($query, '<date-to>', intval($year) + 1);

		$template->rows = $this->getSparqlClient()->query($query);

		$this->sendResponse(new TextResponse( (string) $template));
	}

}

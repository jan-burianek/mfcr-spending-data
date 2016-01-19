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

	public function actionListOfPartners ($year) {

		$template = $this->createTemplate();
		$template->setFile(__DIR__ . '/templates/components/list-of-partners.latte');

		$query = $this->setUpYear(
			file_get_contents(__DIR__ . '/../../static-sparql-queries/list-of-partners.sp')
			, $year
		);

		$template->rows = $this->getSparqlClient()->query($query);

		$this->sendResponse(new TextResponse( (string) $template));
	}

	private function setUpYear($query, $year) {
		$f = str_replace("<year-from>", $year, $query);
		return str_replace("<year-to>", $year + 1, $f);
	}

	/**
	 *
	 *
	 * @param $year
	 * @param $legalName
	 */
	public function actionGetPartnerInTime ($year, $legalName) {

		$f = $this->setUpYear(
			file_get_contents(__DIR__ . '/../../static-sparql-queries/partner-in-time.sp')
			, $year
		);

		$query = str_replace("<legal-name>", $legalName, $f);

		$result = $this->getSparqlClient()->query($query);

		$months = array();

		foreach ($result as $row) {
			$datetime = new \DateTime((string) $row->date);
			$date = $datetime->format('Y-m');

			if (!isset ($months[$date . '-01'])) {
				$months[$date . '-01'] = 0;
			}

			$months[$date . '-01'] += (string) $row->amount;
		}

		$data = array();
		foreach ($months as $date => $amount) {
			$data[] = array('date' => $date, 'amount' => $amount);
		}

		$this->sendResponse(new JsonResponse($data));
	}

}

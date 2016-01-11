<?php

namespace App\Presenters;

use Nette;
use App\Model;
use Nette\Application\Responses\JsonResponse;
use Nette\Application\Responses\TextResponse;


class HomepagePresenter extends BasePresenter
{

	/**
	 *
	 */
	public function actionGetTimeline () {

		if (!$this->isAjax()) {
			return;
		};

		$result = $this->getSparqlClient()->query(file_get_contents(__DIR__ . '/../../static-sparql-queries/query.sp'));

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

	/**
	 *
	 */
	public function actionGetComparsion () {

		if (!$this->isAjax()) {
			return;
		};

		$result = $this->getSparqlClient()->query(file_get_contents(__DIR__ . '/../../static-sparql-queries/query.sp'));

		$months = array();

		foreach ($result as $row) {
			$datetime = new \DateTime((string) $row->date);
			$month = $datetime->format('m');
			$year = $datetime->format('Y');

			if (!isset ($months[$month][$year])) {
				$months[$month][$year] = 0;
			}

			$months[$month][$year] += (string) $row->amount;
		}

		$data = array();
		foreach ($months as $month => &$years) {
			$years['y'] = '1970-'.$month.'-01';
			$data[] = $years;
		}

		$this->sendResponse(new JsonResponse($data));
	}

	/**
	 *
	 */
	public function actionGetDonut () {
		if (!$this->isAjax()) {
			return;
		};

		$result = $this->getSparqlClient()->query(file_get_contents(__DIR__ . '/../../static-sparql-queries/query.sp'));

		$years = array();

		foreach ($result as $row) {
			$datetime = new \DateTime((string) $row->date);
			$date = $datetime->format('Y');

			if (!isset ($years[$date])) {
				$years[$date] = 0;
			}

			$years[$date] += (string) $row->amount;
		}

		$data = array();
		foreach ($years as $date => $amount) {
			$data[] = array('label' => $date, 'value' => $amount);
		}

		$this->sendResponse(new JsonResponse($data));
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

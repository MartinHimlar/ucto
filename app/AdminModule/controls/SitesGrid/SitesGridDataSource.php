<?php

namespace AdminModule;

use Nette\Object;
use Nette\Utils\Paginator;
use Sites\SiteRepository;

class SitesGridDataSource extends Object
{
	/** @var SiteRepository $sites */
	protected $sites;

	public function __construct(SiteRepository $siteRepository)
	{
		$this->sites = $siteRepository;
	}


	public function getDatasource($filter, $order, Paginator $paginator = NULL)
	{
		$selection = $this->prepareDataSource($filter, $order);
		if ($paginator) {
			$selection->limit($paginator->getItemsPerPage(), $paginator->getOffset());
		}
		return $selection;
	}


	public function getDatasourceSum($filter, $order)
	{
		return $this->prepareDataSource($filter, $order)->count('*');
	}


	private function prepareDataSource($filter, $order)
	{
		$filters = array();
		foreach ($filter as $k => $v) {
			if (is_array($v)) {
				$filters[$k] = $v;
			} else {
				$filters[$k . ' LIKE ?'] = "%$v%";
			}
		}
		$selection = $this->sites->findAll()
			->where('default', 0)
			->where($filters);
		if ($order) {
			$selection->order(implode(' ', $order));
		}

		return $selection;
	}
}

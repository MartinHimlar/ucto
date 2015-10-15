<?php

namespace AdminModule;

use Nette;
use Nette\Forms\Container;
use Nette\Object;
use Nextras\Datagrid\Datagrid;

class SitesGridFactory extends Object
{

	public function create(SitesGridDataSource $dataSource, $parent = NULL, $name = NULL)
	{
		$grid = new Datagrid($parent, $name);
		$grid->addColumn('id', 'ID');
		$grid->addColumn('title', 'Stránka');
		$grid->addColumn('default', 'Výchozí');
		$grid->addColumn('active', 'Aktivní');

		$grid->setDataSourceCallback(callback($dataSource, 'getDatasource'));
		$grid->setPagination(20, callback($dataSource, 'getDatasourceSum'));

		$grid->setFilterFormFactory(function () {
			$form = new Container;
			$form->addText('id', NULL, 1, 3);
			$form->addText('title', NULL, 15);
			$form->addSubmit('filter', 'Odfiltruj')
				->getControlPrototype()->class = 'btn btn-primary';
			return $form;
		});

		$grid->addCellsTemplate(__DIR__ . '/../../../templates/components/datagrid/@bootstrap3.datagrid.latte');
		$grid->addCellsTemplate(__DIR__ . '/../../../templates/components/datagrid/@bootstrap3.extended-pagination.datagrid.latte');
		$grid->addCellsTemplate(__DIR__ . '/@sitesItemGrid.latte');
		return $grid;
	}

}

<?php

namespace AdminModule;

use Nette,
	App\Model;

class RolesPresenter extends BasePresenter
{

	public function renderDefault()
	{
		$this->template->title = 'Administrace';
	}

}

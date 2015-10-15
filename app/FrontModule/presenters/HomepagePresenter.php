<?php

namespace FrontModule;

use Nette;

class HomepagePresenter extends BasePresenter
{

	public function renderDefault()
	{
		$this->template->site = $this->sites->get(1);
	}

	public function renderContacts()
	{
		$this->template->site = $this->sites->get(3);
		$this->template->map = $this->sites->get(2);
	}

	public function actionSite($id)
	{
		$this->template->site = $this->sites->get($id);
	}

}

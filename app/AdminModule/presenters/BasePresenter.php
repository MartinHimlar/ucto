<?php


namespace AdminModule;

use Nette;

class BasePresenter extends \BasePresenter {

	public function startup()
	{
		parent::startup();
		if($this->user->loggedIn) {
			if ($this->user->isInRole('není')) {
				$this->flashMessage('Do téte sekce nemáte přístup', 'error');
				$this->redirect(':Front:Homepage:default');
			}
		} else {
			$this->redirect(':Front:Sign:in');
		}
	}

}

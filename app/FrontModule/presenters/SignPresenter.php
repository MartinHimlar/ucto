<?php

namespace FrontModule;

use Nette;
use Nette\Security\AuthenticationException;


/**
 * Sign in/out presenters.
 */
class SignPresenter extends BasePresenter
{
	public function actionIn()
	{
		parent::startup();
		if ($this->user->loggedIn) {
			$this->flashMessage('Někde je chyba, už jste přihlášení :)', 'info');
			$this->redirect('Homepage:default');
		}
	}


	public function actionOut()
	{
		$this->getUser()->logout();
		$this->flashMessage('Byli jste úspěšně odhlášeni.');
		$this->redirect('Homepage:Default');
	}

}

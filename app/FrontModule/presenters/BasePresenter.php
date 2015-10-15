<?php


namespace FrontModule;

use Nette;
use Nette\Security\AuthenticationException;
use Sites\CarouselManager;

class BasePresenter extends \BasePresenter
{

	public function startup()
	{
		parent::startup();
		$this->template->otherSites = $this->sites->findOtherActive();
		if (!$this->sites->get(1)) {
			throw new Nette\Application\AbortException();
		}
	}




	/**
	 * Sign-in form factory.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentSignInForm()
	{
		$form = new Nette\Application\UI\Form;
		$form->addText('username', 'Username: ')
			->setRequired('Prosím zadejte uživatelské jméno.');

		$form->addPassword('password', 'Password: ')
			->setRequired('Prosím zadejte heslo.');

		$form->addCheckbox('remember', ' Neodhlašovat');

		$form->addSubmit('send', 'Přihlásit');

		// call method signInFormSucceeded() on success
		$form->onSuccess[] = array($this, 'signInFormSucceeded');
		return $form;
	}


	public function signInFormSucceeded($form, $values)
	{
		if ($values->remember) {
			$this->getUser()->setExpiration('14 days', FALSE);
		} else {
			$this->getUser()->setExpiration('20 minutes', TRUE);
		}

		try {
			$this->getUser()->login($values->username, $values->password);
			$this->redirect('Homepage:');

		} catch (AuthenticationException $e) {
			$form->addError('Neplatné uživatelské jméno nebo heslo');
		}
	}

}

<?php

namespace App\Presenters;

use App\Model\ContentManager;
use Nette,
	App\Model;
use Nette\Application\UI\Form;
use Nette\Database\Context;
use Nette\Security\AuthenticationException;

abstract class BasePresenter extends Nette\Application\UI\Presenter
{
	const ROLE_ADMINISTRATOR = 3,
		ROLE_OWNER = 2,
		ROLE_REGISTERED = 1;

	/**
	 * @inject Context
	 * @var Context $database
	 */
	public $database;
	/**
	 * @inject ContentManager
	 * @var ContentManager $pages
	 */
	public $pages;

	/**
	 * Sign-in form factory.
	 * @return Form
	 */
	public function createComponentSignInForm()
	{
		$form = new Form;
		$form->addText('username', 'Uživatelské jméno:')
			->setAttribute("title", "Zadejte své prihlašovací jméno")
			->setRequired('Prosím vyplňte své uživatelské jméno.');

		$form->addPassword('password', 'Heslo:')
			->setAttribute("title", "Zadejte své prihlašovací heslo")
			->setRequired('Prosím vyplňte své heslo.');

		$form->addCheckbox('remember', 'Zůstat přihlášen');

		$form->addSubmit('send', 'Přihlásit');

		$form->onSuccess[] = $this->signInFormSucceeded;
		return $form;
	}


	public function signInFormSucceeded($form)
	{

		$values = $form->values;
		try {
			$this->getUser()->login($values->username, $values->password);
			$this->redirect('Homepage:');

		} catch (AuthenticationException $e) {
			$form->addError('Nesprávné přihlašovací jméno nebo heslo.');
		}
	}

	public function flashMessage($message, $type = 'info')
	{
		switch ($type) {
			case 'error':
				$bootstrapType = 'alert-danger';
				break;
			case 'warning':
				$bootstrapType = 'alert-warning';
				break;
			case 'success':
				$bootstrapType = 'alert-success';
				break;
			default:
				$bootstrapType = 'alert-info';
				break;
		}
		parent::flashMessage($message, $bootstrapType);
	}
}

<?php

namespace App\Presenters;

use App\Model\ContentManager;
use Nette,
	App\Model;
use Nette\Application\UI\Form;
use Nette\Database\Context;
use Nette\Security\AuthenticationException;


/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
	protected $database;
	protected $pages;


	public function __construct(Context $database, ContentManager $pages)
	{
		$this->database = $database;
		$this->pages = $pages;
	}

	/**
	 * Sign-in form factory.
	 * @return Form
	 */
	protected function createComponentSignInForm()
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

	public function isRole($role)
	{

		foreach ($this->getUser()->identity->getRoles() as $r) {
			if ($role === $r) {
				return true;
			} else
				return false;
		}

	}
}

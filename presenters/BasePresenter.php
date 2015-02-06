<?php

namespace App\Presenters;

use Nette,
	App\Model;


/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
        protected $database;
        protected $pages;
        

        public function __construct(Nette\Database\Context $database, \App\Model\ContentManager $pages)
        {
            $this->database = $database;
            $this->pages = $pages;
        }
    
        protected function createComponentSignInForm()
	{
		$form = new Nette\Application\UI\Form;
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

            } catch (Nette\Security\AuthenticationException $e) {
                $form->addError('Nesprávné přihlašovací jméno nebo heslo.');
            }
        }
        
        public function isRole($role)
        {
            
                foreach($this->getUser()->identity->getRoles() as $r)
                {
                    if($role === $r)
                    {
                        return true;
                    }
                    else
                        return false;
                }
           
        }
}

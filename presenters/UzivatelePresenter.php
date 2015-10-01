<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Presenters;

use App\Model,
 Nette\Application\UI\Form,
        Nette;
/**
 * Description of UzivatelePresenter
 *
 * @author PC-3
 */
class UzivatelePresenter extends BasePresenter {
    
    private $users;
    
    public function startup() {
        parent::startup();
        
        if(!$this->user->isLoggedIn() || $this->isRole('visitor')){
                $this->flashMessage ("Litujeme, nemáte dostatečné oprávnění.");
                $this->redirect ('Homepage:');
            }
    }
    
    public function __construct(Nette\Database\Context $database, Model\ContentManager $pages, \App\Model\UserManager $uzivatele) {
        parent::__construct($database, $pages);
        $this->users = $uzivatele;
    }
    
    public function renderDefault()
    {
         if(!$this->isRole('visitor') && $this->user->isLoggedIn())
            {
                $this->template->uzivatele = $this->users->findAll();
                $this->template->activeUser = $this->getUser()->id;
                $this->template->opravneni = $this->isRole("admin");
            }
            else
            {
                $this->flashMessage ("Litujeme, nemáte dostatečné oprávnění.");
                $this->redirect ('Homepage:');
            }
        
    }
    public function renderCreate()
    {
        if(!$this->isRole('admin'))
        {
            $this->flashMessage ("Litujeme, nemáte dostatečné oprávnění.");
                $this->redirect ('Homepage:');
        } 
    }
    
    public function renderEdit($id){
            if(!$this->isRole('visitor'))
            {
                if($id != null)
                {
                    $item = $this->users->findById($id);
                    $defaults = $this->editujHodnoty($item);
                    $this['novyUzivatel']->setDefaults($defaults);
                }
                else
                    {
                        $this->flashMessage('Nesprávná adresa, zkuste to znovu :)');
                        $this->redirect("Homepage:");
                    }
            }
            else
            {
                $this->flashMessage ("Litujeme, nemáte dostatečné oprávnění.");
                $this->redirect ('Homepage:');
            }
        }
    
    public function actionDelete($id)
    {
        if($this->isRole('admin')){
            $this->users->remove($id);
            $this->flashMessage('Uživatel byl smazán.');
            $this->redirect('Uzivatele:');
        }
        else{
            $this->flashMessage('Nemáte dostatečné oprávnění!');
            $this->redirect('Homepage:');
        }
    }
    
    protected function createComponentNovyUzivatel() {
        
        $opravneni = array(
            'visitor' => 'Kontrolor',
            'op' => 'Operátor',
            'admin' => 'Administrator'
        );
        
        $form = new \Nette\Application\UI\Form;
        
        $form->addHidden('id', null);
        $form->addHidden('edit',false);
        
        $form->addText('username','Uživatelské jméno:')
            ->setDisabled(!$this->isRole('admin'))
                ->setRequired('Chybějicí uživatelské jméno');
        
        $form->addPassword('password','Heslo:')
                ->setRequired('Chybějící heslo');
        
        $form->addPassword("confirm_password", "Znovu heslo:")
                ->addRule(Form::FILLED, "Potvrzovací heslo musí být vyplněné !")
                ->addConditionOn($form["password"], Form::FILLED)
                    ->addRule(Form::EQUAL, "Hesla se musí shodovat !", $form["password"]);
        
        $form->addSelect('role', 'Oprávnění', $opravneni)
            ->setDisabled(!$this->isRole('admin'))
            ->setPrompt('Vyberte')
                ->setRequired('Musíte vybrat jednu z možností');
        
        $form->addSubmit('submit', 'Uložit');
        
        $form->onSuccess[] = $this->NovyUzivatelSucceeded;
        
        return $form;
    }
    
    public function NovyUzivatelSucceeded($form)
    {
        $hodnoty = $form->values;
        
        try {
            if($hodnoty->edit)
                $this->users->update ($hodnoty->id, $hodnoty->username, $hodnoty->password, $hodnoty->role);
            else
                $this->users->add($hodnoty->username, $hodnoty->password, $hodnoty->role);
            
            $this->flashMessage('Uživatel úspěšně uložen.');
            $this->redirect('Uzivatele:'); 

            } catch (\Nette\Forms $e) {
                $form->addError('Někde se stala chyba :)');
            }
    }
    
    
        private function editujHodnoty($hodnoty){
            $default = array(
                'id' => $hodnoty->id,
                'username' => $hodnoty->username,
                'role' => $hodnoty->role,
                'edit' => true
            );
            return $default;
            
        }
}

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Presenters;

use App\Model\ContentManager;
use Nette,
    Nette\Utils\Strings,
    App\model;
use Nette\Database\Context;
use Nette\Utils\Paginator;

/**
 * Description of WebPresenters
 *
 * @author PC-3
 */
class WebPresenter extends BasePresenter {

	/** @var ContentManager  */
    private $stranky;

	/** @var Paginator  */
    protected $strankovac;
    
    public function startup() {
        parent::startup();
        
            if(!$this->user->isLoggedIn()){
                $this->flashMessage ("Litujeme, nemáte dostatečné oprávnění.");
                $this->redirect ('Homepage:');
            }
    }
    
    public function __construct(Context $database, ContentManager $pages) {
        parent::__construct($database, $pages);
        $this->strankovac = new Paginator;
        $this->strankovac->setItemCount($pages->findAll()->count());
        $this->strankovac->setItemsPerPage(10);
        $this->stranky = $pages;
    }
    
        public function renderDefault($str){
                if(isset($str))
                    $this->strankovac->setPage($str);
                else
                    $this->strankovac->setPage (1);
                $this->template->items = $this->stranky->limitFind($this->strankovac->getLength(), $this->strankovac->getOffset());
                $this->template->str = $this->strankovac;
                $this->template->opravneni = $this->isRole('admin');
        }
        
        public function renderCreate(){
            
            if($this->isRole('visitor')){
                $this->flashMessage('Nemáte dostatečné oprávnění!');
                $this->redirect('Homepage:');
            }
                
        }
    
        public function renderEdit($id){
            if(!$this->isRole('visitor'))
            {
                if($id != null)
                {
                    $item = $this->stranky->findByLink($id);
                    $defaults = $this->editujHodnoty($item);
                    $this['pageForm']->setDefaults($defaults);
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
        
        public function renderShow($id){
            $this->template->item = $this->stranky->findByLink($id);
        }
        
        public function actionDelete($id){
            if($this->isRole('admin')){
                if($this->stranky->findById($id))
                {
                    $this->stranky->removeById($id);
                    $this->flashMessage("Stránka byla smazána.");
                    $this->redirect("Web:");
                }
            }
                else
                {
                    $this->flashMessage("Neplatná stránka, nesmazáno!");
                    $this->redirect("Web:");
                }
        }
        
        public function createComponentPageForm() {
            $form = new \Nette\Application\UI\Form;
            
            $form->addHidden('id', '');
            $form->addHidden('edit', false);
            
            $form->addText('title','Menu zobrazení:')
                    ->setAttribute("title", "Text, který se bude zobrazovat v menu jako odkaz na tuto stránku")
                    ->setRequired('Text pro zobrazení menu musí být vyplněn!');
                        
            $form->addCheckbox('zobrazit','Zobrazit na stránkách')
                        ->setAttribute("title", "Zaškrtni, zda máš pocit, že stránka je kompletní pro zobrazení na stránkách");
            $form->addCheckbox('hlavnistranka','Úvodní stránka')
                        ->setAttribute("title", "Zaškrtni, zda má být tato stránka jako hlavní, tedy aby se zobrazovala jako první při otevření stránek");
            
            $form->addTextArea('content', 'obsah stránky')
                    ->setAttribute('class', 'mceEditor')
                    ->setRequired('Musíte vyplnit obsah stránky!');
            
            $form->addSubmit('send', 'Uložit')
                        ->setAttribute("title", "Uloží stránku");
                
            $form->getElementPrototype()->onsubmit('tinyMCE.triggerSave()');
            $form->onSuccess[] = $this->PageFormSucceeded;
            
            return $form;
        }
        
        public function PageFormSucceeded($form){
            $formValues = $form->values;
            $adresa = Strings::webalize($formValues->title);
            
            try{
                if($formValues->hlavnistranka)
                    $this->stranky->nullDefault();
                
                if($formValues->edit)
                    $this->stranky->update($formValues->id,$formValues->title, $formValues->content, $formValues->zobrazit, $adresa, $formValues->hlavnistranka);    
                else    
                    $this->stranky->add($formValues->title, $formValues->content, $adresa, $formValues->zobrazit, $formValues->hlavnistranka);
                
                $this->flashMessage('Stránka uložena do systému');
                $this->redirect('Web:');
            } catch (Exception $ex) {
                $this->flashMessage('Někde se stala chyba.');
                $this->redirect('Web:');
            }
        }
        
        private function editujHodnoty($hodnoty){
            $default = array(
                'id' => $hodnoty->id,
                'title' => $hodnoty->title,
                'zobrazit' => $hodnoty->zobraz,
                'content' => $hodnoty->content,
                'edit' => true,
                'hlavnistranka'=> $hodnoty->vychozi
            );
            return $default;
            
        }
    
}

<?php

namespace App\Presenters;

use Nette,
    Nette\Utils\Strings,
	App\Model;


/**
 * Homepage presenter.
 */
class HomepagePresenter extends BasePresenter
{
    protected $pages;
    
    public function __construct(Nette\Database\Context $database, Model\ContentManager $pages) {
        parent::__construct($database, $pages);
        $this->pages = $pages;
    }
        
	public function renderDefault($id)
	{
            if($this->pages->findAll()->count() > 0){
                $this->template->menuItems = $this->pages->findEnabled(true);
                
                if($id != null){
                    $this->template->items = $this->pages->findEnabled(true);
                    $this->template->id = $id;
                }
                else{
                    if($this->pages->findByDefault()->count() > 0){
                        $link = $this->pages->findByDefault()->fetch();
                        $this->redirect("Homepage:default", $link->link);
                    }
                    else{
                        $this->flashMessage("Není nastavena hlavní stránka!!! Změňte ji nebo kontaktujte správce stránek!!!");
                        $this->redirect("Homepage:homepage");
                    }
                }
            }
            else
                $this->redirect ("Homepage:homepage");
	}

}

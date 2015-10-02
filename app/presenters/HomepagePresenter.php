<?php

namespace App\Presenters;

use Nette,
	App\Model;


/**
 * Homepage presenter.
 */
class HomepagePresenter extends BasePresenter
{
	public function renderDefault($site)
	{
            if($this->pages->findAll()->count() > 0){
                $this->template->menuItems = $this->pages->findEnabled(true);
                
                if($site != null){
                    $this->template->items = $this->pages->findEnabled(true);
                    $this->template->site = $site;
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

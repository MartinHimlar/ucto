<?php

namespace AdminModule;

use App\SiteNotAddedException;
use App\SiteNotFoundException;
use Nette;
use Nette\Application\UI\Form;
use Sites\CarouselManager;
use Sites\SiteRepository;
use Tracy\Debugger;

class HomepagePresenter extends BasePresenter
{
	/** @var SiteRepository $sites */
	public $sites;

	public function __construct(SiteRepository $repository)
	{
		$this->sites = $repository;
	}

	public function startup()
	{
		parent::startup();
		$this->template->id = NULL;
		$this->template->siteTitle = 'Nová Stránka';
	}

	public function renderEditOther($id)
	{
		$this->template->setFile(__DIR__ . '/../templates/Homepage/addOther.latte');
		$this->template->id = $id;

		try {
			$site = $this->sites->get($id);
			if ($site->title) {
				$this->template->siteTitle = $site->title;
			}
			$this['siteForm']->setValues($site);
		} catch (SiteNotFoundException $e) {
			$this->flashMessage($e->getMessage(), 'error');
			$this->redirect('other');
		}
	}

	public function actionOther()
	{
		$factory = new SitesGridFactory();
		$dataSource = new SitesGridDataSource($this->sites);
		$this->addComponent($factory->create($dataSource), 'sites');
		$this->redrawControl('sites');
	}

	public function actionDeleteOther($id)
	{
		try {
			$this->sites->delete($id);
			$this->flashMessage('Stránka úspěšně smazána', 'success');
			$this->redirect('other');
		} catch (SiteNotFoundException $e) {
			$this->flashMessage('Stránka nelze smazat, již neexistuje');
			$this->redirect('other');
		}
	}

	public function handleAddOther($siteUrl)
	{
		$this['siteForm']['url']->setValue($siteUrl);
		$this->redrawControl('formInput');
		$this->invalidateControl('formInput');
	}

	public function createComponentSiteForm()
	{
		$form = new Form();

		$form->addText('title', 'Název:')
			->setHtmlId('siteTitle')
			->setRequired('Název musí být vyplněn');

		$form->addText('url', 'Adresa:')
			->setHtmlId('siteUrl');

		$form->addTextArea('content')
			->getControlPrototype()->id('siteTextarea')
			->setRequired('Obsah musí být vyplněn');

		$form->addCheckbox('active', 'Aktivní')
		->setValue(TRUE);

		$form->addHidden('id', NULL);

		$form->addText('map_url', 'odkaz URL mapy:');

		$form->addSubmit('send', 'Uložit')
			->getControlPrototype()->class('btn btn-primary');

		$form->onSuccess[] = array($this, 'siteFormSuccessed');

		return $form;
	}

	public function siteFormSuccessed(Form $form)
	{
		$values = $form->getValues(TRUE);
		try {
			if ($values['id']) {
				$this->sites->update($values);
				$this->flashMessage('Stránka úspěšně upravena', 'success');
			} else {
				$this->sites->add($values);
				$this->flashMessage('Stránka úspěšně vytvořena', 'success');
			}

			$this->redirect('other');
		} catch (SiteNotAddedException $e) {
			Debugger::log($e->getMessage());
			$this->flashMessage($e->getMessage(), 'error');
			$this->redirect('this');
		}
	}
}

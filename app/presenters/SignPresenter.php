<?php

namespace App\Presenters;

use Nette,
	App\Model;


/**
 * Sign in/out presenters.
 */
class SignPresenter extends BasePresenter
{



	


	public function actionOut()
        {
            $this->getUser()->logout();
            $this->flashMessage('Odhlášení bylo úspěšné.');
            $this->redirect('Homepage:');
        }

}

<?php

namespace App;

use Nette,
	Nette\Application\Routers\RouteList,
	Nette\Application\Routers\Route,
	Nette\Application\Routers\SimpleRouter;


/**
 * Router factory.
 */
class RouterFactory
{

	/**
	 * @return \Nette\Application\IRouter
	 */
	public function createRouter()
	{
		$router = new RouteList();
		$router[] = new Route('web/<action>[/<id>]', 'Web:default');
		$router[] = new Route('/<site>', 'Homepage:default');
		$router[] = new Route('<presenter>/<action>[/<id>]', 'Uzivatele:default');
		return $router;
	}

}

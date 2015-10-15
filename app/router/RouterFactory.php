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
	public static function createRouter()
	{
		$router = new RouteList();
		$router[] = new Route('index.php', 'Front:Homepage:default', Route::ONE_WAY);

		$router[] = $adminRouter = new RouteList('Admin');
		$adminRouter[] = new Route('admin[/<presenter>[/<action>[/<id>]]]', 'Homepage:default');

		$router[] = $frontRouter = new RouteList('Front');
		$frontRouter[] = new Route('katalog', 'Magazine:default');
		$frontRouter[] = new Route('kontakty', 'Homepage:contacts');
		$frontRouter[] = new Route('o-nas', 'Homepage:location');
		$frontRouter[] = new Route('<presenter>/<action>[/<id>]', 'Homepage:default');

		$frontRouter[] = new Route('', 'Homepage:default');
		return $router;
	}

}

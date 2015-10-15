<?php
/**
 * Created by PhpStorm.
 * User: Martin
 * Date: 10. 7. 2015
 * Time: 22:37
 */

namespace Sites;


use App\SiteNotAddedException;
use App\SiteNotFoundException;
use Nette\Database\Context;
use Nette\Object;

class SiteRepository extends Object
{
	/** @var Context $db */
	private $db;

	public function __construct(Context $database)
	{
		$this->db = $database;
	}

	public function findAll()
	{
		return $this->db->table('sites');
	}

	public function findOtherActive()
	{
		return $this->findAll()
			->where('default', 0)
			->where('active', 1);
	}

	public function get($id)
	{
		$row = $this->findAll()->where('id', $id)->fetch();

		if (!$row) {
			throw new SiteNotFoundException('Stránka nenalezena');
		}

		return $row;
	}

	public function add($values)
	{
		$db = $this->findAll();

		if ($db->where('title', $values['title'])->fetch()) {
			throw new SiteNotAddedException('Existující název stránky');
		}

		$db->insert($values);
	}

	public function update($values)
	{
		$db = $this->findAll();
		$selection = clone $db;

		if ($db->where('title', $values['title'])->where('id NOT LIKE', $values['id'])->fetch()) {
			throw new SiteNotAddedException('Existující název stránky');
		}
		$selection->where('id', $values['id'])->update($values);
	}

	public function delete($id)
	{
		$row = $this->findAll()->where('id', $id);

		if (!$row->fetch()) {
			throw new SiteNotFoundException('Stránka nenalezena');
		}

		$row->delete();
	}
}
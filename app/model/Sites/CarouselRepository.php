<?php
/**
 * Created by PhpStorm.
 * User: Martin
 * Date: 25. 8. 2015
 * Time: 20:37
 */

namespace Sites;


use App\CarouselNotFoundException;
use Nette\Database\Context;
use Nette\Object;

class CarouselRepository extends Object
{
	const TABLE_NAME = 'carousel';

	/** @var Context $db */
	private $db;

	public function __construct(Context $database)
	{
		$this->db = $database;
	}

	/**
	 * find collection of
	 *
	 * @return \Nette\Database\Table\Selection
	 */
	public function find()
	{
		return $this->db->table(self::TABLE_NAME);
	}

	/**
	 * Add new row
	 *
	 * @param $values
	 * @return bool|int|\Nette\Database\Table\IRow
	 */
	public function add($values)
	{
		return $this->find()->insert($values);
	}

	/**
	 * get current image row
	 *
	 * @param $id
	 * @return bool|\Nette\Database\Table\IRow
	 */
	public function get($id)
	{
		return $this->find()
			->where('id', $id)
			->fetch();
	}

	/**
	 * remove current row if exist
	 *
	 * @param $id
	 */
	public function remove($id)
	{
		if (!$this->get($id)) {
			throw new CarouselNotFoundException('nelze smazat obrázek. Již neexistuje.');
		}

		$this->find()
			->where('id', $id)
			->delete();
	}
}
<?php

namespace App\Model;

use Nette;

class ContentManager extends \Nette\Object
{

	const
		TABLE_NAME = 'web',
		COLUMN_ID = 'id',
		COLUMN_TITLE = 'title',
		COLUMN_CONTENT = 'content',
		COLUMN_SHOW = 'show',
		COLUMN_LINK = 'link',
		COLUMN_DEFAULT = 'default';


	/** @var Nette\Database\Context */
	private $db;

	public function __construct(\Nette\Database\Context $database)
	{
		$this->db = $database;
	}

	public function add($title, $content, $link, $zobraz, $vychozi)
	{
		$this->db->table(self::TABLE_NAME)->insert(array(
			self::COLUMN_TITLE => $title,
			self::COLUMN_CONTENT => $content,
			self::COLUMN_LINK => $link,
			self::COLUMN_SHOW => $zobraz,
			self::COLUMN_DEFAULT => $vychozi
		));
	}

	public function removeAll()
	{
		$this->findAll()->delete();
	}

	public function removeById($content_id)
	{
		$this->findById($content_id)->delete();
	}

	public function update($id, $title, $content, $zobraz, $link, $vychozi)
	{
		$data = array(
			self::COLUMN_ID => $id,
			self::COLUMN_TITLE => $title,
			self::COLUMN_CONTENT => $content,
			self::COLUMN_SHOW => $zobraz,
			self::COLUMN_LINK => $link,
			self::COLUMN_DEFAULT => $vychozi
		);
		$this->findById($id)->update($data);
	}

	public function findAll()
	{
		return $this->db->table(self::TABLE_NAME);
	}

	public function findEnabled($enabled)
	{
		if ($enabled)
			$zobraz = 1;
		else
			$zobraz = 0;
		return $this->findAll()->where(self::COLUMN_SHOW, $zobraz);
	}

	public function findById($id)
	{
		return $this->findAll()->get($id);
	}

	public function findByLink($link)
	{
		return $this->findAll()->where(self::COLUMN_LINK, $link)->fetch();
	}

	public function findByDefault()
	{
		return $this->findEnabled(true)->where(self::COLUMN_DEFAULT, 1);
	}

	public function limitFind($lenght, $offset)
	{
		return $this->findAll()->limit($lenght, $offset);
	}

	public function nullDefault()
	{
		$rows = $this->findAll();
		foreach ($rows as $row) {
			if ($row->default != 0) {
				$this->update($row->id, $row->title, $row->content, $row->show, $row->link, 0);
			}
		}
	}
}

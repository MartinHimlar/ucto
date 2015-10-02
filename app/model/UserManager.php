<?php

namespace App\Model;

use Latte\RuntimeException;
use Nette;
use Nette\Security\Passwords;


/**
 * Users management.
 */
class UserManager extends Nette\Object implements Nette\Security\IAuthenticator
{
	const
		TABLE_NAME = 'users',
		COLUMN_ID = 'id',
		COLUMN_NAME = 'username',
		COLUMN_PASSWORD_HASH = 'password',
		COLUMN_ROLE = 'role';


	/** @var Nette\Database\Context */
	private $database;


	public function __construct(Nette\Database\Context $database)
	{
		$this->database = $database;
	}


	/**
	 * Performs an authentication.
	 * @return Nette\Security\Identity
	 * @throws Nette\Security\AuthenticationException
	 */
	public function authenticate(array $credentials)
	{
		list($username, $password) = $credentials;

		$row = $this->database->table(self::TABLE_NAME)
			->where(self::COLUMN_NAME, $username)
			->fetch();

		if (!$row) {
			throw new Nette\Security\AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);

		} elseif (!Passwords::verify($password, $row[self::COLUMN_PASSWORD_HASH])) {
			throw new Nette\Security\AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);

		} elseif (Passwords::needsRehash($row[self::COLUMN_PASSWORD_HASH])) {
			$row->update(array(
				self::COLUMN_PASSWORD_HASH => Passwords::hash($password),
			));
		}

		$arr = $row->toArray();
		unset($arr[self::COLUMN_PASSWORD_HASH]);
		return new Nette\Security\Identity($row[self::COLUMN_ID], $row[self::COLUMN_ROLE], $arr);
	}


	/**
	 * Adds new user.
	 * @param $username string
	 * @param $password string
	 * @throws DuplicateNameException
	 */
	public function add($username, $password, $role = NULL)
	{
		try {
			$this->database->table(self::TABLE_NAME)->insert(array(
				self::COLUMN_NAME => $username,
				self::COLUMN_PASSWORD_HASH => Passwords::hash($password),
				self::COLUMN_ROLE => $role,
			));
		} catch (Nette\Database\UniqueConstraintViolationException $e) {
			throw new DuplicateNameException;
		}
	}

	/**
	 * @param $id
	 * @throws UserNotFoundException
	 */
	public function remove($id)
	{
		if ($this->getById($id)) {
			$this->findAll()->where('id', $id)->delete();
		} else {
			throw new UserNotFoundException('Nelze smazat uživatele, neexistující uživatel');
		}
	}

	/**
	 * @return Nette\Database\Table\Selection
	 */
	public function findAll()
	{
		return $this->database->table(self::TABLE_NAME);
	}

	/**
	 * @param $id
	 * @return Nette\Database\Table\IRow
	 */
	public function getById($id)
	{
		return $this->findAll()->get($id);
	}

	/**
	 * @param $id
	 * @param $name
	 * @param $password
	 * @param $role
	 */
	public function update($id,$name,$password,$role){
		$data = array(
			self::COLUMN_ID => $id,
			self::COLUMN_NAME => $name,
			self::COLUMN_PASSWORD_HASH => Passwords::hash($password),
			self::COLUMN_ROLE => $role,
		);
		$this->getById($id)->update($data);
	}

}



class DuplicateNameException extends \Exception
{}
class UserNotFoundException extends RuntimeException
{}

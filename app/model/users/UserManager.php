<?php
namespace App\Users;

use App\AlreadyUserExistException;
use Nette,
	Nette\Security\Passwords;
use App\UserNotFoundException;
use Nette\Database\SqlLiteral;

class UserManager extends Nette\Object
{

	/** @var Nette\Database\Context */
	private $database;

	public function __construct(Nette\Database\Context $database)
	{
		$this->database = $database;
	}

	/**
	 * @return Nette\Database\Table\Selection
	 */
	public function getAll()
	{
		return $this->database->table('users');
	}

	/**
	 * Return User repository
	 * @return Nette\Database\Table\Selection
	 */
	public function findUser($userId)
	{
		return $this->getAll()
			->where('id', $userId)
			->fetch();
	}

	/**
	 * Adds new user.
	 * @param array $credentials
	 */
	public function add($credentials)
	{
		$rows = $this->getAll();
		if ((count($rows->where('nickname', $credentials['nickname'])) > 0) ||
			(count($rows->where('email', $credentials['email'])) > 0)
		) {
			throw new AlreadyUserExistException('Uživatel s tímto uživatelským jménem nebo emailem již existuje');
		}
		$credentials['password'] = Passwords::hash($credentials['password']);
		$this->database->table('users')->insert($credentials);
	}

	public function update($values)
	{
		$id = $values['id'];
		unset($values['id']);
		return $this->findUser($id)->update($values);
	}

	/**
	 * Set user password
	 * @param int $userId
	 * @param string $plaintext
	 *
	 * @return bool
	 * @throws UserNotFoundException
	 */
	public function setPassword($userId, $plaintext)
	{
		$user = $this->database->table('users')->get($userId);
		if (!$user) {
			throw new UserNotFoundException('Neplatný uživatel');
		}
		$user->update(array(
			'password' => Passwords::hash($plaintext),
		));
		return TRUE;
	}

	/**
	 * change bool value of user account activated
	 *
	 * @param int $userId
	 */
	public function changeUserPublic($userId)
	{
		$this->getAll()->where('id', $userId)->update(array('public' => new SqlLiteral('IF(' . 'public' . ' = 1, 0, 1)')));
	}

	/**
	 * get users roles
	 *
	 * @return array(id=>title)
	 */
	public function getRoles()
	{
		return $this->database->table('roles')->fetchPairs('id', 'title');
	}
}

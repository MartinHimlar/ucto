<?php


namespace App\UserManager;

use App\UserNotFoundException;
use App\Users\UserManager;
use Nette;
use Nette\Security as NS;
use Nette\Security\AuthenticationException;
use Nette\Security\Passwords;

class Authenticator extends Nette\Object implements NS\IAuthenticator
{
	public $database;

	function __construct(Nette\Database\Context $database)
	{
		$this->database = $database;
	}

	/**
	 * Performs an authentication.
	 * @return Nette\Security\Identity
	 * @throws Nette\Security\AuthenticationException
	 */
	function authenticate(array $credentials)
	{
		list($username, $password) = $credentials;

		$row = $this->database->table('users')->where('nickname', $username)->fetch();

		if (!$row) {
			throw new AuthenticationException('Neplatné uživatelské jméno.', self::IDENTITY_NOT_FOUND);

		} elseif (!Passwords::verify($password, $row->password)) {
			throw new AuthenticationException('Neplatné uživatelské heslo.', self::INVALID_CREDENTIAL);

		} elseif (Passwords::needsRehash($row->password)) {
			$row->update(array(
				'password' => Passwords::hash($password),
			));
		} elseif (!$row->public) {
			throw new AuthenticationException('Váš uživatelský účet byt deaktivován. Kontaktuje nás prosím.');
		}
		$role = $row->ref('role_id')->title;

		$arr = $row->toArray();
		unset($arr['password']);
		return new Nette\Security\Identity($row->id, $role, $arr);
	}
}

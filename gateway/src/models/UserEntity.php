<?php

namespace app\models;

use ValueError;

require_once __DIR__ . '/../../vendor/autoload.php';

class UserEntity
{
    private int $id;
    private string $login;
    private string $password;
    private string $role;
    private static array $roles = ['admin', 'client'];

    public function __construct()
    {
    }

    public static function withParams(string $login, string $password, string $role = 'client')
    {
        $user = new UserEntity();
        $hash_passwd = password_hash(
            self::checkIsNull($password), PASSWORD_BCRYPT);
        if ($hash_passwd) {
            $user->login = self::checkIsNull($login);
            $user->password = $hash_passwd;
            $user->role = self::checkAllowabilityRoles($role);
        } else throw new ValueError('Password cannot be hashed', 1);
        return $user;
    }

    public static function withCleanParams(string $login, string $password, string $role = 'client')
    {
        $user = new UserEntity();
        $user->login = self::checkIsNull($login);
        $user->password = self::checkIsNull($password);
        $user->role = self::checkAllowabilityRoles($role);
        return $user;
    }

    private static function checkIsNull($value)
    {
        if (empty($value)) {
            throw new ValueError('Login or password cannot be null', 1);
        } else return $value;
    }

    private static function checkAllowabilityRoles($role)
    {
        if (!array_intersect([$role], self::$roles)) {
            throw new ValueError('Role cannot be no admin or no client', 1);
        } else return $role;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return mixed|string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param string $login
     */
    public function setLogin(string $login): void
    {
        $this->login = self::checkIsNull($login);
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = self::checkIsNull($password);
    }

    /**
     * @param string $role
     */
    public function setRole(string $role): void
    {
        $this->role = self::checkAllowabilityRoles($role);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
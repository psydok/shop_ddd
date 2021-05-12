<?php

namespace app\repositories;

use app\models\UserEntity;
use PDO;
use PDOException;

require_once __DIR__ . '/../../vendor/autoload.php';

class UsersRepository extends Database
{
    public function __construct()
    {
        parent::__construct();
        $query_res = $this->getConnection()->query("SELECT * FROM users;");
        $count = count($query_res->fetchAll());
        if ($count == 0) {
            $db = $this->getConnection();
            $user = UserEntity::withParams('admin', 'admin', 'admin');
            $query = $db->prepare("INSERT INTO users (login, password) VALUES (?, ?)");
            $query->execute([$user->getLogin(), $user->getPassword()]);
            $query = $db->prepare("INSERT INTO roles_users (user_id, role) VALUES (?, ?)");
            $query->execute([$db->lastInsertId(), $user->getRole()]);
        }
    }

    private function execute($statement, array $input_parameters, $msgError = null)
    {
        $db = $this->getConnection();
        try {
            if (!$query = $db->prepare($statement)) {
                echo 'prepare: ' . $msgError . '. ' . var_export($db->errorInfo(), true);
                return false;
            }

            if (!$query->execute($input_parameters)) {
                echo 'execute: ' . $msgError . '. ' . var_export($input_parameters, true);
                return false;
            }
            return $query;
        } catch (PDOException $e) {
            $db->rollback();
            echo "Error!: " . $e->getMessage() . "</br>";
        }
        return false;
    }

    public function insertUser(UserEntity $user): void
    {
        $stmtNewUser = "INSERT INTO users (login, password) VALUES (?, ?)";
        $stmtRoleUser = "INSERT INTO roles_users (user_id, role) VALUES (?, ?)";
        $this->execute($stmtNewUser, [$user->getLogin(), $user->getPassword()], 'add new user');
        $idUser = $this->getConnection()->lastInsertId();
        $this->execute($stmtRoleUser, [$idUser, $user->getRole()], 'add role user\'s');
    }

    public function compareUser(UserEntity $user): bool
    {
        $userFromDb = $this->getByLogin($user->getLogin());
        if (!$userFromDb)
            return false;
        if (password_verify($user->getPassword(), $userFromDb->getPassword()))
            return $userFromDb->getId();
        return false;
    }

    public function getByLogin(string $login)
    {
        $statGetUser = "SELECT * FROM users AS u INNER JOIN roles_users AS r ON (u.id=r.user_id) "
            . "WHERE u.login=?";
        $query = $this->execute($statGetUser, [$login], 'get user');
        if (!$query)
            return false;

        $arrUser = $query->fetch(PDO::FETCH_ASSOC);
        $userEntity = new UserEntity();
        $userEntity->setLogin($arrUser['login']);
        $userEntity->setPassword($arrUser['password']);
        $userEntity->setRole($arrUser['role']);
        $userEntity->setId($arrUser['id']);
        return $userEntity;
    }
}
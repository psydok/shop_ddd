<?php

namespace app\repositories;

use app\models\UserEntity;
use PDO;
use PDOException;

require_once __DIR__ . '/../../vendor/autoload.php';

class UsersRepository extends Database
{
    private function execute($statement, array $input_parameters, $msgError = null)
    {
        $db = $this->getConnection();
        try {
            if (!$query = $db->prepare($statement)) {
                echo 'prepare: ' . $msgError . '. ' . $db->errorInfo()[2];
                return null;
            }

            if (!$query->execute($input_parameters)) {
                echo 'execute: ' . $msgError . '. ' . var_export($db->errorInfo(), true);
                return null;
            }
            return $query;
        } catch (PDOException $e) {
            $db->rollback();
            echo "Error!: " . $e->getMessage() . "</br>";
        }
        return null;
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
        if ($userFromDb->getPassword() === $user->getPassword())
            return $userFromDb->getId();
        return false;
    }

    public function getByLogin(string $login): UserEntity
    {
        $statGetUser = "SELECT * FROM users AS u INNER JOIN roles_users AS r ON (u.id=r.user_id) "
            . "WHERE u.login=?";
        $query = $this->execute($statGetUser, [$login], 'get user');
        $arrUser = $query->fetch(PDO::FETCH_ASSOC);
        echo var_dump($arrUser);
        $userEntity = new UserEntity();
        $userEntity->setLogin($arrUser['login']);
        $userEntity->setPassword($arrUser['password']);
        $userEntity->setRole($arrUser['role']);
        $userEntity->setId($arrUser['id']);
        return $userEntity;
    }
}
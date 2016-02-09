<?php

namespace DAO;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Manager\User;

class UserDAO extends DAO implements UserProviderInterface
{
    /**
     * Returns a user matching the supplied id.
     *
     * @param integer $id The user id.
     *
     * @return \Manager\User|throws an exception if no matching user is found
     */
    public function find($id) {
        $sql = "SELECT * FROM users WHERE user_id=?";
        $row = $this->getDb()->fetchAssoc($sql, array($id));

        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new \Exception("No user matching id " . $id);
    }

    /**
     * Returns a list of all users, sorted by role and name.
     *
     * @return array A list of all users.
     */
    public function findAll() {
        $sql = "SELECT * FROM users ORDER BY role, pseudo";
        $result = $this->getDb()->fetchAll($sql);

        // Convert query result to an array of domain objects
        $entities = array();
        foreach ($result as $row) {
            $id = $row['user_id'];
            $entities[$id] = $this->buildDomainObject($row);
        }
        return $entities;
    }


    /**
     * Saves a user into the database.
     *
     * @param \Manager\User $user The user to save
     */
    public function save(User $user) {
        $userData = array(
            'pseudo' => $user->getUsername(),
            'salt' => $user->getSalt(),
            'password' => $user->getPassword(),
            'role' => $user->getRole(),
            'prenom' => $user->getUserprenom(),
            'nom' => $user->getUserom(),
            'mail' => $user->getUsermail(),
            'date_inscription' => $user->getUserdateinscription()
            );

        if ($user->getId()) {
            // The user has already been saved : update it
            $this->getDb()->update('users', $userData, array('user_id' => $user->getId()));
        } else {
            // The user has never been saved : insert it
            $this->getDb()->insert('users', $userData);
            // Get the id of the newly created user and set it on the entity.
            $id = $this->getDb()->lastInsertId();
            $user->setId($id);
        }
    }

    /**
     * Removes a user from the database.
     *
     * @param @param integer $id The user id.
     */
    public function delete($id) {
        // Delete the user
        $this->getDb()->delete('users', array('user_id' => $id));
    }




    /**
     * {@inheritDoc}
     */
    public function loadUserByUsername($username)
    {
        $sql = "SELECT * FROM users where pseudo=?";
        $row = $this->getDb()->fetchAssoc($sql, array($username));

        if ($row)
            return $this->buildDomainObject($row);
        else
            throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username));
    }

    /**
     * {@inheritDoc}
     */
    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $class));
        }
        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * {@inheritDoc}
     */
    public function supportsClass($class)
    {
        return 'Manager\User' === $class;
    }

    /**
     * Creates a User object based on a DB row.
     *
     * @param array $row The DB row containing User data.
     * @return \MicroCMS\Domain\User
     */
    protected function buildDomainObject($row) {
        $user = new User();
        $user->setId($row['user_id']);
        $user->setUsername($row['pseudo']);
        $user->setPassword($row['password']);
        $user->setSalt($row['salt']);
        $user->setRole($row['role']);
        $user->setUserprenom($row['prenom']);
        $user->setUsernom($row['nom']);
        $user->setUsermail($row['mail']);
        $user->setUserdateinscription($row['date_inscription']);
        return $user;
    }
}

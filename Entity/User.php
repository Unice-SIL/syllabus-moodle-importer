<?php

namespace UniceSIL\SyllabusMoodleImporterBundle\Entity;


use UniceSIL\SyllabusImporterToolkit\User\UserInterface;

/**
 * Class User
 * @package UniceSIL\SyllabusMoodleImporterBundle\Entity
 */
class User implements UserInterface
{
    /**
     * @var string
     */
    private $username;

    /**
     * @var string|null
     */
    private $firstname;

    /**
     * @var string|null
     */
    private $lastname;

    /**
     * @var string|null
     */
    private $email;

    /**
     * Get user username
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Get user firstname
     * @return null|string
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * Get user lastname
     * @return null|string
     */
    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    /**
     * Get user email
     * @return null|string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $username
     * @return User
     */
    public function setUsername(string $username): User
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @param null|string $firstname
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * @param null|string $lastname
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @param null|string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * If return true, create the permission if does not already exist in Syllabus database
     * @return bool
     */
    public function createIfNotExist(): bool
    {
        return true;
    }
}
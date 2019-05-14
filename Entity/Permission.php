<?php
namespace UniceSIL\SyllabusMoodleImporterBundle\Entity;


use UniceSIL\SyllabusImporterToolkit\Course\CourseInfoInterface;
use UniceSIL\SyllabusImporterToolkit\Permission\PermissionInterface;
use UniceSIL\SyllabusImporterToolkit\User\UserInterface;

/**
 * Class Permission
 * @package UniceSIL\SyllabusMoodleImporterBundle\Entity
 */
class Permission implements PermissionInterface
{
    /**
     * @var CourseInfo
     */
    private $courseInfo;

    /**
     * @var string
     */
    private $permission;

    /**
     * @var User
     */
    private $user;

    /**
     * Get course info
     * @return CourseInfoInterface
     */
    public function getCourseInfo(): CourseInfoInterface
    {
        return $this->courseInfo;
    }

    /**
     * Get permission
     * @return string
     */
    public function getPermission(): string
    {
        return $this->permission;
    }

    /**
     * @return UserInterface
     */
    public function getUser(): UserInterface
    {
        return $this->user;
    }

    /**
     * @param CourseInfo $courseInfo
     * @return Permission
     */
    public function setCourseInfo(CourseInfo $courseInfo): Permission
    {
        $this->courseInfo = $courseInfo;

        return $this;
    }

    /**
     * @param string $permission
     * @return Permission
     */
    public function setPermission(string $permission): Permission
    {
        $this->permission = $permission;

        return $this;
    }

    /**
     * @param User $user
     * @return Permission
     */
    public function setUser(User $user): Permission
    {
        $this->user = $user;

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
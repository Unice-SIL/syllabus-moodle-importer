<?php

namespace UniceSIL\SyllabusMoodleImporterBundle\Entity;


use UniceSIL\SyllabusImporterToolkit\Course\CourseInfoInterface;
use UniceSIL\SyllabusImporterToolkit\Permission\PermissionCollection;
use UniceSIL\SyllabusImporterToolkit\Structure\StructureInterface;

/**
 * Class CourseInfo
 * @package UniceSIL\SyllabusMoodleImporterBundle\Entity
 */
class CourseInfo implements CourseInfoInterface
{
    /**
     * @var string
     */
    private $yearId;

    /**
     * @var PermissionCollection
     */
    private $permissions;

    /**
     * @return null|string
     */
    public function getYearId(): string
    {
        return $this->yearId;
    }

    /**
     * @param string $yearId
     * @return $this
     */
    public function setYearId(string $yearId): CourseInfo
    {
        $this->yearId = $yearId;
        return $this;
    }

    /**
     * Get Structure
     * @return StructureInterface
     */
    public function getStructure(): StructureInterface
    {
        // TODO: Implement getStructure() method.
    }

    /**
     * Get course title
     * @return string
     */
    public function getTitle(): string
    {
        return '';
    }

    /**
     * @return float|null
     */
    public function getEcts(): ?float
    {
        return null;
    }

    /**
     * Get course ECTS
     * @return null|string
     */
    public function getDomain(): ?string
    {
        return null;
    }

    /**
     * Get course period
     * @return null|string
     */
    public function getPeriod(): ?string
    {
        return null;
    }

    /**
     * get course cm hours in class
     * @return float|null
     */
    public function getTeachingCmClass(): ?float
    {
        return null;
    }

    /**
     * Get course td hours in class
     * @return null|float
     */
    public function getTeachingTdClass(): ?float
    {
        return null;
    }

    /**
     * et course tp hours in class
     * @return null|float
     */
    public function getTeachingTpClass(): ?float
    {
        return null;
    }

    /**
     * @return PermissionCollection
     */
    public function getCoursePermission(): PermissionCollection
    {
        return $this->permissions;
    }

    /**
     * @param PermissionCollection $permissions
     * @return CourseInfo
     */
    public function setCoursePermissions(PermissionCollection $permissions): CourseInfo
    {
        $this->permissions = $permissions;
        return $this;
    }
}
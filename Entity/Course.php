<?php

namespace UniceSIL\SyllabusMoodleImporterBundle\Entity;

use UniceSIL\SyllabusImporterToolkit\Course\CourseCollection;
use UniceSIL\SyllabusImporterToolkit\Course\CourseInfoCollection;
use UniceSIL\SyllabusImporterToolkit\Course\CourseInterface;

class Course implements CourseInterface
{
    /**
     * @var string
     */
    private $etbId;

    /**
     * @var CourseInfoCollection
     */
    private $courseInfos;

    /**
     * Course constructor.
     */
    public function __construct()
    {
        $this->courseInfos = new CourseInfoCollection();
    }

    /**
     * Get course establishment id from source repository
     * @return string
     */
    public function getEtbId(): string
    {
        return $this->etbId;
    }

    /**
     * @param string $etbIb
     * @return $this
     */
    public function setEtbId(string $etbIb): Course
    {
        $this->etbId = $etbIb;
        return $this;
    }

    /**
     * Get course type
     * @return string
     */
    public function getType(): string
    {
        return '';
    }

    /**
     * Get courses parents of course
     * @return CourseCollection
     */
    public function getParents(): CourseCollection
    {
        return new CourseCollection();
    }

    /**
     * Get infos of course
     * @return CourseInfoCollection
     */
    public function getCourseInfos(): CourseInfoCollection
    {
        return $this->courseInfos;
    }

    /**
     * @param CourseInfoCollection $courseInfos
     * @return Course
     */
    public function setCourseInfos(CourseInfoCollection $courseInfos): Course
    {
        $this->courseInfos = $courseInfos;
        return $this;
    }

    /**
     * If return true, create the course if does not already exist in Syllabus database
     * @return bool
     */
    public function createIfNotExist(): bool
    {
        return false;
    }
}
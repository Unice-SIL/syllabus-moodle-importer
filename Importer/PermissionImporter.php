<?php

namespace UniceSIL\SyllabusMoodleImporterBundle\Importer;

use GuzzleHttp\Client;
use UniceSIL\SyllabusImporterToolkit\Course\CourseCollection;
use UniceSIL\SyllabusImporterToolkit\Course\CourseInfoCollection;
use UniceSIL\SyllabusImporterToolkit\Permission\PermissionCollection;
use UniceSIL\SyllabusImporterToolkit\Permission\PermissionImporterInterface;
use UniceSIL\SyllabusMoodleImporterBundle\Entity\Course;
use UniceSIL\SyllabusMoodleImporterBundle\Entity\CourseInfo;

class PermissionImporter implements PermissionImporterInterface
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $token;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var array
     */
    private $years = [];

    /**
     * PermissionImporter constructor.
     */
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @param array $args
     * @return PermissionImporterInterface
     */
    public function setArgs(array $args = []): PermissionImporterInterface
    {
        if(array_key_exists('url', $args)){
            $this->url = $args['url'];
        }
        if(array_key_exists('token', $args)){
            $this->token = $args['token'];
        }

        return $this;
    }

    /**
     * Set years could be used to get courses permissions
     * @param array $years
     * @return PermissionImporterInterface
     */
    public function setYears(array $years): PermissionImporterInterface
    {
        $this->years = $years;
        return $this;
    }

    /**
     * @return CourseCollection
     */
    public function execute(): CourseCollection
    {
        ini_set('max_execution_time', 0);
        $courses = new CourseCollection();
        try {
            $coursesMoodle = $this->request('GET', [
                'query' => [
                    'moodlewsrestformat' => 'json',
                    'wstoken' => $this->token,
                    'wsfunction' => "core_course_get_courses"
                ]
            ]);
            foreach ($coursesMoodle as $courseMoodle){
                try {
                    if (array_key_exists('id', $courseMoodle) && array_key_exists('idnumber', $courseMoodle)) {
                        $id = $courseMoodle['id'];
                        $etbId = $courseMoodle['idnumber'];

                        // COURSE
                        $course = new Course();
                        $course->setEtbId($etbId);

                        // PERMISSIONS
                        $permissions = new PermissionCollection();
                        $permissionMoodle = $this->request(
                            'GET',
                            [
                                'query' => [
                                    'moodlewsrestformat' => 'json',
                                    'wstoken' => $this->token,
                                    'wsfunction' => "core_enrol_get_enrolled_users_with_capability",
                                    'coursecapabilities' => [
                                        ['courseid' => $id, 'capabilities' => 'moodle/course:markcomplete']
                                    ]
                                ]
                            ]
                        );
                        dump($permissionMoodle);

                        // COURSE INFO
                        $courseInfos = new CourseInfoCollection();
                        foreach ($this->years as $year) {
                            $courseInfo = new CourseInfo();
                            $courseInfo->setYearId($year)
                                ->setCoursePermissions($permissions);
                        }
                        $course->setCourseInfos($courseInfos);
                        $courses->append($course);
                    }
                }catch (\Exception $e){
                    dump($e);
                }
            }
        }catch (\Exception $e){
            dump($e);
        }
        return $courses;
    }

    /**
     * @param $method
     * @param $parameters
     * @return mixed
     * @throws \Exception
     */
    private function request($method, $parameters){

        $response = $this->client->request(
            $method,
            $this->url,
            $parameters
        );
        $body = json_decode($response->getBody(), true);
        if (is_array($body) && array_key_exists('exception', $body)) {
            $exception = $body['exception'];
            $code = $body['errorcode'];
            $message = $body['message'];
            $debuginfo = isset($body['debuginfo']) ? $body['debuginfo'] : null;
            throw new \Exception("[{$code}] {$exception} : {$message} " . (!empty($debuginfo) ? " => {$debuginfo} " : null), 0);
        }
        return $body;
    }
}
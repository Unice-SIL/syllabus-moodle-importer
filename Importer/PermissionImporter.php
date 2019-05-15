<?php

namespace UniceSIL\SyllabusMoodleImporterBundle\Importer;

use GuzzleHttp\Client;
use UniceSIL\SyllabusImporterToolkit\Course\CourseCollection;
use UniceSIL\SyllabusImporterToolkit\Course\CourseInfoCollection;
use UniceSIL\SyllabusImporterToolkit\Permission\PermissionCollection;
use UniceSIL\SyllabusImporterToolkit\Permission\PermissionImporterInterface;
use UniceSIL\SyllabusMoodleImporterBundle\Entity\Course;
use UniceSIL\SyllabusMoodleImporterBundle\Entity\CourseInfo;
use UniceSIL\SyllabusMoodleImporterBundle\Entity\Permission;
use UniceSIL\SyllabusMoodleImporterBundle\Entity\User;

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

                    if (array_key_exists('id', $courseMoodle) && array_key_exists('idnumber', $courseMoodle)) {
                        $id = $courseMoodle['id'];
                        $etbId = $courseMoodle['idnumber'];

                        try {
                            // COURSE
                            $course = new Course();
                            $course->setEtbId($etbId);

                            // PERMISSIONS
                            $permissions = new PermissionCollection();
                            $permissionsMoodle = $this->request(
                                'POST',
                                [
                                    'query' => [
                                        'moodlewsrestformat' => 'json',
                                        'wstoken' => $this->token,
                                        'wsfunction' => "core_enrol_get_enrolled_users_with_capability",
                                        'coursecapabilities' => [
                                            ['courseid' => $id, 'capabilities' => ['moodle/course:markcomplete']]
                                        ]
                                    ]
                                ]
                            );

                            if (is_array($permissionsMoodle)) {
                                $permissionsMoodle = current($permissionsMoodle);
                            }

                            if (array_key_exists('users', $permissionsMoodle)) {
                                foreach ($permissionsMoodle['users'] as $userMoodle) {
                                    if (array_key_exists('username', $userMoodle)) {
                                        $user = new User();
                                        $user->setUsername($userMoodle['username']);
                                        if (array_key_exists('firstname', $userMoodle)) {
                                            $user->setFirstname($userMoodle['firstname']);
                                        }
                                        if (array_key_exists('lastname', $userMoodle)) {
                                            $user->setLastname($userMoodle['lastname']);
                                        }
                                        if (array_key_exists('email', $userMoodle)) {
                                            $user->setEmail($userMoodle['email']);
                                        }
                                        $permission = new Permission();
                                        $permission->setPermission('WRITE')
                                            ->setUser($user);
                                        $permissions->append($permission);
                                    }
                                }
                            }

                            // COURSE INFO
                            $courseInfos = new CourseInfoCollection();
                            foreach ($this->years as $year) {
                                $courseInfo = new CourseInfo();
                                $courseInfo->setYearId($year)
                                    ->setCoursePermissions($permissions);
                                $courseInfos->append($courseInfo);
                            }
                            $course->setCourseInfos($courseInfos);
                            $courses->append($course);

                        } catch (\Exception $e) {
                            sprintf("Error while exporting permissions for course %s : %s", $id, $e->getMessage());
                        }
                    }
            }
        }catch (\Exception $e){
            sprintf("%s", $e->getMessage());
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
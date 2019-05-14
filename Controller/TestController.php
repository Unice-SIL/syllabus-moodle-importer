<?php

namespace UniceSIL\SyllabusMoodleImporterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends Controller
{
    /**
     * @Route("/test", name="test")
     */
    public function testAction(){
        $courses = $this->get('unicesil.syllabus_moodle_importer.permission')
            ->setYears(['2018'])
            ->setArgs([
                'url' => 'https://lms.unice.fr/webservice/rest/server.php',
                'token' => '0b64c084786e85195a7c59014b87d636',
            ])
            ->execute();
        dump($courses);
        return $this->render('base.html.twig');
    }
}
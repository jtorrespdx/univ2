<?php

    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Student.php";
    require_once __DIR__."/../src/Course.php";

    $app = new Silex\Application();

    $app['debug']=true;
    $server = 'mysql:host=localhost;dbname=university';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views'
    ));

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    //Home
    $app->get("/", function() use ($app) {
        return $app['twig']->render('index.html.twig');
    });

    $app->post("/delete_courses", function() use ($app) {
        Course::deleteAll();
        return $app['twig']->render('index.html.twig');
    });

    //Courses
    $app->get("/courses", function() use ($app) {
        return $app['twig']->render('courses.html.twig', array('courses' => Course::getAll()));
    });

    $app->post("/courses",function() use ($app) {
        $course = new Course($_POST['name'], $_POST['number']);
        $course->save();
        return $app['twig']->render('courses.html.twig', array('courses' => Course::getAll()));
    });

    $app->get("/courses/{id}", function($id) use ($app) {
    $course = Course::find($id);
        return $app['twig']->render('course.html.twig', array('course' => $course, 'students' =>                     $course->getStudents(), 'all_students' => Student::getAll()));
    });

    $app->get("/courses/{id}/edit", function($id) use ($app) {
        $course = Course::find($id);
        return $app['twig']->render('course_edit.html.twig', array('course' => $course));
    });

    $app->patch("/courses/{id}", function($id) use ($app) {
    $name = $_POST['name'];
    $course = Course::find($id);
    $course->update($name);
    return $app['twig']->render('course.html.twig', array('course' => $course, 'all_students' => Student::getAll(), 'students' => $course->getStudents()));
    });

    $app->delete("/courses/{id}", function($id) use ($app) {
        $course = Course::find($id);
        $course->delete();
        return $app['twig']->render('courses.html.twig', array('courses' => Course::getAll()));
    });

    $app->post("/add_courses", function() use ($app){
        $course = Course::find($_POST['course_id']);
        $student = Student::find($_POST['student_id']);
        $student->addCourse($course);
        return $app['twig']->render('student.html.twig', array('student' => $student, 'students' => Student::getAll(), 'courses' => $student->getCourses(), 'all_courses' => Course::getAll()));
    });


    //Students
    $app->get("/students", function() use ($app) {
        return $app['twig']->render('students.html.twig', array('students' => Student::getAll()));
    });

    $app->post("/students",function() use ($app) {
        $student = new Student($_POST['name'],$_POST['date']);
        $student->save();
        return $app['twig']->render('students.html.twig', array('students' => Student::getAll()));
    });

    $app->get("/students/{id}", function($id) use ($app) {
        $student = Student::find($id);
        return $app['twig']->render('student.html.twig', array('student' => $student, 'courses' => $student->getCourses(), 'all_courses' => Course::getAll()));
    });

    $app->get("/students/{id}/edit", function($id) use ($app) {
        $student = Student::find($id);
        return $app['twig']->render('student_edit.html.twig', array('student' => $student));
    });

    $app->patch("/students/{id}", function($id) use ($app) {
        $name = $_POST['name'];
        $student = Student::find($id);
        $student->update($name);
        return $app['twig']->render('student.html.twig', array('student' => $student, 'courses' => $student->getCourses(), 'all_courses'=>Course::getAll()));
    });

    $app->post("/add_students", function() use ($app) {
        $course = Course::find($_POST['course_id']);
        $student = Student::find($_POST['student_id']);
        $course->addStudent($student);
        return $app['twig']->render('course.html.twig', array('course'=>$course, 'courses'=>Course::getAll(), 'students'=>$course->getStudents(), 'all_students'=>Student::getAll()));
    });

    $app->post("/delete_students", function() use ($app) {
        Student::deleteAll();
        return $app['twig']->render('index.html.twig');
    });

    $app->delete("/students/{id}", function($id) use ($app) {
        $student = Student::find($id);
        $student->delete();
        return $app['twig']->render('students.html.twig', array('students' => Student::getAll()));
    });

    return $app;
?>

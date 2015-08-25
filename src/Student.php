<?php
class Student
{
    private $name;
    private $date;
    private $id;
    function __construct($name, $date, $id = null)
    {
        $this->name = $name;
        $this->date = $date;
        $this->id = $id;
    }
    function setName($new_name)
    {
        $this->name = (string) $new_name;
    }
    function getName()
    {
        return $this->name;
    }

    function setDate($new_date)
    {
        $this->date = (string) $new_date;
    }
    function getDate()
    {
        return $this->date;
    }
    function getId()
    {
        return $this->id;
    }
    function save()
    {
        $statement = $GLOBALS['DB']->exec("INSERT INTO students (name) VALUES ('{$this->getName()}'), ('{$this->getDate()}')");
        $this->id = $GLOBALS['DB']->lastInsertId();
    }
    static function getAll()
    {
        $returned_students = $GLOBALS['DB']->query("SELECT * FROM students;");
        $students = array();
        foreach($returned_students as $student) {
            $name = $student['name'];
            $date = $student['date'];
            $id = $student['id'];
            $new_student = new Student($name, $date, $id);
            array_push($students, $new_student);
        }
        return $students;
    }
    static function deleteAll()
    {
        $GLOBALS['DB']->exec("DELETE FROM students;");
    }
    static function find($search_id) {
        $found_student = null;
        $students = Student::getAll();
        foreach($students as $student) {
            $student_id = $student->getId();
            if ($student_id == $search_id) {
                $found_student = $student;
            }
        }
        return $found_student;
    }
    function update($new_name) {
        $GLOBALS['DB']->exec("UPDATE students SET name = '{$new_name}' WHERE id = {$this->getId()};");
        $this->setName($new_name);
    }
    function addCourse($course)
    {
        $GLOBALS['DB']->exec("INSERT INTO courses_students (course_id, student_id) VALUES ({$course->getId()}, {$this->getId()});");
    }
    function getCourses()
    {
        $query = $GLOBALS['DB']->query("SELECT course_id FROM courses_students WHERE student_id = {$this->getId()};");
        $course_ids = $query->fetchAll(PDO::FETCH_ASSOC);
        $courses = array();
        foreach($course_ids as $id) {
            $course_id = $id['course_id'];
            $result = $GLOBALS['DB']->query("SELECT * FROM courses WHERE id = {$course_id};");
            $returned_course = $result->fetchAll(PDO::FETCH_ASSOC);
            $name = $returned_course[0]['name'];
            $number = $returned_course[0]['number'];
            $id = $returned_course[0]['id'];
            $new_course = new Course($name, $number, $id);
            array_push($courses, $new_course);
        }
        return $courses;
    }
    function delete()
    {
        $GLOBALS['DB']->exec("DELETE FROM students WHERE id = {$this->getId()};");
        $GLOBALS['DB']->exec("DELETE FROM courses_students WHERE student_id = {$this->getId()};");
    }
}
?>

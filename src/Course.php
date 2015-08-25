<?php

    class Course
    {
        private $name;
        private $number;
        private $id;

        function __construct($name, $number, $id = null)
        {
            $this->name = $name;
            $this->number = $number;
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

        function setNumber($new_number)
        {
            $this->number = (int) $new_number;
        }

        function getNumber()
        {
            return $this->number;
        }

        function getId()
        {
            return $this->id;
        }

        function save()
        {
          $GLOBALS['DB']->exec("INSERT INTO courses (name, number) VALUES ('{$this->getName()}', {$this->getNumber()});");
          $this->id = $GLOBALS['DB']->lastInsertId();
        }

        function update($new_name)
        {
          $GLOBALS['DB']->exec("UPDATE courses SET name = '{$new_name}' WHERE id = {$this->getId()}, {$this->getNumber()};");
          $this->setName($new_name);
        }

        static function getAll()
        {
          $returned_courses = $GLOBALS['DB']->query("SELECT * FROM courses;");
          $courses = array();
          foreach($returned_courses as $course) {
            $id = $course['id'];
            $name = $course['name'];
            $number = $course['number'];
            $new_course = new Course($name, $number, $id);
            array_push($courses, $new_course);
          }
          return $courses;
        }

        static function deleteAll()
        {
          $GLOBALS['DB']->exec("DELETE FROM courses;");
        }

        static function find($search_id)
        {
          $found_course = null;
          $courses = Course::getAll();
          foreach($courses as $course) {
            $course_id = $course->getId();
            if ($course_id == $search_id) {
              $found_course = $course;
            }
          }
          return $found_course;
        }

        function addStudent($student)
        {
            $GLOBALS['DB']->exec("INSERT INTO courses_students (course_id, student_id) VALUES ({$this->getId()}, {$student->getId()});");
        }

        function getStudents()
        {
            $query = $GLOBALS['DB']->query("SELECT student_id FROM courses_students WHERE course_id = {$this->getId()};");
            $student_ids = $query->fetchAll(PDO::FETCH_ASSOC);

            $students = array();
            foreach($student_ids as $id) {
                $student_id = $id['student_id'];
                $result = $GLOBALS['DB']->query("SELECT * FROM students WHERE id = {$student_id};");
                $returned_student = $result->fetchAll(PDO::FETCH_ASSOC);

                $id = $returned_student[0]['id'];
                $name = $returned_student[0]['name'];
                $date= $returned_student[0]['date'];
                $new_student = new Student($name, $date, $id);
                array_push($students, $new_student);
            }
            return $students;
        }

        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM courses WHERE id = {$this->getId()};");
            $GLOBALS['DB']->exec("DELETE FROM courses_students WHERE course_id = {$this->getId()};");
        }

    }
?>

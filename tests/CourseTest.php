<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Course.php";
    require_once "src/Student.php";

    $server = 'mysql:host=localhost;dbname=university_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class CourseTest extends PHPUnit_Framework_TestCase {

        protected function tearDown() {
            Course::deleteAll();
            Student::deleteAll();
        }

        function testGetName()
        {
            //Arrange
            $name = "History";
            $number= 101;
            $test_course = new Course($name, $number);

            //Act
            $result = $test_course->getName();

            //Assert
            $this->assertEquals($name, $result);
        }

        function testSetName()
        {
            //Arrange
            $name = "History";
            $number= 101;
            $test_course = new Course($name, $number);

            //Act
            $test_course->setName("Science");
            $result = $test_course->getName();

            //Assert
            $this->assertEquals("Science", $result);
        }

        function testGetId()
        {
            //Arrange
            $name = "Biology";
            $number= 101;
            $id = 1;
            $test_course = new Course($name, $number, $id);

            //Act
            $result = $test_course->getId();

            //Assert
            $this->assertEquals(1, $result);
        }

        function testSave()
        {
            //Arrange
            $name = "Biology";
            $number= 101;
            $id = 1;
            $test_course = new Course($name, $number, $id);
            $test_course->save();

            //Act
            $result = Course::getAll();

            //Assert
            $this->assertEquals($test_course, $result[0]);
        }

        function testUpdate()
        {
            //Arrange
            $name = "Biology";
            $number= 101;
            $id = 1;
            $test_course = new Course($name, $number, $id);
            $test_course->save();

            $new_name = "Auto";
            $new_name = 201;

            //Act
            $test_course->update($new_name, $new_number);

            //Assert
            $this->assertEquals("Auto", $test_course->getName());
        }

        function testDeleteCourse()
        {
            //Arrange
            $name = "Biology";
            $number= 101;
            $id = 1;
            $test_course = new Course($name, $number, $id);
            $test_course->save();

            $name2 = "Auto";
            $number= 101;
            $id2 = 2;
            $test_course2 = new Course($name2, $number2, $id2);
            $test_course2->save();

            //Act
            $test_course->delete();

            //Assert
            $this->assertEquals([$test_course2], Course::getAll());
        }

        function testGetAll()
        {
            //Arrange
            $name = "Biology";
            $number = 101;
            $id = 1;

            $name2 = "Auto";
            $number2 = 101;
            $id2 = 2;

            $test_course = new Course($name, $number, $id);
            $test_course->save();
            $test_course2 = new Course($name2, $number2, $id2);
            $test_course2->save();

            //Act
            $result = Course::getAll();

            //Assert
            $this->assertEquals([$test_course, $test_course2], $result);
        }

        function testDeleteAll()
        {
            //Arrange
            $name = "Biology";
            $number = 101;
            $id = 1;

            $name2 = "Auto";
            $number2 = 101;
            $id2 = 2;

            $test_course = new Course($name, $number, $id);
            $test_course->save();
            $test_course2 = new Course($name2, $number2, $id2);
            $test_course2->save();

            //Act
            Course::deleteAll();

            //Assert
            $result = Course::getAll();
            $this->assertEquals([], $result);
        }

        function testFind()
        {
            //Arrange
            $name = "Biology";
            $number = 101;
            $id = 1;
            $test_course = new Course($name, $number, $id);
            $test_course->save();

            $name2 = "Auto";
            $number2 = 101;
            $id2 = 2;
            $test_course2 = new Course($name2, $number2, $id2);
            $test_course2->save();

            //Act
            $result = Course::find($test_course->getId());

            //Assert
            $this->assertEquals($test_course, $result);
        }

        function testAddStudent()
        {
            //Arrange
            $name = "Biology";
            $number = 101;
            $id = 1;
            $test_course = new Course($name, $number, $id);
            $test_course->save();

            $name = "Elliot Michaels";
            $date = "2015-08-03";
            $id2 = 2;
            $test_student = new Student($name, $date, $id2);
            $test_student->save();

            //Act
            $test_course->addStudent($test_student);

            //Assert
            $this->assertEquals($test_course->getStudents(), [$test_student]);
        }

        function testGetStudents()
        {
            //Arrange
            $name = "Biology";
            $number = 101;
            $id = 1;
            $test_course = new Course($name, $id);
            $test_course->save();

            $name = "Elliot Michaels";
            $date = "2015-08-03";
            $id2 = 2;
            $test_student = new Student($name, $date, $id2);
            $test_student->save();

            $name2 = "Agatha Heterodyne";
            $date2 = "1866-04-06";
            $id3 = 3;
            $test_student2 = new Student($name2, $date2, $id3);
            $test_student2->save();

            //Act
            $test_course->addStudent($test_student);
            $test_course->addStudent($test_student2);

            //Assert
            $this->assertEquals($test_course->getStudents(), [$test_student, $test_student2]);
        }

        function testDelete()
        {
            //Arrange
            $name = "Biology";
            $number = 101;
            $id = 1;
            $test_course = new Course($name, $number, $id);
            $test_course->save();

            $name = "Elliot Michaels";
            $date = "2015-08-03";
            $id2 = 2;
            $test_student = new Student($name, $date, $id2);
            $test_student->save();

            //Act
            $test_course->addStudent($test_student);
            $test_course->delete();

            //Assert
            $this->assertEquals([], $test_student->getCourses());
        }
    }
?>

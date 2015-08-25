<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */
    require_once "src/Student.php";
    $server = 'mysql:host=localhost;dbname=to_do_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);
    class StudentTest extends PHPUnit_Framework_TestCase {
        protected function tearDown() {
            Student::deleteAll();
            Courses::deleteAll();
        }
        function testGetName() {
          //Arrange
          $name = "Elliot Michaels";
          $date = "2015-08-03";
          $test_student = new Student($name);
          //Act
          $result = $test_student->getName();
          //Assert
          $this->assertEquals($name, $date, $result);
        }
        function testSetName() {
            //Arrange
            $name = "Elliot Michaels";
            $date = "2015-08-03";
            $test_student = new Student($name);
            //Act
            $test_student->setName("Elliot Michaels");
            $result = $test_student->getName();
            //Assert
            $this->assertEquals("Elliot Michaels", $result);
        }
        function testGetId() {
          //Arrange
          $id = 1;
          $name = "Agatha Heterodyne";
          $date = "1866-02-06";
          $test_student = new Student($name, $date, $id);
          //Act
          $result = $test_student->getId();
          //Assert
          $this->assertEquals(1, $result);
        }
        function testSave() {
          //Arrange
          $name = "Agatha Heterodyne";
          $date = "1866-02-06";
          $id = 1;
          $test_student = new Student($name, $date, $id);
          //Act
          $test_student->save();
          //Assert
          $result = Student::getAll();
          $this->assertEquals($test_student, $result[0]);
        }
        function testSaveSetsId() {
          $name = "Agatha Heterodyne";
          $date = "1866-02-06";
          $id = 1;
          $test_student = new Student($name, $date, $id);
          //Act
          $test_student->save();
          //Assert
          $this->assertEquals(true, is_numeric($test_student->getId()));
        }
        function testGetAll() {
          //Arrange
          $name = "Agatha Heterodyne";
          $date = "1866-02-06";
          $id = 1;
          $test_student = new Student($name, $date, $id);
          $test_student->save();
          $name2 = "Drake Michaels";
          $date2 = "2011-02-02";
          $id2 = 2;
          $test_student2 = new Student($name2, $id2);
          $test_student2->save();
          //Act
          $result = Student::getAll();
          //Assert
          $this->assertEquals([$test_student, $test_student2], $result);
        }
        function testDeleteAll() {
          //Arrange
          $name = "Agatha Heterodyne";
          $date = "1866-02-06";
          $id = 1;
          $test_student = new Student($name, $date, $id);
          $test_student->save();
          $name2 = "Drake Michaels";
          $date2 = "2011-02-02";
          $id2 = 2;
          $test_student2 = new Student($name2, $id2);
          $test_student2->save();
          //Act
          Student::deleteAll();
          //Assert
          $result = Student::getAll();
          $this->assertEquals([], $result);
        }
        function testFind() {
          //Assert
          $name = "Elliot Michaels"
          $id = 1;
          $date = "2015-08-03";
          $test_student = new Student($name, $date, $id);
          $test_student->save();
          $name2 = "Agatha Heterodyne";
          $date2 = "1866-02-06";
          $id2 = 2;
          $test_student2 = new Student($name2, $id2);
          $test_student2->save();
          //Act
          $result = Student::find($test_student->getId());
          //Assert
          $this->assertEquals($test_student, $result);
        }
        function testUpdate()
        {
            //Arrange
            $name = "Elliot Michaels";
            $date = "2015-08-03";
            $id = 1;
            $test_student = new Student($name, $date, $id);
            $test_student->save();
            $new_name = "Drake Michaels";
            //Act
            $test_student->update($new_name);
            //Assert
            $this->assertEquals("Drake Michaels", $test_student->getName());
        }
        function testDeleteStudent()
        {
            //Arrange
            $name = "Elliot Michaels";
            $date = "2015-08-03";
            $id = 1;
            $test_student = new Student($name, $date, $id);
            $test_student->save();
            $name2 = "Agatha Heterodyne";
            $date2 = "1866-02-06";
            $id2 = 2;
            $test_student2 = new Student($name2, $id2);
            $test_student2->save();
            //Act
            $test_student->delete();
            //Assert
            $this->assertEquals([$test_student2], Student::getAll());
        }
        function testAddCourses()
        {
        //Arrange
            $name = "Biology 101";
            $number = "BIO 101";
            $id = 1;
            $test_course = new Courses($name, $number, $id);
            $test_course->save();
            $name = "Auto";
            $number2 = "Auto 101";
            $id2 = 2;
            $test_student = new Student($name, $id2);
            $test_student->save();
            //Act
            $test_student->addCourses($test_course);
            //Assert
            $this->assertEquals($test_student->getCourses(), [$test_course]);
        }
        function testGetCategories()
        {
            //Arrange
            $name = "Biology 101";
            $number = "BIO 101";
            $id = 1;
            $test_course = new Courses($name, $number, $id);
            $test_course->save();
            $name2 = "Physics";
            $number2 = "PHYS 101";
            $id2 = 2;
            $test_course2 = new Courses($name2, $id2);
            $test_course2->save();
            $name3 = "Auto";
            $number3 = "Auto 101";
            $id3 = 3;
            $test_student = new Student($name, $id3);
            $test_student->save();
            //Act
            $test_student->addCourses($test_course);
            $test_student->addCourses($test_course2);
            //Assert
            $this->assertEquals($test_student->getCourses(), [$test_course, $test_course2]);
        }
        function testDelete()
        {
            //Arrange
            $name = "Biology 101";
            $number = "BIO 101";
            $id = 1;
            $test_course = new Courses($name, $number, $id);
            $test_course->save();
            $name = "Auto";
            $number2 = "Auto 101";
            $id2 = 2;
            $test_student = new Student($name, $id2);
            $test_student->save();
            //Act
            $test_student->addCourses($test_course);
            $test_student->delete();
            //Assert
            $this->assertEquals([], $test_course->getStudents());
        }
      }
?>

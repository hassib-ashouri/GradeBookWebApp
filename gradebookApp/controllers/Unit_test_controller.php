<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unit_test_controller extends MY_Controller
{
    /**
     * Runs all unit tests
     */
    public function testAll()
    {
        $this->classManipulationTest();
        $this->passwordTest();
        $this->statisticsTest();
    }

    /**
     * Tests methods from Login_model
     */
    public function passwordTest()
    {
        $dataSets = array(
            'password',
            '',
            'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras nulla eros, dignissim bibendum urna at, laoreet iaculis purus. Nunc non euismod elit. Etiam a mauris imperdiet, luctus eros vel, consectetur augue. Nunc et tortor vel orci mollis scelerisque. Suspendisse potenti. Donec semper in felis eu lacinia. Aliquam euismod urna libero, sit amet aliquet lacus semper eu. Nullam eu rhoncus arcu.',
            '<div>Hello Friend</div>',
            '🙂',
        );
        $answerSets = array(
            array(
                '$2y$10$ijV8RzB6GPfOLf.Ec2XHTeRCGBdK2tBVE6v.7BDioG2n03J/H4kX.',
                '$2y$10$xhVLmRT/A4t0gD9ZBDS/R.2qb3OnYARrJVxf1jeHgB52NTiBj.c9q',
                '$2y$10$XYzmYCXQQvo/xvWe2Env1Oz0VRS0hdzKMM0Jt9TDrw7rEHbWI/a0y',
                '$2y$10$XnUmkaKuxyPXp7KSu63qreuaIszRAZtIl8eBLvpRq521YmZ3NjETC',
                '$2y$10$iv5QyX2EyBAdNB6HYF8rjew2ncP8kT5xlixhKD.VC0PDb9hRLaKPq',
            ),
            array(
                '$2y$10$BQJIwjiEEAZmlSBaAKKeueVwWQn.YcJ4W/bIm4oQwafbdfv5ctfsm',
                '$2y$10$qk3iIEBsdAElUkbf3dfSuutlmi/Wq4lvEiWAGC/GUGWBy8/Q17E5O',
                '$2y$10$NxKxBa/KEIxTk5kPEuaX4.ClhCxCrS2hJHi4KhEQJyLehAJMXyj02',
                '$2y$10$8iDCxgyyi9RSLoPUzwOb/uay0Owp46PyxajXfSgUMXP8qViw0nrce',
                '$2y$10$U1yMOoVAS7eC.dBEsFk6..nJusKdNczmZpd..mUsd.GR7eUOdtq/G',
            ),
            array(
                '$2y$10$wVanFJfzebeggCcCq6vbpe/Iw9NP2u/DWZ5Su5nRXhT7K2TywC1vS',
                '$2y$10$4jq7jvEOe5n985.559kXzOIgCilDG24M0EPxCDg3u4Fpc509uBlq6',
                '$2y$10$q3U35bQ3IlzcTBjSmLOJj.xV2ciy3BKJVaJVcGMumbwF8zMukkYPy',
                '$2y$10$JXxbLITHQYLpv7TjlEKCEe3ZnK3bWLGJpJC7ZrhSTSf.SWtuV3X8S',
                '$2y$10$MbRx7bPG0qgAxy/5w39jg.voRq2sav6x1/AhqCqGW3cryDZjmCvNu',
            ),
            array(
                '$2y$10$TJwp0k7N4LiK5ogBJjjRK.bLVDZikTazOtRFqxi/7Gub3Q3u8pSIe',
                '$2y$10$DLdd.nHWNBWIPP3tkzlkROcafdWOOdcneU.Yubiu7Q6idOScV66Ja',
                '$2y$10$PSVPo1XqDI68QGUVeeXRUuyhXDx0SADYxAuPHIZA7HTXctkLlTo/O',
                '$2y$10$e3hkcTJ/44gX7JHwOHBILOEuvsiXINBWjgiYPqJaDMBRaaFauI3Ei',
                '$2y$10$UHw3GQgmorBANVAtUZmxVefrdQ3WWo6NU6RhWghZcoDJFS86dxKZq',
            ),
            array(
                '$2y$10$U4SeWGutwK5anEM4QujrsOm35aY3fKmXEYXPu/r6ba4aBYak9u7DW',
                '$2y$10$HRa2oKHG6AEnNz0RsuksnOsQork260A2LQgO7KJUB/5KQnxiMO9..',
                '$2y$10$loyHx.UHNeke.wzyEud5zebL5IdYaP2RdeL/R.kzvkTRmvt6gJRk.',
                '$2y$10$LiEGHltDxbOYt/dHg20OSOrSJQICs3amniCHkt.Mj.CdxzbMXvqKW',
                '$2y$10$Tc3/0ojanlPcUZlzN5TkfuzKGBAyEPy9WSoiWrOEWx6DSNRe3Ay6q',
            ),
        );

        $pass = array();
        for ($i = 0; $i < count($dataSets); $i++) {
            $pass[$i] = true;
        }

        $this->load->model("login_model");
        for ($i = 0; $i < count($dataSets); $i++) {
            $passwordInput = $dataSets[$i];
            foreach ($answerSets[$i] as $passwordHash) {
                $user = new User();
                $user->password_hash = $passwordHash;
                $this->login_model->setUser($user);
                $passed = $this->login_model->verifyPassword($passwordInput);
                if (!$passed) {
                    $pass[$i] = false;
                }
            }
        }

        echo "<h2>Password Test</h2>";
        $this->_printResult("simple", $pass[0]);
        $this->_printResult("empty", $pass[1]);
        $this->_printResult("long", $pass[2]);
        $this->_printResult("simple html", $pass[3]);
        $this->_printResult("emoji", $pass[4]);
    }

    /**
     * Tests methods from statistics_helper
     *      tests: gradeLow, gradeHigh, gradeMean, gradeMedian, gradeVar, gradeStdDev
     */
    public function statisticsTest()
    {
        $dataSets = array(
            array(48, 50, 64, 2, 40, 19, 23, 3, 58, 12, 13, 70, 69, 61, 98, 64, 50, 36, 4, 89, 93, 94, 16, 15, 15, 30, 43, 96, 38, 86),
            array(79, 72, 40, 96, 95, 54, 21, 84, 94, 37, 29, 87, 17, 55, 64, 1, 26, 59, 52, 22, 50, 41, 81, 7, 69, 36, 68, 91, 49, 61),
            array(97, 22, 14, 98, 60, 58, 66, 97, 76, 20, 92, 91, 24, 78, 59, 77, 51, 66, 26, 38, 31, 88, 65, 11, 2, 96, 56, 35, 98, 34),
            array(47, 18, 28, 87, 28, 70, 8, 42, 46, 91, 75, 35, 22, 51, 46, 92, 35, 41, 78, 72, 84, 14, 47, 39, 49, 28, 47, 53, 61, 79),
            array(7, 39, 34, 89, 12, 56, 23, 15, 42, 25, 3, 12, 90, 72, 73, 92, 92, 47, 47, 12, 46, 65, 35, 30, 98, 30, 4, 42, 25, 90),
        );
        $answerSets = array(
            array(
                "low" => 2,
                "high" => 98,
                "mean" => 46.6333333333,
                "median" => 45.5,
                "var" => 921.6988888889,
                "stdDev" => 30.3594942133,
            ),
            array(
                "low" => 1,
                "high" => 96,
                "mean" => 54.5666666667,
                "median" => 54.5,
                "var" => 719.9788888889,
                "stdDev" => 26.8324223448,
            ),
            array(
                "low" => 2,
                "high" => 98,
                "mean" => 57.5333333333,
                "median" => 59.5,
                "var" => 885.9822222222,
                "stdDev" => 29.7654535027,
            ),
            array(
                "low" => 8,
                "high" => 92,
                "mean" => 50.4333333333,
                "median" => 47,
                "var" => 544.1788888889,
                "stdDev" => 23.3276421631,
            ),
            array(
                "low" => 3,
                "high" => 98,
                "mean" => 44.9,
                "median" => 40.5,
                "var" => 877.3566666667,
                "stdDev" => 29.6202070666,
            ),
        );
        $pass = array(
            "low" => true,
            "high" => true,
            "mean" => true,
            "median" => true,
            "var" => true,
            "stdDev" => true,
        );

        for ($i = 0; $i < count($dataSets); $i++) {
            $data = $dataSets[$i];
            $answers = $answerSets[$i];

            if (!$this->_aboutEqual(gradeLow($data), $answers["low"])) {
                $pass["low"] = false;
            }
            if (!$this->_aboutEqual(gradeHigh($data), $answers["high"])) {
                $pass["high"] = false;
            }
            if (!$this->_aboutEqual(gradeMean($data), $answers["mean"])) {
                $pass["mean"] = false;
            }
            if (!$this->_aboutEqual(gradeMedian($data), $answers["median"])) {
                $pass["median"] = false;
            }
            if (!$this->_aboutEqual(gradeVar($data), $answers["var"])) {
                $pass["var"] = false;
            }
            if (!$this->_aboutEqual(gradeStdDev($data), $answers["stdDev"])) {
                $pass["stdDev"] = false;
            }
        }

        echo "<h2>Statistics Test</h2>";
        foreach ($pass as $key => $value) {
            $this->_printResult($key, $value);
        }
    }

    /**
     * Tests methods from Class_list_model
     *      tests: createClass and deleteClass using classData
     */
    public function classManipulationTest()
    {
        $this->load->model("class_model");
        $this->load->model("class_list_model");

        $students = array(new Student());
        $students[0]->student_id = "000000001";

        $classObj = new ClassObj(array(), $students);
        $classObj->class_id = "26692";
        $classObj->professor_id = "0124";
        $classObj->class_name = "ISE 164";
        $classObj->section = "01";
        $classObj->class_title = "Comp & Hum Interact";
        $classObj->meeting_times = "Tu 6:00PM - 8:45PM";
        $classObj->table_name = "class_26692_ISE-164_01_table";

        $this->class_list_model->createClass($classObj);
        $classDataCreate = $this->class_list_model->classData($classObj);
        $this->class_list_model->deleteClass($classObj);
        $classDataDelete = $this->class_list_model->classData($classObj);

        $passCreate = array(
            "rowExists" => $classDataCreate["rowExists"],
            "tableExists" => $classDataCreate["tableExists"],
            "studentsEnrolled" => $classDataCreate["studentsEnrolled"] > 0,
        );
        $passDelete = array(
            "rowExists" => $classDataDelete["rowExists"] === false,
            "tableExists" => $classDataDelete["tableExists"] === false,
            "studentsEnrolled" => $classDataDelete["studentsEnrolled"] == 0,
        );

        echo "<h2>Class Manipulation Test</h2>";
        $this->_printResult("row Created", $passCreate["rowExists"]);
        $this->_printResult("row Deleted", $passDelete["rowExists"]);
        $this->_printResult("table Created", $passCreate["tableExists"]);
        $this->_printResult("table Deleted", $passDelete["tableExists"]);
        $this->_printResult("students Enrolled", $passCreate["studentsEnrolled"]);
        $this->_printResult("students De-Enrolled", $passDelete["studentsEnrolled"]);
    }

    /**
     * Checks that $numberTest is about equal to $numberReal
     *      only different (+ or -) by less than $tolerance
     * @param number $numberTest
     * @param number $numberReal
     * @param float $tolerance
     * @return bool
     */
    private function _aboutEqual($numberTest, $numberReal, $tolerance = .001)
    {
        return ($numberTest - $tolerance < $numberReal)
            && ($numberTest + $tolerance > $numberReal);
    }

    /**
     * Prints out the result of a test
     * @param string $testName
     * @param bool $result
     */
    private function _printResult($testName, $result)
    {
        if ($result) {
            $resultP = '<b style="color: green">Pass</b>';
        } else {
            $resultP = '<b style="color: red">Fail</b>';
        }

        $testNameP = ucfirst($testName);

        echo "<pre>$resultP: $testNameP</pre>";
    }
}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UnitTestController extends MY_Controller
{
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

    private function _aboutEqual($numberTest, $numberReal, $tolerance = .001)
    {
        return ($numberTest - $tolerance < $numberReal)
            && ($numberTest + $tolerance > $numberReal);
    }

    private function _printResult($testName, $result)
    {
        if ($result) {
            $resultP = "<b><span style=\"color: green\">Passed</span></b>";
        } else {
            $resultP = "<b><span style=\"color: red\">Failed</span></b>";
        }

        $testNameP = ucfirst($testName);

        echo "<div>$testNameP: $resultP</div>";
    }
}
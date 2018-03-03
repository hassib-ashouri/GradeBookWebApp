<?php

if (!function_exists('gradeLow')) {
    /**
     * Gets the lowest of the grades
     * @param array $grades
     * @return number
     */
    function gradeLow($grades)
    {
        return min($grades);
    }
}

if (!function_exists('gradeHigh')) {
    /**
     * Gets the highest of the grades
     * @param array $grades
     * @return number
     */
    function gradeHigh($grades)
    {
        return max($grades);
    }
}

if (!function_exists('gradeMean')) {
    /**
     * Gets the mean of the grades
     * @param array $grades
     * @return number
     */
    function gradeMean($grades)
    {
        return array_sum($grades) / count($grades);
    }
}

if (!function_exists('gradeMedian')) {
    /**
     * Gets the median of the grades
     * many thanks to: https://codereview.stackexchange.com/a/223
     * @param array $grades
     * @return number|null
     */
    function gradeMedian($grades)
    {
        $count = count($grades);
        if ($count == 0) {
            return null;
        }
        sort($grades, SORT_NUMERIC);
        $midIndex = (int)floor($count / 2);
        $median = $grades[$midIndex];
        if ($count % 2 == 0) {
            $median = ($median + $grades[$midIndex - 1]) / 2;
        }
        return $median;
    }
}

if (!function_exists('gradeVar')) {
    /**
     * Gets the variance of the grades
     * @param array $grades
     * @return number
     */
    function gradeVar($grades)
    {
        $mean = gradeMean($grades);
        $sumSquares = 0;
        foreach ($grades as $grade) {
            $sumSquares += pow($grade - $mean, 2);
        }
        return $sumSquares / count($grades);
    }
}

if (!function_exists('gradeStdDev')) {
    /**
     * Gets the variance of the grades
     * @param array $grades
     * @return number
     */
    function gradeStdDev($grades)
    {
        $variance = gradeVar($grades);
        return sqrt($variance);
    }
}
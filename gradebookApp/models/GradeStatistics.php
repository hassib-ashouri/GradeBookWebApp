<?php

interface GradeStatistics
{
    /**
     * Gets the lowest of the grades
     * @return number
     */
    public function getLowGrade();

    /**
     * Gets the highest of the grades
     * @return number
     */
    public function getHighGrade();

    /**
     * Gets the mean of the grades
     * @return number
     */
    public function getMeanGrade();

    /**
     * Gets the standard deviation of the grades
     * @return number
     */
    public function getStdDevGrade();
}
<?php namespace Interfaces;

/**
 * Implementing classes can calculate grade statistics
 * Interface GradeStatistics
 * @package Interfaces
 */
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
     * Gets the median of the grades
     * @return number
     */
    public function getMedianGrade();

    /**
     * Gets the variance of the grades
     * @return number
     */
    public function getVarGrade();

    /**
     * Gets the standard deviation of the grades
     * @return number
     */
    public function getStdDevGrade();
}
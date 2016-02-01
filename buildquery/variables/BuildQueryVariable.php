<?php namespace Craft;

/**
 * BuildQuery Variables
 *
 * These can be accessed in a Twig template using `craft.buildQuery`
 *
 */

class BuildQueryVariable
{
    /**
     * Sets the initial ElementCriteraModel that will be
     * the starting point for our query.
     *
     * @param   ElementCriteriaModel  $criteria
     * @return  BuildQueryService
     */
    public function source(ElementCriteriaModel $criteria)
    {
        return craft()->buildQuery->setSource($criteria);
    }

    /**
     * Dump details about the ElementCriteriaModel
     * that our query is built upon.
     *
     * In a template, call {% do craft.buildQuery.debugCriteria %}
     */
    public function debugCriteria()
    {
        craft()->buildQuery->showCriteria();
    }

    /**
     * Dump the underlying SQL query that is built
     * after the ElementCriteriaModel has been
     * converted to a dbCommand object.
     *
     * In a template, call {% do craft.buildQuery.debugSql %}
     */
    public function debugSql()
    {
        craft()->buildQuery->showSql();
    }

    /**
     * Dump the results of our query in array form,
     * before the results get populated into EntryModels.
     *
     * In a template, call {% do craft.buildQuery.debugResults %}
     */
    public function debugResults()
    {
        craft()->buildQuery->showResults();
    }
}

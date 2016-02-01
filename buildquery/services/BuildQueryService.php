<?php namespace Craft;

/**
 * BuildQuery Service
 *
 * This handles the logic of extending an ElementCriteriaModel
 * in order to customize or optimize a query.
 *
 * As an example, we've added a `countRelated()` method that can be
 * called from our template. It adds a `COUNT` clause to the query,
 * allowing us to retrieve a count of related elements in the same
 * query that retreives the original elements. This saving us from
 * having to loop over each resulting element in our template and
 * calling `count` for each one, eliminating the N+1 problem.
 *
 * You can add your own query directives here; see, for instance,
 * the method `yourOwnMethod()` below for a scaffold.
 *
 */

class BuildQueryService extends BaseApplicationComponent
{
    /**
     * The ElementCriteriaModel used as a starting point for our query
     *
     * @var  ElementCriteriaModel
     */
    protected $criteria;

    /**
     * The dbCommand object containing the query we're building
     *
     * @var  dbCommand
     */
    protected $dbCommand;

    /**
     * Set the initial ElementCriteriaModel that will be extended.
     *
     * @param  ElementCriteriaModel  $criteria
     * @return  BuildQueryService
     */
    public function setSource(ElementCriteriaModel $criteria)
    {
        $this->criteria = $criteria;

        return $this;
    }

    /**
     * Include a count of related elements in our query results.
     *
     * @param   ElementCriteriaModel  $relation
     * @return  BuildQueryService
     */
    public function countRelated(ElementCriteriaModel $relation)
    {
        $this->addRelationToElementCriteriaModel($relation);
        $this->extendElementCriteriaModel();
        $this->addCountToQuery('sources1.targetId', 'workCount');

        return $this;
    }

    /**
     * Use this as a scaffold for your own query directives.
     * From your template, it can be chained onto other query directives
     * after your query has been set up with `craft.buildQuery.source()...`
     *
     * @return  BuildQueryService
     */
    public function yourOwnMethod()
    {
        $this->extendElementCriteriaModel();

        // -----------------------------------------------
        // Add your own query logic here, using any method
        // that is available to the `dbCommand` class.
        //
        // $this->dbCommand->...
        // -----------------------------------------------

        return $this;
    }

    /**
     * Perform the find after the query has been built and return
     * an array of the resulting EntryModels or BuildQueryModels.
     *
     * @return Array
     */
    public function find()
    {
        if (! $this->dbCommand) {
            return $this->criteria->find();
        }

        $result = $this->dbCommand->queryAll();

        return BuildQueryModel::populateModels($result);
    }

    /**
     * Add a `relatedTo` parameter to our ElementCriteriaModel.
     *
     * Accepts an ElementCriteriaModel representing the
     * elements that we want to relate to our source.
     *
     * @param  ElementCriteriaModel  $relation
     * @return  void
     */
    protected function addRelationToElementCriteriaModel(ElementCriteriaModel $relation)
    {
        $this->criteria->relatedTo = ['sourceElement' => $relation];
    }

    /**
     * Convert our ElementCriteriaModel into a dbCommand object,
     * which we can use to add further parameters to our query.
     *
     * @return  void
     */
    protected function extendElementCriteriaModel()
    {
        if (! $this->dbCommand) {
            $this->dbCommand = craft()->elements->buildElementsQuery($this->criteria);
        }
    }

    /**
     * Add COUNT to our query's SELECT clause,
     * optionally naming the resulting count with an alias.
     *
     * @param  String  $field   The field from the SQL query that we want to count
     * @param  string  $alias   Optional name for the resulting count
     * @return void
     */
    protected function addCountToQuery($field, $alias = 'count')
    {
        if (! $this->dbCommand) {
            return;
        }

        $this->dbCommand->addSelect('count(' . $field . ') AS ' . $alias);
    }

    /*
    |--------------------------------------------------------------------------
    | For debugging
    |--------------------------------------------------------------------------
    |
    | These methods are helpful when building an extended query,
    | to take a look at the underlying ElementCriteriaModel
    | and prepared SQL statement.
    |
    */

    /**
     * Dump details about the ElementCriteriaModel
     * that our query is built upon.
     */
    public function showCriteria()
    {
        Craft::dd($this->criteria->getAttributes());
    }

    /**
     * Dump the underlying SQL query that is built
     * after the ElementCriteriaModel has been
     * converted to a dbCommand object.
     */
    public function showSql()
    {
        print_r(str_replace(
            array_keys($this->dbCommand->params),
            array_values($this->dbCommand->params),
            $this->dbCommand->getText()
        ));

        Craft::dd('');
    }

    /**
     * Dump the results of our query in array form,
     * before the results get populated into EntryModels.
     */
    public function showResults()
    {
        if (! $this->dbCommand) {
            Craft::dd($this->criteria->find());
        }

        Craft::dd($this->dbCommand->queryAll());
    }
}

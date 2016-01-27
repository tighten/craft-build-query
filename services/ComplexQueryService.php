<?php namespace Craft;

class ComplexQueryService extends BaseApplicationComponent
{
    protected $criteria;
    protected $dbCommand;

    public function setSource(ElementCriteriaModel $criteria)
    {
        $this->criteria = $criteria;

        return $this;
    }

    public function withRelatedTotals(ElementCriteriaModel $relation)
    {
        $this->criteria->relatedTo = ['sourceElement' => $relation];
        $this->dbCommand = $this->extendElementCriteriaModel();

        if (! $this->dbCommand) {
            return $this->populateModels();
        }

        $this->addCount('sources1.targetId');

        return $this;
    }

    public function find()
    {
        if ($this->dbCommand) {
            $result = $this->dbCommand->queryAll();

            return $this->populateModels($result);
        }

        return $this->criteria->find();
    }

    protected function extendElementCriteriaModel()
    {
        return craft()->elements->buildElementsQuery($this->criteria);
    }

    protected function addCount($field, $alias = 'count')
    {
        $this->dbCommand->addSelect('count(' . $field . ') AS ' . $alias);
    }

    protected function populateModels($result = null)
    {
        return ComplexQueryModel::populateModels($result);
    }

    /**
     * For debugging
     */
    public function showResult()
    {
        if ($this->dbCommand) {
            Craft::dd($this->dbCommand->queryAll());
        }

        Craft::dd($this->criteria->find());
    }

    public function showSql()
    {
        print_r(str_replace(
            array_keys($this->dbCommand->params),
            array_values($this->dbCommand->params),
            $this->dbCommand->getText()
        ));

        Craft::dd('');
    }

    public function showCriteria()
    {
        Craft::dd($this->criteria->getAttributes());
    }
}

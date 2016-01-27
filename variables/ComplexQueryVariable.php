<?php namespace Craft;

class ComplexQueryVariable
{
    public function source(ElementCriteriaModel $criteria)
    {
        return craft()->complexQuery->setSource($criteria);
    }
}

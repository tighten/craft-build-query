<?php namespace Craft;

/**
 * Demonstration of how to extend the ElementCriteriaModel
 * for making complex or optimized queries.
 */
class BuildQueryPlugin extends BasePlugin
{

    public function getName()
    {
        return 'BuildQuery Demo';
    }

    public function getVersion()
    {
        return '1.0';
    }

    public function getDeveloper()
    {
        return 'Tighten Co. • Keith Damiani';
    }

    public function getDeveloperUrl()
    {
        return 'http://tighten.co';
    }

}

<?php namespace Craft;

/**
 * A plugin for Craft CMS, demonstrating how to make complex queries possible
 * by modifying an ElementCriteriaModel using buildElementsQuery().
 */

class ComplexQueryPlugin extends BasePlugin
{

    public function getName()
    {
        return 'ComplexQuery Demo';
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

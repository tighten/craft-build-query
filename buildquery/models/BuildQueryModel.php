<?php namespace Craft;

/**
 * BuildQuery Model
 *
 * If our ElementCriteriaModel has been converted into a dbCommand,
 * calling `queryAll()` will give us an array. We need to convert
 * the array into a EntryModel that Craft can work with.
 *
 */

class BuildQueryModel extends EntryModel
{
    /**
     * Allow access to any additional attributes we've set.
     *
     * In our example, 'count' was added to the query, and can
     * now be accessed as an attribute in the result object
     * using {{ result.count }} in our template.
     *
     * @var  boolean
     */
    protected $strictAttributes = false;

    /**
     * Convert the query result array into an array of EntryModels,
     * which can be interacted with as a normal Craft result object.
     *
     * NOTE: This method is optional.
     *
     * We could omit this and instead just call `populateModels()`
     * on the parent EntryModel. Doing it explicitly here, though,
     * allows us to call `setContent()` to set the correct ContentModel
     * for each EntryModel. This can potentially save a few extra
     * queries later on when getting field contents in a template,
     * but is only necessary for optimization reasons.
     *
     * @param   Array  $result
     * @return  Array
     */
    public static function populateModels($result = null)
    {
        $models = [];

        foreach ($result as $value) {
            $model = self::populateModel($value);
            $model->setContent($value);
            $models[] = $model;
        }

        return $models;
    }
}

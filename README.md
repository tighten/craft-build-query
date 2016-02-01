# Building Complex Queries in Craft CMS

![Tighten.co](https://cloud.githubusercontent.com/assets/357312/12723277/be17beda-c8d6-11e5-97ce-db2281b9b283.png)

A plugin for Craft CMS, demonstrating how to build complex or optimized queries by modifying an **ElementCriteriaModel** using `buildElementsQuery()`.

For background on what this demo plugin is all about, see the post [Craft CMS: Extending the ElementCriteriaModel for Complex Queries](http://blog.tighten.co/craft-cms-extending-the-elementcriteriamodel-for-complex-queries) on the **Tighten.co** blog.

### Installation

Add the `buildquery` folder to your `craft/app/plugins` directory, then activate the BuildQuery plugin in the _Settings_ section of Craft's control panel.

### Use

This plugin can be used as a starting point for adding your own advanced query logic, allowing you to perform queries that aren't possible using Craft's built-in methods. Using this plugin as a basis, you can, for instance:

* Include data from a third-party plugin in your query
* Perform a complex join involving data from several tables
* Optimize a complex search in order to reduce the number of database queries performed
* Group, order, and aggregate results at the database level rather than relying on the `group` filter in your Twig template

To begin your query, call the `buildQuery` variable from within a Twig template, and pass it an initial ElementCriteriaModel as `source`:

```twig
craft.buildQuery.source(...)
```

From there, you can chain additional query methods that you store in `BuildQueryService`, and finally grab your results with `find`:

```twig
craft.buildQuery.source(serviceEntries).countRelated(workEntries).find
```

Take a look at `yourOwnMethod()` in `services/BuildQueryService.php` for a good place to start building your own complex query logic.

___

### Example
Using Craft's HappyLager demo site as an example, suppose we want to show the number of *Work* entries that are related to each *Service* entry in the Services navigation bar:

![Work counts](https://cloud.githubusercontent.com/assets/357312/12723250/a475b4e6-c8d6-11e5-981b-e0a35e2166ff.png)


The typical way to do this would be to add a `relatedTo` query inside the loop where we output each Service, and grab each `total`:

```twig
{% for serviceEntry in craft.entries.section('services') %}

    {# Perform a `relatedTo` query for each element in `serviceEntry` #}
    {% set workCount = craft.entries.section('work').relatedTo(serviceEntry).total() %}

    <li>
        <a href="{{ serviceEntry.url }}" class="subfont">
            {{ serviceEntry.title }} · {{ workCount }}
        </a>
    </li>

{% endfor %}
```

The downside to this standard approach is that we are firing an additional database query for each Service. If we have only 6 services, this isn't a huge deal; but if we wanted to calculate totals for 50 elements, all those extra queries would start to add up fast.

Using `buildElementsQuery()`, we can optimize this count by attaching the `relatedTo` criteria to our original query, and adding a `COUNT` statement to our query's `SELECT` clause. This gives us the same results, but requires only 1 additional query—regardless of how many elements we have (`n`)—rather than performing `n+1` queries.

```twig
{# Get ElementCriteriaModels for Service and Work sections #}
{% set serviceEntries = craft.entries.section('services') %}
{% set workEntries = craft.entries.section('work') %}

{% for serviceEntry in craft.buildQuery.source(serviceEntries).countRelated(workEntries).find %}

    <li>
        <a href="{{ serviceEntry.url }}" class="subfont">
            {{ serviceEntry.title }} · {{ serviceEntry.workCount }}
        </a>
    </li>

{% endfor %}
```

### To see the plugin example in action:

1. Install [Craft's HappyLager demo site](https://github.com/pixelandtonic/HappyLager)
2. Add and activate this plugin (see [Installation](#installation) above)
2. Rename the existing template file `templates/services/_entry.html` to `_entry_original.html` for safekeeping
3. Replace it with the example template file from this plugin, located at `examples/happylager/services/_entry.html`
4. Visit the **How It's Made** section, and click one of the Section tiles on the page (e.g. **Design**)

___

### Debugging

The plugin includes a few methods that are helpful when building and debugging a complex query. These can be called from within a Twig template to dump details about your query.

* To display details about the ElementCriteriaModel that your query is built on:
    `{% do craft.buildQuery.debugCriteria %}`
* To dump the underlying SQL query that is built after the ElementCriteriaModel has been converted to a dbCommand object:
    `{% do craft.buildQuery.debugSql %}`
* To show the results of your query in array form, before they get populated into **EntryModel**s:
    `{% do craft.buildQuery.debugResults %}`


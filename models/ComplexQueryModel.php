<?php namespace Craft;

class ComplexQueryModel extends EntryModel
{
    protected $custom_attributes = [
        'count' => AttributeType::Number,
        'title' => AttributeType::String,
        'url' => AttributeType::String,
    ];

    protected function defineAttributes()
    {
        return array_merge($this->custom_attributes, parent::defineAttributes());
    }
}

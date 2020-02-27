<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'parent_id'];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

    public function parents()
    {
        $parents = [];
        $parent = $this->parent;
        while (!empty($parent)) {
            array_push($parents, $parent);
            $parent = $parent->parent;
        }
        return $parents;
    }
}

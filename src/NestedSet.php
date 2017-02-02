<?php


namespace ITaikai;


use Kalnoy\Nestedset\NodeTrait;

trait NestedSet  {
    use NodeTrait;

    public function getLftName()
    {
        return 'lft';
    }

    public function getRgtName()
    {
        return 'rght';
    }

    public function getParentIdName()
    {
        return 'parent_id';
    }

    /**
     * @return Collection
     */
    public static function getLeafs()
    {
        return self::whereRaw("rght = lft + 1")->pluck('title', 'id');
    }
}
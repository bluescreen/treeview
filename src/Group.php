<?php


namespace ITaikai;


class Group extends Model {

    public static function adjust($maxPerGroup, $count = 0)
    {
        if ($count <= 0 || $maxPerGroup <= 0) {
            return;
        }
        $neededGroups   = (int)ceil($count / $maxPerGroup);
        $currentGroups  = self::count();
        do {
            if ($currentGroups < $neededGroups) {
                self::add($maxPerGroup);
                $currentGroups++;
            }
            if ($currentGroups > $neededGroups) {
                self::removeLast();
                $currentGroups--;
            }
        } while ($currentGroups != $neededGroups);
    }

    public static function add($group_size = null, $parent_id = null)
    {
        $last = self::orderBy('group_pos', 'DESC')->first();
        $group_pos = (!empty($last)) ? $last->group_pos : 0;
        $next_pos  = (!empty($last)) ? $last->next_pos : 0;

        return self::create([
            'group_size' => $group_size,
            'next_pos'   => ($next_pos == 1) ? 2 : 1,
            'group_pos'  => $group_pos + 1,
            'parent_id'  => $parent_id,
            'name'       => __('Group')." ".($group_pos + 1),
        ]);
    }

    private static function removeLast()
    {
        self::orderBy('group_pos', 'DESC')->first()->delete();
    }
}
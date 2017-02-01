<?php


use ITaikai\Competitor;

trait Shared {

    protected function given_competitors($num)
    {
        factory(Competitor::class, [], $num);
        Competitor::participateAll();
    }
}
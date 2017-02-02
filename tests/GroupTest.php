<?php


use ITaikai\Group;
use ITaikai\Participant;

class GroupTest extends TestCase
{
    use DatabaseTransactions,Shared;

    /** @test */
    public function it_should_add_single_group_of_size(){
        $group1 =Group::add(3);
        $group2 = Group::add(3, $group1->id);

        $this->assertEquals(3, $group2->group_size);
        $this->assertEquals(2, $group2->group_pos);
        $this->assertEquals("Group 2", $group2->name);
        $this->assertEquals(2, $group2->next_pos);
        $this->assertEquals(1, $group2->parent_id);
    }

    /** @test */
    public function it_should_adjust_group_size_to_seeds(){
        $this->given_competitors(12);
        Group::adjust(3, Participant::count());
        $this->assertCount(4, Group::get());
    }

}
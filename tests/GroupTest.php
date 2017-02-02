<?php


use ITaikai\Group;

class GroupTest extends TestCase
{
    use DatabaseTransactions, Shared;

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
    public function it_should_adjust_group_size_to_seeds_increase(){
        Group::adjust(3, 12);
        $this->assertCount(4, Group::get());
    }

    /** @test */
    public function it_should_adjust_group_size_to_seeds_decrease(){
        factory(Group::class, [], 4);

        Group::adjust(3, 5);
        $this->assertCount(2, Group::get());
    }

}
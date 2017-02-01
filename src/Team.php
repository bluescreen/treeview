<?php


namespace ITaikai;


use Illuminate\Support\Collection;

class Team extends Model {

    public function participants()
    {
        return $this->belongsToMany(Participant::class, 'team_participants')->orderBy('position', 'ASC');
    }

    public function group()
    {
        return $this->belongsToMany(Group::class, 'team_groups');
    }



    public function assignParticipants(Collection $participants)
    {
        $this->participants()->detach();
        $participants->each(function (Participant $participant, $pos) {
            $this->participants()->attach($participant, ['position' => $pos + 1]);
            $participant->update(['team_id' => $this->id]);
        });
    }

    public function assignToGroup($group_id, $pos)
    {
        $this->group()->detach();
        $this->group()->attach($group_id, ['pos' => $pos, 'tournament_id' => $this->tournament_id]);
    }

    public function getTeamMembers()
    {
        return $this->participants()->with('competitor')->get();
    }
}
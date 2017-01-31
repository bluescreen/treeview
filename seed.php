<?php
use ITaikai\Competition;
use ITaikai\Competitor;
use ITaikai\Match;
use ITaikai\Participant;
use ITaikai\Seed;

Match::truncate();
Participant::truncate();
Competitor::truncate();
Match::truncate();
Competition::truncate();

Competition::create([
    'name'     => "Test Tournament",
    'date'     => date('Y-m-d', time()),
    'location' => 'Testhausen',
    'type'     => 'individual',
    'mode'     => 'elimination'
]);

$num = isset($_REQUEST['num']) ? $_REQUEST['num'] : 4;

for ($i = 1; $i <= $num; $i++) {
    $competitor = Competitor::create(['id' => $i, 'name' => 'Test ' . $i, 'pass_nr' => $i]);
    Participant::create(['competitor_id' => $competitor->id, 'tournament_id' => 1, 'group_id' => 1]);
}
$depth = ceil(log($num,2))-1;


$seeds = Participant::inRandomOrder()->lists('competitor_id');
$seed = new Seed();
$seed->createPlayoffMatches($seeds, $depth);
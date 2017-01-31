<?
use ITaikai\Competition;
use ITaikai\Competitor;
use ITaikai\Match;
use ITaikai\Participant;
use ITaikai\Seed;

include_once "bootstrap.php";

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

$num = 16;

for ($i = 1; $i <= $num; $i++) {
    $competitor = Competitor::create(['id' => $i, 'name' => 'Test ' . $i, 'pass_nr' => $i]);
    Participant::create(['competitor_id' => $competitor->id, 'tournament_id' => 1, 'group_id' => 1]);
}
$depth = ceil(log($num,2))-1;

$seed = new Seed();
$seed->createPlayoffMatches([], $depth);
Match::fixTree();


$participant = new Participant();
$match       = new Match();

$pools        = Participant::getNameList();
$matches      = Match::getTreeMatches();
$participants = Match::getMatchPartcipants();
$paths        = Match::getTreePaths();

die(json_encode(compact('depth', 'pools', 'participants', 'matches', 'paths')));
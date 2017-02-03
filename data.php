<?
use ITaikai\Competition;
use ITaikai\Competitor;
use ITaikai\Match;
use ITaikai\Participant;
use ITaikai\Seed\IndividualSeed;

include_once "bootstrap.php";


Competition::clear();
$num = isset($_REQUEST['num']) ? $_REQUEST['num'] : 4;
$num = ($num == 'random') ? rand(2, 32) : $num;

factory(Competition::class);
factory(Competitor::class, [], $num);
Competitor::participateAll();

$seed = new IndividualSeed();
$seed->setup('elimination');

Match::simulateAll($seed->getDepth());
$winner       = Match::getTotalWinner();
$depth        = $seed->getDepth() - 1;
$pools        = Participant::getNameList();
$matches      = Match::getTreeMatches();
$participants = Match::getMatchPartcipants();
$paths        = Match::getTreePaths();

header("Content-Type: application/json");
die(json_encode(compact('depth', 'pools', 'participants', 'matches', 'paths', 'winner')));
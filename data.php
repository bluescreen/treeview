<?
use ITaikai\Competition;
use ITaikai\Competitor;
use ITaikai\IndividualMatch;
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

IndividualMatch::simulateAll($seed->getDepth());
$winner       = IndividualMatch::getTotalWinner();
$depth        = $seed->getDepth() - 1;
$pools        = Participant::getNameList();
$matches      = IndividualMatch::getTreeMatches();
$participants = IndividualMatch::getMatchPartcipants();
$paths        = IndividualMatch::getTreePaths();

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Max-Age: 86400');
die(json_encode(compact('num','depth', 'pools', 'participants', 'matches', 'paths', 'winner')));

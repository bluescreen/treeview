<?
use ITaikai\Match;
use ITaikai\Participant;

include_once "bootstrap.php";
include_once "seed.php";

$pools        = Participant::getNameList();
$matches      = Match::getTreeMatches();
$participants = Match::getMatchPartcipants();
$paths        = Match::getTreePaths();

die(json_encode(compact('depth', 'pools', 'participants', 'matches', 'paths')));
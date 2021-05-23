<?
use ITaikai\Competition;
use ITaikai\Competitor;
use ITaikai\IndividualMatch;
use ITaikai\Participant;
use ITaikai\Seed\IndividualSeed;

include_once "bootstrap.php";

function createCompetition(){
    $faker = Faker\Factory::create();
    Competition::create([
        'name'       => $faker->sentence,
        'date'       => $faker->dateTime,
        'group_size' => 3,
        'type'       => 'individual',
        'mode'       => 'elimination',
        'location'   => $faker->city
    ]);
}

function createCompetitors($num){
    $faker = Faker\Factory::create();

    $firstName = $faker->firstName;
    $lastName  = $faker->lastName;

    for($i=0;$i<= $num; $i++){

        Competitor::create([
            'name'       => $lastName,
            'first_name' => $firstName,
            'alias'      => '',
            'birth_date' => $faker->dateTime,
            'pass_nr'    => microtime(),
            'grading_id' => rand(1, 8),
        ]);
    }
}

function createIndividualMatch(){

    IndividualMatch::create([
        'max_points'    => 2,
        'max_time'      => 180,
        'title'         => "TestMatch",
        'white_id'      => 1,
        'red_id'        => 2,
        'tournament_id' => 1,
        'winner_id'     => 0,
        'score_red'     => '{"men":0,"kote":0,"do":0,"tsuki":0,"penalty":0,"hansoku":0}',
        'score_white'   => '{"men":0,"kote":0,"do":0,"tsuki":0,"penalty":0,"hansoku":0}',
        'points_white'  => 0,
        'points_red'    => 0,
    ]);
}


Competition::clear();
$num = isset($_REQUEST['num']) ? $_REQUEST['num'] : 4;
$num = ($num == 'random') ? rand(2, 32) : $num;

createCompetition();
createCompetitors($num);

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
die(json_encode(compact('num','depth', 'pools', 'participants', 'matches', 'paths', 'winner')));

<?php


namespace ITaikai;


class Configure {

    public static function data()
    {
        return [
            'team_positions' => ['Senpo', 'Jiho', 'Chuken', 'Fukusho', 'Taisho', 'Substitute 1', 'Substitute 2'],
            'types'          => ['individual' => __('Individual'), 'team' => __('Team')],
            'modes'          => [
                'random'      => __('Random Match'),
                'elimination' => __('Elimination'),
                'pool'        => __('Pool'),
                'both'        => __('Pool and Elimination'),
                'round-robin' => __('Round Robin'),
            ],
            'team_size'      => 3,        // Größe der Teams
            'group_size'     => 3,        // Standardgröße der Pools
            'chance_hit'     => 50,        // Treffer Chance (Nur für Simulation)
            'chance_penalty' => 0,        // Strafpunkt Chance (Nur für Simulation)
            'max_points'     => 2,        // sanbon shobu
            'max_time'       => 180,    // Kampfzeit
            'max_overtime'   => 360,    // Verlängerung

            'hits'   => [
                'men'     => ['symbol' => 'メ', 'percent' => 60, 'message' => '%s men ari!'],
                'kote'    => ['symbol' => 'コ', 'percent' => 28, 'message' => '%s kote ari!'],
                'do'      => ['symbol' => 'ド', 'percent' => 10, 'message' => '%s do ari!'],
                'tsuki'   => ['symbol' => 'ツ', 'percent' => 1, 'message' => '%s tsuki ari!'],
                'penalty' => ['symbol' => '▲', 'percent' => 12, 'message' => '%s hansoku ikai!'],
                'hansoku' => ['symbol' => '❙', 'percent' => 0, 'message' => '%s hansoku nikai, ippon ari!'],
            ],
            'events' => [
                'first-penalty'  => ['message' => '%s hansoku ikai'],
                'second-penalty' => ['message' => '%s hansoku nikai, ippon ari'],
                'draw'           => ['message' => 'hikiwake'],
                'overtime'       => ['message' => 'encho'],
                'win'            => ['message' => '%s shobu ari!'],
                'first'          => ['message' => 'nihon me!'],
                'second'         => ['message' => 'shobu!'],
                'decision'       => ['message' => 'hantei gachi shobu ari!'],
            ],
            'flags'  => [
                'nopoint'    => 'f-noippon.gif',
                'wait'       => 'f-matte.gif',
                'conference' => 'f-gogi.gif',
                'draw'       => 'f-hikiwake.gif',
                'score'      => 'f-ippon.gif',
                'neutral'    => 'f-neutral.gif',
                'seperate'   => 'f-wakare.gif'
            ]
        ];
    }

    public static function read($name)
    {
        $parts    = explode(".", $name);
        $lastPart = end($parts);
        $data     = self::data();
        return isset($data[$lastPart]) ? $data[$lastPart] : null;
    }
}
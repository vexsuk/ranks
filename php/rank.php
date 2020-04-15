<?php
    class Rank
    {
        private $name;
        private $hours;
        private $points;
        private $epaulette;

        function __construct($name, $hours, $points, $bonus, $old)
        {
            $this->name = $name;
            $this->hours = $hours;
            $this->points = $points;
            $this->bonus = $bonus;
            $this->old = $old;
            $this->epaulette = strtolower(preg_replace("/ |-/", '_', "{$this->name}"));
        }

        public function toJSON() {
            $object = array(
                'name' => $this->name,
                'epaulette' => "./public/images/epaulettes/" . ($this->old ? 'old' : 'new') . "/{$this->epaulette}." . ($this->old ? 'gif' : 'png')
            );

            return $object;
        }

        public function checkRequirementsMet($hours, $points, $bonus)
        {
            return ($hours >= $this->hours) && ($points >= $this->points) && ($bonus >= $this->bonus);
        }
    }

    class Ranks
    {
        public $ranks = [];

        public function getRanks()
        {
            $file = fopen(dirname(__FILE__)."/./data/{$this->fileName}.csv", "r");
            while(!feof($file)) {
                [$name, $hours, $points, $bonus] = fgetcsv($file);

                if ($name != 'name') {
                    array_push($this->ranks, new Rank($name, $hours, $points, $bonus, $this->old));
                }
            }
            fclose($file);
        }

        public function findRankFromCriteria($hours, $points, $bonus)
        {
            $index = 0;
            $rankFound = FALSE;
            $rank = null;
            while ($rankFound === FALSE)
            {
                $rank = $this->ranks[$index];
                $rankFound = $rank->checkRequirementsMet($hours, $points, $bonus);
                $index = ++$index;
            }

            return $rank;
        }
    }

    class NewRanks extends Ranks
    {
        function __construct()
        {
            $this->old = FALSE;
            $this->fileName = 'newRanks';
            $this->getRanks();
        }
    };

    class OldRanks extends Ranks
    {
        function __construct()
        {
            $this->old = TRUE;
            $this->fileName = 'oldRanks';
            $this->getRanks();
        }
    };

    class RankSystem
    {
        public $oldRanks;
        public $newRanks;

        public function __construct()
        {
            $this->oldRanks = new OldRanks;
            $this->newRanks = new NewRanks;
        }

        public function getRanks($hours, $points, $bonus) {
            $oldRank = $this->oldRanks->findRankFromCriteria((int)$hours, (int)$points + (int)$bonus, 0);
            $newRank = $this->newRanks->findRankFromCriteria((int)$hours, (int)$points, (int)$bonus);

            return [$oldRank, $newRank];
        }
    }

    $rankSystem = new RankSystem;

    function exportRanks($oldRank, $newRank) {
        $object = new stdClass();

        $object->oldRank = $oldRank->toJSON();
        $object->newRank = $newRank->toJSON();

        echo json_encode($object);
    }

    if (isset($_POST))
    {
        if ($_POST["mode"] === "manual") {
            [$oldRank, $newRank] = $rankSystem->getRanks($_POST["hours"], $_POST["points"], $_POST["bonus"]);
            exportRanks($oldRank, $newRank);
        } else if ($_POST["mode"] === "id") {
            $pilot;
            $url = 'https://vamsys.io/api/bot';
            $data = array(
                'api_key' => 'REDACTED',
                'username' => 'EXS' . $_POST["pilotId"]
            );
            $jsonData = json_encode($data);

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            $result = curl_exec($ch);

            if ($result == '"Incorrect Pilot ID"' || $result == '"No Such Pilot"') {
                $pilot = 'NO_SUCH_PILOT';
            } else {
                $pilot = json_decode($result, true);
            }

            [$oldRank, $newRank] = $rankSystem->getRanks($pilot['stats']['hours'] / 3600, $pilot['stats']["points"], $pilot['stats']["bonus_points"]);
            exportRanks($oldRank, $newRank);
        }
    };
?>
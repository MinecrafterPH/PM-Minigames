<?php
namespace SkyWars\Tasks;

use pocketmine\Player;
use pocketmine\scheduler\PluginTask;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\math\Vector3;
use pocketmine\block\Block;

use SkyWars\Main;

class StartGame extends PluginTask{
    public $plugin;
    public $time;
    public $gameNumber;
    public function __construct(Main $plugin, $gameNumber,$time){
        $this->plugin = $plugin;
        parent::__construct($plugin);
        $this->gameNumber = $gameNumber;
        $this->time = $time;


    }



    /**
     * Actions to execute when run
     *
     * @return void
     */
    public function onRun($currentTick){
        $levelName = "game-$this->gameNumber";
        $time = $this->time;
        if($time == 0){
            $this->plugin->game[$this->gameNumber."-started"] = true;
            $this->plugin->game[$this->gameNumber."-open"] = false;
            foreach($this->getServer()->getLevelByName($levelName)->getPlayers() as $p){
                $this->getServer()->getLevelByName("game-".$this->gameNumber)->setBlock(new Vector3($p->getX(),$p->getY()-1,$p->getZ()),Block::get(0,0));
                $p->sendPopup(TextFormat::GOLD . TextFormat::BOLD . "The game has started!");
            }
        }else{
            foreach($this->getServer()->getLevelByName($levelName)->getPlayers() as $p) {
                $p->sendPopup(TextFormat::GOLD . TextFormat::BOLD . "The game starting in " . TextFormat::BOLD . TextFormat::GREEN . $this->time . TextFormat::GOLD . TextFormat::BOLD . ".");
            }
        }

    }

}

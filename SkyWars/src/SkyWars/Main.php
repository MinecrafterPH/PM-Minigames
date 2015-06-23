<?php

namespace SkyWars;

use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\utils\TextFormat;

class Main extends PluginBase implements Listener
{
  
  public function onEnable()
  {
    $this->getServer()->getPluginManager()->registerEvents($this,$this);
    $this->getLogger()->info("SkyWars has been enabled.");
    $this->game = [
      "1-players" => 0,"1-open"=>true,"1-started"=>false,"1-player-1"=>false,"1-player-2"=>false,"1-player-3"=>false,"1-player-4"=>false
      ,"2-players" => 0,"2-open"=>true,"2-started"=>false,"2-player-1"=>false,"2-player-2"=>false,"2-player-3"=>false,"2-player-4"=>false
      ,"3-players" => 0,"3-open"=>true,"3-started"=>false,"3-player-1"=>false,"3-player-2"=>false,"3-player-3"=>false,"3-player-4"=>false
      ,"4-players" => 0,"4-open"=>true,"4-started"=>false,"4-player-1"=>false,"4-player-2"=>false,"4-player-3"=>false,"4-player-4"=>false
      ,"5-players" => 0,"5-open"=>true,"5-started"=>false,"5-player-1"=>false,"5-player-2"=>false,"5-player-3"=>false,"5-player-4"=>false
      ,"6-players" => 0,"6-open"=>true,"6-started"=>false,"6-player-1"=>false,"6-player-2"=>false,"6-player-3"=>false,"6-player-4"=>false
      ,"7-players" => 0,"7-open"=>true,"7-started"=>false,"7-player-1"=>false,"7-player-2"=>false,"7-player-3"=>false,"7-player-4"=>false
      ,"8-players" => 0,"8-open"=>true,"8-started"=>false,"8-player-1"=>false,"8-player-2"=>false,"8-player-3"=>false,"8-player-4"=>false
    ];
  } 
  
  public function onDisable()
  {
    $this->getLogger()->info("SkyWars has been disabled.");
  } 
  
  public function getAvailableGame()
  {
    $openGames = [];
      $i = 0;
      $k = 1;
      while ($i !=8){
        $i++;
        if ($this->game[$i."-open"] == true){
          $openGames[$k] = $i;
          $k++;
        }
      }
    return $openGames;
  }
  
  

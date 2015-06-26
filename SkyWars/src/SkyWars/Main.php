<?php

namespace SkyWars;

use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\utils\TextFormat;

class Main extends PluginBase implements Listener
{
  
  public function onEnable()
  {
    $this->getServer()->getPluginManager()->registerEvents($this,$this);
    $this->getLogger()->info("SkyWars has been enabled.");
    $cfg = $this->getConfig();
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
  
  public function playerJoinGame (Player $player,$gameNumber)
  {
    $levelName = "game-".$gameNumber;
    $e = 0;
    while ($e < 4){
      $e++;
      if($this->game[$gameNumber."-player-".$e] == false){
        $place = $e;
      }
    }
    if(isset($place)){
      if($place < 5){
        $player->teleport($this->getServer()->getLevelByName($levelName)->getSafeSpawn());
        $pos = $this->pedestrals["$gameNumber-$place"];
        if(isset($pos) and count($this->getServer()->getLevelByName($levelName)->getPlayers())<=4){
          $player->teleport($pos);
          }else{
            $player->sendMessage(TextFormat::RED."[Error 2] No place available");
            $player->teleport($this->getServer()->getDefaultLevel()->getSpawnLocation());
          }
        $numberOfPlayers = count($this->getServer()->getLevelByName($levelName)->getPlayers());
        foreach($this->getServer()->getLevelByName($levelName)->getPlayers() as $p){
          $p->sendMessage(TextFormat::BLUE."[Skywars][".$gameNumber."] Number of player(s) : ".$numberOfPlayers."/4");
        }
      }
      if (isset($numberOfPlayers) and $numberOfPlayers == 4){
        $this->getServer()->getScheduler()->scheduleDelayedTask(new Tasks\StartGame($this,$gameNumber,3),0);
        $this->getServer()->getScheduler()->scheduleDelayedTask(new Tasks\StartGame($this,$gameNumber,2),20);
        $this->getServer()->getScheduler()->scheduleDelayedTask(new Tasks\StartGame($this,$gameNumber,1),40);
        $this->getServer()->getScheduler()->scheduleDelayedTask(new Tasks\StartGame($this,$gameNumber,0),60);
        $this->game[$gameNumber."-open"] = false;
      }
    $player->getInventory()->clearAll();
    $player->setHealth(20);
    $item = Item::get(297);
    $item->setCount(6);
    $player->getInventory()->addItem($item);
    $item = Item::get(1);
    $item->setCount(64);
    $player->getInventory()->addItem($item);
    if(isset($this->playerKits[$player->getName()]) == false){
      $this->playerKits[$player->getName()] = "default";
    }
    switch($this->playerKits[$player->getName()]){
      case "default":
        if(isset($this->vips[$player->getName()]) and $this->vips[$player->getName()] == true){
          $player->getInventory()->setArmorContents([Item::get(302),Item::get(303),Item::get(304),Item::get(305)]);
          $player->getInventory()->addItem(Item::get(267));
        }else{
          $player->getInventory()->setArmorContents([Item::get(298),Item::get(299),Item::get(300),Item::get(301)]);
          $player->getInventory()->addItem(Item::get(268));
        }
      break;
      case "vip":
        $player->getInventory()->setArmorContents([Item::get(302),Item::get(303),Item::get(304),Item::get(305)]);
        $player->getInventory()->addItem(Item::get(267));
      break;
      case "archer":
        $player->getInventory()->setArmorContents([Item::get(298),Item::get(299),Item::get(300),Item::get(301)]);
        $player->getInventory()->addItem(Item::get(261));
        $item = Item::get(262);
        $item->setCount(32);
        $player->getInventory()->addItem($item);
        break;
      case "barbarian":
        $player->getInventory()->setArmorContents([Item::get(298),Item::get(299),Item::get(300),Item::get(301)]);
        $player->getInventory()->addItem(Item::get(268));
      break;
      case "miner":
        $player->getInventory()->addItem(Item::get(303));
        $player->getInventory()->addItem(Item::get(257));
      }
    $this->game[$gameNumber."-player-".$place] = true;
    $this->playersInGame[$player->getName()] = true;
    $this->playersInGame[$player->getName()."-gameNumber"] = $gameNumber;
    $this->playersInGame[$player->getName()."-place"] = $place;
    }
    else{
      $player->sendMessage(TextFormat::RED."No game available. Please try again later");
    }
  }
  
  public function onBlockBreak(BlockBreakEvent $event){
		if($event->getPlayer()->getLevel()->getName() == $cfg->get('lobby') and !$event->getPlayer()->hasPermission("skywars.breakblocks")){
			$event->setCancelled(); 
			$event->getPlayer()->sendMessage("You are not allowed to break blocks.");
		}
	}
	
	public function onBlockPlace(BlockPlaceEvent $event){
		if($event->getPlayer()->getLevel()->getName() == $this->getConfig()->get('lobby') and !$event->getPlayer()->hasPermission("skywars.placeblocks")){
			$event->setCancelled();
			$event->getPlayer()->sendMessage("You sre not allowed to place blocks.");
		}
	}
}

?>

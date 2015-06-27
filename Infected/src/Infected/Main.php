<?php
 
namespace Infected;
 
use Infected\BaseGame;
use Infected\utils\TipTask;
use Infected\utils\InvincibilityTask;
use Infected\utils\minigames\utils;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\utils\TextFormat;
use pocketmine\level\Position;
use pocketmine\item\LeatherBoots;
use pocketmine\item\LeatherCap;
use pocketmine\item\LeatherPants;
use pocketmine\item\LeatherTunic;
use pocketmine\item\ChainBoots;
use pocketmine\item\ChainChestplate;
use pocketmine\item\ChainHelmet;
use pocketmine\item\ChainLeggings;
use pocketmine\item\StoneSword;
use pocketmine\item\IronSword;
use pocketmine\event\entity\EntityDamageByBlockEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\block\Air;
use pocketmine\event\player\PlayerRespawnEvent;
 
class Infected implements BaseGame, Listener
{
        public static $isRunning;
        public $plugin;
        public $invincibilityTask;
        private $level;
        private $spawn
       
        public $alive = [];
        public $infected = [];
       
        public function __construct($plugin)
        {
                $this->plugin = $plugin;
                $this->level = $this->plugin->getServer()->getLevelByName("infected");
                $this->spawn = new Position(133, 4,  128, $this->level);
        }
       
        public function start($players)
        {
                $this->alive = $players;
                foreach($this->alive as $p)
                {
                        $p->teleport($this->spawn);
                        $p->getInventory()->setArmorContents(array(new ChainBoots(), new ChainHelmet(), new ChainLeggings(), new ChainHelmet()));
                        $p->getInventory()->addItem(new IronSword());
                        $this->plugin->giveSpeed($p, 1.5);
                }
                $this->plugin->getServer()->getLogger()->info(var_dump($this->alive)); //shows all players
                self::$isRunning = TRUE;
        }
       
        public function onPlayerDeath(PlayerDeathEvent $event)
        {
                $this->plugin->getServer()->getLogger()->info(var_dump($this->alive)); //array is empty for some reason. Why??
                if(self::$isRunning)
                {
                        $this->plugin->getServer()->getLogger()->info(TextFormat::AQUA . "And here...");
                        if(in_array($event->getEntity()->getPlayer(), $this->alive))
                        {
                                $this->plugin->getServer()->getLogger()->info(TextFormat::AQUA . $event->getEntity()->getPlayer()->getName() . " is in alive array");
                        }
                        else
                        {
                                $this->plugin->getServer()->getLogger()->info(TextFormat::AQUA . $event->getEntity()->getPlayer()->getName() . " is in infected array");
                        }
                }
        }
       
        public function stop($winner) {
                foreach($this->infected as $infected)
                {
                        $infected->teleport($this->plugin->spawn);
                        $infected->setNameTag(TextFormat::RESET . $infected->getName);
                }
                foreach($this->alive as $alive)
                {
                        $alive->teleport($this->plugin->spawn());
                }
                self::$isRunning = FALSE;
        }
       
        public function getAlive() { return $this->alive; }
        public function getDesc() { return $this->desc; }
        public function getInfected() { return $this->infected; }
        public function getLevel() { return $this->level; }
        public function getSpawn() { return $this->spawn; }
        public function setInvincible($boolean) { $this->invincible = $boolean; }
}

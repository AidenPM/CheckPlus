<?php

declare(strict_types=1);

namespace pju6791\CheckPlus;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\nbt\tag\IntTag;
use pocketmine\utils\SingletonTrait;

use onebone\economyapi\EconomyAPI;

use pju6791\CheckPlus\command\CheckCommand;
use pju6791\CheckPlus\listener\EventListener;

use function class_exists;

class CheckPlus extends PluginBase
{

    use SingletonTrait;

    public static $prefix = '§l§e[수표] §r§f';

    public function onLoad()
    {
        self::setInstance($this);
    }

    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);

        $this->getServer()->getCommandMap()->register('pju6791', new CheckCommand());

        if(!class_exists(EconomyAPI::class)) {
            $this->getServer()->getPluginManager()->disablePlugin($this);
            $this->getServer()->getLogger()->alert("EconomyAPI가 필요합니다.");
        }
    }

    public static function message(Player $player, string $message)
    {
        $player->sendMessage(self::$prefix . $message);
    }

    public function getCheck(Player $player, int $money) :void {

        $check = Item::get(ItemIds::PAPER, 0, 10);
        $KoreaWon = EconomyAPI::getInstance()->koreanWonFormat($money);
        $check->setCustomName("§r§a[ §f수표 §a]");
        $check->setLore([
            "§r§b====================",
            "",
            "§r§e● §f: 클릭시 §d{$KoreaWon}§f으로 전환됩니다.",
            "§r§a▶ §f: 명령어 : /수표",
            "§r§b===================="
        ]);
        $check->setNamedTagEntry(new IntTag("Check", $money));

        if($player->getInventory()->canAddItem($check)) {
            EconomyAPI::getInstance()->reduceMoney($player, $money);
            $player->getInventory()->addItem($check);
            CheckPlus::message($player, "§a{$KoreaWon}§f이 수표로 전환되었습니다.");
        } else {
            CheckPlus::message($player, "인벤토리 공간이 부족합니다.");
        }
    }
}

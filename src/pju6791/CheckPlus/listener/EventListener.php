<?php

namespace pju6791\CheckPlus\listener;


use onebone\economyapi\EconomyAPI;
use pju6791\CheckPlus\CheckPlus;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\nbt\tag\IntTag;

class EventListener implements Listener
{
    public CheckPlus $owner;

    public function __construct(CheckPlus $owner) {
        $this->owner = $owner;
    }

    public function onInter(PlayerInteractEvent $event) {

        $player = $event->getPlayer();
        $item = $event->getItem();

        $check = $item->getNamedTagEntry("Check");

        if($check instanceof IntTag) {
            $event->setCancelled();
            $item->setCount($item->getCount() - 1);
            $player->getInventory()->setItemInHand($item);
            EconomyAPI::getInstance()->addMoney($player, (int) $check->getValue());
            CheckPlus::message($player, "해당 수표가 돈으로 전환되었습니다.");
        }
    }
}

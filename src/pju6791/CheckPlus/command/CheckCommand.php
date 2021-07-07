<?php

namespace pju6791\CheckPlus\command;

use pocketmine\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pju6791\CheckPlus\form\CheckMainForm;

class CheckCommand extends Command {
    
    public function __construct() {
        parent::__construct("수표", "수표 명령어 입니다.");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $player = $sender;
        
        if ($player instanceof Player) {
            $player->sendForm(new CheckMainForm($player));
        }
    }
}

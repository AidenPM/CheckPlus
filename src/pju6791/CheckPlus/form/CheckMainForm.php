<?php

namespace pju6791\CheckPlus\form;

use pocketmine\Player;
use pocketmine\form\Form;

use pju6791\CheckPlus\CheckPlus;
use onebone\economyapi\EconomyAPI;

use function is_numeric;

class CheckMainForm implements Form {

    public Player $player;

    public function __construct(Player $player) {
        $this->player = $player;
    }

    public function jsonSerialize() :array
    {
        $money = EconomyAPI::getInstance()->myMoney($this->player);

        return [
            "type" => "custom_form",
            "title" => "",
            "content" => [
                [
                    "type" => "label",
                    "text" => "\n§r§b* §7내돈 : {$money}\n"
                ],
                [
                    "type" => "input",
                    "text" => "§r§6[!] §f수표로 전환하실 금액을 입력해주세요."
                ]
            ]
        ];
    }

    public function handleResponse(Player $player, $data): void
    {
        if(!isset($data)) return;

        if($data[1] == null) {
            CheckPlus::message($player, "전환 하실 금액을 정확히 입력해주세요.");
        } else {
            if(EconomyAPI::getInstance()->myMoney($player) < $data[1]) {
                $money = (int) $data[1];
                CheckPlus::getInstance()->getCheck($player, $money);
            } else {
                CheckPlus::message($player, "돈이 부족합니다.");
            }
        }

        if($data[1] < 0) {
            CheckPlus::message($player, "전환 하실 금액은 0보다 커야됩니다.");
        }

        if(!is_numeric($data[1])) {
            CheckPlus::message($player, "전환 하실금액은 숫자로 입력해주세요.");
        }
    }
}

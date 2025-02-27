<?php

/*
 *
 *      ______           __  _                __  ___           __
 *     / ____/___ ______/ /_(_)___  ____     /  |/  /___ ______/ /____  _____
 *    / /_  / __ `/ ___/ __/ / __ \/ __ \   / /|_/ / __ `/ ___/ __/ _ \/ ___/
 *   / __/ / /_/ / /__/ /_/ / /_/ / / / /  / /  / / /_/ (__  ) /_/  __/ /  
 *  /_/    \__,_/\___/\__/_/\____/_/ /_/  /_/  /_/\__,_/____/\__/\___/_/ 
 *
 * FactionMaster - A Faction plugin for PocketMine-MP
 * This file is part of FactionMaster
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 * @author ShockedPlot7560 
 * @link https://github.com/ShockedPlot7560
 * 
 *
*/

namespace ShockedPlot7560\FactionMasterBank\Task;

use pocketmine\scheduler\Task;
use ShockedPlot7560\FactionMaster\Task\DatabaseTask;
use ShockedPlot7560\FactionMasterBank\API\BankAPI;
use ShockedPlot7560\FactionMasterBank\Database\Entity\Money;
use ShockedPlot7560\FactionMasterBank\Database\Table\MoneyTable;
use ShockedPlot7560\FactionMasterBank\FactionMasterBank;

class SyncServerTask extends Task {

    private $main;

    public function __construct(FactionMasterBank $main) {
       $this->main = $main; 
    }

    public function onRun(): void {

        FactionMasterBank::getInstance()->getServer()->getAsyncPool()->submitTask(new DatabaseTask(
            "SELECT * FROM " . MoneyTable::TABLE_NAME,
            [],
            function (array $result) {
                if (count($result) > 0) BankAPI::$money = [];
                foreach ($result as $money) {
                    if ($money instanceof Money) BankAPI::$money[$money->faction] = $money;
                }
            },
            Money::class
        ));
    }
}
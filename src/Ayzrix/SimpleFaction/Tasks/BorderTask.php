<?php

/***
 *       _____ _                 _      ______         _   _
 *      / ____(_)               | |    |  ____|       | | (_)
 *     | (___  _ _ __ ___  _ __ | | ___| |__ __ _  ___| |_ _  ___  _ __
 *      \___ \| | '_ ` _ \| '_ \| |/ _ \  __/ _` |/ __| __| |/ _ \| '_ \
 *      ____) | | | | | | | |_) | |  __/ | | (_| | (__| |_| | (_) | | | |
 *     |_____/|_|_| |_| |_| .__/|_|\___|_|  \__,_|\___|\__|_|\___/|_| |_|
 *                        | |
 *                        |_|
 */

namespace Ayzrix\SimpleFaction\Tasks;

use Ayzrix\SimpleFaction\API\FactionsAPI;
use pocketmine\level\particle\DustParticle;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class BorderTask extends Task {

    /**
     * @param int $currentTick
     * @return bool
     */
    public function onRun(int $currentTick): bool {
        if (empty(FactionsAPI::$border)) return false;
        foreach (FactionsAPI::$border as $name => $bool) {
            $player = Server::getInstance()->getPlayer($name);
            if ($player instanceof Player) {
                $chunk = $player->getLevel()->getChunkAtPosition($player);
                $minX = (float)$chunk->getX() * 16;
                $maxX = (float)$minX + 16;
                $minZ = (float)$chunk->getZ() * 16;
                $maxZ = (float)$minZ + 16;

                for ($x = $minX; $x <= $maxX; $x += 0.5) {
                    for ($z = $minZ; $z <= $maxZ; $z += 0.5) {
                        if ($x === $minX || $x === $maxX || $z === $minZ || $z === $maxZ) {
                            $player->getLevel()->addParticle(new DustParticle(new Vector3($x, $player->getY() + 0.8, $z), 212, 0, 255), [$player]);
                        }
                    }
                }
            } else unset(FactionsAPI::$border[$name]);
        }
        return true;
    }
}
<?php

/*
 * This file is the doff package.
 *
 * (c) Simon Micheneau <contact@simon-micheneau.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SimonDevelop;

use SimonDevelop\Doff;

/**
 * Class DoffFacade
 * Manage your data yaml with query functions and more.
 */
class DoffFacade
{
    /**
     * @param array $datas the tables for the merger
     * @return array return merged of tables or false for error
     */
    public static function fusion(array $datas)
    {
        $doff = new Doff(["path" => __DIR__]);
        return $doff->fusion($datas);
    }

    /**
     * @param array $datas the tables base
     * @param array $datasToRemove the tables for fission
     * @return array return fission of tables
     */
    public static function fission(array $datas, array $datasToRemove)
    {
        $doff = new Doff(["path" => __DIR__]);
        return $doff->fission($datas, $datasToRemove);
    }
}

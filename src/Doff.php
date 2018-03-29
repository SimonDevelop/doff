<?php

/*
 * This file is the array-organize package.
 *
 * (c) Simon Micheneau <contact@simon-micheneau.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SimonDevelop;

/**
 * Class Doff
 * Manage your data yaml with query functions and more.
 */
class Doff
{
    /**
     * @var string $path absolut path of data
     */
    private $path;

    /**
     * @param array $settings Settings
     */
    public function __construct(array $settings = [])
    {
        if (empty($settings)) {
            throw new \Exception("Unable build: Argument $settings must not be empty");
        }
        if (isset($settings["path"])) {
            if (file_exists($settings["path"])) {
                if (is_dir($settings["path"])) {
                    if (is_readable($settings["path"]) && is_writable($settings["path"])) {
                        $this->path = $settings["path"];
                    } else {
                        throw new \Exception("Unable build:
                        Path setting of data must be accessible reading and writing");
                    }
                } else {
                    throw new \Exception("Unable build: Path setting of data must be a dir");
                }
            } else {
                throw new \Exception("Unable build: Path setting of data does not exist");
            }
        } else {
            throw new \Exception("Unable build: Argument $settings need 'path' param for absolut path of data");
        }

    }

    /**
     * @return string Absolut path of data
     */
    public function getPath()
    {
        return $this->path;
    }
}

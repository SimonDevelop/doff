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

use Symfony\Component\Yaml\Yaml;

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
        if (!empty($settings)) {
            if (isset($settings["path"])) {
                if (file_exists($settings["path"])) {
                    if (is_dir($settings["path"])) {
                        if (is_readable($settings["path"]) && is_writable($settings["path"])) {
                            if (substr($settings["path"], -1) == "/") {
                                $this->path = $settings["path"];
                            } else {
                                $this->path = $settings["path"]."/";
                            }
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
        } else {
            throw new \Exception("Unable build: Argument $settings must not be empty");
        }
    }

    /**
     * @param string $dataName name of data file
     * @return array|bool return array or false for error
     */
    public function getData($dataName)
    {
        $filename = strtolower($dataName);
        if (file_exists($this->path.$filename.".yml")) {
            $value = Yaml::parseFile($this->path.$filename.".yml");
            if ($value === null) {
                return [];
            } elseif (is_array($value)) {
                return $value;
            } else {
                return false;
            }
        } else {
            return false;
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

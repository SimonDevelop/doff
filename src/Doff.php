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
use SimonDevelop\ArrayOrganize;

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
     * @var mixed $chmod chmod code for permissions
     */
    private $chmod;

    /**
     * @var string $user user unix for permissions
     */
    private $chown;

    /**
     * @var string $groupe group unix for permissions
     */
    private $chgrp;

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
            if (isset($settings["chmod"])) {
                if (is_numeric($settings["chmod"])) {
                    $this->chmod = $settings["chmod"];
                    chmod($this->path, $this->chmod);
                } else {
                    throw new \Exception("Unable build: Chmod setting is not validate");
                }
            } else {
                $this->chmod = null;
            }
            if (isset($settings["chown"])) {
                if (is_string($settings["chown"])) {
                    $this->chown = $settings["chown"];
                    chown($this->path, $this->chown);
                } else {
                    throw new \Exception("Unable build: Chown setting is not validate");
                }
            } else {
                $this->chown = null;
            }
            if (isset($settings["chgrp"])) {
                if (is_string($settings["chgrp"])) {
                    $this->chgrp = $settings["chgrp"];
                    chgrp($this->path, $this->chgrp);
                } else {
                    throw new \Exception("Unable build: Chown setting is not validate");
                }
            } else {
                $this->chgrp = null;
            }
        } else {
            throw new \Exception("Unable build: Argument $settings must not be empty");
        }
    }

    /**
     * @param string $dataName name of data file
     * @return array|bool return array or false for error
     */
    public function getData(string $dataName)
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
     * @param string $dataName name of data file
     * @param array $where filter param
     * @param array $order order param
     * @return array|bool return array or false for error
     */
    public function select(string $dataName, array $where = [], array $order = [])
    {
        $filename = strtolower($dataName);
        if (file_exists($this->path.$filename.".yml")) {
            if (is_readable($this->path.$filename.".yml")) {
                $value = Yaml::parseFile($this->path.$filename.".yml");
                if ($value === null) {
                    return [];
                } elseif ($value != null && is_array($value)) {
                    $datas = new ArrayOrganize($value);
                    $result = $datas->dataFilter($where);
                    if ($result === true) {
                        if (!empty($order) && isset($order["on"]) && is_string($order["on"])
                        && isset($order["order"]) && is_string($order["order"])) {
                            $result = $datas->dataSort($order["on"], $order["order"]);
                            if ($result === true) {
                                return $datas->getData();
                            } else {
                                return false;
                            }
                        } else {
                            return $datas->getData();
                        }
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                throw new \Exception("Unable build: ".$this->path.$filename.".yml"."
                is not be accessible reading");
            }
        } else {
            return false;
        }
    }

    /**
     * @param string $dataName name of data file
     * @param array $update update param
     * @param array $where where param
     * @return array|bool return array or false for error
     */
    public function update(string $dataName, array $update, array $where = [])
    {
        $filename = strtolower($dataName);
        if (file_exists($this->path.$filename.".yml")) {
            if (is_readable($this->path.$filename.".yml") && is_writable($this->path.$filename.".yml")) {
                $value = Yaml::parseFile($this->path.$filename.".yml");
                if ($value != null && is_array($value)) {
                    $datas = new ArrayOrganize($value);
                    $result = $datas->dataFilter($where);
                    if ($result == true) {
                        $value2 = $datas->getData();
                        foreach ($value as $k1 => $v1) {
                            foreach ($value2 as $v2) {
                                if ($v1 === $v2) {
                                    foreach ($v1 as $k => $v) {
                                        if (array_key_exists($k, $update)) {
                                            $value[$k1][$k] = $update[$k];
                                        }
                                    }
                                }
                            }
                        }
                        $yaml = Yaml::dump($value);
                        file_put_contents($this->path.$filename.".yml", $yaml);
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                throw new \Exception("Unable build: ".$this->path.$filename.".yml"."
                is not be accessible reading and/or writing");
            }
        } else {
            return false;
        }
    }

    /**
     * @param string $dataName name of data file
     * @param array $insert insert param
     * @return bool return array or false for error
     */
    public function insert(string $dataName, array $insert)
    {
        $filename = strtolower($dataName);
        if (file_exists($this->path.$filename.".yml")) {
            if (is_readable($this->path.$filename.".yml") && is_writable($this->path.$filename.".yml")) {
                $value = Yaml::parseFile($this->path.$filename.".yml");
                if ($value != null && is_array($value)) {
                    $value[count($value)] = $insert;
                    file_put_contents($this->path.$filename.".yml", Yaml::dump($value));
                    return true;
                } else {
                    return false;
                }
            } else {
                throw new \Exception("Unable build: ".$this->path.$filename.".yml"."
                is not be accessible reading and/or writing");
            }
        } else {
            if (is_writable($this->path)) {
                file_put_contents($this->path.$filename.".yml", Yaml::dump($insert));
                return true;
            } else {
                throw new \Exception("Unable build: ".$this->path."
                is not be accessible writing");
            }
        }
    }

    /**
     * @param string $dataName name of data file
     * @param array $where where param
     * @return bool return array or false for error
     */
    public function delete(string $dataName, array $where)
    {
        $filename = strtolower($dataName);
        if (file_exists($this->path.$filename.".yml")) {
            if (is_readable($this->path.$filename.".yml") && is_writable($this->path.$filename.".yml")) {
                $value = Yaml::parseFile($this->path.$filename.".yml");
                if ($value != null && is_array($value)) {
                    $datas = new ArrayOrganize($value);
                    $result = $datas->dataFilter($where);
                    if ($result == true) {
                        $value2 = $datas->getData();
                        foreach ($value as $k1 => $v1) {
                            foreach ($value2 as $v2) {
                                if ($v1 === $v2 && isset($value[$k1])) {
                                    unset($value[$k1]);
                                }
                            }
                        }
                        $yaml = Yaml::dump($value);
                        file_put_contents($this->path.$filename.".yml", $yaml);
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                throw new \Exception("Unable build: ".$this->path.$filename.".yml"."
                is not be accessible reading and/or writing");
            }
        } else {
            throw new \Exception("Unable build: ".$this->path.$filename.".yml"." does not exist");
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

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
     * @var mixed|null $chmod chmod code for permissions
     */
    private $chmod = null;

    /**
     * @var string|null $user user unix for permissions
     */
    private $chown = null;

    /**
     * @var string|null $groupe group unix for permissions
     */
    private $chgrp = null;

    /**
     * @param array $settings Settings
     */
    public function __construct(array $settings = [])
    {
        if (!empty($settings)) {
            if (isset($settings["path"])) {
                if (file_exists($settings["path"])) {
                    if (is_dir($settings["path"])) {
                        if (is_readable($settings["path"])) {
                            if (substr($settings["path"], -1) == "/") {
                                $this->path = $settings["path"];
                            } else {
                                $this->path = $settings["path"]."/";
                            }
                        } else {
                            throw new \Exception("Unable build:
                            Path setting of data must be accessible reading");
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
                if (is_int($settings["chmod"])) {
                    $this->chmod = $settings["chmod"];
                } else {
                    throw new \Exception("Unable build: Chmod setting is not validate");
                }
            }
            if (isset($settings["chown"])) {
                if (is_string($settings["chown"])) {
                    $this->chown = $settings["chown"];
                } else {
                    throw new \Exception("Unable build: Chown setting is not validate");
                }
            }
            if (isset($settings["chgrp"])) {
                if (is_string($settings["chgrp"])) {
                    $this->chgrp = $settings["chgrp"];
                } else {
                    throw new \Exception("Unable build: Chown setting is not validate");
                }
            }
        } else {
            throw new \Exception("Unable build: Argument $settings must not be empty");
        }
    }

    /**
     * @param string $dataName name of data file
     * @return array|bool returns array or false if error
     */
    public function getData(string $dataName)
    {
        $filename = strtolower($dataName);
        if (file_exists($this->path.$filename.".yml")) {
            if (is_readable($this->path.$filename.".yml")) {
                $value = Yaml::parseFile($this->path.$filename.".yml");
                if ($value === null) {
                    return [];
                } elseif (is_array($value)) {
                    return $value;
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
     * @param array $data data to set in the file
     * @return bool returns true if set or exception if error
     */
    public function setData(string $dataName, array $data)
    {
        $filename = strtolower($dataName);
        if (file_exists($this->path.$filename.".yml")) {
            if (is_writable($this->path.$filename.".yml")) {
                file_put_contents($this->path.$filename.".yml", Yaml::dump($data));
                return true;
            } else {
                throw new \Exception("Unable build: ".$this->path.$filename.".yml"."
                is not be accessible writing");
            }
        } else {
            if (is_writable($this->path)) {
                file_put_contents($this->path.$filename.".yml", Yaml::dump($data));
                if ($this->setPermissions($this->path.$filename.".yml")) {
                    return true;
                } else {
                    return false;
                }
            } else {
                throw new \Exception("Unable build: ".$this->path." is not be accessible writing");
            }
        }
    }

    /**
     * @param string $dataName name of data file
     * @param array $where filter param
     * @param array $order order param
     * @return array|bool returns array if selected or false if error
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
     * @return bool returns true if updated or false if error
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
     * @return bool returns true if inserted or false if error
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
                if ($this->setPermissions($this->path.$filename.".yml")) {
                    return true;
                } else {
                    return false;
                }
            } else {
                throw new \Exception("Unable build: ".$this->path."
                is not be accessible writing");
            }
        }
    }

    /**
     * @param string $dataName name of data file
     * @param array $where where param
     * @return bool returns true if deleted or false if error
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
     * @param array $datas the tables for the merger
     * @return array return merged of tables or false for error
     */
    public function fusion(array $datas)
    {
        $merged = [];
        if (!empty($datas)) {
            foreach ($datas as $k1 => $v1) {
                if (is_array($datas)) {
                    foreach ($v1 as $k2 => $v2) {
                        if (!in_array($v2, $merged)) {
                            $merged[] = $v2;
                        }
                    }
                } else {
                    throw new \Exception("Unable build: Bad data array format");
                }
            }
            return $merged;
        } else {
            return [];
        }
    }

    /**
     * @param array $datas the tables base
     * @param array $datasToRemove the tables for fission
     * @return array return fission of tables
     */
    public function fission(array $datas, array $datasToRemove)
    {
        if (!empty($datas)) {
            if (!empty($datasToRemove)) {
                foreach ($datasToRemove as $k1 => $v1) {
                    foreach ($datas as $k2 => $v2) {
                        if ($v2 == $v1) {
                            unset($datas[$k2]);
                        }
                    }
                }
                return $datas;
            } else {
                return $datas;
            }
        } else {
            return [];
        }
    }

    /**
     * @param string $dataName name of data file to remove
     * @return bool returns true if deleted or false if file not found
     */
    public function remove(string $dataName)
    {
        $filename = strtolower($dataName);
        if (file_exists($this->path.$filename.".yml")) {
            if (is_writable($this->path.$filename.".yml")) {
                unlink($this->path.$filename.".yml");
                return true;
            } else {
                throw new \Exception("Unable build: ".$this->path.$filename.".yml"."
                is not be accessible writing");
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

    /**
     * @return int|null Chmod code
     */
    public function getChmod()
    {
        return $this->chmod;
    }

    /**
     * @return string|null Chown user unix
     */
    public function getChown()
    {
        return $this->chown;
    }

    /**
     * @return string|null Chgrp group unix
     */
    public function getChgrp()
    {
        return $this->chgrp;
    }

    /**
     * @param string $path Path of file or folder
     * @return bool Set permission
     */
    private function setPermissions(string $path)
    {
        if (file_exists($path)) {
            if ($this->chmod != null) {
                if (!chmod($path, $this->chmod)) {
                    return false;
                }
            }
            if ($this->chown != null) {
                if (!chown($path, $this->chown)) {
                    return false;
                }
            }
            if ($this->chgrp != null) {
                if (!chgrp($path, $this->chgrp)) {
                    return false;
                }
            }
            return true;
        } else {
            return false;
        }
    }
}

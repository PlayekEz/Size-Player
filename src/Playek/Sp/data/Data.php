<?php

namespace Playek\Sp\data;

use Playek\Sp\Size;
use pocketmine\Player;
use pocketmine\utils\Config;

class Data
{

    private $data = [];
    private $main;

    public function __construct(Size $main)
    {
        $this->main = $main;
    }

    public function getConfig():Config
    {
        return new Config($this->main->getDataFolder() . "data.yml", Config::YAML);
    }

    public function load():void
    {
        $config = $this->getConfig();
        foreach ($config->getAll() as $key => $value)
        {
            $this->data[$key] = $value;
        }
    }

    public function save():void {
        $config = $this->getConfig();
        foreach ($this->data as $key => $value){
            $config->set($key, $value);
        }
        $config->save();
    }

    public function getScale(String $name){
        return (isset($this->data[$name])) ? ((isset($this->data[$name]["scale"])) ? $this->data[$name]["scale"] : null) : null;
    }

    public function setScale(String $name, float $scale):bool {
        if(isset($this>data[$name])){
            $this->data[$name]["scale"] = $scale;
            return true;
        }
        return false;
    }

    public function getPermission(String $name){
        return (isset($this->data[$name])) ? ((isset($this->data[$name]["permission"])) ? $this->data[$name]["permission"] : "default") : "default";
    }

    public function setPermission(String $name, string $permission = "default"):bool {
        if(isset($this>data[$name])){
            $this->data[$name]["permission"] = $permission;
            return true;
        }
        return false;
    }

    public function create(String $name, float $scale, string $permission = "default"):bool {
        if(!isset($this->data[$name])){
            return false;
        }
        $this->data[$name] = [
            "scale" => $scale,
            "permission" => $permission
        ];
        return true;
    }

    public function exists(String $size){
        return isset($this->data[$size]);
    }
}
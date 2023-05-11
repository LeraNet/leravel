<?php

function get_input(){
    $input = trim(fgets(STDIN));
    return $input;
}

class textColor {
    private $foreground_colors = array(
        "black" => "30",
        "red" => "31",
        "green" => "32",
        "yellow" => "33",
        "blue" => "34",
        "magenta" => "35",
        "cyan" => "36",
        "light gray" => "37",
        "dark gray" => "90",
        "light red" => "91",
        "light green" => "92",
        "light yellow" => "93",
        "light blue" => "94",
        "light magenta" => "95",
        "light cyan" => "96",
        "white" => "97",
    );

    private $background_colors = array(
        "black" => "40",
        "red" => "41",
        "green" => "42",
        "yellow" => "43",
        "blue" => "44",
        "magenta" => "45",
        "cyan" => "46",
        "light gray" => "47",
        "dark gray" => "100",
        "light red" => "101",
        "light green" => "102",
        "light yellow" => "103",
        "light blue" => "104",
        "light magenta" => "105",
        "light cyan" => "106",
        "white" => "107"
    );

    private $sets = array(
        "bold" => "1",
        "dim" => "2",
        "underlined" => "4",
        "blink" => "5",
        "reverse" => "7",
        "hidden" => "8"
    );

    private $color;
    private $background;
    private $set;

    public function __construct($color = null, $background = null, $set = null){
        $this->color = $color;
        $this->background = $background;
        $this->set = $set;
    }

    public function getColoredString($string){
        $colored_string = "";

        if(isset($this->foreground_colors[$this->color])){
            $colored_string .= "\033[" . $this->foreground_colors[$this->color] . "m";
        }

        if(isset($this->background_colors[$this->background])){
            $colored_string .= "\033[" . $this->background_colors[$this->background] . "m";
        }

        if(isset($this->sets[$this->set])){
            $colored_string .= "\033[" . $this->sets[$this->set] . "m";
        }

        $colored_string .= $string . "\033[0m";

        return $colored_string;
    }
}
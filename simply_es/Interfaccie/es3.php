<?php
    interface Volante {
        public function volare();
    }

    class Aereo implements Volante {
        public function volare() {
            return "L' Aereo sta volando";
        }
    }

    $Aereo = new Aereo();
    echo $Aereo->volare();
?>
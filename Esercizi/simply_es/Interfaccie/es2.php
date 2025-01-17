<?php
    interface Suonabile {
        public function Suona();
    }

    class Chitarra implements Suonabile {
        public function Suona() {
            return "La chitarra sta suonando";
        }
    }

    $chitarra = new Chitarra();
    echo $chitarra->Suona();
?>
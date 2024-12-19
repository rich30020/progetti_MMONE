<?php
    abstract class Animali {
        abstract public function emettiSuono();
    }

        class Gatto extends Animali {
            public function emettiSuono() {
                return "Miao!";
            }
        }
    
    $Gatto = new Gatto();
    echo "Come fa il gatto?</br>" . $Gatto->emettiSuono();
?>

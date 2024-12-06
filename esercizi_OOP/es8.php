<?php
// Scrivere una classe PHP chiamata 'BankAccount' con proprietà come 'accountNumber' e 'balance'. Implementare metodi per depositare e prelevare denaro dal conto.

// Classe BankAccount 
class BankAccount {
    public $accountNumber;
    public $balance;

    //Si costruisce la funzione con il numero account e la disponibilit
    function __construct($accountNumber, $balance) {
        $this->accountNumber = $accountNumber;
        $this->balance = $balance; 
    }


    public function deposit($amount) {
        $this->balance += $amount;
        echo "Hai depositato $amount € nell'account. </br><hr>
        Il nuovo credito è di: $this->balance €</br><hr>";
    }

    public function withdraw($amount) {
        if ($this->balance >= $amount) {
            $this->balance -= $amount;
            echo "Hai prelevato $amount € dall'account<hr>Saldo Rimanente: $this->balance €</br><hr>";
        } else {
            echo "Saldo insufficiente nell'account $this->accountNumber.Saldo attuale: $this->balance</br><hr>";
        }
    }
}

$account = new BankAccount("41641416", 10550);
echo "Numero del Conto: " . $account->accountNumber . "</br><hr>";
echo "Saldo iniziale: " . $account->balance?> €</br><hr>
<?php

$account->deposit(30000);
$account->withdraw(550);
$account->withdraw(40000);
?>

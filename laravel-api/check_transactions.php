<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

$db = $app->make('Illuminate\Database\DatabaseManager');
$transactions = $db->table('transactions')->select('id', 'transaction_type', 'status', 'amount')->limit(15)->get();

echo "Total Transactions: " . $db->table('transactions')->count() . "\n";
echo "Sample Transactions:\n";
foreach ($transactions as $t) {
    echo "ID: {$t->id}, Type: {$t->transaction_type}, Status: {$t->status}, Amount: {$t->amount}\n";
}

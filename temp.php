<?php 

$userArr = array (
  0 => 'Yves',
  1 => 'Nadège',
  2 => 'Odile',
  3 => 'Marcel',
  4 => 'Abraham',
  5 => 'Lydie',
  6 => 'Edouard',
  7 => 'Nestor',
  8 => 'Juste',
  9 => 'Justin',
  10 => 'Florent',
  11 => 'Agathe',
  12 => 'Marc',
  13 => 'Natacha',
  14 => 'Judith',
  15 => 'Aude',
  16 => 'Michel',
  17 => 'Bernard',
  18 => 'Colette',
  19 => 'Richard',
  20 => 'Carine',
  21 => 'PAQUES',
  22 => 'Gaston',
  23 => 'Casimir',
  24 => 'Reine',
  25 => 'Nicolas',
  26 => 'Gaël',
  27 => 'Kévin',
  28 => 'Adeline',
  29 => 'AUTOMNE',
  30 => 'Alexis',
  31 => 'Norbert',
  32 => 'Olive',
  33 => 'Elisée',
  34 => 'Rameaux',
  35 => 'Diane',
  36 => 'Fiacre',
  37 => 'Denis',
  38 => 'Gilbert',
  39 => 'Gisèle',
  40 => 'Albert',
  41 => 'Renaud',
  42 => 'Edmond',
  43 => 'Roger',
  44 => 'Paul',
  45 => 'Firmin',
  46 => 'Fernand',
  47 => 'René',
  48 => 'Raïssa',
  49 => 'Thomas',
  50 => 'Cécile',
  51 => 'Hermann',
  52 => 'Amour',
  53 => 'Crépin',
  54 => 'Anselme',
  55 => 'Aymar',
  56 => 'Géraud',
  57 => 'Marina',
  58 => 'Benoît',
  59 => 'Gilles',
  60 => 'Elodie',
  61 => 'Parfait',
  62 => 'Daniel',
  63 => 'Fleur',
  64 => 'Samson',
  65 => 'Etienne',
  66 => 'Jean',
  67 => 'Arsène',
  68 => 'Thècle',
  69 => 'Thierry',
  70 => 'Emilie',
  71 => 'Gatien',
  72 => 'Fulbert',
  73 => 'Gontran',
  74 => 'Léa',
  75 => 'Charles',
  76 => 'Odilon',
  77 => 'Raoul',
  78 => 'Abel',
  79 => 'Adelphe',
  80 => 'Claude',
  81 => 'Marius',
  82 => 'Olivier',
  83 => 'Alix',
  84 => 'Roméo',
  85 => 'André',
  86 => 'Gérard',
  87 => 'Igor',
  88 => 'Armand',
  89 => 'Edwige',
  90 => 'Cyrille',
  91 => 'Julie',
  92 => 'Tanguy',
  93 => 'Victor',
  94 => 'Barnard',
  95 => 'Félix',
  96 => 'Romuald',
  97 => 'Armel',
  98 => 'Ella',
  99 => 'Solange',
);
exit();
require('./wp-load.php');

global $wpdb;

$uname = 'testdb';
$uemail = 'test@moteur-graphique.com';

foreach ($userArr as $uname) {

	$wpdb->insert( 'wp_users', array( 'user_login' => $uname, 'user_nicename' => $uname, 'user_email' => $uemail, 'display_name' => $uname )) ;

}
?>
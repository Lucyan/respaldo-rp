<?php
require('config.php');
include('twitter.php');


$twitter = new Twitter($key, $secret);

$bots = mysql_query("SELECT * FROM bots");
$log = '';

while ($bot = mysql_fetch_assoc($bots)) {
    $twitter->setOAuthToken($bot['tw_token']);
    $twitter->setOAuthTokenSecret($bot['tw_secret']);

    $query = "SELECT * FROM tweets WHERE fecha <  '" . date('Y-m-d', strtotime('-2 days')) . " 00:00:00' AND bot_id = '" . $bot['id'] . "' AND estado <> 2;";

    $qry = mysql_query($query);

    while($row = mysql_fetch_assoc($qry)) {
        mysql_query("UPDATE tweets SET estado = 0 WHERE id = {$row['id']}");
        
        try {
            if ($twitter->friendshipsExists($row['usuario_id'], $bot[tw_usuario])) {
                mysql_query("UPDATE tweets SET estado = 2 WHERE id = '{$row['id']}'");
                $qry_cont = mysql_query("SELECT * FROM bots WHERE id = '{$bot['id']}'");
                while ($bot_cont = mysql_fetch_assoc($qry_cont)) {
                    $contador = $bot_cont['seguidores'];
                }
                $contador++;
                mysql_query("UPDATE bots SET seguidores = '$contador' WHERE id = '{$bot['id']}'");
            } else {

                if ($twitter->friendshipsExists($bot[tw_usuario], $row['usuario_id'])) {
                    //mysql_query("UPDATE twitter SET estado = 1 WHERE id = {$row['id']}");
                    try {
                        $twitter->friendshipsDestroy($row['usuario_id']);
                        mysql_query("UPDATE tweets SET estado = 2 WHERE id = {$row['id']}");
                        $qry_cont = mysql_query("SELECT * FROM bots WHERE id = '{$bot['id']}'");
                        while ($bot_cont = mysql_fetch_assoc($qry_cont)) {
                            $contador = $bot_cont['siguiendo'];
                        }
                        $contador--;
                        mysql_query("UPDATE bots SET siguiendo = '$contador' WHERE id = '{$bot['id']}'");
                        //mysql_query("DELETE FROM twitter WHERE id = {$row['id']}");
                    } catch (Exception $e) {
                        echo $e;
                    }

                } else {
                    mysql_query("DELETE FROM tweets WHERE id = {$row['id']}");
                }
            }
        } catch (Exception $e) {
            if ($e->getMessage() == 'You do not have permission to retrieve following status for both specified users.') {
                mysql_query("UPDATE tweets SET estado = 2 WHERE id = {$row['id']}");
                //mysql_query("DELETE FROM twitter WHERE id = {$row['id']}");
            } else {
                exit();
            }
        }
    }
}
?>
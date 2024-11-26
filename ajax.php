<?php 
    $answer = 'Halo. Ada yang bisa saya bantu?';

    if(isset($_GET['say']))
    {
        $say = rawurlencode($_GET['say']);
        $json = file_get_contents("http://localhost/BabuVirtual/chatbot/conversation_start.php?bot_id=1&say=".$say."");
        $json_data = json_decode($json); 
        $answer = $json_data->botsay; 
    }
	echo $answer;
?>
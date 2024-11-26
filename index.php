<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>BabuVirtual - Chatbot</title>
    <meta name="description" content="Vanika">
    <script type="text/javascript" src="./libgif.js"></script>
    <script src="https://code.responsivevoice.org/responsivevoice.js?key=gRzHOHli"></script>
    <style>
        * {
    -webkit-tap-highlight-color: transparent;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f0f2f5;
    color: #333;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh;
    margin: 10px;
    background: radial-gradient(circle, rgba(52,152,219,0.1) 0%, rgba(52,152,219,0.05) 100%); /* Gradasi latar belakang */
}


.container {
    width: 100%;
    max-width: 360px;
    padding: 20px;
    background-color: #ffffff;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1), 0 0 20px 2px #3498db; /* Efek neon biru */
    border-radius: 8px;
    text-align: center;
    position: relative;
    z-index: 1; /* Meningkatkan posisi stacking kontainer */
}

h1 {
    color: #2c3e50;
    margin-bottom: 20px;
    font-size: 24px;
}

input[type="text"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: 2px solid #2c3e50;
    border-radius: 8px;
    font-size: 16px;
    transition: border-color 0.3s;
}

input[type="text"]:focus {
    border-color: #3498db;
    outline: none;
}

.button, #bStart {
    width: 100%;
    padding: 10px;
    background-color: #3498db;
    border: none;
    border-radius: 8px;
    color: white;
    font-weight: bold;
    cursor: pointer;
    margin-bottom: 10px;
    transition: background-color 0.3s, box-shadow 0.3s;
}
.button:hover, #bStart:hover {
    background-color: #2980b9;
    box-shadow: 0 0 15px #3498db; /* Menambahkan glow saat hover */
}

#bStart {
    background-color: #e74c3c;
}

#bStart:hover {
    background-color: #c0392b;
}

.superGifCanvas {
    max-width: 100%;
    border-radius: 8px;
}

.img-container {
    width: 200px; /* Lebar total + padding + border */
    height: 348px; /* Tinggi total + padding + border */
    display: flex; /* Mengaktifkan Flexbox */
    justify-content: center; /* Pusatkan konten secara horizontal */
    align-items: center; /* Pusatkan konten secara vertikal */
    overflow: hidden;
    position: relative;
    margin-top: 20px;
    padding: 12px;
    background: #f8f9fa;
    border-radius: 12px;
    border: 2px solid #3498db;
    margin: auto; /* Menambahkan ini untuk pusatkan container di dalam body jika perlu */
}

#loadingstat {
    color: #c0392b;
    font-size: 14px;
    height: 20px;  /* Ensure the text doesn't jump when content changes */
}

    </style>
</head>
<body>

<div class="container">
    <h1>Chatbot BabuVirtual</h1>
    
    <label for="say" style="font-weight: bold;">Tuliskan pertanyaan Anda:</label>

    <form name="ask" onsubmit="return false">
        <input type="text" name="say" id="say" autofocus />
        <input type="button" value="Kirim" class="button" onclick="animasi(this.form)" />
        <input id="bStart" type="button" value="Suara" onclick="start(this.form);" />
    </form>

    <div id="ttsoptions" style="display: none;">
        <div>
            <input type="hidden" id="texttospeakinput" value="<?php echo $answer; ?>" />
            <div id="texterrormessage" class="guierrormessage"></div>
        </div>
        <div>
            <input type="hidden" id="gifurlinput" value="Snapchat.gif" />
            <div id="giferrormessage" class="guierrormessage"></div>
        </div>
    </div>

    <div id="instruction"></div>

    <div id="imagecontainer" class="img-container">
        <img id="exampleimg" src="Snapchat.gif" rel:animated_src="Snapchat.gif" rel:auto_play="0" />
    </div>

    <div id="loadingstat"></div>
</div>

<script type="text/javascript" src="./example.js"></script>
<script type="text/javascript" src="ajax.js"></script>
<script type="text/javascript">
    var sup1 = new SuperGif({ gif: document.getElementById('exampleimg') });
    sup1.load();

    function animasi(form) {
        answer(form.say.value);
    }

    var recognition = new webkitSpeechRecognition();
    recognition.continuous = false;
    recognition.lang = 'id-ID';

    function start(form) {
        recognition.onstart = function () {
            form.say.value = "";
            document.getElementById("loadingstat").innerHTML = "<span>Listening to your voice...</span>";
        };
        recognition.onresult = function (event) {
            form.say.value = "";
            for (var i = 0; i < event.results.length; i++) {
                form.say.value += event.results[i][0].transcript;
            }
        };
        recognition.onend = function () {
            if (form.say.value !== "") answer(form.say.value);
            document.getElementById("loadingstat").innerHTML = "";
        };
        recognition.start();
    }

    function answer(question) {
    // Pastikan GIF animasi dan suara di-reset sebelum melakukan hal lain
    if (sup1.get_playing()) {
        sup1.pause();  // Pause animasi jika masih berjalan
    }

    // Reset responsiveVoice sebelum mulai berbicara lagi
    responsiveVoice.cancel();

    var xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var responseText = this.responseText;
            document.getElementById('texttospeakinput').value = responseText;

            // Estimasi durasi untuk sinkronisasi suara dan animasi GIF
            var estimatedDuration = responseText.length * 100; // 100ms per karakter

            // Mulai pemutaran suara
            responsiveVoice.speak(responseText, "Indonesian Male", {
                onstart: function() {
                    // Mulai animasi saat suara dimulai
                    sup1.play_for_duration(estimatedDuration);
                },
                onend: function() {
                    // Hentikan animasi dan reset ke frame awal saat suara selesai
                    sup1.pause();
                    sup1.move_to(0);  // Reset animasi ke frame pertama setelah selesai
                }
            });
        }
    };
    xmlHttp.open("GET", "ajax.php?say=" + encodeURIComponent(question), true);
    xmlHttp.send();
}

</script>

</body>
</html>

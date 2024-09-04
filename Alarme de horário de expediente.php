<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alarme de Horários</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
        }
        #alarm-light {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: red;
            opacity: 0.1;
            transition: opacity 0.5s;
        }
        .light-on {
            opacity: 1;
            animation: blink 1s infinite;
        }
        @keyframes blink {
            0% { opacity: 1; }
            50% { opacity: 0.1; }
            100% { opacity: 1; }
        }
    </style>
</head>
<body>
    <div id="alarm-light"></div>
    <audio id="alarm-sound" src="i-feel-good.mp3" preload="auto"></audio>
    <script>
        const alarmLight = document.getElementById('alarm-light');
        const alarmSound = document.getElementById('alarm-sound');

        function checkAlarm() {
            const now = new Date();
            const hours = now.getHours();
            const minutes = now.getMinutes();

            const alarmTimes = [
                { hour: 8, minute: 0 },
                { hour: 12, minute: 0 },
                { hour: 14, minute: 05 },
                { hour: 18, minute: 0 }
            ];

            if (alarmTimes.some(alarm => alarm.hour === hours && alarm.minute === minutes)) {
                triggerAlarm();
            }
        }

        function triggerAlarm() {
            alarmLight.classList.add('light-on');
            alarmSound.play().catch(error => console.error('Erro ao tocar o áudio:', error));
            setTimeout(stopAlarm, 60000); // Para o alarme após 1 minuto
        }

        function stopAlarm() {
            alarmLight.classList.remove('light-on');
            alarmSound.pause();
            alarmSound.currentTime = 0;
        }

        setInterval(checkAlarm, 60000); // Verifica a cada minuto
    </script>
</body>
</html>

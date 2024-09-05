<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alarme de Horários</title>
    <style>
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
        }
        #alarm-light {
            width: 100%;
            height: 150px;
            border-radius: 0%;
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
        #stop-button, #test-button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #ff0000;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        #stop-button {
            display: none;
        }
        #alarm-times {
            margin-top: 20px;
        }
        .time-input {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div id="alarm-light"></div>
    <button id="stop-button">Parar Alarme</button>
    <button id="test-button">Testar Alarme</button>
    <audio id="alarm-sound" src="i-feel-good.mp3" preload="auto"></audio>

    <div id="alarm-times">
        <h3>Horários Programados:</h3>
        <div class="time-input">
            <label for="time1">Alarme 1:</label>
            <input type="time" id="time1" value="08:00">
        </div>
        <div class="time-input">
            <label for="time2">Alarme 2:</label>
            <input type="time" id="time2" value="12:00">
        </div>
        <div class="time-input">
            <label for="time3">Alarme 3:</label>
            <input type="time" id="time3" value="13:30">
        </div>
        <div class="time-input">
            <label for="time4">Alarme 4:</label>
            <input type="time" id="time4" value="18:00">
        </div>
        <button id="save-times">Salvar Horários</button>
    </div>

    <script>
        const alarmLight = document.getElementById('alarm-light');
        const alarmSound = document.getElementById('alarm-sound');
        const stopButton = document.getElementById('stop-button');
        const testButton = document.getElementById('test-button');
        const saveTimesButton = document.getElementById('save-times');
        let checkAlarmInterval;

        // Horários padrão para o alarme
        let alarmTimes = [
            { hour: 8, minute: 0 },
            { hour: 12, minute: 0 },
            { hour: 13, minute: 30 },
            { hour: 18, minute: 0 }
        ];

        // Função que verifica se o horário atual corresponde a algum horário de alarme
        function checkAlarm() {
            const now = new Date();
            const hours = now.getHours();
            const minutes = now.getMinutes();

            if (alarmTimes.some(alarm => alarm.hour === hours && alarm.minute === minutes)) {
                triggerAlarm();
            }
        }

        // Função que dispara o alarme
        function triggerAlarm() {
            alarmLight.classList.add('light-on');
            alarmSound.play().catch(error => console.error('Erro ao tocar o áudio:', error));
            stopButton.style.display = 'block';

            // Traz a janela para frente se estiver em segundo plano
            if (document.hidden) {
                window.focus();
                alert("Alarme disparado!");
            }

            setTimeout(stopAlarm, 60000); // Para o alarme após 1 minuto
        }

        // Função que para o alarme e pausa a verificação por 60 segundos
        function stopAlarm() {
            alarmLight.classList.remove('light-on');
            alarmSound.pause();
            alarmSound.currentTime = 0;
            stopButton.style.display = 'none';

            // Pausa a verificação por 60 segundos
            clearInterval(checkAlarmInterval);
            setTimeout(() => {
                startAlarmCheck();
            }, 60000); // Retoma a verificação após 60 segundos
        }

        // Função para salvar os horários alterados
        function saveAlarmTimes() {
            const time1 = document.getElementById('time1').value.split(':');
            const time2 = document.getElementById('time2').value.split(':');
            const time3 = document.getElementById('time3').value.split(':');
            const time4 = document.getElementById('time4').value.split(':');

            alarmTimes = [
                { hour: parseInt(time1[0]), minute: parseInt(time1[1]) },
                { hour: parseInt(time2[0]), minute: parseInt(time2[1]) },
                { hour: parseInt(time3[0]), minute: parseInt(time3[1]) },
                { hour: parseInt(time4[0]), minute: parseInt(time4[1]) }
            ];

            alert("Horários salvos com sucesso!");
        }

        // Inicia a verificação dos alarmes
        function startAlarmCheck() {
            checkAlarmInterval = setInterval(checkAlarm, 10000); // Verifica a cada 10 segundos
        }

        // Adiciona evento ao botão de parada do alarme
        stopButton.addEventListener('click', stopAlarm);

        // Adiciona evento ao botão de teste do alarme
        testButton.addEventListener('click', triggerAlarm);

        // Adiciona evento ao botão de salvar horários
        saveTimesButton.addEventListener('click', saveAlarmTimes);

        // Inicia a verificação dos alarmes ao carregar a página
        startAlarmCheck();
    </script>
</body>
</html>

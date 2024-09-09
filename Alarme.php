<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alarme de Horários</title>
    <style>
        :root {
            --invert-value: 0%; /* Valor padrão da inversão */
            --background-color: #fff; /* Cor de fundo padrão */
            --text-color: #000; /* Cor do texto padrão */
        }

        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: var(--background-color);
            color: var(--text-color);
            transition: background-color 0.3s, color 0.3s, filter 0.3s;
            filter: invert(var(--invert-value));
            position: relative;
        }

        #alarm-light {
            width: 100%;
            height: 150px;
            background-color: red;
            opacity: 0.1;
            transition: opacity 0.5s;
        }

        #head {
            width: 90%;
            text-align: center;
            padding: 10px;
        }

        #logo {
            max-width: 100%;
            height: auto;
        }

        .light-on {
            opacity: 1;
            animation: blink 1s infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.1; }
        }

        button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #ff0000;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button.toggle-mode {
            background-color: #000;
            color: #fff;
            position: absolute;
            top: 10px;
            right: 10px;
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

        .slider-container {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div id="head">
        <a href="http://www.alfatek.com.br"><img id="logo" src="logo.png" alt="Logo"></a>
    </div>
    <button class="toggle-mode" id="toggle-mode">Alternar Modo</button>
    <div id="clock">
        <input type="text" id="datetime" readonly style="border: none; font-size: 8vw; text-align: center;">
    </div>
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

    <div class="slider-container">
        <label for="invert-slider">Mudança de Tonalidade</label>
        <input type="range" id="invert-slider" min="0" max="100" value="0" step="1">
    </div>

    <script>
        const alarmLight = document.getElementById('alarm-light');
        const alarmSound = document.getElementById('alarm-sound');
        const stopButton = document.getElementById('stop-button');
        const testButton = document.getElementById('test-button');
        const saveTimesButton = document.getElementById('save-times');
        const toggleModeButton = document.getElementById('toggle-mode');
        const invertSlider = document.getElementById('invert-slider');
        const body = document.body;

        let alarmTimes = [
            { hour: 8, minute: 0 },
            { hour: 12, minute: 0 },
            { hour: 13, minute: 30 },
            { hour: 18, minute: 0 }
        ];
        let checkAlarmInterval;

        function updateDateTime() {
            const now = new Date();
            document.getElementById("datetime").value = now.toLocaleString('pt-BR', {
                weekday: 'short', year: 'numeric', month: 'numeric', day: 'numeric',
                hour: 'numeric', minute: 'numeric', second: 'numeric'
            });
        }

        function checkAlarm() {
            const now = new Date();
            if (alarmTimes.some(alarm => alarm.hour === now.getHours() && alarm.minute === now.getMinutes())) {
                triggerAlarm();
            }
        }

        function triggerAlarm() {
            alarmLight.classList.add('light-on');
            alarmSound.play();
            stopButton.style.display = 'block';
            if (document.hidden) window.focus();
            setTimeout(stopAlarm, 60000); // Para o alarme após 1 minuto
        }

        function stopAlarm() {
            alarmLight.classList.remove('light-on');
            alarmSound.pause();
            alarmSound.currentTime = 0;
            stopButton.style.display = 'none';
            clearInterval(checkAlarmInterval);
            setTimeout(startAlarmCheck, 60000); // Pausa de 60s
        }

        function saveAlarmTimes() {
            alarmTimes = Array.from({ length: 4 }, (_, i) => {
                const [hour, minute] = document.getElementById(`time${i + 1}`).value.split(':');
                return { hour: parseInt(hour), minute: parseInt(minute) };
            });
            alert("Horários salvos com sucesso!");
        }

        function startAlarmCheck() {
            checkAlarmInterval = setInterval(checkAlarm, 10000); // Verifica a cada 10 segundos
        }

        toggleModeButton.addEventListener('click', () => {
            const currentValue = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--invert-value'));
            const newValue = currentValue === 0 ? 100 : 0; // Alterna entre 0% e 100%
            document.documentElement.style.setProperty('--invert-value', `${newValue}%`);
            document.documentElement.style.setProperty('--background-color', newValue === 100 ? '#000' : '#fff');
            document.documentElement.style.setProperty('--text-color', newValue === 100 ? '#fff' : '#000');
            invertSlider.value = newValue; // Atualiza o slider para refletir o valor atual
        });

        invertSlider.addEventListener('input', (event) => {
            const value = event.target.value;
            document.documentElement.style.setProperty('--invert-value', `${value}%`);
            document.documentElement.style.setProperty('--background-color', value === '100' ? '#000' : '#fff');
            document.documentElement.style.setProperty('--text-color', value === '100' ? '#fff' : '#000');
        });

        stopButton.addEventListener('click', stopAlarm);
        testButton.addEventListener('click', triggerAlarm);
        saveTimesButton.addEventListener('click', saveAlarmTimes);
        setInterval(updateDateTime, 1000);
        startAlarmCheck();
    </script>
</body>
</html>

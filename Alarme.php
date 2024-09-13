<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alarme de Horários</title>
    <style>
        :root {
            --background-color: #000;
            --text-color: #fff;
            --clock-bg-color: #222;
            --button-bg-color: #ff0000;
            --button-text-color: #fff;
        }

        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 90vh;
            margin: 0;
            background-color: var(--background-color);
            color: var(--text-color);
            transition: background-color 0.3s, color 0.3s;
            position: relative;
            overflow: hidden;
        }

        #head {
            width: 90%;
            text-align: center;
            padding: 10px;
        }

        #logo {
            max-width: 100%;
            height: auto;
            transition: filter 0.3s;
        }

        button {
            margin-top: 5px;
            padding: 8px 16px;
            background-color: var(--button-bg-color);
            color: var(--button-text-color);
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 150px;
            font-size: 16px;
            transition: background-color 0.3s, color 0.3s;
        }

        #stop-button {
            display: none;
        }

        #alarm-times {
            margin-top: 10px;
            color: var(--text-color);
        }

        .time-input {
            margin-bottom: 5px;
        }

        #buttons-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h3, label {
            color: var(--text-color);
            transition: color 0.3s;
        }

        #clock {
            background-color: var(--clock-bg-color);
            transition: background-color 0.5s;
            padding: 10px;
            border-radius: 10px;
            font-size: 5vw;
            text-align: center;
            width: 100%;
            max-width: 800px;
            color: var(--text-color);
        }

        input#datetime {
            border: none;
            background: none;
            color: inherit;
            font-size: inherit;
            text-align: center;
            width: 100%;
            box-sizing: border-box;
        }

        @keyframes blink {
            0%, 100% { background-color: var(--clock-bg-color); }
            50% { background-color: red; }
        }
    </style>
</head>
<body>
    <div id="head">
        <a href="http://www.alfatek.com.br"><img id="logo" src="logo.png" alt="Logo"></a>
    </div>

    <div id="clock">
        <input type="text" id="datetime" readonly>
    </div>

    <div id="buttons-container">
        <button id="stop-button">Parar Alarme</button>
        <button id="test-button">Testar Alarme</button>
        <button id="toggle-mode">Modo Claro</button>

        <audio id="alarm-sound" src="i-feel-good.mp3" preload="auto"></audio>

        <div id="alarm-times">
            <h3>Horários Programados:</h3>
            <div class="time-input">
                <label for="time1">Alarme 1:</label>
                <input type="time" id="time1">
            </div>
            <div class="time-input">
                <label for="time2">Alarme 2:</label>
                <input type="time" id="time2">
            </div>
            <div class="time-input">
                <label for="time3">Alarme 3:</label>
                <input type="time" id="time3">
            </div>
            <div class="time-input">
                <label for="time4">Alarme 4:</label>
                <input type="time" id="time4">
            </div>
            <button id="save-times">Salvar Horários</button>
        </div>
    </div>

    <script>
        const alarmSound = document.getElementById('alarm-sound');
        const stopButton = document.getElementById('stop-button');
        const testButton = document.getElementById('test-button');
        const saveTimesButton = document.getElementById('save-times');
        const toggleModeButton = document.getElementById('toggle-mode');
        const clock = document.getElementById('clock');
        const logo = document.getElementById('logo');

        let isDarkMode = true;
        let alarmTimes = loadAlarmTimes() || [
            { hour: 8, minute: 0 },
            { hour: 12, minute: 0 },
            { hour: 13, minute: 30 },
            { hour: 18, minute: 0 }
        ];
        let alarmPaused = false;

        function loadAlarmTimes() {
            const savedTimes = localStorage.getItem('alarmTimes');
            return savedTimes ? JSON.parse(savedTimes) : null;
        }

        function saveAlarmTimes() {
            alarmTimes = Array.from({ length: 4 }, (_, i) => {
                const [hour, minute] = document.getElementById(`time${i + 1}`).value.split(':');
                return { hour: parseInt(hour), minute: parseInt(minute) };
            });
            localStorage.setItem('alarmTimes', JSON.stringify(alarmTimes));
            alert("Horários salvos com sucesso!");
        }

        function populateAlarmTimes() {
            alarmTimes.forEach((alarm, i) => {
                document.getElementById(`time${i + 1}`).value = `${String(alarm.hour).padStart(2, '0')}:${String(alarm.minute).padStart(2, '0')}`;
            });
        }

        function updateDateTime() {
            const now = new Date();
            document.getElementById("datetime").value = now.toLocaleString('pt-BR', {
                weekday: 'short', year: 'numeric', month: 'numeric', day: 'numeric',
                hour: 'numeric', minute: 'numeric', second: 'numeric'
            });
        }

        function checkAlarm() {
            if (!alarmPaused) {
                const now = new Date();
                if (alarmTimes.some(alarm => alarm.hour === now.getHours() && alarm.minute === now.getMinutes())) {
                    triggerAlarm();
                }
            }
        }

        function triggerAlarm() {
            alarmSound.play();
            stopButton.style.display = 'block';
            clock.style.animation = 'blink 1s infinite';
            if (document.hidden) window.focus();
            setTimeout(stopAlarm, 60000);
        }

        function stopAlarm() {
            alarmSound.pause();
            alarmSound.currentTime = 0;
            stopButton.style.display = 'none';
            clock.style.animation = '';
            alarmPaused = true; // Pausar verificação por 60 segundos
            setTimeout(() => { alarmPaused = false; }, 60000); // Retomar verificação após 60 segundos
        }

        function startAlarmCheck() {
            setInterval(checkAlarm, 10000); // Verifica a cada 10 segundos
        }

        function updateMode() {
            document.documentElement.style.setProperty('--background-color', isDarkMode ? '#000' : '#fff');
            document.documentElement.style.setProperty('--text-color', isDarkMode ? '#fff' : '#000');
            document.documentElement.style.setProperty('--clock-bg-color', isDarkMode ? '#222' : '#fff');
            document.documentElement.style.setProperty('--button-bg-color', isDarkMode ? '#ff0000' : '#3b5998');
            document.documentElement.style.setProperty('--button-text-color', isDarkMode ? '#fff' : '#fff');
            toggleModeButton.textContent = isDarkMode ? 'Modo Claro' : 'Modo Escuro';

            const invertValue = isDarkMode ? 'invert(100%)' : 'invert(0%)';
            logo.style.filter = invertValue;
        }

        toggleModeButton.addEventListener('click', () => {
            isDarkMode = !isDarkMode;
            updateMode();
        });

        updateMode();
        populateAlarmTimes();

        stopButton.addEventListener('click', stopAlarm);
        testButton.addEventListener('click', triggerAlarm);
        saveTimesButton.addEventListener('click', saveAlarmTimes);

        setInterval(updateDateTime, 1000); // Atualiza a cada 1 segundo
        startAlarmCheck();
    </script>
</body>
</html>

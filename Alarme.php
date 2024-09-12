<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alarme de Horários</title>
    <style>
        :root {
            --background-color: #000; /* Cor de fundo para o modo escuro */
            --text-color: #fff; /* Cor do texto para o modo escuro */
            --clock-bg-color: #222; /* Cor de fundo do relógio para o modo escuro */
        }

        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 90vh; /* Ajusta a altura da página */
            margin: 0;
            background-color: var(--background-color);
            color: var(--text-color);
            transition: background-color 0.3s, color 0.3s;
            position: relative;
            overflow: hidden; /* Remove a barra de rolagem */
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
            background-color: #ff0000;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 150px;
            font-size: 16px;
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
            font-size: 5vw; /* Tamanho da fonte do relógio */
            text-align: center;
            width: 100%;
            max-width: 800px; /* Define uma largura máxima para o relógio */
            color: var(--text-color); /* Cor do texto do relógio */
        }

        input#datetime {
            border: none;
            background: none;
            color: inherit;
            font-size: inherit;
            text-align: center;
            width: 100%;
            box-sizing: border-box; /* Garante que o padding não afete a largura */
        }

        @keyframes blink {
            0%, 100% { background-color: var(--clock-bg-color); }
            50% { background-color: red; } /* Cor piscante durante o alarme */
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
        <button id="toggle-mode">Modo Claro</button> <!-- Botão de Alternar Modo aqui -->

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
    </div>

    <script>
        const alarmSound = document.getElementById('alarm-sound');
        const stopButton = document.getElementById('stop-button');
        const testButton = document.getElementById('test-button');
        const saveTimesButton = document.getElementById('save-times');
        const toggleModeButton = document.getElementById('toggle-mode');
        const clock = document.getElementById('clock');
        const logo = document.getElementById('logo');

        let isDarkMode = true; // Define o modo escuro como padrão inicial

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
            alarmSound.play();
            stopButton.style.display = 'block';
            clock.style.animation = 'blink 1s infinite'; // Animação de piscar para o relógio
            if (document.hidden) window.focus();
            setTimeout(stopAlarm, 60000); // Para o alarme após 1 minuto
        }

        function stopAlarm() {
            alarmSound.pause();
            alarmSound.currentTime = 0;
            stopButton.style.display = 'none';
            clock.style.animation = ''; // Remove a animação piscante
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

        function updateMode() {
            document.documentElement.style.setProperty('--background-color', isDarkMode ? '#000' : '#fff');
            document.documentElement.style.setProperty('--text-color', isDarkMode ? '#fff' : '#000');
            document.documentElement.style.setProperty('--clock-bg-color', isDarkMode ? '#222' : '#fff');
            toggleModeButton.textContent = isDarkMode ? 'Modo Claro' : 'Modo Escuro';

            // Aplica ou remove a inversão nos elementos específicos
            const invertValue = isDarkMode ? 'invert(100%)' : 'invert(0%)';
            logo.style.filter = invertValue;
        }

        toggleModeButton.addEventListener('click', () => {
            isDarkMode = !isDarkMode;
            updateMode();
        });

        // Configura o modo escuro ao carregar a página
        updateMode();

        stopButton.addEventListener('click', stopAlarm);
        testButton.addEventListener('click', triggerAlarm);
        saveTimesButton.addEventListener('click', saveAlarmTimes);
        setInterval(updateDateTime, 1000);
        startAlarmCheck();
    </script>
</body>
</html>


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

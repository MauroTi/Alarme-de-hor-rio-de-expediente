<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alarme de Horários</title>

    <link rel="stylesheet" href="styles.css">

    <!-- Script principal do alarme -->
    <script src="scripts.js" defer></script>
</head>

<body>

    <!-- FUNDO ANIMADO -->
    <canvas id="water"></canvas>

    <div id="head">
        <a href="http://www.alfatek.com.br">
            <img id="logo" src="logo.png" alt="Logo Alfatek">
        </a>
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

    <!-- Script do fundo animado -->
    <script src="background.js"></script>

</body>
</html>

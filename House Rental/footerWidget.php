<?php
    $kathmanduTimeZone = new DateTimeZone('Asia/Kathmandu');
    $kathmanduNow = new DateTimeImmutable('now', $kathmanduTimeZone);

    $nepalTime = $kathmanduNow->format('g:i A');
    $nepalDate = $kathmanduNow->format('F j, Y');

    $weatherCondition = 'Partly Cloudy';
    $temperature = 22;
    $temperatureUnit = '°C';

    $weatherCodes = [
        0 => 'Clear sky',
        1 => 'Mostly clear',
        2 => 'Partly cloudy',
        3 => 'Overcast',
        45 => 'Foggy',
        48 => 'Depositing rime fog',
        51 => 'Light drizzle',
        53 => 'Moderate drizzle',
        55 => 'Dense drizzle',
        56 => 'Light freezing drizzle',
        57 => 'Dense freezing drizzle',
        61 => 'Slight rain',
        63 => 'Moderate rain',
        65 => 'Heavy rain',
        66 => 'Light freezing rain',
        67 => 'Heavy freezing rain',
        71 => 'Slight snow',
        73 => 'Moderate snow',
        75 => 'Heavy snow',
        77 => 'Snow grains',
        80 => 'Rain showers',
        81 => 'Heavy rain showers',
        82 => 'Violent rain showers',
        85 => 'Snow showers',
        86 => 'Heavy snow showers',
        95 => 'Thunderstorm',
        96 => 'Thunderstorm with hail',
        99 => 'Severe thunderstorm'
    ];

    $weatherUrl = 'https://api.open-meteo.com/v1/forecast?latitude=27.7172&longitude=85.3240&current=temperature_2m,weather_code&timezone=auto';
    $weatherData = @file_get_contents($weatherUrl);

    if ($weatherData !== false) {
        $weatherJson = json_decode($weatherData, true);

        if (isset($weatherJson['current']['temperature_2m'], $weatherJson['current']['weather_code'])) {
            $temperature = round((float) $weatherJson['current']['temperature_2m']);
            $weatherCondition = $weatherCodes[$weatherJson['current']['weather_code']] ?? 'Partly cloudy';
        }
    }
?>

<footer>
    <div class="footerContainer">
        <div class="footerRow">
            <div class="footerSide">
                <strong>Nepal</strong>
                <span><?php echo htmlspecialchars($nepalTime, ENT_QUOTES, 'UTF-8'); ?>, <?php echo htmlspecialchars($nepalDate, ENT_QUOTES, 'UTF-8'); ?></span>
                <span>Kathmandu</span>
            </div>
            <div class="footerSide">
                <span><?php echo htmlspecialchars($weatherCondition, ENT_QUOTES, 'UTF-8'); ?></span>
                <span class="weatherTemp"><?php echo htmlspecialchars((string) $temperature, ENT_QUOTES, 'UTF-8'); ?><?php echo htmlspecialchars($temperatureUnit, ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
        </div>
    </div>

    <p class="copyRight">2026 House Rental Property Ltd. | All Rights Reserved</p>
</footer>
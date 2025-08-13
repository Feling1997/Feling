<?php

// Mensaje del usuario
$prompt = '¿Qué es la inteligencia artificial?';

// Crea la solicitud POST a la API de Ollama
$data = [
    'model' => 'llama3',
    'messages' => [
        ['role' => 'user', 'content' => $prompt]
    ]
];

$ch = curl_init('http://localhost:11434/api/chat');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);
curl_close($ch);

// Decodifica y muestra la respuesta
$result = json_decode($response, true);
echo "Respuesta del modelo:\n" . $result['message']['content'] . "\n";

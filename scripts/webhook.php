<?php

// Настройки
$url_server = "https://api.coinlore.net/api/tickers/?limit=5"; // URL-адрес сервера с JSON-данными
$url_receiver = "https://localhost/api/v1/coins"; // URL-адрес получателя JSON-данных
$minute = 60; // Интервал обновления в секундах
$update_interval = 10;
// Функция для отправки запроса и получения JSON-данных
function get_json($url) {
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  $response = curl_exec($ch);
  curl_close($ch);
  return json_decode($response, true);
}

sleep($update_interval);
// Цикл для периодического выполнения
while (true) {
  // Получение JSON-данных с сервера
  $data = get_json($url_server);

  // Отправка JSON-данных получателю
  $ch = curl_init($url_receiver);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  $response = curl_exec($ch);
  curl_close($ch);

  // Ожидание интервала обновления
  sleep($update_interval);
}

?>
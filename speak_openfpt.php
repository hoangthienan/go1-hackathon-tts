<?php

error_reporting(E_ALL);

define('BASE_DIR', dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('UPLOAD_DIR', BASE_DIR . 'uploads' . DIRECTORY_SEPARATOR);

require(BASE_DIR . 'vendor/autoload.php');

$config = require(BASE_DIR . 'config.php');

use Go1\Fpt\Text2Speech;

$params = [
    'api_key' => 'd8e77e6a0a0f4a6fb43d1bc908b4aa00',
    'text'    => 'Ý tưởng

Tính năng chuyển văn bản thành giọng nói tự động biến nội dung học tập dựa trên văn bản của bạn thành nội dung tương tác và hấp dẫn mà người học sẽ yêu thích.

Tính năng này sử dụng các kỹ thuật máy học thông minh nhân tạo tiên tiến để lấy nội dung văn bản trong các Khoá học Go1 và biến nó thành âm thanh gần hoàn hảo của nội dung của bạn.

Sau đó, người học có thể chọn cách họ muốn học - họ có thể đọc văn bản hoặc nghe nội dung đang được đọc cho họ, cung cấp cho họ nhiều tùy chọn hơn về cách họ tìm hiểu.

Rõ ràng đội ngũ của chúng tôi có thể nói cho bạn biết tất cả về tính năng này - nhưng chúng tôi rất tự tin vào những gì chúng tôi đã xây dựng ở đây mà chúng tôi nghĩ chúng tôi sẽ giới thiệu toàn bộ bản trình diễn trực tiếp. Vì vậy, hãy ngồi lại và tìm hiểu thông qua tính năng chuyển văn bản thành giọng nói của Go1 làm thế nào ý tưởng này hoạt động và những lợi ích.',

    'voice' => 'female',
];

try {
    $openFpt = new Text2Speech($config['openFptOptions']['apikey']);
    $tts = $openFpt->post('', $params);

    echo json_encode(['text' => $params['text'], 's3_url' => $tts['async']], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . PHP_EOL;
} catch (\Exception $e) {
    print_r($e);
}

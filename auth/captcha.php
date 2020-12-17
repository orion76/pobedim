<?php
$dir0 = substr($_SERVER['SCRIPT_FILENAME'],0, strpos($_SERVER['SCRIPT_FILENAME'], 'main')+4);
require_once $dir0 . '/ut.php';

if (isUserLoggedOn()) exit();


define('CAPTCHA_COOKIE', 'imgcaptcha_');
mt_srand(time());

define('PATH_TTF',  '/css/font/');
$fonts = array('HeliosCondLight.ttf', 'MyriadPro-Regular.ttf', 'MyriadPro-Semibold.ttf');

 $par = array(
	// ширина капчи
	'WIDTH' => 180,
	// высота капчи
	'HEIGHT' => 52,
	// размер шрифта на капче
	'FONT_SIZE' => 18,
 	// кол-во символов на капче
	'CHARS_COUNT' => 5,
	// разрешенные символы капчи
	'ALLOWED_CHARS' => '0123456789', //'ABCDEFGHJKLMNPQRSTUVWXYZ23458',
 
	// фоновый цвет капчи - белый в нашем случае
	'BG_COLOR' => '#FFFFFF',
	// кол-во линий на капче
	'LINES_COUNT' => 3,
	// толщина линий
	'LINES_THICKNESS' => 2
);
// цвета символов
define('CODE_CHAR_COLORS', '#880000,#008800,#000088,#888800,#880088,#008888,#000000');
// цвета линий
define('CODE_LINE_COLORS', '#880000,#008800,#000088,#888800,#880088,#008888,#000000');

$line_colors = preg_split('/,\s*?/', CODE_LINE_COLORS);
$char_colors = preg_split('/,\s*?/', CODE_CHAR_COLORS);

$r = gd_info();
$img = imagecreatetruecolor($par['WIDTH'], $par['HEIGHT']);
imagefilledrectangle($img, 0, 0, $par['WIDTH'] - 1, $par['HEIGHT'] - 1, gd_color($par['BG_COLOR']));
 
// устанавливаем толщину линий и выводим их на капчу
imagesetthickness($img, $par['LINES_THICKNESS']);
 
for ($i = 0; $i < $par['LINES_COUNT']; $i++)
    imageline($img,
        mt_rand(0, $par['WIDTH'] - 1),
        mt_rand(0, $par['HEIGHT'] - 1),
        mt_rand(0, $par['WIDTH'] - 1),
        mt_rand(0, $par['HEIGHT'] - 1),
        gd_color($line_colors[mt_rand(0, count($line_colors) - 1)])
    );
 
// Переменная для хранения кода капчи
$code = '';
 
// Зададим координату по центру оси Y 
$y = ($par['HEIGHT'] / 2) + ($par['FONT_SIZE'] / 2);
 
// Выводим символы на капче
for ($i = 0; $i < $par['CHARS_COUNT']; $i++) {
		// выбираем случайный цвет из доступного набора
    $color = gd_color($char_colors[mt_rand(0, count($char_colors) - 1)]);
    // определяем случайный угол наклона символа от -45 до 45 градусов
    $angle = mt_rand(-30, 30);
    // выбираем случайный символ из доступного набора
    $char = substr($par['ALLOWED_CHARS'], mt_rand(0, strlen($par['ALLOWED_CHARS']) - 1), 1);
    // выбираем случайный шрифт из доступного набора
    $font = dirname(__DIR__) . PATH_TTF . $fonts[mt_rand(0, count($fonts) - 1)];
    // вычислим координату текущего символа по оси X
    $x = (intval(($par['WIDTH'] / $par['CHARS_COUNT']) * $i) + ($par['FONT_SIZE'] / 2));
 
    // выводим символ на капчу
    imagettftext($img, $par['FONT_SIZE'], $angle, $x, $y, $color, $font, $char);
 
    // сохраняем код капчи
    $code .= $char;
}
 
// сохраним капчу в куках для дальнейшей проверки
setcookie(CAPTCHA_COOKIE, md5($code));

$_SESSION['captcha']=$code;

$r = new db(DB_USER,'insert into w0_captcha (captcha) values (:v)',[':v'=>$code]);

/*
 
	Посылаем сформированный рисунок в браузер и избавляемся от него, 
	хотя сборщик мусора обычно это делает за нас
 
*/


header("Content-Type: image/png");
imagepng($img);
imagedestroy($img);
 
// Преобразуем HTML 6-символьный цвет в GD цвет 
function gd_color($html_color)
{
  return preg_match('/^#?([\dA-F]{6})$/i', $html_color, $rgb)
    ? hexdec($rgb[1]) : false;
}
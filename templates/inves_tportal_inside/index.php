<?php 
defined('_JEXEC') or die;
 ?>
<!DOCTYPE html>
<html lang="ru">
<head>
<jdoc:include type="head" />
<meta charset="UTF-8">
<link href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/css/template.css"  rel="stylesheet">
<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,300,600,700|Cuprum&subset=latin,cyrillic,cyrillic-ext' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
</head>
<body>
<script>
var urlDoc = window.location.pathname;
var proekty = new RegExp("/index.php/proekty");
var ploshchadki = new RegExp("/index.php/ploshchadki");
var proektySerch = new RegExp("/index.php/component/jak2filter/");
if (urlDoc.search(proekty) != -1 || urlDoc.search(ploshchadki)  != -1 || urlDoc.search(proektySerch)  != -1) {
document.body.innerHTML += '<link href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/html/com_k2/templates/projects/k2.css"  rel="stylesheet">';
}
</script>
<!----Обёртка--><section id="wrapper"><!--Обёртка---->
<!--Шапка из логотипа и меню-->
<header>
<section id="fixed_header" class="">
<!--Логотип-->
<jdoc:include type="modules" name="logo" />
<!--Логотип-->
<!--Меню-->
<div id="mainmenu"><jdoc:include type="modules" name="menu" /></div>
<!--Меню-->
</section>
</header>
<!--Шапка из логотипа и меню-->
<div style="width:100%;height:98px;"></div>
<main id="contentCom">
<div id="maincontent"><jdoc:include type="component" /></div>
<aside id="modulesposition1" style=""><jdoc:include type="modules" name="mod1" /></aside>
<aside id="modulesposition2" style=""><jdoc:include type="modules" name="mod2" /></aside>
</main> 

<!-------------------------------------- ФУТЕР ТУТ ВСЕ ПОНЯТНО -------------------------------------->
  <footer id="futer" class="">
  <!----<span id="pater"></span>---->
  <div class="ppppp"><h1 class="h1_cept" style="width:100%;margin-left:0;min-height: 48px;background-color: #9A3680;font-family: ArsenalRegular;color: #FFFFFF;text-transform: uppercase;
  font-size: 26px;text-align: center;line-height: 34px;font-weight: 300;padding-top: 14px;box-shadow: 0 4px 5px -2px rgba(153,153,153,0.8);"></h1></div>
    <article id="fromfoot" class="wrap_content">
      <aside id="ulfoot" class="foot1">
      <h1>О портале</h1>
      <p>Данный ресурс позволяет посетителю получить актуальную консолидированную информацию об инвестиционной привлекательности Забайкальского края.<br><br>Здесь сосредоточена информация об отраслях экономики, приоритетах в направлениях инвестиционной деятельности, об условиях ведения бизнеса в нашем регионе.<br><br>Мы постарались сделать все возможное, чтобы вам было понятно и комфортно, находясь на страницах Портала.</p>
      </aside>
      
      <aside id="ulfoot" class="foot2">
      <h1>Разделы сайта</h1>
      <ul>
        <li class="mapfoot">Инвестиционные стандарты АСИ</li>
        <li class="mapfoot">Инвестиционная карта</li>
        <li class="mapfoot">Промышленные парки</li>
        <li class="mapfoot">Проекты</li>
        <li class="mapfoot">Площадки</li>
        <li class="mapfoot">Организации</li>
        <li class="mapfoot">Навигатор инвестора</li>
        <li class="mapfoot">Градостроительный паспорт</li>
        <li class="mapfoot">Документы</li>
        <li class="mapfoot">Путеводитель инвестора</li>
        <li class="mapfoot">Сельскохозяйственный потенциал</li>
        <li class="mapfoot">Карта сайта</li>
      </ul>
      </aside>
      
      <aside id="ulfoot" class="foot3">
      <h1>Подписка на рассылку</h1>
      <jdoc:include type="modules" name="mail-responder" />
<!------ РАЗДЕЛИТЕЛЬ ------><div class="clearBoth"></div><!------ РАЗДЕЛИТЕЛЬ ------>
      <ul id="socialcollbut">
        <li id="socibut" class="socibut1" title="Поделиться | Вконтакте"></li>
        <li id="socibut" class="socibut2" title="Поделиться | Одноклассники"></li>
        <li id="socibut" class="socibut3" title="Поделиться | Mail.ru"></li>
        <li id="socibut" class="socibut4" title="Поделиться | Яндекс"></li>
        <li id="socibut" class="socibut5" title="Поделиться | Google+"></li>
        <li id="socibut" class="socibut6" title="Поделиться | Twitter"></li>
        <li id="socibut" class="socibut7" title="Поделиться | RSS лентой"></li>
        <li id="socibut" class="socibut8" title="Поделиться | На почту другу"></li>
      </ul>
      </aside>      
    </article>
    <section id="froobottom"></section>
  </footer>
<!--ФУТЕР ТУТ ВСЕ ПОНЯТНО-->

<!-------------------------------------- Обёртка--></section><!--Обёртка-->
<div id="scroller"></div>
<script src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/js/scroll_menu.js"></script>
<script src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/js/jquery.scrollspeed.js"></script>
<script src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/js/scripts.js"></script>
</body>
</html>
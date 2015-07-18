<?php 
defined('_JEXEC') or die;
 ?>
<!DOCTYPE html>
<html lang="ru">
<head>
<jdoc:include type="head" />
<meta charset="UTF-8">
<link href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/css/template.css"  rel="stylesheet">
</head>
<body>
<script>
var urlDoc = window.location.pathname;
var proekty = new RegExp("/index.php/ru/proekty");
var ploshchadki = new RegExp("/index.php/ru/ploshchadki");
var proektySerch = new RegExp("/index.php/ru/component/jak2filter");
var singl_stats = new RegExp("/index.php/ru/stati");
var sobitiyaAndInterviu = new RegExp("/index.php/ru/sobytiya-i-intervyu");
var gallery = new RegExp("/index.php/ru/gallery");
var standartsASI = new RegExp("/index.php/ru/investitsionnye-standarty-asi");
var muzteatr = new RegExp("/index.php/ru/muzykalnyj-teatr-imeni-lundstrema-v-chite");
var lukodrom = new RegExp("/index.php/ru/krytyj-lukodrom-v-chite");
var prompark = new RegExp("/index.php/ru/promyshlennyj-park-gorod-krasnokamensk");
var aviacionniy = new RegExp("/index.php/ru/aviatsionnyj-kompleks-zabajkalskogo-kraya");
if (urlDoc.search(proekty) != -1 || urlDoc.search(ploshchadki)  != -1 || urlDoc.search(proektySerch)  != -1 || urlDoc.search(standartsASI)  != -1) {
document.body.innerHTML += '<link href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/html/com_k2/templates/projects/k2.css"  rel="stylesheet">';
}
else if (urlDoc.search(singl_stats) != -1 || urlDoc.search(sobitiyaAndInterviu) != -1) {
document.body.innerHTML += '<link href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/html/com_k2/templates/singl-stats/k2.css"  rel="stylesheet">';
}
else if (urlDoc.search(gallery) != -1 || urlDoc.search(muzteatr) != -1 || urlDoc.search(lukodrom) != -1 || urlDoc.search(prompark) != -1 || urlDoc.search(aviacionniy) != -1) {
document.body.innerHTML += '<link href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/html/com_k2/templates/singl-stats/k2.css"  rel="stylesheet">';
}
////////////////////////////////////////////////////////////////////////////////////////////
var cb_profile = new RegExp("/index.php/ru/cb-profile");
var cb_manage_connections = new RegExp("/index.php/ru/cb-manage-connections");
var cb_forgot_login = new RegExp("/index.php/ru/cb-forgot-login");
var cb_moderate_user_approvals = new RegExp("/index.php/ru/cb-moderate-user-approvals");
var cb_moderate_reports = new RegExp("/index.php/ru/cb-moderate-reports");
var cb_moderate_images = new RegExp("/index.php/ru/cb-moderate-images");
var cb_moderate_bans = new RegExp("/index.php/ru/cb-moderate-bans");
var cb_manage_connections = new RegExp("/index.php/ru/cb-manage-connections");
var cb_userlist = new RegExp("/index.php/ru/cb-userlist");
var cb_logout = new RegExp("/index.php/ru/cb-logout");
var cb_login = new RegExp("/index.php/ru/cb-login");
var cb_registration = new RegExp("/index.php/ru/cb-registration");
var cb_profile_edit = new RegExp("/index.php/ru/cb-profile-edit");
var cb_profile = new RegExp("/index.php/ru/cb-profile");
if (urlDoc.search(cb_profile) != -1 || urlDoc.search(cb_manage_connections) != -1 || urlDoc.search(cb_forgot_login) != -1 || urlDoc.search(cb_moderate_user_approvals) != -1 || urlDoc.search(cb_moderate_reports) != -1 || urlDoc.search(cb_moderate_images) != -1 || urlDoc.search(cb_moderate_bans) != -1 || urlDoc.search(cb_manage_connections) != -1 || urlDoc.search(cb_userlist) != -1 || urlDoc.search(cb_logout) != -1 || urlDoc.search(cb_login) != -1 || urlDoc.search(cb_registration) != -1 || urlDoc.search(cb_profile_edit) != -1 || urlDoc.search(cb_profile) != -1) {
document.body.innerHTML += '<link href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/css/cbprof.css"  rel="stylesheet">';
}
</script>
<!----Обёртка--><section id="wrapper"><!--Обёртка---->
<!--Шапка из логотипа и меню-->
<header>
<section id="fixed_header" class="">
<jdoc:include type="modules" name="lang" />
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
<?php
$urlDoc = $_SERVER['REQUEST_URI'];
if (preg_match('/\/index.php\/ru\/gallery/', $urlDoc) == 0) {print_r('<section id="maincontent"><jdoc:include type="component" /></section>');}
else {print_r('<section id="maincontent"><h1 id="galH">Галерея</h1><article id="modGallery" class="modGall"><jdoc:include type="modules" name="modgallery" /></article><article id="modGallery2" class="modGall"><jdoc:include type="modules" name="modgallery2" /></article></section>');}
?>
<!---->
<aside id="modulesposition1" style=""><jdoc:include type="modules" name="mod1" /></aside>
<aside id="modulesposition2" style=""><jdoc:include type="modules" name="mod2" /></aside>
</main> 

<!------ РАЗДЕЛИТЕЛЬ ------><div class="clearBoth"></div><!------ РАЗДЕЛИТЕЛЬ ------>
<!-------------------------------------- ФУТЕР ТУТ ВСЕ ПОНЯТНО -------------------------------------->

  <footer id="futer" class="">
  <!----<span id="pater"></span>---->
  <div class="ppppp"><h1 class="h1_cept" style="width:100%;margin-left:0;min-height:0;background-color: #302A25;font-family: ArsenalRegular;color: #FFFFFF;text-transform: uppercase;
  font-size: 26px;text-align: center;line-height: 34px;font-weight: 300;padding-top: 14px;border-bottom:48px solid transparent;box-shadow: 0 4px 5px -2px rgba(153,153,153,0.8);"></h1></div>
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
<link rel="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/css/font-awesome.min.css">
<script src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/js/scroll_menu.js"></script>
<script src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/js/scripts.js"></script>
</body>
</html>
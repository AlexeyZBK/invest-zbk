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
var parki = new RegExp("/promyshlennye-parki");
var proekty = new RegExp("/proekty");
var ploshchadki = new RegExp("/ploshchadki");
var organizatsii = new RegExp("/organizatsii");
var svobobj = new RegExp("/svobodnye-ob-ekty-nedvizhimosti");
var sonaasas= new RegExp("/sozdavaemye-investitsionnye-ob-ekty");
var proektySerch = new RegExp("/component/jak2filter");
var investpredlozheniya = /investitsionnye-predlozheniya$/gm;
var singl_stats = new RegExp("/stati");
var sobitiyaAndInterviu = new RegExp("/sobytiya-i-intervyu");
var gallery = new RegExp("/gallery");
var standartsASI = new RegExp("/investitsionnye-standarty-asi");
var doki = /dokumenty$/gm;
var dokifeder = /federalnyj-uroven/;
var dokiregeon = /regionalnyj-uroven/;
var muzteatr = new RegExp("/muzykalnyj-teatr-imeni-lundstrema-v-chite");
var lukodrom = new RegExp("/krytyj-lukodrom-v-chite");
var prompark = new RegExp("/promyshlennyj-park-gorod-krasnokamensk");
var aviacionniy = new RegExp("/aviatsionnyj-kompleks-zabajkalskogo-kraya");
var asi1 = new RegExp("/utverzhdenie-vysshimi-organami-gosudarstvennoj-vlasti-sub-ekta-rossijskoj-federatsii-investitsionnoj-strategii-regiona");
var asi2 = new RegExp("/formirovanie-i-ezhegodnoe-obnovlenie-plana-sozdaniya-investitsionnykh-ob-ektov-i-ob-ektov-infrastruktury-v-regione");
var asi3 = new RegExp("/3-ezhegodnoe-poslanie-vysshego-dolzhnostnogo-litsa-sub-ekta-rossijskoj-federatsii-investitsionnyj-klimat-i-investitsionnaya-politika-sub-ekta-rossijskoj-federatsii");
var asi4 = new RegExp("/4-prinyatie-normativnogo-pravovogo-akta-sub-ekta-rossijskoj-federatsii-o-zashchite-prav-investorov-i-mekhanizmakh-podderzhki-investitsionnoj-deyatelnosti");
var asi5 = new RegExp("/5-nalichie-soveta-po-uluchsheniyu-investitsionnogo-klimata");
var asi6 = new RegExp("/6-nalichie-spetsializirovannoj-organizatsii-po-privlecheniyu-investitsij");
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
if (urlDoc.search(investpredlozheniya)  != -1 || urlDoc.search(doki) != -1) {document.body.innerHTML += '<link href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/css/k2invest.css"  rel="stylesheet">';
}
else if (urlDoc.search(proekty) != -1 || urlDoc.search(sonaasas)  != -1 || urlDoc.search(organizatsii)  != -1 || urlDoc.search(parki)  != -1 || urlDoc.search(svobobj)  != -1  || urlDoc.search(ploshchadki)  != -1 || urlDoc.search(proektySerch)  != -1 || urlDoc.search(standartsASI)  != -1 || urlDoc.search(asi1) != -1 || urlDoc.search(asi2) != -1 || urlDoc.search(asi3) != -1 || urlDoc.search(asi4) != -1 || urlDoc.search(asi5) != -1 || urlDoc.search(asi6) != -1 || urlDoc.search(dokifeder) != -1 || urlDoc.search(dokiregeon) != -1) {
document.body.innerHTML += '<link href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/html/com_k2/templates/projects/k2.css"  rel="stylesheet">';
}
else if (urlDoc.search(singl_stats) != -1 || urlDoc.search(sobitiyaAndInterviu) != -1 || urlDoc.search(gallery) != -1 || urlDoc.search(muzteatr) != -1 || urlDoc.search(lukodrom) != -1 || urlDoc.search(prompark) != -1 || urlDoc.search(aviacionniy) != -1) {
document.body.innerHTML += '<link href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/html/com_k2/templates/singl-stats/k2.css"  rel="stylesheet">';
}
////////////////////////////////////////////////////////////////////////////////////////////
var maincab = new RegExp("/component/users");
var cb_profile = new RegExp("/cb-profile");
var cb_manage_connections = new RegExp("/cb-manage-connections");
var cb_forgot_login = new RegExp("/cb-forgot-login");
var cb_moderate_user_approvals = new RegExp("/cb-moderate-user-approvals");
var cb_moderate_reports = new RegExp("/cb-moderate-reports");
var cb_moderate_images = new RegExp("/cb-moderate-images");
var cb_moderate_bans = new RegExp("/cb-moderate-bans");
var cb_manage_connections = new RegExp("/cb-manage-connections");
var cb_userlist = new RegExp("/cb-userlist");
var cb_logout = new RegExp("/cb-logout");
var cb_login = new RegExp("/cb-login");
var cb_registration = new RegExp("/cb-registration");
var cb_profile_edit = new RegExp("/cb-profile-edit");
var cb_profile = new RegExp("/cb-profile");
if (urlDoc.search(cb_profile) != -1 || urlDoc.search(cb_manage_connections) != -1 || urlDoc.search(cb_forgot_login) != -1 || urlDoc.search(cb_moderate_user_approvals) != -1 || urlDoc.search(cb_moderate_reports) != -1 || urlDoc.search(cb_moderate_images) != -1 || urlDoc.search(cb_moderate_bans) != -1 || urlDoc.search(cb_manage_connections) != -1 || urlDoc.search(cb_userlist) != -1 || urlDoc.search(cb_logout) != -1 || urlDoc.search(cb_login) != -1 || urlDoc.search(cb_registration) != -1 || urlDoc.search(cb_profile_edit) != -1 || urlDoc.search(cb_profile) != -1 || urlDoc.search(maincab) != -1) {
document.body.innerHTML += '<link href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/css/cbprof.css"  rel="stylesheet">';
}
</script>
<!----Обёртка--><section id="wrapper"><!--Обёртка---->
<!--Шапка из логотипа и меню-->
<header>
<section id="fixed_header" class="">
<div style="position:absolute;top:26px;left:7px"><jdoc:include type="modules" name="lang" /></div>
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
if (preg_match('/\/gallery/', $urlDoc) == 0) {print_r('<section id="maincontent"><jdoc:include type="component" /></section>');}
else {
if (preg_match('/\/zh/', $urlDoc) == 1) {print_r('<section id="maincontent"><h1 id="galH">图库</h1><article id="modGallery" class="modGall"><jdoc:include type="modules" name="modgallery" /></article><article id="modGallery2" class="modGall"><jdoc:include type="modules" name="modgallery2" /></article></section>');}
elseif (preg_match('/\/zh/', $urlDoc) == 0) {print_r('<section id="maincontent"><h1 id="galH">Галерея</h1><article id="modGallery" class="modGall"><jdoc:include type="modules" name="modgallery" /></article><article id="modGallery2" class="modGall"><jdoc:include type="modules" name="modgallery2" /></article></section>');}
}
?>
<?php
$urlDoc = $_SERVER['REQUEST_URI'];
if (preg_match('/\/investitsionnye-predlozheniya$/', $urlDoc) == 0 || preg_match('/\/dokumenty$/', $urlDoc) == 0) {print_r('<aside id="modulesposition1" style=""><jdoc:include type="modules" name="mod1" /></aside><aside id="modulesposition2" style=""><jdoc:include type="modules" name="mod2" /></aside>');}
?>

</main> 

<!------ РАЗДЕЛИТЕЛЬ ------><div class="clearBoth"></div><!------ РАЗДЕЛИТЕЛЬ ------>
<!-------------------------------------- ФУТЕР ТУТ ВСЕ ПОНЯТНО -------------------------------------->

  <footer id="futer" class="">
  <!----<span id="pater"></span>---->
  <div class="ppppp"><h1 class="h1_cept" style="width:100%;margin-left:0;min-height:0;background-color: #302A25;font-family: ArsenalRegular;color: #FFFFFF;text-transform: uppercase;
  font-size: 26px;text-align: center;line-height: 34px;font-weight: 300;padding-top: 14px;border-bottom:48px solid transparent;box-shadow: 0 4px 5px -2px rgba(153,153,153,0.8);"></h1></div>
    <article id="fromfoot" class="wrap_content">
      <aside id="ulfoot" class="foot1">
<h1>
<?php
$urlDoc = $_SERVER['REQUEST_URI'];
if (preg_match('/\/zh/', $urlDoc) == 1) {print_r('关于传送门');}
elseif (preg_match('/\/zh/', $urlDoc) == 0) {print_r('О портале');}
?>
</h1>
<?php
$urlDoc = $_SERVER['REQUEST_URI'];
if (preg_match('/\/zh/', $urlDoc) == 1) {print_r('<p>这一资源允许的访问得到的最新综合信息有关的投资吸引力的外贝加尔领土。<br><br>它的重点是信息经济的优先方向的投资活动的条件做生意，在我们地区。<br><br>我们试图尽一切可能帮助你理解和适应的网页，该网站。</p>');}
elseif (preg_match('/\/zh/', $urlDoc) == 0 || preg_match('/\/en/', $urlDoc) == 0) {print_r('<p>Данный ресурс позволяет посетителю получить актуальную консолидированную информацию об инвестиционной привлекательности Забайкальского края.<br><br>Здесь сосредоточена информация об отраслях экономики, приоритетах в направлениях инвестиционной деятельности, об условиях ведения бизнеса в нашем регионе.<br><br>Мы постарались сделать все возможное, чтобы вам было понятно и комфортно, находясь на страницах Портала.</p>');}
?>
</aside>
<aside id="ulfoot" class="foot2">
<h1>
<?php
$urlDoc = $_SERVER['REQUEST_URI'];
if (preg_match('/\/zh/', $urlDoc) == 1) {print_r('网站地图');}
elseif (preg_match('/\/zh/', $urlDoc) == 0) {print_r('Разделы сайта');}
?>
</h1>
<ul>
<li class="mapfoot">
<?php
$urlDoc = $_SERVER['REQUEST_URI'];
if (preg_match('/\/zh/', $urlDoc) == 1) {print_r('战略倡议署的投资标准');}
elseif (preg_match('/\/zh/', $urlDoc) == 0) {print_r('Инвестиционные стандарты АСИ');}
?></li>
<li class="mapfoot">
<?php
$urlDoc = $_SERVER['REQUEST_URI'];
if (preg_match('/\/zh/', $urlDoc) == 1) {print_r('投资地图');}
elseif (preg_match('/\/zh/', $urlDoc) == 0) {print_r('Инвестиционная карта');}
?></li>
<li class="mapfoot">
<?php
$urlDoc = $_SERVER['REQUEST_URI'];
if (preg_match('/\/zh/', $urlDoc) == 1) {print_r('工业园区');}
elseif (preg_match('/\/zh/', $urlDoc) == 0) {print_r('Промышленные парки');}
?></li>
<li class="mapfoot">
<?php
$urlDoc = $_SERVER['REQUEST_URI'];
if (preg_match('/\/zh/', $urlDoc) == 1) {print_r('投资项目');}
elseif (preg_match('/\/zh/', $urlDoc) == 0) {print_r('Проекты');}
?></li>
<li class="mapfoot">
<?php
$urlDoc = $_SERVER['REQUEST_URI'];
if (preg_match('/\/zh/', $urlDoc) == 1) {print_r('投资平台');}
elseif (preg_match('/\/zh/', $urlDoc) == 0) {print_r('Площадки');}
?></li>
<li class="mapfoot">
<?php
$urlDoc = $_SERVER['REQUEST_URI'];
if (preg_match('/\/zh/', $urlDoc) == 1) {print_r('机构');}
elseif (preg_match('/\/zh/', $urlDoc) == 0) {print_r('Организации');}
?></li>
<li class="mapfoot">
<?php
$urlDoc = $_SERVER['REQUEST_URI'];
if (preg_match('/\/zh/', $urlDoc) == 1) {print_r('投资者导航');}
elseif (preg_match('/\/zh/', $urlDoc) == 0) {print_r('Навигатор инвестора');}
?></li>
<li class="mapfoot">
<?php
$urlDoc = $_SERVER['REQUEST_URI'];
if (preg_match('/\/zh/', $urlDoc) == 1) {print_r('城市建设地籍号');}
elseif (preg_match('/\/zh/', $urlDoc) == 0) {print_r('Градостроительный паспорт');}
?></li>
<li class="mapfoot">
<?php
$urlDoc = $_SERVER['REQUEST_URI'];
if (preg_match('/\/zh/', $urlDoc) == 1) {print_r('文件');}
elseif (preg_match('/\/zh/', $urlDoc) == 0) {print_r('Документы');}
?></li>
<li class="mapfoot">
<?php
$urlDoc = $_SERVER['REQUEST_URI'];
if (preg_match('/\/zh/', $urlDoc) == 1) {print_r('投资者指引');}
elseif (preg_match('/\/zh/', $urlDoc) == 0) {print_r('Путеводитель инвестора');}
?></li>
<li class="mapfoot">
<?php
$urlDoc = $_SERVER['REQUEST_URI'];
if (preg_match('/\/zh/', $urlDoc) == 1) {print_r('农业发展潜力');}
elseif (preg_match('/\/zh/', $urlDoc) == 0) {print_r('Сельскохозяйственный потенциал');}
?></li>
<li class="mapfoot">
<?php
$urlDoc = $_SERVER['REQUEST_URI'];
if (preg_match('/\/zh/', $urlDoc) == 1) {print_r('网站索引');}
elseif (preg_match('/\/zh/', $urlDoc) == 0) {print_r('Карта сайта');}
?></li>
</ul>
</aside>
<aside id="ulfoot" class="foot3">
<h1>
<?php
$urlDoc = $_SERVER['REQUEST_URI'];
if (preg_match('/\/zh/', $urlDoc) == 1) {print_r('通讯订阅');}
elseif (preg_match('/\/zh/', $urlDoc) == 0) {print_r('Подписка на рассылку');}
?></h1>
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
<span id="modalOrphus" style=""><jdoc:include type="modules" name="Orphus" /></span>
<script src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/js/scripts.js"></script>
</body>
</html>
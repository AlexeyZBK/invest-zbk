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
<main> 
<!--Slider-->
<section id="" class="main-slider-wrapper" style=""><jdoc:include type="modules" name="slider" /></section>
<!--Slider-->  
<!---- Обращение Губернатора и блоки 6 причин инвестировать ---->
<section id="first_block" class="">
<!--Обращение Губернатора-->
<div class=""><h1 class="h1_cept" name="obrashenie">Обращение Губернатора</h1></div><jdoc:include type="modules" name="obrashenie" />
<!--Обращение Губернатора-->
<!------ РАЗДЕЛИТЕЛЬ МЕЖДУ ОБРАЩЕНИЕМ И 6 ПРИЧИН ------><div class="clearBoth"></div><!------ РАЗДЕЛИТЕЛЬ МЕЖДУ ОБРАЩЕНИЕМ И 6 ПРИЧИН ------>
<!--6 причин-->
<jdoc:include type="modules" name="sixcause" />
<!--6 причин-->
</section>
<!------Обращение Губернатора и блоки 6 причин инвестировать------>
<!-------------------------------------- ЗАСТАВКА ПРИРОДНЫЕ РЕСУРСЫ -->
<section id="parallax_slide_1" class="parallax_slide">
<span class="pattern">Природные ресурсы</span>
</section>
<!-- ЗАСТАВКА ПРИРОДНЫЕ РЕСУРСЫ --------------------------------------> 
<!-------------------------------------- ИНВЕСТИЦИОННАЯ КАРТА -->
<section id="invest_map" class="block_content"><a name="invm" style="display:block; position:relative; top:-100px;"></a>
<div class=""><h1 class="h1_cept">Инвестиционная карта</h1></div>
<article class="wrap_content">
<img id="rusmap" src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/russia.png" alt="Инвестиционная карта россии">
<img id="zbk" src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/images/zk.png" alt="Забайкальский край">
</article>
</section>
<!-- ИНВЕСТИЦИОННАЯ КАРТА -------------------------------------->
<!-------------------------------------- ЗАСТАВКА ЖЕЛЕЗНАЯ ДОРОГА -->  
<section id="parallax_slide_2" class="parallax_slide">
<span class="pattern">Железная дорога</span></section>
<!-- ЗАСТАВКА ЖЕЛЕЗНАЯ ДОРОГА -------------------------------------->  
<!-------------------------------------- НАВИГАТОР ИНВЕСТОРА-->
<section id="navigator_inv" class="block_content"><div class=""><h1 class="h1_cept">Навигатор инвестора</h1></div>
<jdoc:include type="modules" name="navig" /></section>
<!-- НАВИГАТОР ИНВЕСТОРА -------------------------------------->
<!-------------------------------------- ЗАСТАВКА ГАЗИФИКАЦИЯ РЕГИОНА --> 
<section id="parallax_slide_3" class="parallax_slide">
<span class="pattern">Газификация региона</span></section>
<!-- ЗАСТАВКА ГАЗИФИКАЦИЯ РЕГИОНА -------------------------------------->



<!-------------------------------------- ПРОЕКТЫ ПЛОЩАДКИ ОРГАНИЗАЦИИ -->
<section id="ppo" class="block_content">
<div class=""><h1 class="h1_cept">Проекты - площадки - организации</h1></div>
<jdoc:include type="modules" name="projects" /></section>
<!-- ПРОЕКТЫ ПЛОЩАДКИ ОРГАНИЗАЦИИ -------------------------------------->



<!-------------------------------------- ВИДЕО-ЗАСТАВКА РУКОПОЖАТИЕ -->
  <section id="parallax_slide_4" class="parallax_slide">
  <div id="pleer">
   <video loop autoplay muted poster="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/video/vid.jpg">
      <source src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/video/vid.mp4" type="video/mp4">
    </video>
  </div>
  <span id="videp" class="pattern">INVEST IN TRANS-BAIKAL REGION</span>
 
  </section>
<!--  ВИДЕО-ЗАСТАВКА РУКОПОЖАТИЕ -------------------------------------->



<!-------------------------------------- СОБЫТИЯ -------------------------------------->
  <section id="stati_sliders" class="block_content">
        <div class=""><h1 class="h1_cept">События</h1></div>
        <article class="wrap_content firstst" style="height:100%; max-width:94%;">
          <jdoc:include type="modules" name="stati" />
        </article>
<!------ РАЗДЕЛИТЕЛЬ ------><div class="clearBoth"></div><!------ РАЗДЕЛИТЕЛЬ ------>
        <article class="wrap_content secondst" style="height:100%; max-width:94%;">
          <jdoc:include type="modules" name="stati-2" />
        </article>
  </section>
  
<!-------------------------------------- ЗАСТАВКА УВЕРЕННОСТЬ В БУДУЩЕМ -->
  <section id="parallax_slide_5" class="parallax_slide">
    <span class="pattern">Уверенность в будущем</span>
  </section>
<!--ЗАСТАВКА УВЕРЕННОСТЬ В БУДУЩЕМ -------------------------------------->


  
<!-------------------------------------- ГАЛЕРЕЯ -->
  <section id="galereyaizk" class="block_content">
                <div id="hGal" class=""><h1 class="h1_cept inter"><a href="index.php/ru/gallery">Галерея <i class="fa fa-sign-out"></i></a></h1></div>
        <article class="commented-wrap_content-commented"  style="width:100%; margin:16px auto; position:absolute;">
         <div style="width:100%;"><jdoc:include type="modules" name="galereya" /></div>
        </article>
  </section>

<!-- ГАЛЕРЕЯ -------------------------------------->
  


<!-------------------------------------- ЗАСТАВКА УВЕРЕННОЕ УПРАВЛЕНИЕ -->
  <section id="parallax_slide_6" class="parallax_slide">
    <span class="pattern">Уверенное управление</span>
  </section>
<!-- ЗАСТАВКА УВЕРЕННОЕ УПРАВЛЕНИЕ -------------------------------------->
</main> 

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
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<script src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/js/scroll_menu.js"></script>
<script src="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/js/scripts.js"></script>
</body>
</html>
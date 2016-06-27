<?php session_start(); ?>
<div id="legalplus-cookies-banner" class="block <?= (get_option('legalplus_banner_position') == 0 ?  'top' :  'bottom')?>" style="display: none;"
     data-cookies_accepted="<?php echo (isset($_COOKIE['legalpluscookie']) == 1 ? $_COOKIE['legalpluscookie'] : 'false') ?>">
    <?php
        if(!isset($_COOKIE['legalpluscookie'])) {
            try {
                setcookie('legalpluscookie', 'accepted', 0, '/');
            } catch (Exception $e) {}
            try {
                $_COOKIE['legalpluscookie'] = 'accepted';
            } catch (Exception $e) {}
        }
    ?>
    <div class="block_content">
        <p id="legal_cookies_banner_text" data-cookies_link="<?php echo get_page_link(get_option('legalplus_page_privacity')) ?>">
            <?php echo get_option('legalplus_COOKIES_TEXT', 'Este sitio web utiliza cookies propias y de terceros que analizan el uso del mismo con la finalidad de mejorar nuestros contenidos y su experiencia como usuario.
Si continua navegando, consideramos que acepta su uso. Puede obtener más información o bien conocer cómo cambiar su configuración en nuestra [legalplus_CONDITIONS_LINK]') ?>
        </p>
        <div class="legalplus-accept-btn">
            <a id="legalplus_deny_cookies" href="">No acepto</a>
            <a id="legalplus_accept_cookies" href="">Acepto</a>
        </div>
    </div>
</div>

<div id="legal-confirm" style="display: none;">
    <div class="text">
        Para poder continuar debe aceptar nuestra
        <a href="<?php echo get_page_link(get_option('legalplus_page_privacity')) ?>" target="_blank">Política de privacidad</a>.
    </div>
    <div id="legal-accept">Aceptar</div>
    <div id="legal-deny">Cancelar</div>
</div>
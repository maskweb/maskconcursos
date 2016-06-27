<?php function getItems()
{
    return array(
        array(
            'type' => 'text',
            'label' => __('Dominio web'),
            'desc' => __('[legalplus_WEB_DOMAIN] Por ejemplo www.mitienda.es'),
            'name' => 'legalplus_WEB_DOMAIN',
            'required' => 'required',
            'extra' => 'maxlength="64"',
        ),
        array(
            'type' => 'text',
            'label' => __('Titular de la web'),
            'desc' => __('[legalplus_TITULAR] Persona física o sociedad propietaria del negocio online. Debe
                coincidir con el titular del negocio dado de alta en Hacienda como empresario, ya sea una persona o
                 una sociedad.'),
            'name' => 'legalplus_TITULAR',
            'required' => 'required',
            'extra' => 'maxlength="64"',
        ),
        array(
            'type' => 'text',
            'label' => __('Domicilio completo'),
            'desc' => __('[legalplus_COMPLETE_ADDRESS] Debe incluir calle, código postal, municipio, Comunidad
                     Autónoma y país'),
            'name' => 'legalplus_COMPLETE_ADDRESS',
            'size' => 20,
            'required' => 'required',
            'extra' => 'maxlength="128"',
        ),
        array(
            'type' => 'text',
            'label' => __('CIF o NIF del titular'),
            'desc' => __('[legalplus_IDENT_CODE] NIF del  titular de la web o CIF en el supuesto que se haya
                    informado como propietario una sociedad o entidad mercantil'),
            'name' => 'legalplus_IDENT_CODE',
            'size' => 20,
            'required' => 'required',
            'extra' => 'maxlength="20"',
        ),
        array(
            'type' => 'text',
            'label' => __('Telefono'),
            'desc' => __('[legalplus_TELEPHONE]'),
            'name' => 'legalplus_TELEPHONE',
            'size' => 20,
            'required' => 'required',
            'extra' => 'maxlength="32"',
        ),
        array(
            'type' => 'text',
            'label' => __('Email titular'),
            'desc' => __('[legalplus_EMAIL]'),
            'name' => 'legalplus_EMAIL',
            'size' => 20,
            'required' => 'required',
            'extra' => 'maxlength="128"',
        ),
        array(
            'type' => 'text',
            'label' => __('Datos Registro Mercantil'),
            'desc' => __('[legalplus_REGISTER] Solo rellenar en el supuesto de que el titular
                     de la web sea una sociedad. Se puede dejar en blanco si el propietario de la web es
                     una persona física. Por ejemplo, si el titular de la web es “Mi tienda S.L” tendrá
                     unos datos de Registro Mercantil que aparecen en las últimas hojas de la escritura
                     de constitución de la sociedad, tipo “Registo Mercantil de Palma de Mallorca n. 
                     Registro 3 Mallorca Tomo 2282 Libro 0 Folio 70 Hoja PM58385 Inscripción I'),
            'name' => 'legalplus_REGISTER',
            'size' => 20,
            'required' => '',
            'extra' => 'maxlength="128"',
        ),
        array(
            'type' => 'text',
            'label' => __('Código de conducta , Enlace web código de conducta'),
            'desc' => __('[legalplus_CONDUCT_CODE] Informar, si fuera el caso, del nombre
                     del código de conducta para resolución de conflictos al que se haya adherido
                      la tienda y la dirección de la web de ese código. Por ejemplo, “Confianza online”
                      y su web “https://www.confianzaonline.es/”. Si no se está adherido a ningún código
                      de conducta este campo se puede dejar en blanco.'),
            'name' => 'legalplus_CONDUCT_CODE',
            'size' => 20,
            'required' => '',
            'extra' => 'maxlength="128"',
        ),
        array(
            'type' => 'text',
            'label' => __('Nº de licencia, órgano supervisor, fecha de otorgamiento'),
            'desc' => __('[legalplus_LICENCE] Este campo solo deberán rellenarlo aquellos negocios que
                     requieran de autorización administrativa especial para la venta de sus productos, como por ejemplo
                      las farmacias.'),
            'name' => 'legalplus_LICENCE',
            'size' => 20,
            'required' => '',
            'extra' => 'maxlength="128"',
        ),
        array(
            'type' => 'text',
            'label' => __('Titulación universitaria, Universidad, Colegio profesional, nº colegiado'),
            'desc' => __('[legalplus_COLEGE] Este campo solo deberán rellenarlo los negocios cuyos titulares
                    sean profesionales colegiados, como por ejemplo abogados, médicos, arquitectos o dentistas que estén
                    vendiendo productos o servicios.'),
            'name' => 'legalplus_COLEGE',
            'size' => 20,
            'required' => '',
            'extra' => 'maxlength="128"',
        ),
        array(
            'type' => 'textarea',
            'label' => __('Texto del banner de cookies'),
            'desc' => __('[legalplus_CONDITIONS_LINK] Link de la página de privacidad y cookies.<br>Dejar en blanco para restaurar por defecto.'),
            'default' => 'Este sitio web utiliza cookies propias y de terceros que analizan el uso del mismo con la finalidad de mejorar nuestros contenidos y su experiencia como usuario.
Si continua navegando, consideramos que acepta su uso. Puede obtener más información o bien conocer cómo cambiar su configuración en nuestra [legalplus_CONDITIONS_LINK]',
            'name' => 'legalplus_COOKIES_TEXT',
            'cols' => 150,
            'rows' => 10,
            'autoload_rte' => 'required',
            'required' => '',
            'extra' => 'maxlength="400"',
        ),
        array(
            'type' => 'radio',
            'label' => __('Posición del banner de cookies'),
            'default' => 0,
            'desc'=> '',
            'name' => 'legalplus_banner_position',
            'options' => array(
                array(
                    'name' => 'Arriba',
                    'value' => 0,
                ),
                array(
                    'name' => 'Abajo',
                    'value' => 1,
                ),
            )
        ),
        array(
            'type' => 'checkbox',
            'label' => __('Panel inferior de enlaces'),
            'desc' => __('Mostrar una barra inferior con los enlaces a las páginas de privacidad y aviso legal'),
            'name' => 'legalplus_bottom_bar',
        ),
    );
}?>

<?php
if ($_POST && check_admin_referer($_SERVER['PHP_SELF']."?page=legal_plus_settings") == '1') {
    foreach($_POST as $key => $val) {
        update_option($key, sanitize_text_field($val));
    }
}
if(!isset($_POST['legalplus_bottom_bar']) && isset($_POST['legalplus_WEB_DOMAIN'])) {
    update_option('legalplus_bottom_bar', 'off');
}
if(get_option('legalplus_COOKIES_TEXT','') == ''){
    update_option('legalplus_COOKIES_TEXT', 'Este sitio web utiliza cookies propias y de terceros que analizan el uso del mismo con la finalidad de mejorar nuestros contenidos y su experiencia como usuario.
Si continua navegando, consideramos que acepta su uso. Puede obtener más información o bien conocer cómo cambiar su configuración en nuestra [legalplus_CONDITIONS_LINK]');
} ?>

<h1>Legal+ ajustes</h1>
<br>
<h3 class="title">
    ¿Cómo funciona?
</h3>
<p>
    Al instalar el módulo "Legal +", creamos las páginas de "Política de privacidad y Cookies" y "Aviso legal" para que puedas editarlas facilmente.
    No obstante, los diferentes parámetros de estas páginas los puedes configurar rellenando los campos que verás a continuación, algunos son obligatorios "*" y otros opcionales. Cada campo te indica su "short code", así como un texto de ayuda.<br>
    Puedes elegir la posición del banner de Cookies, así como hacer aparecer o desaparecer el faldón con los enlaces a dichas páginas.
    Por último, te facilitamos toda una serie de "Recomendaciones legales" para que leas con atención.
</p>
<form method="POST" action="<?php echo $_SERVER['PHP_SELF']."?page=legal_plus_settings" ?>">
    <?php wp_nonce_field($_SERVER['PHP_SELF']."?page=legal_plus_settings") ?>
    <table class="form-table">
        <tbody>
            <?php
            foreach(getItems() as $key => $val) {
                echo '<tr class="item">';
                    echo '<th><label>'.(isset($val['required']) && $val['required'] == 'required' ? '* ':'').$val['label'].':</label></th>';
                    echo '<td><fieldset>';
                        echo '<legend class="screen-reader-text"><span>'.$val['label'].'</span></legend>';
                        if($val['type'] == 'textarea') {
                            echo '<textarea name="' . $val['name'] . '" ' . $val['required'];
                            echo ' cols="' . $val['cols'] . '" rows="' . $val['rows'] .'" ';
                            if (isset($val['extra']) != '') {
                                echo $val['extra'];
                            }
                            echo '>';
                            if (get_option($val['name']) != '' || get_option($val['name']) != null) {
                                echo esc_html(get_option($val['name']));
                            } else {
                                echo $val['default'];
                            }
                            echo '</textarea><br>';
                        } elseif($val['type'] == 'checkbox') {
                            echo '<p>';
                            echo '<input name="'.$val['name'].'" type="checkbox" class=""';
                            if (get_option($val['name']) == 'on') {
                                echo 'checked="checked"';
                            }
                            echo '>';
                            echo '</p>';
                        } elseif($val['type'] == 'radio') {
                            foreach($val['options'] as $option) {
                                echo '<p>';
                                echo '<input name="'.$val['name'].'" type="radio" value="'.$option['value'].'" class="tog"';
                                if (get_option($val['name']) == $option['value'] ||
                                    ($option['value'] == $val['default']) && get_option($val['name']) == null) {
                                    echo ' checked="checked"';
                                }
                                echo '>'.$option['name'];
                                echo '</p>';
                            }
                        } else {
                            echo '<input type="'.$val['type'].'" name="'.$val['name'].'" '.$val['required'].
                                 ' value="'.esc_html(get_option($val['name'])).'" class="regular-text" ';
                            if (isset($val['extra'])) {
                                echo $val['extra'];
                            }
                            echo '><br>';
                        }
                    if($val['desc'] != '') {
                        echo '<p class="description" >'.$val['desc'].'</p>';
                    }
                    echo '</fieldset></td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
    <p class="submit">
        <input type="submit" name="submit" id="submit" class="button button-primary" value="Guardar cambios">
    </p>
</form>
<br>
<h3 class="title">
    Recomendaciones legales
</h3>
<p>
<br>
    1. No te olvides de comunicar a la Agencia de Protección de Datos que tienes una base de datos de suscriptores o usuarios web si recoges direcciones de correo para enviar newsletters o comunicaciones. Las preguntas frecuentes respecto al modo y lugar de esta comunicación telemática las puedes encontrar en el siguiente enlace <a href="https://www.agpd.es/portalwebAGPD/canalresponsable/inscripcion_ficheros/preguntas_frecuentes/cuestiones_generales/index-ides-idphp.php">https://www.agpd.es/portalwebAGPD/canalresponsable/inscripcion_ficheros/preguntas_frecuentes/cuestiones_generales/index-ides-idphp.php</a>
    <br><br>
    2. Los textos legales creados a través de este módulo son generales y standards por lo que debes proceder a su revisión para comprobar que tu web específica puede regularse mediante los mismas. 
    <br><br>
    3. En la “Política de privacidad y cookies” se han informado las cookies habituales de una web corporativa: las analíticas de Google y las de Facebook. Si no instalas algunas de estas cookies o, por el contrario, has añadido alguna más, debes modificar el listado de cookies que por defecto publicarás en tu texto legal. 
    <br><br><br><br>
    Para cualquier consulta profesional o específica sobre los aspectos legales de tu web no dudes en contactar con nosotros para personalizar tus textos legales a través del correo info@consultingdms.com
</p>
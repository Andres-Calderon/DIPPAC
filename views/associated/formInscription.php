<?php
defined('EXECG__') or die('<h1>404 - <strong>Not Found</strong></h1>');
?>
<div class="container" style="margin-bottom: 20px; margin-top: 10px">
  <div id="main">
    <div class="maxcontent" id="content">
      <div id="fancybox-title" class="fancybox-title-float" style="display: block; z-index: 50">
        <table cellspacing="0" cellpadding="0" id="fancybox-title-float-wrap"><tbody><tr><td id="fancybox-title-float-main">Inscripci&oacute;n de estudiantes: Formulario</td></tr></tbody></table>
      </div>
      <div align="center"> 
        A continuaci&oacute;n diligencie la informaci&oacute;n del nuevo usuario, los campos que tienen asterisco "(*)" son obligatorios.  
        <form action="index.php?controlador=Associated&accion=prepareUser" method="POST" id="formInscription">
          <fieldset class="colorleyend" style="width: 90%;">
            <legend class="colorleyendinto"><?php $doc->texto('BASIC_INFORMATION') ?></legend>
            <div class="form-group">
              <label><?php $doc->texto('FULL_NAME') ?>: </label>
              <?php
              $view->input("full_name", "text", $doc->t('FULL_NAME'), array('required' => true,
              'text' => 'onlytext',
              'minsize' => '8'), array(
              'maxlength' => '35',
              'value'=>$usuario?$usuario->getNombre():NULL));
              ?>
            </div>
            <div class="form-group">
              <label for=""><?php $doc->texto('IDENTIFIER') ?>: </label>
              <?php
              $view->input("cedula",
              "numeric",
              $doc->t('IDENTIFIER'),
              array('required' => true, 'text' => 'numeric', 'norepeat' => 'val1', 'minsize' => '5'),
              array( 'maxlength' => '15', 'value'=>$usuario?$usuario->getCedula():NULL));
              ?>
            </div>
            <div class="form-group">
              <label for=""><?php $doc->texto('BORN_DATE') ?>(*): </label>
              <?php
              $view->input("born_date", "calendar", $doc->t('BORN_DATE'),
              array('required' => true), array('readonly' => 'readonly',
              'midate' => (int) date("Y") - 100,
              'madate' => (int) date("Y") - 18,
              'value'=>$usuario?$usuario->getFechaNacimiento():NULL));
              ?>
            </div>
            <div class="form-group">
              <label for=""><?php $doc->texto('MAIL') ?>: </label>
              <?php
              $view->input("email", "text", $doc->t('MAIL'),
              array('required' => true, 'text' => 'email', 'norepeat' => 'val1'),
              array( 'maxlength' => '40','value'=>$usuario?$usuario->getEmail():NULL));
              ?>
            </div>
          </fieldset>
          <fieldset class="colorleyend" style="width: 90%; display: none">
            <legend class="colorleyendinto"><?php $doc->texto('SEND_INFORMATION') ?></legend>
            <div class="form-group">
              <label for=""><?php $doc->texto('PHONE') ?>:</label>
              <?php
              $view->input("phone", "numeric", $doc->t('PHONE'), array('text' => 'numeric', 'minsize' => '7'), array(
              'maxlength' => '10','value'=>$usuario?($usuario->getTelefono()==0?"":$usuario->getTelefono()):NULL));
              ?>
            </div>
            <div class="form-group">
              <label for=""><?php $doc->texto('MOVIL') ?>:</label>
              <?php
              $view->input("movil", "numeric", $doc->t('MOVIL'), array('text' => 'numeric', 'minsize' => '10'), array(
              'maxlength' => '10', 'value'=>$usuario?($usuario->getMovil()==0?"":$usuario->getMovil()):NULL));
              ?>
            </div>
            <div class="form-group">
              <label for=""><?php $doc->texto('STADE') ?>: </label>
              <?php echo $deps; ?>
            </div>
            <div class="form-group">
              <label for=""><?php $doc->texto('CITY') ?>: </label>
              <div id="resCid"><?php echo $cids; ?></div>
            </div>
            <div class="form-group">
              <label for=""> <?php $doc->texto('SECTOR') ?>: </label>
              <?php echo $locvin; ?>
            </div>
            <div class="form-group">
              <label for=""> <?php $doc->texto('NEIGHBORHOOD') ?>: </label>
              <div id="barrio"><?php echo $barrvin; ?></div>
            </div>
            <div class="form-group">
              <label for=""><?php $doc->texto('ADDRESS') ?>:</label>
              <?php
              $view->input("address", "text", "Direcci&oacute;n",
              array('required' => true,
              'text' => 'address'),
              array(
              'maxlength' => '50',
              'minsize' => '10',
              'value'=>$usuario?$usuario->getDireccion():NULL));
              ?>
            </div>
            <div class="form-group">
              <label for=""> <?php $doc->texto('FAX') ?>: </label>
              <?php
              $view->input("fax", "numeric", $doc->t('FAX'), array('text' => 'numeric',
              'minsize' => '7'), array(
              'maxlength' => '10','value'=>$usuario?($usuario->getFax()==0?"":$usuario->getFax()):NULL));
              ?>
            </div>
          </fieldset>
          <fieldset class="colorleyend" style="width: 90%; display: none;">
            <legend class="colorleyendinto"><?php $doc->texto('INFORMACION_BENEFICIARIO') ?></legend>
            <div class="form-group">
              <label for=""><?php $doc->texto('FULL_NAME') ?>: </label>
              <?php
              $view->input("full_name_ben", "text", $doc->t('FULL_NAME'), array('required' => false,
              'text' => 'onlytext',
              'minsize' => '8'), array(
              'maxlength' => '35', 'value'=>$usuario?$beneficiario->getNombre():NULL));
              ?>
            </div>
            <div class="form-group">
              <label for=""><?php $doc->texto('IDENTIFIER') ?>:</label>
              <?php
              $view->input("cedula_ben", "numeric", $doc->t('IDENTIFIER'), array('required' => false, 'text' => 'numeric', 'minsize' => '5'),
              array( 'maxlength' => '15','value'=>$usuario?$beneficiario->getCedula():NULL));
              ?>
            </div>
            <div class="form-group">
              <label for=""><?php $doc->texto('PHONE') ?>: </label>
              <?php
              $view->input("phone_ben", "numeric", $doc->t('PHONE'), array('text' => 'numeric', 'minsize' => '7'), array(
              'maxlength' => '10','value'=>$usuario?($beneficiario->getTelefono()==0?"":$beneficiario->getTelefono()):NULL));
              ?>
            </div>
            <div class="form-group">
              <label for=""><?php $doc->texto('MAIL') ?>: </label>
              <?php
              $view->input("email_ben", "text", $doc->t('MAIL'), array('required' => false, 'text' => 'email', 'norepeat' => 'val1'),
              array( 'maxlength' => '40','value'=>$usuario?$beneficiario->getEmail():NULL));
              ?>
            </div>
            <div class="form-group">
              <label for=""><?php $doc->texto('STADE') ?>:</label>
              <?php echo $depsben; ?>
            </div>
            <div class="form-group">
              <label for=""><?php $doc->texto('CITY') ?>: </label>
              <div id="resCid2"><?php echo $cidsben; ?></div>
            </div>
            <div class="form-group">
              <label for=""><?php $doc->texto('ADDRESS') ?>: </label>
              <?php 
              $view->input("address_ben", "text","Direcci&oacute;n", array('required' => false,
              'text' => 'address'), array('value'=>$usuario?$beneficiario->getDireccion():NULL));
              ?>
            </div>
            <div class="form-group">
              <label for=""> <?php $doc->texto('BORN_DATE') ?>(*):</label>
              <?php
              $view->input("born_date_ben", "calendar", $doc->t('BORN_DATE'), array('required' => false), array('readonly' => 'readonly',
              'midate' => (int) date("Y") - 100,
              'madate' => (int) date("Y") - 18,'value'=>$usuario?$beneficiario->getFechaNacimiento():NULL));
              ?>
            </div>
            <div class="form-group">
              <label for=""><?php $doc->texto('PARENTAGE') ?>: </label>
              <select name="parentesco" id="parentesco">
                <option value="Padre">Padre</option>
                <option value="Madre">Madre</option>
                <option value="Esposo (a)">Esposo (a)</option>
                <option value="Hijo(a)">Hijo(a)</option>
                <option value="Abuelo(a)">Abuelo(a)</option>
                <option value="Hermano(a)">Hermano(a)</option>
                <option value="Tio(a)">Tio(a)</option>
                <option value="Primo(a)">Primo(a)</option>
                <option value="Sobrino(a)">Sobrino(a)</option>
                <option value="Amigo(a)">Amigo(a)</option>
                <option value="Otro">Otro</option>
              </select>
            </div>
          </fieldset>
          <div class="mitadboton">
            <button class="buscarButton" style="float: left;height: 40px;margin-left: 5%" id="finish"><?php $doc->texto('CONTINUE') ?></button>
            <div id="loader" style="float: left;margin-left: auto; margin-right: auto; display: none">
              <img src="images/ajax-loader.gif"/> Procesando...
            </div>
          </div>
           
        </form>
        <form action="index.php?controlador=Associated" method="post" >
          <div class="mitadboton">
            <button class="buscarButton" style="float: left;height: 40px; margin-left: 5px" id="cancel"><?php $doc->texto('BACK') ?></button>
          </div>
          
        </form>
        <div style="clear: both"></div>
      </div>
    </div>
  </div>
</div>
<script src="http://malsup.github.com/jquery.form.js"></script>
<script>
$(document).ready(function() {
<?php if($localidad){?>
$("#departamentos").val('<?php echo $departamento?>');
$("#depben").val('<?php echo $departamentoBen?>');
$("#locvin").val('<?php echo $localidad;?>');
$('#barrio').val('<?php echo $usuario->getBarrio();?>');
$("select[name='ciudades']").val('<?php echo $usuario->getCiudad();?>');
$("select[name='cidben']").val('<?php echo $beneficiario->getCiudad();?>');
$('#parentesco').val('<?php echo $beneficiario->getParentesco();?>');
<?php }else{?>
$("#departamentos").val('<?php echo $departamento?>');
$("#depben").val('<?php echo $departamentoBen?>');
<?php }?>
$("#departamentos").change(function() {
var ajaxOpts = {
type: "get",
url: "index.php",
data: "&controlador=Associated&accion=ajaxCiudades&departamento=" + $("#departamentos").val()+"&tag=ciudades",
success: function(data) {
$('#resCid').html(data);
}

};
$.ajax(ajaxOpts);
});
$("#depben").change(function() {
var ajaxOpts = {
type: "get",
url: "index.php",
data: "&controlador=Associated&accion=ajaxCiudades&departamento=" + $("#depben").val()+"&tag=cidben",
success: function(data) {
$('#resCid2').html(data);
}

};
$.ajax(ajaxOpts);
});
$("#locvin").change(function() {
var ajaxOpts = {
type: "get",
url: "index.php",
data: "&controlador=Associated&accion=ajaxBarrios&localidad=" + $("#locvin").val()+"&tag=barrvin",
success: function(data) {
$('#barrio').html(data);
}

};
$.ajax(ajaxOpts);
});


$('input.onepic').click(function(){
$("[name='year']").val(<?php echo $ano ?>);
$("[name='month']").val(<?php echo $mes ?>);
var dia =<?php echo $dia ?>;
var $ttd = $('td.date');
$ttd.each(function() {
if($.trim($(this).html())>dia){
$(this).removeClass("chosen");
$(this).css("color","#555555");
$(this).css("cursor","default");
$(this).unbind();
}
});
$("select[name='year']").change(function(){
var ano =  $(this).val();
var mes =  $("[name='month']").val();
var diamax =<?php echo $dia ?>;
var mesmax =<?php echo $mes ?>;
var anomax =<?php echo $ano ?>;
var $ttd = $('td.date');
if(ano<anomax){
  $ttd.each(function() {
  $(this).css("color","#000000");
  $(this).css("cursor","pointer");
  });
  }else if(ano==anomax){
  if(mes<mesmax){
  $ttd.each(function() {
  $(this).css("color","#000000");
  $(this).css("cursor","pointer");
  });
  }else if(mes==mesmax){
  $ttd.each(function() {
  if($.trim($(this).html())>diamax){
  $(this).css("color","#555555");
  $(this).css("cursor","default");
  $(this).unbind();
  }else{
  $(this).css("color","#000000");
  $(this).css("cursor","pointer");
  }
  });
  }else if(mes>mesmax){
  $ttd.each(function() {
  $(this).css("color","#555555");
  $(this).css("cursor","default");
  $(this).unbind();
  });
  }
  }else if(ano>anomax){
  $ttd.each(function() {
  $(this).css("color","#555555");
  $(this).css("cursor","default");
  $(this).unbind();
  });
  }
  });
  $("[name='month']").change(function(){
  var ano =  $("[name='year']").val();
  var mes =  $("[name='month']").val();
  var diamax =<?php echo $dia ?>;
  var mesmax =<?php echo $mes ?>;
  var anomax =<?php echo $ano ?>;
  var $ttd = $('td.date');
  if(ano<anomax){
    $ttd.each(function() {
    $(this).css("color","#000000");
    $(this).css("cursor","pointer");
    });
    }else if(ano==anomax){
    if(mes<mesmax){
    $ttd.each(function() {
    $(this).css("color","#000000");
    $(this).css("cursor","pointer");
    });
    }else if(mes==mesmax){
    $ttd.each(function() {
    if($.trim($(this).html())>diamax){
    $(this).css("color","#555555");
    $(this).css("cursor","default");
    $(this).unbind();
    }else{
    $(this).css("color","#000000");
    $(this).css("cursor","pointer");
    }
    });
    }else if(mes>mesmax){
    $ttd.each(function() {
    $(this).css("color","#555555");
    $(this).css("cursor","default");
    $(this).unbind();
    });
    }
    }else if(ano>anomax){
    $ttd.each(function() {
    $(this).css("color","#555555");
    $(this).css("cursor","default");
    $(this).unbind();
    });
    }
    });
    $("#formInscription input").not(".onepic").click(function(){
    $(".close").click();
    });
    });
    $("#formInscription input").focus(function(){
    $(this).css("background-color","#FFF");
    $(this).nextAll().remove();
    });
    
    $('#formInscription').ajaxForm({
    dataType: 'json',
    beforeSubmit: function() {
    $('#finish').attr('disabled', 'disabled');
    $("#finish").addClass("buscarButtonDis");
    $("#finish").removeClass("buscarButton");
    $('#cancel').attr('disabled', 'disabled');
    $("#cancel").addClass("buscarButtonDis");
    $("#cancel").removeClass("buscarButton");
    $('#loader').css('display', 'block');
    if (validates("formInscription")) {
    return true;
    } else {
    $('#loader').css('display', 'none');
    $('#finish').removeAttr('disabled');
    $("#finish").addClass("buscarButton");
    $("#finish").removeClass("buscarButtonDis");
    $('#cancel').removeAttr('disabled');
    $("#cancel").addClass("buscarButton");
    $("#cancel").removeClass("buscarButtonDis");
    return false;
    }
    },
    success: function(responseText) {
    /*
    $('#loader').css('display', 'none');
    $('#finish').removeAttr('disabled');
    $("#finish").addClass("buscarButton");
    $("#finish").removeClass("buscarButtonDis");
    $('#cancel').removeAttr('disabled');
    $("#cancel").addClass("buscarButton");
    $("#cancel").removeClass("buscarButtonDis");
    */
    window.location=responseText.url;
    }
    });
    });
    </script>
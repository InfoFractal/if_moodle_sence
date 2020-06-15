
<h1 align="center">
  <br>
  <a href="infofractal.io"><img src="https://i.postimg.cc/x1VFSc3T/IF-moodle-sence.png" alt="Sence Plugin para Moodle" width="750"></a>
  <br>
  Plugin para integración de Moodle a Sence
  <br>
</h1>

<h4 align="center"> Permite crear una clase de cursos especiales, a los que para ingresar se debe hacer una autentificación externa en la página de sence</h4>

<p align="center">


  <a href="https://saythanks.io/to/contacto%40infofractal.io">
      <img src="https://img.shields.io/badge/Say%20Thanks-!-1EAEDB.svg">
  </a>
  <a href="https://paypal.me/infofractal">
    <img src="https://img.shields.io/badge/$-donate-ff69b4.svg?maxAge=2592000&amp;style=flat">
  </a>
</p>

<p align="center">
  <a href="#Acerca de">Acerca de</a> •
  <a href="#Instalación">Instalación</a> •
  <a href="#Modo de uso">Modo de uso</a> •
  <a href="#license">License</a>
</p>


![screenshot](https://raw.githubusercontent.com/amitmerchant1990/electron-markdownify/master/app/img/markdownify.gif)


## Acerca de
Este plugin fue desarrollado por InfoFractal para los organismos técnicos de capacitacion *(OTEC)*, que a partir de agosto 2020 necesitan que sus usuarios realicen una autentificación externa en la página del Servicio Nacional de Capacitación y Empleo *(Sence)*.
El plugin esta diseñado para forzar el login del estudiante o capacitado al iniciar un curso sence en la plataforma Moodle.

## Instalación
Este plugin requiere de Moodle 3.8 o superior para funcionar y permisos de administrador para instalarlo. 
* **[Descarga](https://github.com/InfoFractal/if_moodle_sence/archive/master.zip)** la última versión de este plugin en formato zip.

* **En Moodle como Admin** ve a: `/ Área personal/ Administración del sitio / Cursos / Campos personalizados del curso / Add new category/`
	* Agrega la categoría sence y agrega como campos de texto corto o short text los siguientes campos: 
		* Código curso sence [codsence], 
		* Código curso [codcurso],
		* Linea de capacitación [lineacap]
	* Es importante que el nombre corto, entre paréntesis [] sea el mismo aquí indicado. Deberías quedar de la siguiente forma:
	[![Moodle-course-fields.png](https://i.postimg.cc/J75FZSS4/Moodle-course-fields.png)](https://postimg.cc/Z00jhsT1) 

* **En Moodle como Admin** ve a: `/ Área personal / Administración del sitio / Usuarios / Cuentas/ Campos de perfil del usuario/`
	* Agrega un nuevo campo del tipo entrada de texto con el nombre corto runalumno: 
		* Run usuario [runalumno], 
	* Deberías terminar con esto:
	[![runalumno.png](https://i.postimg.cc/RZW6c5sk/runalumno.png)](https://postimg.cc/WdP1vKP8)


* **En Moodle como admin** ve a: `/ Área personal / Administración del sitio / Extensiones / Instalar módulos externos /`
	* Seleciona: `Instalar módulo externo desde un archivo ZIP` y carga el plugin.

	* Guardalo con el nombre de: autentificación sence y dale a `subir este archivo` y `Instalar módulo externo desde un archivo ZIP` 
	* dale click a `Actualiza la base de datos de moodle`.

	* Luego de la instalación el plugn requirirá los datos de la OTEC, run y token, además la url de error que debe hacer referencia al dominio donde esta moodle, ejemplo: ` https://forlab.infofractal.cl/blocks/if_sence_login/error/error.php ` 

	* Deberías terminar con algo cómo esto:
	[![afterplugin.png](https://i.postimg.cc/JztQ2F08/afterplugin.png)](https://postimg.cc/gxFRwN3t)
	
## Modo de uso


* **En Moodle como admin** ve a: `/ Inicio del Sitio ` haz click en el engranaje y activa edición 
	* Seleciona: `Agregar bloque` en el panel de la izquierda y carga el plugin autentificación sence.

	El plugin agregará botones de incio a los cursos sences y desplegará en el bloque un seleccionador. 
 
* **En Moodle con permisos para crear curso** ve a: `/ Área personal / Administración del sitio / Cursos / Administrar cursos y categorías/ Agrega otro curso /`
	* Crea tu curso y abajo agrega los campos correspondientes a la categoria sence: 
		* codsence, codcurso, lineacap 
	* Deberías terminar con esto:
	[![cursos.png](https://i.postimg.cc/NjHKLhjp/cursos.png)](https://postimg.cc/LJm90bf1)


* **En Moodle como usuario** haz click en algún curso sence y se obligará a hacer el login en la plataforma Sence.  

## Licencia

	* **GPLv3
	


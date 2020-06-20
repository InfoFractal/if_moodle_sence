
<h1 align="center">
  <br>
  <a href="infofractal.io"><img src="https://i.postimg.cc/x1VFSc3T/IF-moodle-sence.png" alt="Sence Plugin para Moodle" width="750"></a>
  <br>
  Extensión para integración de Moodle a Sence
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
  <a href="#Acerca de">Acerca de esta extensión </a> •
  <a href="#Instalación">Instalación</a> •
  <a href="#Modo de uso">Modo de uso</a> •
  <a href="#license">License</a>
</p>




## Acerca de esta extensión
Esta extensión o plugin fue desarrollada por InfoFractal para los organismos técnicos de capacitacion *(OTEC)* de Chile, que a partir de agosto 2020 necesitan que sus usuarios realicen una autentificación externa en la página del Servicio Nacional de Capacitación y Empleo *(Sence)*.
La extensión esta diseñado para forzar el login del estudiante o capacitado al iniciar un curso sence en la plataforma Moodle.

## Instalación
Esta extensión requiere de Moodle 3.8 o superior para funcionar y permisos de administrador para instalarlo. 
* **[Descarga](https://github.com/InfoFractal/if_moodle_sence/archive/master.zip)** la última versión de este plugin en formato zip.

* **En Moodle como Admin** ve a: `/ Área personal/ Administración del sitio / Cursos / Campos personalizados del curso / Add new category/`
	* Agrega la categoría sence y agrega como campos de texto corto o short text los siguientes campos: 
		* Código curso sence [codsence], 
		* Código curso [codcurso],
		* Linea de capacitación [lineacap]
	* Es importante que el nombre corto, entre paréntesis [] sea el mismo aquí indicado. Debería quedar de la siguiente forma:

	[![Moodle-course-fields.png](https://i.postimg.cc/J75FZSS4/Moodle-course-fields.png)](https://postimg.cc/Z00jhsT1) 

* **En Moodle como Admin** ve a: `/ Área personal / Administración del sitio / Usuarios / Cuentas/ Campos de perfil del usuario/`
	* Agrega un nuevo campo del tipo entrada de texto con el nombre corto runalumno: 
		* Run usuario [runalumno], 
	* El formato de run es 15123456-k, deberías terminar con esto:
	[![runalumno.png](https://i.postimg.cc/RZW6c5sk/runalumno.png)](https://postimg.cc/WdP1vKP8)


* **En Moodle como admin** ve a: `/ Área personal / Administración del sitio / Extensiones / Instalar módulos externos /`
	* Seleciona: `Instalar módulo externo desde un archivo ZIP` y carga el plugin.

	* Guardalo con el nombre de: autentificación sence y dale a `subir este archivo` y `Instalar módulo externo desde un archivo ZIP` 
	* dale click a `Actualiza la base de datos de moodle`.

	* Luego de la instalación el plugn requirirá los datos de la OTEC, run y token.

	* Deberías terminar con algo cómo esto:
	[![moodlwplug1.png](https://i.postimg.cc/qq64pv3M/moodlwplug1.png)](https://postimg.cc/Cn0WNY7W)
	
## Modo de uso


* **En Moodle como admin** ve a: `/ Inicio del Sitio ` haz click en el engranaje y activa edición 
	* Seleciona: `Agregar bloque` en el panel de la izquierda y carga el plugin autentificación sence.

	El plugin agregará botones de incio a los cursos sences y desplegará en el bloque un seleccionador. 
 
* **En Moodle con permisos para crear curso** ve a: `/ Área personal / Administración del sitio / Cursos / Administrar cursos y categorías/ Agrega otro curso /`
	* Crea tu curso y abajo agrega los campos correspondientes a la categoria sence: 
		* codsence, codcurso, lineacap 
	* Deberías terminar con esto:
	[![cursos.png](https://i.postimg.cc/NjHKLhjp/cursos.png)](https://postimg.cc/LJm90bf1)

* **En Moodle con permisos para agreagr usuarios(estudiantes o capacitados)** ve a: `/ Área personal / Administración del sitio / Usuarios / Cuentas /  Agregar un usuario /`

	* Agrega los campos obligatorios del usurario: 
		* nombres, apellidos, mail 
	* Ve al final del formulario y seleciona: ` Otros campos ` y completa
		* runalumno 
	* El formato de run es 15123456-k, deberías terminar con esto:
	[![moodle3.png](https://i.postimg.cc/pTbc7VBt/moodle3.png)](https://postimg.cc/3y1Z0T6L)


* **En Moodle como usuario estudiante o capacitado** haz click en algún curso sence y se te obligará a hacer el login en la plataforma Sence para acceder al curso. 

## Licencia

*  **GPLv3**
	


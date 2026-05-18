=== EWEB - Content Functionalities ===
Contributors: (añade tu usuario de WP.org o equipo)
Tags: acf, shortcodes, pipeline, popups, vacancies, departamentos
Requires at least: 6.0
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.2.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Plugin de utilidades basado en ACF que añade shortcodes para mostrar pipelines (Bio y Digital), vacantes, miembros de departamento, popups con CF7 y otras funciones reutilizables. Incluye soporte de i18n.

== Description ==

Este plugin centraliza diversos shortcodes y utilidades para WordPress + ACF:

* **Pipelines** (Bio y Digital) con visualización en barras
* **Vacantes** (grid y opciones administrativas)
* **Departamentos** (miembros, proyectos, eventos)
* **Popups espontáneos** vinculados a CF7
* **Tiempo estimado de lectura**
* Otros bloques dinámicos (info_acf, contacto de miembros, etc.)

**Características:**
- Todos los shortcodes están documentados en esta guía y pueden usarse en páginas, entradas o widgets (incluido Elementor).
- Incluye assets CSS/JS para popups, pipelines y tarjetas de miembros.
- Internacionalización lista con text domain `eweb-content-functionalities`.

== Installation ==

1. Sube la carpeta del plugin a `/wp-content/plugins/` o instala el ZIP desde el panel de administración.
2. Activa el plugin en *Plugins → Plugins instalados*.
3. Inserta los shortcodes en las páginas o plantillas según necesidad.

== Shortcodes ==

A continuación una lista de los principales shortcodes incluidos:

* `[pipeline_bio_pipeline]` — Renderiza el pipeline BIO con barras.
* `[pipeline_digital_pipeline]` — Renderiza el pipeline DIGITAL (listado de productos).
* `[ipp_popup_espontaneo form="6f7a3f8" btn_text="Texto" title="Título"]` — Popup con formulario de CF7.
* `[tempo_leitura]` — Muestra el tiempo estimado de lectura de un post.
* `[info_acf section="NOMBRE" fields="campo1,campo2"]` — Muestra secciones de campos ACF filtrados.
* `[info_shortcode]` — Shortcode auxiliar para info dinámica.
* `[departamento_miembros]` — Lista los miembros de un departamento (repeater ACF).
* `[dept_eventos]` — Lista de eventos asociados a un departamento.
* `[dept_projetos]` — Lista de proyectos asociados a un departamento.
* `[ipp_vacancies_grid form="6e5f724"]` — Grid de vacantes activas (formulario de candidatura).
* `[ipp_vacancy_options]` — Opciones y columnas extra para CPT de vacantes.
* `[member_contact_popup id="123" btn="Contacto"]` — Popup de contacto para un miembro.

== Screenshots ==

1. Ejemplo de pipeline BIO.
2. Ejemplo de pipeline DIGITAL.
3. Popup espontáneo con CF7.

== Frequently Asked Questions ==

= ¿Necesito ACF Pro? =
Sí, se recomienda ACF Pro para los campos avanzados.

= ¿Funciona con Elementor? =
Sí, puedes insertar los shortcodes en cualquier widget de texto o shortcode.

= ¿Dónde están los archivos de idioma? =
En la carpeta `/languages/` se incluye el `.pot` para traducciones.

== Changelog ==

= 1.2.1 = (2026-05-18)
* Publicación inicial de la documentación.
* Inclusión de pipelines (Bio/Digital), vacantes, popups y shortcodes de departamento.

== Upgrade Notice ==

= 1.2.1 =
Alineación de naming EWEB, documentación EN/ES y workflow de calidad PHP.

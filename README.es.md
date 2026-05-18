# Info ACF i18n

Plugin de WordPress con shortcodes personalizados para campos ACF, pipelines, vacantes, popups y bloques informativos reutilizables.

## Características

- Shortcodes para departamentos, proyectos, eventos y miembros.
- Pipelines visuales (Bio y Digital).
- Popups con integración de Contact Form 7.
- Soporte de internacionalización (`eweb-content-functionalities`).
- Assets CSS/JS incluidos para frontend.

## Requisitos

- WordPress 6.0+
- PHP 7.4+
- ACF (recomendado ACF Pro para campos avanzados)

## Instalación

1. Copia la carpeta del plugin en `/wp-content/plugins/eweb-content-functionalities/`.
2. Activa el plugin desde el panel de WordPress.
3. Usa los shortcodes documentados en `readme.txt` o `README.md`.

## Shortcodes principales

- `[pipeline_bio_pipeline]`
- `[pipeline_digital_pipeline]`
- `[ipp_popup_espontaneo form="ID_CF7"]`
- `[tempo_leitura]`
- `[info_acf section="..." fields="..."]`
- `[departamento_miembros]`
- `[dept_eventos]`
- `[dept_projetos]`
- `[ipp_vacancies_grid form="ID_CF7"]`
- `[member_contact_popup id="123"]`

## Versionado

La versión del plugin se define en:

- Cabecera de `eweb-content-functionalities.php`
- `Stable tag` en `readme.txt`

Ambos deben mantenerse sincronizados.

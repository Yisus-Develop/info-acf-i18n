# Info ACF Plugin
Custom shortcodes for ACF fields.

**Versión:** 1.2.1  
**Text Domain:** `info-acf-plugin`  
**Archivo principal:** `info-acf-plugin.php`

---

## 📦 Estructura (resumen)
```
assets/
  css/
    ipp-popups.css
    member-card.css
    pipeline.css
    style.css
  js/
    ipp-popups.js
    member-card.js
    pipeline.js
includes/
  admin/
    shortcodes-guide.php
  features/
    vcards.php
  shortcodes/
    departamento-miembros-shortcode.php
    dept-eventos-shortcode.php
    dept-projetos-shortcode.php
    info-acf-shortcode.php
    info-shortcode.php
    ipp-vacancies-shortcode.php
    ipp-vacancy-options.php
    member-contact-popup.php
    pipeline-bio-shortcode.php
    pipeline-digital-shortcode.php
    popup-espontaneo.php
    router.php
    tempo_leitura.php
  enqueue.php
languages/
  info-acf-plugin.pot
info-acf-plugin.php
readme.txt
```

## 🧩 Shortcodes
Coloca estos shortcodes en entradas/páginas o widgets de Elementor. Entre paréntesis se indica el archivo fuente.

- `[**departamento_miembros**]` — [departamento_miembros] *(src: includes/shortcodes/departamento-miembros-shortcode.php)*
- `[**departamento_miembros**]` — [departamento_miembros] *(src: includes/shortcodes/departamento-miembros-shortcode.php)*
- `[**dept_eventos**]` — [dept_eventos] *(src: includes/shortcodes/dept-eventos-shortcode.php)*
- `[**dept_projetos**]` — [dept_projetos] *(src: includes/shortcodes/dept-projetos-shortcode.php)*
- `[**info_acf**]` — [info_acf] *(src: includes/shortcodes/info-acf-shortcode.php)*
  - **Atributos:** 'section' => '',   'fields' => '',
- `[**info_shortcode**]` — [info_shortcode] *(src: includes/shortcodes/info-shortcode.php)*
- `[**ipp_vacancies_grid**]` — [ipp_vacancies_grid form="6e5f724"] *(src: includes/shortcodes/ipp-vacancies-shortcode.php)*
  - **Atributos:** 'form' => '6e5f724', // ID CF7 por defecto para "Candidatura a Vaga"
- `[**ipp_vacancy_options**]` — [ipp_vacancy_options] *(src: includes/shortcodes/ipp-vacancy-options.php)*
- `[**member_contact_popup**]` — Íconos SVG inline *(src: includes/shortcodes/member-contact-popup.php)*
  - **Atributos:** 'id'=>'','btn'=>$default_btn
- `[**pipeline_bio_pipeline**]` — Shortcode: [pipeline_bio_pipeline]
Este shortcode renderiza el pipeline BIO y carga solo el CSS y JS necesarios. *(src: includes/shortcodes/pipeline-bio-shortcode.php)*
- `[**pipeline_digital_pipeline**]` — Shortcode: [pipeline_digital_pipeline] *(src: includes/shortcodes/pipeline-digital-shortcode.php)*
- `[**ipp_popup_espontaneo**]` — [ipp_popup_espontaneo form="6f7a3f8" btn_text="Candidatura Espontânea" title="Candidatura Espontânea"] *(src: includes/shortcodes/popup-espontaneo.php)*
  - **Atributos:** 'form'     => '',         'btn_text' => __('Candidatura Espontânea', 'info-acf-plugin'),         'title'    => __('Candidatura Espontânea', 'info-acf-plugin'),
- `[**tempo_leitura**]` — Função para calcular tempo estimado de leitura *(src: includes/shortcodes/tempo_leitura.php)*


## 🗂️ Assets encolados
**Estilos**
- `iap-style` → plugins_url('assets/css/style.css'
- `iap-popups` → plugins_url('assets/css/ipp-popups.css'
- `iap-member-card` → plugins_url('assets/css/member-card.css'
- `pipeline-pipeline-css` → plugins_url('assets/css/pipeline.css'

**Scripts**
- `iap-popups` → plugins_url('assets/js/ipp-popups.js'
- `iap-member-card` → plugins_url('assets/js/member-card.js'
- `pipeline-pipeline-js` → plugins_url('assets/js/pipeline.js'


## ⚙️ Ajustes / Admin
- (No se detectaron páginas de admin propias en este escaneo rápido)


## 🌐 Internacionalización
- Archivos de idioma en `languages/` (POT incluido).
- Usa `__()`, `_e()`, `_x()` con el text domain `info-acf-plugin`.

## 🧰 Requisitos
- WordPress 6.x recomendado
- PHP 7.4 o superior

## 🚀 Instalación
1. Sube la carpeta del plugin a `/wp-content/plugins/` o instala el ZIP desde el admin.
2. Actívalo en **Plugins → Plugins instalados**.
3. Usa los shortcodes según tu caso de uso.

## 📝 Changelog
### 1.2.1 — 2025-09-08
- Añadida documentación base y listado de shortcodes/recursos detectados.

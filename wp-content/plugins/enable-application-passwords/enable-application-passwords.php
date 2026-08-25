<?php
/**
 * Plugin Name: Enable Application Passwords
 * Description: Повертає стандартну можливість WordPress «Паролі застосунків», яку на цьому сайті хтось вимкнув фільтром. Потрібна для програмного доступу до REST API. Деактивація плагіна повністю повертає попередню поведінку.
 * Version:     1.0.0
 * Author:      Mechaman Dispatch
 */

defined( 'ABSPATH' ) || exit;

// Пріоритет 999 — щоб перебити будь-який чужий фільтр, що вимикає цю можливість.
add_filter( 'wp_is_application_passwords_available', '__return_true', 999 );

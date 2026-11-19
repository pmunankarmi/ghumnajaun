<?php
/** Theme-owned functionality requested for this project. */
defined('ABSPATH') || exit;
foreach (['setup','fields','content','design','seo','contact','updates'] as $module) require_once __DIR__ . '/includes/' . $module . '.php';

<?php
/** Theme-owned functionality requested for this project. */
defined('ABSPATH') || exit;
foreach (['setup','languages','fields','content','link-repairs','design','internal-links','blog','performance','seo','contact','updates'] as $module) require_once __DIR__ . '/includes/' . $module . '.php';

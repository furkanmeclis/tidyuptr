<?php

return [
    'font_cache' => storage_path('fonts'),
    'font_dir' => storage_path('fonts'),
    'default_font' => 'DejaVu Sans',
    'default_paper_size' => 'A4',
    'default_paper_orientation' => 'portrait',
    'is_remote_enabled' => true,
    'is_html5_parser_enabled' => true,
    'pdfa' => false,
    'chroot' => realpath(base_path()),
    'log_output_file' => storage_path('logs/dompdf.log'),
    'enable_font_subsetting' => false,
    'dpi' => 96,
    'enable_html5_parser' => true,
    'enable_remote' => true,
    'temp_dir' => sys_get_temp_dir(),
];

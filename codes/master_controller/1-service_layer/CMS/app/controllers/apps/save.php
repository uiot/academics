<?php
$array_mas = [];

if (isset($_POST['action'])):
    switch ($_POST['action']):
        case 'container_save':
        case 'azexo_container_save':
            $name = escape_text($_POST['name']);
            $short_code = urldecode(base64_decode($_POST['shortcode']));
            SaveAppFile::make($name, $short_code);
            break;
        case 'azexo_save_site':
            $name = escape_text($_POST['name']);
            if (AppsMapper::check_by_id($name) > 0):
                $array = json_decode($_POST['site'], true);
                $code = $array['pages']['index']['containers']['builder/75'];
                $code_clean = urldecode(base64_decode($code));
                SaveAppFile::make($name, $code_clean);
            endif;
            break;
        case 'azexo_get_sites':
            function listFolderFiles($dir, $array_mas)
            {
                $ffs = scandir($dir);
                foreach ($ffs as $ff) {
                    if ($ff != '.' && $ff != '..') {
                        if (!is_dir($dir . '/' . $ff)) {
                            $array_mas[] = str_replace('.html', '', $ff);
                        }
                        if (is_dir($dir . '/' . $ff)) {
                            $array_mas = listFolderFiles($dir . '/' . $ff, $array_mas);
                        }
                    }
                }
                return $array_mas;
            }

            echo json_encode(listFolderFiles(ROOT_PATH . '/app/apps/', []));
            break;
        case 'azexo_load_site':
        case 'load_site':
            $plugin_name = escape_text($_POST['name']);
            $plugin_code = GetAppFile::make($plugin_name);
            $plugin_code_masked = base64_encode(urlencode($plugin_code));
            $array
                = [
                'settings' => [
                    'theme' => '',
                    'host' => '',
                    'username' => '',
                    'password' => '',
                    'port' => '21',
                    'directory' => '.'
                ],
                'current_page' => 'index',
                'pages' => [
                    'index' => [
                        'title' => 'home',
                        'containers' => [
                            'builder/75' => $plugin_code_masked
                        ]
                    ]
                ]
            ];
            echo json_encode($array, JSON_UNESCAPED_SLASHES);
            break;
    endswitch;
endif;
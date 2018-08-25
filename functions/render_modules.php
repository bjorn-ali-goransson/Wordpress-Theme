<?php



/* RENDER MODULES */

function render_module_example_code($target, $nested_names = array(), $level = 1) {
  $contents = '';

  foreach($target as $key => $value) {
    if($key == 'acf_fc_layout') {
      continue;
    }

    $names = !empty($nested_names) ? "['" . implode("'], ['", $nested_names) . "']" : NULL;

    if(is_numeric($value)) {
      $contents .= str_repeat('  ', $level) . "  <div class=\"image\" responsive-background-image>\n";
      $contents .= str_repeat('  ', $level) . "    <img class=\"responsive-background-image\" src=\"<?= get_src(\$module{$names}['$key']) ?>\" srcset=\"<?= get_srcset(\$module{$names}['$key']) ?>\" alt=\"\">\n";
      $contents .= str_repeat('  ', $level) . "  </div>\n";

      continue;
    }

    if(is_array($value)){
      $new_names = array_merge($nested_names, array($key));

      $contents .= str_repeat('  ', $level) . "  <div>\n";
      $contents .= render_module_example_code($value, $new_names, $level + 1);
      $contents .= str_repeat('  ', $level) . "  </div>\n";

      continue;
    }

    $tag = 'div';

    if($key == 'heading'){
      $tag = 'h2';
    }

    $contents .= str_repeat('  ', $level) . "  <$tag><?= \$module{$names}['$key'] ?></$tag>\n";
  }

  return $contents;
}

function render_modules(){
  $modules = get_field('modules', get_the_ID());
  
  if(!empty($modules)){
    foreach($modules as $module){
      $name = $module['acf_fc_layout'];
      $function_name = 'module_' . $name;
      
      if(function_exists($function_name)){
        $function_name($module);
      } else {
        ?>
          <p>Could not display <?= $function_name ?></p>
        <?php

        if(user_has_role('administrator')){
          $path = get_template_directory() . '/application/modules';
          $src = $path . '/' . $name . '.php';
          
          if(file_exists($src)){
            continue;
          }
          
          if(!file_exists($path)){
            mkdir($path);
          }

          $contents = '';

          $contents .= "<?php\n";
          $contents .= "\n";
          $contents .= "\n";
          $contents .= "/* " . strtoupper(str_replace('_', ' ', $name)) . " */\n";
          $contents .= "\n";
          $contents .= "function $function_name(\$module){\n";
          $contents .= "  ?>\n";
          
          $contents .= render_module_example_code($module);

          $contents .= "  <?php\n";
          $contents .= "}\n";

          file_put_contents($src, $contents);
        }
      }
    }
  }
}
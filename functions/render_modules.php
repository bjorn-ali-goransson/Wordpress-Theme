<?php



/* RENDER MODULES */

function render_module_example_code($variable_name, $class_name, $value, $indent = 3) {
  $contents = '';

  if(count(array_filter(array_keys($value), 'is_string')) == 0){ // keys are numerical
    $contents .= str_repeat('  ', $indent) . "<?php\n";
    $contents .= str_repeat('  ', $indent) . "  foreach($variable_name as \$item){\n";
    $contents .= str_repeat('  ', $indent) . "    ?>\n";

    $value = array_shift($value);

    $contents .= render_module_example_code('$item', $class_name, $value, $indent + 3);

    $contents .= str_repeat('  ', $indent) . "    <?php\n";
    $contents .= str_repeat('  ', $indent) . "  }\n";
    $contents .= str_repeat('  ', $indent) . "?>\n";
    
    return $contents;
  }

  foreach($value as $key => $value) {
    if($key == 'acf_fc_layout') {
      continue;
    }

    if($class_name == $key && is_array($value) && substr($class_name, -1) == 's'){
      $property_class_name = substr($class_name, 0, -1);
    } else {
      $property_class_name = $class_name . '-' . str_replace('_', '-', $key);
    }

    if(is_numeric($value)) {
      $contents .= str_repeat('  ', $indent) . "<div class=\"$property_class_name\" responsive-background-image>\n";
      $contents .= str_repeat('  ', $indent) . "  <img class=\"responsive-background-image\" src=\"<?= get_src({$variable_name}['$key']) ?>\" srcset=\"<?= get_srcset({$variable_name}['$key']) ?>\" alt=\"\">\n";
      $contents .= str_repeat('  ', $indent) . "</div>\n";

      continue;
    }

    if(is_array($value)){
      if(array_key_exists('url', $value) && array_key_exists('target', $value) && array_key_exists('title', $value)){
        $contents .= str_repeat('  ', $indent) . "<a class=\"$property_class_name\" href=\"<?= {$variable_name}['url'] ?>\" target=\"<?= {$variable_name}['target'] ?>\"><?= {$variable_name}['title'] ?></a>\n";

        continue;
      }

      $property_variable_name = $variable_name . "['$key']";

      $contents .= str_repeat('  ', $indent) . "<div class=\"$property_class_name\">\n";
      $contents .= render_module_example_code($property_variable_name, $property_class_name, $value, $indent + 1);
      $contents .= str_repeat('  ', $indent) . "</div>\n";

      continue;
    }

    $tag = 'div';

    if($key == 'heading'){
      $tag = 'h2';
    }

    $contents .= str_repeat('  ', $indent) . "<$tag class=\"$property_class_name\"><?= {$variable_name}['$key'] ?></$tag>\n";
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
          $path = get_stylesheet_directory() . '/application/modules';
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
          $contents .= "\n";
          $contents .= "/* " . strtoupper(str_replace('_', ' ', $name)) . " */\n";
          $contents .= "\n";
          $contents .= "function $function_name(\$module){\n";
          $contents .= "  ?>\n";
          $contents .= "    <div class=\"" . str_replace('_', '-', $name) . "\">\n";
          
          $contents .= render_module_example_code('$module', str_replace('_', '-', $name), $module);

          $contents .= "    </div>\n";
          $contents .= "  <?php\n";
          $contents .= "}\n";

          file_put_contents($src, $contents);
        }
      }
    }
  }
}
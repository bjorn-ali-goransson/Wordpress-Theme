<?php



/* RENDER MODULES */

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
          
          foreach($module as $key => $value) {
            if($key == 'acf_fc_layout') {
              continue;
            }

            if(+$value > 0) {
              $contents .= "    <div class=\"image\" responsive-background-image>\n";
              $contents .= "      <img class=\"responsive-background-image\" src=\"<?= get_src(\$module['$key']) ?>\" srcset=\"<?= get_srcset(\$module['$key']) ?>\" alt=\"\">\n";
              $contents .= "    </div>\n";
              
              continue;
            }

            $tag = 'div';

            if($key == 'heading'){
              $tag = 'h2';
            }

            $contents .= "    <$tag><?= \$module['$key'] ?></$tag>\n";
          }

          $contents .= "  <?php\n";
          $contents .= "}\n";

          file_put_contents($src, $contents);
        }
      }
    }
  }
}
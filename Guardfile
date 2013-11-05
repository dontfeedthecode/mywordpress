# SASS - let the compression be handled by 'jammit'.
guard :sass,
  :input           => "wp-content/themes/sparky/css",
  :output          => "wp-content/themes/sparky/css",
  :style           => :expanded

# Jammit - production.
guard :jammit,
  :config_path     => "assets.yml",
  :output_folder   => "." do
    watch(%r{^wp-content/themes/sparky/js/(.*)\.js$})
    watch(%r{^wp-content/themes/sparky/css/main\.css$})
  end

# Jammit - development.
guard :jammit,
  :config_path     => "assets-dev.yml",
  :output_folder   => "." do
    watch(%r{^wp-content/themes/sparky/js/(.*)\.js$})
    watch(%r{^wp-content/themes/sparky/css/main\.css$})
  end

# Watch all jade files and compile all the main pages when anything is edited.
guard 'shell' do
  watch(/wp-content\/themes\/sparky\/cutups\/source\/(.*).jade/) { |m|
    `jade -P wp-content/themes/sparky/cutups/source/pages -o wp-content/themes/sparky/cutups`
  }
end

guard 'livereload' do
  watch(%r{.+\.(php|html|js|css)$})
end

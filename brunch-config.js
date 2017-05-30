exports.conventions = {
  ignored: [
    '**/compiled/*',
    'styles/*.built.css'
  ]
};

exports.paths = {
  'public': '.',
  watched: ['scripts', 'styles']
}

exports.files = {
  javascripts: {
    joinTo: {
      'scripts/compiled/vendor.js': 'node_modules/**/*',
      'scripts/compiled/main.js': 'scripts/main.js',
      'scripts/compiled/admin.js': 'scripts/admin.js'
    }
  },
  stylesheets: {
    joinTo: {
      'styles/compiled/admin.scss.css': 'styles/admin.scss',
      'styles/compiled/main.scss.css': 'styles/main.scss',
      'styles/compiled/wp-editor-styles.scss.css': 'styles/wp-editor-styles.scss',
      'styles/compiled/wp-required-styles.scss.css': 'styles/wp-required-styles.scss'
    }
  }
};

exports.plugins = {
  copycat:{
    "fonts" : ["node_modules/font-awesome/fonts"],
    onlyChanged: true
  },
  autoReload: {
    match: {
      stylesheets: /.s?css$/,
    }
  }
};

exports.modules = {
  autoRequire: {
    'ripts/compiled/main.js': ['scripts/main.js']
  }
};
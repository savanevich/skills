'use strict';

module.exports = require('./environment/' + (process.env.NODE_ENV || 'local') + '.js');
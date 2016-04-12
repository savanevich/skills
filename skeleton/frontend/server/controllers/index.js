'use strict';

var express = require('express');
var router = express.Router();
var config = require('./../config');
var assetHashes = require('../../public/dist/asset-hashes.json');
var fs = require('fs');
var path = require('path');

router.get('/', function(req, res) {
    var embeddedData = {
        siteUrl: config.siteUrl,
        api: {
            url: config.api.url
        }
    };

    if (process.env.NODE_ENV == 'local') {
        var assetHashesPath = path.resolve(__dirname + '/../../public/dist/asset-hashes.json');
        assetHashes = JSON.parse(fs.readFileSync(assetHashesPath, 'utf8'));
    }

    res.render("home", {
        assetHashes: assetHashes,
        embeddedData: embeddedData
    });
});

module.exports = router;
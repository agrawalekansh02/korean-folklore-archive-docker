#!/bin/sh
bower install

sass --update .

# jQRangeSlider
echo "Packaging jQRangeSlider"
rm -rf bower_components/jQRangeSlider-5.5.0
cd bower_components/jQRangeSlider
npm install
grunt
unzip packages/jQRangeSlider-5.5.0.zip
mv jQRangeSlider-5.5.0 ..
rm packages/jQRangeSlider-5.5.0.zip
cd ..
# Get rid of original for smaller upload
rm -rf jQRangeSlider
# Get rid of demos for smaller upload
rm -rf jQRangeSlider-5.5.0/demo
cd ..

# Build OpenLayers
echo "Building OpenLayers"
cd bower_components/openlayers/build
./build.py
mv OpenLayers.js ..
rm -rf apidoc_config
rm -rf build
rm -rf doc_config
rm -rf examples
rm -rf tests
cd ../../..

# Get rid of some files for smaller upload
rm -rf bower_components/backbone/docs
rm -rf bower_components/backbone/examples


# QB:
mkdir search/QuB
mv bower_components/QuB/*.php search/QuB


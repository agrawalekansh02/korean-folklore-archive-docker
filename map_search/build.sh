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
# Get rid of demos for smaller upload/build
rm -rf jQRangeSlider-5.5.0/demo
cd ..


# QB:
mkdir search/QuB
mv bower_components/QuB/*.php search/QuB


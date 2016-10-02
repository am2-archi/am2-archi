#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

#update images list and dimensions
/usr/bin/php ${DIR}/images.php

#serve jekyll
bundle exec jekyll serve

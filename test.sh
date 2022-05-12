#!/usr/bin/env bash
set -eou pipefail
cur=$(pwd)
cd $(mktemp -d) && mkdir books && echo 'title=my title' >books/100 && echo 'title=The Bible' >books/999 && eval $cur/bookstore_mgr.php books title

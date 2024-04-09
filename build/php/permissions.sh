#!/bin/bash

# Permission 640 means that the owner has read and write permissions,
#   the group has read permissions, and all other user have no rights to the file

# Permission Value      Permissions
# 0                     No Permissions
# 1                     Execute
# 2                     Write
# 3                     Write and Execute
# 4                     Read
# 5                     Read and Execute
# 6                     Read and Write
# 7                     Read, Write, and Execute

# Owner for a file/folder is going to be root, and group is going to be www-data (for apache)

# Change permissions for configurations in inc/
pushd /var/www/html/inc/
chgrp www-data config.php config2.php
chmod 640 config.php config2.php
popd

# Change permissions for apache log files
pushd /var/log/apache2/
chown root access.log error.log other_vhosts_access.log
chgrp www-data access.log error.log other_vhosts_access.log
chmod 660 access.log error.log other_vhosts_access.log
popd

# Change permissions for monolog log files
# Owner can only read, www-data can only write
pushd /logs
chown root ./
chgrp www-data ./
chmod 720 ./
popd

# Change permissions for apache configurations
pushd /etc/apache2
chgrp www-data -R ./
chmod 740 -R ./
popd

# Change permissions for php configurations
pushd /usr/local/etc/php/
chmod 640 php.ini
popd
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

# Change permissions for mysql configurations files
chgrp mysql /etc/my.cnf /etc/mysql/my.cnf /usr/etc/my.cnf
chmod 640 /etc/my.cnf

# Change permissions for mysql folder
chmod 750 -R /var/lib/mysql

# NOTE: Mysql log files are already set to the correct permissions
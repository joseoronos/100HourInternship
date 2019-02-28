#!/usr/bin/env python
from __future__ import absolute_import
from __future__ import print_function
import csv
import datetime
import sys
import time

import duo_client
from six.moves import input

argv_iter = iter(sys.argv[1:])

admin_api = duo_client.Admin(

    ikey="secret key",
    skey="secret key",
    host="api-afab536c.duosecurity.com",
)


def countdown(n):
    while n > 0:
        print("Closing in: " + str(n))
        time.sleep(1)
        n = n - 1
    print("Closing...")


# Retrieve user info from API:

print()
print("----Loading----")
print()
users = admin_api.get_users()

reporter = csv.writer(sys.stdout)

print("----Report of all users and last login----")
print()

count = 0

#read files in file created by Duo
with open('people.csv', 'w') as csvfile:
    filewriter = csv.writer(csvfile, delimiter=',', quotechar='|', quoting=csv.QUOTE_MINIMAL)
    reporter.writerow(('Username', 'Last Login (UTC)'))
    filewriter.writerow(['Username', 'Last Login (UTC)'])
    for user in users:
        count += 1
        reporter.writerow([
            user["username"],
            datetime.datetime.utcfromtimestamp(user["last_login"]),
        ])

print()
print(str(count) + " records read")
print()
print("----Load Complete-----")
print()

countdown(5)

# input("Press enter to stop...")

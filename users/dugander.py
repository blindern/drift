#!/usr/bin/env python3

import datetime
import difflib
import json
import sys
import urllib.request
import check_users

days = ['mandag', 'tirsdag', 'onsdag', 'torsdag', 'fredag', 'lørdag', 'søndag']

class DugnadListe:
    def __init__(self):
        self.users = check_users.getUsers()
        self.users_names = [user['realname'] for user in self.users]
        self.dugnader = self.getDugnads()

    def findUser(self, name):
        match = check_users.findMatch(name, self.users_names)
        if match == False:
            return False

        for elm in self.users:
            if elm['realname'] == match:
                return elm

        return False

    def dayPrefix(self, day):
        d = days[day.weekday()]
        return d[0].upper() + d[1:] + ' ' + day.strftime('%d.%m') + ': '

    def printUser(self, dayinfo, name):

        user = self.findUser(name)
        if user == False:
            print(dayinfo + "%s (ukjent person)" % name)
        elif user['phone'] == None:
            print(dayinfo + "%s (ukjent nr)" % user['realname'])
        else:
            print(dayinfo + "%s (%s)" % (user['realname'], user['phone']))


    def checkDay(self, day):
        dayinfo = '%16s' % self.dayPrefix(day)

        hasAnretning = day in self.dugnader['anretning']
        hasLordag = day in self.dugnader['lordag']

        if not hasAnretning and not hasLordag:
            print(dayinfo + "-")
            return

        if hasAnretning:
            people = self.dugnader['anretning'][day]
            for name in people:
                self.printUser(dayinfo, name)

        if hasLordag:
            people = self.dugnader['lordag'][day]
            for name in people:
                self.printUser(dayinfo, name)

    def getDugnads(self):
        with urllib.request.urlopen('https://foreningenbs.no/dugnaden/api.php?method=list') as response:
            data = response.read().decode('utf-8')
        data = json.loads(data)

        list = {}
        for dugnad in data:
            #if dugnad['type'] != 'anretning':
            #    continue

            day = datetime.datetime.strptime(dugnad['date'], '%Y-%m-%d %H:%M:%S').date()

            if dugnad['type'] not in list:
                list[dugnad['type']] = {}


            if day not in list[dugnad['type']]:
                list[dugnad['type']][day] = []

            people = [x['name'] for x in dugnad['people']]
            list[dugnad['type']][day] += people

        return list

if __name__ == '__main__':
    checkToday = False
    checkWeeks = True

    dugnad = DugnadListe()

    if checkToday:
        dugnad.checkDay(datetime.date.today())

    if checkWeeks:
        start_of_week = datetime.date.today()
        start_of_week -= datetime.timedelta(days=start_of_week.weekday())

        d = start_of_week
        for x in range(0, 10):
            print("Uke %d" % d.isocalendar()[1])
            for offset in range(0, 7):
                dugnad.checkDay(d)
                d += datetime.timedelta(days=1)
            #d += datetime.timedelta(days=2)

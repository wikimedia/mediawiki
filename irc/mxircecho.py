#! /usr/bin/env python
#
# usage: mxircecho.py nickname server
import sys
sys.path.append('/home/kate/pylib/lib/python2.2/site-packages')

from ircbot import SingleServerIRCBot
import threading

class EchoReader(threading.Thread):
	def __init__(self, bot):
		threading.Thread.__init__(self)
		self.abot = bot

	def run(self):
		while True:
			try:
				s = raw_input()
				sp = s.split("\t")
				if len(sp) == 2:
					channel = sp[0]
					text = sp[1]

					if channel not in bot.chans:
						bot.chans.append(channel)
						bot.connection.join(channel)


					# this throws an exception if not connected.
					bot.connection.privmsg(channel, text)
			except EOFError:
				# Once the input is finished, the bot should exit
				break
			except:
				pass

class EchoBot(SingleServerIRCBot):
	def __init__(self, chans, nickname, server):
		print "*** Connecting to IRC server %s..." % server
		SingleServerIRCBot.__init__(self, [(server, 6667)], nickname, "IRC echo bot")
		self.chans = chans

	def on_nicknameinuse(self, c, e):
		c.nick(c.get_nickname() + "_")

	def on_welcome(self, c, e):
		print "*** Connected"
		for chan in self.chans:
			c.join(chan)
	
bot = EchoBot([], sys.argv[1], sys.argv[2]);
sthr = EchoReader(bot)
sthr.start()
bot.start()

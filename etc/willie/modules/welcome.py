# coding=utf-8
"""
Welcomes users to chat.
"""

from willie.module import *

@event('JOIN')
@rule(r'.*')
def welcome(bot, trigger):
    new_nick = trigger.nick
    if new_nick != bot.nick:
        bot.say('Welcome, ' + new_nick + '! Please say hi and ask any questions you may have. Responses may take a while. See recent messages at: http://bit.ly/1acWxVE')
